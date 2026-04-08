<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class Inspector {
    use \App\Core\Database;

    public function inspect() {
        echo "Checking index uq_donor_custodian...\n";
        $results = $this->query("SHOW INDEX FROM custodians WHERE Key_name = 'uq_donor_custodian'");
        print_r($results);

        echo "\nChecking all custodians for donor 1...\n";
        $custodians = $this->query("SELECT * FROM custodians WHERE donor_id = 1");
        print_r($custodians);
    }
}

$i = new Inspector();
$i->inspect();
