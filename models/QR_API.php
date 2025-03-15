<?php
declare(strict_types=1);

namespace QR_API;
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use \PDO;
use \Exception;
use DB\DB;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

require_once __DIR__. '/../DB.php';

class QR_API {
  private PDO $db;

  public function __construct(){
    $this->db = (new DB(
      $_ENV["DB_HOST"], 
      $_ENV["DB_NAME_API"], 
      $_ENV["DB_USERNAME"], 
      $_ENV["DB_PASSWORD"]))->connect();
  }

  public function select($query, $params) {
    
    $stmt = $this->db->prepare($query);
    foreach ($params as $key => $value) {
        $stmt->bindValue($key + 1, $value);
    }
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
  
}