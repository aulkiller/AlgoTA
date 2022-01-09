<?php

// Function Requirement
require_once('stroke.php');
require_once('diabetes.php');

// Input
$answers = file_get_contents('contoh_input.json');
$decoded_jsonf = json_decode($answers, false);

// Variable Handling
// foreach($decoded_jsonf as $key=>$value)
// {
//     echo "$key $value <br>";
// }
// print($decoded_jsonf->{"Tanggal Lahir"});
$stroke_res = GetStrokeResult($decoded_jsonf);
$diabetes_res = GetDiabeteseResult($decoded_jsonf);
// Dummy
// Call Python Koles here
$kolesterol_res = 0.55;

// Debug Result
echo "Tes Output Stroke <br>";
if($stroke_res == 1){
    print("Stroke Resiko Rendah");
}
else if($stroke_res == 2){
    print("Waspada Struk");
}
else if($stroke_res == 3){
    print("Stroke Resiko Tinggi");
}
echo "<br> Tes Output DM <br>";

if($diabetes_res > 20){
    print("DM Sangat Tinggi");
}
elseif($diabetes_res >= 15){
    print("DM Tinggi");
}
elseif($diabetes_res >= 12){
    print("DM Sedang");
}
elseif($diabetes_res >= 7){
    print("DM Rendah");
}
else{
    print("DM Sangat Rendah");
}
echo "<br>";

// Input ke DB

// Output
$data = array(
    "StrokeResult" => $stroke_res,
    "DiabetesResult" => $diabetes_res,
    "KolesterolResult" => $kolesterol_res
    );
    
echo json_encode($data);

?>