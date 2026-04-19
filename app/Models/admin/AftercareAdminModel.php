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
        $query = "SELECT a.id, a.registration_number, a.nic, a.full_name, a.patient_type, r.age, r.blood_group, a.status, a.hospital_registration_no, a.created_at 
                  FROM {$this->table} a
                  LEFT JOIN recipient_patient r ON a.registration_number = r.registration_number
                  WHERE 1=1";

        if ($type) {
            $query .= " AND a.patient_type = :type";
            $params['type'] = strtoupper($type);
        }

        if ($blood) {
            $query .= " AND r.blood_group = :blood";
            $params['blood'] = $blood;
        }

        if ($search) {
            $query .= " AND (a.full_name LIKE :search OR a.registration_number LIKE :search OR a.nic LIKE :search)";
            $params['search'] = "%$search%";
        }

        $query .= " ORDER BY a.created_at DESC";
        
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
        $query = "SELECT a.*, 
                         r.age as r_age, r.gender as r_gender, r.blood_group as r_blood, 
                         r.contact_details, r.medical_details, r.surgery_type, r.surgery_date,
                         d.blood_group as d_blood, d.gender as d_gender
                   FROM {$this->table} a
                   LEFT JOIN recipient_patient r ON a.registration_number = r.registration_number
                   LEFT JOIN donors d ON a.user_id = d.user_id
                   WHERE a.id = :id LIMIT 1";
        
        $results = $this->query($query, ['id' => $id]);
        if (!$results) return null;

        $p = $results[0];
        
        // Normalize fields based on patient type
        if ($p->patient_type === 'RECIPIENT') {
            $p->age = $p->r_age;
            $p->gender = $p->r_gender;
            $p->blood_group = $p->r_blood;
        } else {
            $p->gender = $p->d_gender;
            $p->blood_group = $p->d_blood;
            $p->age = null; // Donors don't have age in this specific join requirement
        }

        return $p;
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
