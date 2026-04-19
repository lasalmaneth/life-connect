<?php
require_once 'app/Core/config.php';
require_once 'app/Core/Database.php';

// Fix for trait instantiation
class TestModel {
    use \App\Core\Database;
    public function run($sql, $params = []) {
        return $this->query($sql, $params);
    }
}

$model = new TestModel();
$sql = "SELECT id, donor_id, kidney_decision, body_cornea_decision, resolved_deceased_mode, resolved_operational_track FROM donation_cases ORDER BY id DESC LIMIT 5";
$cases = $model->run($sql);

echo "--- KIDNEY DECISION DEBUG ---\n";
foreach($cases as $c) {
    echo "ID: {$c->id} | Mode: {$c->resolved_deceased_mode} | Track: {$c->resolved_operational_track}\n";
    echo "Kidney Decision: [{$c->kidney_decision}]\n";
    echo "Body Cornea Decision: [{$c->body_cornea_decision}]\n";
    echo "-----------------------------\n";
}
