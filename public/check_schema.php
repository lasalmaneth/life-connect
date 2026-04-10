<?php
$pdo = new PDO("mysql:host=localhost;dbname=life-connect", "root", "");
foreach(["donors", "custodians", "death_cases", "documents", "case_documents"] as $table) {
    echo "TABLE: $table\n";
    $stmt = $pdo->query("SHOW COLUMNS FROM $table");
    if($stmt) print_r($stmt->fetchAll(PDO::FETCH_COLUMN));
}
