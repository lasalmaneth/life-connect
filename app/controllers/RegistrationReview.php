<?php 

namespace App\Controllers;
use App\Core\Controller;
use App\Models\UserModel;
use App\Models\DonorModel;
use App\Models\HospitalModel;
use App\Models\MedicalSchoolModel;

class RegistrationReview {

    use Controller;

    public function index(){
        if (!isset($_SESSION['donor_registration']) && !isset($_SESSION['institution_registration'])) {
            redirect('signup');
            return;
        }

        $this->view('registration/review');
    }

    public function submit(){
        if($_SERVER['REQUEST_METHOD'] == "POST"){
            
            $json = $_POST['full_state_json'] ?? '{}';
            $state = json_decode($json, true) ?? [];
            $role = get_role($state);

            // Safety guard: if session is gone (expired/cleared), restart
            if (!isset($_SESSION['donor_registration']) && !isset($_SESSION['institution_registration'])) {
                error_log("Registration submit: Session missing. Role=$role. JSON=$json");
                $_SESSION['reg_error'] = "Your session has expired. Please start your registration again.";
                redirect('signup');
                return;
            }

            error_log("Registration submit: role=$role, state_email=" . ($state['donor']['email'] ?? $state['institution']['email'] ?? 'none'));

            if($role == 'donor'){
                $success = $this->saveDonor($state);
            } else if ($role == 'institution'){
                $success = $this->saveInstitution($state);
            } else {
                $success = false;
                $_SESSION['reg_error'] = "Invalid registration role. Please try again.";
            }
            
            if($success){
                // Clear session data after successful registration
                unset($_SESSION['donor_registration']);
                unset($_SESSION['institution_registration']);
                unset($_SESSION['basic_donor_data']);
                
                redirect('registration/pending');
            } else {
                // Handle error – redirect back to review with message
                $_SESSION['reg_error'] = $_SESSION['reg_error'] ?? "Failed to save data. Please try again.";
                error_log("Registration failed for role: $role. Error: " . $_SESSION['reg_error']);
                redirect('registration/review');
            }
        }
    }

    private function saveDonor($state){
        try {
            $sessionData = $_SESSION['donor_registration'] ?? [];
            // Merge session data (has password) with state data
            // Priority to POST state to allow review page edits
            
            // Preference to POST state, fallback to Session mapping
            $username = !empty($state['donor']['username']) ? $state['donor']['username'] : ($sessionData['username'] ?? '');
            $password = $sessionData['password']; // Hashed
            $email = !empty($state['donor']['email']) ? $state['donor']['email'] : ($sessionData['email'] ?? '');
            $phone = !empty($state['donor']['phone']) ? $state['donor']['phone'] : ($sessionData['phone'] ?? '');
            $firstName = !empty($state['donor']['firstName']) ? $state['donor']['firstName'] : ($sessionData['first_name'] ?? '');
            $lastName = !empty($state['donor']['lastName']) ? $state['donor']['lastName'] : ($sessionData['last_name'] ?? '');
            $nic = !empty($state['donor']['nic']) ? $state['donor']['nic'] : ($sessionData['nic'] ?? '');
            $district = !empty($state['donor']['district']) ? $state['donor']['district'] : ($sessionData['district'] ?? '');
            
            // Fix: Use ISO format (YYYY-MM-DD) for database compatibility
            $dob = $state['donor']['dobIso'] ?? $state['donor']['dob'] ?? null;
            if (!$dob && !empty($sessionData['dob'])) {
                $dob = date('Y-m-d', strtotime($sessionData['dob']));
            }
            if (!$dob) {
                $dob = !empty($state['donor']['dobDisplay']) ? date('Y-m-d', strtotime($state['donor']['dobDisplay'])) : date('Y-m-d');
            }

            $gender = !empty($state['donor']['gender']) ? $state['donor']['gender'] : ($sessionData['gender'] ?? 'OTHER');
            
            $email = strtolower(trim($email));
            // Diagnostic: Log what we are checking
            error_log("OTP Gate Check (Donor): Email=" . $email);
            
            $otpModel = new \App\Models\RegistrationOtpModel();
            if (!$otpModel->isEmailVerified($email)) {
                error_log("OTP Gate Failed for Donor: " . $email);
                throw new \Exception("Email verification (OTP) required before completing registration.");
            }

            // Donation types
            $donationTypes = $state['donation'] ?? [];
            $isFinancial = in_array('financial', $donationTypes);
            $isNonDonor = in_array('nondonor', $donationTypes);
            $isLive = in_array('willing', $donationTypes); 

            $userModel = new UserModel();
            if ($userModel->usernameExists($username)) throw new \Exception("Username is already taken."); 
            if ($userModel->emailExists($email)) throw new \Exception("Email is already registered.");
            
            $donorModel = new DonorModel();
            if ($donorModel->nicExists($nic)) throw new \Exception("NIC is already registered.");
            
            // Determine user role and status
            $userRole = 'DONOR';
            $status = 'PENDING';
            if($isFinancial) {
                $userRole = 'DONOR'; 
                $status = 'ACTIVE';
            }
            
            $userId = $userModel->createUser($username, $password, $userRole, $email, $phone, $status);
            if(!$userId) {
                throw new \Exception("Failed to create user record for $username");
            }

            // Create Donor Profile
            $donorModel = new DonorModel();
            
            // For initial registration, we default all to Non-Donor (Category 1, NONE)
            // Preferences are set later in the User Profile dashboard.
            $categoryId = 1; 
            $pledgeType = 'NONE';
            $isFinancial = false; 
            
            $donorData = [
                'first_name' => $firstName,
                'last_name' => $lastName,
                'dob' => $dob, 
                'nic' => $nic,
                'gender' => strtoupper($gender),
                'blood_group' => $state['donor']['blood_group'] ?? null, 
                'address' => '', 
                'district' => $district,
                'divisional_secretariat' => '',
                'gn_division' => ''
            ];

            // $isFinancial is intentionally set to false above (line 111) — no financial_donors table anymore.
            
            // Always create a donor profile entry for individual users
            $donorId = $donorModel->createDonor($userId, $donorData, $categoryId, $pledgeType);
            if(!$donorId) {
                throw new \Exception("Failed to create donor profile for $userId");
            }

            $_SESSION['submitted_username'] = $username;
            return true;
        } catch (\Throwable $e) {
            $_SESSION['reg_error'] = $e->getMessage();
            error_log("Registration Exception: " . $e->getMessage());
            return false;
        }
    }

    private function saveInstitution($state){
        try {
            $sessionData = $_SESSION['institution_registration'] ?? [];
            
            // Priority to POST state to allow review page edits
            $username = !empty($state['institution']['username']) ? $state['institution']['username'] : ($sessionData['username'] ?? '');
            $password = $sessionData['password'] ?? ''; 
            $email = strtolower(trim(!empty($state['institution']['email']) ? $state['institution']['email'] : ($sessionData['email'] ?? '')));
            $phone = !empty($state['institution']['phone']) ? $state['institution']['phone'] : ($sessionData['phone'] ?? '');
            $name = !empty($state['institution']['name']) ? $state['institution']['name'] : ($sessionData['name'] ?? '');
            $type = !empty($state['institution']['type']) ? $state['institution']['type'] : ($sessionData['type'] ?? 'hospital');
            $address = !empty($state['institution']['address']) ? $state['institution']['address'] : ($sessionData['address'] ?? '');
            $regNo = !empty($state['institution']['reg']) ? $state['institution']['reg'] : ($sessionData['reg_no'] ?? '');
            $transplantId = !empty($state['institution']['transplant_id'])
                ? $state['institution']['transplant_id']
                : (!empty($state['institution']['transplant'])
                    ? $state['institution']['transplant']
                    : ($sessionData['transplant_id'] ?? ''));

            // Diagnostic: Log what we are checking
            error_log("OTP Gate Check (Inst): Email=" . $email);
            
            $otpModel = new \App\Models\RegistrationOtpModel();
            if (!$otpModel->isEmailVerified($email)) {
                error_log("OTP Gate Failed for Inst: " . $email);
                throw new \Exception("Email verification (OTP) required before completing registration.");
            }

            $userModel = new UserModel();
            if ($userModel->usernameExists($username)) {
                throw new \Exception("Username $username is already taken.");
            }
            if ($userModel->emailExists($email)) {
                throw new \Exception("Email $email is already registered.");
            }

            if ($type == 'hospital') {
                $hospitalModel = new HospitalModel();
                if ($hospitalModel->hospitalRegNoExists($regNo)) {
                    throw new \Exception("Institution Registration Number $regNo is already registered.");
                }
            } else {
                $medModel = new MedicalSchoolModel();
                if ($medModel->ugcNumberExists($regNo)) {
                    throw new \Exception("UGC Accreditation Number $regNo is already registered.");
                }
            }

            $role = ($type == 'school') ? 'MEDICAL_SCHOOL' : 'HOSPITAL';
            $userId = $userModel->createUser($username, $password, $role, $email, $phone, 'PENDING');
            if (!$userId) {
                throw new \Exception("Failed to create user record for $username");
            }
            
            if($type == 'hospital'){
                $hospitalModel = new HospitalModel();
                $success = $hospitalModel->registerHospital($userId, [
                    'registration_number' => $regNo,
                    'transplant_id' => $transplantId,
                    'name' => $name,
                    'address' => $address,
                    'contact_number' => $phone,
                    'district' => '',
                    'type' => 'General' 
                ], [
                    'name' => 'Pending', 
                    'nic' => '', 
                    'license_number' => ''
                ]);
                if (!$success) throw new \Exception("Failed to register hospital profile.");
            } else {
                 $medModel = new MedicalSchoolModel();
                 $success = $medModel->registerMedicalSchool($userId, [
                    'name' => $name,
                    'university' => 'Pending',
                    'ugc_number' => $regNo,
                    'address' => $address,
                    'district' => ''
                 ], [
                    'name' => 'Pending',
                    'email'=> $email,
                    'phone' => $phone
                 ]);
                 if (!$success) throw new \Exception("Failed to register medical school profile.");
            }
            
            $_SESSION['submitted_username'] = $username;
            return true;
        } catch (\Throwable $e) {
            $_SESSION['reg_error'] = $e->getMessage();
            error_log("Institution Registration Exception: " . $e->getMessage());
            return false;
        }
    }
 }

 function get_role($state){
     return $state['role'] ?? 'donor';
 }
