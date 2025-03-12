<?php
namespace Interfaces;

interface WebInterface{
        
    public function text2qr($t_text);
    public function qr2txt($uploaded);
    public function qr2txtbot($uploadedFilePath);
}

?>