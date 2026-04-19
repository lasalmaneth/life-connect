<?php
require_once __DIR__ . '/../app/Core/config.php';
try {
    $pdo = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
    $stmt1 = $pdo->query("DESCRIBE body_donation_consents");
    echo "BODY CONSENTS SCHEMA:\n";
    print_r($stmt1->fetchAll(PDO::FETCH_ASSOC));
    
    $stmt2 = $pdo->query("SELECT * FROM witnesses LIMIT 5");
    echo "\nWITNESS DATA SAMPLE:\n";
    print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));
} catch (Exception $e) {}
