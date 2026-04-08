<?php

/**
 * Migration script to remove manual recipient fields from living_donor_consents table.
 */

require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class Migration {
    use \App\Core\Database;

    public function run() {
        try {
            echo "Starting migration (Cleanup manual recipient fields)...\n";

            // Remove columns from living_donor_consents
            echo "Dropping manual recipient columns from living_donor_consents...\n";
            $this->query("ALTER TABLE `living_donor_consents` 
                DROP COLUMN IF EXISTS `recipient_name`,
                DROP COLUMN IF EXISTS `recipient_relationship`,
                DROP COLUMN IF EXISTS `recipient_hospital`
            ");
            echo "Columns dropped successfully.\n";

            echo "Migration completed successfully!\n";
        } catch (Exception $e) {
            echo "Migration failed: " . $e->getMessage() . "\n";
        }
    }
}

$migration = new Migration();
$migration->run();
