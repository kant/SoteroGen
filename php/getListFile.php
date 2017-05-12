<?php

$dir = "../gerator/posts";

$arrFiles = array();
//use for return a json format
$jsonReturn = array();
$files = array();

//if directory exists
if (file_exists($dir)){

    //open directory
    if ($handle = opendir($dir)) {
        //while has file
        while (false !== ($file = readdir($handle))) {
            //exception

            if ($file != "." && $file != "..") {

                //explode for know extension of file
                $nameFile = explode(".",$file);
                //encode content
                $file = utf8_encode($file);
                //if file has extension and is txt file
                if((count($nameFile) > 1) && ($nameFile[1] == "txt" )){
                    $files[ filemtime($dir."/".$file) ] = $file;
                }
            }
        }
        //close dir
        closedir($handle);
    }

    //sort in growing order
    //if(count($files) > 0){

        krsort($files);

        foreach($files as $file) {

            $lastModified = date('F d Y, H:i:s', filemtime($dir."/".$file)  );
            $filename = $dir."/".$file;
            array_push($arrFiles, $filename);

            $myfile = fopen($filename, "r") or die("Unable to open file!");
            $arr = array();
            $arr["filename"] = $filename;

            //turn file to end
            while(!feof($myfile)) {
                //get line
                $linha = fgets($myfile);
                $taglinha = explode(" : ",$linha);

                while($taglinha[0] == "title"){
                    $str = str_replace(" ", "",$taglinha[1]);
                    $str = str_replace(PHP_EOL, "", $str);

                    $arr["title"]= $str;
                    array_push($jsonReturn, $arr);
                    break;
                }
            }
        }

        echo json_encode($jsonReturn, JSON_PRETTY_PRINT);

    //}
}

?>