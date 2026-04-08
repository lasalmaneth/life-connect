<?php
require __DIR__ . '/../app/Core/config.php';

try {
    $pdo = new PDO('mysql:host=' . DBHOST . ';dbname=' . DBNAME, DBUSER, DBPASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = 'lifeconnectsrilanka@gmail.com';

    echo "=== OTP records for: $email ===\n";
    $stmt = $pdo->prepare('SELECT * FROM registration_otps WHERE email = ? ORDER BY id DESC LIMIT 5');
    $stmt->execute([$email]);
    $rows = $stmt->fetchAll(PDO::FETCH_OBJ);

    if ($rows) {
        foreach ($rows as $r) {
            $attempts = isset($r->attempts) ? $r->attempts : 'COLUMN MISSING';
            echo "ID={$r->id} | OTP={$r->otp} | Verified={$r->verified} | Expires={$r->expires_at} | Attempts=$attempts\n";
        }
    } else {
        echo "No records found for that email.\n";
        echo "Last 10 rows in table (any email):\n";
        $stmt2 = $pdo->query('SELECT email, verified FROM registration_otps ORDER BY id DESC LIMIT 10');
        foreach ($stmt2->fetchAll(PDO::FETCH_OBJ) as $r) {
            echo "  email={$r->email} verified={$r->verified}\n";
        }
    }

    echo "\n=== Table structure ===\n";
    $stmt3 = $pdo->query('DESCRIBE registration_otps');
    foreach ($stmt3->fetchAll(PDO::FETCH_OBJ) as $col) {
        echo "{$col->Field} ({$col->Type}) default={$col->Default}\n";
    }

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
