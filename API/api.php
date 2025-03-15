<?php

require "vendor/autoload.php";

use Web\Converter;

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"]==='POST' && isset($_GET['action'])){
    
    $action=$_GET['action'];
    $web=new Converter();
    $response=[];

    switch($action){
        case 'text2qr' :
            $text=$_REQUEST['text']?? '';
            if(empty($text)){
                $response['error']="Empty text! Text is required!";
            }
            else{
                $qr=$web->text2qr($text);
                if(file_exists($qr)){
                    $response['qr']=$qr;
                }
                else{
                    $response['error']="Failed to save or display the QR code";
                }
                
            }
            break;
        case 'qr2txt' :
            if(isset($_FILES['qrImage'])&&$_FILES['qrImage']['error']===UPLOAD_ERR_OK){
                $uploadedFile=$_FILES['qrImage']['tmp_name'];
                if(empty($uploadedFile)){
                    $response['error']="Empty uploaded file !";
                }
                else{
                    $response['decoded_text']=$web->qr2txt($uploadedFile);
                }                
            }
            else{
                $response['error']="Error in the uploaded QR file or no QR code uploaded !";
            }
            break;
        case 'qr2txtbot' :
            $uploadedFilePath=$_POST['filePath']??'';
            if(empty($uploadedFilePath)){
                $response['error']="File path required !";
            }
            else{
                $response['decoded_text']=$web->qr2txtbot($uploadedFilePath);
            }
            break;
        default:
            $response['error']="Error in action !";
    }

    echo json_encode($response);
}
else{
    echo json_encode(["error"=>"Invalid request or missing action parametres !"]);
}