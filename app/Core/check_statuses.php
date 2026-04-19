<?php
require_once 'app/Core/config.php';
require_once 'app/Core/Database.php';

class Checker
{
    use \App\Core\Database;

    public function run()
    {
        $pdo = $this->connect();
        $stmt = $pdo->query("SELECT match_id, donor_status FROM donor_patient_match WHERE donor_status != 'REJECTED' LIMIT 10");
        $all = $stmt->fetchAll(PDO::FETCH_ASSOC);
        print_r($all);
    }
}

$c = new Checker();
$c->run();
