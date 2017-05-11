<?php

function getURLReplaced($title, $tag){
    //get title post
    $newtitle = str_replace(" ", "_",$title);
    $newtitle = str_replace(PHP_EOL, "", $newtitle);
    //get tag post
    $newtag = str_replace(" ", "_",$tag);
    $newtag = str_replace(PHP_EOL, "", $newtag);

    return $newtag."/".$newtitle;
}

function getStringReplaced($str){
    $str = str_replace(" ", "",$str);
    $str = str_replace(PHP_EOL, "", $str);
    return $str;
}

?>