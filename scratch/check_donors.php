<?php
require_once 'app/Core/Database.php';
class DebugDB extends \App\Core\Database {
    public function check() {
        $res = $this->query("SELECT id, first_name, last_name, eligible_to_donate FROM donors WHERE eligible_to_donate = 'Yes'");
        print_r($res);
        $pledges = $this->query("SELECT * FROM donor_pledges LIMIT 5");
        // print_r($pledges);
    }
}
$d = new DebugDB();
$d->check();
