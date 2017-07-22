<?php
include "functionUtil.php";
session_start();

//use for return a json format
$jsonReturn = array();
$namesite = $_POST["namesite"];
$dirsite = $_POST["dirsite"];
$stylesite = $_POST["stylesite"];

$filepath = "../sotero_settings";

$myfile = fopen($filepath, "w") or die("Unable to open file!");
$content = "namesite : ".$namesite."\ndirsite : ".$dirsite." \nstylesite : ".$stylesite;

fwrite($myfile, $content);
fclose($myfile);

$arr = array();
$arr["dirsite"] = $dirsite;
$arr["stylesite"] = $stylesite;
$arr["namesite"] = $namesite;


array_push($jsonReturn, $arr);
echo json_encode($jsonReturn, JSON_PRETTY_PRINT);


?>