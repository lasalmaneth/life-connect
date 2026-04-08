<?php 

namespace App\Controllers;
use App\Core\Controller;

class RegistrationDonor {

    use Controller;
    public function index(){

         $role = $_GET['role'] ?? 'donor'; 
         if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $data = [
                'first_name' => trim($_POST['first_name'] ?? ''),
                'last_name'  => trim($_POST['last_name'] ?? ''),
                'username'   => trim($_POST['username'] ?? ''),
                'nic'        => trim($_POST['nic'] ?? ''),
                'dob'        => trim($_POST['dob'] ?? ''),
                'gender'     => trim($_POST['gender'] ?? ''),
                'phone'      => trim($_POST['phone'] ?? ''),
                'email'      => trim($_POST['email'] ?? ''),
                'password'   => $_POST['password'] ?? '',
                'confirm_password' => $_POST['confirm_password'] ?? '',
                'terms'      => $_POST['terms'] ?? '',
                'role'       => $role
            ];

            $_SESSION['basic_donor_data'] = $data; // Store data in session for later steps

            $errors = $this->validateBasicInformation($data);

            if (empty($errors)) {

                // Hash password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Save into session (step based registration)
                $_SESSION['donor_registration'] = $data;

                // Redirect to next step
                redirect('registration/donation');
            }

            $this->view('registration/donor', ['errors' => $errors]);
            return;
        }
        
        $sessionData = $_SESSION['basic_donor_data'] ?? [];
        unset($sessionData['password']);
        unset($sessionData['confirm_password']);
        
        $this->view('registration/donor', ['sessionData' => $sessionData]);

    }


    //     if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //         $data = [
    //             'full_name' => trim($_POST['full_name'] ?? ''),
    //             'username'  => trim($_POST['username'] ?? ''),
    //             'nic'       => trim($_POST['nic'] ?? ''),
    //             'dob'       => trim($_POST['dob'] ?? ''),
    //             'gender'    => trim($_POST['gender'] ?? ''),
    //             'phone'     => trim($_POST['phone'] ?? ''),
    //             'email'     => trim($_POST['email'] ?? ''),
    //             'password'  => $_POST['password'] ?? '',
    //             'confirm_password' => $_POST['confirm_password'] ?? '',
    //             'terms'     => $_POST['terms'] ?? ''
    //         ];

    //         $errors = $this->validateBasicInformation($data);

    //         if (empty($errors)) {

    //             // Hash password
    //             $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

    //             // Save into session (step based registration)
    //             $_SESSION['donor_registration'] = $data;

    //             // Redirect to next step
    //             header("Location: /life-connect/public/registration?page=donor-step2");
    //             exit;
    //         }

    //         $this->view('registration/donor', ['errors' => $errors]);
    //         return;
    //     }

    //     $this->view('registration/donor');
    // }

    private function validateBasicInformation($data) {

        $errors = [];

        // Names
        if (empty($data['first_name'])) {
            $errors[] = "First name is required.";
        }
        if (empty($data['last_name'])) {
            $errors[] = "Last name is required.";
        }

        // Username
        if (empty($data['username'])) {
            $errors[] = "Username is required.";
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $data['username'])) {
            $errors[] = "Username can contain only letters, numbers and underscores.";
        }

        // NIC (support old and new format)
        if (empty($data['nic'])) {
            $errors[] = "NIC is required.";
        } elseif (!preg_match('/^([0-9]{9}[VvXx]|[0-9]{12})$/', $data['nic'])) {
            $errors[] = "Invalid NIC format.";
        }

        // DOB (auto-extracted from NIC, may be empty on first POST)
        // Not blocking — will be re-extracted from NIC on the backend if needed

        // Gender (auto-extracted from NIC, may be empty on first POST)
        // Not blocking — will be re-extracted from NIC on the backend if needed

        // Phone
        if (!preg_match('/^0[0-9]{9}$/', $data['phone'])) {
            $errors[] = "Phone number must be 10 digits and start with 0.";
        }

        // Email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Valid email address is required.";
        }

        // Password
        if (strlen($data['password']) < 8) {
            $errors[] = "Password must be at least 8 characters.";
        } elseif (!preg_match('/[A-Z]/', $data['password']) ||
                  !preg_match('/[a-z]/', $data['password']) ||
                  !preg_match('/[0-9]/', $data['password']) ||
                  !preg_match('/[^A-Za-z0-9]/', $data['password'])) {
            $errors[] = "Password must include uppercase, lowercase, number and special character.";
        }

        // Confirm Password
        if ($data['password'] !== $data['confirm_password']) {
            $errors[] = "Passwords do not match.";
        }

        // Terms
        if (empty($data['terms'])) {
            $errors[] = "You must accept the Terms & Conditions.";
        }

        return $errors;
    }


 }
