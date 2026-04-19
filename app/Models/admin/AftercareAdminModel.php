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
                  LEFT JOIN recipient_patient r ON a.user_id = r.user_id
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
                    LEFT JOIN recipient_patient r ON a.user_id = r.user_id
                    LEFT JOIN donors d ON a.user_id = d.user_id
                    WHERE a.id = :id LIMIT 1";
        
        $results = $this->query($query, ['id' => $id]);
        return $results ? $results[0] : null;
    }

    /**
     * Get statistics specifically for the aftercare admin dashboard
     */
    public function getPatientStats() {
        $stats = [
            'total_patients' => 0,
            'recipient_patients' => 0,
            'donor_patients' => 0,
            'average_age' => 0
        ];

        // Total Counts
        $res = $this->query("SELECT patient_type, COUNT(*) as count FROM {$this->table} GROUP BY patient_type");
        if ($res) {
            foreach ($res as $row) {
                $count = (int)$row->count;
                $stats['total_patients'] += $count;
                if ($row->patient_type === 'RECIPIENT') $stats['recipient_patients'] = $count;
                if ($row->patient_type === 'DONOR') $stats['donor_patients'] = $count;
            }
        }

        // Average Age (calculated from recipient_patient table)
        $resAge = $this->query("SELECT AVG(age) as avg_age FROM recipient_patient WHERE age IS NOT NULL");
        if ($resAge) {
            $stats['average_age'] = round($resAge[0]->avg_age ?? 0);
        }

        return $stats;
    }
}
