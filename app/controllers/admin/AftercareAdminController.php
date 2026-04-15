<?php

namespace App\Controllers\admin;

use App\Core\Controller;
use App\Models\SupportRequestModel;
use App\Models\admin\AftercareAdminModel;

class AftercareAdminController {
    use Controller;

    /**
     * Display the Aftercare Admin Dashboard
     */
    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        // Ensure user is an Aftercare Admin
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'AC_ADMIN') {
            redirect('login');
        }

        $requestModel = new SupportRequestModel();
        $adminModel = new AftercareAdminModel();

        // Fetch ALL requests by default, or you can keep it as 'PENDING' if you want the filter to default to pending
        // The user said "SHOW ALL THE SUPPORT REQUESTS. alll statuses.", so we load all.
        $requests = $requestModel->getAllRequests(); 
        $supportStats = $requestModel->getStats();
        $patientStats = $adminModel->getPatientStats();

        // Merge stats for the view
        $stats = array_merge($supportStats, $patientStats);

        $this->view('admin/aftercareAdmin/aftercare', [
            'requests' => $requests,
            'stats' => $stats
        ]);
    }

    /**
     * AJAX endpoint: Filter support requests
     */
    public function filterSupportRequests() {
        header('Content-Type: application/json');
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'AC_ADMIN') {
                throw new \Exception("Unauthorized access");
            }

            $status = $_GET['status'] ?? '';
            $search = $_GET['search'] ?? '';

            $model = new SupportRequestModel();
            
            // Basic filtering logic
            $query = "SELECT * FROM support_requests WHERE 1=1";
            $params = [];

            if (!empty($status)) {
                $query .= " AND status = :status";
                $params[':status'] = strtoupper($status);
            }

            if (!empty($search)) {
                $query .= " AND (patient_name LIKE :search OR patient_nic LIKE :search OR reason LIKE :search)";
                $params[':search'] = "%$search%";
            }

            $query .= " ORDER BY created_at DESC";
            
            $requests = $model->query($query, $params);
            
            echo json_encode(['success' => true, 'requests' => $requests ? $requests : []]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * AJAX endpoint: Get all aftercare patients
     */
    public function getPatients() {
        header('Content-Type: application/json');
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'AC_ADMIN') {
                throw new \Exception("Unauthorized access");
            }

            $model = new AftercareAdminModel();
            $patients = $model->getAllPatients();
            echo json_encode(['success' => true, 'patients' => $patients]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * AJAX endpoint: Get single patient details
     */
    public function getPatientDetails() {
        header('Content-Type: application/json');
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'AC_ADMIN') {
                throw new \Exception("Unauthorized access");
            }

            $id = $_GET['id'] ?? null;
            if (!$id) throw new \Exception("Patient ID is required");

            $model = new AftercareAdminModel();
            $patient = $model->getPatientById($id);

            if ($patient) {
                echo json_encode(['success' => true, 'patient' => $patient]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Patient not found']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * AJAX endpoint: Update support status
     */
    public function updateSupportStatus() {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $id = $_POST['id'] ?? null;
                $status = $_POST['status'] ?? null;
                $reviewer = $_SESSION['username'] ?? 'Aftercare Admin';

                if ($id && $status) {
                    $model = new SupportRequestModel();
                    $finalStatus = (strtolower($status) === 'approved') ? 'VERIFIED' : 'REJECTED';
                    $model->updateStatus($id, $finalStatus, $reviewer);
                    echo json_encode(['success' => true]);
                    return;
                }
            } catch (\Exception $e) {
                error_log("AftercareAdminController error: " . $e->getMessage());
                echo json_encode(['success' => false, 'message' => $e->getMessage()]);
                return;
            }
        }
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }

    /**
     * Handle Approve/Reject actions via traditional form POST
     */
    public function handleAction() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['request_id']) && isset($_POST['action'])) {
            // Ensure user is an Aftercare Admin
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'AC_ADMIN') {
                redirect('login');
            }

            $id = $_POST['request_id'];
            $action = $_POST['action']; // 'verified' or 'rejected'
            $reviewer = $_SESSION['username'] ?? 'Aftercare Admin';

            $model = new SupportRequestModel();
            $status = ($action === 'approved') ? 'VERIFIED' : 'REJECTED'; 
            $model->updateStatus($id, $status, $reviewer);

            // Redirect back to dashboard
            redirect('aftercare-admin');
        } else {
            redirect('aftercare-admin');
        }
    }
}
