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

$names = $_FILES['image']['name'];
$tmp_names = $_FILES['image']['tmp_name'];

$upload_data = array_combine($tmp_names, $names);

foreach ($upload_data as $temp_folder => $file) {
    $pathinfo = pathinfo($file);
    $fileExtension = strtolower($pathinfo['extension']);

    // Check if the file has a valid extension
    if (!in_array($fileExtension, $allowedExtensions)) {
        $invalidUploads[] = $file;
    } else {
        //  Replace any characters not \w- in the original filename
        $base = $pathinfo["filename"];
        $base = preg_replace("/[^\w-]/", "_", $base);
        $filename = $base . "." . $pathinfo["extension"];

        // // Add a numeric suffix if the file already exists
        $i = 1;
        $upload_file_tmp_name = __DIR__ . "/uploads/" . $filename;
        while (file_exists($upload_file_tmp_name)) {
            $filename = $base . "($i)." . $pathinfo["extension"];
            $upload_file_tmp_name = __DIR__ . "/uploads/" . $filename;
            $i++;
        }

        // Track the successful upload
        $successfulUploads[] = $filename;

        // Move the file with edited filename
        move_uploaded_file($temp_folder, $upload_file_tmp_name);
    }
}

// Provide feedback to the user

if (!empty($successfulUploads)) {
    echo 'Successfully uploaded files:<br>';
    
    foreach ($successfulUploads as $uploadedFile) {
        echo $uploadedFile . '<br>';
    }
}

if (!empty($invalidUploads)) {
    echo '<br>Invalid files (only txt and pdf allowed):<br>';
    
    foreach ($invalidUploads as $invalidFile) {
        echo $invalidFile . '<br>';
    }
}

?>

<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Display the convert button
        echo '<form method="post" enctype="multipart/form-data" action="convert5.php">';
        foreach ($_FILES['image']['name'] as $uploadedFile) {
            echo '<input type="hidden" name="uploaded_file[]" value="' . htmlspecialchars($uploadedFile) . '">';
        }
        echo '<br>';
        echo '<button type="submit" name="convert">Convert</button>';
        echo '</form>';
    }
?>
