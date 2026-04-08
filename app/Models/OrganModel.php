<?php

namespace App\Models;

use App\Core\Database;

class OrganModel {
    use Database;

    protected $table = 'organs';

    public function getAllAvailableOrgans()
    {
        $query = "SELECT * FROM organs WHERE is_available = 1";
        return $this->query($query);
    }

    public function getOrganIdByName($organName)
    {
        $query = "SELECT id FROM organs WHERE name = :name LIMIT 1";
        $result = $this->query($query, [':name' => $organName]);
        return $result ? $result[0]->id : null;
    }

    public function addDonorPledge($donorId, $organId, $details = [])
    {
        // Check if already pledged
        $check = "SELECT id FROM donor_pledges WHERE donor_id = :donor_id AND organ_id = :organ_id";
        $exists = $this->query($check, [':donor_id' => $donorId, ':organ_id' => $organId]);
        
        if ($exists) {
            return true; // Already pledged
        }

        $conditions = $details['conditions'] ?? null;
        $medications = $details['medications'] ?? null;
        $allergies = $details['allergies'] ?? null;
        $hospitalId = !empty($details['hospital_id']) ? (int)$details['hospital_id'] : null;

        $query = "INSERT INTO donor_pledges (donor_id, organ_id, pledge_date, status, conditions, medications, allergies, preferred_hospital_id) 
                  VALUES (:donor_id, :organ_id, NOW(), 'PENDING', :conditions, :medications, :allergies, :hospital_id)";
        
        $this->insert($query, [
            ':donor_id' => $donorId,
            ':organ_id' => $organId,
            ':conditions' => $conditions,
            ':medications' => $medications,
            ':allergies' => $allergies,
            ':hospital_id' => $hospitalId
        ]);
        return true;
    }

    public function removeDonorPledge($donorId, $organId)
    {
        $query = "UPDATE donor_pledges SET status = 'WITHDRAWN' WHERE donor_id = :donor_id AND organ_id = :organ_id";
        return $this->query($query, [
            ':donor_id' => $donorId,
            ':organ_id' => $organId
        ]);
    }
    public function getPledgeHistory($donorId)
    {
        $query = "SELECT dp.*, o.name as organ_name 
                  FROM donor_pledges dp 
                  JOIN organs o ON dp.organ_id = o.id 
                  WHERE dp.donor_id = :donor_id 
                  ORDER BY dp.pledge_date DESC";
        return $this->query($query, [':donor_id' => $donorId]);
    }

    public function getUploadedPledges($donorId)
    {
        $query = "SELECT dp.*, o.name as organ_name 
                  FROM donor_pledges dp 
                  JOIN organs o ON dp.organ_id = o.id 
                  WHERE dp.donor_id = :donor_id AND dp.signed_form_path IS NOT NULL
                  ORDER BY dp.pledge_date DESC";
        return $this->query($query, [':donor_id' => $donorId]);
    }
}

