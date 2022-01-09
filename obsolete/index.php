<?php
require_once('stroke.php');
require_once('bmi.php');

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
$bmi_res = GetBMIResult($bmi);
$stroke_res = GetStrokeResult($bmi_res,$stroke,1);
echo "Pasien Sampel 1<br>";
if($stroke_res == 1){
    print("Stroke Resiko Rendah");
}
else if($stroke_res == 2){
    print("Waspada Struk");
}
else if($stroke_res == 3){
    print("Stroke Resiko Tinggi");
}
?>