<table align="center" border='1' width="100%">
<?php
    $strJsonFileContents = file_get_contents("sampel_fix_fisik.json");
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
        $score = 0;
        $high = 0;
        $medium = 0;
        $low = 0;
        $stroke_risk = '??';

        $gender = $response->{"Jenis Kelamin"};

        $diff = abs(strtotime($year) - strtotime($response->{"Tanggal Lahir"}));
        $age = floor($diff / (365*60*60*24));
        if($age > 64){
            $score += 4;
        }
        elseif($age <= 64 && $age >= 55){
            $score += 3;
        }
        elseif($age <= 55 && $age >= 45){
            $score += 2;
        }

        $bmi = intval($response->{"Masukkan berat badan (kg)"}) / pow(intval($response->{"Masukkan tinggi badan (cm)"})/100,2);
        if($bmi > 25 && $bmi < 30 ){
            $score += 1;
        }
        elseif($bmi > 30){
            $score += 3;
        }


        if($response->{"Apakah anda aktif melakukan aktivitas fisik?"} !== "Ya"){
            $score += 2;
        }

        if($gender == "Laki-laki"){
            if(intval($response->{"Ukuran lingkar pinggang (cm)"}) > 102){
                $score += 4;
            }
            elseif(intval($response->{"Ukuran lingkar pinggang (cm)"}) <= 102 && intval($response->{"Ukuran lingkar pinggang (cm)"}) >= 94){
                $score += 3;
            }
        }
        else{
            if(intval($response->{"Ukuran lingkar pinggang (cm)"}) > 88){
                $score += 4;
            }
            elseif(intval($response->{"Ukuran lingkar pinggang (cm)"}) <= 88 && intval($response->{"Ukuran lingkar pinggang (cm)"}) >= 80){
                $score += 3;
            }
        }

        if($response->{"Apakah anda pernah mengalami peningkatan kadar gula darah (saat hamil, sakit, pemeriksaan gula darah) ?"} == "Ya"){
            $score += 5;
        }

        if($response->{"Seberapa sering anda makan sayuran, buah-buahan atau beri?"} !== "Setiap Hari"){
            $score += 1;
        }

        if($response->{"Apakah mengonsumsi obat anti hipertensi secara reguler?"} == "Ya"){
            $score += 2;
        }

        // if($response->{"Apakah memiliki anggota keluarga atau saudara yang terdiagnosa diabetes? (Diabetes 1 atau Diabetes 2)"} == "Ya (Orang tua, Kakak, Adik, Anak kandung)"){
        //     $score += 5;
        // }
        // elseif($response->{"Apakah memiliki anggota keluarga atau saudara yang terdiagnosa diabetes? (Diabetes 1 atau Diabetes 2)"} == "Ya (Kakek/Nenek, Bibi, Paman, atau sepupu dekat)"){
        //     $score += 3;
        // }

        if($score > 20){
            $diabetes_risk = "Sangat Tinggi";
            // $high++;
        }
        elseif($score >= 15 && $score <= 20){
            $diabetes_risk = "Tinggi";
            // $high++;
        }
        elseif($score >= 12 && $score <= 14){
            $diabetes_risk = "Sedang";
            // $medium++;
        }
        elseif($score >= 7 && $score <= 11){
            $diabetes_risk = "Rendah";
            // $low++;
        }
        else{
            $diabetes_risk = "Sangat Rendah";
            // $low++;
        }

        // START bmi stroke - stroke
        if ($bmi <= 25){
            $low++;
        }
        else if ($bmi <= 30){
            $medium++;
        }
        else{
            $high++;
        }

        if($response->{"Apakah anda aktif melakukan aktivitas fisik?"} == "Ya"){
            $low++;
        } else if($response->{"Apakah anda aktif melakukan aktivitas fisik?"} == "Jarang") {
            $medium++;
        } else {
            $high++;
        }

        if($response->{"Apakah anda merokok?"} == "Tidak Merokok"){
            $low++;
        } else if($response->{"Apakah anda merokok?"} == "Sedang berusaha berhenti merokok") {
            $medium++;
        } else {
            $high++;
        }

        if($response->{"Masukkan tekanan darah anda saat ini:"} == "< 120/80"){
            $low++;
        } else if($response->{"Masukkan tekanan darah anda saat ini:"} == "120 - 139 / 80 - 89") {
            $medium++;
        } else {
            $high++;
        }

        if($response->{"Berapa kadar kolesterol anda saat ini? (mmol/L)"} == "< 200"){
            $low++;
        } else if($response->{"Berapa kadar kolesterol anda saat ini? (mmol/L)"} == "200 - 239") {
            $medium++;
        } else {
            $high++;
        }

        if($response->{"Apakah keluarga memiliki riwayat stroke?"} == "Tidak"){
            $low++;
        } else if($response->{"Apakah keluarga memiliki riwayat stroke?"} == "Tidak diketahui") {
            $medium++;
        } else {
            $high++;
        }

        if($response->{"Apakah anda menderita gangguan irama jantung?"} == "Tidak pernah"){
            $low++;
        } else if($response->{"Apakah anda menderita gangguan irama jantung?"} == "Tidak diketahui") {
            $medium++;
        } else {
            $high++;
        }

        if($response->{"Masukkan kadar gula anda saat ini:"} == "< 120"){
            $low++;
        } else if($response->{"Masukkan kadar gula anda saat ini:"} == "120 - 150") {
            $medium++;
        } else {
            $high++;
        }

        // if($response->{"Apakah anda pernah mengalami peningkatan kadar gula darah (saat hamil, sakit, pemeriksaan gula darah) ?"} == "Tidak"){
        //     $low++;
        // } else {
        //     $high++;
        // }

        $high = $response->{"high_score"};
        $medium = $response->{"medium_score"};
        $low = $response->{"low_score"};

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
        echo "<td>".$low."</td>";
        echo "<td>".$response->{"Hasil yang diharapkan"}."</td>";
        echo "<td>".$stroke_risk."</td>";
        echo "</tr>";
    }
?>