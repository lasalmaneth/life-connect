<?php
require_once __DIR__ . '/app/Core/init.php';
$m = new \App\Core\Database();
$out = "";
foreach(['donors','recipient_patient','test_results'] as $t) {
    $out .= "--- $t ---\n";
    $res = $m->query("SHOW COLUMNS FROM $t");
    foreach($res as $r) $out .= $r->Field . " (" . $r->Type . ")\n";
}
file_put_contents('out.txt', $out);
