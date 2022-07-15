<table align="center" border='1' width="100%">
<?php
    $strJsonFileContents = file_get_contents("screening_2.json");
    $array = json_decode($strJsonFileContents);
    echo "<tr>";
    echo "<td> No. </td>";
    echo "<td> Gender </td>";
    // echo "<td> TTL </td>";
    echo "<td> Umur </td>";
    // echo "<td> Tinggi Badan </td>";
    // echo "<td> Berat Badan </td>";
    echo "<td> BMI </td>";
    // echo "<td> Skor Diabetes </td>";
    echo "<td> Aktivitas Fisik </td>";
    echo "<td> Merokok </td>";
    echo "<td> Tekanan Darah </td>";
    echo "<td> Kadar Kolesterol </td>";
    echo "<td> Riwayat Stroke </td>";
    echo "<td> Gangguan Irama Jantung </td>";
    echo "<td> Kadar Gula </td>";
    echo "<td> Strokecard Tinggi </td>";
    echo "<td> Strokecard Menengah </td>";
    echo "<td> Strokecard Rendah </td>";
    echo "<td> Realita </td>";
    echo "<td> Resiko Stroke</td>";
    echo "</tr>";
    // var_dump($array);
    $year = date('m/d/Y');
    $i=0;
    foreach($array as $response){
        $i++;
        $year = date('Y');
        $birth_day = explode("/", $response->{"Tanggal Lahir"});
        $birth_year = $birth_day[2];
        $age = intval($year) - intval($birth_year);

        $isMale = False;
        $smoker = True;
        $hypertensive = False;
        $diabetic = False;

        if($response->{"Jenis Kelamin"} == "Laki-laki"){
            $isMale = True;
        }

        if($response->{"Apakah anda merokok?"} == "Tidak Merokok"){
            $smoker = False;
        }

        if ($response->{"Apakah mengonsumsi obat anti hipertensi secara reguler?"} == "Ya"){
            $hypertensive = True;
        }

        if ($response->{"Apakah anda pernah mengalami peningkatan kadar gula darah (saat hamil, sakit, pemeriksaan gula darah) ?"} == "Ya"){
            $diabetic = True;
        }
        if($response->{"Apakah memiliki anggota keluarga atau saudara yang terdiagnosa diabetes? (Diabetes 1 atau Diabetes 2)"} == "Ya (Orang tua, Kakak, Adik, Anak kandung)"){
            $diabetic = True;
        }
        elseif($response->{"Apakah memiliki anggota keluarga atau saudara yang terdiagnosa diabetes? (Diabetes 1 atau Diabetes 2)"} == "Ya (Kakek/Nenek, Bibi, Paman, atau sepupu dekat)"){
            $diabetic = True;
        }
            
        if($response->{"Masukkan tekanan darah anda saat ini:"} == "< 120/80"){
            $sbp = 110;
        }else if($response->{"Masukkan tekanan darah anda saat ini:"} == "120 - 139 / 80 - 89"){
            $sbp = 130;
        }else{
            $sbp = 150;
        }

        if($response->{"Berapa kadar kolesterol anda saat ini? (mmol/L)"} == "< 200"){
            $chol = 190;
        }else if($response->{"Berapa kadar kolesterol anda saat ini? (mmol/L)"} == "200 - 239"){
            $chol = 220;
        }else{
            $chol = 250;
        }

        if($response->{"Berapakah kadar kolesterol sehat (HDL) anda saat ini (mmol/L)"} == "> 50"){
            $hdl = 60;
        }else if($response->{"Berapakah kadar kolesterol sehat (HDL) anda saat ini (mmol/L)"} == "30 - 50"){
            $hdl = 40;
        }else{
            $hdl = 20;
        }

        if ($age < 40 || $age > 79){
            $pctfix = 0;
            goto skip;
        }
        $lnAge = log($age);
        $lnTotalChol = log($chol);
        $lnHdl = log($hdl);
        if($hypertensive == True){
            $trlnsbp = $log($sbp);
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

        if (!$isMale){
            $s010Ret = 0.96652;
            $mnxbRet = -29.18;
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
        else{
            $s010Ret = 0.91436;
            $mnxbRet = 61.18;
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
        $pctfix = round($pct * 100 * 10) / 10;

        // if($response->{"Apakah anda pernah mengalami peningkatan kadar gula darah (saat hamil, sakit, pemeriksaan gula darah) ?"} == "Tidak"){
        //     $low++;
        // } else {
        //     $high++;
        // }

        // $high = $response->{"high_score"};
        // $medium = $response->{"medium_score"};
        // $low = $response->{"low_score"};

        // if ($high >= 3) {
        //     $stroke_risk = "Risiko Tinggi";
        // } else {
        //     if ($high == 2){
        //         if ($medium >= 3) {
        //             $stroke_risk = "Risiko Tinggi";
        //         } else if ($medium >= 2) {
        //             $stroke_risk = "Risiko Menengah";
        //         } else {
        //             $stroke_risk = "Risiko Rendah";
        //         }
        //     }
        //     else if ($high == 1){
        //         if ($medium >= 5) {
        //             $stroke_risk = "Risiko Tinggi";
        //         } else if ($medium >= 3) {
        //             $stroke_risk = "Risiko Menengah";
        //         } else {
        //             $stroke_risk = "Risiko Rendah";
        //         }
        //     }
        //     else if ($medium >= 4) {
        //         $stroke_risk = "Risiko Menengah";
        //     } else {
        //         if ($medium == 3){
        //             if ($low >= 3) {
        //                 $stroke_risk = "Risiko Menengah";
        //             } else {
        //                 $stroke_risk = "Risiko Rendah";
        //             }
        //         }
        //         else if ($medium == 2){
        //             if ($low >= 5) {
        //                 $stroke_risk = "Risiko Menengah";
        //             } else {
        //                 $stroke_risk = "Risiko Rendah";
        //             }
        //         }
        //         else if ($low >= 6){
        //             $stroke_risk = "Risiko Rendah";
        //         }
        //     }
        // }

        // New Algo
		if ($high >= 3) {
            $stroke_risk = "Risiko Tinggi";
        } else {
            if ($high == 2){
                if ($medium >= 2) {
                    $stroke_risk = "Risiko Menengah";
                } else {
                    $stroke_risk = "Risiko Rendah";
                }
            }
            else if ($high == 1){
                if ($medium >= 3) {
                    $stroke_risk = "Risiko Menengah";
                } else {
                    $stroke_risk = "Risiko Rendah";
                }
            }
            else if ($medium >= 4) {
                $stroke_risk = "Risiko Menengah";
            } else {
                $stroke_risk = "Risiko Rendah";
            }
        }


skip:

        // END bmi stroke - stroke

        echo "<tr>";
        echo "<td>".$i."</td>";
        echo "<td>".$response->{"Jenis Kelamin"}."</td>";
        // echo "<td>".$response->{"Tanggal Lahir"}."</td>";
        echo "<td>".$age."</td>";
        // echo "<td>".$response->{"Masukkan tinggi badan (cm)"}."</td>";
        // echo "<td>".$response->{"Masukkan berat badan (kg)"}."</td>";
        echo "<td>".$bmi."</td>";
        // echo "<td>".$score."</td>";
        echo "<td>".$response->{"Apakah anda aktif melakukan aktivitas fisik?"}."</td>";
        echo "<td>".$response->{"Apakah anda merokok?"}."</td>";
        echo "<td>".$response->{"Masukkan tekanan darah anda saat ini:"}."</td>";
        echo "<td>".$response->{"Berapa kadar kolesterol anda saat ini? (mmol/L)"}."</td>";
        echo "<td>".$response->{"Apakah keluarga memiliki riwayat stroke?"}."</td>";
        echo "<td>".$response->{"Apakah anda menderita gangguan irama jantung?"}."</td>";
        echo "<td>".$response->{"Masukkan kadar gula anda saat ini:"}."</td>";
        echo "<td>".$high."</td>";
        echo "<td>".$medium."</td>";
        echo "<td>".$pctfix."</td>";
        echo "<td>".$response->{"Hasil yang diharapkan"}."</td>";
        echo "<td>".$stroke_risk."</td>";
        echo "</tr>";
    }
?>