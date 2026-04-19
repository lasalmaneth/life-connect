<?php

namespace App\Models;

use App\Core\Model;

class MedicalHistoryModel
{
    use Model;

    protected $table = 'test_results';

    /**
     * Get medical history (test results) for a recipient patient by NIC
     * Links test results with hospital information
     * 
     * @param string $patientNic - Patient NIC number
     * @return array - Array of test results with hospital details
     */
    public function getMedicalHistoryByNIC($patientNic)
    {
        $query = "
            SELECT 
                tr.id,
                tr.test_name,
                tr.status as result_value,
                tr.test_date,
                tr.document_path,
                h.id as hospital_id,
                h.name as hospital_name,
                h.address as hospital_address,
                h.contact_number as hospital_phone,
                rp.registration_number,
                rp.full_name as patient_name
            FROM test_results tr
            LEFT JOIN hospitals h ON tr.verified_by_hospital_id = h.id
            LEFT JOIN aftercare_patients ap ON tr.donor_id = ap.id
            LEFT JOIN recipient_patient rp ON ap.user_id = rp.user_id
            WHERE rp.nic = :nic OR (SELECT COUNT(*) FROM aftercare_patients ap2 JOIN recipient_patient rp2 ON ap2.user_id = rp2.user_id WHERE ap2.id = tr.donor_id AND rp2.nic = :nic) > 0
            ORDER BY tr.test_date DESC
        ";
        
        return $this->query($query, [':nic' => $patientNic]) ?: [];
    }

    /**
     * Get medical history using recipient ID
     * 
     * @param int $recipientId - Recipient patient ID
     * @return array - Array of test results
     */
    public function getMedicalHistoryByRecipientId($recipientId)
    {
        $query = "
            SELECT 
                tr.id,
                tr.test_name,
                tr.status as result_value,
                tr.test_date,
                tr.document_path,
                h.id as hospital_id,
                h.name as hospital_name,
                h.address as hospital_address,
                h.contact_number as hospital_phone,
                rp.registration_number,
                rp.full_name as patient_name
            FROM test_results tr
            LEFT JOIN hospitals h ON tr.verified_by_hospital_id = h.id
            LEFT JOIN aftercare_patients ap ON tr.donor_id = ap.id
            LEFT JOIN recipient_patient rp ON ap.user_id = rp.user_id
            WHERE tr.donor_id = :id
            ORDER BY tr.test_date DESC
        ";
        
        return $this->query($query, [':id' => $recipientId]) ?: [];
    }

    /**
     * Get recent medical history (last N months)
     * 
     * @param string $patientNic - Patient NIC
     * @param int $months - Number of months to look back (default 12)
     * @return array - Recent test results
     */
    public function getRecentMedicalHistory($patientNic, $months = 12)
    {
        $query = "
            SELECT 
                tr.id,
                tr.test_name,
                tr.status as result_value,
                tr.test_date,
                tr.document_path,
                h.id as hospital_id,
                h.name as hospital_name,
                h.address as hospital_address,
                DATEDIFF(CURDATE(), tr.test_date) as days_ago
            FROM test_results tr
            LEFT JOIN hospitals h ON tr.verified_by_hospital_id = h.id
            LEFT JOIN aftercare_patients ap ON tr.donor_id = ap.id
            LEFT JOIN recipient_patient rp ON ap.user_id = rp.user_id
            WHERE rp.nic = :nic 
            AND tr.test_date >= DATE_SUB(CURDATE(), INTERVAL :months MONTH)
            ORDER BY tr.test_date DESC
        ";
        
        return $this->query($query, [':nic' => $patientNic, ':months' => $months]) ?: [];
    }

    /**
     * Get medical history grouped by test type
     * Useful for showing trending data
     * 
     * @param string $patientNic - Patient NIC
     * @return array - Grouped medical history
     */
    public function getMedicalHistoryGrouped($patientNic)
    {
        $query = "
            SELECT 
                tr.test_name,
                COUNT(*) as test_count,
                MAX(tr.test_date) as last_test_date,
                GROUP_CONCAT(DISTINCT h.name SEPARATOR ', ') as hospitals
            FROM test_results tr
            LEFT JOIN hospitals h ON tr.verified_by_hospital_id = h.id
            LEFT JOIN aftercare_patients ap ON tr.donor_id = ap.id
            LEFT JOIN recipient_patient rp ON ap.user_id = rp.user_id
            WHERE rp.nic = :nic
            GROUP BY tr.test_name
            ORDER BY last_test_date DESC
        ";
        
        return $this->query($query, [':nic' => $patientNic]) ?: [];
    }

    /**
     * Add a new medical record
     * 
     * @param array $data - Test result data
     * @return bool - Success status
     */
    public function addTestResult($data)
    {
        $query = "
            INSERT INTO test_results (donor_id, test_name, status, notes, document_path, test_date, verified_by_hospital_id)
            VALUES (:donor_id, :test_name, :result_value, '', :document_path, :test_date, :hospital_id)
        ";
        
        return $this->query($query, [
            ':donor_id' => $data['donor_id'] ?? null,
            ':test_name' => $data['test_name'] ?? '',
            ':result_value' => $data['result_value'] ?? null,
            ':document_path' => $data['document_path'] ?? null,
            ':test_date' => $data['test_date'] ?? date('Y-m-d'),
            ':hospital_id' => $data['hospital_id'] ?? null,
        ]);
    }

    /**
     * Get hospital information for a medical record
     * 
     * @param int $hospitalId - Hospital ID
     * @return stdClass - Hospital details
     */
    public function getHospitalInfo($hospitalId)
    {
        $query = "
            SELECT id, name AS hospital_name, address, contact_number AS phone
            FROM hospitals
            WHERE id = :id
            LIMIT 1
        ";
        
        $result = $this->query($query, [':id' => $hospitalId]);
        return $result ? $result[0] : null;
    }

    /**
     * Export medical history as array for reporting
     * 
     * @param string $patientNic - Patient NIC
     * @return array - Medical history for export
     */
    public function exportMedicalHistory($patientNic)
    {
        $history = $this->getMedicalHistoryByNIC($patientNic);
        return array_map(function($record) {
            return [
                'date' => $record->test_date ?? 'N/A',
                'test_name' => $record->test_name ?? 'N/A',
                'result' => $record->result_value ?? 'N/A',
                'hospital' => $record->hospital_name ?? 'Unknown Hospital',
                'document' => $record->document_path ?? 'N/A',
            ];
        }, $history);
    }
}
