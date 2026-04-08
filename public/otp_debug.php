<?php
// Quick diagnostic for OTP issues - accessible at /Life-connect/public/otp_debug.php
// REMOVE THIS FILE from production!
session_start();
require "../app/Core/init.php";

$email = strtolower(trim($_GET['email'] ?? ''));
header('Content-Type: text/plain');

if (!$email) {
    echo "Usage: otp_debug.php?email=your@email.com\n\n";
    echo "SESSION donor_registration email: " . ($_SESSION['donor_registration']['email'] ?? 'NOT SET') . "\n";
    echo "SESSION institution_registration email: " . ($_SESSION['institution_registration']['email'] ?? 'NOT SET') . "\n";
    exit;
}

class Test { use App\Core\Database; public function q($sql, $p=[]) { return $this->query($sql, $p); } }
$db = new Test();

echo "=== OTP Debug for: $email ===\n\n";

$rows = $db->q("SELECT * FROM registration_otps WHERE email = :e ORDER BY id DESC LIMIT 5", [':e' => $email]);
if ($rows) {
    foreach ($rows as $r) {
        echo "ID: {$r->id} | OTP: {$r->otp} | Verified: {$r->verified} | Expires: {$r->expires_at} | Attempts: " . ($r->attempts ?? 'col missing') . "\n";
    }
} else {
    echo "No OTP records found for: $email\n";
    
    // Try without normalization
    $rows2 = $db->q("SELECT email, verified FROM registration_otps ORDER BY id DESC LIMIT 5");
    echo "\nLast 5 records in table (any email):\n";
    if ($rows2) {
        foreach ($rows2 as $r) { echo "  email={$r->email} verified={$r->verified}\n"; }
    } else {
        echo "  Table is empty or doesn't exist.\n";
    }
}
