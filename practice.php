<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PDF Page Extractor</title>
    <!-- Include PDF.js library -->
    <script src="https://unpkg.com/pdfjs-dist/build/pdf.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        #inputContainer {
            margin-bottom: 20px;
        }

        #pdfContainer {
            display: flex;
            flex-wrap: wrap;
        }

        .pdfPage {
            margin: 10px;
            border: 1px solid #ccc;
            padding: 10px;
        }
    </style>
</head>
<body>
    <div id="inputContainer">
        <label for="pdfInput">Select a PDF file:</label>
        <input type="file" id="pdfInput" accept=".pdf" onchange="displayFilePreviews(this)">
    </div>

    <div id="pdfContainer"></div>

    <script>
        function displayFilePreviews(input) {
            const container = document.getElementById('pdfContainer');
            container.innerHTML = ''; // Clear previous previews

            const file = input.files[0];

            const fileReader = new FileReader();
            fileReader.onload = function (event) {
                const pdfData = new Uint8Array(event.target.result);
                displayPdfPages(pdfData, container);
            };

            fileReader.readAsArrayBuffer(file);
        }

        async function displayPdfPages(pdfData, container) {
            const pdf = await pdfjsLib.getDocument({ data: pdfData }).promise;

            for (let pageNumber = 1; pageNumber <= pdf.numPages; pageNumber++) {
                // Create a div for each page
                const pageContainer = document.createElement('div');
                pageContainer.classList.add('pdfPage');

                // Display the page number
                const pageLabel = document.createElement('p');
                pageLabel.textContent = `Page ${pageNumber}`;
                pageContainer.appendChild(pageLabel);

                // Create a canvas for each page
                const canvas = document.createElement('canvas');
                pageContainer.appendChild(canvas);

                // Append the page container to the main container
                container.appendChild(pageContainer);

                // Display the current page of the PDF file on the canvas
                const ctx = canvas.getContext('2d');
                const page = await pdf.getPage(pageNumber);
                const viewport = page.getViewport({ scale: 1 });

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