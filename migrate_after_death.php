<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class DeceasedMigrator {
    use \App\Core\Database;

    public function migrate() {
        echo "Starting After-Death Consent migration...\n";

        // 1. Create after_death_consents table
        $tableQuery = "CREATE TABLE IF NOT EXISTS after_death_consents (
            id INT(11) AUTO_INCREMENT PRIMARY KEY,
            donor_id INT(11) NOT NULL,
            suitability_any TINYINT(1) DEFAULT 1,
            is_restricted TINYINT(1) DEFAULT 0,
            religion VARCHAR(100),
            special_instructions TEXT,
            preferred_hospital_id INT(11) NULL,
            witness_name VARCHAR(255),
            witness_nic VARCHAR(20),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (donor_id) REFERENCES donors(id) ON DELETE CASCADE,
            FOREIGN KEY (preferred_hospital_id) REFERENCES hospitals(id) ON DELETE SET NULL
        )";
        
        try {
            $this->query($tableQuery);
            echo "Successfully created after_death_consents table.\n";
        } catch (Exception $e) {
            echo "Error creating table: " . $e->getMessage() . "\n";
        }

        // 2. Add address to next_of_kin
        try {
            $this->query("ALTER TABLE next_of_kin ADD COLUMN address TEXT AFTER email");
            echo "Added address column to next_of_kin table.\n";
        } catch (Exception $e) {
            echo "Address column may already exist: " . $e->getMessage() . "\n";
        }

        echo "Deceased Migration Completed.\n";
    }
}

$m = new DeceasedMigrator();
$m->migrate();
