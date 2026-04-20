<?php
require_once 'app/Core/config.php';
require_once 'app/Core/Database.php';
class DB { use App\Core\Database; }
$db = new DB();

function dumpTable($db, $table) {
    echo "--- $table ---\n";
    $cols = $db->query("DESCRIBE $table");
    foreach($cols as $c) {
        echo "{$c->Field} ({$c->Type})\n";
    }
    echo "\n";
}

dumpTable($db, 'donors');
dumpTable($db, 'body_donation_consents');
dumpTable($db, 'death_declarations');
dumpTable($db, 'donation_cases');
