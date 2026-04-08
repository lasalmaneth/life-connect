<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class Inspector {
    use \App\Core\Database;

    public function inspect() {
        echo "\ndonors structure:\n";
        print_r($this->query("DESCRIBE donors"));
    }
}

$i = new Inspector();
$i->inspect();
