<?php
    namespace Interfaces;

    require 'vendor/autoload.php';

    interface BotInterface{

        public function handle(string $update);
        public function setWebhook(string $url): string;
        public function handleStartCommand();
        public function prepareTextToQr();
        public function prepareQrToText();
        public function handleDefaultCommand(string $text, string $called_query);
    }

    interface WebInterface{
        
        public function text2qr($t_text);
        public function qr2txt($uploaded);
    }
?>