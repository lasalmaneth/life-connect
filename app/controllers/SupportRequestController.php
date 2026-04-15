<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

/**
 * Support Request Controller
 * Handles CRUD operations for patient support requests
 */
class SupportRequestController {
    use Controller, Database;

    public function __construct() {
        // Set header for JSON response
        header('Content-Type: application/json');
    }

    /**
     * Get all support requests
     */
    public function getAll() {
        try {
            $query = "SELECT * FROM support_requests ORDER BY submitted_date DESC, created_at DESC";
            $results = $this->query($query);
            echo json_encode(['success' => true, 'data' => $results ?: []]);
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Get a single support request
     */
    public function getOne() {
        $id = isset($_GET['id']) ? intval($_GET['id']) : 0;
        
        if($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid request ID']);
            return;
        }
        
        try {
            $query = "SELECT * FROM support_requests WHERE id = :id";
            $result = $this->query($query, ['id' => $id]);
            
            if($result) {
                echo json_encode(['success' => true, 'data' => $result[0]]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Request not found']);
            }
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Create a new support request
     */
    public function create() {
        $patient_nic = isset($_POST['patient_nic']) ? trim($_POST['patient_nic']) : '';
        $patient_name = isset($_POST['patient_name']) ? trim($_POST['patient_name']) : '';
        $patient_type = isset($_POST['patient_type']) ? trim($_POST['patient_type']) : '';
        $reason = isset($_POST['reason']) ? trim($_POST['reason']) : '';
        $amountRaw = isset($_POST['amount']) ? trim((string)$_POST['amount']) : '';
        $amountRawNorm = str_replace([',', ' '], '', $amountRaw);
        $description = isset($_POST['description']) ? trim($_POST['description']) : '';
        
        // Validation
        if(empty($patient_nic) || empty($patient_name) || empty($patient_type) || empty($reason)) {
            echo json_encode(['success' => false, 'message' => 'Please fill all required fields']);
            return;
        }

        $amount = null;
        if ($amountRawNorm !== '') {
            if (!is_numeric($amountRawNorm)) {
                echo json_encode(['success' => false, 'message' => 'Invalid amount']);
                return;
            }
            $amountNum = (float)$amountRawNorm;
            if ($amountNum < 0) {
                echo json_encode(['success' => false, 'message' => 'Invalid amount']);
                return;
            }
            $amount = number_format($amountNum, 2, '.', '');
        }
        
        try {
            // Ensure optional support_requests.amount column exists
            try {
                $res = $this->query("SHOW COLUMNS FROM support_requests LIKE 'amount'");
                if (empty($res)) {
                    $con = $this->connect();
                    $con->exec("ALTER TABLE support_requests ADD COLUMN amount DECIMAL(10,2) NULL AFTER reason");
                }
            } catch (\Throwable $e) {
                // Ignore migration errors; insert will fail if blocking
            }

            $query = "INSERT INTO support_requests (patient_nic, patient_name, patient_type, reason, amount, description, status, submitted_date) VALUES (:patient_nic, :patient_name, :patient_type, :reason, :amount, :description, 'PENDING', CURDATE())";
            $data = [
                'patient_nic' => $patient_nic,
                'patient_name' => $patient_name,
                'patient_type' => $patient_type,
                'reason' => $reason,
                'amount' => $amount,
                'description' => $description
            ];
            
            $newId = $this->insert($query, $data);
            if ($newId) {
                echo json_encode(['success' => true, 'message' => 'Support request created successfully', 'id' => $newId]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to create support request']);
            }
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Approve a support request
     */
    public function approve() {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $reviewed_by = isset($_POST['reviewed_by']) ? trim($_POST['reviewed_by']) : 'Aftercare Admin';
        
        if($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid request ID']);
            return;
        }
        
        try {
            $query = "UPDATE support_requests SET status = 'APPROVED', reviewed_date = CURDATE(), reviewed_by = :reviewed_by WHERE id = :id";
            $this->query($query, ['reviewed_by' => $reviewed_by, 'id' => $id]);
            echo json_encode(['success' => true, 'message' => 'Support request approved successfully']);
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Reject a support request
     */
    public function reject() {
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $reviewed_by = isset($_POST['reviewed_by']) ? trim($_POST['reviewed_by']) : 'Aftercare Admin';
        
        if($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid request ID']);
            return;
        }
        
        try {
            $query = "UPDATE support_requests SET status = 'REJECTED', reviewed_date = CURDATE(), reviewed_by = :reviewed_by WHERE id = :id";
            $this->query($query, ['reviewed_by' => $reviewed_by, 'id' => $id]);
            echo json_encode(['success' => true, 'message' => 'Support request rejected']);
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Bulk approve support requests
     */
    public function bulkApprove() {
        $ids = isset($_POST['ids']) ? $_POST['ids'] : [];
        $reviewed_by = isset($_POST['reviewed_by']) ? trim($_POST['reviewed_by']) : 'Aftercare Admin';
        
        if(empty($ids) || !is_array($ids)) {
            echo json_encode(['success' => false, 'message' => 'No requests selected']);
            return;
        }
        
        try {
            $placeholders = '';
            $params = ['reviewed_by' => $reviewed_by];
            foreach ($ids as $index => $id) {
                $key = "id$index";
                $placeholders .= ":$key,";
                $params[$key] = $id;
            }
            $placeholders = rtrim($placeholders, ',');
            
            $query = "UPDATE support_requests SET status = 'APPROVED', reviewed_date = CURDATE(), reviewed_by = :reviewed_by WHERE id IN ($placeholders)";
            $this->query($query, $params);
            
            echo json_encode(['success' => true, 'message' => "Request(s) approved successfully"]);
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Bulk reject support requests
     */
    public function bulkReject() {
        $ids = isset($_POST['ids']) ? $_POST['ids'] : [];
        $reviewed_by = isset($_POST['reviewed_by']) ? trim($_POST['reviewed_by']) : 'Aftercare Admin';
        
        if(empty($ids) || !is_array($ids)) {
            echo json_encode(['success' => false, 'message' => 'No requests selected']);
            return;
        }
        
        try {
            $placeholders = '';
            $params = ['reviewed_by' => $reviewed_by];
            foreach ($ids as $index => $id) {
                $key = "id$index";
                $placeholders .= ":$key,";
                $params[$key] = $id;
            }
            $placeholders = rtrim($placeholders, ',');
            
            $query = "UPDATE support_requests SET status = 'REJECTED', reviewed_date = CURDATE(), reviewed_by = :reviewed_by WHERE id IN ($placeholders)";
            $this->query($query, $params);
            
            echo json_encode(['success' => true, 'message' => "Request(s) rejected"]);
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Search support requests
     */
    public function search() {
        $queryText = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if(empty($queryText)) {
            $this->getAll();
            return;
        }
        
        try {
            $searchTerm = "%$queryText%";
            $query = "SELECT * FROM support_requests WHERE patient_nic LIKE :q1 OR patient_name LIKE :q2 OR reason LIKE :q3 ORDER BY submitted_date DESC";
            $results = $this->query($query, ['q1' => $searchTerm, 'q2' => $searchTerm, 'q3' => $searchTerm]);
            echo json_encode(['success' => true, 'data' => $results ?: []]);
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
