<?php

//Stores the filename as it was on the client computer.
$imagename = $_FILES['inp-image']['name'];
//Stores the filetype e.g image/jpeg
$imagetype = $_FILES['inp-image']['type'];
//Stores any error codes from the upload.
$imageerror = $_FILES['inp-image']['error'];
//Stores the tempname as it is given by the host when uploaded.
$imagetemp = $_FILES['inp-image']['tmp_name'];

//The path you wish to upload the image to
session_start();
$imagePath = $_SESSION['dirsite']."/images/";

if(!is_dir($imagePath)){
    echo "no-dir";
}else{
    if(is_uploaded_file($imagetemp)) {
        if(move_uploaded_file($imagetemp, $imagePath . $_POST["name-image"]."_".$imagename  )) {
            echo  $_POST["name-image"]."_".$imagename;
        }
        else {
            echo "Failed to move your image.";
        }
    }
    else {
        echo "Failed to upload your image.";
    }
}

?>