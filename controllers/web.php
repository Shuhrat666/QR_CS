<?php
    declare(strict_types= 1);

    require 'vendor/autoload.php';

    use Web\Converter;
?>
<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR CODE Generator and Scanner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../style/styles.css" rel="stylesheet"> 
</head>
<body>
    <div class="container mt-5">
        <h1>QR CODE Generator & Scanner</h1>
        <div class="card mb-4 shadow-sm">
            <div class="card-header">
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
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["text"])) {
            $web=new Converter();
            $qr=$web->text2qr($_POST["text"]);
            if (file_exists($qr)) {
                echo "<p>QR code generated successfully!</p>";
                echo "<a href='$qr' download><img src='$qr' alt='QR Code not found!' width='200px' height='200px'></a>";
                echo "<p>Click on the QR code to download!</p>";
            } else {
                echo "<p>Failed to save or display the QR code.</p>";
            }
        }
    ?>

            </div>
    </div>
        <div class="card shadow-sm">
            <div class="card-header">
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
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["qrImage"]) && isset($_POST["decode"])) {
            $web=new Converter();
            $uploadedFile = $_FILES["qrImage"]["tmp_name"];
            $text=$web->qr2txt($uploadedFile);
            if ($text) {
                echo "<p>QR code text: <strong>$text</strong></p>";
            } else {
                echo "<p>Error occurred in reading the QR code!<br>Only QR codes allowed !</p>";
            }
            
        }
    ?>
    
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
