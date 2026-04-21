<?php
header('Content-Type: text/plain');
$pdo = new PDO('mysql:host=localhost;dbname=life-connect', 'root', '');

function describeTable($pdo, $table) {
    echo "--- $table ---\n";
    try {
        $q = $pdo->query("DESCRIBE $table");
        if (!$q) {
            echo "Table $table does not exist.\n";
            return;
        }
        while($r = $q->fetch(PDO::FETCH_ASSOC)) {
            echo str_pad($r['Field'], 20) . " | " . str_pad($r['Type'], 20) . " | Null: " . $r['Null'] . " | Default: " . ($r['Default'] === null ? 'NULL' : $r['Default']) . "\n";
        }
    } catch(Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

describeTable($pdo, 'donors');
describeTable($pdo, 'test_results');
describeTable($pdo, 'upcoming_appointments');
