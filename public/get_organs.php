<?php
require '../app/Core/config.php';
$db = new PDO('mysql:host=localhost;dbname=life-connect', 'root', '');
$stmt = $db->query("SELECT id, name FROM organs");
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
