<?php
$host = 'localhost';
$db   = 'life-connect';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     $query = "ALTER TABLE hospitals ADD COLUMN phone VARCHAR(20) DEFAULT NULL AFTER address";
     $pdo->exec($query);
     echo "Column 'phone' successfully added to 'hospitals' table.\n";
} catch (\PDOException $e) {
     if ($e->getCode() == '42S21') {
         echo "Column 'phone' already exists.\n";
     } else {
         echo "Migration failed: " . $e->getMessage() . "\n";
     }
}
?>
