<?php
//include "functionUtil.php";

//session_start();

$filepath = "../sotero_settings";

$myfile = fopen($filepath, "r") or die("Unable to open file!");
$arr = array();

//use for return a json format
$jsonReturn = array();

$contenttrue = false;

//turn file to end
while(!feof($myfile)) {
    //get line
    $linha = fgets($myfile);

    $taglinha = explode(" : ",$linha);
        //if has content
        if(count($taglinha ) > 1){
            //tag is equal dirsite
            if($taglinha[0] == "dirsite" ){
                $taglinha[1]  =  str_replace(" ", "",$taglinha[1]);
                $_SESSION['dirsite'] = preg_replace( "/\r|\n/", "", str_replace( utf8_encode( str_replace(PHP_EOL, "",$taglinha[1]))) ;
				//$arr["dirsite"] =  utf8_encode( str_replace(PHP_EOL, "",$taglinha[1]) ) ;

            //tag is equal currentstyle
            }else if($taglinha[0] == "stylesite" ){
                $taglinha[1]  =  str_replace(" ", "",$taglinha[1]);
                $_SESSION['stylesite'] =  preg_replace( "/\r|\n/", "", str_replace( utf8_encode(str_replace(PHP_EOL, "",$taglinha[1])) ;
                //$arr["stylesite"] =  utf8_encode(str_replace(PHP_EOL, "",$taglinha[1])) ;
            }
        }
}

 

//array_push($jsonReturn, $arr);
//echo json_encode($jsonReturn, JSON_PRETTY_PRINT);


?>