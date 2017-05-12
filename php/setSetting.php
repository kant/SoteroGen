<?php
include "functionUtil.php";

//use for return a json format
$jsonReturn = array();
$dirsite = $_POST["dirsite"];
$stylesite = $_POST["stylesite"];

$filepath = "../sotero_settings";

$myfile = fopen($filepath, "w") or die("Unable to open file!");
$content = "dirsite : ".$dirsite." \nstylesite : ".$stylesite;

fwrite($myfile, $content);
fclose($myfile);

$arr = array();
$arr["dirsite"] = $dirsite;
$arr["stylesite"] = $stylesite;
array_push($jsonReturn, $arr);

echo json_encode($jsonReturn, JSON_PRETTY_PRINT);


?>