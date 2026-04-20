<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AftercarePatientModel;
use App\Models\HospitalModel;
use App\Models\MedicalHistoryModel;
use App\Models\LoginModel;
use App\Models\UserModel;

class Aftercare
{
    use Controller;

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['aftercare_patient_id'])) {
            redirect('login');
        }

        if (!empty($_SESSION['aftercare_must_change_password'])) {
            redirect('aftercare/change-password');
        }

        $patientModel = new AftercarePatientModel();
        $patient = $patientModel->query(
            "SELECT ap.id, rp.registration_number, rp.nic, rp.full_name, ap.patient_type, rp.hospital_registration_no
             FROM aftercare_patients ap
             JOIN recipient_patient rp ON ap.user_id = rp.user_id
             WHERE ap.id = :id AND ap.patient_type = 'RECIPIENT'
             LIMIT 1",
            [':id' => (int)$_SESSION['aftercare_patient_id']]
        );
        $patient = $patient ? $patient[0] : null;

        if (!$patient) {
            unset(
                $_SESSION['aftercare_user_id'],
                $_SESSION['aftercare_patient_id'],
                $_SESSION['aftercare_registration_number'],
                $_SESSION['aftercare_must_change_password']
            );
            redirect('login');
        }

        $nic = (string)($patient->nic ?? '');

        $appointments = [];
        $supportRequests = [];
        $medicalHistory = [];
        
        if ($nic !== '') {
            $appointments = $patientModel->query(
                "SELECT * FROM aftercare_appointments WHERE user_id = :uid ORDER BY appointment_date ASC",
                [':uid' => (int)($_SESSION['aftercare_user_id'] ?? 0)]
            ) ?: [];

            $supportRequests = $patientModel->query(
                "SELECT sr.*, v.voucher_code, v.expiry_date, v.status as voucher_status 
                 FROM support_requests sr 
                 LEFT JOIN support_vouchers v ON sr.id = v.request_id 
                 WHERE sr.patient_nic = :nic ORDER BY sr.created_at DESC",
                [':nic' => $nic]
            ) ?: [];

            // Fetch medical history from hospital records
            $medicalHistoryModel = new MedicalHistoryModel();
            $medicalHistory = $medicalHistoryModel->getMedicalHistoryByNIC($nic) ?: [];
        }

        $hospitalModel = new HospitalModel();
        $approvedHospitals = $hospitalModel->getAllHospitals() ?: [];

        // Recipient Aftercare portal landing (donor-style page)
        $this->view('aftercare/aftercare', [
            'patient' => $patient,
            'appointments' => $appointments,
            'support_requests' => $supportRequests,
            'medical_history' => $medicalHistory,
            'hospitals' => $approvedHospitals,
        ]);
    }

    public function createAppointment()
    {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['aftercare_patient_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $patientModel = new AftercarePatientModel();
            $rows = $patientModel->query(
                "SELECT ap.id, rp.nic, rp.full_name, ap.patient_type, rp.status
                 FROM aftercare_patients ap
                 JOIN recipient_patient rp ON ap.user_id = rp.user_id
                 WHERE ap.id = :id AND ap.patient_type = 'RECIPIENT' LIMIT 1",
                [':id' => (int)$_SESSION['aftercare_patient_id']]
            );
            $patient = $rows ? $rows[0] : null;

            if (!$patient || !in_array(strtoupper((string)($patient->status ?? 'ACTIVE')), ['ACTIVE', 'PENDING'])) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }

            $date = (string)($_POST['appointment_date'] ?? '');
            $type = (string)($_POST['appointment_type'] ?? '');
            $desc = (string)($_POST['description'] ?? '');
            $hospitalRegistrationNo = (string)($_POST['hospital_registration_no'] ?? '');

            if ($date === '' || $type === '' || $hospitalRegistrationNo === '') {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                exit;
            }

            $date = trim($date);
            $date = str_replace('T', ' ', $date); // normalize HTML datetime-local
            if (preg_match('/^\d{4}-\d{2}-\d{2} \d{2}:\d{2}$/', $date)) {
                $date .= ':00';
            }

            $dt = \DateTime::createFromFormat('Y-m-d H:i:s', $date);
            if (!$dt) {
                echo json_encode(['success' => false, 'message' => 'Invalid appointment date/time']);
                exit;
            }

            $mysqlDate = $dt->format('Y-m-d H:i:s');

            $patientModel->query(
                "INSERT INTO aftercare_appointments (user_id, hospital_registration_no, appointment_date, appointment_type, description, status)
                 VALUES (:uid, :hosp, :date, :type, :desc, 'Requested')",
                [
                    ':uid' => (int)($_SESSION['aftercare_user_id'] ?? 0),
                    ':hosp' => $hospitalRegistrationNo,
                    ':date' => $mysqlDate,
                    ':type' => $type,
                    ':desc' => $desc,
                ]
            );

            echo json_encode([
                'success' => true,
                'appointment' => [
                    'date' => $dt->format('Y-m-d'),
                    'time' => $dt->format('h:i A'),
                    'datetime_display' => $dt->format('M d, Y - h:i A'),
                    'type' => $type,
                    'description' => $desc,
                    'status' => 'Scheduled',
                ],
            ]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
        exit;
    }

    public function submitSupportRequest()
    {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['aftercare_patient_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        try {
            $patientModel = new AftercarePatientModel();

            // Ensure optional support_requests.amount column exists
            try {
                $res = $patientModel->query("SHOW COLUMNS FROM support_requests LIKE 'amount'");
                if (empty($res)) {
                    $patientModel->query("ALTER TABLE support_requests ADD COLUMN amount DECIMAL(10,2) NULL AFTER reason");
                }
            } catch (\Throwable $e) {
                // Ignore migration errors; insert will fail if truly required
            }

            $rows = $patientModel->query(
                "SELECT ap.id, rp.nic, rp.full_name, ap.patient_type, rp.status
                 FROM aftercare_patients ap
                 JOIN recipient_patient rp ON ap.user_id = rp.user_id
                 WHERE ap.id = :id AND ap.patient_type = 'RECIPIENT' LIMIT 1",
                [':id' => (int)$_SESSION['aftercare_patient_id']]
            );
            $patient = $rows ? $rows[0] : null;

            if (!$patient || !in_array(strtoupper((string)($patient->status ?? 'ACTIVE')), ['ACTIVE', 'PENDING'])) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }

            $reason = (string)($_POST['reason'] ?? '');
            $amountRaw = trim((string)($_POST['amount'] ?? ''));
            $amountRawNorm = str_replace([',', ' '], '', $amountRaw);
            $desc = (string)($_POST['description'] ?? '');
            $hospitalRegistrationNo = (string)($_POST['hospital_registration_no'] ?? '');
            $today = date('Y-m-d');

            if ($reason === '' || $hospitalRegistrationNo === '') {
                echo json_encode(['success' => false, 'message' => 'Missing required fields']);
                exit;
            }

            $amount = null;
            if ($amountRawNorm !== '') {
                if (!is_numeric($amountRawNorm)) {
                    echo json_encode(['success' => false, 'message' => 'Invalid amount']);
                    exit;
                }
                $amountNum = (float)$amountRawNorm;
                if ($amountNum < 0) {
                    echo json_encode(['success' => false, 'message' => 'Invalid amount']);
                    exit;
                }
                $amount = number_format($amountNum, 2, '.', '');
            }

            $patientModel->query(
                "INSERT INTO support_requests (patient_nic, patient_name, patient_type, hospital_registration_no, reason, amount, description, status, submitted_date)
                 VALUES (:nic, :name, 'RECIPIENT', :hosp, :reason, :amount, :desc, 'PENDING', :today)",
                [
                    ':nic' => (string)$patient->nic,
                    ':name' => (string)$patient->full_name,
                    ':hosp' => $hospitalRegistrationNo,
                    ':reason' => $reason,
                    ':amount' => $amount,
                    ':desc' => $desc,
                    ':today' => $today,
                ]
            );

            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
        exit;
    }

    public function login()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!empty($_SESSION['aftercare_patient_id'])) {
            redirect('aftercare');
        }

        $this->view('aftercare/login', [
            'flash_error' => $_SESSION['aftercare_flash_error'] ?? null,
            'flash_success' => $_SESSION['aftercare_flash_success'] ?? null,
        ]);

        unset($_SESSION['aftercare_flash_error'], $_SESSION['aftercare_flash_success']);
    }

    public function verify()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('login');
        }

        $registrationNumber = trim((string)($_POST['registration_number'] ?? ''));
        $password = (string)($_POST['password'] ?? '');

        if ($registrationNumber === '' || $password === '') {
            $_SESSION['aftercare_flash_error'] = 'Please enter registration number and password.';
            redirect('login');
        }

        $patientModel = new AftercarePatientModel();
        $patient = $patientModel->getByRegistrationNumber($registrationNumber);

        if (!$patient) {
            $_SESSION['aftercare_flash_error'] = 'Aftercare profile not found. Please contact your hospital.';
            redirect('login');
        }

        if (!empty($patient->patient_type) && strtoupper((string)$patient->patient_type) !== 'RECIPIENT') {
            $_SESSION['aftercare_flash_error'] = 'Only recipient patients can sign in to the Aftercare Portal.';
            redirect('login');
        }

        if (!empty($patient->status) && !in_array(strtoupper((string)$patient->status), ['ACTIVE', 'PENDING'])) {
            $_SESSION['aftercare_flash_error'] = 'Your account is currently inactive. Please contact your hospital.';
            redirect('login');
        }

        // Prefer users-based authentication (username = registration number)
        $loginModel = new LoginModel();
        $user = $loginModel->getUserByUsername($registrationNumber);

        $userOk = false;
        if (
            $user &&
            (strtoupper((string)($user->role ?? '')) === 'AFTERCARE_PATIENT' || strtoupper((string)($user->role ?? '')) === 'RECIPIENT_PATIENT') &&
            strtoupper((string)($user->status ?? 'ACTIVE')) === 'ACTIVE' &&
            !empty($user->password_hash) &&
            password_verify($password, (string)$user->password_hash)
        ) {
            $userOk = true;
        }

        if (!$userOk) {
            $_SESSION['aftercare_flash_error'] = 'Invalid registration number or password.';
            redirect('login');
        }

        $mustChange = !empty($user->must_change_credentials) ? 1 : (!empty($patient->must_change_password) ? 1 : 0);

        if (!empty($user->id)) {
            $_SESSION['aftercare_user_id'] = (int)$user->id;
        } else {
            unset($_SESSION['aftercare_user_id']);
        }

        $_SESSION['aftercare_patient_id'] = (int)$patient->id;
        $_SESSION['aftercare_registration_number'] = (string)$patient->registration_number;
        $_SESSION['aftercare_must_change_password'] = $mustChange;

        if (!empty($_SESSION['aftercare_must_change_password'])) {
            $_SESSION['aftercare_flash_success'] = 'Please change your password before continuing.';
            redirect('aftercare/change-password');
        }

        redirect('aftercare');
    }

    public function changePassword()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['aftercare_patient_id'])) {
            redirect('login');
        }

        $this->view('aftercare/change-password', [
            'flash_error' => $_SESSION['aftercare_flash_error'] ?? null,
            'flash_success' => $_SESSION['aftercare_flash_success'] ?? null,
        ]);

        unset($_SESSION['aftercare_flash_error'], $_SESSION['aftercare_flash_success']);
    }

    public function updatePassword()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['aftercare_patient_id'])) {
            redirect('login');
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('aftercare/change-password');
        }

        $newPassword = (string)($_POST['new_password'] ?? '');
        $confirmPassword = (string)($_POST['confirm_password'] ?? '');

        if (strlen($newPassword) < 8) {
            $_SESSION['aftercare_flash_error'] = 'Password must be at least 8 characters.';
            redirect('aftercare/change-password');
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['aftercare_flash_error'] = 'Passwords do not match.';
            redirect('aftercare/change-password');
        }

        $hash = password_hash($newPassword, PASSWORD_DEFAULT);

        $userModel = new UserModel();
        $userId = (int)($_SESSION['aftercare_user_id'] ?? 0);

        if ($userId <= 0) {
            $rn = (string)($_SESSION['aftercare_registration_number'] ?? '');
            if ($rn !== '') {
                $u = $userModel->first(['username' => $rn]);
                if ($u && !empty($u->id)) {
                    $userId = (int)$u->id;
                    $_SESSION['aftercare_user_id'] = $userId;
                }
            }
        }

        if ($userId > 0) {
            $userModel->updatePassword($userId, $hash);
            $userModel->clearMustChangeFlag($userId);
        } else {
            $_SESSION['aftercare_flash_error'] = 'User account not found for password update.';
            redirect('aftercare/change-password');
        }

        $_SESSION['aftercare_must_change_password'] = 0;
        $_SESSION['aftercare_flash_success'] = 'Password updated successfully.';
        redirect('aftercare');
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        unset(
            $_SESSION['aftercare_user_id'],
            $_SESSION['aftercare_patient_id'],
            $_SESSION['aftercare_registration_number'],
            $_SESSION['aftercare_must_change_password']
        );

        redirect('login');
    }
}
