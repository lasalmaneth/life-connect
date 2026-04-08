<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class Migrator {
    use \App\Core\Database;

    public function run() {
        try {
            echo "Starting migration...\n";
            
            // 1. Add column if not exists
            $this->query("ALTER TABLE witnesses ADD COLUMN witness_number INT(11) AFTER organ_id");
            echo "Added witness_number column.\n";
        } catch (Exception $e) {
            echo "Note: witness_number may already exist or error: " . $e->getMessage() . "\n";
        }

        try {
            // 2. Modify columns
            $this->query("ALTER TABLE witnesses MODIFY COLUMN organ_id INT(11) NULL");
            $this->query("ALTER TABLE witnesses MODIFY COLUMN contact_number VARCHAR(15) NULL");
            echo "Modified organ_id and contact_number columns.\n";

            // 3. Update indices
            $this->query("DROP INDEX IF EXISTS uq_donor_organ_witness ON witnesses");
            $this->query("CREATE UNIQUE INDEX uq_donor_organ_witness ON witnesses(donor_id, organ_id, witness_number)");
            echo "Updated unique index uq_donor_organ_witness.\n";

            echo "Migration completed successfully!\n";
        } catch (Exception $e) {
            echo "Migration failed: " . $e->getMessage() . "\n";
        }
    }
}

$m = new Migrator();
$m->run();
