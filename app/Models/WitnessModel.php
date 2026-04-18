<?php

namespace App\Models;

use App\Core\Model;

class WitnessModel {
    use Model;

    protected $table = 'witnesses';

    protected $allowedColumns = [
        'donor_id',
        'organ_id',
        'witness_number',
        'name',
        'nic_number',
        'contact_number',
        'address'
    ];

    public function addWitness($donorId, $data) {
        return $this->insert([
            'donor_id' => $donorId,
            'name' => $data['name'],
            'nic_number' => $data['nic'],
            'contact_number' => $data['phone'],
            'address' => $data['address'] ?? null
        ]);
    }

    public function getWitnessesByDonorId($donorId) {
        $query = "SELECT w.*, o.name as organ_name 
                  FROM witnesses w 
                  LEFT JOIN organs o ON w.organ_id = o.id 
                  WHERE w.donor_id = :donor_id 
                  ORDER BY (w.organ_id IS NULL) DESC, w.organ_id ASC, w.id ASC";
        $result = $this->query($query, [':donor_id' => $donorId]);
        return $result ? $result : [];
    }
    
    public function addOrganWitnesses($donorId, $organId, array $witnesses)
    {
        // Remove any existing organ-specific witnesses for this organ
        $this->query(
            "DELETE FROM witnesses WHERE donor_id = :donor_id AND organ_id = :organ_id",
            [':donor_id' => $donorId, ':organ_id' => $organId]
        );

        $saved = 0;
        foreach ($witnesses as $index => $data) {
            if (empty($data['nic'])) continue;

            $query = "INSERT INTO witnesses (
                        donor_id, organ_id, witness_number, name, nic_number, contact_number, address
                      ) VALUES (
                        :donor_id, :organ_id, :witness_number, :name, :nic, :phone, :address
                      )";
            $result = $this->insert($query, [
                ':donor_id'       => $donorId,
                ':organ_id'       => $organId,
                ':witness_number' => $index + 1,
                ':name'           => $data['name'] ?? '',
                ':nic'            => $data['nic'] ?? '',
                ':phone'          => $data['phone'] ?? null,
                ':address'        => $data['address'] ?? null,
            ]);
            if ($result) $saved++;
        }
        return $saved === count($witnesses);
    }

    public function updateWitness($witnessId, $donorId, $data) {
        $query = "UPDATE witnesses SET name = :name, nic_number = :nic, contact_number = :phone, address = :address 
                  WHERE id = :id AND donor_id = :donor_id";
        return $this->query($query, [
            ':id' => $witnessId,
            ':donor_id' => $donorId,
            ':name' => $data['name'],
            ':nic' => $data['nic'],
            ':phone' => $data['phone'],
            ':address' => $data['address'] ?? null
        ]);
    }

    public function deleteWitness($witnessId, $donorId) {
        $query = "DELETE FROM witnesses WHERE id = :id AND donor_id = :donor_id";
        return $this->query($query, [':id' => $witnessId, ':donor_id' => $donorId]);
    }

    public function countWitnessesByDonorId($donorId) {
        return $this->count(['donor_id' => $donorId]);
    }

    public function deleteWitnessesByOrganPledge($donorId, $organId)
    {
        $query = "DELETE FROM witnesses WHERE donor_id = :donor_id AND organ_id = :organ_id";
        return $this->query($query, [':donor_id' => $donorId, ':organ_id' => $organId]);
    }

    public function deleteAllOrganWitnesses($donorId)
    {
        $query = "DELETE FROM witnesses WHERE donor_id = :donor_id AND organ_id IS NOT NULL";
        return $this->query($query, [':donor_id' => $donorId]);
    }
}
