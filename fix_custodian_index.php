<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class Migrator {
    use \App\Core\Database;

    public function run() {
        try {
            echo "Starting custodian index fix...\n";
            
            // 1. Check if uq_donor_custodian exists and includes organ_id
            // We'll just drop and recreate it to be safe
            $this->query("DROP INDEX IF EXISTS uq_donor_custodian ON custodians");
            $this->query("CREATE UNIQUE INDEX uq_donor_custodian ON custodians(donor_id, organ_id, custodian_number)");
            echo "Updated unique index uq_donor_custodian to include organ_id.\n";

            echo "Custodians table migration completed successfully!\n";
        } catch (Exception $e) {
            echo "Migration failed: " . $e->getMessage() . "\n";
        }
    }
}

$m = new Migrator();
$m->run();
