<?php
require 'app/Core/init.php';

class TestModel {
    use \App\Core\Model;
    protected $table = 'donor_patient_match';
}

$model = new TestModel();
$res = $model->query("DESCRIBE donor_patient_match");
print_r($res);
