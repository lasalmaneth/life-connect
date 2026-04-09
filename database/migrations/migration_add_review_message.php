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

    $query = "ALTER TABLE users ADD COLUMN review_message TEXT DEFAULT NULL AFTER status";
    $pdo->exec($query);

    echo "Column 'review_message' successfully added to 'users' table.\n";
} catch (\PDOException $e) {
    // 42S21 = column already exists
    if ($e->getCode() == '42S21') {
        echo "Column 'review_message' already exists.\n";
    } else {
        echo "Migration failed: " . $e->getMessage() . "\n";
    }
}
