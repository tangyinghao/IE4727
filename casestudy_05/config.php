<?php
$DB_HOST = '127.0.0.1';
$DB_NAME = 'javajam';  
$DB_USER = 'root';     
$DB_PASS = '';         
$DB_PORT = 3306;       

$options = [
  PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
  PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      
  PDO::ATTR_EMULATE_PREPARES   => false,                 
];

try {
  $pdo = new PDO(
    "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8mb4",
    $DB_USER,
    $DB_PASS,
    $options
  );
} catch (PDOException $e) {
  exit('DB connection failed: ' . htmlspecialchars($e->getMessage()));
}
