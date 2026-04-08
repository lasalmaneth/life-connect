<?php

class HospitalController
{
    use Controller;

    public function registerHospital()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        try {
            // Validate required fields
            $requiredFields = ['username', 'password', 'instName', 'regNo', 'instType', 'instAddress', 'instDistrict', 'instPhone', 'instEmail', 'cmoName', 'cmoNIC'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Field {$field} is required.");
                }
            }

            // Collect credentials
            $credentials = [
                'username' => trim($_POST['username']),
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role' => 'Hospital'
            ];

            // Collect hospital data
            $hospitalData = [
                'registration_no' => trim($_POST['regNo']),
                'h_name' => trim($_POST['instName']),
                'h_email' => trim($_POST['instEmail']),
                'contact_number' => trim($_POST['instPhone']),
                'h_location' => trim($_POST['instAddress']),
                'district' => $_POST['instDistrict'],
                'type' => $_POST['instType'],
                'cmo_name' => trim($_POST['cmoName']),
                'cmo_nic' => trim($_POST['cmoNIC'])
            ];

            // Load model
            $hospitalModel = new HospitalModel();

            // Check if username exists
            if ($hospitalModel->usernameExists($credentials['username'])) {
                throw new Exception("Username already exists.");
            }

            // Check if registration number exists
            if ($hospitalModel->registrationNoExists($hospitalData['registration_no'])) {
                throw new Exception("Hospital registration number already exists.");
            }

            // Create user account
            $userId = $hospitalModel->createUser($credentials);

            // Create hospital record
            $hospitalModel->createHospital($userId, $hospitalData);

            echo json_encode([
                'success' => true,
                'message' => 'Registration submitted successfully. Your application will be reviewed by our admin team within 24-48 hours.',
                'redirect' => 'view4-pending'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ]);
        }
    }
}

