<?php

namespace App\Models\admin;

use App\Core\Model;

class AftercareAdminModel {
    use Model;

    protected $table = 'aftercare_patients';

    /**
     * Get all post-surgery patients for the admin list
     * Excludes sensitive data like password_hash
     */
    public function getAllPatients() {
        $query = "SELECT a.id, a.registration_number, a.nic, a.full_name, a.patient_type, r.age, r.blood_group, a.status, a.hospital_registration_no, a.created_at 
                  FROM {$this->table} a
                  LEFT JOIN recipient_patient r ON a.registration_number = r.registration_number
                  ORDER BY a.created_at DESC";
        $results = $this->query($query);
        return $results ? $results : [];
    }

    /**
     * Get full details for a single patient
     */
    public function getPatientById($id) {
        $query = "SELECT a.id, a.registration_number, a.nic, a.full_name, a.patient_type, r.age, r.blood_group, a.status, 
                         a.hospital_registration_no, r.gender, r.contact_details, r.medical_details, a.created_at 
                   FROM {$this->table} a
                   LEFT JOIN recipient_patient r ON a.registration_number = r.registration_number
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
