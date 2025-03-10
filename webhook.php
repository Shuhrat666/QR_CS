<?php
declare(strict_types=1);

require 'vendor/autoload.php';

use Dotenv\Dotenv;
use Bot\Bot;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

echo (new Bot($_ENV["TOKEN"]))->setWebhook($argv[1]);
