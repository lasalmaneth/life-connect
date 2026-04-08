<?php

class LiveOrganDonorController
{
    use Controller;

    public function registerLiveOrganDonor()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        try {
            // Split full name into first and last name
            $fullName = trim($_POST['fullName'] ?? '');
            $nameParts = explode(' ', $fullName, 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

            // Validate age (must be 21+)
            $dob = $_POST['dob'] ?? '';
            if (empty($dob)) {
                throw new Exception("Date of birth is required.");
            }
            
            $age = date_diff(date_create($dob), date_create('now'))->y;
            if ($age < 21) {
                throw new Exception("You must be 21 or older to register as a live organ donor.");
            }

            // Collect personal information
            $personalData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'dob' => $dob,
                'nic' => trim($_POST['nic'] ?? ''),
                'gender' => $_POST['gender'] ?? '',
                'blood_group' => $_POST['bloodGroup'] ?? '',
                'address' => trim($_POST['address'] ?? ''),
                'district' => $_POST['district'] ?? '',
                'divisional_secretariat' => trim($_POST['divSec'] ?? ''),
                'grama_niladhari_division' => trim($_POST['gnDiv'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'email' => trim($_POST['email'] ?? '')
            ];

            // Basic validation
            if (empty($personalData['first_name']) || empty($personalData['nic'])) {
                throw new Exception("Required fields are missing.");
            }

            // Collect credentials
            $credentials = [
                'username' => trim($_POST['username'] ?? ''),
                'password' => password_hash(trim($_POST['password'] ?? ''), PASSWORD_DEFAULT),
                'role' => 'LiveDonor'
            ];

            if (empty($credentials['username'])) {
                throw new Exception("Username is required.");
            }

            // Collect selected organs
            $organsJson = $_POST['organs'] ?? '[]';
            $selectedOrgans = json_decode($organsJson, true);
            if (empty($selectedOrgans) || !is_array($selectedOrgans)) {
                throw new Exception("Please select at least one organ to donate.");
            }

            // Collect witness information
            $witnesses = [
                [
                    'name' => trim($_POST['witness1Name'] ?? ''),
                    'nic' => trim($_POST['witness1NIC'] ?? ''),
                    'phone' => trim($_POST['witness1Phone'] ?? '')
                ],
                [
                    'name' => trim($_POST['witness2Name'] ?? ''),
                    'nic' => trim($_POST['witness2NIC'] ?? ''),
                    'phone' => trim($_POST['witness2Phone'] ?? '')
                ]
            ];

            // Validate witnesses
            foreach ($witnesses as $index => $witness) {
                if (empty($witness['name']) || empty($witness['nic']) || empty($witness['phone'])) {
                    throw new Exception("Complete witness " . ($index + 1) . " information is required.");
                }
            }

            // Validate consent
            if (!isset($_POST['consentAgree'])) {
                throw new Exception("You must agree to the legal declaration.");
            }

            // Load model
            $donorModel = new LiveOrganDonorModel();

            // Start transaction
            $donorModel->beginTransaction();

            // Create user account
            $userId = $donorModel->createUser($credentials);

            // Create donor record
            $donorId = $donorModel->createDonor($userId, $personalData);

            // Create live donor record
            $liveDonorId = $donorModel->createLiveDonor($donorId);

            // Create witness records
            $donorModel->createWitnesses($liveDonorId, $witnesses);

            // Create organ pledges
            $donorModel->createOrganPledges($donorId, $selectedOrgans);

            // Commit transaction
            $donorModel->commit();

            echo json_encode([
                'success' => true,
                'message' => 'Registration submitted successfully. Your application will be reviewed by our admin team within 24-48 hours.',
                'redirect' => 'view4-pending'
            ]);

        } catch (Exception $e) {
            // Rollback on error
            if (isset($donorModel)) {
                $donorModel->rollback();
            }
            
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function index() {
        $this->view('LiveOrganDonor/registration');
    }
}