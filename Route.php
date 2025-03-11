<?php
declare(strict_types=1);

namespace Route;

use Dotenv\Dotenv;
use Bot\Bot;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

class Route {
  public static function handleBot(): void {
    $update = file_get_contents('php://input');

    if ($update) {
        try {
            (new Bot($_ENV["TOKEN"]))->handle($update);
        } catch (\Exception $e) {
            http_response_code(500);
            echo "Bot error: " . $e->getMessage();
        }
    } else {
        http_response_code(400); 
        echo "No bot payload received.";
    }
  }

  public static function handleWeb(): void {
    require 'view/web.php'; 
  }
}
