<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['convert'])) {
    // Continue with the conversion process
    $uploadedFiles = $_POST['uploaded_files'];
    $convertedFiles = [];

    foreach ($uploadedFiles as $uploadedFile) {
        $location_output = "outputs/";

        // Specify convert path and output path
        $pathinfo = pathinfo($uploadedFile);
        $base = $pathinfo["filename"];
        $converted_file = $base . "_converted." . ($pathinfo["extension"] === "pdf" ? "txt" : "pdf");
        $output_path = $location_output . $converted_file;

        // Specify the path to your JAR file
        $jar_file_path = "C:/xampp/htdocs/cat201ver4.jar";

        // Choose the operation based on file extension
        $operation = ($pathinfo["extension"] === "pdf") ? "pdfToTxt" : "txtToPdf";

        // Build the conversion command
        $convert_command = "java -jar $jar_file_path $operation " . escapeshellarg(__DIR__ . "/uploads/$uploadedFile") . " " . escapeshellarg($output_path);

        // Execute the JAR file to perform the conversion
        exec($convert_command, $output, $return_var);

        //** */ Debugging output
        //echo "Convert command: $convert_command<br>";
        //echo "Output: ";
        //print_r($output);
        //echo "Return var: $return_var<br>";

        // Check if the conversion was successful
        if ($return_var === 0 && file_exists($output_path)) {
            // Move the converted file to "outputs" folder
            $location_output = __DIR__ . "/outputs/" . $converted_file;
            if (rename($output_path, $location_output)) {
                echo "File '$uploadedFile' uploaded and converted successfully.<br>";
                $convertedFiles[] = $converted_file; // Keep track of converted files
            } else {
                echo "Can't move converted file '$uploadedFile' to outputs folder.<br>";
            }
        } else {
            echo "Conversion failed or converted file '$uploadedFile' does not exist.<br>";
        }

        // Separate the output of each conversion with a line break
        echo "<br>";
    }

    // Redirect to download.php with all converted filenames
    if (!empty($convertedFiles)) {
        $convertedFilesParam = implode(",", $convertedFiles);
        header("Location: download.php?converted_files=$convertedFilesParam");
        exit();
    }
}
?>
