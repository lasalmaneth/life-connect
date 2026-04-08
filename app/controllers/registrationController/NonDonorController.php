<?php

class NonDonorController
{
    use Controller;

    public function registerNonDonor()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        try {
            // Validate required fields
            $requiredFields = ['username', 'password', 'fullName', 'dob', 'nic', 'gender', 'address', 'district', 'phone'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Field {$field} is required.");
                }
            }

            // Split full name
            $fullName = trim($_POST['fullName']);
            $nameParts = explode(' ', $fullName, 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

            // Collect credentials
            $credentials = [
                'username' => trim($_POST['username']),
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role' => 'NonDonor'
            ];

            // Collect personal data
            $personalData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'dob' => $_POST['dob'],
                'nic' => trim($_POST['nic']),
                'gender' => $_POST['gender'],
                'address' => trim($_POST['address']),
                'district' => $_POST['district'],
                'divisional_secretariat' => trim($_POST['divSec'] ?? ''),
                'grama_niladhari_division' => trim($_POST['gnDiv'] ?? ''),
                'phone' => trim($_POST['phone']),
                'email' => trim($_POST['email'] ?? '')
            ];

            // Collect opt-out reason
            $optOutReason = $_POST['reason'] ?? null;

            // Load model
            $nonDonorModel = new NonDonorModel();

            // Check if username exists
            if ($nonDonorModel->usernameExists($credentials['username'])) {
                throw new Exception("Username already exists.");
            }

            // Check if NIC exists
            if ($nonDonorModel->nicExists($personalData['nic'])) {
                throw new Exception("NIC number already registered.");
            }

            // Create user account
            $userId = $nonDonorModel->createUser($credentials);

            // Create donor record (non-donors are also in donors table)
            $donorId = $nonDonorModel->createDonor($userId, $personalData);

            // Create non-donor record
            $nonDonorModel->createNonDonor($donorId, $optOutReason);

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

