<?php

namespace App\Models;

use App\Core\Database;

class NextOfKinModel {
    use Database;

    protected $table = 'next_of_kin';

    public function updateNextOfKin($id, $donorId, $data) {
        $query = "UPDATE next_of_kin SET 
                  name = :name, 
                  relationship = :relationship, 
                  nic_number = :nic, 
                  contact_number = :phone, 
                  email = :email 
                  WHERE id = :id AND donor_id = :donor_id";
        
        return $this->query($query, [
            ':id' => $id,
            ':donor_id' => $donorId,
            ':name' => $data['name'],
            ':relationship' => $data['relationship'],
            ':nic' => $data['nic'],
            ':phone' => $data['phone'],
            ':email' => $data['email'] ?? null
        ]);
    }

    public function deleteNextOfKin($id, $donorId) {
        $query = "DELETE FROM next_of_kin WHERE id = :id AND donor_id = :donor_id";
        return $this->query($query, [':id' => $id, ':donor_id' => $donorId]);
    }

    public function getKinByDonorId($donorId) {
        $query = "SELECT * FROM next_of_kin WHERE donor_id = :donor_id ORDER BY id ASC";
        return $this->query($query, [':donor_id' => $donorId]);
    }

    public function countKinByDonorId($donorId) {
        $query = "SELECT COUNT(*) as count FROM next_of_kin WHERE donor_id = :donor_id";
        $result = $this->query($query, [':donor_id' => $donorId]);
        return $result ? $result[0]->count : 0;
    }
}
