<?php
declare(strict_types=1);

namespace QR_code;
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use \PDO;
use \Exception;
use DB\DB;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require_once __DIR__. '/../DB.php';

class QR_code {
  private PDO $db;

  public function __construct(){
    $this->db = (new DB(
      $_ENV["DB_HOST"], 
      $_ENV["DB_NAME"], 
      $_ENV["DB_USERNAME"], 
      $_ENV["DB_PASSWORD"]))->connect();
  }
  public function setQuery(string $text): void {
    try {
        $stmt = $this->db->prepare("UPDATE qr_code SET text = :text;");
        $stmt->execute([':text' => $text]);
    } catch (Exception $e) {
        error_log("Database Error: " . $e->getMessage());
    }
}

  public function getQuery(): string {
    try {
        return $this->db->query("SELECT text FROM qr_code")->fetch(PDO::FETCH_ASSOC)["text"];
    } catch (Exception $e) {
        error_log("Database Error: " . $e->getMessage());
        return "";
    }
}

  
}