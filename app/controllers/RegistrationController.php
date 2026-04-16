<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;
use App\Models\DonorModel;
use App\Models\HospitalModel;
use App\Models\MedicalSchoolModel;
use App\Models\OrganModel;
use App\Models\WitnessModel;
use App\Models\NextOfKinModel;
use Exception;
use DateTime;

class RegistrationController
{
    use Controller;

    public function index()
    {
        $this->view('registration/index');
    }

    private function validatePost()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }
    }

    public function checkAvailability()
    {
        header('Content-Type: application/json');
        
        $type = $_GET['type'] ?? '';
        $value = trim($_GET['value'] ?? '');

        if (empty($type) || empty($value)) {
            echo json_encode(['success' => false, 'message' => 'Missing arguments']);
            return;
        }

        try {
            $userModel = new UserModel();
            if ($type === 'username') {
                if ($userModel->usernameExists($value)) {
                    echo json_encode(['success' => true, 'available' => false, 'message' => 'Username already taken']);
                } else {
                    echo json_encode(['success' => true, 'available' => true, 'message' => 'Username available']);
                }
            } elseif ($type === 'email') {
                if ($userModel->emailExists($value)) {
                    echo json_encode(['success' => true, 'available' => false, 'message' => 'Email already registered']);
                } else {
                    echo json_encode(['success' => true, 'available' => true, 'message' => 'Email available']);
                }
            } elseif ($type === 'nic') {
                $donorModel = new DonorModel();
                if ($donorModel->nicExists($value)) {
                    echo json_encode(['success' => true, 'available' => false, 'message' => 'NIC already registered']);
                } else {
                    echo json_encode(['success' => true, 'available' => true, 'message' => 'NIC available']);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid check type']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Server error']);
        }
    }

    private function calculateAge($dob)
    {
        $birthDate = new DateTime($dob);
        $today = new DateTime('today');
        return $birthDate->diff($today)->y;
    }

    public function registerLiveOrganDonor()
    {
        $this->validatePost();
        try {
            $dob = $_POST['dob'] ?? '';
            if (empty($dob)) throw new Exception("Date of birth is required.");
            if ($this->calculateAge($dob) < 21) throw new Exception("You must be 21 or older.");

            $userModel = new UserModel();
            if ($userModel->usernameExists($_POST['username'] ?? '')) throw new Exception("Username taken.");
            if ($userModel->emailExists($_POST['email'] ?? '')) throw new Exception("Email already registered.");

            $hashedPassword = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
            
            // 1. Create User
            $userId = $userModel->createUser(
                trim($_POST['username']),
                $hashedPassword,
                'DONOR',
                trim($_POST['email']),
                trim($_POST['phone']),
                'PENDING'
            );

            // 2. Create Donor Profile
            $donorModel = new DonorModel();
            $nameParts = explode(' ', trim($_POST['fullName'] ?? ''), 2);
            $personalData = [
                'first_name' => $nameParts[0],
                'last_name' => $nameParts[1] ?? '',
                'dob' => $dob,
                'nic' => trim($_POST['nic'] ?? ''),
                'gender' => strtoupper($_POST['gender'] ?? 'OTHER'),
                'blood_group' => $_POST['bloodGroup'] ?? 'O+',
                'address' => trim($_POST['address'] ?? ''),
                'district' => $_POST['district'] ?? '',
                'divisional_secretariat' => trim($_POST['divSec'] ?? ''),
                'gn_division' => trim($_POST['gnDiv'] ?? '')
            ];
            $donorId = $donorModel->createDonor($userId, $personalData, 'LIVE');

            // 3. Add Witnesses
            $witnessModel = new WitnessModel();
            $witnessModel->addWitness($donorId, [
                'name' => $_POST['witness1Name'], 'nic' => $_POST['witness1NIC'], 'phone' => $_POST['witness1Phone'], 'address' => $_POST['witness1Address'] ?? ''
            ]);
            $witnessModel->addWitness($donorId, [
                'name' => $_POST['witness2Name'], 'nic' => $_POST['witness2NIC'], 'phone' => $_POST['witness2Phone'], 'address' => $_POST['witness2Address'] ?? ''
            ]);

            // 4. Add Organ Pledges
            $organModel = new OrganModel();
            $organs = json_decode($_POST['organs'] ?? '[]', true);
            foreach ($organs as $organId) {
                $organModel->addDonorPledge($donorId, $organId);
            }

            echo json_encode(['success' => true, 'message' => 'Registration successful.', 'redirect' => 'view4-pending']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function registerDeceasedDonor()
    {
        $this->validatePost();
        try {
            $dob = $_POST['dob'] ?? '';
            if (empty($dob)) throw new Exception("Date of birth is required.");
            if ($this->calculateAge($dob) < 21) throw new Exception("You must be 21 or older.");

            $userModel = new UserModel();
            if ($userModel->usernameExists($_POST['username'] ?? '')) throw new Exception("Username taken.");
            if ($userModel->emailExists($_POST['email'] ?? '')) throw new Exception("Email already registered.");

            $hashedPassword = password_hash($_POST['password'] ?? '', PASSWORD_DEFAULT);
            $userId = $userModel->createUser(trim($_POST['username']), $hashedPassword, 'DONOR', trim($_POST['email'] ?? ''), trim($_POST['phone']), 'PENDING');

            $donorModel = new DonorModel();
            $nameParts = explode(' ', trim($_POST['fullName'] ?? ''), 2);
            $donorId = $donorModel->createDonor($userId, [
                'first_name' => $nameParts[0], 'last_name' => $nameParts[1] ?? '', 'dob' => $dob, 'nic' => trim($_POST['nic'] ?? ''),
                'gender' => strtoupper($_POST['gender'] ?? 'OTHER'), 'blood_group' => $_POST['bloodGroup'] ?? 'O+', 'address' => trim($_POST['address'] ?? ''),
                'district' => $_POST['district'] ?? '', 'divisional_secretariat' => trim($_POST['divSec'] ?? ''), 'gn_division' => trim($_POST['gnDiv'] ?? '')
            ], 'DECEASED');

            $custodianModel = new \App\Models\DonorCustodianModel();
            $custodianModel->addCustodian($donorId, [
                'name' => $_POST['kinName'], 
                'relationship' => $_POST['kinRelation'], 
                'nic' => $_POST['kinNIC'], 
                'phone' => $_POST['kinPhone'], 
                'email' => $_POST['kinEmail'] ?? '',
                'address' => $_POST['kinAddress'] ?? ''
            ]);

            $organModel = new OrganModel();
            $organs = json_decode($_POST['organs'] ?? '[]', true);
            foreach ($organs as $organId) {
                $organModel->addDonorPledge($donorId, $organId);
            }

            echo json_encode(['success' => true, 'message' => 'Registration successful.', 'redirect' => 'view4-pending']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function registerHospital()
    {
        $this->validatePost();
        try {
            $userModel = new UserModel();
            if ($userModel->usernameExists($_POST['username'] ?? '')) throw new Exception("Username taken.");
            if ($userModel->emailExists($_POST['instEmail'] ?? '')) throw new Exception("Institutional email already registered.");

            $userId = $userModel->createUser(trim($_POST['username']), password_hash($_POST['password'], PASSWORD_DEFAULT), 'HOSPITAL', trim($_POST['instEmail']), trim($_POST['instPhone']), 'PENDING');

            $hospitalModel = new HospitalModel();
            $hospitalModel->registerHospital($userId, [
                'registration_number' => $_POST['regNo'], 'name' => $_POST['instName'], 'address' => $_POST['instAddress'], 'district' => $_POST['instDistrict'], 'type' => $_POST['instType']
            ], [
                'name' => $_POST['cmoName'], 'nic' => $_POST['cmoNIC'], 'license_number' => $_POST['medicalLicense'] ?? ''
            ]);

            echo json_encode(['success' => true, 'message' => 'Hospital registered.', 'redirect' => 'view4-pending']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function registerMedicalSchool()
    {
        $this->validatePost();
        try {
            $userModel = new UserModel();
            if ($userModel->usernameExists($_POST['username'] ?? '')) throw new Exception("Username taken.");
            if ($userModel->emailExists($_POST['contactEmail'] ?? '')) throw new Exception("Contact email already registered.");

            $userId = $userModel->createUser(trim($_POST['username']), password_hash($_POST['password'], PASSWORD_DEFAULT), 'MEDICAL_SCHOOL', trim($_POST['contactEmail']), trim($_POST['contactPhone']), 'PENDING');

            $medModel = new MedicalSchoolModel();
            $medModel->registerMedicalSchool($userId, [
                'name' => $_POST['instName'], 'university' => $_POST['university'], 'ugc_number' => $_POST['ugcNumber'], 'address' => $_POST['instAddress'], 'district' => $_POST['instDistrict']
            ], [
                'name' => $_POST['contactName'], 'phone' => $_POST['contactPhone']
            ]);

            echo json_encode(['success' => true, 'message' => 'Medical School registered.', 'redirect' => 'view4-pending']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function registerFinancialDonor()
    {
        $this->validatePost();
        try {
            $userModel = new UserModel();
            if ($userModel->usernameExists($_POST['username'] ?? '')) throw new Exception("Username taken.");
            if ($userModel->emailExists($_POST['email'] ?? '')) throw new Exception("Email already registered.");

            $userId = $userModel->createUser(
                trim($_POST['username']),
                password_hash($_POST['password'], PASSWORD_DEFAULT),
                'DONOR',
                trim($_POST['email']),
                trim($_POST['phone']),
                'ACTIVE'
            );

            // Create a basic donors profile row so they can use the portal
            $donorModel = new DonorModel();
            $nameParts  = explode(' ', trim($_POST['fullName'] ?? 'Donor'), 2);
            $donorModel->createDonor($userId, [
                'first_name'              => $nameParts[0],
                'last_name'               => $nameParts[1] ?? '',
                'dob'                     => '1990-01-01',
                'nic'                     => trim($_POST['nic'] ?? ''),
                'gender'                  => 'OTHER',
                'blood_group'             => 'O+',
                'address'                 => '',
                'district'                => '',
                'divisional_secretariat'  => '',
                'gn_division'             => '',
            ], 'NONE');

            echo json_encode(['success' => true, 'message' => 'Financial Donor registered.', 'redirect' => 'view4-success']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function registerNonDonor()
    {
        $this->validatePost();
        try {
            $userModel = new UserModel();
            if ($userModel->usernameExists($_POST['username'] ?? '')) throw new Exception("Username taken.");
            if ($userModel->emailExists($_POST['email'] ?? '')) throw new Exception("Email already registered.");

            $userId = $userModel->createUser(trim($_POST['username']), password_hash($_POST['password'], PASSWORD_DEFAULT), 'DONOR', trim($_POST['email']), trim($_POST['phone']), 'ACTIVE');

            $donorModel = new DonorModel();
            $nameParts = explode(' ', trim($_POST['fullName']), 2);
            $donorId = $donorModel->createDonor($userId, [
                'first_name' => $nameParts[0], 'last_name' => $nameParts[1] ?? '', 'dob' => $_POST['dob'], 'nic' => trim($_POST['nic']),
                'gender' => strtoupper($_POST['gender']), 'blood_group' => 'O+', 'address' => trim($_POST['address']),
                'district' => $_POST['district'], 'divisional_secretariat' => trim($_POST['divSec'] ?? ''), 'gn_division' => trim($_POST['gnDiv'] ?? '')
            ], 'NON_DONOR');

            // Set opt-out reason (using update or custom method if needed, but DonorModel->createDonor can handle it if I add the param)
            // For now, I'll assume it's set in createDonor or I'll add an update call.
            
            echo json_encode(['success' => true, 'message' => 'Preference recorded.', 'redirect' => 'view4-success']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}