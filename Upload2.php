<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit('POST request method required');
}

if (empty($_FILES)) {
    exit('No file uploaded');
}

if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {

    switch ($_FILES["image"]["error"]) {
        case UPLOAD_ERR_PARTIAL:
            exit('File only partially uploaded');
            break;
        case UPLOAD_ERR_NO_FILE:
            exit('No file was uploaded');
            break;
        case UPLOAD_ERR_EXTENSION:
            exit('File upload stopped by a PHP extension');
            break;
        case UPLOAD_ERR_FORM_SIZE:
            exit('File exceeds MAX_FILE_SIZE in the HTML form');
            break;
        case UPLOAD_ERR_INI_SIZE:
            exit('File exceeds upload_max_filesize in php.ini');
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            exit('Temporary folder not found');
            break;
        case UPLOAD_ERR_CANT_WRITE:
            exit('Failed to write file');
            break;
        default:
            exit('Unknown upload error');
            break;
    }
}

// Reject uploaded file larger than 16MB
if ($_FILES["image"]["size"] > 16000000) {
    exit('File too large (max 16MB)');
}

// Use fileinfo to get the mime type
$finfo = new finfo(FILEINFO_MIME_TYPE);
$mime_type = $finfo->file($_FILES["image"]["tmp_name"]);

$mime_types = ["application/pdf",
                "text/plain",];
        
if ( ! in_array($_FILES["image"]["type"], $mime_types)) {
    exit("Invalid file type");
}

// Replace any characters not \w- in the original filename
$pathinfo = pathinfo($_FILES["image"]["name"]);

$base = $pathinfo["filename"];

$base = preg_replace("/[^\w-]/", "_", $base);

$filename = $base . "." . $pathinfo["extension"];

$upload_file_tmp_name = __DIR__ . "/uploads/" . $filename;

// Add a numeric suffix if the file already exists
$i = 1;

while (file_exists($upload_file_tmp_name)) {

    $filename = $base . "($i)." . $pathinfo["extension"];
    $upload_file_tmp_name = __DIR__ . "/uploads/" . $filename;
    $i++;
}
echo "File uploaded successfully.";

// Continue with the upload process if it's successful
if (move_uploaded_file($_FILES["image"]["tmp_name"], $upload_file_tmp_name)) {
    $location_output = "outputs/";

    // Specify convert path and output path
    $converted_file = $base . "_converted." . ($pathinfo["extension"] === "pdf" ? "txt" : "pdf");
    $output_path = $location_output . $converted_file;
    
    // Specify the path to your JAR file
    $jar_file_path = "C:/xampp/htdocs/cat201ver4.jar";

    // Choose the operation based on file extension
    $operation = ($pathinfo["extension"] === "pdf") ? "pdfToTxt" : "txtToPdf";

    // Build the conversion command
    $convert_command = "java -jar $jar_file_path $operation $upload_file_tmp_name $output_path";

    // Execute the JAR file to perform the conversion
    exec($convert_command, $output, $return_var);
    //exec($convert_command . ' 2>&1', $output, $return_var);

    // Debugging output
    echo "Convert command: $convert_command<br>";
    echo "Output: ";
    print_r($output);
    echo "Return var: $return_var<br>";

     // Check if the conversion was successful
     if ($return_var === 0 && file_exists($output_path)) {
        // Move the converted file to "outputs" folder
        $location_output = __DIR__ . "/outputs/" . $converted_file;
        if (rename($output_path, $location_output)) {
            echo "File uploaded and converted successfully.";
            header("Location: download.php?converted_file=" . $converted_file);
        } else {
            exit("Can't move converted file to outputs folder.");
        }
        } else {
        exit("Conversion failed or converted file does not exist.". implode("\n", $output));
     }
    } else {
    exit("Failed to move uploaded file.");
    }  
?>