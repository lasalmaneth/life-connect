<?php
define('ROOT', 'http://localhost/Life-connect/public');
require_once 'app/Core/config.php';
require_once 'app/Core/Database.php';

use App\Core\Database;

$db = new Database();
$donor_id = 1; // Assuming donor 1 based on previous context or just check all
$sql = "SELECT id, donor_id, case_number, resolved_operational_track, resolved_deceased_mode, kidney_decision, body_cornea_decision, operational_items_json FROM donation_cases ORDER BY id DESC LIMIT 5";
$cases = $db->query($sql);

echo "--- DONATION CASES ---\n";
foreach($cases as $c) {
    echo "ID: {$c->id} | Case: {$c->case_number} | Donor: {$c->donor_id}\n";
    echo "Track: {$c->resolved_operational_track} | Mode: {$c->resolved_deceased_mode}\n";
    echo "Kidney Dec: '{$c->kidney_decision}' | Body Cornea Dec: '{$c->body_cornea_decision}'\n";
    echo "Items: " . substr($c->operational_items_json, 0, 100) . "...\n";
    echo "----------------------\n";
}

$hospitals = $db->query("SELECT id, name, verification_status FROM hospitals");
echo "\n--- HOSPITALS ---\n";
foreach($hospitals as $h) {
    echo "ID: {$h->id} | Name: {$h->name} | Status: {$h->verification_status}\n";
}
