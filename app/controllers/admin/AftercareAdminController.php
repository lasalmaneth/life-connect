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
            $action = $_POST['action']; // 'approved' or 'rejected'
            $reviewer = $_SESSION['username'] ?? 'Admin';

            $model = new SupportRequestModel();
            $model->updateStatus($id, $action, $reviewer);

            // Redirect back to dashboard
            redirect('aftercare-admin');
        } else {
            redirect('aftercare-admin');
        }
    }
}
