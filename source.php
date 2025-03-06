<?php
    declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR CODE Generator and Scanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center text-primary mb-4">QR CODE Generator & Scanner</h1>
        
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2>TEXT 2 QR CODE</h2>
            </div>
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label for="text" class="form-label">Enter text:</label>
                        <input type="text" id="text" name="text" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Create QR Code</button>
                </form>
                <?php
                require 'vendor/autoload.php';

                use chillerlan\QRCode\QRCode;
                use chillerlan\QRCode\QROptions;

                if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["text"])) {
                    $text = $_POST["text"];
                    $qrCodeFile = __DIR__ . "/qrcodes/" . uniqid() . ".png";

                    if (!is_dir(__DIR__ . "/qrcodes/")) {
                        mkdir(__DIR__ . "/qrcodes/", 0775, true);
                    }

                    $options = new QROptions([
                        'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                        'eccLevel' => QRCode::ECC_L,
                    ]);

                    $qrcode = new QRCode($options);
                    $qrcode->render($text, $qrCodeFile);

                    $webPath = "qrcodes/" . basename($qrCodeFile);
                    echo "<div class='alert alert-success mt-3'>QR code generated successfully!</div>";
                    echo "<img src='$webPath' alt='QR Code' class='img-fluid'>";
                }
                ?>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h2>QR CODE 2 TEXT</h2>
            </div>
            <div class="card-body">
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="qrImage" class="form-label">Upload QR code:</label>
                        <input type="file" id="qrImage" name="qrImage" accept="image/*" class="form-control" required>
                    </div>
                    <button type="submit" name="decode" class="btn btn-success">Read QR Code</button>
                </form>
                <?php
                
                use Zxing\QrReader;

                if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["qrImage"]) && isset($_POST["decode"])) {
                    $uploadedFile = $_FILES["qrImage"]["tmp_name"];
                    $imageFileType = strtolower(pathinfo($_FILES["qrImage"]["name"], PATHINFO_EXTENSION));
        
                    if (!is_uploaded_file($uploadedFile)) {
                        echo "<div class='alert alert-danger mt-3'>File upload failed!</div>";
                    } else {
                        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                            echo "Sorry, only JPG, JPEG, PNG files are allowed.";
                            exit;
                        }
                        $qrReader = new QrReader($uploadedFile);
                        $decodedText = $qrReader->text();

                        if ($decodedText) {
                            echo "<div class='alert alert-success mt-3'>QR code text: <strong>$decodedText</strong></div>";
                        } else {
                            echo "<div class='alert alert-danger mt-3'>Error occurred in reading the QR code!<br>Only QR codes allowed !</div>";
                        }
                    }
                }
                ?>
            </div>
        </div>
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
