<?php
require_once('stroke.php');

$answers = file_get_contents('contoh.json');

$decoded_jsont = json_decode($answers, true);
$decoded_jsonf = json_decode($answers, false);

// var_dump($decoded_jsonf);
$diabetes = array();
$kolesterol = array();
$stroke = array();
$bmi = array();
foreach($decoded_jsonf as $answers)
{
    if($answers->QuestionNo < 5){
        array_push($bmi,$answers);
    }
    if($answers->Diabetes == true)
    {
        array_push($diabetes,$answers);
    }
    if($answers->Kolesterol)
    {
        array_push($kolesterol, $answers);
    }
    if($answers->Stroke)
    {
        array_push($stroke, $answers);
    }
}
var_dump(GetStrokeResult($stroke,$stroke));
?>