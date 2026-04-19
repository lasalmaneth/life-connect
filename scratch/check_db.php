<?php
require 'app/Core/config.php';
$pdo = new PDO('mysql:host='.DBHOST.';dbname='.DBNAME, DBUSER, DBPASS);

echo "--- death_declarations ---\n";
$stmt = $pdo->query('DESCRIBE death_declarations');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}

echo "\n--- donation_cases ---\n";
$stmt = $pdo->query('DESCRIBE donation_cases');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo $row['Field'] . " (" . $row['Type'] . ")\n";
}
