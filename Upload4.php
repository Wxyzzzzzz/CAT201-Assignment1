<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit('POST request method required');
}

if (empty($_FILES['image']['name'][0])) {
    exit('No file uploaded');
}

$folder = "uploads/";
$outputFolder = 'output/';

$allowedExtensions = ['txt', 'pdf'];

$successfulUploads = [];
$invalidUploads = [];

// Process each uploaded file

// (A) SOME FLAGS
$total = isset($_FILES["image"]) ? count($_FILES["image"]["name"]) : 0 ;
$status = [];
 
$outputThing ="";
// (B) PROCESS FILE UPLOAD
if ($total>0) { 
    for ($i=0; $i<$total; $i++) {
        $source = $_FILES["image"]["tmp_name"][$i];
        $destination = $_FILES["image"]["name"][$i];
        $option = '';

        if(strpos($destination, '.pdf') > 0){
            $option = "pdfToTxt";
        }
        else{    
            $option = "txtToPdf";
        }

        if (move_uploaded_file($source, 'uploads/'.$destination) === false) {
            $status[] = "Error uploading to $destination";
        } 
        $conversionCommand = 'java -jar "cat201 ver5.jar" ' . $option . ' "' . $folder . $destination . '" ' . $outputFolder;
        $useless;
        $output_var;
        $useless = exec($conversionCommand);
        // var_dump($destination);
        $o = str_replace("Text conversion successful. Text saved to: ", '', $useless);
        //   var_dump($useless);
        $outputThing .= $destination . ' <a class="d-block w-block btn btn-sm btn-success" href="' . $o. '" download>Download</a>';
        //   echo '<a href="' . $outputFolder .  substr($source, 0, strpos($source, $option === 'txtToPdf' ? '.txt' : '.pdf')) . $option === 'txtToPdf' ? '.pdf' : '.txt' . '"> ' . $source . '</a>';
        //   echo $conversionCommand;


    }
} else { $status[] = "No files uploaded!"; }
 
/* (C) DONE - WHAT'S NEXT?
if (count($status)==0) {
  // REDIRECT TO OK PAGE?
  header("Location: http://site.com/somewhere/");
 
  // SHOW AN "OK" PAGE?
  require "OK.PHP"
}
 
// (D) HANDLE ERRORS?
else {
  // (D1) SHOW ERRORS?
  // print_r($status);
 
  // (D2) REDIRECT BACK TO UPLOAD PAGE?
  header("Location: http://site.com/1-upload.html/?error=1");
}
*/


// foreach ($_FILES['image']['name'] as $key => $filename) {
//     $tmp_name = $_FILES['image']['tmp_name'][$key];

//     $pathinfo = pathinfo($filename);
//     $fileExtension = strtolower($pathinfo['extension']);

//     // Check if the file has a valid extension
//     if (!in_array($fileExtension, $allowedExtensions)) {
//         $invalidUploads[] = $filename;
//     } else {
//         // Replace any characters not \w- in the original filename
//         $base = $pathinfo["filename"];
//         $base = preg_replace("/[^\w-]/", "_", $base);
//         $newFilename = $base . "." . $fileExtension;

//         // Add a numeric suffix if the file already exists
//         $i = 1;
//         $upload_file_tmp_name ="/uploads/" . $newFilename;
//         while (file_exists($upload_file_tmp_name)) {
//             $newFilename = $base . "($i)." . $fileExtension;
//             $upload_file_tmp_name = "/uploads/" . $newFilename;
//             $i++;
//         }

//         // Move the file with edited filename
//         if (move_uploaded_file($tmp_name, $upload_file_tmp_name)) {
//             $successfulUploads[] = $newFilename;
//         }
//     }
// }

// Provide feedback to the user

if (!empty($successfulUploads)) {
    // echo 'Successfully uploaded files to server:<br>';
    
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

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>pdf to txt</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="./css/style.css">


</head>

<body>

    <div class="top text-center">
        <small class="text-white">GROUP PROJECT CAT201</small>
        <p class="text-white mb-0">GROUP 46</p>
        <h1>FILE CONVERTER</h1>
        <h5 class="text-white">Seamless PDF Conversion Experience</h5>
        <div class="container  my-5">
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <img src="./images/logo.jpg" width="100" height="60" alt="">
                            <h5 class="mt-2 card-pdf-title">PDF TO TEXT/ TEXT TO PDF</h5>
                            <p>Greetings from Group 46, your dedicated facilitators of document conversion
                                excellence.
                                We present to you a sophisticated platform that allows for the transformation of
                                PDFs
                                into editable text and vice versa.
                                Proceed by selecting your document and invoking the 'Convert' feature to initiate
                                the
                                process.
                            </p>
                            <form action="./Upload4.php" method="post" enctype="multipart/form-data">

                                <div class="form-group">
                                    <label for="file">Upload Your File</label>
                                    <input type="file" name="image[]" id="file" class="form-control" multiple>

                                </div>
                                <div class="form-group mt-2">
                                    <input type="submit" class="bt btn-sm btn-primary" id="convertButton"
                                        value="CONVERT">
                                </div>
                            </form>

                        </div>

                    </div>
                </div>
                <div class="col-8 ">
                    <div class="d-flex justify-content-center prev-content bg-light align-items-center h-100">
                        <div class="d-block">
                            <h4>Collect Your Converted File Here</h4>
                            <div class="clear-fix w-100 d-block text-center mt-3">
                                <?= 
                                    $outputThing;
                                    ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="container mb-5">
        <div class="row title">
            <h3>Members</h3>
        </div>
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card text-center">
                    <div class="member-img" style="background: url('./images/members/jiajoo.jpg');"
                        class="card-img-top"></div>

                    <div class="card-body">
                        <h5 class="card-title">Tan Jia Joo</h5>
                        <h5>163573</h5>
                        <p>
                            I'm a second-year computer science student from USM, currently residing in the lovely town
                            of Lunas, Kedah, known for its delicious roasted duck. I'm a big travel enthusiast. I love
                            exploring new places, immersing myself in different cultures, and learning about diverse
                            perspectives. It's amazing how travel broadens your horizons and helps you appreciate the
                            world in a whole new light. I'm always up for trying new things and meeting new people, so
                            feel free to say hi!

                        </p>
                        <a href="https://www.linkedin.com/in/tan-jia-joo-115167256/" target="_blank" class="linked"><img
                                src="./linkedin.svg" width="40" height="40" alt=""></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card text-center">
                    <div class="member-img" style="background: url('./images/members/yipei.jpg');" class="card-img-top">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Tan Yi Pei </h5>
                        <h5>164767</h5>
                        <p>
                            I am an extroverted undergraduate student currently pursuing a Bachelor of Computer Science
                            at USM. As a Penangite, I have a passion or heritage cultures and delicious foods.
                            Additionally, I am a K-pop lover. Feel free to connect with me, as I am eager to immerse you
                            in the world of diverse cultural experiences. I enjoy connecting with individuals from
                            diverse fields, and creating such a network is a goal I am enthusiastic about achieving.
                        </p>
                        <a href="https://www.linkedin.com/in/tan-yi-pei-0362ab236/" target="_blank" class="linked"><img
                                src="./linkedin.svg" width="40" height="40" alt=""></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-md-12">
                <div class="card text-center">
                    <div class="member-img" style="background: url('./images/members/wanxuan.jpg');"
                        class="card-img-top"></div>
                    <div class="card-body">
                        <h5 class="card-title">Lim Wan Xuan</h5>
                        <h5>164963</h5>
                        <p>
                            I'm in my second year of Computer Science at USM, diving into the tech world. Beyond coding,
                            you'll find me enjoying good food and showering love on my furry friends. Whether it's
                            exploring local eats or spending time with pets, I'm all about those joyful moments. Let's
                            swap stories about our favorite dishes and share some pet love!

                        </p>
                        <a href="http://www.linkedin.com/in/wanxuan-lim" target="_blank" class="linked"><img
                                src="./linkedin.svg" width="40" height="40" alt=""></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12">
                <div class="card text-center">
                    <div class="member-img" style="background: url('./images/members/passport.jpg');"
                        class="card-img-top">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Mueed Hyder mir</h5>
                        <h5>160796</h5>
                        <p>I am quite enthusiastic about the things
                            I cherish and one of them is surely “people” .
                            With that said, I admire doing something that
                            helps the people around me in one way or
                            another, which directly or indirectly
                            contributes to my ambition to become an
                            entrepreneur.</p>
                        <a href="http://www.linkedin.com/in/mirmueed" target="_blank" class="linked"><img
                                src="./linkedin.svg" width="40" height="40" alt=""></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF"
        crossorigin="anonymous"></script>

</body>

</html>