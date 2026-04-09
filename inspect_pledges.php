<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class T { use \App\Core\Database; }
$db = new T();

$user = $db->query("SELECT id FROM users WHERE username = 'donor_1'");
if (!$user) {
    echo "User donor_1 not found\n";
    exit;
}

$userId = $user[0]->id;
$donor = $db->query("SELECT id FROM donors WHERE user_id = $userId");
if (!$donor) {
    echo "Donor record not found for user_id $userId\n";
    exit;
}

$donorId = $donor[0]->id;
echo "Donor ID: $donorId\n";

$pledges = $db->query("SELECT dp.*, o.name FROM donor_pledges dp JOIN organs o ON dp.organ_id = o.id WHERE dp.donor_id = $donorId AND dp.status != 'WITHDRAWN'");
echo "Active Pledges (Total ".count($pledges)."):\n";

foreach($pledges as $p) {
    echo "ID: {$p->id} | Organ: {$p->name} | Date: {$p->pledge_date} | Status: {$p->status} | Path: {$p->signed_form_path}\n";
}
