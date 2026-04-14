<?php
require_once 'app/Core/Database.php';
class DBCheck { use \App\Core\Database; }
$db = new DBCheck();
$res = $db->query("SELECT COUNT(*) as count FROM aftercare_patients");
echo "Count: " . ($res[0]->count ?? 'Error');
