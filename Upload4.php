<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit('POST request method required');
}

if (empty($_FILES['image']['name'][0])) {
    exit('No file uploaded');
}

$folder = "uploads/";

$allowedExtensions = ['txt', 'pdf'];

$successfulUploads = [];
$invalidUploads = [];

// Process each uploaded file
foreach ($_FILES['image']['name'] as $key => $filename) {
    $tmp_name = $_FILES['image']['tmp_name'][$key];

    $pathinfo = pathinfo($filename);
    $fileExtension = strtolower($pathinfo['extension']);

    // Check if the file has a valid extension
    if (!in_array($fileExtension, $allowedExtensions)) {
        $invalidUploads[] = $filename;
    } else {
        // Replace any characters not \w- in the original filename
        $base = $pathinfo["filename"];
        $base = preg_replace("/[^\w-]/", "_", $base);
        $newFilename = $base . "." . $fileExtension;

        // Add a numeric suffix if the file already exists
        $i = 1;
        $upload_file_tmp_name = __DIR__ . "/uploads/" . $newFilename;
        while (file_exists($upload_file_tmp_name)) {
            $newFilename = $base . "($i)." . $fileExtension;
            $upload_file_tmp_name = __DIR__ . "/uploads/" . $newFilename;
            $i++;
        }

        // Move the file with edited filename
        if (move_uploaded_file($tmp_name, $upload_file_tmp_name)) {
            $successfulUploads[] = $newFilename;
        }
    }
}

// Provide feedback to the user

if (!empty($successfulUploads)) {
    echo 'Successfully uploaded files to server:<br>';
    
    foreach ($successfulUploads as $uploadedFile) {
        echo $uploadedFile . '<br>';
    }

    // Display the convert button
    echo '<form method="post" action="convert6.php">';
    foreach ($successfulUploads as $uploadedFile) {
        echo '<input type="hidden" name="uploaded_files[]" value="' . htmlspecialchars($uploadedFile) . '">';
    }
    echo '<br>';
    echo '<button type="submit" name="convert">Convert</button>';
    echo '</form>';
}

if (!empty($invalidUploads)) {
    echo '<br>Invalid files (only txt and pdf allowed):<br>';
    
    foreach ($invalidUploads as $invalidFile) {
        echo $invalidFile . '<br>';
    }
}
?>
