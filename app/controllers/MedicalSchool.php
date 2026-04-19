<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\MedicalSchoolModel;
use App\Models\SuccessStoryModel;

class MedicalSchool
{
    use Controller;

    private function checkAuth()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'MEDICAL_SCHOOL') {
            redirect('login');
            die();
        }

        $schoolModel = new MedicalSchoolModel();
        $school = $schoolModel->getSchoolByUserId($_SESSION['user_id']);
        if (!$school) {
            die('School not found');
        }
        return ['model' => $schoolModel, 'school' => $school];
    }

    /**
     * Shared authorization for document viewing (allows both School and Custodian)
     */
    private function checkDocumentAuth()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();
        
        $allowedRoles = ['MEDICAL_SCHOOL', 'CUSTODIAN'];
        if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], $allowedRoles)) {
            redirect('login');
            die();
        }

        $schoolModel = new MedicalSchoolModel();
        $school = $schoolModel->getSchoolByUserId($_SESSION['user_id']);
        return ['model' => $schoolModel, 'school' => $school];
    }

    public function dashboard()
    {
        $auth = $this->checkAuth();
        $stats = $auth['model']->getDashboardStats($auth['school']->id);
        $urgentAlerts = $auth['model']->getUrgentAlerts($auth['school']->id);
        
        $this->view('medical_schools/dashboard', [
            'school' => $auth['school'], 
            'stats' => $stats,
            'urgentAlerts' => $urgentAlerts
        ]);
    }

    // ── STAGE A: CONSENT REGISTRY ─────────────────
    public function consents()
    {
        $auth = $this->checkAuth();
        $status = $_GET['status'] ?? 'ALL';
        $donors = $auth['model']->getPreDeathConsents($auth['school']->id, $status);
        $this->view('medical_schools/consents', [
            'school' => $auth['school'], 
            'donors' => $donors,
            'active_status' => $status
        ]);
    }

    public function viewConsent()
    {
        $auth = $this->checkDocumentAuth();
        $id = $_GET['id'] ?? null;
        if (!$id)
            redirect('medical-school/consents');

        $schoolId = $auth['school']->id ?? null;
        $donor = $auth['model']->getDonorDetails($schoolId, $id, 'CONSENT');
        $custodians = $donor ? $auth['model']->getCustodiansForDonor($donor->id) : [];
        $this->view('medical_schools/drawers/consent', [
            'school' => $auth['school'] ?? null, 
            'donor' => $donor,
            'custodians' => $custodians
        ]);
    }

    public function flagConsent()
    {
        $auth = $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['donor_id'] ?? null;
            $reason = $_POST['flag_reason'] ?? '';
            if ($id && $reason) {
                $auth['model']->flagConsent($auth['school']->id, $id, $reason, $_SESSION['user_id']);
                $_SESSION['flash_success'] = "Record has been flagged.";
            } else {
                $_SESSION['flash_error'] = "Reason is required.";
            }
            redirect('medical-school/consents/view?id=' . $id);
            die();
        }
    }

    // ── STAGE B: WITHDRAWALS ─────────────────
    public function withdrawals()
    {
        $auth = $this->checkAuth();
        $donors = $auth['model']->getWithdrawnConsents($auth['school']->id);
        $this->view('medical_schools/withdrawals', ['school' => $auth['school'], 'donors' => $donors]);
    }

    public function viewWithdrawal()
    {
        $auth = $this->checkAuth();
        $id = $_GET['id'] ?? null;
        if (!$id)
            redirect('medical-school/withdrawals');

        $donor = $auth['model']->getDonorDetails($auth['school']->id, $id, 'WITHDRAWAL');
        $this->view('medical_schools/drawers/withdrawal', ['school' => $auth['school'], 'donor' => $donor]);
    }

    // ── STAGE C: BODY SUBMISSION REQUESTS ─────────────────
    public function submissionRequests()
    {
        $auth = $this->checkAuth();
        $filter = $_GET['status'] ?? 'PENDING';
        $requests = $auth['model']->getSubmissionRequests($auth['school']->id, $filter);
        $caseModel = new \App\Models\DonationCaseModel();
        
        foreach ($requests as &$req) {
            $activeCase = $caseModel->getCaseByDonor($req->donor_id);
            $deathDecl = $caseModel->getDeathDeclaration($req->donor_id);
            $req->clinical_deadline = $caseModel->getClinicalWindowStatus($activeCase, $deathDecl);
        }

        $stats = $auth['model']->getDashboardStats($auth['school']->id);
        
        $this->view('medical_schools/submission-requests', [
            'school' => $auth['school'], 
            'requests' => $requests, 
            'active_status' => $filter,
            'stats' => $stats
        ]);
    }

    public function viewSubmissionRequest()
    {
        $auth = $this->checkAuth();
        $id = $_GET['id'] ?? null;
        if (!$id)
            redirect('medical-school/submission-requests');

        $request = $auth['model']->getSubmissionRequestDetails($auth['school']->id, $id);
        $custodians = $request ? $auth['model']->getCustodiansForDonor($request->donor_id) : [];
        $this->view('medical_schools/drawers/submission-request', [
            'school' => $auth['school'], 
            'request' => $request,
            'custodians' => $custodians
        ]);
    }

    public function acceptSubmissionRequest()
    {
        $auth = $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['request_id'] ?? null;
            if ($id) {
                $requestData = $auth['model']->getSubmissionRequestDetails($auth['school']->id, $id);
                if ($requestData) {
                    $caseModel = new \App\Models\DonationCaseModel();
                    $activeCase = $caseModel->getCaseByDonor($requestData->donor_id);
                    $deathDecl = $caseModel->getDeathDeclaration($requestData->donor_id);
                    $window = $caseModel->getClinicalWindowStatus($activeCase, $deathDecl);

                    if ($window && $window['is_expired']) {
                        $_SESSION['flash_error'] = "The clinical window for this donation has expired. You can no longer accept this request.";
                        redirect('medical-school/submission-requests');
                        die();
                    }
                }
                $auth['model']->updateRequestStatus($auth['school']->id, $id, 'ACCEPTED', null, $_SESSION['user_id']);
                $_SESSION['flash_success'] = "Request accepted. Custodian can now submit the full bundle.";
            }
            redirect('medical-school/submission-requests');
            die();
        }
    }

    public function rejectSubmissionRequest()
    {
        $auth = $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['request_id'] ?? null;
            $reasonType = $_POST['reason_type'] ?? '';
            $reason = ($reasonType === 'Other') ? ($_POST['reason'] ?? '') : $reasonType;

            if ($id && $reason) {
                $auth['model']->updateRequestStatus($auth['school']->id, $id, 'REJECTED', $reason, $_SESSION['user_id']);
                $_SESSION['flash_success'] = "Request has been rejected and reason stored.";
            } else {
                $_SESSION['flash_error'] = "Reason is required to reject.";
            }
            redirect('medical-school/submission-requests');
            die();
        }
    }

    // ── STAGE D: CUSTODIAN DECLINE NOTICES ─────────────────
    public function custodianDeclines()
    {
        $auth = $this->checkAuth();
        $declines = $auth['model']->getCustodianDeclines($auth['school']->id);
        $this->view('medical_schools/custodian-declines', ['school' => $auth['school'], 'declines' => $declines]);
    }

    public function viewCustodianDecline()
    {
        $auth = $this->checkAuth();
        $id = $_GET['id'] ?? null;
        if (!$id)
            redirect('medical-school/custodian-declines');

        $decline = $auth['model']->getCustodianDeclineDetails($auth['school']->id, $id);
        $this->view('medical_schools/drawers/custodian-decline', ['school' => $auth['school'], 'decline' => $decline]);
    }

    // ── STAGE E & F: BODY SUBMISSIONS ─────────────────
    public function submissions()
    {
        $auth = $this->checkAuth();
        $status = $_GET['status'] ?? 'ALL';
        $submissions = $auth['model']->getBodySubmissions($auth['school']->id, $status);
        $caseModel = new \App\Models\DonationCaseModel();

        foreach ($submissions as &$sub) {
            $activeCase = $caseModel->getCaseByDonor($sub->donor_id);
            $deathDecl = $caseModel->getDeathDeclaration($sub->donor_id);
            $sub->clinical_deadline = $caseModel->getClinicalWindowStatus($activeCase, $deathDecl);
        }

        $this->view('medical_schools/submissions', [
            'school' => $auth['school'], 
            'submissions' => $submissions,
            'active_status' => $status
        ]);
    }

    public function viewSubmission()
    {
        $auth = $this->checkAuth();
        $id = $_GET['id'] ?? null;
        if (!$id)
            redirect('medical-school/submissions');

        $submission = $auth['model']->getSubmissionDetails($auth['school']->id, $id);
        $documents = $auth['model']->getSubmissionDocuments($id);
        $custodians = $submission ? $auth['model']->getCustodiansForDonor($submission->donor_id) : [];
        $this->view('medical_schools/drawers/submission', [
            'school' => $auth['school'], 
            'submission' => $submission, 
            'documents' => $documents,
            'custodians' => $custodians
        ]);
    }

    public function acceptSubmission()
    {
        $auth = $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['submission_id'] ?? null;
            $extra = [
                'handover_date' => $_POST['handover_date'] ?? null,
                'handover_time' => $_POST['handover_time'] ?? null,
                'handover_msg'  => $_POST['handover_message'] ?? ''
            ];

            if ($id && $extra['handover_date'] && $extra['handover_time']) {
                $sub = $auth['model']->getSubmissionDetails($auth['school']->id, $id);
                if ($sub) {
                    $caseModel = new \App\Models\DonationCaseModel();
                    $activeCase = $caseModel->getCaseByDonor($sub->donor_id);
                    $deathDecl = $caseModel->getDeathDeclaration($sub->donor_id);
                    $window = $caseModel->getClinicalWindowStatus($activeCase, $deathDecl);

                    if ($window && $window['is_expired']) {
                        $_SESSION['flash_error'] = "The clinical window for this donation has expired. Final document verification cannot be completed.";
                        redirect('medical-school/submissions');
                        die();
                    }
                }
                $auth['model']->updateDocumentStatus($auth['school']->id, $id, 'ACCEPTED', 'Documents Verified', $_SESSION['user_id'], $extra);
                $_SESSION['flash_success'] = "Documents verified and accepted. Handover scheduled.";
            } else {
                $_SESSION['flash_error'] = "Handover date and time are required to accept.";
            }
            redirect('medical-school/submissions');
            die();
        }
    }

    public function rejectSubmission()
    {
        $auth = $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['submission_id'] ?? null;
            $code = $_POST['rejection_reason_code'] ?? '';
            $otherText = $_POST['reason_other'] ?? '';
            $missingDocs = $_POST['missing_docs'] ?? []; // Array of doc names

            $reason = $code;
            if ($code === 'Other' || empty($code)) {
                $reason = $otherText;
            }

            $extra = [
                'reason_code' => $code,
                'missing_json' => !empty($missingDocs) ? json_encode($missingDocs) : null
            ];

            if ($id && ($code || $otherText)) {
                $auth['model']->updateDocumentStatus($auth['school']->id, $id, 'REJECTED', $reason, $_SESSION['user_id'], $extra);
                $_SESSION['flash_success'] = "Documents rejected and reason stored.";
            } else {
                $_SESSION['flash_error'] = "A rejection reason is required.";
            }
            redirect('medical-school/submissions');
            die();
        }
    }


    public function requestAdditionalDocuments()
    {
        $auth = $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['submission_id'] ?? null;
            $type = $_POST['reason_type'] ?? '';
            $details = $_POST['reason'] ?? '';

            $reason = trim($type);
            if ($type === 'Other' || empty($type)) {
                $reason = trim($details);
            } else if (!empty($details)) {
                $reason .= ' - ' . trim($details);
            }

            if ($id && $reason) {
                $auth['model']->updateDocumentStatus($auth['school']->id, $id, 'NEED_MORE_DOCS', $reason, $_SESSION['user_id']);
                $_SESSION['flash_success'] = "Additional documents requested seamlessly.";
            } else {
                $_SESSION['flash_error'] = "Reason is required to request more docs.";
            }
            redirect('medical-school/submissions');
            die();
        }
    }

    // ── STAGE G: FINAL BODY EXAMINATION ─────────────────
    public function finalExaminations()
    {
        $auth = $this->checkAuth();
        $status = $_GET['status'] ?? 'ALL';
        $exams = $auth['model']->getFinalExaminations($auth['school']->id, $status);
        $this->view('medical_schools/final-examinations', [
            'school' => $auth['school'], 
            'exams' => $exams,
            'active_status' => $status
        ]);
    }

    public function viewFinalExamination()
    {
        $auth = $this->checkAuth();
        $id = $_GET['id'] ?? null;
        if (!$id)
            redirect('medical-school/final-examinations');

        $exam = $auth['model']->getFinalExaminationDetails($auth['school']->id, $id);
        $certificate = $auth['model']->getCertificateByCisId($id);
        $this->view('medical_schools/drawers/final-examination', [
            'school' => $auth['school'], 
            'exam' => $exam,
            'certificate' => $certificate
        ]);
    }

    public function viewInventoryDetail()
    {
        $auth = $this->checkAuth();
        $id = $_GET['id'] ?? null;
        if (!$id) exit;

        $case = $auth['model']->getFinalExaminationDetails($auth['school']->id, $id);
        $this->view('medical_schools/drawers/inventory-detail', [
            'school' => $auth['school'],
            'case' => $case
        ]);
    }

    public function acceptFinalBody()
    {
        $auth = $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['exam_id'] ?? null;
            if ($id) {
                $auth['model']->updateFinalExamStatus($auth['school']->id, $id, 'ACCEPTED', null, null, $_SESSION['user_id']);
                $_SESSION['flash_success'] = "Body formally accepted into the institution.";
            }
            redirect('medical-school/final-examinations');
            die();
        }
    }

    public function rejectFinalBody()
    {
        $auth = $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['exam_id'] ?? null;
            $reason = $_POST['reason'] ?? '';
            $notes = $_POST['notes'] ?? '';
            if ($id && $reason) {
                $auth['model']->updateFinalExamStatus($auth['school']->id, $id, 'REJECTED', $reason, $notes, $_SESSION['user_id']);
                $_SESSION['flash_success'] = "Body rejected. Reasons and notes formally archived.";
            } else {
                $_SESSION['flash_error'] = "Rejection reason is absolutely mandatory.";
            }
            redirect('medical-school/final-examinations');
            die();
        }
    }

    // ── RECOGNITION & EXTENDED FORMS ─────────────────
    public function appreciation()
    {
        $auth = $this->checkAuth();
        $letters = $auth['model']->getAppreciationLetters($auth['school']->id);
        $this->view('medical_schools/appreciation', ['school' => $auth['school'], 'letters' => $letters]);
    }


    public function certificates()
    {
        $auth = $this->checkAuth();
        $certificates = $auth['model']->getDonationCertificates($auth['school']->id);
        $this->view('medical_schools/certificates', ['school' => $auth['school'], 'certificates' => $certificates]);
    }

    public function viewCertificate()
    {
        $auth = $this->checkDocumentAuth();
        $id = $_GET['id'] ?? null;
        if($id) {
            $certificate = $auth['model']->getDonationCertificateById($id);
            $this->view('medical_schools/print-certificate', ['id' => $id, 'certificate' => $certificate]);
        }
    }

    public function stories()
    {
        $auth = $this->checkAuth();
        $stories = $auth['model']->getStories($auth['school']->id);
        $this->view('medical_schools/stories', ['school' => $auth['school'], 'stories' => $stories]);
    }

    public function createStory()
    {
        $auth = $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $storyModel = new SuccessStoryModel();
            
            $data = [
                'institution_id' => $auth['school']->id,
                'institution_type' => 'MEDICAL_SCHOOL',
                'title' => $_POST['title'] ?? '',
                'description' => $_POST['description'] ?? '',
                'story_type' => $_POST['story_type'] ?? 'IMPACT',
                'author_name' => $_POST['author_name'] ?? null,
                'donors_count' => (int)($_POST['donors_count'] ?? 0),
                'students_helped' => (int)($_POST['students_helped'] ?? 0),
                'success_date' => $_POST['success_date'] ?? date('Y-m-d'),
                'status' => 'Pending'
            ];

            $storyModel->saveStory($data);
            header("Location: " . ROOT . "/medical-school/stories?success=1");
            exit;
        }
    }

    public function deleteStory()
    {
        $auth = $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['story_id'] ?? null;
            if ($id) {
                $storyModel = new SuccessStoryModel();
                $story = $storyModel->getStoryById($id);
                
                // Security check: ensure the story belongs to this school
                if ($story && $story->institution_id == $auth['school']->id && $story->institution_type === 'MEDICAL_SCHOOL') {
                    $storyModel->deleteStory($id);
                }
            }
            header("Location: " . ROOT . "/medical-school/stories?deleted=1");
            exit;
        }
    }

    public function archived()
    {
        $auth = $this->checkAuth();
        $archived = $auth['model']->getArchivedRecords($auth['school']->id);
        $this->view('medical_schools/archived', ['school' => $auth['school'], 'archived' => $archived]);
    }

    /**
     * Diagnostic helper to reset a donor for re-testing
     * URL: medical-school/debug/reset-donor?id=DR-00037
     */
    public function resetDonor()
    {
        $auth = $this->checkAuth();
        $id = $_GET['id'] ?? null;
        if ($id) {
            $auth['model']->resetDonorUsage($id, $auth['school']->id);
            $_SESSION['flash_success'] = "Test donor #{$id} reset successfully. Logs and letters cleared.";
        }
        redirect('medical-school/usage-logs');
    }


    public function usageLogs($cisId = null)
    {
        $auth = $this->checkAuth();
        
        // Handle cis_id passed via argument or URL parameter
        $cisId = $cisId ?? ($_GET['cis_id'] ?? null);
        
        $caseInfo = null;
        if ($cisId) {
            $caseInfo = $auth['model']->getAnatomicalCaseInfo($auth['school']->id, $cisId);
        }

        $logs = $auth['model']->getUsageLogs($auth['school']->id, $cisId);
        $inventory = $auth['model']->getAnatomicalInventory($auth['school']->id);
        $inventoryStats = $auth['model']->getInventoryStats($auth['school']->id);
        
        $this->view('medical_schools/usage-logs', [
            'school' => $auth['school'],
            'caseInfo' => $caseInfo,
            'logs' => $logs,
            'inventory' => $inventory,
            'inventoryStats' => $inventoryStats,
            'cis_id' => $cisId,
            'activePage' => 'usage-logs'
        ]);
    }

    public function submitUsage()
    {
        $auth = $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $dept = $_POST['usage_department'] === 'Other' ? ($_POST['other_dept'] ?? 'Other') : $_POST['usage_department'];
            $subject = $_POST['subject_area'] === 'Other' ? ($_POST['other_subject'] ?? 'Other') : $_POST['subject_area'];

            $data = [
                'donor_id' => $_POST['donor_id'] ?? null,
                'school_id' => $auth['school']->id,
                'usage_type' => $_POST['usage_type'] ?? 'Teaching',
                'description' => $_POST['description'] ?? '',
                'usage_date' => $_POST['usage_date'] ?? date('Y-m-d'),
                'usage_department' => $dept,
                'subject_area' => $subject,
                'handled_by' => $_POST['handled_by'] ?? '',
                'duration' => $_POST['duration'] ?? '',
                'other_notes' => $_POST['other_notes'] ?? ''
            ];

            if ($data['donor_id'] && $data['usage_date']) {
                $auth['model']->recordUsage($data);
                $_SESSION['flash_success'] = "Academic usage record added to the timeline successfully.";
            } else {
                $_SESSION['flash_error'] = "Required fields are missing.";
            }
            redirect($_SERVER['HTTP_REFERER'] ?? 'medical-school/usage-logs');
            die();
        }
    }

    public function issueAppreciationLetter()
    {
        $auth = $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usageId = $_POST['usage_id'] ?? null;
            if ($usageId) {
                $auth['model']->issueAppreciationLetter($usageId, $auth['school']->id, $_SESSION['user_id']);
                $_SESSION['flash_success'] = "Appreciation letter issued formally.";
            }
            redirect($_SERVER['HTTP_REFERER'] ?? 'medical-school/usage-logs');
            die();
        }
    }

    public function viewAppreciationLetter()
    {
        $auth = $this->checkDocumentAuth();
        $id = $_GET['id'] ?? null;
        $letter = $auth['model']->getAppreciationLetter($id);

        if (!$letter) {
            $_SESSION['flash_error'] = "Letter record not found.";
            redirect('medical-school/usage-logs');
            die();
        }

        $this->view('medical_schools/appreciation-letter', [
            'letter' => $letter
        ]);
    }

    public function reports()
    {
        $auth = $this->checkAuth();
        $this->view('medical_schools/reports', ['school' => $auth['school']]);
    }
}
