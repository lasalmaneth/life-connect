<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class DBUpdate {
    use \App\Core\Database;

    public function update() {
        try {
            $sql = "ALTER TABLE `notifications` 
                    ADD COLUMN `link` VARCHAR(255) DEFAULT NULL AFTER `message`,
                    ADD COLUMN `sender_id` INT DEFAULT NULL AFTER `user_id`,
                    ADD COLUMN `sender_type` ENUM('ADMIN', 'HOSPITAL') DEFAULT 'ADMIN' AFTER `sender_id`";
            
            $this->query($sql);
            echo "Database updated successfully!\n";
        } catch (Exception $e) {
            echo "Error updating database: " . $e->getMessage() . "\n";
        }
    }
}

$u = new DBUpdate();
$u->update();
?>
