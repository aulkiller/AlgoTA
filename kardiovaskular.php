<?php
    function GetCholesterolResult($response)
    {
        $year = date('Y');
        $birth_day = explode("/", $responses->{"Tanggal Lahir"});
        $birth_year = $birth_day[2];
        $age = intval($year) - intval($birth_year);

        $isMale = False;
        $smoker = True;
        $hypertensive = False;
        $diabetic = False;

        if($responses->{"Jenis Kelamin"} == "Laki-laki"){
            $isMale = True;
        }

        if($responses->{"Apakah anda merokok?"} == "Tidak Merokok"){
            $smoker = False;
        }

        if ($responses->{"Apakah mengonsumsi obat anti hipertensi secara reguler?"} == "Ya"){
            $hypertensive = True;
        }

        if ($responses->{"Apakah anda pernah mengalami peningkatan kadar gula darah (saat hamil, sakit, pemeriksaan gula darah) ?"} == "Ya"){
            $diabetic = True;
        }
        if($responses->{"Apakah memiliki anggota keluarga atau saudara yang terdiagnosa diabetes? (Diabetes 1 atau Diabetes 2)"} == "Ya (Orang tua, Kakak, Adik, Anak kandung)"){
            $diabetic = True;
        }
        elseif($responses->{"Apakah memiliki anggota keluarga atau saudara yang terdiagnosa diabetes? (Diabetes 1 atau Diabetes 2)"} == "Ya (Kakek/Nenek, Bibi, Paman, atau sepupu dekat)"){
            $diabetic = True;
        }
            
        if($responses->{"Masukkan tekanan darah anda saat ini:"} == "< 120/80"){
            $sbp = 110;
        }else if($responses->{"Masukkan tekanan darah anda saat ini:"} == "120 - 139 / 80 - 89"){
            $sbp = 130;
        }else{
            $sbp = 150;
        }

        if($responses->{"Berapa kadar kolesterol anda saat ini? (mmol/L)"} == "< 200"){
            $chol = 190;
        }else if($responses->{"Berapa kadar kolesterol anda saat ini? (mmol/L)"} == "200 - 239"){
            $chol = 220;
        }else{
            $chol = 250;
        }

        if($responses->{"Berapakah kadar kolesterol sehat (HDL) anda saat ini (mmol/L)"} == "> 50"){
            $hdl = 60;
        }else if($responses->{"Berapakah kadar kolesterol sehat (HDL) anda saat ini (mmol/L)"} == "30 - 50"){
            $hdl = 40;
        }else{
            $hdl = 20;
        }

        if ($age < 40 || $age > 79){
            return 0;
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

