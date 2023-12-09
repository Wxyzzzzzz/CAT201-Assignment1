<?php
// get converted  file name from url parameter
$converted_file = $_GET['converted_file'];

// get path of converted file name
$location_output= 'outputs/'. $converted_file;

// if successfully get converted file name and path
if (!empty($converted_file) && file_exists($location_output)){
header("Cache-Control: public");
header("content-Description: File Transfer");
header("content-Disposition: attachment; filename={$converted_file}");

//download converted file
readfile($location_output);
}

else {
    echo "This file does not exist.";
}
