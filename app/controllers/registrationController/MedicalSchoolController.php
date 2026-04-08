<?php

class MedicalSchoolController
{
    use Controller;

    public function registerMedicalSchool()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        try {
            // Validate required fields
            $requiredFields = ['username', 'password', 'instName', 'university', 'ugcNumber', 'instAddress', 'contactName', 'contactTitle', 'contactEmail', 'contactPhone'];
            foreach ($requiredFields as $field) {
                if (empty($_POST[$field])) {
                    throw new Exception("Field {$field} is required.");
                }
            }

            // Collect credentials
            $credentials = [
                'username' => trim($_POST['username']),
                'password' => password_hash($_POST['password'], PASSWORD_DEFAULT),
                'role' => 'MedicalSchool'
            ];

            // Collect medical school data
            $schoolData = [
                'institution_name' => trim($_POST['instName']),
                'university_affiliation' => trim($_POST['university']),
                'ugc_accreditation_number' => trim($_POST['ugcNumber']),
                'address' => trim($_POST['instAddress']),
                'district' => $_POST['instDistrict'] ?? '',
                'contact_person_name' => trim($_POST['contactName']),
                'contact_person_title' => trim($_POST['contactTitle']),
                'contact_email' => trim($_POST['contactEmail']),
                'contact_phone' => trim($_POST['contactPhone'])
            ];

            // Load model
            $schoolModel = new MedicalSchoolModel();

            // Check if username exists
            if ($schoolModel->usernameExists($credentials['username'])) {
                throw new Exception("Username already exists.");
            }

            // Create user account
            $userId = $schoolModel->createUser($credentials);

            // Create medical school record
            $schoolModel->createMedicalSchool($userId, $schoolData);

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

