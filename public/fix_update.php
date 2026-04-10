<?php
require '../app/Core/config.php';
require '../app/Core/App.php';
require '../app/Core/Controller.php';
require '../app/Core/Database.php';
require '../app/Core/Model.php';
require '../app/Models/MedicalSchoolModel.php';

use App\Models\MedicalSchoolModel;

$m = new MedicalSchoolModel();
$schoolId = 2; // Kelaniya
$cisId = 7; // Request ID

$res = $m->updateRequestStatus($schoolId, $cisId, 'REJECTED', 'Too many bodies right now', 9001);
var_dump($res);

$req = $m->query("SELECT * FROM case_institution_status WHERE id = 7");
print_r($req[0]);
