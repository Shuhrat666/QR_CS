<?php
    declare(strict_types= 1);
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR CODE Generator and Scanner</title>
</head>
<body>
    <h1>QR CODE Generator & Scanner</h1>
    <h2>TEXT 2 QR CODE</h2>
    <form method="post">
        <label for="text">Enter text :</label><br>
        <input type="text" id="text" name="text" required>
        <button type="submit">Create QR code</button>
    </form>

    <?php
    require 'vendor/autoload.php';

    use chillerlan\QRCode\QRCode;
    use chillerlan\QRCode\QROptions;

    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["text"])) {
        $text = $_POST["text"];
        $qrCodeFile = "qrcodes/" . uniqid() . ".png";

        $options = new QROptions([
            'outputType' => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel' => QRCode::ECC_L,
        ]);
        $qrcode = new QRCode($options);
        $qrcode->render($text, $qrCodeFile);

        echo "<p>QR code generated successfully !</p>";
        echo "<img src='$qrCodeFile' alt='QR Code'>";
    }
    ?>

    <h2>QR CODE 2 TEXT</h2>
    <form method="post" enctype="multipart/form-data">
        <label for="qrImage">Upload QR code:</label><br>
        <input type="file" id="qrImage" name="qrImage" accept="image/*" required>
        <button type="submit" name="decode">Read QR code</button>
    </form>

    <?php

        use Zxing\QrReader;

        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["qrImage"]) && isset($_POST["decode"])) {
            $uploadedFile = $_FILES["qrImage"]["tmp_name"];
            $qrReader = new QrReader($uploadedFile);
            $decodedText = $qrReader->text();

            if ($decodedText) {
                echo "<p>QR code text: <strong>$decodedText</strong></p>";
            } else {
                echo "<p>Error occured in reading the QR code !</p>";
            }
        }
    ?>
</body>
</html>
