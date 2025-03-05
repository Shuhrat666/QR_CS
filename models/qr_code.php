<?php
declare(strict_types=1);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

require_once __DIR__. '/../DB.php';

class QR_code_ {
  private PDO $db;

  public function __construct(){
    $this->db = (new DB(
      $_ENV["DB_HOST"], 
      $_ENV["DB_NAME"], 
      $_ENV["DB_USER"], 
      $_ENV["DB_PASSWORD"]))->connect();
  }

  
}