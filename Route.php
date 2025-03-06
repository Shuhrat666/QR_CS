<?php
declare(strict_types=1);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

class Route {
//   public static function handleBot(): void {
//     $update = file_get_contents('php://input');

//     if($update){
//         (new Bot($_ENV["TOKEN"]))->handle($update);
//     }
//   }

  public static function handleWeb(): void {
    
    require 'controllers/web.php';
    // print_r((new Web())->text2qr());
    // print_r((new Web())->qr2txt());
    
  }
}