<?php
require_once __DIR__ . '/../../app/Core/init.php';
$m = new \App\Core\Database();
echo "--- donors ---\n";
print_r($m->query("SHOW COLUMNS FROM donors"));
echo "--- recipient_patient ---\n";
print_r($m->query("SHOW COLUMNS FROM recipient_patient"));
echo "--- test_results ---\n";
print_r($m->query("SHOW COLUMNS FROM test_results"));
