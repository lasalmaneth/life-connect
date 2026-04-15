<?php 

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AftercarePatientModel;
use App\Models\LoginModel;

class Login {
    use Controller;

    private function ensureCustodianProfileForUser(object $user): void
    {
        try {
            $role = strtoupper((string)($user->role ?? ''));
            if ($role !== 'CUSTODIAN') {
                return;
            }

            $username = trim((string)($user->username ?? ''));
            if ($username === '') {
                return;
            }

            $db = new class {
                use \App\Core\Database;
            };

            $existing = $db->query(
                "SELECT id FROM custodians WHERE user_id = :uid LIMIT 1",
                [':uid' => (int)$user->id]
            );
            if ($existing) {
                return;
            }

            // Try to infer donor linkage from sworn statements (stores relation NICs in JSON form_data)
            $rows = $db->query(
                "SELECT ss.form_data, ss.created_at, dc.donor_id
                 FROM sworn_statements ss
                 JOIN donation_cases dc ON dc.id = ss.donation_case_id
                 WHERE ss.form_data LIKE :needle
                 ORDER BY ss.created_at DESC
                 LIMIT 1",
                [':needle' => '%' . $username . '%']
            );

            if (!$rows) {
                return;
            }

            $row = $rows[0];
            $donorId = (int)($row->donor_id ?? 0);
            if ($donorId <= 0) {
                return;
            }

            $name = $username;
            $relationship = 'Family';
            $form = json_decode((string)($row->form_data ?? ''), true);
            if (is_array($form)) {
                $nics = $form['relations_nic'] ?? null;
                $names = $form['relations_name'] ?? null;
                $rels = $form['relations_rel'] ?? null;

                if (is_array($nics)) {
                    $matchIndex = null;
                    foreach ($nics as $i => $nic) {
                        if (trim((string)$nic) === $username) {
                            $matchIndex = $i;
                            break;
                        }
                    }

                    if ($matchIndex !== null) {
                        if (is_array($names) && isset($names[$matchIndex]) && trim((string)$names[$matchIndex]) !== '') {
                            $name = trim((string)$names[$matchIndex]);
                        }
                        if (is_array($rels) && isset($rels[$matchIndex]) && trim((string)$rels[$matchIndex]) !== '') {
                            $relationship = trim((string)$rels[$matchIndex]);
                        }
                    }
                }
            }

            $maxRow = $db->query(
                "SELECT MAX(COALESCE(custodian_number, 0)) AS max_num FROM custodians WHERE donor_id = :did",
                [':did' => $donorId]
            );
            $nextNumber = (int)(($maxRow && isset($maxRow[0]->max_num)) ? $maxRow[0]->max_num : 0) + 1;
            if ($nextNumber < 1) $nextNumber = 1;

            $db->insert(
                "INSERT INTO custodians (
                    user_id, donor_id, organ_id, relationship, custodian_number, status,
                    name, nic_number, phone, email, address
                ) VALUES (
                    :uid, :did, NULL, :rel, :num, 'PENDING',
                    :name, :nic, :phone, :email, NULL
                )",
                [
                    ':uid' => (int)$user->id,
                    ':did' => $donorId,
                    ':rel' => $relationship,
                    ':num' => $nextNumber,
                    ':name' => $name,
                    ':nic' => $username,
                    ':phone' => $user->phone ?? null,
                    ':email' => $user->email ?? null,
                ]
            );
        } catch (\Throwable $e) {
            // Best-effort only; never block login because of linkage heuristics.
            return;
        }
    }

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

            // Prevent session fixation and clear stale data from previous users
            session_regenerate_id(true);
            
            // Clear role-specific variables just in case
            unset($_SESSION['donor_id']);
            unset($_SESSION['hospital_id']);
            unset($_SESSION['school_id']);

            // For custodian accounts, ensure a matching custodians row exists so the portal won't bounce back to login.
            $this->ensureCustodianProfileForUser($user);

            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['role'] = $user->role;
            $_SESSION['status'] = $user->status;

            session_write_close(); // Ensure session data is saved before response

            echo json_encode([
                'success' => true,
                'role' => $user->role
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