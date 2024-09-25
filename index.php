<?php
require_once('tcpdf/tcpdf.php');

// Handle PDF generation upon form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['info']) && isset($_POST['filename'])) {
    $info = $_POST['info'];
    $filename = $_POST['filename'];

    // Ensure the filename has .pdf extension
    if (!preg_match('/\.pdf$/', $filename)) {
        $filename .= '.pdf';
    }

    // Create new PDF
    $pdf = new TCPDF();
    $pdf->AddPage();

    // Set PDF title and information
    $pdf->SetTitle('QR Code PDF');
    $pdf->SetAuthor('Your Name');
    $pdf->SetMargins(10, 10, 10);

    // Generate QR Code image using external API
    $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($info);
    $pdf->Image($qrCodeUrl, 80, 50, 50, 50, 'PNG');

    // Add text below QR code
    $pdf->Ln(60);
    $pdf->SetFont('helvetica', '', 12);
    $pdf->Cell(0, 10, 'Information: ' . $info, 0, 1, 'C');

    // Output the generated PDF
    $pdf->Output($filename, 'D'); // 'D' forces the download
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code to PDF Generator</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }

        h1 {
            color: #333;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        textarea, input {
            width: 100%;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
        }

        button {
            padding: 10px 20px;
            border: none;
            background-color: #28a745;
            color: white;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #218838;
        }

        #qrcode {
            margin: 20px auto;
        }
    </style>
    <script src="https://davidshimjs.github.io/qrcodejs/qrcode.min.js"></script>
</head>
<body>
    <div class="container">
        <h1>QR Code to PDF Generator</h1>
        <form id="qrForm" method="POST" action="">
            <label for="info">Enter Information:</label>
            <textarea id="info" name="info" rows="4" required></textarea>
            
            <label for="filename">Enter PDF File Name:</label>
            <input type="text" id="filename" name="filename" placeholder="file_name.pdf" required>
            
            <div id="qrcode"></div>
            
            <button type="button" id="generateQR">Generate QR Code</button>
            <button type="submit" id="downloadPDF">Generate PDF</button>
        </form>
    </div>

    <script>
        // QR code generation logic
        document.getElementById('generateQR').addEventListener('click', function () {
            var qrContainer = document.getElementById("qrcode");
            qrContainer.innerHTML = ""; // Clear previous QR code

            var qrText = document.getElementById("info").value;
            if (qrText.trim() === "") {
                alert("Please enter some information.");
                return;
            }

            // Generate new QR code
            new QRCode(qrContainer, {
                text: qrText,
                width: 128,
                height: 128,
            });
        });
    </script>
</body>
</html>
