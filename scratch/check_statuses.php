<?php
require 'app/Core/Config.php';
require 'app/Core/Database.php';

class DB {
    use \App\Core\Database;
    public function getStatuses() {
        return $this->query("SELECT status, COUNT(*) as count FROM users GROUP BY status");
    }
}

$db = new DB();
try {
    $res = $db->getStatuses();
    print_r($res);
} catch (Exception $e) {
    echo $e->getMessage();
}
