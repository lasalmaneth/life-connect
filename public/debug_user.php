<?php
require '../app/Core/config.php';
require '../app/Core/Database.php';

class DebugUser {
    use App\Core\Database;
    public function check() {
        $users = $this->query("SELECT id, username, email, role, status FROM users WHERE username = 'test_kidney_all' OR email = 'test_kidney_all@test.com'");
        echo "USERS FOUND: " . json_encode($users) . "\n";
        
        $donors = $this->query("SELECT id, user_id, first_name, last_name FROM donors WHERE user_id IN (SELECT id FROM users WHERE username = 'test_kidney_all')");
        echo "DONORS FOUND: " . json_encode($donors) . "\n";

        $custodians = $this->query("SELECT id, user_id, donor_id, name FROM custodians WHERE user_id IN (SELECT id FROM users WHERE username = 'test_kidney_all')");
        echo "CUSTODIANS FOUND: " . json_encode($custodians) . "\n";
    }
}

$d = new DebugUser();
$d->check();
