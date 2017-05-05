<?php

//create a file name
$filepath = $_POST["filepath"];
$title = $_POST["title"];
$tag = $_POST["tag"];
$date = $_POST["date"];
$abstract = $_POST["abstract"];
$content = $_POST["content"];

$filecontent = "title : ".$title."".PHP_EOL;
$filecontent .= "tag : ".$tag."".PHP_EOL;
$filecontent .= "date : ".$date."".PHP_EOL;
$filecontent .= "abstract : ".$abstract."".PHP_EOL;
$filecontent .= "content : ".$content."".PHP_EOL;


$myfile = fopen($filepath, "w");
//write in file
fwrite($myfile, $filecontent);
//close file
fclose($myfile);

?>