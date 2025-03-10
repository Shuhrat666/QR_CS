<?php
    declare(strict_types= 1);

    namespace Web;
    require 'vendor/autoload.php';

    use Zxing\QrReader;
    use chillerlan\QRCode\QRCode;
    use chillerlan\QRCode\QROptions;
    class Converter{
        public function text2qr() {
        
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["text"])) {

                $text = $_POST["text"];
                $qrCodeFile = "qrcodes/".uniqid() . ".png";

                $options = new QROptions([
                    'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                    'eccLevel' => QRCode::ECC_L,
                ]);

                $qrcode = new QRCode($options);
                $qrcode->render($text, $qrCodeFile);
        
                if (file_exists($qrCodeFile)) {
                    echo "<p>QR code generated successfully!</p>";
                    echo "<a href='$qrCodeFile' download><img src='$qrCodeFile' alt='QR Code not found!' width='200px' height='200px'></a>";
                    echo "<p>Click on the QR code to download!</p>";
                } else {
                    echo "<p>Failed to save or display the QR code.</p>";
                }

                //unlink($qrCodeFile);
            }
        }
    
        public function qr2txt() {
    
            if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_FILES["qrImage"]) && isset($_POST["decode"])) {
    
                $uploadedFile = $_FILES["qrImage"]["tmp_name"];
                $imageFileType = strtolower(pathinfo($_FILES["qrImage"]["name"], PATHINFO_EXTENSION));
            
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    echo "Sorry, only JPG, JPEG, PNG files are allowed.";
                    exit;
                }
            
                $qrReader = new QrReader($uploadedFile);
                $decodedText = $qrReader->text();
            
                if ($decodedText) {
                    echo "<p>QR code text: <strong>$decodedText</strong></p>";
                } else {
                    echo "<p>Error occurred in reading the QR code!<br>Only QR codes allowed !</p>";
                }
            }
        }

    }

?>
