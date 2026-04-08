<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class Migrator {
    use \App\Core\Database;

    public function migrate() {
        $sql = "ALTER TABLE body_donation_consents 
                ADD COLUMN IF NOT EXISTS religion VARCHAR(100) AFTER medical_school_id, 
                ADD COLUMN IF NOT EXISTS special_requests TEXT AFTER religion, 
                ADD COLUMN IF NOT EXISTS responsible_person VARCHAR(255) AFTER special_requests, 
                ADD COLUMN IF NOT EXISTS responsible_contact VARCHAR(20) AFTER responsible_person, 
                ADD COLUMN IF NOT EXISTS transport_arrangement TEXT AFTER responsible_contact";
        
        try {
            $this->query($sql);
            echo "Migration successful!\n";
        } catch (Exception $e) {
            echo "Migration failed: " . $e->getMessage() . "\n";
        }
    }
}

$m = new Migrator();
$m->migrate();
