<?php

// Function Requirement
require_once('Kebugaran.php');

// Input
$answers = file_get_contents('contoh_input.json');
$decoded_jsonf = json_decode($answers, false);

// Variable Convertion
if (count($decoded_jsonf->data) != 14) {
    echo "Wrong Data Count <br>";
    return 0;
}

$decoded_json = array();

foreach($decoded_jsonf->data as $indicator)
{
    $decoded_json[$indicator->question] = $indicator->answer;
}

$decoded_jsonobj = (object) $decoded_json;

// Variable Handling
// foreach($decoded_jsonobj as $key=>$value)
// {
//     echo "$key $value <br>";
// }
// print($decoded_jsonobj->{"Tanggal Lahir"});


$kebugaran_res = GetKebugaranResult($decoded_jsonobj);

// Debug Result
echo "<br> Tes Output Kebugaran <br>";
if ($kebugaran_res < 13){
    print("Tidak Bugar");
}
else if ($kebugaran_res >= 13 and $kebugaran_res < 26){
    print("Bugar Rendah");
}
else if ($kebugaran_res >= 26 and $kebugaran_res < 39){
    print("Bugar Menengah");
}
else if ($kebugaran_res >= 39){
    print("Bugar");
}

echo "<br>";

// Input ke DB

// Output
$data = array(
    "KebugaranResult" => $kebugaran_res
    );
    
echo json_encode($data);

?>