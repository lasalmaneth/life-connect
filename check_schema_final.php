<?php
require_once __DIR__ . '/app/Core/init.php';
$db = new \App\Core\Database();

function checkTable($db, $tableName) {
    echo "--- Table: $tableName ---\n";
    try {
        $res = $db->query("SHOW COLUMNS FROM $tableName");
        foreach($res as $r) {
            echo "{$r->Field} ({$r->Type})\n";
        }
    } catch(Exception $e) {
        echo "Error checking table $tableName: " . $e->getMessage() . "\n";
    }
    echo "\n";
}

checkTable($db, 'test_results');
checkTable($db, 'donors');
