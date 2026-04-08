<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\SupportRequestModel;

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

        $model = new SupportRequestModel();
        $requests = $model->getAllRequests();
        $stats = $model->getStats();

        $this->view('admin/aftercareAdmin/aftercare', [
            'requests' => $requests,
            'stats' => $stats
        ]);
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
