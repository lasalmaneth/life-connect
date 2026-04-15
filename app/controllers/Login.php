<?php 

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AftercarePatientModel;
use App\Models\LoginModel;

class Login {
    use Controller;

    public function index() {
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Expires: 0');
        $this->view('login');
    }

    public function register() {
        $this->view('register');
    }

    public function verify() {
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

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

            if ($status === 'SUSPENDED') {
                $reason = !empty($user->review_message) ? " Reason: " . $user->review_message : " Please contact support.";
                echo json_encode([
                    'success' => false,
                    'message' => 'Your account has been suspended.' . $reason
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
                'role' => $user->role,
                'must_change_credentials' => !empty($user->must_change_credentials) ? 1 : 0
            ]);

            return;
        }

        // Fallback: Aftercare Recipient login via main login page
        // Username = registration_number (e.g., REG-2026-0001)
        $looksLikeAftercareReg = (bool)preg_match('/^REG-\d{4}-\d{4}$/', $username);
        if ($looksLikeAftercareReg) {
            $aftercareModel = new AftercarePatientModel();
            $patient = $aftercareModel->getByRegistrationNumber($username);

            if (
                $patient &&
                !empty($patient->password_hash) &&
                password_verify($password, (string)$patient->password_hash) &&
                strtoupper((string)($patient->patient_type ?? '')) === 'RECIPIENT' &&
                strtoupper((string)($patient->status ?? 'ACTIVE')) === 'ACTIVE'
            ) {
                session_regenerate_id(true);

                // Clear main-auth session keys to avoid mixing contexts
                unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['role'], $_SESSION['status']);
                unset($_SESSION['donor_id'], $_SESSION['hospital_id'], $_SESSION['school_id']);

                // Set Aftercare session keys
                $_SESSION['aftercare_patient_id'] = (int)$patient->id;
                $_SESSION['aftercare_registration_number'] = (string)$patient->registration_number;
                $_SESSION['aftercare_must_change_password'] = !empty($patient->must_change_password) ? 1 : 0;

                session_write_close();

                echo json_encode([
                    'success' => true,
                    'role' => 'AFTERCARE_PATIENT'
                ]);
                return;
            }
        }

        echo json_encode([
            'success' => false,
            'message' => 'Invalid credentials'
        ]);
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_unset();
        session_destroy();
        redirect('login');
    }
}