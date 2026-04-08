<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class Inspector {
    use \App\Core\Database;

    public function inspect() {
        echo "Inspecting donor_pledges table...\n";
        $pledges = $this->query("SELECT * FROM donor_pledges ORDER BY id DESC LIMIT 5");
        print_r($pledges);

        echo "\nInspecting living_donor_consents table...\n";
        $consents = $this->query("SELECT * FROM living_donor_consents ORDER BY id DESC LIMIT 5");
        print_r($consents);
    }
}

$i = new Inspector();
$i->inspect();
