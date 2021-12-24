<?php
    function GetStrokeResult($bmi,$param,$diabetes)
    {
        $high = 0;
        $medium = 0;
        $low = 0;
        foreach($param as $answers)
        {
            if($answers->QuestionNo == 5){
                if($answers->Answer == 1){
                    $low++;
                }
                else if($answers->Answer == 2){
                    $high++;
                }
                else if($answers->Answer == 3){
                    $medium++;
                }
                else{
                    $high++;
                }
            }
            if($answers->QuestionNo == 6){
                if($answers->Answer == 1){
                    $high++;
                }
                else if($answers->Answer == 2){
                    $medium++;
                }
                else if($answers->Answer == 3){
                    $low++;
                }
                else{
                    $high++;
                }
            }
            if($answers->QuestionNo == 9){
                if($answers->Answer == 1){
                    $high++;
                }
                else if($answers->Answer == 2){
                    $medium++;
                }
                else if($answers->Answer == 3){
                    $low++;
                }
                else{
                    $high++;
                }
            }
            if($answers->QuestionNo == 12){
                if($answers->Answer == 1){
                    $high++;
                }
                else if($answers->Answer == 2){
                    $medium++;
                }
                else if($answers->Answer == 3){
                    $low++;
                }
                else{
                    $high++;
                }
            }
            if($answers->QuestionNo == 13){
                if($answers->Answer == 1){
                    $high++;
                }
                else if($answers->Answer == 2){
                    $low++;
                }
                else if($answers->Answer == 3){
                    $medium++;
                }
                else{
                    $high++;
                }
            }
            if($answers->QuestionNo == 14){
                if($answers->Answer == 1){
                    $high++;
                }
                else if($answers->Answer == 2){
                    $medium++;
                }
                else if($answers->Answer == 3){
                    $low++;
                }
                else{
                    $high++;
                }
            }
        }
        if($diabetes == 1){
            $low++;
        }
        else if($diabetes == 2){
            $medium++;
        }
        else if($diabetes == 3){
            $high++;
        }
        else{
            $high++;
        }

        if ($high > 2) return 3;
        if ($medium > 3 && $medium < 7) return 2;
        if ($low > 5 && $low < 9) return 1;
    }
?>

