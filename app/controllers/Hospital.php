<?php 

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AftercarePatientModel;
use App\Models\HospitalModel;

class Hospital {
    use Controller;

    public function index(){
        // Session check
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
            redirect('login');
        }

        $hospitalModel = new HospitalModel();
        $userId = $_SESSION['user_id'];
        
        // Get hospital details
        $hospital = $hospitalModel->getHospitalByUserId($userId);
        $hospitalId = $hospital ? (int)$hospital->id : 0;
        
        if (!$hospital) {
            $hospital_registration = $_SESSION['hospital_registration'] ?? 'HOSP001';
            $hospital_name = $_SESSION['hospital_name'] ?? 'Hospital';
            $hospital_details = [
                'name' => $hospital_name,
                'registration' => $hospital_registration,
                'email' => $_SESSION['email'] ?? 'admin@lifeconnect.lk',
                'role' => $_SESSION['role'] ?? 'Hospital',
                'last_login' => date('Y-m-d H:i:s'),
                'status' => 'Active'
            ];
        } else {
            $hospital_registration = $hospital->registration_number;
            $hospital_name = $hospital->name;
            $hospital_details = [
                'name' => $hospital->name,
                'registration' => $hospital->registration_number,
                'email' => $_SESSION['email'] ?? 'admin@lifeconnect.lk',
                'role' => $_SESSION['role'] ?? 'Medical Coordinator',
                'address' => $hospital->address,
                'phone' => $hospital->contact_number ?? 'Not specified',
                'status' => $hospital->verification_status ?? 'Active',
                'last_login' => date('Y-m-d H:i:s')
            ];
        }

        // Handle POST requests
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost($hospital_registration);
        }

        // Fetch Data - ensure they are arrays even if model returns false
        $organ_requests = $hospitalModel->getOrganRequests($hospital_registration) ?: [];
        $organs = $hospitalModel->getAvailableOrgans() ?: [];
        $recipients = $hospitalModel->getRecipients($hospital_registration) ?: [];
        $success_stories = $hospitalModel->getSuccessStories($hospital_registration) ?: [];
        $aftercare_appointments = $hospitalModel->getAftercareAppointments($hospital_registration) ?: [];
        $lab_reports = $hospitalModel->getLabReports($hospital_registration) ?: [];
        $eligibility_pledges = $hospitalModel->getApprovedPledgesForEligibility($hospitalId) ?: [];
        $test_results = $hospitalModel->getTestResultsByHospitalId($hospitalId) ?: [];

        // Calculate Stats
        $stats = [
            'total_organ_requests' => count($organ_requests),
            // Treat PENDING (and legacy OPEN) as "pending" requests
            'pending_requests' => count(array_filter($organ_requests, function($req) {
                $s = strtoupper(trim((string)($req->status ?? '')));
                return $s === 'PENDING' || $s === 'OPEN';
            })),
            // Treat MATCHED as "approved" for existing UI counters
            'approved_requests' => count(array_filter($organ_requests, function($req) {
                $s = strtoupper(trim((string)($req->status ?? '')));
                return $s === 'MATCHED';
            })),
            'total_recipients' => count($recipients),
            'active_recipients' => count(array_filter($recipients, function($rec) { return $rec->status === 'Active'; })),
            'recovered_recipients' => count(array_filter($recipients, function($rec) { return $rec->status === 'Recovered'; })),
            'total_success_stories' => count($success_stories),
            'approved_stories' => count(array_filter($success_stories, function($story) { return $story->status === 'Approved'; })),
            'total_appointments' => count($aftercare_appointments),
            'scheduled_appointments' => count(array_filter($aftercare_appointments, function($apt) { return $apt->status === 'Scheduled'; }))
        ];

        $data = [
            'hospital_name' => $hospital_name,
            'hospital_registration' => $hospital_registration,
            'hospital_details' => $hospital_details,
            'organ_requests' => $organ_requests,
            'organs' => $organs,
            'recipients' => $recipients,
            'success_stories' => $success_stories,
            'aftercare_appointments' => $aftercare_appointments,
            'lab_reports' => $lab_reports,
            'test_results' => $test_results,
            'eligibility_pledges' => $eligibility_pledges,
            'stats' => $stats
        ];

        $this->view('hospital/index', $data);
    }

    private function handlePost($regNo) {
        $hospitalModel = new HospitalModel();
        $action = $_POST['action'] ?? '';

        $allowedBloodGroups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
        $allowedGenders = ['Male', 'Female', 'Other'];

        switch ($action) {
            case 'add_organ_request':
                $recipientAge = $_POST['recipient_age'] ?? null;
                $bloodGroup = trim((string)($_POST['blood_group'] ?? ''));
                $gender = trim((string)($_POST['gender'] ?? ''));
                $hlaTyping = trim((string)($_POST['hla_typing'] ?? ''));
                $transplantReason = trim((string)($_POST['transplant_reason'] ?? ''));

                $ageNum = filter_var($recipientAge, FILTER_VALIDATE_INT);
                if ($ageNum === false || $ageNum < 18 || $ageNum > 80) {
                    $_SESSION['flash_error'] = 'Recipient age must be between 18 and 80.';
                    break;
                }
                if (!in_array($bloodGroup, $allowedBloodGroups, true)) {
                    $_SESSION['flash_error'] = 'Please select a valid blood group.';
                    break;
                }
                if (!in_array($gender, $allowedGenders, true)) {
                    $_SESSION['flash_error'] = 'Please select a valid gender.';
                    break;
                }
                if ($transplantReason === '') {
                    $_SESSION['flash_error'] = 'Reason for transplant is required.';
                    break;
                }

                $data = [
                    'registration_no' => $regNo,
                    // Prefer organ_id (matches organs table IDs)
                    'organ_id' => $_POST['organ_id'] ?? '',
                    // Backwards compatibility if any view still posts organ_type
                    'organ_type' => $_POST['organ_type'] ?? '',
                    'urgency' => $_POST['urgency'] ?? '',
                    'recipient_age' => $ageNum,
                    'blood_group' => $bloodGroup,
                    'gender' => $gender,
                    'hla_typing' => $hlaTyping,
                    'transplant_reason' => $transplantReason
                ];
                if ($hospitalModel->addOrganRequest($data)) {
                    $_SESSION['flash_success'] = "Organ request added successfully.";
                }
                break;

            case 'edit_organ_request':
                $requestId = $_POST['request_id'] ?? null;
                $recipientAge = $_POST['recipient_age'] ?? null;
                $bloodGroup = trim((string)($_POST['blood_group'] ?? ''));
                $gender = trim((string)($_POST['gender'] ?? ''));
                $hlaTyping = trim((string)($_POST['hla_typing'] ?? ''));
                $transplantReason = trim((string)($_POST['transplant_reason'] ?? ''));
                $editedReason = trim((string)($_POST['edited_reason'] ?? ($_POST['urgency_reason'] ?? '')));

                $ageNum = filter_var($recipientAge, FILTER_VALIDATE_INT);
                if ($ageNum === false || $ageNum < 18 || $ageNum > 80) {
                    $_SESSION['flash_error'] = 'Recipient age must be between 18 and 80.';
                    break;
                }
                if (!in_array($bloodGroup, $allowedBloodGroups, true)) {
                    $_SESSION['flash_error'] = 'Please select a valid blood group.';
                    break;
                }
                if (!in_array($gender, $allowedGenders, true)) {
                    $_SESSION['flash_error'] = 'Please select a valid gender.';
                    break;
                }
                if ($transplantReason === '') {
                    $_SESSION['flash_error'] = 'Reason for transplant is required.';
                    break;
                }

                if ($editedReason === '') {
                    $_SESSION['flash_error'] = 'Reason for change is required.';
                    break;
                }

                $data = [
                    'urgency' => $_POST['urgency'] ?? '',
                    // Persist edit reason (DB column is edited_reason in current schema)
                    'edited_reason' => $editedReason,
                    // Backwards compatibility
                    'urgency_reason' => $editedReason,
                    'recipient_age' => $ageNum,
                    'blood_group' => $bloodGroup,
                    'gender' => $gender,
                    'hla_typing' => $hlaTyping,
                    'transplant_reason' => $transplantReason
                ];
                if ($hospitalModel->updateOrganRequest($requestId, $data)) {
                    $_SESSION['flash_success'] = "Organ request updated successfully.";
                }
                break;

            case 'delete_organ_request':
                if ($hospitalModel->deleteOrganRequest($_POST['request_id'])) {
                    $_SESSION['flash_success'] = "Organ request deleted successfully.";
                }
                break;

            case 'add_recipient':
                $data = [
                    'nic' => $_POST['nic'],
                    'name' => $_POST['name'],
                    'organ_received' => $_POST['organ_received'],
                    'surgery_date' => $_POST['surgery_date'],
                    'treatment_notes' => $_POST['treatment_notes'],
                    'hospital_registration_no' => $regNo
                ];
                if ($hospitalModel->addRecipient($data)) {
                    $_SESSION['flash_success'] = "Recipient added successfully.";
                }
                break;

            case 'update_recipient':
                $data = [
                    'recipient_id' => $_POST['recipient_id'],
                    'nic' => $_POST['nic'],
                    'name' => $_POST['name'],
                    'organ_received' => $_POST['organ_received'],
                    'surgery_date' => $_POST['surgery_date'],
                    'treatment_notes' => $_POST['treatment_notes'],
                    'status' => $_POST['status']
                ];
                if ($hospitalModel->updateRecipient($data)) {
                    $_SESSION['flash_success'] = "Recipient updated successfully.";
                }
                break;

            case 'delete_recipient':
                if ($hospitalModel->deleteRecipient($_POST['recipient_id'])) {
                    $_SESSION['flash_success'] = "Recipient deleted successfully.";
                }
                break;

            case 'add_success_story':
                $data = [
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'success_date' => $_POST['success_date'],
                    'hospital_registration_no' => $regNo
                ];
                if ($hospitalModel->addSuccessStory($data)) {
                    $_SESSION['flash_success'] = "Success story added successfully.";
                }
                break;

            case 'update_success_story':
                $data = [
                    'story_id' => $_POST['story_id'],
                    'title' => $_POST['title'],
                    'description' => $_POST['description'],
                    'success_date' => $_POST['success_date'],
                    'status' => $_POST['status']
                ];
                if ($hospitalModel->updateSuccessStory($data)) {
                    $_SESSION['flash_success'] = "Success story updated successfully.";
                }
                break;

            case 'delete_success_story':
                if ($hospitalModel->deleteSuccessStory($_POST['story_id'])) {
                    $_SESSION['flash_success'] = "Success story deleted successfully.";
                }
                break;

            case 'submit_lab_report':
                $data = [
                    'donor_id' => $_POST['donor_id'] ?? null,
                    'hospital_registration_no' => $regNo,
                    'test_type' => $_POST['test_type'],
                    'test_date' => $_POST['test_date'],
                    'result_status' => $_POST['result_status'],
                    'result_notes' => $_POST['result_notes'] ?? '',
                    'blood_type' => $_POST['blood_type'] ?? '',
                    'recipient_id' => !empty($_POST['recipient_id']) ? $_POST['recipient_id'] : null
                ];
                if ($hospitalModel->addLabReport($data)) {
                    $_SESSION['flash_success'] = "Lab report submitted successfully.";
                }
                break;

            case 'edit_lab_report':
                $reportId = $_POST['report_id'];
                $before = $hospitalModel->getLabReportById($reportId);
                $data = [
                    'donor_id' => !empty($_POST['donor_id']) ? $_POST['donor_id'] : null,
                    'test_type' => $_POST['test_type'],
                    'test_date' => $_POST['test_date'],
                    'result_status' => $_POST['result_status'],
                    'result_notes' => $_POST['result_notes'] ?? '',
                    'blood_type' => $_POST['blood_type'] ?? '',
                    'recipient_id' => !empty($_POST['recipient_id']) ? $_POST['recipient_id'] : null
                ];
                if ($hospitalModel->updateLabReport($reportId, $data)) {
                    $_SESSION['flash_success'] = "Lab report updated successfully.";

                    // Notify donor when schedule is edited
                    $donorIdForNotify = (int)($data['donor_id'] ?? 0);
                    if ($donorIdForNotify <= 0 && $before && !empty($before->donor_id)) {
                        $donorIdForNotify = (int)$before->donor_id;
                    }

                    if ($donorIdForNotify > 0) {
                        $oldType = $before ? (string)($before->test_type ?? '') : '';
                        $oldDate = $before ? (string)($before->test_date ?? '') : '';
                        $newType = (string)($data['test_type'] ?? '');
                        $newDate = (string)($data['test_date'] ?? '');

                        $msg = "Your appointment schedule was updated by the hospital.";
                        if ($oldType !== '' || $oldDate !== '') {
                            $msg .= "\nPrevious: " . ($oldType !== '' ? $oldType : '—') . " on " . ($oldDate !== '' ? $oldDate : '—');
                        }
                        $msg .= "\nUpdated: " . ($newType !== '' ? $newType : '—') . " on " . ($newDate !== '' ? $newDate : '—');

                        $hospitalModel->notifyDonor(
                            $donorIdForNotify,
                            'Appointment updated',
                            $msg,
                            'INFO'
                        );
                    }
                }
                break;

            case 'delete_lab_report':
                $rid = (int)($_POST['report_id'] ?? 0);
                if ($rid > 0 && $hospitalModel->softDeleteLabReport($rid)) {
                    $_SESSION['flash_success'] = "Schedule deleted successfully.";
                } else {
                    $_SESSION['flash_error'] = "Failed to delete schedule.";
                }
                break;

            case 'update_lab_report_status':
                $reportId = $_POST['report_id'];
                $status = $_POST['status'];
                if ($hospitalModel->updateLabReportStatus($reportId, $status)) {
                    $_SESSION['flash_success'] = "Lab report status updated to " . $status . ".";
                }
                break;

            case 'schedule_appointment':
                $donorId = (int)($_POST['donor_id'] ?? 0);
                $organId = (int)($_POST['organ_id'] ?? 0);
                $tests = $_POST['tests'] ?? [];
                if (!is_array($tests)) $tests = [];
                $tests = array_values(array_filter(array_map('trim', $tests), fn($t) => $t !== ''));

                $testDate = trim((string)($_POST['test_date'] ?? ''));
                $notes = trim((string)($_POST['notes'] ?? ''));

                if ($donorId <= 0 || $organId <= 0 || empty($tests) || $testDate === '') {
                    $_SESSION['flash_error'] = 'Please select donor, organ, at least one test, and a date.';
                    break;
                }

                $organName = $hospitalModel->getOrganNameById($organId) ?? 'Organ';
                $scheduledCount = 0;
                foreach ($tests as $t) {
                    $data = [
                        'donor_id' => $donorId,
                        'hospital_registration_no' => $regNo,
                        'test_type' => $organName . ' - ' . $t,
                        'test_date' => $testDate,
                        'result_status' => 'Pending',
                        'result_notes' => $notes,
                    ];
                    if ($hospitalModel->addLabReport($data)) {
                        $scheduledCount++;
                    }
                }

                if ($scheduledCount > 0) {
                    $hospitalModel->notifyDonor(
                        $donorId,
                        'Appointment scheduled',
                        'Your hospital appointment for ' . $organName . ' tests has been scheduled on ' . $testDate . '. Please review and approve/reject in your Appointments page.',
                        'INFO'
                    );
                    $_SESSION['flash_success'] = "Appointment scheduled successfully ($scheduledCount test(s)).";
                } else {
                    $_SESSION['flash_error'] = 'Failed to schedule appointment.';
                }
                break;

            case 'submit_test_result':
                $donorId = (int)($_POST['donor_id'] ?? 0);
                $testName = trim((string)($_POST['test_name'] ?? ''));
                $testDate = trim((string)($_POST['test_date'] ?? ''));
                $resultValue = trim((string)($_POST['result_value'] ?? ''));

                if ($donorId <= 0 || $testName === '' || $testDate === '') {
                    $_SESSION['flash_error'] = 'Please select donor, test name, and test date.';
                    break;
                }

                $documentPath = null;
                if (!empty($_FILES['document']) && isset($_FILES['document']['tmp_name']) && is_uploaded_file($_FILES['document']['tmp_name'])) {
                    $uploadDir = __DIR__ . '/../../public/assets/uploads/test_results';
                    if (!is_dir($uploadDir)) {
                        @mkdir($uploadDir, 0775, true);
                    }
                    $originalName = (string)($_FILES['document']['name'] ?? '');
                    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                    $allowed = ['pdf', 'png', 'jpg', 'jpeg', 'webp'];
                    if ($ext && in_array($ext, $allowed, true)) {
                        $safeBase = 'tr_' . $donorId . '_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4));
                        $fileName = $safeBase . '.' . $ext;
                        $dest = $uploadDir . '/' . $fileName;
                        if (move_uploaded_file($_FILES['document']['tmp_name'], $dest)) {
                            // Public URL path (web root points at /public)
                            $documentPath = '/assets/uploads/test_results/' . $fileName;
                        }
                    }
                }

                $hospitalIdForVerification = null;
                if (!empty($_SESSION['user_id'])) {
                    $h = $hospitalModel->getHospitalByUserId((int)$_SESSION['user_id']);
                    if ($h && !empty($h->id)) {
                        $hospitalIdForVerification = (int)$h->id;
                    }
                }

                if ($hospitalModel->addTestResult([
                    'donor_id' => $donorId,
                    'test_name' => $testName,
                    'result_value' => $resultValue,
                    'document_path' => $documentPath,
                    'test_date' => $testDate,
                    'verified_by_hospital_id' => $hospitalIdForVerification,
                ])) {
                    $hospitalModel->notifyDonor(
                        $donorId,
                        'New test result available',
                        'A new test result (' . $testName . ') was uploaded to your profile. You can view it under Test Results.',
                        'INFO'
                    );
                    $_SESSION['flash_success'] = 'Test result uploaded successfully.';
                } else {
                    $_SESSION['flash_error'] = 'Failed to upload test result.';
                }
                break;

            case 'delete_scheduled_appointment':
                if ($hospitalModel->deleteLabReport($_POST['appointment_id'])) {
                    $_SESSION['flash_success'] = "Appointment deleted successfully.";
                }
                break;

            case 'update_hospital_profile':
                $data = [
                    'name' => $_POST['hospital_name'],
                    'address' => $_POST['address'],
                    'phone' => $_POST['phone'],
                    'registration' => $regNo
                ];
                if ($hospitalModel->updateHospitalProfile($data)) {
                    $_SESSION['hospital_name'] = $_POST['hospital_name']; // Update session too
                    $_SESSION['flash_success'] = "Profile updated successfully.";
                }
                break;
        }

        redirect('hospital');
    }

    public function organRequests() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
            redirect('login');
        }

        $hospitalModel = new HospitalModel();
        $userId = $_SESSION['user_id'];
        $hospital = $hospitalModel->getHospitalByUserId($userId);
        $hospital_registration = $hospital->registration_number ?? $_SESSION['hospital_registration'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost($hospital_registration);
        }

        $organ_requests = $hospitalModel->getOrganRequests($hospital_registration) ?: [];
        $organs = $hospitalModel->getAvailableOrgans() ?: [];
        $hospital_details = [
            'name' => $hospital->name,
            'registration' => $hospital->registration_number,
            'registration_number' => $hospital->registration_number,
            'role' => 'HOSPITAL',
            'email' => $_SESSION['email'] ?? 'admin@lifeconnect.lk',
            'address' => $hospital->address ?? 'Not specified',
            'status' => $hospital->verification_status ?? 'Active',
            'last_login' => date('Y-m-d H:i:s')
        ];

        $data = [
            'hospital_name' => $hospital->name,
            'hospital_registration' => $hospital_registration,
            'hospital_details' => $hospital_details,
            'organ_requests' => $organ_requests,
            'organs' => $organs,
            'success_stories' => []
        ];

        $this->view('hospital/organ-requests', $data);
    }

    public function eligibility() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
            redirect('login');
        }

        $hospitalModel = new HospitalModel();
        $userId = $_SESSION['user_id'];
        $hospital = $hospitalModel->getHospitalByUserId($userId);
        $hospitalId = $hospital ? (int)$hospital->id : 0;
        $hospital_registration = $hospital->registration_number ?? $_SESSION['hospital_registration'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost($hospital_registration);
        }

        $hospital_details = [
            'name' => $hospital->name,
            'registration' => $hospital->registration_number,
            'registration_number' => $hospital->registration_number,
            'role' => 'HOSPITAL',
            'email' => $_SESSION['email'] ?? 'admin@lifeconnect.lk',
            'address' => $hospital->address ?? 'Not specified',
            'status' => $hospital->verification_status ?? 'Active',
            'last_login' => date('Y-m-d H:i:s')
        ];

        $data = [
            'hospital_name' => $hospital->name,
            'hospital_registration' => $hospital_registration,
            'hospital_details' => $hospital_details
        ];

        $data['eligibility_pledges'] = $hospitalModel->getApprovedPledgesForEligibility($hospitalId) ?: [];

        $this->view('hospital/eligibility', $data);
    }

    public function recipients() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
            redirect('login');
        }

        $hospitalModel = new HospitalModel();
        $userId = $_SESSION['user_id'];
        $hospital = $hospitalModel->getHospitalByUserId($userId);
        $hospital_registration = $hospital->registration_number ?? $_SESSION['hospital_registration'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost($hospital_registration);
        }

        $recipients = $hospitalModel->getRecipients($hospital_registration) ?: [];
        $hospital_details = [
            'name' => $hospital->name,
            'registration' => $hospital->registration_number,
            'registration_number' => $hospital->registration_number,
            'role' => 'HOSPITAL',
            'email' => $_SESSION['email'] ?? 'admin@lifeconnect.lk',
            'address' => $hospital->address ?? 'Not specified',
            'status' => $hospital->verification_status ?? 'Active',
            'last_login' => date('Y-m-d H:i:s')
        ];

        $data = [
            'hospital_name' => $hospital->name,
            'hospital_registration' => $hospital_registration,
            'hospital_details' => $hospital_details,
            'recipients' => $recipients
        ];

        $this->view('hospital/recipients', $data);
    }

    public function stories() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
            redirect('login');
        }

        $hospitalModel = new HospitalModel();
        $userId = $_SESSION['user_id'];
        $hospital = $hospitalModel->getHospitalByUserId($userId);
        $hospital_registration = $hospital->registration_number ?? $_SESSION['hospital_registration'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost($hospital_registration);
        }

        $success_stories = $hospitalModel->getSuccessStories($hospital_registration) ?: [];
        $hospital_details = [
            'name' => $hospital->name,
            'registration' => $hospital->registration_number,
            'registration_number' => $hospital->registration_number,
            'role' => 'HOSPITAL',
            'email' => $_SESSION['email'] ?? 'admin@lifeconnect.lk',
            'address' => $hospital->address ?? 'Not specified',
            'status' => $hospital->verification_status ?? 'Active',
            'last_login' => date('Y-m-d H:i:s')
        ];

        $data = [
            'hospital_name' => $hospital->name,
            'hospital_registration' => $hospital_registration,
            'hospital_details' => $hospital_details,
            'success_stories' => $success_stories
        ];

        $this->view('hospital/stories', $data);
    }

    public function addpatient() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
            redirect('login');
        }

        $hospitalModel = new HospitalModel();
        $userId = $_SESSION['user_id'];
        $hospital = $hospitalModel->getHospitalByUserId($userId);
        $hospital_registration = $hospital->registration_number ?? $_SESSION['hospital_registration'];

        $hospital_details = [
            'name' => $hospital->name,
            'registration' => $hospital->registration_number,
            'role' => 'HOSPITAL',
            'email' => $_SESSION['email'] ?? 'admin@lifeconnect.lk',
        ];

        $data = [
            'hospital_name' => $hospital->name,
            'hospital_details' => $hospital_details,
            'hospital_registration' => $hospital_registration,
        ];

        $this->view('hospital/addpatient', $data);
    }

    public function addpatientRecipient()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
            redirect('login');
        }

        $hospitalModel = new HospitalModel();
        $userId = $_SESSION['user_id'];
        $hospital = $hospitalModel->getHospitalByUserId($userId);
        $hospital_registration = $hospital->registration_number ?? $_SESSION['hospital_registration'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim((string)($_POST['recipient_name'] ?? ''));
            $nic = trim((string)($_POST['recipient_nic'] ?? ''));
            $requestedReg = trim((string)($_POST['registration_number'] ?? ''));

            if ($name === '' || $nic === '') {
                $_SESSION['flash_error'] = 'Full name and NIC are required.';
                redirect('hospital/addpatient/recipient');
            }

            try {
                $aftercarePatientModel = new AftercarePatientModel();

                $registrationNumber = $aftercarePatientModel->createRecipientAccount([
                    'full_name' => $name,
                    'nic' => $nic,
                    'hospital_registration_no' => $hospital_registration,
                    'registration_number' => $requestedReg,
                    'age' => !empty($_POST['recipient_age']) ? (int)$_POST['recipient_age'] : null,
                    'gender' => !empty($_POST['recipient_gender']) ? trim((string)$_POST['recipient_gender']) : null,
                    'blood_group' => !empty($_POST['recipient_blood_group']) ? trim((string)$_POST['recipient_blood_group']) : null,
                    'contact_details' => !empty($_POST['recipient_contact']) ? trim((string)$_POST['recipient_contact']) : null,
                    'medical_details' => !empty($_POST['recipient_medical']) ? trim((string)$_POST['recipient_medical']) : null,
                ]);

                $_SESSION['flash_success'] = 'Recipient aftercare account created successfully.';
                $_SESSION['generated_aftercare_credentials'] = [
                    'registration_number' => $registrationNumber,
                    'password' => $nic,
                ];
            } catch (\Throwable $e) {
                $msg = (string)($e->getMessage() ?? '');
                if (stripos($msg, 'aftercare_patients') !== false && (stripos($msg, 'doesn\'t exist') !== false || stripos($msg, 'Base table or view not found') !== false)) {
                    $_SESSION['flash_error'] = 'Aftercare tables not found. Run the migration once: /life-connect/migration_aftercare_patients.php';
                } else {
                    $_SESSION['flash_error'] = $msg ?: 'Failed to create aftercare account.';
                }
            }

            redirect('hospital/addpatient/recipient');
        }

        $hospital_details = [
            'name' => $hospital->name,
            'registration' => $hospital->registration_number,
            'role' => 'HOSPITAL',
            'email' => $_SESSION['email'] ?? 'admin@lifeconnect.lk',
        ];

        $this->view('hospital/addpatient-recipient', [
            'hospital_name' => $hospital->name,
            'hospital_details' => $hospital_details,
            'hospital_registration' => $hospital_registration,
        ]);
    }

    public function addpatientDonor()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
            redirect('login');
        }

        $hospitalModel = new HospitalModel();
        $userId = $_SESSION['user_id'];
        $hospital = $hospitalModel->getHospitalByUserId($userId);
        $hospital_registration = $hospital->registration_number ?? $_SESSION['hospital_registration'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nic = trim((string)($_POST['donor_nic'] ?? ''));
            if ($nic === '') {
                $_SESSION['flash_error'] = 'Donor NIC is required.';
                redirect('hospital/addpatient/donor');
            }

            // Find donor in users table
            $donorUser = $hospitalModel->query(
                "SELECT u.id as user_id
                 FROM users u
                 JOIN donors d ON u.id = d.user_id
                 WHERE d.nic_number = :nic
                 LIMIT 1",
                [':nic' => $nic]
            );

            if (!empty($donorUser)) {
                $uid = (int)$donorUser[0]->user_id;
                $hospitalModel->query(
                    "UPDATE users SET aftercare_access = 1 WHERE id = :uid",
                    [':uid' => $uid]
                );

                // Also record donor patient details in aftercare_patients
                try {
                    $donorRow = $hospitalModel->query(
                        "SELECT first_name, last_name, gender, blood_group, nic_number
                         FROM donors
                         WHERE nic_number = :nic
                         LIMIT 1",
                        [':nic' => $nic]
                    );

                    if (!empty($donorRow)) {
                        $d = $donorRow[0];
                        $fullName = trim((string)($d->first_name ?? '') . ' ' . (string)($d->last_name ?? ''));

                        $aftercarePatientModel = new AftercarePatientModel();
                        $aftercarePatientModel->upsertDonorPatient([
                            'full_name' => $fullName !== '' ? $fullName : $nic,
                            'nic' => (string)($d->nic_number ?? $nic),
                            'hospital_registration_no' => (string)$hospital_registration,
                            'gender' => $d->gender ?? null,
                            'blood_group' => $d->blood_group ?? null,
                        ]);
                    }
                } catch (\Throwable $e) {
                    // Don't block access grant if logging donor details fails
                }

                $_SESSION['flash_success'] = 'Aftercare access successfully granted to donor.';
            } else {
                $_SESSION['flash_error'] = "Donor not found with NIC {$nic}.";
            }

            redirect('hospital/addpatient/donor');
        }

        $hospital_details = [
            'name' => $hospital->name,
            'registration' => $hospital->registration_number,
            'role' => 'HOSPITAL',
            'email' => $_SESSION['email'] ?? 'admin@lifeconnect.lk',
        ];

        $donors = [];
        try {
            $hospitalId = !empty($hospital->id) ? (int)$hospital->id : 0;
            if ($hospitalId > 0) {
                $donors = $hospitalModel->searchDonorsForHospital($hospitalId, '') ?: [];
            }
        } catch (\Throwable $e) {
            $donors = [];
        }

        $this->view('hospital/addpatient-donor', [
            'hospital_name' => $hospital->name,
            'hospital_details' => $hospital_details,
            'donors' => $donors,
        ]);
    }

    /**
     * API endpoint to search recipients by ID or name
     */
    public function searchRecipients() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        header('Content-Type: application/json');

        try {
            $hospitalModel = new HospitalModel();
            $userId = $_SESSION['user_id'];
            $hospital = $hospitalModel->getHospitalByUserId($userId);
            $hospital_registration = $hospital->registration_number ?? $_SESSION['hospital_registration'];

            // Get search query
            $searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';

            if (empty($searchQuery)) {
                // Return all recipients if no search query
                $recipients = $hospitalModel->getRecipients($hospital_registration) ?: [];
                echo json_encode(['success' => true, 'data' => $recipients]);
                return;
            }

            // Search by NIC (patient ID) or name
            $query = "SELECT * FROM recipients 
                      WHERE hospital_registration_no = :reg_no 
                      AND (nic LIKE :search OR name LIKE :search)
                      ORDER BY created_at DESC";
            
            $searchParam = '%' . $searchQuery . '%';
            $results = $hospitalModel->query($query, [
                ':reg_no' => $hospital_registration,
                ':search' => $searchParam
            ]);

            echo json_encode(['success' => true, 'data' => $results ?: []]);
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Export recipients in various formats (PDF, CSV, Excel)
     */
    public function exportRecipients() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
            http_response_code(401);
            echo 'Unauthorized';
            exit;
        }

        try {
            $hospitalModel = new HospitalModel();
            $userId = $_SESSION['user_id'];
            $hospital = $hospitalModel->getHospitalByUserId($userId);
            $hospital_registration = $hospital->registration_number ?? $_SESSION['hospital_registration'];
            $hospital_name = $hospital->name ?? 'Hospital';

            // Get format from request
            // NOTE: PDF export disabled (no external libraries allowed).
            $format = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : 'csv';
            
            // Get all recipients for this hospital
            $recipients = $hospitalModel->getRecipients($hospital_registration) ?: [];

            switch($format) {
                case 'csv':
                    $this->generateCSV($recipients, $hospital_name);
                    break;
                case 'xlsx':
                case 'excel':
                    $this->generateExcel($recipients, $hospital_name);
                    break;
                case 'svg':
                    $this->generateSVG($recipients, $hospital_name);
                    break;
                case 'pdf':
                    http_response_code(400);
                    echo 'PDF export is disabled. Please use CSV or Excel.';
                    break;
                default:
                    http_response_code(400);
                    echo 'Invalid format';
            }
        } catch(\Exception $e) {
            http_response_code(500);
            echo 'Error: ' . $e->getMessage();
        }
    }

    /**
     * Generate CSV export
     */
    private function generateCSV($recipients, $hospital_name) {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="Recipient_Report_' . date('Y-m-d_H-i-s') . '.csv"');
        
        $output = fopen('php://output', 'w');
        
        // Write BOM for UTF-8 Excel compatibility
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Write branding header
        fputcsv($output, ['LifeConnect Sri Lanka - Recipient Patient Report']);
        fputcsv($output, []);
        fputcsv($output, ['Hospital:', $hospital_name]);
        fputcsv($output, ['Generated:', date('Y-m-d H:i:s')]);
        fputcsv($output, []);
        
        // Write table header
        fputcsv($output, [
            'Patient NIC',
            'Patient Name',
            'Organ Received',
            'Surgery Date',
            'Status',
            'Treatment Notes',
            'Date Added'
        ]);
        
        // Write data rows
        foreach($recipients as $recipient) {
            fputcsv($output, [
                $recipient->nic,
                $recipient->name,
                $recipient->organ_received,
                date('Y-m-d', strtotime($recipient->surgery_date)),
                $recipient->status,
                $recipient->treatment_notes,
                date('Y-m-d', strtotime($recipient->created_at))
            ]);
        }
        
        fclose($output);
        exit;
    }

    // PDF export removed (no external libraries allowed)

    /**
     * Generate Excel XML export
     */
    private function generateExcel($recipients, $hospital_name) {
        header('Content-Type: application/vnd.ms-excel; charset=utf-8');
        header('Content-Disposition: attachment; filename="Recipient_Report_' . date('Y-m-d_H-i-s') . '.xlsx"');
        
        // Excel XML format (simplified)
        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        echo 'xmlns:x="urn:schemas-microsoft-com:office:excel"' . "\n";
        echo 'xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet"' . "\n";
        echo 'xmlns:html="http://www.w3.org/TR/REC-html40">' . "\n";
        
        echo '<DocumentProperties xmlns="urn:schemas-microsoft-com:office:office">' . "\n";
        echo '<Title>Recipient Report</Title>' . "\n";
        echo '<Subject>' . $hospital_name . '</Subject>' . "\n";
        echo '<Created>' . date('Y-m-dT H:i:sZ') . '</Created>' . "\n";
        echo '</DocumentProperties>' . "\n";
        
        echo '<Styles>' . "\n";
        echo '<Style ss:ID="Header" ss:Name="Header">' . "\n";
        echo '<Alignment ss:Horizontal="Center" ss:Vertical="Center"/>' . "\n";
        echo '<Interior ss:Color="#005BAA" ss:Pattern="Solid"/>' . "\n";
        echo '<Font ss:Bold="1" ss:Color="FFFFFF"/>' . "\n";
        echo '</Style>' . "\n";
        echo '</Styles>' . "\n";
        
        echo '<Worksheet ss:Name="Recipients">' . "\n";
        echo '<Table>' . "\n";
        
        // Title row
        echo '<Row>' . "\n";
        echo '<Cell ss:StyleID="Header" ss:MergedAcross="6"><Data ss:Type="String">LifeConnect Sri Lanka - Recipient Patient Report</Data></Cell>' . "\n";
        echo '</Row>' . "\n";
        
        // Empty row
        echo '<Row></Row>' . "\n";
        
        // Hospital info row
        echo '<Row>' . "\n";
        echo '<Cell><Data ss:Type="String">Hospital:</Data></Cell>' . "\n";
        echo '<Cell ss:MergedAcross="5"><Data ss:Type="String">' . $hospital_name . '</Data></Cell>' . "\n";
        echo '</Row>' . "\n";
        
        // Generated date row
        echo '<Row>' . "\n";
        echo '<Cell><Data ss:Type="String">Generated:</Data></Cell>' . "\n";
        echo '<Cell ss:MergedAcross="5"><Data ss:Type="String">' . date('Y-m-d H:i:s') . '</Data></Cell>' . "\n";
        echo '</Row>' . "\n";
        
        // Empty row
        echo '<Row></Row>' . "\n";
        
        // Header row
        echo '<Row ss:StyleID="Header">' . "\n";
        echo '<Cell><Data ss:Type="String">Patient NIC</Data></Cell>' . "\n";
        echo '<Cell><Data ss:Type="String">Patient Name</Data></Cell>' . "\n";
        echo '<Cell><Data ss:Type="String">Organ Received</Data></Cell>' . "\n";
        echo '<Cell><Data ss:Type="String">Surgery Date</Data></Cell>' . "\n";
        echo '<Cell><Data ss:Type="String">Status</Data></Cell>' . "\n";
        echo '<Cell><Data ss:Type="String">Treatment Notes</Data></Cell>' . "\n";
        echo '<Cell><Data ss:Type="String">Date Added</Data></Cell>' . "\n";
        echo '</Row>' . "\n";
        
        // Data rows
        foreach($recipients as $recipient) {
            echo '<Row>' . "\n";
            echo '<Cell><Data ss:Type="String">' . htmlspecialchars($recipient->nic) . '</Data></Cell>' . "\n";
            echo '<Cell><Data ss:Type="String">' . htmlspecialchars($recipient->name) . '</Data></Cell>' . "\n";
            echo '<Cell><Data ss:Type="String">' . htmlspecialchars($recipient->organ_received) . '</Data></Cell>' . "\n";
            echo '<Cell><Data ss:Type="Date">' . date('Y-m-d', strtotime($recipient->surgery_date)) . '</Data></Cell>' . "\n";
            echo '<Cell><Data ss:Type="String">' . htmlspecialchars($recipient->status) . '</Data></Cell>' . "\n";
            echo '<Cell><Data ss:Type="String">' . htmlspecialchars($recipient->treatment_notes) . '</Data></Cell>' . "\n";
            echo '<Cell><Data ss:Type="Date">' . date('Y-m-d', strtotime($recipient->created_at)) . '</Data></Cell>' . "\n";
            echo '</Row>' . "\n";
        }
        
        echo '</Table>' . "\n";
        echo '</Worksheet>' . "\n";
        echo '</Workbook>';
        exit;
    }

    /**
     * Lab Reports page
     */
    public function labReports() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
            redirect('login');
        }

        $hospitalModel = new HospitalModel();
        $userId = $_SESSION['user_id'];
        $hospital = $hospitalModel->getHospitalByUserId($userId);
        $hospital_registration = $hospital->registration_number ?? $_SESSION['hospital_registration'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->handlePost($hospital_registration);
        }

        // Get lab reports for this hospital
        $lab_reports = $hospitalModel->getLabReports($hospital_registration) ?: [];
        
        $hospital_details = [
            'name' => $hospital->name,
            'registration' => $hospital->registration_number,
            'registration_number' => $hospital->registration_number,
            'role' => 'HOSPITAL',
            'email' => $_SESSION['email'] ?? 'admin@lifeconnect.lk',
            'address' => $hospital->address ?? 'Not specified',
            'status' => $hospital->verification_status ?? 'Active',
            'last_login' => date('Y-m-d H:i:s')
        ];

        $data = [
            'hospital_name' => $hospital->name,
            'hospital_registration' => $hospital_registration,
            'hospital_details' => $hospital_details,
            'scheduled_appointments' => $lab_reports
        ];

        $this->view('hospital/upcoming_appointments', $data);
    }

    /**
     * Search donors API endpoint
     */
    public function searchDonors() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        header('Content-Type: application/json');

        try {
            // Get search query
            $searchQuery = isset($_GET['q']) ? trim($_GET['q']) : '';

            $hospitalModel = new HospitalModel();
            $hospital = $hospitalModel->getHospitalByUserId($_SESSION['user_id']);
            $hospitalId = $hospital ? (int)$hospital->id : 0;
            
            $results = $hospitalModel->searchDonorsForHospital($hospitalId, $searchQuery);
            echo json_encode($results ?: []);
        } catch(\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Generate SVG export
     */
    private function generateSVG($recipients, $hospital_name) {
        header('Content-Type: image/svg+xml; charset=utf-8');
        header('Content-Disposition: attachment; filename="Recipient_Report_' . date('Y-m-d_H-i-s') . '.svg"');
        
        $width = 1200;
        $height = 800;
        $rowHeight = 30;
        $yPos = 100;

        $logoPath = __DIR__ . '/../../public/assets/images/logo.png';
        $logoDataUri = '';
        if (file_exists($logoPath)) {
            $logoDataUri = 'data:image/png;base64,' . base64_encode(file_get_contents($logoPath));
        }
        
        $svg = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $svgHeight = $height + count($recipients) * $rowHeight;
        $svg .= '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="' . $width . '" height="' . $svgHeight . '">' . "\n";
        
        // Add watermark background
        $svg .= '<defs>' . "\n";
        $svg .= '<pattern id="watermark" x="0" y="0" width="420" height="240" patternUnits="userSpaceOnUse">' . "\n";
        if (!empty($logoDataUri)) {
            $svg .= '<image x="20" y="20" width="90" height="90" opacity="0.06" xlink:href="' . $logoDataUri . '" />' . "\n";
        }
        $svg .= '<text x="130" y="85" font-size="44" fill="#bdbdbd" opacity="0.12" font-family="Arial" font-weight="bold">LifeConnect Sri Lanka</text>' . "\n";
        $svg .= '</pattern>' . "\n";
        $svg .= '</defs>' . "\n";
        
        // Add watermark rectangle
        $svg .= '<rect width="' . $width . '" height="' . $svgHeight . '" fill="url(#watermark)"/>' . "\n";

        // Add logo at top
        if (!empty($logoDataUri)) {
            $svg .= '<image x="20" y="18" width="55" height="55" xlink:href="' . $logoDataUri . '" />' . "\n";
        }
        $svg .= '<text x="90" y="50" font-size="24" font-weight="bold" fill="#005BAA">LifeConnect</text>' . "\n";
        $svg .= '<text x="90" y="68" font-size="12" fill="#6b7280">Sri Lanka</text>' . "\n";
        
        // Add title
        $svg .= '<text x="600" y="50" font-size="20" font-weight="bold" fill="#333" text-anchor="middle">Recipient Patient Report</text>' . "\n";
        $svg .= '<text x="600" y="75" font-size="12" fill="#666" text-anchor="middle">' . htmlspecialchars($hospital_name) . '</text>' . "\n";
        $svg .= '<text x="600" y="90" font-size="10" fill="#999" text-anchor="middle">Generated: ' . date('Y-m-d H:i:s') . '</text>' . "\n";
        
        // Add table header
        $headerY = 120;
        $svg .= '<rect x="20" y="' . $headerY . '" width="' . ($width - 40) . '" height="25" fill="#005BAA" rx="3"/>' . "\n";
        
        $columns = ['NIC', 'Name', 'Organ', 'Surgery Date', 'Status'];
        $colWidths = [100, 250, 150, 200, 150];
        $xPos = 30;
        
        foreach ($columns as $i => $col) {
            $svg .= '<text x="' . $xPos . '" y="' . ($headerY + 17) . '" font-size="12" font-weight="bold" fill="white">' . $col . '</text>' . "\n";
            $xPos += $colWidths[$i];
        }
        
        // Add table data rows
        $yPos = $headerY + 35;
        $rowNum = 0;
        foreach ($recipients as $recipient) {
            $bgColor = ($rowNum % 2 == 0) ? '#f9f9f9' : '#ffffff';
            $svg .= '<rect x="20" y="' . $yPos . '" width="' . ($width - 40) . '" height="' . $rowHeight . '" fill="' . $bgColor . '" stroke="#ddd" stroke-width="1"/>' . "\n";
            
            $xPos = 30;
            $data = [
                substr($recipient->nic, -6),
                substr($recipient->name, 0, 20),
                substr($recipient->organ_received, 0, 10),
                date('m/d/Y', strtotime($recipient->surgery_date)),
                $recipient->status
            ];
            
            foreach ($data as $i => $cellData) {
                $svg .= '<text x="' . $xPos . '" y="' . ($yPos + 20) . '" font-size="11" fill="#333">' . htmlspecialchars($cellData) . '</text>' . "\n";
                $xPos += $colWidths[$i];
            }
            
            $yPos += $rowHeight;
            $rowNum++;
        }
        
        // Add footer
        $svg .= '<text x="30" y="' . ($yPos + 20) . '" font-size="11" font-weight="bold" fill="#666">Total Recipients: ' . count($recipients) . '</text>' . "\n";
        
        $svg .= '</svg>';
        
        echo $svg;
        exit;
    }

    /**
     * API endpoint to get donor details directly for the Add Patient form
     */
    public function fetchDonorDetails() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        header('Content-Type: application/json');

        try {
            $nic = isset($_GET['nic']) ? trim($_GET['nic']) : '';

            if (empty($nic)) {
                echo json_encode(['success' => false, 'message' => 'NIC is required']);
                exit;
            }

            $hospitalModel = new HospitalModel();
            // Fetch donor using query method
            $donor = $hospitalModel->query("SELECT first_name, last_name, gender, blood_group FROM donors WHERE nic_number = :nic LIMIT 1", [':nic' => $nic]);

            if (!empty($donor)) {
                echo json_encode([
                    'success' => true, 
                    'donor' => $donor[0]
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Donor not found']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error']);
        }
        exit;
    }
}
