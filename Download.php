<?php
// get converted file names from URL parameter
$convertedFiles = explode(",", $_GET['converted_files']);

// check if at least one converted file is provided
if (!empty($convertedFiles)) {
    foreach ($convertedFiles as $converted_file) {
        // get path of converted file name
        $location_output = 'outputs/' . $converted_file;

        // if successfully get converted file name and path
        if (!empty($converted_file) && file_exists($location_output)) {
            header("Cache-Control: public");
            header("content-Description: File Transfer");
            header("content-Disposition: attachment; filename=$converted_file");

            // download converted file
            readfile($location_output);
        } else {
            echo "One or more files do not exist.";
            break; // exit the loop if any file is missing
        }
    }
} else {
    echo "No valid converted files specified or files do not exist.";
}
?>
