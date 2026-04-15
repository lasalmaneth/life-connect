<?php
/**
 * Migration: Organ-Specific Re-Donation Eligibility System
 * - Adds SUSPENDED to donor_pledges.status enum
 * - Adds next_eligible_date to donors table
 * - Creates donation_medical_history table
 */

$pdo = new PDO('mysql:host=localhost;dbname=life-connect', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$steps = [];

// 1. Modify donor_pledges.status enum to include SUSPENDED
try {
    $pdo->exec("ALTER TABLE donor_pledges 
        MODIFY COLUMN status ENUM('PENDING','UPLOADED','APPROVED','IN_PROGRESS','COMPLETED','SUSPENDED','WITHDRAWN') 
        NOT NULL DEFAULT 'PENDING'");
    $steps[] = "✅ donor_pledges.status enum updated (SUSPENDED added)";
} catch (PDOException $e) {
    $steps[] = "⚠️  donor_pledges.status: " . $e->getMessage();
}

// 2. Add next_eligible_date to donors table
try {
    $check = $pdo->query("SHOW COLUMNS FROM donors LIKE 'next_eligible_date'")->fetch();
    if (!$check) {
        $pdo->exec("ALTER TABLE donors ADD COLUMN next_eligible_date DATE DEFAULT NULL AFTER consent_date");
        $steps[] = "✅ donors.next_eligible_date column added";
    } else {
        $steps[] = "ℹ️  donors.next_eligible_date already exists";
    }
} catch (PDOException $e) {
    $steps[] = "⚠️  donors.next_eligible_date: " . $e->getMessage();
}

// 3. Create donation_medical_history table
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS donation_medical_history (
        history_id INT(11) NOT NULL AUTO_INCREMENT,
        donor_id INT(11) NOT NULL,
        pledge_id INT(11) NOT NULL,
        donated_organ VARCHAR(100) NOT NULL,
        donation_date DATE NOT NULL,
        recovery_status ENUM('recovering','recovered') NOT NULL DEFAULT 'recovering',
        next_eligible_date DATE DEFAULT NULL,
        doctor_notes TEXT DEFAULT NULL,
        hospital_id INT(11) DEFAULT NULL,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (history_id),
        KEY idx_donor_id (donor_id),
        KEY idx_pledge_id (pledge_id),
        CONSTRAINT fk_dmh_donor FOREIGN KEY (donor_id) REFERENCES donors (id) ON DELETE CASCADE,
        CONSTRAINT fk_dmh_pledge FOREIGN KEY (pledge_id) REFERENCES donor_pledges (id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $steps[] = "✅ donation_medical_history table created";
} catch (PDOException $e) {
    $steps[] = "⚠️  donation_medical_history: " . $e->getMessage();
}

// Summary
echo "=== Migration Results ===\n";
foreach ($steps as $step) {
    echo $step . "\n";
}
echo "\nDone.\n";
