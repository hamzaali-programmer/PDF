<?php

require 'vendor/autoload.php';

use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\Writer\Pdf\Mpdf as PdfMpdf;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['File'])) {
    // Handle the uploaded file
    $wordFile = $_FILES['File'];

    // Check if there was no file error
    if ($wordFile['error'] === UPLOAD_ERR_OK) {
        // Specify the target directory for file upload
        $targetDirectory = 'C:/xampp/htdocs/PdftoWordConverter/uploads/';

        // Generate a unique filename for the uploaded file
        $targetFileName = $targetDirectory . uniqid('word_', true) . '.' . pathinfo($wordFile['name'], PATHINFO_EXTENSION);

        // Move the uploaded file to the target directory
        if (move_uploaded_file($wordFile['tmp_name'], $targetFileName)) {
            // Load the Word file
            $phpWord = IOFactory::load($targetFileName);

            // Set PDF options if needed
            Settings::setPdfRendererPath('vendor/mpdf/mpdf');
            Settings::setPdfRendererName('MPDF');

            // Create an Mpdf writer
            $writer = new PdfMpdf($phpWord);

            // Set the output PDF file path without specifying a directory
            $pdfFilePath = 'converted_file.pdf';

            // Save the PDF file
            $writer->save($pdfFilePath);

            // Provide the generated PDF for download
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="converted_file.pdf"');
            readfile($pdfFilePath);

            // Delete the temporary Word and generated PDF files
            unlink($targetFileName);
            unlink($pdfFilePath);

            // Exit to prevent any additional output
            exit;
        } else {
            echo "Failed to move the uploaded file to the target directory.";
        }
    } else {
        echo "File upload failed with error code: " . $wordFile['error'];
    }
} else {
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToconvertwordtoPDF</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>

    <header>

        <div class="main-div">
            <div class="logo-div">
                <img src="graphics/ilovepdf.svg" alt="">
            </div>
            <div class="nav-div">
                <ul>
                    <li><a href="merge.php">Merge PDF</a></li>
                    <li><a href="split.php">SPLIT PDF</a></li>
                    <li><a href="compress.php">COMPRESS PDF</a></li>
                    <li><a href="">CONVERT PDF<i class="fa-solid fa-caret-down"></i></a>
                        <div class="sub-nav-div">
                            <ul class="ul-1">
                                <h2 class="sub-nav-heading-1">Convert to PDF</h2>
                                <li> <a href="JPG.php">JPG to PDF</a>
                                </li>
                                <li><a href="word.php">WORD to PDF</a>
                                </li>
                                <li><a href="powerpoint.php">POWERPOINT to PDF</a>
                                </li>
                                <li><a href="excel.php">EXCEL to PDF</a>
                                </li>
                            </ul>
                            <ul class="ul-2">
                                <h2 class="sub-nav-heading-2">Convert to PDF</h2>
                                <li><a href="PDFtoJPG.php">PDF to JPG</a>
                                </li>
                                <li><a href="index.php">PDF to WORD</a>
                                </li>
                                <li><a href="PDFtoPowerPoint.php">PDF to POWERPOINT</a>
                                </li>
                                <li><a href="PDFtoEXCEL.php">PDF to EXCEL</a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <div class="login-div">
                <i class="fa-solid fa-right-to-bracket"></i>
                <a href="login.php">Login</a>
            </div>
            <div class="signup-div">
                <a href="signup.php">Sign up</a>
            </div>
            <div class="burger-div">
                <i class="fa-solid fa-bars"></i>
            </div>

        </div>
    </header>

    <section>
        <div class="main-div-converter">
            <div class="main-div-converter-1" id="imagePreviewContainer">
                <div class="file-container">
                    <canvas id="previewCanvas" style="padding: 42px; background-color: white; height: 100px; width: auto; border: 1px solid #ccc; object-fit: contain;"></canvas>
                    <p id="fileName"></p>
                </div>
            </div>
            <div class="main-div-converter-2">
                <h1>Word to PDF</h1>
                <hr class="line">

                <div class="button-div-converter">
                    <form id="uploadForm" action="ToconvertwordtoPDF.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="File" accept=".doc, .docx" onchange="displayFileName(this)" />
                        <button type="submit">Convert to PDF<i class="fa-solid fa-arrow-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        function displayFileName(input) {
            const fileName = input.files[0].name;
            const fileNameElement = document.getElementById('fileName');
            fileNameElement.textContent = `Selected File: ${fileName}`;
            sessionStorage.setItem('selectedFileName', fileName);

            // Display default image or clear previous image
            const canvas = document.getElementById('previewCanvas');
            const ctx = canvas.getContext('2d');
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            // Display default image or desired image here
            // For now, displaying a placeholder image
            const img = new Image();
            img.onload = function() {
                canvas.width = img.width;
                canvas.height = img.height;
                ctx.drawImage(img, 0, 0);
            };
            img.src = 'graphics/doc.svg';
        }

        function convertToPDF() {
            // Trigger the form submission
            document.getElementById("uploadForm").submit();
        }
    </script>
</body>

</html>