<?php
include "functionUtil.php";

$filepath = $_POST["filepath"];

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
            //tag is equal title
            if($taglinha[0] == "title" ){
                $arr["title"] =  utf8_encode( str_replace(PHP_EOL, "",$taglinha[1]) ) ;
                //tag is equal tag
            }else if($taglinha[0] == "tag" ){
                $arr["tag"] =  utf8_encode(str_replace(PHP_EOL, "",$taglinha[1])) ;
                //tag is equal date
            }else if($taglinha[0] == "date" ){
                $arr["date"] =  utf8_encode(str_replace(PHP_EOL, "",$taglinha[1]));
                //tag is equal date
            }else if($taglinha[0] == "abstract" ){
                $arr["abstract"] =  utf8_encode(str_replace(PHP_EOL, "",$taglinha[1]));
                //tag is equal content
            }else if( ($taglinha[0] == "content") && (!$contenttrue) ){
                $contenttrue = true;
                $arr["content"] = utf8_encode($taglinha[1]);
            }
        }else if($contenttrue){
            $arr["content"] .=  utf8_encode($taglinha[0]);
        }

}

array_push($jsonReturn, $arr);
echo json_encode($jsonReturn, JSON_PRETTY_PRINT);


?>