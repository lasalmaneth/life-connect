<?php 

namespace App\Controllers;

use App\Core\Controller;
use App\Models\AftercarePatientModel;
use App\Models\HospitalModel;

class Hospital {
    use Controller;

    public function organRequestsLegacy() {
        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'HOSPITAL') {
            redirect('login');
        }

        // Redirect legacy /hospital/organ_request.view.php to clean URL
        redirect('hospital/organ-requests');
    }

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
                'email' => $_SESSION['email'] ?? 'Not specified',
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
                'email' => $hospital->user_email ?? ($_SESSION['email'] ?? 'Not specified'),
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
        $success_stories = $hospitalModel->getSuccessStories($hospital_registration) ?: [];
        $aftercare_appointments = $hospitalModel->getAftercareAppointments($hospital_registration) ?: [];
        $aftercare_support_requests = $hospitalModel->getAftercareSupportRequests($hospital_registration) ?: [];
        $lab_reports = $hospitalModel->getLabReports($hospital_registration) ?: [];
        $eligibility_pledges = $hospitalModel->getApprovedPledgesForEligibility($hospitalId) ?: [];
        $test_results = $hospitalModel->getTestResultsByHospitalId($hospitalId) ?: [];

        $aftercarePatientModel = new AftercarePatientModel();
        $aftercare_recipients = $aftercarePatientModel->getRecipientsByHospital($hospital_registration) ?: [];

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
            'total_aftercare_recipients' => count($aftercare_recipients),
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
            'success_stories' => $success_stories,
            'aftercare_recipients' => $aftercare_recipients,
            'aftercare_appointments' => $aftercare_appointments,
            'aftercare_support_requests' => $aftercare_support_requests,
            'lab_reports' => $lab_reports,
            'test_results' => $test_results,
            'eligibility_pledges' => $eligibility_pledges,
            'stats' => $stats
        ];

        $this->view('hospital/index', $data);
    }

    public function aftercareRecipients()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
            redirect('login');
        }

        $hospitalModel = new HospitalModel();
        $userId = $_SESSION['user_id'];
        $hospital = $hospitalModel->getHospitalByUserId($userId);
        $hospital_registration = $hospital->registration_number ?? $_SESSION['hospital_registration'];

        $aftercarePatientModel = new AftercarePatientModel();
        $aftercare_recipients = $aftercarePatientModel->getRecipientsByHospital($hospital_registration) ?: [];

        $data = [
            'hospital_name' => $hospital->name ?? ($_SESSION['hospital_name'] ?? 'Hospital'),
            'hospital_registration' => $hospital_registration,
            'hospital_details' => [
                'name' => $hospital->name ?? 'Hospital',
                'registration' => $hospital_registration,
                'role' => 'HOSPITAL',
                'email' => $hospital->user_email ?? ($_SESSION['email'] ?? 'Not specified'),
            ],
            'aftercare_recipients' => $aftercare_recipients,
            'current_page' => 'aftercare-recipients'
        ];

        $this->view('hospital/aftercare-recipients', $data);
    }

    public function notifications()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'HOSPITAL') {
            redirect('login');
        }

        $hospitalModel = new HospitalModel();
        $userId = (int)$_SESSION['user_id'];
        $hospital = $hospitalModel->getHospitalByUserId($userId);

        $hospital_registration = $hospital->registration_number ?? ($_SESSION['hospital_registration'] ?? 'HOSP001');
        $hospital_name = $hospital->name ?? ($_SESSION['hospital_name'] ?? 'Hospital');
        $hospital_details = [
            'name' => $hospital_name,
            'registration' => $hospital_registration,
            'registration_number' => $hospital_registration,
            'role' => $_SESSION['role'] ?? 'HOSPITAL',
            'email' => $hospital->user_email ?? ($_SESSION['email'] ?? 'Not specified'),
            'address' => $hospital->address ?? 'Not specified',
            'phone' => $hospital->contact_number ?? 'Not specified',
            'status' => $hospital->verification_status ?? 'Active',
            'last_login' => date('Y-m-d H:i:s')
        ];

        $notificationModel = new \App\Models\NotificationModel();

        if (isset($_GET['mark_all_read'])) {
            $notificationModel->markAllAsRead($userId);
            redirect('hospital/notifications');
        }

        $notifications = $notificationModel->getNotificationsForUser($userId);
        $notifications = json_decode(json_encode($notifications), true) ?: [];
        $unread_count = (int)$notificationModel->getUnreadCount($userId);

        $data = [
            'hospital_name' => $hospital_name,
            'hospital_registration' => $hospital_registration,
            'hospital_details' => $hospital_details,
            'notifications' => $notifications,
            'unread_count' => $unread_count,
            'active_page' => 'notifications'
        ];

        $this->view('hospital/notifications', $data);
    }

    public function markAllNotificationsRead()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'HOSPITAL') {
            redirect('login');
        }

        $userId = (int)$_SESSION['user_id'];
        $notificationModel = new \App\Models\NotificationModel();
        $notificationModel->markAllAsRead($userId);

        $back = $_SERVER['HTTP_REFERER'] ?? (ROOT . '/hospital/notifications');
        header('Location: ' . $back);
        exit;
    }

    public function markNotificationRead()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'HOSPITAL') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $userId = (int)$_SESSION['user_id'];
        $id = (int)($_POST['id'] ?? 0);

        header('Content-Type: application/json');
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid id']);
            exit;
        }

        $notificationModel = new \App\Models\NotificationModel();
        $row = $notificationModel->query("SELECT user_id FROM notifications WHERE id = :id LIMIT 1", [':id' => $id]);
        if (!$row || (int)($row[0]->user_id ?? 0) !== $userId) {
            echo json_encode(['success' => false, 'message' => 'Not found']);
            exit;
        }

        $ok = (bool)$notificationModel->markAsRead($id);
        echo json_encode(['success' => $ok]);
        exit;
    }

    public function deleteNotification()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'HOSPITAL') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $userId = (int)$_SESSION['user_id'];
        $id = (int)($_POST['id'] ?? 0);

        header('Content-Type: application/json');
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid id']);
            exit;
        }

        $notificationModel = new \App\Models\NotificationModel();
        $row = $notificationModel->query("SELECT user_id FROM notifications WHERE id = :id LIMIT 1", [':id' => $id]);
        if (!$row || (int)($row[0]->user_id ?? 0) !== $userId) {
            echo json_encode(['success' => false, 'message' => 'Not found']);
            exit;
        }

        $ok = (bool)$notificationModel->deleteNotification($id);
        echo json_encode(['success' => $ok]);
        exit;
    }

    private function handlePost($regNo) {
        $hospitalModel = new HospitalModel();
        $action = $_POST['action'] ?? '';

        $allowedBloodGroups = ['A+','A-','B+','B-','AB+','AB-','O+','O-'];
        $allowedGenders = ['Male', 'Female', 'Other'];

        switch ($action) {
            case 'accept_aftercare_appointment':
                $appointmentId = (int)($_POST['appointment_id'] ?? 0);
                if ($appointmentId <= 0) {
                    $_SESSION['flash_error'] = 'Invalid appointment.';
                    break;
                }

                if ($hospitalModel->acceptAftercareAppointment($appointmentId, $regNo)) {
                    $apt = $hospitalModel->query(
                        "SELECT patient_id, appointment_date FROM aftercare_appointments WHERE appointment_id = :id AND hospital_registration_no = :reg_no LIMIT 1",
                        [':id' => $appointmentId, ':reg_no' => $regNo]
                    );
                    $patientNic = $apt ? (string)($apt[0]->patient_id ?? '') : '';
                    $when = $apt ? (string)($apt[0]->appointment_date ?? '') : '';
                    if ($patientNic !== '') {
                        $hospital = $hospitalModel->getHospitalByUserId($_SESSION['user_id']);
                        $hName = $hospital->name ?? 'Hospital';
                        $msg = 'Your aftercare appointment request was accepted by ' . $hName . '.';
                        if ($when) $msg .= ' Scheduled for: ' . date('Y-m-d h:i A', strtotime($when)) . '.';
                        $hospitalModel->notifyDonorByNic($patientNic, 'Aftercare appointment accepted', $msg, 'INFO');
                    }
                    $_SESSION['flash_success'] = 'Aftercare appointment accepted.';
                } else {
                    $_SESSION['flash_error'] = 'Failed to accept aftercare appointment.';
                }
                break;

            case 'reject_aftercare_appointment':
                $appointmentId = (int)($_POST['appointment_id'] ?? 0);
                $reason = trim((string)($_POST['reason'] ?? ''));
                if ($appointmentId <= 0) {
                    $_SESSION['flash_error'] = 'Invalid appointment.';
                    break;
                }
                if ($reason === '') {
                    $_SESSION['flash_error'] = 'Please provide a rejection reason.';
                    break;
                }

                if ($hospitalModel->rejectAftercareAppointment($appointmentId, $regNo, $reason)) {
                    $apt = $hospitalModel->query(
                        "SELECT patient_id, appointment_date FROM aftercare_appointments WHERE appointment_id = :id AND hospital_registration_no = :reg_no LIMIT 1",
                        [':id' => $appointmentId, ':reg_no' => $regNo]
                    );
                    $patientNic = $apt ? (string)($apt[0]->patient_id ?? '') : '';
                    $when = $apt ? (string)($apt[0]->appointment_date ?? '') : '';
                    if ($patientNic !== '') {
                        $hospital = $hospitalModel->getHospitalByUserId($_SESSION['user_id']);
                        $hName = $hospital->name ?? 'Hospital';
                        $phone = $hospital->contact_number ?? '';
                        $msg = 'Your aftercare appointment request was rejected by ' . $hName . '.';
                        if ($when) $msg .= ' Requested time: ' . date('Y-m-d h:i A', strtotime($when)) . '.';
                        $msg .= ' Reason: ' . $reason . '.';
                        if ($phone) $msg .= ' Contact: ' . $phone . '.';
                        $hospitalModel->notifyDonorByNic($patientNic, 'Aftercare appointment rejected', $msg, 'WARNING');
                    }
                    $_SESSION['flash_success'] = 'Aftercare appointment rejected.';
                } else {
                    $_SESSION['flash_error'] = 'Failed to reject aftercare appointment.';
                }
                break;
            case 'approve_eligibility':
                $pledgeId = (int)($_POST['pledge_id'] ?? 0);
                if ($pledgeId <= 0) {
                    $_SESSION['flash_error'] = 'Invalid pledge.';
                    break;
                }

                $hospitalId = (int)$hospitalModel->getHospitalIdByRegistrationNo($regNo);
                if ($hospitalId <= 0) {
                    $_SESSION['flash_error'] = 'Hospital account not found.';
                    break;
                }

                if ($hospitalModel->approveEligibilityPledge($pledgeId, $hospitalId)) {
                    $_SESSION['flash_success'] = 'Donor eligibility approved.';
                } else {
                    $_SESSION['flash_error'] = 'Unable to approve eligibility (already processed or not assigned to this hospital).';
                }
                break;

            case 'reject_eligibility':
                $pledgeId = (int)($_POST['pledge_id'] ?? 0);
                if ($pledgeId <= 0) {
                    $_SESSION['flash_error'] = 'Invalid pledge.';
                    break;
                }

                $hospitalId = (int)$hospitalModel->getHospitalIdByRegistrationNo($regNo);
                if ($hospitalId <= 0) {
                    $_SESSION['flash_error'] = 'Hospital account not found.';
                    break;
                }

                if ($hospitalModel->rejectEligibilityPledge($pledgeId, $hospitalId)) {
                    $_SESSION['flash_success'] = 'Donor eligibility rejected.';
                } else {
                    $_SESSION['flash_error'] = 'Unable to reject eligibility (already processed or not assigned to this hospital).';
                }
                break;

            case 'approve_support_request':
                $requestId = (int)($_POST['support_request_id'] ?? 0);
                if ($requestId <= 0) {
                    $_SESSION['flash_error'] = 'Invalid support request.';
                    break;
                }

                if ($hospitalModel->approveSupportRequest($requestId, $regNo, 'Hospital ' . (string)$regNo)) {
                    $_SESSION['flash_success'] = 'Support request approved.';
                } else {
                    $_SESSION['flash_error'] = 'Failed to approve support request.';
                }
                break;

            case 'reject_support_request':
                $requestId = (int)($_POST['support_request_id'] ?? 0);
                $reason = trim((string)($_POST['reject_reason'] ?? ''));
                if ($requestId <= 0) {
                    $_SESSION['flash_error'] = 'Invalid support request.';
                    break;
                }
                if ($reason === '') {
                    $_SESSION['flash_error'] = 'Please provide a rejection reason.';
                    break;
                }

                if ($hospitalModel->rejectSupportRequest($requestId, $regNo, $reason, 'Hospital ' . (string)$regNo)) {
                    $_SESSION['flash_success'] = 'Support request rejected.';
                } else {
                    $_SESSION['flash_error'] = 'Failed to reject support request.';
                }
                break;

            case 'add_organ_request':
                $recipientAge = $_POST['recipient_age'] ?? null;
                $bloodGroup = trim((string)($_POST['blood_group'] ?? ''));
                $gender = trim((string)($_POST['gender'] ?? ''));

                // Prefer split HLA fields (new schema), but keep legacy packed field as a fallback.
                $hlaA1 = trim((string)($_POST['hla_a1'] ?? ''));
                $hlaA2 = trim((string)($_POST['hla_a2'] ?? ''));
                $hlaB1 = trim((string)($_POST['hla_b1'] ?? ''));
                $hlaB2 = trim((string)($_POST['hla_b2'] ?? ''));
                $hlaDr1 = trim((string)($_POST['hla_dr1'] ?? ''));
                $hlaDr2 = trim((string)($_POST['hla_dr2'] ?? ''));

                $hlaTypingFromParts = '';
                if ($hlaA1 !== '' || $hlaA2 !== '' || $hlaB1 !== '' || $hlaB2 !== '' || $hlaDr1 !== '' || $hlaDr2 !== '') {
                    $hlaTypingFromParts = "A1={$hlaA1}; A2={$hlaA2}; B1={$hlaB1}; B2={$hlaB2}; DR1={$hlaDr1}; DR2={$hlaDr2}";
                }
                $hlaTyping = $hlaTypingFromParts !== ''
                    ? $hlaTypingFromParts
                    : trim((string)($_POST['hla_typing'] ?? ''));

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
                    'hla_a1' => $hlaA1 !== '' ? $hlaA1 : null,
                    'hla_a2' => $hlaA2 !== '' ? $hlaA2 : null,
                    'hla_b1' => $hlaB1 !== '' ? $hlaB1 : null,
                    'hla_b2' => $hlaB2 !== '' ? $hlaB2 : null,
                    'hla_dr1' => $hlaDr1 !== '' ? $hlaDr1 : null,
                    'hla_dr2' => $hlaDr2 !== '' ? $hlaDr2 : null,
                    // Legacy packed field (used only if the DB schema still has single-column HLA)
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

                // Prefer split HLA fields (new schema), but keep legacy packed field as a fallback.
                $hlaA1 = trim((string)($_POST['hla_a1'] ?? ''));
                $hlaA2 = trim((string)($_POST['hla_a2'] ?? ''));
                $hlaB1 = trim((string)($_POST['hla_b1'] ?? ''));
                $hlaB2 = trim((string)($_POST['hla_b2'] ?? ''));
                $hlaDr1 = trim((string)($_POST['hla_dr1'] ?? ''));
                $hlaDr2 = trim((string)($_POST['hla_dr2'] ?? ''));

                $hlaTypingFromParts = '';
                if ($hlaA1 !== '' || $hlaA2 !== '' || $hlaB1 !== '' || $hlaB2 !== '' || $hlaDr1 !== '' || $hlaDr2 !== '') {
                    $hlaTypingFromParts = "A1={$hlaA1}; A2={$hlaA2}; B1={$hlaB1}; B2={$hlaB2}; DR1={$hlaDr1}; DR2={$hlaDr2}";
                }
                $hlaTyping = $hlaTypingFromParts !== ''
                    ? $hlaTypingFromParts
                    : trim((string)($_POST['hla_typing'] ?? ''));

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
                    'hla_a1' => $hlaA1 !== '' ? $hlaA1 : null,
                    'hla_a2' => $hlaA2 !== '' ? $hlaA2 : null,
                    'hla_b1' => $hlaB1 !== '' ? $hlaB1 : null,
                    'hla_b2' => $hlaB2 !== '' ? $hlaB2 : null,
                    'hla_dr1' => $hlaDr1 !== '' ? $hlaDr1 : null,
                    'hla_dr2' => $hlaDr2 !== '' ? $hlaDr2 : null,
                    // Legacy packed field (used only if the DB schema still has single-column HLA)
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
                    'blood_type' => $_POST['blood_type'] ?? ''
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
                    'blood_type' => $_POST['blood_type'] ?? ''
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
                $patientType = strtoupper(trim((string)($_POST['patient_type'] ?? 'DONOR')));
                $donorId = (int)($_POST['donor_id'] ?? 0);
                $recipientId = (int)($_POST['recipient_id'] ?? 0);
                $testName = trim((string)($_POST['test_name'] ?? ''));
                $testDate = trim((string)($_POST['test_date'] ?? ''));
                $resultValue = trim((string)($_POST['result_value'] ?? ''));

                if (!in_array($patientType, ['DONOR', 'RECIPIENT'], true)) {
                    $_SESSION['flash_error'] = 'Invalid patient type.';
                    break;
                }

                if ($testName === '' || $testDate === '') {
                    $_SESSION['flash_error'] = 'Please select patient, test name, and test date.';
                    break;
                }

                if ($patientType === 'DONOR' && $donorId <= 0) {
                    $_SESSION['flash_error'] = 'Please select a donor patient.';
                    break;
                }

                if ($patientType === 'RECIPIENT' && $recipientId <= 0) {
                    $_SESSION['flash_error'] = 'Please select a recipient patient.';
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

                $payload = [
                    'patient_type' => $patientType,
                    'donor_id' => $patientType === 'DONOR' ? $donorId : null,
                    'recipient_id' => $patientType === 'RECIPIENT' ? $recipientId : null,
                    'test_name' => $testName,
                    'result_value' => $resultValue,
                    'document_path' => $documentPath,
                    'test_date' => $testDate,
                    'verified_by_hospital_id' => $hospitalIdForVerification,
                ];

                if ($hospitalModel->addTestResult($payload)) {
                    if ($patientType === 'DONOR') {
                        $hospitalModel->notifyDonor(
                            $donorId,
                            'New test result available',
                            'A new test result (' . $testName . ') was uploaded to your profile. You can view it under Test Results.',
                            'INFO'
                        );
                    }
                    $_SESSION['flash_success'] = 'Test result uploaded successfully.';
                } else {
                    if ($patientType === 'RECIPIENT') {
                        $_SESSION['flash_error'] = 'Failed to upload recipient test result. Your database may need an update to support recipient test results.';
                    } else {
                        $_SESSION['flash_error'] = 'Failed to upload test result.';
                    }
                }
                break;

            case 'delete_scheduled_appointment':
                if ($hospitalModel->deleteLabReport($_POST['appointment_id'])) {
                    $_SESSION['flash_success'] = "Appointment deleted successfully.";
                }
                break;

            case 'apply_reschedule_request':
                $rid = (int)($_POST['report_id'] ?? 0);
                if ($rid <= 0) {
                    $_SESSION['flash_error'] = 'Invalid appointment.';
                    break;
                }

                $apt = $hospitalModel->getLabReportById($rid);
                if (!$apt || (string)($apt->hospital_registration_no ?? '') !== (string)$regNo) {
                    $_SESSION['flash_error'] = 'Appointment not found.';
                    break;
                }

                $notesText = (string)($apt->notes ?? '');
                $proposed = null;
                if (preg_match_all('/Proposed date:\s*([0-9]{4}-[0-9]{2}-[0-9]{2})/i', $notesText, $m) && !empty($m[1])) {
                    $proposed = end($m[1]) ?: null;
                }

                if (!$proposed) {
                    $_SESSION['flash_error'] = 'No reschedule request found for this appointment.';
                    break;
                }

                $stamp = date('Y-m-d H:i');
                $respLine = "[Hospital Response] Reschedule approved. New date: {$proposed} | Approved at: {$stamp}";
                $newNotes = $notesText ? ($notesText . "\n" . $respLine) : $respLine;

                $data = [
                    'donor_id' => $apt->donor_id ?? null,
                    'hospital_registration_no' => $regNo,
                    'test_type' => $apt->test_type ?? '',
                    'test_date' => $proposed,
                    'result_status' => $apt->status ?? 'Pending',
                    'result_notes' => $newNotes,
                ];

                if ($hospitalModel->updateLabReport($rid, $data)) {
                    $hospitalModel->notifyDonor(
                        (int)($apt->donor_id ?? 0),
                        'Reschedule approved',
                        'Your reschedule request was approved. New appointment date: ' . $proposed . '.',
                        'INFO'
                    );
                    $_SESSION['flash_success'] = 'Reschedule applied and donor notified.';
                } else {
                    $_SESSION['flash_error'] = 'Failed to apply reschedule.';
                }
                break;

            case 'decline_reschedule_request':
                $rid = (int)($_POST['report_id'] ?? 0);
                $reason = trim((string)($_POST['reason'] ?? ''));
                if ($rid <= 0 || $reason === '') {
                    $_SESSION['flash_error'] = 'Please provide a reason to decline.';
                    break;
                }

                $apt = $hospitalModel->getLabReportById($rid);
                if (!$apt || (string)($apt->hospital_registration_no ?? '') !== (string)$regNo) {
                    $_SESSION['flash_error'] = 'Appointment not found.';
                    break;
                }

                // Fetch hospital contact number for donor instructions
                $contact = '';
                $h = null;
                if (!empty($_SESSION['user_id'])) {
                    $h = $hospitalModel->getHospitalByUserId((int)$_SESSION['user_id']);
                    $contact = trim((string)($h->contact_number ?? ''));
                }
                $hospitalName = $h ? (string)($h->name ?? 'Hospital') : 'Hospital';

                $stamp = date('Y-m-d H:i');
                $respBlock = "[Hospital Response] Unable to reschedule. Reason: {$reason} | Responded at: {$stamp}";
                if ($contact !== '') {
                    $respBlock .= " | Please contact {$hospitalName} ({$regNo}) at {$contact}.";
                } else {
                    $respBlock .= " | Please contact {$hospitalName} ({$regNo}).";
                }

                $notesText = (string)($apt->notes ?? '');
                $newNotes = $notesText ? ($notesText . "\n" . $respBlock) : $respBlock;

                $data = [
                    'donor_id' => $apt->donor_id ?? null,
                    'hospital_registration_no' => $regNo,
                    'test_type' => $apt->test_type ?? '',
                    'test_date' => $apt->test_date ?? null,
                    'result_status' => $apt->status ?? 'Pending',
                    'result_notes' => $newNotes,
                ];

                if ($hospitalModel->updateLabReport($rid, $data)) {
                    $msg = "Your reschedule request could not be approved.\nReason: {$reason}";
                    if ($contact !== '') {
                        $msg .= "\nPlease contact {$hospitalName} ({$regNo}) at {$contact}.";
                    } else {
                        $msg .= "\nPlease contact {$hospitalName} ({$regNo}).";
                    }
                    $hospitalModel->notifyDonor(
                        (int)($apt->donor_id ?? 0),
                        'Reschedule declined',
                        $msg,
                        'WARNING'
                    );
                    $_SESSION['flash_success'] = 'Decline sent to donor.';
                } else {
                    $_SESSION['flash_error'] = 'Failed to send decline.';
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

        // Prefer returning to the page that submitted the form (donor-style routing).
        $back = $_SERVER['HTTP_REFERER'] ?? '';
        if (is_string($back) && $back !== '') {
            header('Location: ' . $back);
            exit;
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
            'email' => $hospital->user_email ?? ($_SESSION['email'] ?? 'Not specified'),
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

        // Single canonical Organ Requests view (also used by the alias route)
        $this->view('hospital/organ_request', $data);
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

            $action = $_POST['action'] ?? '';
            if (in_array($action, ['approve_eligibility', 'reject_eligibility'], true)) {
                redirect('hospital/eligibility');
            }
        }

        $hospital_details = [
            'name' => $hospital->name,
            'registration' => $hospital->registration_number,
            'registration_number' => $hospital->registration_number,
            'role' => 'HOSPITAL',
            'email' => $hospital->user_email ?? ($_SESSION['email'] ?? 'Not specified'),
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
            'email' => $hospital->user_email ?? ($_SESSION['email'] ?? 'Not specified'),
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
            'email' => $hospital->user_email ?? ($_SESSION['email'] ?? 'Not specified'),
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
                    'surgery_type' => !empty($_POST['surgery_type']) ? trim((string)$_POST['surgery_type']) : null,
                    'surgery_date' => !empty($_POST['surgery_date']) ? trim((string)$_POST['surgery_date']) : null,
                ]);

                $_SESSION['flash_success'] = 'Recipient aftercare account created successfully.';
                $_SESSION['generated_aftercare_credentials'] = [
                    'registration_number' => $registrationNumber,
                    'password' => $nic,
                ];
            } catch (\Throwable $e) {
                $msg = (string)($e->getMessage() ?? '');
                if (stripos($msg, 'aftercare_patients') !== false && (stripos($msg, 'doesn\'t exist') !== false || stripos($msg, 'Base table or view not found') !== false)) {
                    $_SESSION['flash_error'] = 'Aftercare tables not found. Please import/apply the latest database schema (main.sql) and try again.';
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
            'email' => $hospital->user_email ?? ($_SESSION['email'] ?? 'Not specified'),
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
                try {
                    // Ensure aftercare_access column exists
                    $hasCol = $hospitalModel->query("SHOW COLUMNS FROM users LIKE 'aftercare_access'");
                    if (empty($hasCol)) {
                        // Column doesn't exist, create it
                        $con = $hospitalModel->connect();
                        $con->exec("ALTER TABLE users ADD COLUMN aftercare_access TINYINT DEFAULT 0");
                    }
                    
                    // Now update the aftercare_access flag
                    $hospitalModel->query(
                        "UPDATE users SET aftercare_access = 1 WHERE id = :uid",
                        [':uid' => $uid]
                    );
                } catch (\Throwable $e) {
                    // Log error but continue
                }

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
                        ], $uid);
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
            'email' => $hospital->user_email ?? ($_SESSION['email'] ?? 'Not specified'),
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


    // PDF export removed (no external libraries allowed)


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

        // Aftercare appointments (donor-initiated requests + accepted/rejected history)
        $aftercare_appointments = $hospitalModel->getAftercareAppointments($hospital_registration) ?: [];
        
        $hospital_details = [
            'name' => $hospital->name,
            'registration' => $hospital->registration_number,
            'registration_number' => $hospital->registration_number,
            'role' => 'HOSPITAL',
            'email' => $hospital->user_email ?? ($_SESSION['email'] ?? 'Not specified'),
            'address' => $hospital->address ?? 'Not specified',
            'status' => $hospital->verification_status ?? 'Active',
            'last_login' => date('Y-m-d H:i:s')
        ];

        $data = [
            'hospital_name' => $hospital->name,
            'hospital_registration' => $hospital_registration,
            'hospital_details' => $hospital_details,
            'scheduled_appointments' => $lab_reports,
            'aftercare_appointments' => $aftercare_appointments,
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

    /**
     * Search patient by NIC for recipient patient lookup
     * POST /hospital/searchPatientByNIC
     */
    public function searchPatientByNIC()
    {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        try {
            if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'HOSPITAL') {
                echo json_encode(['success' => false, 'message' => 'Unauthorized']);
                exit;
            }

            $data = json_decode(file_get_contents('php://input'), true);
            $nic = trim($data['nic'] ?? '');

            if (empty($nic)) {
                echo json_encode(['success' => false, 'message' => 'NIC is required']);
                exit;
            }

            $hospitalModel = new HospitalModel();
            
            // Search in donors table first
            $patient = $hospitalModel->query(
                "SELECT id, first_name, last_name, gender, blood_group, CONCAT(first_name, ' ', last_name) as name 
                 FROM donors WHERE nic_number = :nic LIMIT 1", 
                [':nic' => $nic]
            );

            if (!empty($patient)) {
                echo json_encode([
                    'success' => true, 
                    'patient' => (array)$patient[0]
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Patient not found with this NIC']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error searching patient: ' . $e->getMessage()]);
        }
        exit;
    }
}
