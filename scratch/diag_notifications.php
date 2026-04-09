<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class Diag {
    use \App\Core\Database;
    public function run() {
        $donors = $this->query("SELECT d.id, d.user_id, u.username FROM donors d JOIN users u ON d.user_id = u.id LIMIT 5");
        echo "DONORS:\n";
        print_r($donors);
        
        if ($donors) {
            $userId = $donors[0]->user_id;
            echo "\nChecking notifications for User ID: $userId\n";
            $notifs = $this->query("SELECT * FROM notifications WHERE user_id = :uid", [':uid' => $userId]);
            print_r($notifs);
            
            if (empty($notifs)) {
                echo "\nNo notifications found. Inserting test notification...\n";
                $res = $this->query("INSERT INTO notifications (user_id, title, message, type, is_read) 
                                     VALUES (:uid, 'Test Notification', 'This is a diagnostic notification to verify the system.', 'GENERAL', 0)", 
                                     [':uid' => $userId]);
                echo "Insert result: " . ($res ? "Success" : "Success (Non-select)") . "\n";
            }
        }
    }
}

$d = new Diag();
$d->run();
?>
