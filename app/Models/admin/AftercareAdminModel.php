<?php

namespace App\Models\admin;

use App\Core\Model;

class AftercareAdminModel {
    use Model;

    protected $table = 'aftercare_patients';

    /**
     * Get all post-surgery patients for the admin list with filtering support
     */
    public function getAllPatients($type = null, $blood = null, $search = null) {
        $params = [];
        $query = "SELECT a.id, a.user_id, a.patient_type,
                         COALESCE(r.registration_number, CONCAT('DN-', d.id)) as registration_number,
                         COALESCE(r.nic, d.nic_number) as nic,
                         COALESCE(r.full_name, CONCAT(d.first_name, ' ', d.last_name)) as full_name,
                         r.age,
                         COALESCE(r.blood_group, d.blood_group) as blood_group,
                         COALESCE(r.status, d.verification_status) as status,
                         r.hospital_registration_no,
                         COALESCE(r.created_at, d.created_at) as created_at
                  FROM {$this->table} a
                  JOIN users u ON a.user_id = u.id
                  LEFT JOIN recipient_patient r ON u.username = r.registration_number
                  LEFT JOIN donors d ON a.user_id = d.user_id
                  WHERE 1=1";

        if ($type) {
            $query .= " AND a.patient_type = :type";
            $params['type'] = strtoupper($type);
        }

        if ($blood) {
            $query .= " AND COALESCE(r.blood_group, d.blood_group) = :blood";
            $params['blood'] = $blood;
        }

        if ($search) {
            $query .= " AND (
                r.full_name LIKE :search 
                OR CONCAT(d.first_name, ' ', d.last_name) LIKE :search 
                OR r.registration_number LIKE :search 
                OR d.nic_number LIKE :search 
                OR r.nic LIKE :search
            )";
            $params['search'] = "%$search%";
        }

        $query .= " ORDER BY created_at DESC";
        
        $results = $this->query($query, $params);
        return $results ? $results : [];
    }

    /**
     * Get full details for a single patient
     */
    /**
     * Get full details for a single patient with selective table joining
     */
    public function getPatientById($id) {
        $query = "SELECT a.id, a.user_id, a.patient_type,
                         COALESCE(r.registration_number, CONCAT('DN-', d.id)) as registration_number,
                         COALESCE(r.nic, d.nic_number) as nic,
                         COALESCE(r.full_name, CONCAT(d.first_name, ' ', d.last_name)) as full_name,
                         COALESCE(r.blood_group, d.blood_group) as blood_group,
                         COALESCE(r.gender, d.gender) as gender,
                         r.age, r.contact_details, r.medical_details, r.surgery_type, r.surgery_date,
                         r.hospital_registration_no,
                         COALESCE(r.created_at, d.created_at) as created_at
                    FROM {$this->table} a
                    JOIN users u ON a.user_id = u.id
                    LEFT JOIN recipient_patient r ON u.username = r.registration_number
                    LEFT JOIN donors d ON a.user_id = d.user_id
                    WHERE a.id = :id LIMIT 1";
        
        $results = $this->query($query, ['id' => $id]);
        return $results ? $results[0] : null;
    }

}
