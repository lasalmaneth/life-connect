<?php
require 'app/core/config.php';
require 'app/core/Database.php';

class MiniModel {
    use \App\Core\Database;
    public function query($query, $data = []) {
        $con = $this->connect();
        $stm = $con->prepare($query);
        $check = $stm->execute($data);
        if($check) {
            return $stm->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }
}

$model = new MiniModel();
$donorId = 8043;
$query = "SELECT p.*, o.name as o_name, o.description as o_desc FROM donor_pledges p 
          JOIN organs o ON p.organ_id = o.id
          WHERE p.donor_id = :did AND p.status != 'WITHDRAWN'";

$results = $model->query($query, [':did' => $donorId]);

echo "All Pledges for Donor $donorId:\n";
if ($results) {
    foreach ($results as $row) {
        $status = $row['status'] ?? 'N/A';
        $hex = bin2hex($status);
        echo "ID: {$row['id']} | Organ: {$row['o_name']} (ID: {$row['organ_id']}) | Status: '{$status}' (Hex: {$hex}) | Desc: {$row['o_desc']}\n";
    }
} else {
    echo "No records found.\n";
}
