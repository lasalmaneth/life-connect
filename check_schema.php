<?php
require_once __DIR__ . '/app/Core/init.php';
$m = new \App\Core\Database();
$t = 'test_results';
echo "--- $t ---\n";
$res = $m->query("SHOW COLUMNS FROM $t");
$cols = [];
foreach($res as $r) {
    $cols[] = $r->Field;
}
echo implode(", ", $cols) . "\n";
