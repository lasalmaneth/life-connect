<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

/**
 * Appointment Controller
 * Handles CRUD operations for aftercare appointments
 */
class AppointmentController {
    use Controller, Database;

    public function __construct() {
        // Set header for JSON response as most methods return JSON
        header('Content-Type: application/json');
    }

    /**
     * Get all appointments
     */
    public function getAll() {
        try {
            $query = "SELECT * FROM aftercare_appointments ORDER BY appointment_date DESC";
            $results = $this->query($query);
            echo json_encode(['success' => true, 'data' => $results ?: []]);
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get a single appointment
     */
    public function getOne() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid appointment ID']);
            return;
        }
        
        try {
            $query = "SELECT * FROM aftercare_appointments WHERE appointment_id = :id";
            $result = $this->query($query, ['id' => $id]);
            
            if($result) {
                echo json_encode(['success' => true, 'data' => $result[0]]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Appointment not found']);
            }
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Create a new appointment
     */
    public function create() {
        $patient_id = isset($_POST['patient_id']) ? trim($_POST['patient_id']) : '';
        $patient_name = isset($_POST['patient_name']) ? trim($_POST['patient_name']) : '';
        $appointment_date = isset($_POST['appointment_date']) ? trim($_POST['appointment_date']) : '';
        $appointment_type = isset($_POST['appointment_type']) ? trim($_POST['appointment_type']) : 'Follow-up';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        
        // Validation
        if(empty($patient_id) || empty($patient_name) || empty($appointment_date)) {
            echo json_encode(['success' => false, 'message' => 'Please fill all required fields']);
            return;
        }
        
        try {
            $query = "INSERT INTO aftercare_appointments (patient_id, patient_name, appointment_date, appointment_type, description, status) VALUES (:patient_id, :patient_name, :appointment_date, :appointment_type, :description, 'Scheduled')";
            $data = [
                'patient_id' => $patient_id,
                'patient_name' => $patient_name,
                'appointment_date' => $appointment_date,
                'appointment_type' => $appointment_type,
                'description' => $description
            ];
            
            $newId = $this->insert($query, $data);
            if ($newId) {
                echo json_encode(['success' => true, 'message' => 'Appointment created successfully', 'id' => $newId]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create appointment']);
            }
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Update an appointment
     */
    public function update() {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $patient_id = isset($_POST['patient_id']) ? trim($_POST['patient_id']) : '';
        $patient_name = isset($_POST['patient_name']) ? trim($_POST['patient_name']) : '';
        $appointment_date = isset($_POST['appointment_date']) ? trim($_POST['appointment_date']) : '';
        $appointment_type = isset($_POST['appointment_type']) ? trim($_POST['appointment_type']) : '';
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        $status = isset($_POST['status']) ? trim($_POST['status']) : '';
        
        if($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid appointment ID']);
            return;
        }
        
        try {
            $query = "UPDATE aftercare_appointments SET patient_id = :patient_id, patient_name = :patient_name, appointment_date = :appointment_date, appointment_type = :appointment_type, description = :description, status = :status WHERE appointment_id = :id";
            $data = [
                'patient_id' => $patient_id,
                'patient_name' => $patient_name,
                'appointment_date' => $appointment_date,
                'appointment_type' => $appointment_type,
                'description' => $description,
                'status' => $status,
                'id' => $id
            ];
            
            $this->query($query, $data);
            echo json_encode(['success' => true, 'message' => 'Appointment updated successfully']);
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Cancel an appointment
     */
    public function cancel() {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        
        if($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid appointment ID']);
            return;
        }
        
        try {
            $query = "UPDATE aftercare_appointments SET status = 'Cancelled' WHERE appointment_id = :id";
            $this->query($query, ['id' => $id]);
            echo json_encode(['success' => true, 'message' => 'Appointment cancelled successfully']);
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Mark appointment as completed
     */
    public function complete() {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        
        if($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid appointment ID']);
            return;
        }
        
        try {
            $query = "UPDATE aftercare_appointments SET status = 'Completed' WHERE appointment_id = :id";
            $this->query($query, ['id' => $id]);
            echo json_encode(['success' => true, 'message' => 'Appointment marked as completed']);
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Search appointments
     */
    public function search() {
        $queryText = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if(empty($queryText)) {
            $this->getAll();
            return;
        }
        
        try {
            $searchTerm = "%$queryText%";
            $query = "SELECT * FROM aftercare_appointments WHERE patient_id LIKE :q1 OR patient_name LIKE :q2 ORDER BY appointment_date DESC";
            $results = $this->query($query, ['q1' => $searchTerm, 'q2' => $searchTerm]);
            echo json_encode(['success' => true, 'data' => $results ?: []]);
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Search patient by NIC from donors and recipients tables
     */
    public function searchPatient() {
        $nic = isset($_GET['nic']) ? trim($_GET['nic']) : '';
        
        if(empty($nic)) {
            echo json_encode(['success' => false, 'message' => 'Please enter NIC number']);
            return;
        }
        
        try {
            $searchTerm = "%$nic%";
            $results = [];
            
            // Search in donors table
            $queryDonors = "SELECT nic_number as nic, CONCAT(first_name, ' ', last_name) as name, 'Donor' as type FROM donors WHERE nic_number LIKE :nic LIMIT 10";
            $donors = $this->query($queryDonors, ['nic' => $searchTerm]);
            if ($donors) $results = array_merge($results, $donors);
            
            // Search in recipients table
            $queryRecipients = "SELECT nic, name, 'Recipient' as type FROM recipients WHERE nic LIKE :nic LIMIT 10";
            $recipients = $this->query($queryRecipients, ['nic' => $searchTerm]);
            if ($recipients) $results = array_merge($results, $recipients);
            
            // Search in live_organ_donor table if exists
            try {
                $queryLive = "SELECT nic, CONCAT(first_name, ' ', last_name) as name, 'Live Donor' as type FROM live_organ_donor WHERE nic LIKE :nic LIMIT 10";
                $liveDonors = $this->query($queryLive, ['nic' => $searchTerm]);
                if ($liveDonors) $results = array_merge($results, $liveDonors);
            } catch(\Exception $e) {
                // Table might not exist, ignore
            }
            
            if(count($results) > 0) {
                echo json_encode(['success' => true, 'data' => $results]);
            } else {
                echo json_encode(['success' => false, 'message' => 'No patient found with this NIC', 'data' => []]);
            }
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Filter appointments by type and status
     */
    public function filter() {
        $type = isset($_GET['type']) ? trim($_GET['type']) : '';
        $status = isset($_GET['status']) ? trim($_GET['status']) : '';
        
        try {
            $sql = "SELECT * FROM aftercare_appointments WHERE 1=1";
            $params = [];
            
            if(!empty($type)) {
                $sql .= " AND appointment_type = :type";
                $params['type'] = $type;
            }
            
            if(!empty($status)) {
                $sql .= " AND status = :status";
                $params['status'] = $status;
            }
            
            $sql .= " ORDER BY appointment_date DESC";
            
            $results = $this->query($sql, $params);
            echo json_encode(['success' => true, 'data' => $results ?: []]);
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
