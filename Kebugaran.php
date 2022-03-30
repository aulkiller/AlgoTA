<?php
    function GetKebugaranResult($response)
    {
        $qs1 = $response->{"Saya merasa lelah secara fisik"};
        $qs2 = $response->{"Saya merasa lemah di semua hal"};
        $qs3 = $response->{"Saya merasa lesu"};
        $qs4 = $response->{"Saya merasa lelah secara fisik dan psikis"};
        $qs5 = $response->{"Saya merasa kesulitan memulai sesuatu karena saya lelah"};
        $qs6 = $response->{"Saya merasa kesulitan menyelesaikan sesuatu karena saya lelah"};
        $qs7 = $response->{"Saya memiliki energi"};
        $qs8 = $response->{"Saya bisa mengerjakan aktivitas seperti biasa"};
        $qs9 = $response->{"Saya butuh tidur seharian"};
        $qs10 = $response->{"Saya merasa terlalu lelah untuk makan"};
        $qs11 = $response->{"Saya membutuhkan bantuan untuk mengerjakan aktivitas seperti biasa"};
        $qs12 = $response->{"Saya merasa frustrasi menjadi terlalu lelah untuk melakukan sesuatu yang saya ingin kerjakan"};
        $qs13 = $response->{"Saya harus membatasi aktivitas sosial saya karena saya lelah"};

        // Asumsi pasien menjawab semua pertanyaan

        $ans = (4 - $qs1) + (4 - $qs2) 
        + (4 - $qs3) + (4 - $qs4) + (4 - $qs5) 
        + (4 - $qs6) + (0 + $qs7) + (0 + $qs8) 
        + (4 - $qs9) + (4 - $qs10) + (4 - $qs11) 
        + (4 - $qs12) + (4 - $qs13);

        return $ans;
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

