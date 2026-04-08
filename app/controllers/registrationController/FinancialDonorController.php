<?php

class FinancialDonorController
{
    use Controller;

    public function registerFinancialDonor()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        try {
            // Collect personal information
            $personalData = [
                'full_name' => trim($_POST['fullName'] ?? ''),
                'dob' => $_POST['dob'] ?? '',
                'nic' => trim($_POST['nic'] ?? ''),
                'gender' => $_POST['gender'] ?? '',
                'address' => trim($_POST['address'] ?? ''),
                'district' => $_POST['district'] ?? '',
                'phone' => trim($_POST['phone'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
            ];

            // Basic validation (you can expand)
            if (empty($personalData['full_name']) || empty($personalData['dob']) || empty($personalData['nic'])) {
                throw new Exception("Required fields are missing.");
            }

            // Collect credentials
            $credentials = [
                'username' => trim($_POST['username'] ?? ''),
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role' => 'FinancialDonor'
            ];

            if (empty($credentials['username'])) {
                throw new Exception("Username is required.");
            }

            // Load model
            $donorModel = new FinancialDonorModel();

            // Create user account
            $userId = $donorModel->createUser($credentials);

            // Create donor record linked with user
            $donorModel->createFinancialDonor($userId, $personalData);

            echo json_encode([
                'success' => true,
                'message' => 'Registration completed successfully. You can now log in.',
                'redirect' => 'view4-success'
            ]);

        } catch (Exception $e) {
            echo json_encode([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ]);
        }
    }

    public function index() {
        $this->view('Financial donor/donation');
    }
}