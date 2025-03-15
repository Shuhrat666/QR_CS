<?php
    declare(strict_types= 1);

    namespace Web;

    use Zxing\QrReader;
    use chillerlan\QRCode\QRCode;
    use chillerlan\QRCode\QROptions;
    use Interfaces\WebInterface;

    class Converter implements WebInterface{
        public function text2qr($t_text) {

            $text = $t_text;
            $qrCodeFile = "qr_codes/".uniqid() . ".png";

            $options = new QROptions([
                'outputType' => QRCode::OUTPUT_IMAGE_PNG,
                'eccLevel' => QRCode::ECC_L,
            ]);

            $qrcode = new QRCode($options);
            $qrcode->render($text, $qrCodeFile);
        
            //unlink($qrCodeFile);
                
            return $qrCodeFile;
        }

        public function qr2txtbot($uploadedFilePath) {
            $imageFileType = strtolower(pathinfo($uploadedFilePath, PATHINFO_EXTENSION));
        
            if (!in_array($imageFileType, ['jpg', 'jpeg', 'png'])) {
                return "Error: Only JPG, JPEG, and PNG files are allowed.";
            }
        
            $qrReader = new QrReader($uploadedFilePath);
            $decodedText = $qrReader->text();
        
            return $decodedText ?: "Error: Unable to decode QR code.";
        }
        
        
        
    
        public function qr2txt($uploaded) {
    
            $uploadedFile = $uploaded;   
            $imageFileType = strtolower(pathinfo($_FILES["qrImage"]["name"], PATHINFO_EXTENSION));
            
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                $decodedText="Sorry, only JPG, JPEG, PNG files are allowed.";
                return $decodedText;
            }
            
            $qrReader = new QrReader($uploadedFile);
            $decodedText = $qrReader->text();

            return $decodedText;
        }
        

    }

?>
