<?php
    function GetStrokeResult($response)
    {
        $high = 0;
        $medium = 0;
        $low = 0;
        $year = date('d/m/Y');
        
        $bmi = intval($response->{"Masukkan berat badan (kg)"}) / pow(intval($response->{"Masukkan tinggi badan (cm)"})/100,2);
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

        // Debug Checklist
        // echo "<table>";
        // echo "<tr>";
        // echo "<td> BMI </td>";
        // echo "<td> Aktivitas Fisik </td>";
        // echo "<td> Merokok </td>";
        // echo "<td> Tekanan Darah </td>";
        // echo "<td> Kadar Kolesterol </td>";
        // echo "<td> Riwayat Stroke </td>";
        // echo "<td> Gangguan Irama Jantung </td>";
        // echo "<td> Kadar Gula </td>";
        // echo "<td> Strokecard Rendah </td>";
        // echo "<td> Strokecard Menengah </td>";
        // echo "<td> Strokecard Tinggi </td>";
        // echo "</tr>";

        // echo "<td>".$bmi."</td>";
        // echo "<td>".$response->{"Apakah anda aktif melakukan aktivitas fisik?"}."</td>";
        // echo "<td>".$response->{"Apakah anda merokok?"}."</td>";
        // echo "<td>".$response->{"Masukkan tekanan darah anda saat ini:"}."</td>";
        // echo "<td>".$response->{"Berapa kadar kolesterol anda saat ini? (mmol/L)"}."</td>";
        // echo "<td>".$response->{"Apakah keluarga memiliki riwayat stroke?"}."</td>";
        // echo "<td>".$response->{"Apakah anda menderita gangguan irama jantung?"}."</td>";
        // echo "<td>".$response->{"Masukkan kadar gula anda saat ini:"}."</td>";
        // echo "<td>".$low."</td>";
        // echo "<td>".$medium."</td>";
        // echo "<td>".$high."</td>";


        if ($high > 2) return 3;
        if ($medium > 3 && $medium < 7) return 2;
        if ($low > 5 && $low < 9) return 1;
    }
?>

