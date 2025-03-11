<?php
    declare(strict_types= 1);

    namespace Web;
    require 'vendor/autoload.php';

    use Zxing\QrReader;
    use chillerlan\QRCode\QRCode;
    use chillerlan\QRCode\QROptions;
    use Interfaces\WebInterface;

    class Converter implements WebInterface{
        public function text2qr($t_text) {

            $text = $t_text;
            $qrCodeFile = "qrcodes/".uniqid() . ".png";

            $options = new QROptions([
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel' => QRCode::ECC_L,
            ]);

            $qrcode = new QRCode($options);
            $qrcode->render($text, $qrCodeFile);
        
            //unlink($qrCodeFile);
                
            return $qrCodeFile;
        }
        
    
        public function qr2txt($uploaded) {
    
            $uploadedFile = $uploaded;    //$_FILES["qrImage"]["tmp_name"];
            $imageFileType = strtolower(pathinfo($_FILES["qrImage"]["name"], PATHINFO_EXTENSION));
            
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                return "Sorry, only JPG, JPEG, PNG files are allowed.";
            }
            
            $qrReader = new QrReader($uploadedFile);
            $decodedText = $qrReader->text();

            return $decodedText;
        }
        

    }

?>
