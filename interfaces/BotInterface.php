<?php

namespace Interfaces;

interface BotInterface {
    public function handle(string $update);
    public function setWebhook(string $url): string;
    public function handleStartCommand();
    public function prepareTextToQr();
    public function prepareQrToText();
    public function handleDefaultCommand(string $text, array $photo, string $called_query);
}


    interface WebInterface{
        
        public function text2qr($t_text);
        public function qr2txt($uploaded);
    }
?>