<?php
declare(strict_types=1);

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
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
  public function setQuery(string $text):void {
    $stmt = $this->db->prepare("UPDATE qr_code SET text = :text;");
    
    $stmt->execute([':query' => $text]);
  }

  public function getQuery(): string {
    return $this->db->query("SELECT text FROM qr_code")->fetch(PDO::FETCH_ASSOC)["text"];
  }
  
}