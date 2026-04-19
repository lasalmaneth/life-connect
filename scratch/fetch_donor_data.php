<?php
require_once 'app/core/config.php';
try {
    $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
    $stmt = $pdo->prepare("SELECT blood_group, hla_a1, hla_a2, hla_b1, hla_b2, hla_dr1, hla_dr2 FROM donors WHERE id = 8043");
    $stmt->execute();
    $donor = $stmt->fetch(PDO::FETCH_ASSOC);
    echo json_encode($donor);
} catch (Exception $e) {
    echo $e->getMessage();
}
