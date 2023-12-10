<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Check if files were uploaded
    if (isset($_POST['uploaded_file']) && is_array($_POST['uploaded_file'])) {
        // Iterate through uploaded files
        foreach ($_POST['uploaded_file'] as $filename) {

            $location_output = "outputs/";

            // Specify convert path and output path
            $pathinfo = pathinfo($filename);
            $base = $pathinfo["filename"];
            $converted_file = $base . "_converted." . ($pathinfo["extension"] === "pdf" ? "txt" : "pdf");
            //$output_path = $location_output . $converted_file;
            $output_path = __DIR__ . "/outputs/" . $converted_file;

            // Specify the path to your JAR file
            $jar_file_path = "C:/xampp/htdocs/cat201ver5.jar";

             // Use $temp_folder when constructing the conversion command
             $temp_folder = __DIR__ . "/uploads";  // Replace with the actual path to your temporary directory
             $convert_command = 'java -jar "' . $jar_file_path . '" pdfToTxt "' . $temp_folder . '/' . $filename . '" "' . $output_path . '"';


            // Execute the conversion command
            exec($convert_command, $output, $return_var);

            // Use $temp_folder when moving the file
            $upload_file_tmp_name = __DIR__ . "/uploads/" . $filename;
            move_uploaded_file($temp_folder . '/' . $filename, $upload_file_tmp_name);

            // Check if the conversion was successful
            if ($return_var === 0) {
            echo "Conversion successful for file: $filename<br>";
            } else {
            echo "Conversion failed for file: $filename<br>";
            // Print the output for debugging
            print_r($output);
                                }
                            }
                        }
                    }
