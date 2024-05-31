<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ToconvertPDFtoword</title>
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
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
                    <canvas id="previewCanvas" style="height: 180px; width: 127px; border: 1px solid #ccc; object-fit: contain;"></canvas>
                    <p id="fileName"></p>
                </div>
            </div>
            <div class="main-div-converter-2">
                <h1>PDF to Word</h1>
                <hr class="line">
                <div class="button-div">
                    <form id="uploadForm" action="downloadword.php" method="post" enctype="multipart/form-data">
                        <input type="file" name="File" accept=".pdf" onchange="displayPdfPreview(this)" />
                        <button type="submit">Convert to word<i class="fa-solid fa-arrow-right"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://mozilla.github.io/pdf.js/build/pdf.js"></script>
    <script>
        // Initialize PDF.js
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';

        async function displayPdfPreview(input) {
            const fileName = input.files[0].name;
            const fileNameElement = document.getElementById('fileName');
            fileNameElement.textContent = `Selected File: ${fileName}`;

            // Display the first page of the PDF file on the canvas
            const canvas = document.getElementById('previewCanvas');
            const ctx = canvas.getContext('2d');

            const fileReader = new FileReader();
            fileReader.onload = async function(event) {
                const pdfData = new Uint8Array(event.target.result);
                await displayFirstPageImage(pdfData, canvas, ctx);
            };

            fileReader.readAsArrayBuffer(input.files[0]);
        }

        async function displayFirstPageImage(pdfData, canvas, ctx) {
            const pdf = await pdfjsLib.getDocument({
                data: pdfData
            }).promise;

            // Get the first page
            const page = await pdf.getPage(1);

            // Get viewport for the canvas
            const viewport = page.getViewport({
                scale: 1
            });
            canvas.width = viewport.width;
            canvas.height = viewport.height;

            // Render the PDF page on the canvas
            const renderContext = {
                canvasContext: ctx,
                viewport: viewport
            };
            await page.render(renderContext);
        }
    </script>
</body>

</html>