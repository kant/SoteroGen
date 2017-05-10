<?php

//create a file name
$filepath = $_POST["filepath"];
$title = $_POST["title"];
$tag = $_POST["tag"];
$date = $_POST["date"];
$abstract = $_POST["abstract"];
$content = $_POST["content"];

$filecontent = "title : ".utf8_decode( $title )."".PHP_EOL;
$filecontent .= "tag : ".utf8_decode( $tag )."".PHP_EOL;
$filecontent .= "date : ". utf8_decode( $date )."".PHP_EOL;
$filecontent .= "abstract : ". utf8_decode($abstract )."".PHP_EOL;
$filecontent .= "content : ". utf8_decode( $content )."".PHP_EOL;


$myfile = fopen($filepath, "w");
//write in file
fwrite($myfile, $filecontent);
//close file
fclose($myfile);

echo $content

?>