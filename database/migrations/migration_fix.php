<?php
$host = 'localhost';
$db   = 'life-connect';
$user = 'root';
$pass = ''; // empty from config.php
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     $query = "ALTER TABLE organ_requests ADD COLUMN urgency_reason TEXT DEFAULT NULL AFTER priority_level";
     $pdo->exec($query);
     echo "Column 'urgency_reason' successfully added to 'organ_requests' table.\n";
} catch (\PDOException $e) {
     if ($e->getCode() == '42S21') {
         echo "Column 'urgency_reason' already exists.\n";
     } else {
         echo "Migration failed: " . $e->getMessage() . "\n";
     }
}
?>
