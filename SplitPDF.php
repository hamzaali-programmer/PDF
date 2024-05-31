<?php

use setasign\Fpdi\Fpdi;

require_once 'vendor/autoload.php'; // Include the autoloader for FPDI

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is submitted
    if (isset($_POST['extractBtn'])) {
        // Validate if a file is selected
        if (isset($_FILES['pdfFile']) && $_FILES['pdfFile']['error'] === UPLOAD_ERR_OK) {
            // Create a temporary directory for storing individual pages
            $tempDir = 'temp_' . time();
            mkdir($tempDir);

            // Get the uploaded PDF file
            $pdfFile = $_FILES['pdfFile']['tmp_name'];

            // Open the PDF file using FPDI library
            $pdf = new Fpdi();
            $pdf->setSourceFile($pdfFile);

            // Iterate through each page of the PDF
            $pageCount = $pdf->setSourceFile($pdfFile);

            for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
                // Add a new page to the output PDF
                $pdf->AddPage();

                // Import the current page from the source PDF
                $tplId = $pdf->importPage($pageNumber);

                // Use the imported page as a template
                $pdf->useTemplate($tplId);
            }

            // Output the PDF as a file
            $outputFilePath = $tempDir . '/output.pdf';
            $pdf->Output($outputFilePath, 'F');

            // Create a zip archive for downloaded files
            $zipName = 'extracted_pages.zip';
            $zip = new ZipArchive();
            $zip->open($zipName, ZipArchive::CREATE);

            // Add the extracted PDF file to the zip archive
            $zip->addFile($outputFilePath, 'output.pdf');

            // Close the zip archive
            $zip->close();

            // Set headers for zip download
            header('Content-Type: application/zip');
            header('Content-Disposition: attachment; filename="' . $zipName . '"');
            header('Content-Length: ' . filesize($zipName));
            readfile($zipName);

            // Clean up: remove the temporary directory, output PDF, and zip file
            unlink($outputFilePath);
            rmdir($tempDir);
            unlink($zipName);

            exit;
        } else {
            echo 'Error: Please select a PDF file.';
        }
    }
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SplitPDF</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://unpkg.com/pdfjs-dist@2.11.338/build/pdf.min.js"></script>
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
            <div class="main-div-converter-1" id="imagePreviewContainer"></div>

            <div class="main-div-converter-2">
                <h1>Extract Pages</h1>
                <hr class="line">
                <div class="button-div-converter">
                    <form id="uploadForm" action="SplitPDF.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="pdfFile" accept=".pdf" onchange="displayFilePreviews(this)" id="choose" />
                        <button type="submit" name="extractBtn">Extract Pages<i class="fa-solid fa-arrow-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        const maxCanvasWidth = 200; // Set the maximum width for each canvas
        const maxCanvasHeight = 250; // Set the maximum height for each canvas

        function displayFilePreviews(input) {
            const container = document.getElementById('imagePreviewContainer');
            container.innerHTML = ''; // Clear previous previews

            // Ensure only one file is selected
            if (input.files.length !== 1) {
                alert('Please select one PDF file.');
                return;
            }

            const file = input.files[0];

            // Create a container div for the selected file
            const fileContainer = document.createElement('div');
            fileContainer.style.display = 'inline-block';
            fileContainer.style.margin = '10px';

            // Create a paragraph to display the file name
            const fileNameElement = document.createElement('p');
            fileNameElement.textContent = `Selected File: ${file.name}`;
            fileContainer.appendChild(fileNameElement);

            // Append the file container to the main container
            container.appendChild(fileContainer);

            // Create a row container to hold canvases
            let rowContainer;

            // Count the number of pages in the PDF
            const fileReader = new FileReader();
            fileReader.onload = function(event) {
                const pdfData = new Uint8Array(event.target.result);
                countAndDisplayPages(pdfData, fileContainer);
            };

            fileReader.readAsArrayBuffer(file);
        }

        async function countAndDisplayPages(pdfData, container) {
            const pdf = await pdfjsLib.getDocument({
                data: pdfData
            }).promise;

            const pageCount = pdf.numPages;

            // Create a row container to hold canvases
            let rowContainer;

            // Loop through each page and create a canvas for it
            for (let pageNumber = 1; pageNumber <= pageCount; pageNumber++) {
                // Create a new canvas for each page
                const canvas = document.createElement('canvas');
                canvas.style.padding = '10px';
                canvas.style.backgroundColor = 'white';
                canvas.style.border = '1px solid #ccc';

                // Set the maximum width and height for each canvas
                canvas.width = Math.min(maxCanvasWidth, canvas.width);
                canvas.height = Math.min(maxCanvasHeight, canvas.height);

                // If it's the first canvas in a row, create a new row container
                if (pageNumber % 3 === 1) {
                    rowContainer = document.createElement('div');
                    rowContainer.style.display = 'flex';
                    rowContainer.style.marginBottom = '10px';
                    container.appendChild(rowContainer);
                }

                // Append the canvas to the current row container
                rowContainer.appendChild(canvas);

                // Display the current page number below the canvas
                const pageNumberElement = document.createElement('p');
                pageNumberElement.textContent = `Page ${pageNumber}`;
                rowContainer.appendChild(pageNumberElement);

                // Display the current page of the PDF file on the canvas
                const ctx = canvas.getContext('2d');
                const page = await pdf.getPage(pageNumber);
                const viewport = page.getViewport({
                    scale: Math.min(maxCanvasWidth / page.getViewport({
                        scale: 1
                    }).width, maxCanvasHeight / page.getViewport({
                        scale: 1
                    }).height),
                });

                canvas.width = viewport.width;
                canvas.height = viewport.height;

                const renderContext = {
                    canvasContext: ctx,
                    viewport: viewport,
                };

                await page.render(renderContext);
            }
        }
    </script>

</body>

</html>