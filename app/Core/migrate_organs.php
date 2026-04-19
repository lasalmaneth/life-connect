<?php
/**
 * ONE-TIME MIGRATION SCRIPT
 * Shift Full Body Donation ID from 9 to 10.
 * Insert Kidney (After Death) as ID 9.
 */

require_once 'app/Core/config.php';
require_once 'app/Core/Database.php';

class Migrator {
    use \App\Core\Database;

    public function run() {
        try {
            $pdo = $this->connect();
            $pdo->beginTransaction();

            echo "Starting migration...\n";

            // Helper to check column existence
            $hasColumn = function($table, $column) use ($pdo) {
                try {
                    $res = $pdo->query("SHOW COLUMNS FROM `$table` LIKE '$column'")->fetch();
                    return !empty($res);
                } catch (\Exception $e) {
                    return false;
                }
            };

            // 1. Shift existing ID 9 to 10 in tables that definitely exist
            // a. donor_pledges (Required)
            $pdo->exec("UPDATE donor_pledges SET organ_id = 10 WHERE organ_id = 9");
            echo "Updated donor_pledges (9 -> 10).\n";

            // b. donor_patient_match (Check column)
            if ($hasColumn('donor_patient_match', 'organ_id')) {
                $pdo->exec("UPDATE donor_patient_match SET organ_id = 10 WHERE organ_id = 9");
                echo "Updated donor_patient_match (9 -> 10).\n";
            }

            // c. witnesses (Check column)
            if ($hasColumn('witnesses', 'organ_id')) {
                $pdo->exec("UPDATE witnesses SET organ_id = 10 WHERE organ_id = 9");
                echo "Updated witnesses (9 -> 10).\n";
            }

            // d. donor_custodians (Check table and column)
            try {
                if ($hasColumn('donor_custodians', 'organ_id')) {
                    $pdo->exec("UPDATE donor_custodians SET organ_id = 10 WHERE organ_id = 9");
                    echo "Updated donor_custodians (9 -> 10).\n";
                }
            } catch (\Exception $e) { /* Table might not exist yet */ }

            // e. organs (Atomic ID swap)
            // First, make sure 10 is clear
            $pdo->exec("UPDATE organs SET id = 999 WHERE id = 10"); 
            // Shift 9 to 10
            $pdo->exec("UPDATE organs SET id = 10 WHERE id = 9");
            // If we temporarily parked someone at 999, move them to 10 if they were Full Body
            $pdo->exec("UPDATE organs SET id = 10 WHERE id = 999 AND name LIKE '%Body%'"); 
            
            // Ensure Full Body has 'educational' in description (used for categorization)
            $pdo->exec("UPDATE organs SET description = 'Educational body donation for research.' WHERE id = 10");
            echo "Updated organs table: Full Body shifted to ID 10.\n";

            // 2. Insert Kidney (After Death) as ID 9
            $check = $pdo->query("SELECT id FROM organs WHERE id = 9 OR name = 'Kidney (After Death)'")->fetch();
            if (!$check) {
                $stmt = $pdo->prepare("INSERT INTO organs (id, name, description) 
                                       VALUES (9, 'Kidney (After Death)', 'Post-mortem kidney donation.')");
                $stmt->execute();
                echo "Inserted 'Kidney (After Death)' as ID 9.\n";
            } else {
                echo "Kidney (After Death) already exists or ID 9 occupied.\n";
            }

            $pdo->commit();
            echo "Migration SUCCESSFUL!\n";
        } catch (\Exception $e) {
            if (isset($pdo) && $pdo->inTransaction()) $pdo->rollBack();
            echo "Migration FAILED: " . $e->getMessage() . "\n";
        }
    }
}

$migrator = new Migrator();
$migrator->run();
