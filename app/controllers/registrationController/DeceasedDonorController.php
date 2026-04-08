<?php

class DeceasedDonorController
{
    use Controller;

    public function registerDeceasedDonor()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        try {
            // Validate age (must be 21+)
            $dob = $_POST['dob'] ?? '';
            if (empty($dob)) {
                throw new Exception("Date of birth is required.");
            }
            
            $age = date_diff(date_create($dob), date_create('now'))->y;
            if ($age < 21) {
                throw new Exception("You must be 21 or older to register as a deceased organ donor.");
            }

            // Validate required fields
            $requiredFields = ['username', 'password', 'fullName', 'dob', 'nic', 'gender', 'bloodGroup', 'address', 'district', 'phone', 'kinName', 'kinRelation', 'kinPhone'];
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
                'role' => 'DeceasedDonor'
            ];

            // Collect personal data
            $personalData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'dob' => $dob,
                'nic' => trim($_POST['nic']),
                'gender' => $_POST['gender'],
                'blood_group' => $_POST['bloodGroup'],
                'address' => trim($_POST['address']),
                'district' => $_POST['district'],
                'divisional_secretariat' => trim($_POST['divSec'] ?? ''),
                'grama_niladhari_division' => trim($_POST['gnDiv'] ?? ''),
                'phone' => trim($_POST['phone']),
                'email' => trim($_POST['email'] ?? '')
            ];

            // Collect next of kin information
            $nextOfKin = [
                'name' => trim($_POST['kinName']),
                'relationship' => $_POST['kinRelation'],
                'phone' => trim($_POST['kinPhone']),
                'email' => trim($_POST['kinEmail'] ?? ''),
                'nic' => trim($_POST['kinNIC'] ?? '')
            ];

            // Collect selected organs
            $organsJson = $_POST['organs'] ?? '[]';
            $selectedOrgans = json_decode($organsJson, true);
            if (empty($selectedOrgans) || !is_array($selectedOrgans)) {
                throw new Exception("Please select at least one organ to donate.");
            }

            // Validate consent
            if (!isset($_POST['consentAgree'])) {
                throw new Exception("You must agree to the legal declaration.");
            }

            // Load model
            $deceasedDonorModel = new DeceasedDonorModel();

            // Check if username exists
            if ($deceasedDonorModel->usernameExists($credentials['username'])) {
                throw new Exception("Username already exists.");
            }

            // Check if NIC exists
            if ($deceasedDonorModel->nicExists($personalData['nic'])) {
                throw new Exception("NIC number already registered.");
            }

            // Start transaction
            $deceasedDonorModel->beginTransaction();

            // Create user account
            $userId = $deceasedDonorModel->createUser($credentials);

            // Create donor record
            $donorId = $deceasedDonorModel->createDonor($userId, $personalData);

            // Create deceased donor record in deceased_organ_donors table
            $deceasedDonorId = $deceasedDonorModel->createDeceasedDonor($userId, $donorId, $personalData, $nextOfKin, $selectedOrgans);

            // Create organ pledges
            $deceasedDonorModel->createOrganPledges($donorId, $selectedOrgans);

            // Commit transaction
            $deceasedDonorModel->commit();

            echo json_encode([
                'success' => true,
                'message' => 'Registration submitted successfully. Your application will be reviewed by our admin team within 24-48 hours.',
                'redirect' => 'view4-pending'
            ]);

        } catch (Exception $e) {
            // Rollback on error
            if (isset($deceasedDonorModel)) {
                $deceasedDonorModel->rollback();
            }
            
            echo json_encode([
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ]);
        }
    }
}

