<?php

if (isset($_GET["file"])) {
    $file = "outputs/" . basename($_GET["file"]);

    if (file_exists($file)) {
        // Set headers to force download
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($file));
        header('Content-Length: ' . filesize($file));

        // Read the file and output to the browser
        readfile($file);

        // Delete the file in converted folder
        unlink($file);      

        exit;
    } else {
        echo "File not found.";
    }
}

?>