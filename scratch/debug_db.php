<?php
require_once 'app/core/config.php';
try {
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
    $stmt = $pdo->query("DESCRIBE donor_patient_match");
    $cols = $stmt->fetchAll(PDO::FETCH_COLUMN);
    file_put_contents('scratch/schema_debug.txt', implode(', ', $cols));
} catch (Exception $e) {
    file_put_contents('scratch/schema_debug.txt', 'ERROR: ' . $e->getMessage());
}
