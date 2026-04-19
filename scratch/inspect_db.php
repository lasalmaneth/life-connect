<?php
require 'app/Core/config.php';
try {
    $dsn = 'mysql:host='.DBHOST.';dbname='.DBNAME;
    $db = new PDO($dsn, DBUSER, DBPASS);
    $tables = ['body_donation_consents', 'donor_pledges', 'organs', 'medical_schools', 'hospitals', 'donation_cases'];
    foreach ($tables as $t) {
        echo "TABLE: $t\n";
        $res = $db->query("DESCRIBE $t");
        print_r($res->fetchAll(PDO::FETCH_ASSOC));
        echo "\n";
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
