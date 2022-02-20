<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\DetailStrokeModel;
use App\Models\DetailDiabetesModel;
use App\Models\DetailKolesterolModel;

class Pemeriksaan extends ResourceController
{
    protected $modelName = 'App\Models\PemeriksaanModel';
    protected $format = 'json';

    public function __construct()
        {
            $this->DiabetesModel    = new DetailDiabetesModel();
            $this->StrokeModel      = new DetailStrokeModel();
            $this->KolesterolModel  = new DetailKolesterolModel();
        }

    public function index()
    {
        return $this->respond($this->model->findAll());
    }

    public function show($slug = null, $id = null)
    {
        if($slug == 'user'){
            $data = $this->model->get_user_by_id_latest($id);
            return $this->respond($data);
        }

        if($slug == 'userAll'){
            $data = $this->model->get_user_by_id_all($id);
            return $this->respond($data);
        }

        $record = $this->model->find($id);
        if (!$record) {
            # code...
            return $this->failNotFound(sprintf(
                'post with id %d not found',
                $id
            ));
        }

        return $this->respond($record);
    }

    public function create()
    {
        $data = $this->request->getJSON();
		
		/*Contoh Input API dari Android	[{"answer":"Laki-laki","question":"jenis_kelamin"},{"answer":"11/1/2022","question":"tanggal_lahir"},{"answer":"3432","question":"tinggi_badan"},{"answer":"34324","question":"berat_badan"},{"answer":"2","question":"aktivitas_fisik"},{"answer":"2","question":"merokok"},{"answer":"324234","question":"lingkar_pinggang"},{"answer":"1","question":"histori_hipertensi"},{"answer":"2","question":"tekanan_darah"},{"answer":"1","question":"gula_darah"},{"answer":"3","question":"kadar_gula"},{"answer":"3","question":"kadar_kolesterol"},{"answer":"3","question":"riwayat_stroke"},{"answer":"3","question":"irama_jantung"},{"answer":"2","question":"buah_sayur"},{"answer":"2","question":"obat_hipertensi"},{"answer":"2","question":"keturunan"},{"answer":"4","question":"kolesterol_hdl"},{"answer":"69","question":"id_user"}]
		*/

        // Update Following Input API yang ada di repo AlgoTA
        $decoded_json = array();

        foreach($data as $indicator)
        {
            $decoded_json[$indicator->question] = $indicator->answer;
        }

        $data = (object) $decoded_json;
        // Update Ends Here


        $diabetes   = $this->get_diabetes($data);
        $stroke     = $this->get_stroke($data);
        $kolesterol = $this->get_kolesterol($data);

        $data_pemeriksaan = array(
            'id_user' => $data->id_user,
            'hasil_diabetes' => $diabetes['hasil'],
            'hasil_kolesterol' => $kolesterol['hasil'],
            'hasil_stroke' => $stroke['hasil']
        );

        if (!$this->model->save($data_pemeriksaan)) {
            # code...
            return $this->fail($this->model->errors());
        }

        $data->id_pemeriksaan = $this->model->insertID();

        $data->low_score = $stroke['low'];
        $data->medium_score = $stroke['medium'];
        $data->high_score =$stroke['high'];

        $data->score_diabetes = $diabetes['score'];
        $data->bmi = intval($data->berat_badan) / pow(intval($data->tinggi_badan)/100,2);

        $this->DiabetesModel->save($data);
        $this->StrokeModel->save($data);
        $this->KolesterolModel->save($data);

        return $this->respondCreated($this->model->find($this->model->insertID()), 'Pemeriksaan created');
    }

    // Update All Indicator
    public function get_diabetes($data)
    {
        $year = date('Y');
        $score = 0;
        $diabetes_risk = "";

        $gender = $data->jenis_kelamin;

        $birth_day = explode("/", $data->tanggal_lahir);
        $birth_year = $birth_day[2];
        $age = intval($year) - intval($birth_year);

        if($age > 64){
            $score += 4;
        }
        elseif($age >= 55){
            $score += 3;
        }
        elseif($age >= 45){
            $score += 2;
        }

        $bmi = intval($data->berat_badan) / pow(intval($data->tinggi_badan)/100,2);
        if($bmi > 30){
            $score += 3;
        }
        elseif($bmi >=25 ){
            $score += 1;
        }

        if($data->aktivitas_fisik !== 1){
            $score += 2;
        }

        if($gender == "Laki-laki"){
            if(intval($data->lingkar_pinggang) > 102){
                $score += 4;
            }
            elseif(intval($data->lingkar_pinggang) >= 94){
                $score += 3;
            }
        }
        else{
            if(intval($data->lingkar_pinggang) > 88){
                $score += 4;
            }
            elseif(intval($data->lingkar_pinggang) >= 80){
                $score += 3;
            }
        }

        if($data->gula_darah == 1){
            $score += 5;
        }

        if($data->buah_sayur == 2){
            $score += 1;
        }

        if($data->obat_hipertensi == 2){
            $score += 2;
        }

        if($data->keturunan == 3){
            $score += 5;
        }
        elseif($data->keturunan == 2){
            $score += 3;
        }

        if($score > 20){
            $diabetes_risk = "Risiko Sangat Tinggi";
        }
        elseif($score >= 15){
            $diabetes_risk = "Risiko Tinggi";
        }
        elseif($score >= 12){
            $diabetes_risk = "Risiko Sedang";
        }
        elseif($score >= 7){
            $diabetes_risk = "Risiko Rendah";
        }
        else{
            $diabetes_risk = "Risiko Sangat Rendah";
        }

        return array(
            'hasil' => $diabetes_risk,
            'score' => $score
        );
    }

    // Update All Indicator
    public function get_stroke($data)
    {
        $high = 0;
        $medium = 0;
        $low = 0;

        $bmi = intval($data->berat_badan) / pow(intval($data->tinggi_badan)/100,2);
        if ($bmi <= 25){
            $low++;
        }
        else if ($bmi <= 30){
            $medium++;
        }
        else{
            $high++;
        }

        if($data->aktivitas_fisik == 1){
            $low++;
        } else if($data->aktivitas_fisik == 2) {
            $medium++;
        } else {
            $high++;
        }

        if($data->merokok == 3){
            $low++;
        } else if($data->merokok == 2) {
            $medium++;
        } else {
            $high++;
        }

        if($data->tekanan_darah == 3){
            $low++;
        } else if($data->tekanan_darah == 2) {
            $medium++;
        } else {
            $high++;
        }

        if($data->kadar_kolesterol == 3){
            $low++;
        } else if($data->kadar_kolesterol  == 2) {
            $medium++;
        } else {
            $high++;
        }

        if($data->riwayat_stroke  == 2){
            $low++;
        } else if($data->riwayat_stroke  == 3) {
            $medium++;
        } else {
            $high++;
        }

        if($data->irama_jantung == 3){
            $low++;
        } else if($data->irama_jantung  == 2) {
            $medium++;
        } else {
            $high++;
        }

        if($data->kadar_gula == 1){
            $low++;
        } else if($data->kadar_gula == 2) {
            $medium++;
        } else {
            $high++;
        }

        $hasil = "";
        //Use Complex Nested IF for Now
        if ($high >= 3) {
            $hasil = "Risiko Tinggi";
        } else {
            if ($high == 2){
                if ($medium >= 3) {
                    $hasil = "Risiko Tinggi";
                } else if ($medium >= 2) {
                    $hasil = "Risiko Menengah";
                } else {
                    $hasil = "Risiko Rendah";
                }
            }
            else if ($high == 1){
                if ($medium >= 5) {
                    $hasil = "Risiko Tinggi";
                } else if ($medium >= 3) {
                    $hasil = "Risiko Menengah";
                } else {
                    $hasil = "Risiko Rendah";
                }
            }
            else if ($medium >= 4) {
                $hasil = "Risiko Menengah";
            } else {
                if ($medium == 3){
                    if ($low >= 3) {
                        $hasil = "Risiko Menengah";
                    } else {
                        $hasil = "Risiko Rendah";
                    }
                }
                else if ($medium == 2){
                    if ($low >= 5) {
                        $hasil = "Risiko Menengah";
                    } else {
                        $hasil = "Risiko Rendah";
                    }
                }
                else if ($low >= 6){
                    $hasil = "Risiko Rendah";
                }
            }
        }

        return array(
            'high' => $high,
            'medium' => $medium,
            'low' => $low,
            'hasil' => $hasil
        );
    }

    public function get_kolesterol($data)
    {
        $year = date('Y');
        $birth_day = explode("/", $data->tanggal_lahir);
        $birth_year = $birth_day[2];
        $age = intval($year) - intval($birth_year);

        $isMale = False;
        $smoker = True;
        $hypertensive = False;
        $diabetic = False;

        if($data->jenis_kelamin == "Laki-laki"){
            $isMale = True;
        }

        if($data->merokok == 3){
            $smoker = False;
        }

        if ($data->obat_hipertensi == 2){
            $hypertensive = True;
        }

        if ($data->gula_darah == 1){
           $diabetic = True;
        }

        if($data->tekanan_darah == 3){
            $sbp = 110;
        }else if($data->tekanan_darah == 2){
            $sbp = 130;
        }else{
            $sbp = 150;
        }

        if($data->kadar_kolesterol == 3){
            $chol = 190;
        }else if($data->kadar_kolesterol == 2){
            $chol = 220;
        }else{
            $chol = 250;
        }

        if($data->{"Berapakah kadar kolesterol sehat (HDL) anda saat ini (mmol/L)"} == "< 30"){
            $hdl = 20;
        }else if($data->{"Berapakah kadar kolesterol sehat (HDL) anda saat ini (mmol/L)"} == "30 - 50"){
            $hdl = 40;
        }else{
            $hdl = 60;
        }

        $hdl = 40;
        $isBlack = False;

        if ($age < 40 || $age > 79){
            return array(
                'score' => -1,
                'hasil' => "Tidak Berisiko"
            );
        }
        $lnAge = log($age);
        $lnTotalChol = log($chol);
        $lnHdl = log($hdl);
        if($hypertensive == True){
            $trlnsbp = log($sbp);
        }else{
            $trlnsbp = 0;
        }
        if($hypertensive == True){
            $ntlnsbp = 0;
        }else{
            $ntlnsbp = log($sbp);
        }
        $ageTotalChol = $lnAge * $lnTotalChol;
        $ageHdl = $lnAge * $lnHdl;
        $agetSbp = $lnAge * $trlnsbp;
        $agentSbp = $lnAge * $ntlnsbp;
        if($smoker == True){
            $ageSmoke = $lnAge;
        }else{
            $ageSmoke = 0;
        }
        if ($isBlack && !$isMale){
            $s010Ret = 0.95334;
            $mnxbRet = 86.6081;
            $predictRet = (
                17.1141 * $lnAge
                + 0.9396 * $lnTotalChol
                + -18.9196 * $lnHdl
                + 4.4748 * $ageHdl
                + 29.2907 * $trlnsbp
                + -6.4321 * $agetSbp
                + 27.8197 * $ntlnsbp
                + -6.0873 * $agentSbp
            );
            if($smoker == True){
                $predictRet += 0.6908;
            }
            if($diabetic == True){
                $predictRet += 0.8738;
            }
        }
        else if (!$isBlack && !$isMale){
            $s010Ret = 0.96652;
            $mnxbRet = -29.1817;
            $predictRet = (
                -29.799 * $lnAge
                + 4.884 * $lnAge ** 2
                + 13.54 * $lnTotalChol
                + -3.114 * $ageTotalChol
                + -13.578 * $lnHdl
                + 3.149 * $ageHdl
                + 2.019 * $trlnsbp
                + 1.957 * $ntlnsbp
                + -1.665 * $ageSmoke
            );
            if($smoker == True){
                $predictRet += 7.574;
            }
            if($diabetic == True){
                $predictRet += 0.661;
            }
        }
        else if ($isBlack && $isMale){
            $s010Ret = 0.89536;
            $mnxbRet = 19.5425;
            $predictRet = (
                2.469 * $lnAge
                + 0.302 * $lnTotalChol
                + -0.307 * $lnHdl
                + 1.916 * $trlnsbp
                + 1.809 * $ntlnsbp
            );
            if($smoker == True){
                $predictRet += 0.549;
            }
            if($diabetic == True){
                $predictRet += 0.645;
            }
        }
        else{
            $s010Ret = 0.91436;
            $mnxbRet = 61.1816;
            $predictRet = (
                12.344 * $lnAge
                + 11.853 * $lnTotalChol
                + -2.664 * $ageTotalChol
                + -7.99 * $lnHdl
                + 1.769 * $ageHdl
                + 1.797 * $trlnsbp
                + 1.764 * $ntlnsbp
                + -1.795 * $ageSmoke
            );
            if($smoker == True){
                $predictRet += 7.837;
            }
            if($diabetic == True){
                $predictRet += 0.658;
            }
        }

        $pct = 1 - $s010Ret ** exp($predictRet - $mnxbRet);
        $kolesterol_res = round($pct * 100 * 10) / 10;
        $hasil = "";

        if ($kolesterol_res < 5){
            $hasil = "Tidak Berisiko";
        }
        elseif ($kolesterol_res >= 5 and $kolesterol_res < 7.4){
            $hasil = "Risiko Rendah";
        }
        elseif ($kolesterol_res >= 7.5 and $kolesterol_res < 19.9){
            $hasil = "Risiko Menengah";
        }
        elseif ($kolesterol_res >= 20){
            $hasil = "Risiko Tinggi";
        }

        return array(
            'score' => $kolesterol_res,
            'hasil' => $hasil
        );
    }
}
