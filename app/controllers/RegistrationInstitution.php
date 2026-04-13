<?php 

namespace App\Controllers;
use App\Core\Controller;

class RegistrationInstitution {

    use Controller;
    public function index(){

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // Basic validation (also done in JS, but good to have here)
            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'username' => trim($_POST['username'] ?? ''),
                'reg_no' => trim($_POST['reg_no'] ?? ''),
                'transplant_id' => trim($_POST['transplant_id'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'phone' => trim($_POST['phone'] ?? ''),
                'address' => trim($_POST['address'] ?? ''),
                'type' => $_POST['type'] ?? 'hospital' // hospital or school
            ];

            // Validate
            $errors = [];
            if (empty($data['name'])) $errors[] = "Institution name is required.";
            if (empty($data['username']) || !preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) $errors[] = "Valid username is required.";
            if (empty($data['reg_no'])) $errors[] = "Registration number is required.";
            if ($data['type'] === 'hospital' && empty($data['transplant_id'])) $errors[] = "Transplant ID is required for hospitals.";
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required.";
            if (!preg_match('/^0[0-9]{9}$/', $data['phone'])) $errors[] = "Phone number must be 10 digits and start with 0.";
            if (empty($data['address'])) $errors[] = "Address is required.";
            if (empty($_POST['password']) || strlen($_POST['password']) < 8) $errors[] = "Password must be at least 8 characters.";
            if (empty($_POST['terms_agreed'])) $errors[] = "You must accept the terms & conditions.";

            if (empty($errors)) {
                // Password handling
                $data['password'] = password_hash($_POST['password'], PASSWORD_DEFAULT);

                // Save to session
                $_SESSION['institution_registration'] = $data;

                // Redirect to review
                redirect('registration/review');
                return;
            }

            // Return to view with errors
            $sessionData = $data;
            unset($sessionData['password']);
            $this->view('registration/institution', ['sessionData' => $sessionData, 'errors' => $errors]);
            return;
        }

        $sessionData = $_SESSION['institution_registration'] ?? [];
        unset($sessionData['password']);
        
        $this->view('registration/institution', ['sessionData' => $sessionData]);

    }
 }
