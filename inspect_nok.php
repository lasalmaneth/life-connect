<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class Inspector {
    use \App\Core\Database;

    public function inspect() {
        echo "\nnext_of_kin structure:\n";
        print_r($this->query("DESCRIBE next_of_kin"));
    }
}

$i = new Inspector();
$i->inspect();
