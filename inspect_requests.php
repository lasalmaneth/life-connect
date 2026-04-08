<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class Inspector {
    use \App\Core\Database;

    public function inspect() {
        echo "\norgan_requests structure:\n";
        print_r($this->query("DESCRIBE organ_requests"));
        
        echo "\norgan_requests content (latest 5):\n";
        print_r($this->query("SELECT * FROM organ_requests ORDER BY id DESC LIMIT 5"));
    }
}

$i = new Inspector();
$i->inspect();
