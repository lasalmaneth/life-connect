<?php
require 'public/index.php'; // This might not work if it tries to route.
// Let's try a simpler approach.
define('DBHOST','localhost');
define('DBNAME','life-connect');
define('DBUSER','root');
define('DBPASS','');

$con = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$usageId = 8004;
// Check if it exists in logs
$stm = $con->prepare("SELECT * FROM body_usage_logs WHERE id = ?");
$stm->execute([$usageId]);
$log = $stm->fetch(PDO::FETCH_OBJ);

if ($log) {
    $ref = "APP-" . date('Y') . "-" . str_pad($usageId, 5, '0', STR_PAD_LEFT);
    $issuerId = 1; // Default to admin or first user
    
    $stm = $con->prepare("INSERT INTO appreciation_letters (usage_log_id, ref_number, issued_by_id, status, issued_at) VALUES (?, ?, ?, 'ISSUED', NOW())");
    $stm->execute([$usageId, $ref, $issuerId]);
    echo "Successfully issued letter for UR-8004\n";
} else {
    echo "Usage Log 8004 not found.\n";
}
