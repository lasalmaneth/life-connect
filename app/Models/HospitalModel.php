<?php

namespace App\Models;

use App\Core\Model;

class HospitalModel {
    use Model;

    protected $table = 'hospitals'; // Updated to match new schema

    public function registerHospital($userId, $hospitalData, $cmoData)
    {
        $query = "INSERT INTO hospitals (
            user_id, registration_number, name, address, district, facility_type, 
            cmo_name, cmo_nic, medical_license_number, verification_status
        ) VALUES (
            :user_id, :reg_no, :name, :address, :district, :type, 
            :cmo_name, :cmo_nic, :license, 'PENDING'
        )";
        
        $params = [
            ':user_id' => $userId,
            ':reg_no' => $hospitalData['registration_number'],
            ':name' => $hospitalData['name'],
            ':address' => $hospitalData['address'],
            ':district' => $hospitalData['district'],
            ':type' => $hospitalData['type'],
            ':cmo_name' => $cmoData['name'],
            ':cmo_nic' => $cmoData['nic'],
            ':license' => $cmoData['license_number']
        ];
        
        $this->query($query, $params);
        return true;
    }

    public function hospitalRegNoExists($regNo)
    {
        $query = "SELECT COUNT(*) as count FROM hospitals WHERE registration_number = :reg_no";
        $result = $this->query($query, [':reg_no' => $regNo]);
        return $result && $result[0]->count > 0;
    }

    public function getAllHospitals()
    {
        $query = "SELECT * FROM hospitals WHERE verification_status = 'APPROVED' ORDER BY name ASC";
        return $this->query($query);
    }

    public function getHospitalByUserId($userId)
    {
        $query = "SELECT * FROM hospitals WHERE user_id = :user_id LIMIT 1";
        $results = $this->query($query, [':user_id' => $userId]);
        return $results ? $results[0] : false;
    }

    public function updateHospitalProfile($data)
    {
        // Store phone inside address field to avoid DB schema errors if 'phone' is missing
        $combinedAddress = "[Phone]: " . ($data['phone'] ?: 'None') . " | [Address]: " . ($data['address'] ?: '');

        $query = "UPDATE hospitals 
                  SET name = :name, address = :address 
                  WHERE registration_number = :reg_no";
        
        $params = [
            ':name' => $data['name'],
            ':address' => $combinedAddress,
            ':reg_no' => $data['registration']
        ];
        
        return $this->query($query, $params);
    }

    // Organ Requests
    public function getOrganRequests($regNo)
    {
        $query = "SELECT r.*, o.name as organ_name 
                  FROM organ_requests r 
                  JOIN organs o ON r.organ_id = o.id 
                  WHERE r.hospital_id = (SELECT id FROM hospitals WHERE registration_number = :reg_no) 
                  ORDER BY r.created_at DESC";
        return $this->query($query, [':reg_no' => $regNo]);
    }

    public function addOrganRequest($data)
    {
        $query = "INSERT INTO organ_requests (hospital_id, organ_id, priority_level, patient_details, status) 
                  VALUES (
                      (SELECT id FROM hospitals WHERE registration_number = :reg_no), 
                      (SELECT id FROM organs WHERE name = :organ_type LIMIT 1), 
                      :urgency, :notes, 'OPEN'
                  )";
        $this->query($query, [
            ':reg_no' => $data['registration_no'],
            ':organ_type' => $data['organ_type'],
            ':urgency' => strtoupper($data['urgency']),
            ':notes' => $data['notes']
        ]);
        return true;
    }

    public function updateOrganRequest($requestId, $data)
    {
        // Store both as a structured string in patient_details to avoid DB schema errors
        $combinedDetails = "[Reason]: " . ($data['urgency_reason'] ?: 'Not specified') . " | [Notes]: " . ($data['notes'] ?: '');
        
        $query = "UPDATE organ_requests 
                  SET priority_level = :urgency, 
                      patient_details = :details
                  WHERE id = :id";
        
        $params = [
            ':id' => $requestId,
            ':urgency' => strtoupper($data['urgency']),
            ':details' => $combinedDetails
        ];
        
        return $this->query($query, $params);
    }

    public function deleteOrganRequest($id)
    {
        $query = "DELETE FROM organ_requests WHERE id = :id";
        return $this->query($query, [':id' => $id]);
    }

    // Recipients
    public function getRecipients($regNo)
    {
        $query = "SELECT * FROM recipients WHERE hospital_registration_no = :reg_no ORDER BY created_at DESC";
        return $this->query($query, [':reg_no' => $regNo]);
    }

    public function addRecipient($data)
    {
        $query = "INSERT INTO recipients (nic, name, organ_received, surgery_date, treatment_notes, status, hospital_registration_no) 
                  VALUES (:nic, :name, :organ_received, :surgery_date, :treatment_notes, 'Active', :reg_no)";
        $this->query($query, [
            ':nic' => $data['nic'],
            ':name' => $data['name'],
            ':organ_received' => $data['organ_received'],
            ':surgery_date' => $data['surgery_date'],
            ':treatment_notes' => $data['treatment_notes'],
            ':reg_no' => $data['hospital_registration_no']
        ]);
        return true;
    }

    public function updateRecipient($data)
    {
        $query = "UPDATE recipients SET nic = :nic, name = :name, organ_received = :organ_received, 
                  surgery_date = :surgery_date, treatment_notes = :treatment_notes, status = :status, updated_at = NOW() 
                  WHERE recipient_id = :id";
        $this->query($query, [
            ':nic' => $data['nic'],
            ':name' => $data['name'],
            ':organ_received' => $data['organ_received'],
            ':surgery_date' => $data['surgery_date'],
            ':treatment_notes' => $data['treatment_notes'],
            ':status' => $data['status'],
            ':id' => $data['recipient_id']
        ]);
        return true;
    }

    public function deleteRecipient($id)
    {
        $query = "DELETE FROM recipients WHERE recipient_id = :id";
        $this->query($query, [':id' => $id]);
        return true;
    }

    // Success Stories
    public function getSuccessStories($regNo)
    {
        $query = "SELECT s.* 
                  FROM success_stories s
                  JOIN hospitals h ON h.user_id = s.user_id
                  WHERE h.registration_number = :reg_no
                  ORDER BY s.created_at DESC";
        return $this->query($query, [':reg_no' => $regNo]);
    }

    public function addSuccessStory($data)
    {
        $query = "INSERT INTO success_stories (title, description, success_date, user_id, status) 
                  VALUES (:title, :description, :success_date, 
                      (SELECT user_id FROM hospitals WHERE registration_number = :reg_no LIMIT 1), 
                      'Pending')";
        $this->query($query, [
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':success_date' => $data['success_date'],
            ':reg_no' => $data['hospital_registration_no']
        ]);
        return true;
    }

    public function updateSuccessStory($data)
    {
        $query = "UPDATE success_stories SET title = :title, description = :description, 
                  success_date = :success_date, status = :status, updated_at = NOW() 
                  WHERE story_id = :id";
        $this->query($query, [
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':success_date' => $data['success_date'],
            ':status' => $data['status'],
            ':id' => $data['story_id']
        ]);
        return true;
    }

    public function deleteSuccessStory($id)
    {
        $query = "DELETE FROM success_stories WHERE story_id = :id";
        $this->query($query, [':id' => $id]);
        return true;
    }

    // Aftercare Appointments
    public function getAftercareAppointments($regNo)
    {
        $query = "SELECT * FROM aftercare_appointments WHERE hospital_registration_no = :reg_no ORDER BY appointment_date ASC";
        return $this->query($query, [':reg_no' => $regNo]);
    }

    // Upcoming Appointments (using upcoming_appointments table)
    public function getLabReports($regNo)
    {
        $query = "SELECT ua.id as appointment_id, ua.*, 
                  COALESCE(CONCAT(d.first_name, ' ', d.last_name), 'N/A') as donor_name,
                  COALESCE(d.nic_number, 'N/A') as donor_nic,
                  COALESCE(ua.donor_id, 'N/A') as patient_id
                  FROM upcoming_appointments ua
                  LEFT JOIN donors d ON ua.donor_id = d.id
                  WHERE ua.hospital_registration_no = :reg_no
                  ORDER BY ua.test_date DESC";
        
        $results = $this->query($query, [':reg_no' => $regNo]);
        return $results;
    }

    public function addLabReport($data)
    {
        $query = "INSERT INTO upcoming_appointments (donor_id, hospital_registration_no, test_type, test_date, status, notes)
                  VALUES (:donor_id, :reg_no, :test_type, :test_date, :status, :notes)";
        
        $params = [
            ':donor_id' => $data['donor_id'] ?? null,
            ':reg_no' => $data['hospital_registration_no'],
            ':test_type' => $data['test_type'],
            ':test_date' => $data['test_date'],
            ':status' => $data['result_status'],
            ':notes' => $data['result_notes'] ?? ''
        ];
        
        $this->query($query, $params);
        return true;
    }

    public function updateLabReport($reportId, $data)
    {
        $query = "UPDATE upcoming_appointments 
                  SET donor_id = :donor_id, 
                      test_type = :test_type, 
                      test_date = :test_date, 
                      status = :status, 
                      notes = :notes
                  WHERE id = :id";
        
        $params = [
            ':id' => $reportId,
            ':donor_id' => $data['donor_id'] ?? null,
            ':test_type' => $data['test_type'],
            ':test_date' => $data['test_date'],
            ':status' => $data['result_status'],
            ':notes' => $data['result_notes'] ?? ''
        ];
        
        $this->query($query, $params);
        return true;
    }

    public function deleteLabReport($reportId)
    {
        $query = "DELETE FROM upcoming_appointments WHERE id = :id";
        $this->query($query, [':id' => $reportId]);
        return true;
    }

    public function updateLabReportStatus($reportId, $status)
    {
        $query = "UPDATE upcoming_appointments SET status = :status WHERE id = :id";
        $this->query($query, [':id' => $reportId, ':status' => $status]);
        return true;
    }
}
