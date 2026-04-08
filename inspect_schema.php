<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class Inspector {
    use \App\Core\Database;

    public function inspect() {
        $tables = $this->query("SHOW TABLES");
        echo "Current tables:\n";
        print_r($tables);

        $desc = $this->query("SHOW TABLES LIKE 'after_death_consents'");
        if ($desc) {
            echo "\nafter_death_consents EXISTS:\n";
            print_r($this->query("DESCRIBE after_death_consents"));
        } else {
            echo "\nafter_death_consents DOES NOT EXIST.\n";
        }
    }
}

$i = new Inspector();
$i->inspect();
