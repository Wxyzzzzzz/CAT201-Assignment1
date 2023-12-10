<?php

// Set the directory to list files from
$directory = "outputs/";

// Check if the directory exists
if (is_dir($directory)) {
    $files = scandir($directory);
    $files = array_diff($files, array('.', '..'));

    // Output a simple HTML page with download links
    echo "<html><head><title>Download Files</title></head><body>";
    echo "<h2>Files available for download:</h2>";

    if (empty($files)) {
        echo "No files found.";
    } else {
        echo "<ul>";
        foreach ($files as $file) {
            echo '<li><a href="test.php?file=' . urlencode($file) . '">' . $file . '</a></li>';
        }
        echo "</ul>";
    }

    echo "</body></html>";
} else {
    echo "Invalid directory.";
}

?>
