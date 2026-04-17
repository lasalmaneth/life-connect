<?php
require_once 'app/Core/config.php';
require_once 'app/Core/Database.php';

class Inspector {
    use \App\Core\Database;

    public function run() {
        $pdo = $this->connect();
        $stmt = $pdo->query("SELECT id, name, description FROM organs ORDER BY id ASC");
        $all = $stmt->fetchAll(PDO::FETCH_ASSOC);
        print_r($all);
    }
}

$i = new Inspector();
$i->run();
