<table align="center" border='1' width="100%">
<?php
    $strJsonFileContents = file_get_contents("screening.json");
    $array = json_decode($strJsonFileContents);
    echo "<tr>";
    echo "<td> Gender </td>";
    echo "<td> TTL </td>";
    echo "<td> Umur </td>";
    echo "<td> Tinggi Badan </td>";
    echo "<td> Berat Badan </td>";
    echo "<td> BMI </td>";
    echo "<td> Aktivitas Fisik </td>";
    echo "<td> Lingkar Pinggang </td>";
    echo "<td> Pernah Mengalami Peningkatan Gula Darah </td>";
    echo "<td> Seberapa sering anda makan sayuran, buah-buahan atau beri? </td>";
    echo "<td> Apakah mengonsumsi obat anti hipertensi secara reguler? </td>";
    echo "<td> Apakah memiliki anggota keluarga atau saudara yang terdiagnosa diabetes? (Diabetes 1 atau Diabetes 2) </td>";
    echo "<td> Realita </td>";
    echo "<td> Skor </td>";
    echo "<td> Resiko </td>";
    echo "</tr>";
    // var_dump($array);
    $year = date("Y");
    foreach($array as $response){

        $score = 0;
        $diabetes_risk = '';

        $gender = $response->{"Jenis Kelamin"};

        $birth_day = explode("/", $response->{"Tanggal Lahir"});
        $birth_year = '19'.$birth_day[2];
        $age = intval($year) - intval($birth_year);
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

        if($response->{"Apakah memiliki anggota keluarga atau saudara yang terdiagnosa diabetes? (Diabetes 1 atau Diabetes 2)"} == "Ya (Orang tua, Kakak, Adik, Anak kandung)"){
            $score += 5;
        }
        elseif($response->{"Apakah memiliki anggota keluarga atau saudara yang terdiagnosa diabetes? (Diabetes 1 atau Diabetes 2)"} == "Ya (Kakek/Nenek, Bibi, Paman, atau sepupu dekat)"){
            $score += 3;
        }

        if($score > 20){
            $diabetes_risk = "Sangat Tinggi";
        }
        elseif($score >= 15 && $score <= 20){
            $diabetes_risk = "Tinggi";
        }
        elseif($score >= 12 && $score <= 14){
            $diabetes_risk = "Sedang";
        }
        elseif($score >= 7 && $score <= 11){
            $diabetes_risk = "Rendah";
        }
        else{
            $diabetes_risk = "Sangat Rendah";
        }

        echo "<tr>";
        echo "<td>".$response->{"Jenis Kelamin"}."</td>";
        echo "<td>".$response->{"Tanggal Lahir"}."</td>";
        echo "<td>".$age."</td>";
        echo "<td>".$response->{"Masukkan tinggi badan (cm)"}."</td>";
        echo "<td>".$response->{"Masukkan berat badan (kg)"}."</td>";
        echo "<td>".$bmi."</td>";
        echo "<td>".$response->{"Apakah anda aktif melakukan aktivitas fisik?"}."</td>";
        echo "<td>".$response->{"Ukuran lingkar pinggang (cm)"}."</td>";
        echo "<td>".$response->{"Apakah anda pernah mengalami peningkatan kadar gula darah (saat hamil, sakit, pemeriksaan gula darah) ?"}."</td>";
        echo "<td>".$response->{"Seberapa sering anda makan sayuran, buah-buahan atau beri?"}."</td>";
        echo "<td>".$response->{"Apakah mengonsumsi obat anti hipertensi secara reguler?"}."</td>";
        echo "<td>".$response->{"Apakah memiliki anggota keluarga atau saudara yang terdiagnosa diabetes? (Diabetes 1 atau Diabetes 2)"}."</td>";
        echo "<td>".$response->{"Hasil yang diharapkan"}."</td>";
        echo "<td>".$score."</td>";
        echo "<td>".$diabetes_risk."</td>";
        echo "</tr>";
    }
?>