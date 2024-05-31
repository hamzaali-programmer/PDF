<?php

require_once 'vendor/autoload.php';

use setasign\Fpdi\Fpdi;

// Function to merge PDF files
function mergePDFs($files, $outputPath)
{
    $pdf = new FPDI();

    foreach ($files as $file) {
        $pageCount = $pdf->setSourceFile($file);
        for ($pageNumber = 1; $pageNumber <= $pageCount; $pageNumber++) {
            $tplIdx = $pdf->importPage($pageNumber);
            $pdf->AddPage();
            $pdf->useTemplate($tplIdx);
        }
    }

    $pdf->Output($outputPath, 'F');
}

// Handling the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_FILES["pdfFiles"]) && isset($_POST["mergeBtn"])) {
        $uploadedFiles = $_FILES["pdfFiles"]["tmp_name"];
        $outputPath = "merged.pdf"; // Adjust the output file name/path as needed

        // Merge PDF files
        mergePDFs($uploadedFiles, $outputPath);

        // Provide the merged file for download
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="merged.pdf"');
        readfile($outputPath);

        // Delete the temporary merged file
        unlink($outputPath);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToMergeallPDF</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="pdf.js/build/pdf.mjs"></script>
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
            <!-- The container div to hold file previews -->
            <div class="main-div-converter-1" id="imagePreviewContainer"></div>

            <div class="main-div-converter-2">
                <h1>Merge All PDF</h1>
                <hr class="line">
                <div class="button-div-converter">
                    <form id="uploadForm" action="mergeallPDF.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="pdfFiles[]" accept=".pdf" onchange="displayFilePreviews(this)" id="choose" multiple />
                        <button type="submit" name="mergeBtn">Merge All PDF<i class="fa-solid fa-arrow-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script>
        function displayFilePreviews(input) {
            const container = document.getElementById('imagePreviewContainer');
            container.innerHTML = ''; // Clear previous previews

            // Set the number of columns in a row
            const columnsPerRow = 3;
            let currentColumn = 0;

            // Loop through each selected file
            for (const file of input.files) {
                // Create a container div for each file
                const fileContainer = document.createElement('div');
                fileContainer.style.display = 'inline-block';
                fileContainer.style.margin = '10px';

                // Create a canvas for each file
                const canvas = document.createElement('canvas');
                canvas.style.padding = '42px';
                canvas.style.backgroundColor = 'white';
                canvas.style.height = '100px';
                canvas.style.width = 'auto';
                canvas.style.border = '1px solid #ccc';
                canvas.style.objectFit = 'contain';

                // Create a paragraph to display the file name
                const fileNameElement = document.createElement('p');
                fileNameElement.textContent = `Selected File: ${file.name}`;

                // Append canvas and file name elements to the file container
                fileContainer.appendChild(canvas);
                fileContainer.appendChild(fileNameElement);

                // Append the file container to the main container
                container.appendChild(fileContainer);

                // Display the first page of the PDF file on the canvas
                const ctx = canvas.getContext('2d');
                const fileReader = new FileReader();
                fileReader.onload = function(event) {
                    const pdfData = new Uint8Array(event.target.result);
                    displayFirstPageImage(pdfData, canvas, ctx);
                };

                fileReader.readAsArrayBuffer(file);

                // Update the column count and start a new row if necessary
                currentColumn++;
                if (currentColumn === columnsPerRow) {
                    currentColumn = 0;
                    container.appendChild(document.createElement('br'));
                }
            }
        }

        async function displayFirstPageImage(pdfData, canvas, ctx) {
            const pdf = await pdfjsLib.getDocument({
                data: pdfData
            }).promise;
            const page = await pdf.getPage(1);
            const viewport = page.getViewport({
                scale: 1
            });

            canvas.width = viewport.width;
            canvas.height = viewport.height;

            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };

            await page.render(renderContext);
        }
    </script>
</body>

</html>