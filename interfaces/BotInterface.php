<?php

namespace Interfaces;

interface BotInterface {

    public function handle(string $update);
    public function setWebhook(string $url): string;
    public function resolveTelegramFilePath($fileId, $token);
    public function handleStartCommand();
    public function prepareTextToQr();
    public function prepareQrToText();
    public function handleDefaultCommand(string $text, array $photo, string $called_query);
}
?>