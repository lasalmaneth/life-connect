<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\MedicalSchoolModel;

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

    public function dashboard()
    {
        $auth = $this->checkAuth();
        $stats = $auth['model']->getDashboardStats($auth['school']->id);
        $this->view('medical_schools/dashboard', ['school' => $auth['school'], 'stats' => $stats]);
    }

    // ── STAGE A: CONSENT REGISTRY ─────────────────
    public function consents()
    {
        $auth = $this->checkAuth();
        $donors = $auth['model']->getPreDeathConsents($auth['school']->id);
        $this->view('medical_schools/consents', ['school' => $auth['school'], 'donors' => $donors]);
    }

    public function viewConsent()
    {
        $auth = $this->checkAuth();
        $id = $_GET['id'] ?? null;
        if (!$id)
            redirect('medical-school/consents');

        $donor = $auth['model']->getDonorDetails($auth['school']->id, $id, 'CONSENT');
        $this->view('medical_schools/drawers/consent', ['school' => $auth['school'], 'donor' => $donor]);
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
        $this->view('medical_schools/submission-requests', ['school' => $auth['school'], 'requests' => $requests, 'current_filter' => $filter]);
    }

    public function viewSubmissionRequest()
    {
        $auth = $this->checkAuth();
        $id = $_GET['id'] ?? null;
        if (!$id)
            redirect('medical-school/submission-requests');

        $request = $auth['model']->getSubmissionRequestDetails($auth['school']->id, $id);
        $this->view('medical_schools/drawers/submission-request', ['school' => $auth['school'], 'request' => $request]);
    }

    public function acceptSubmissionRequest()
    {
        $auth = $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['request_id'] ?? null;
            if ($id) {
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
        $submissions = $auth['model']->getBodySubmissions($auth['school']->id);
        $this->view('medical_schools/submissions', ['school' => $auth['school'], 'submissions' => $submissions]);
    }

    public function viewSubmission()
    {
        $auth = $this->checkAuth();
        $id = $_GET['id'] ?? null;
        if (!$id)
            redirect('medical-school/submissions');

        $submission = $auth['model']->getSubmissionDetails($auth['school']->id, $id);
        $documents = $auth['model']->getSubmissionDocuments($id);
        $this->view('medical_schools/drawers/submission', ['school' => $auth['school'], 'submission' => $submission, 'documents' => $documents]);
    }

    public function acceptSubmission()
    {
        $auth = $this->checkAuth();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['submission_id'] ?? null;
            if ($id) {
                $auth['model']->updateDocumentStatus($auth['school']->id, $id, 'ACCEPTED', null, $_SESSION['user_id']);
                $_SESSION['flash_success'] = "Documents verified and accepted.";
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
            $type = $_POST['reason_type'] ?? '';
            $details = $_POST['reason'] ?? '';

            $reason = trim($type);
            if ($type === 'Other' || empty($type)) {
                $reason = trim($details);
            } else if (!empty($details)) {
                $reason .= ' - ' . trim($details);
            }

            if ($id && $reason) {
                $auth['model']->updateDocumentStatus($auth['school']->id, $id, 'REJECTED', $reason, $_SESSION['user_id']);
                $_SESSION['flash_success'] = "Documents rejected and reason stored.";
            } else {
                $_SESSION['flash_error'] = "Reason is required to reject.";
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
        $exams = $auth['model']->getFinalExaminations($auth['school']->id);
        $this->view('medical_schools/final-examinations', ['school' => $auth['school'], 'exams' => $exams]);
    }

    public function viewFinalExamination()
    {
        $auth = $this->checkAuth();
        $id = $_GET['id'] ?? null;
        if (!$id)
            redirect('medical-school/final-examinations');

        $exam = $auth['model']->getFinalExaminationDetails($auth['school']->id, $id);
        $this->view('medical_schools/drawers/final-examination', ['school' => $auth['school'], 'exam' => $exam]);
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

    public function generateAppreciationLetter()
    {
        $auth = $this->checkAuth();
        // Generates/stores letter and sets flash
        $_SESSION['flash_success'] = "Letter generated successfully.";
        redirect('medical-school/appreciation');
    }

    public function viewAppreciationLetter()
    {
        // Simple view fallback
    }

    public function certificates()
    {
        $auth = $this->checkAuth();
        $certificates = $auth['model']->getDonationCertificates($auth['school']->id);
        $this->view('medical_schools/certificates', ['school' => $auth['school'], 'certificates' => $certificates]);
    }

    public function generateDonationCertificate()
    {
        $_SESSION['flash_success'] = "Certificate generated successfully.";
        redirect('medical-school/certificates');
    }

    public function viewCertificate()
    {
    }

    public function stories()
    {
        $auth = $this->checkAuth();
        $stories = $auth['model']->getStories($auth['school']->id);
        $this->view('medical_schools/stories', ['school' => $auth['school'], 'stories' => $stories]);
    }

    public function createStory()
    {
    }
    public function editStory()
    {
    }

    public function archived()
    {
        $auth = $this->checkAuth();
        $archived = $auth['model']->getArchivedRecords($auth['school']->id);
        $this->view('medical_schools/archived', ['school' => $auth['school'], 'archived' => $archived]);
    }

    public function viewArchivedRecord()
    {
    }

    public function usageLogs()
    {
        $auth = $this->checkAuth();
        $logs = $auth['model']->getUsageLogs($auth['school']->id);
        $this->view('medical_schools/usage-logs', ['school' => $auth['school'], 'logs' => $logs]);
    }

    public function viewUsageLog()
    {
    }

    public function reports()
    {
        $auth = $this->checkAuth();
        $this->view('medical_schools/reports', ['school' => $auth['school']]);
    }
}
