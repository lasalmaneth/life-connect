<?php
$host = 'localhost';
$db   = 'life-connect';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

function columnExists(PDO $pdo, string $table, string $column): bool
{
    $stmt = $pdo->prepare("SELECT COUNT(*) AS c FROM information_schema.COLUMNS WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = :t AND COLUMN_NAME = :c");
    $stmt->execute([':t' => $table, ':c' => $column]);
    return ((int)($stmt->fetch()['c'] ?? 0)) > 0;
}

function safeExec(PDO $pdo, string $sql, string $successMsg)
{
    try {
        $pdo->exec($sql);
        echo $successMsg . "\n";
    } catch (PDOException $e) {
        // 42S21 = duplicate column name, 42000 often for unsupported syntax
        echo "Skipped/Failed: {$e->getMessage()}\n";
    }
}

try {
    $pdo = new PDO($dsn, $user, $pass, $options);

    // Add missing columns (store request details in DB columns)
    if (!columnExists($pdo, 'organ_requests', 'recipient_age')) {
        safeExec($pdo, "ALTER TABLE organ_requests ADD COLUMN recipient_age TINYINT UNSIGNED NULL AFTER organ_id", "Added organ_requests.recipient_age");
    } else {
        echo "Column organ_requests.recipient_age already exists\n";
    }

    if (!columnExists($pdo, 'organ_requests', 'blood_group')) {
        safeExec($pdo, "ALTER TABLE organ_requests ADD COLUMN blood_group VARCHAR(3) NULL AFTER recipient_age", "Added organ_requests.blood_group");
    } else {
        echo "Column organ_requests.blood_group already exists\n";
    }

    if (!columnExists($pdo, 'organ_requests', 'gender')) {
        safeExec($pdo, "ALTER TABLE organ_requests ADD COLUMN gender ENUM('Male','Female','Other') NULL AFTER blood_group", "Added organ_requests.gender");
    } else {
        echo "Column organ_requests.gender already exists\n";
    }

    if (!columnExists($pdo, 'organ_requests', 'hla_typing')) {
        safeExec($pdo, "ALTER TABLE organ_requests ADD COLUMN hla_typing VARCHAR(255) NULL AFTER gender", "Added organ_requests.hla_typing");
    } else {
        echo "Column organ_requests.hla_typing already exists\n";
    }

    if (!columnExists($pdo, 'organ_requests', 'transplant_reason')) {
        safeExec($pdo, "ALTER TABLE organ_requests ADD COLUMN transplant_reason TEXT NULL AFTER hla_typing", "Added organ_requests.transplant_reason");
    } else {
        echo "Column organ_requests.transplant_reason already exists\n";
    }

    // Optional: store edit-time urgency change reason separately
    if (!columnExists($pdo, 'organ_requests', 'urgency_reason')) {
        safeExec($pdo, "ALTER TABLE organ_requests ADD COLUMN urgency_reason TEXT NULL AFTER priority_level", "Added organ_requests.urgency_reason");
    } else {
        echo "Column organ_requests.urgency_reason already exists\n";
    }

    // Update status column to support PENDING and default to PENDING
    $statusCol = $pdo->query("SHOW COLUMNS FROM organ_requests LIKE 'status'")->fetch();
    $statusType = strtolower((string)($statusCol['Type'] ?? ''));

    if (str_starts_with($statusType, 'enum(')) {
        safeExec(
            $pdo,
            "ALTER TABLE organ_requests MODIFY status ENUM('PENDING','OPEN','MATCHED','CLOSED') DEFAULT 'PENDING'",
            "Updated organ_requests.status enum to include PENDING and default to PENDING"
        );
    } else {
        safeExec(
            $pdo,
            "ALTER TABLE organ_requests MODIFY status VARCHAR(20) DEFAULT 'PENDING'",
            "Updated organ_requests.status to VARCHAR(20) with default PENDING"
        );
    }

    // Backfill: treat existing OPEN as PENDING (what the UI calls \"Pending\")
    safeExec($pdo, "UPDATE organ_requests SET status = 'PENDING' WHERE status = 'OPEN' OR status IS NULL OR status = ''", "Backfilled organ_requests.status to PENDING where applicable");

    // Backfill from previous structured patient_details, if present
    if (columnExists($pdo, 'organ_requests', 'patient_details')) {
        $rows = $pdo->query("SELECT id, patient_details FROM organ_requests WHERE patient_details IS NOT NULL AND patient_details <> ''")->fetchAll();
        $update = $pdo->prepare(
            "UPDATE organ_requests
             SET recipient_age = COALESCE(recipient_age, :age),
                 blood_group = COALESCE(blood_group, :bg),
                 hla_typing = COALESCE(hla_typing, :hla),
                 transplant_reason = COALESCE(transplant_reason, :reason)
             WHERE id = :id"
        );

        $count = 0;
        foreach ($rows as $r) {
            $details = (string)($r['patient_details'] ?? '');

            $age = null;
            $bg = null;
            $hla = null;
            $reason = null;

            if (preg_match('/\[Age\]:\s*([0-9]{1,3})/i', $details, $m)) {
                $age = (int)$m[1];
            }
            if (preg_match('/\[Blood\s*Group\]:\s*([ABO]{1,2}[+-])/i', $details, $m)) {
                $bg = strtoupper($m[1]);
            }
            if (preg_match('/\[HLA\]:\s*([^|]+)/i', $details, $m)) {
                $hla = trim($m[1]);
            }
            // Old flow stored notes under [Notes]
            if (preg_match('/\[Notes\]:\s*([^|]+)\s*(\||$)/i', $details, $m)) {
                $reason = trim($m[1]);
            }

            if ($age !== null || $bg !== null || $hla !== null || $reason !== null) {
                $update->execute([
                    ':id' => (int)$r['id'],
                    ':age' => $age,
                    ':bg' => $bg,
                    ':hla' => $hla,
                    ':reason' => $reason,
                ]);
                $count++;
            }
        }

        echo "Backfilled details from patient_details for {$count} row(s).\n";
    }

    echo "Migration completed.\n";
} catch (PDOException $e) {
    echo "Migration failed: " . $e->getMessage() . "\n";
}
