<?php 

namespace App\Controllers;

use App\Core\Controller;
use App\Models\LoginModel;

class Login {
    use Controller;

    public function index() {
        $this->view('login');
    }

    public function register() {
        $this->view('register');
    }

    public function verify() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        if (!$username || !$password) {
            echo json_encode(['success' => false, 'message' => 'Username and password required']);
            return;
        }

        $loginModel = new LoginModel();
        $user = $loginModel->getUserByUsername($username);
        // show($user); // Removed debugging line to fix JSON response

        if ($user && password_verify($password, $user->password_hash)) {
            // Check status constraints
            $status = strtoupper($user->status ?? 'ACTIVE');
            
            if ($status === 'PENDING') {
                echo json_encode([
                    'success' => false,
                    'message' => 'Your registration is still under admin review.'
                ]);
                return;
            }
            
            if ($status === 'REJECTED') {
                $reason = !empty($user->review_message) ? " Reason: " . $user->review_message : "";
                echo json_encode([
                    'success' => false,
                    'message' => 'Your registration was rejected.' . $reason
                ]);
                return;
            }

            // Prevent session fixation and clear stale data from previous users
            session_regenerate_id(true);
            
            // Clear role-specific variables just in case
            unset($_SESSION['donor_id']);
            unset($_SESSION['hospital_id']);
            unset($_SESSION['school_id']);

            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['role'] = $user->role;
            $_SESSION['status'] = $user->status;

            session_write_close(); // Ensure session data is saved before response

            echo json_encode([
                'success' => true,
                'role' => $user->role
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid credentials'
            ]);
        }
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        redirect('login');
    }
}