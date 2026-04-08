<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class Inspector {
    use \App\Core\Database;

    public function inspect() {
        echo "\nusers structure:\n";
        print_r($this->query("DESCRIBE users"));
    }
}

$i = new Inspector();
$i->inspect();
