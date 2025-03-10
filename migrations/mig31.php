<?php
declare(strict_types=1);

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$pdo = new PDO('mysql:host='.$_ENV["DB_HOST"].';dbname='. $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
$stmt=$pdo->prepare(query:"CREATE table qr_code(id INT PRIMARY KEY  auto_increment, text varchar(255));");
$stmt->execute();
printf("Created successsfully (Table 'qr_code')!\n");

$pdo = new PDO('mysql:host='.$_ENV["DB_HOST"].';dbname='. $_ENV["DB_NAME"], $_ENV["DB_USER"], $_ENV["DB_PASSWORD"]);
$stmt=$pdo->prepare(query:"INSERT INTO qr_code(text) values('default');");
$stmt->execute();
printf("Inserted successsfully (Table 'qr_code')!\n");

?>