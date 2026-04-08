<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../app/core/init.php';

class DBUpdater {
    use \App\Core\Database;

    public function run() {
        try {
            // Check if attempts column exists
            $result = $this->query("SHOW COLUMNS FROM registration_otps LIKE 'attempts'");
            if (empty($result)) {
                $this->query("ALTER TABLE registration_otps ADD COLUMN attempts INT DEFAULT 0");
                echo "Added 'attempts' column.\n";
            } else {
                echo "'attempts' column already exists.\n";
            }
            echo "SUCCESS";
        } catch (\Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

$updater = new DBUpdater();
$updater->run();
