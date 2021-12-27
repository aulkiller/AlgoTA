<?php
    function GetBMIResult($bmi)
    {
        $date1 = $bmi[1]->Answer;
        $date2 = date('d/m/Y');

        $diff = abs(strtotime($date2) - strtotime($date1));

        $years = floor($diff / (365*60*60*24));

        $bmicalc = $bmi[3]->Answer / ($bmi[3]->Answer * $bmi[2]->Answer);
        if ($years > 17) {
            if ($bmicalc <= 25){
                return 1;
            }
            else if ($bmicalc <= 30){
                return 2;
            }
            else{
                return 3;
            }
        }
        else {
            if ($bmicalc <= 25){
                return 1;
            }
            else if ($bmicalc <= 30){
                return 2;
            }
            else{
                return 3;
            }
        }
    }
?>

