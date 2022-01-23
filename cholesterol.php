<?php
    function GetCholesterolResult($response)
    {
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

        // if($response->{"Berapakah kadar kolesterol sehat (HDL) anda saat ini (mmol/L)"} == "< 30"){
        //     $hdl = 20;
        // }else if($response->{"Berapakah kadar kolesterol sehat (HDL) anda saat ini (mmol/L)"} == "30 - 50"){
        //     $hdl = 40;
        // }else{
        //     $hdl = 60;
        // }

        // Asumsi hdl pasien sehat dan ras diabaikan
        $hdl = 40;
        $isBlack = False;

        if ($age < 40 || $age > 79){
            return -1;
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
        return round($pct * 100 * 10) / 10;
        // Debug Checklist
        // echo "<table>";
        // echo "<tr>";
        // echo "<td> Gender </td>";
        // echo "<td> TTL </td>";
        // echo "<td> Umur </td>";
        // echo "<td> Tinggi Badan </td>";
        // echo "<td> Berat Badan </td>";
        // echo "<td> BMI </td>";
        // echo "<td> Aktivitas Fisik </td>";
        // echo "<td> Lingkar Pinggang </td>";
        // echo "<td> Pernah Mengalami Peningkatan Gula Darah </td>";
        // echo "<td> Seberapa sering anda makan sayuran, buah-buahan atau beri? </td>";
        // echo "<td> Apakah mengonsumsi obat anti hipertensi secara reguler? </td>";
        // echo "<td> Apakah memiliki anggota keluarga atau saudara yang terdiagnosa diabetes? (Diabetes 1 atau Diabetes 2) </td>";
        // echo "<td> Realita </td>";
        // echo "<td> Skor </td>";
        // echo "<td> Resiko </td>";
        // echo "</tr>";

        // echo "<tr>";
        // echo "<td>".$response->{"Jenis Kelamin"}."</td>";
        // echo "<td>".$response->{"Tanggal Lahir"}."</td>";
        // echo "<td>".$age."</td>";
        // echo "<td>".$response->{"Masukkan tinggi badan (cm)"}."</td>";
        // echo "<td>".$response->{"Masukkan berat badan (kg)"}."</td>";
        // echo "<td>".$bmi."</td>";
        // echo "<td>".$response->{"Apakah anda aktif melakukan aktivitas fisik?"}."</td>";
        // echo "<td>".$response->{"Ukuran lingkar pinggang (cm)"}."</td>";
        // echo "<td>".$response->{"Apakah anda pernah mengalami peningkatan kadar gula darah (saat hamil, sakit, pemeriksaan gula darah) ?"}."</td>";
        // echo "<td>".$response->{"Seberapa sering anda makan sayuran, buah-buahan atau beri?"}."</td>";
        // echo "<td>".$response->{"Apakah mengonsumsi obat anti hipertensi secara reguler?"}."</td>";
        // echo "<td>".$response->{"Apakah memiliki anggota keluarga atau saudara yang terdiagnosa diabetes? (Diabetes 1 atau Diabetes 2)"}."</td>";
        // echo "<td>".$response->{"Hasil yang diharapkan"}."</td>";
        // echo "<td>".$score."</td>";
        // echo "<td>".$diabetes_risk."</td>";
        // echo "</tr>";
    }
?>

