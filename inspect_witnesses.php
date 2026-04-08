<?php
require_once 'app/core/config.php';
require_once 'app/core/Database.php';

class Inspector {
    use \App\Core\Database;

    public function inspect() {
        echo "DESCRIBE witnesses:\n";
        $results = $this->query("DESCRIBE witnesses");
        print_r($results);

        echo "\nSHOW INDEX FROM witnesses:\n";
        $indices = $this->query("SHOW INDEX FROM witnesses");
        print_r($indices);
    }
}

$i = new Inspector();
$i->inspect();
