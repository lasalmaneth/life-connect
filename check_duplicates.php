<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class Inspector {
    use \App\Core\Database;

    public function inspect() {
        echo "Inspecting living_donor_consents table (Latest 6)...\n";
        $consents = $this->query("SELECT * FROM living_donor_consents ORDER BY id DESC LIMIT 6");
        print_r($consents);
    }
}

$i = new Inspector();
$i->inspect();
