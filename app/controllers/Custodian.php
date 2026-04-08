<?php 

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CustodianModel;

class Custodian {
    use Controller;

    private $model;

    public function __construct()
    {
        $this->model = new CustodianModel();
    }

    /**
     * Helper: ensure user is logged in as CUSTODIAN and return custodian record
     */
    private function requireCustodian()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Unauthorized'], 401);
            }
            redirect('login');
            return null;
        }
        if (isset($_SESSION['role']) && $_SESSION['role'] !== 'CUSTODIAN') {
            if ($this->isAjax()) {
                $this->json(['error' => 'Forbidden'], 403);
            }
            redirect('login');
            return null;
        }

        $custodian = $this->model->getCustodianByUserId($_SESSION['user_id']);
        if (!$custodian) {
            if ($this->isAjax()) {
                $this->json(['error' => 'Custodian record not found'], 404);
            }
            redirect('login');
            return null;
        }
        return $custodian;
    }

    /**
     * Check if request is AJAX
     */
    private function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Send JSON response
     */
    private function json($data, $status = 200)
    {
        http_response_code($status);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    // ═══════════════════════════════════════════════════
    // PAGE VIEWS
    // ═══════════════════════════════════════════════════

    /**
     * GET /custodian
     * Hard redirect to /custodian/dashboard. Does NOT render inline.
     */
    public function index()
    {
        header('Location: ' . ROOT . '/custodian/dashboard');
        exit;
    }

    /** GET /custodian/dashboard */
    public function dashboard()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $this->renderPage('custodian/dashboard', 'dashboard', 'Dashboard', $custodian);
    }

    /** GET /custodian/consent */
    public function consent()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $this->renderPage('custodian/registered-consent', 'consent', 'Registered Consent', $custodian);
    }

    /** GET /custodian/donor-profile */
    public function donorProfile()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $this->renderPage('custodian/donor-profile', 'donor-profile', 'Donor Profile', $custodian);
    }

    /** GET /custodian/co-custodian */
    public function coCustodian()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $this->renderPage('custodian/co-custodian', 'co-custodian', 'Co-Custodian', $custodian);
    }

    /** GET /custodian/report-death */
    public function reportDeath()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $this->renderPage('custodian/report-death', 'report-death', 'Report Death', $custodian);
    }

    /** GET /custodian/legal-response */
    public function legalResponse()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $this->renderPage('custodian/legal-response', 'legal-response', 'Legal Response', $custodian);
    }

    /** GET /custodian/cadaver-data-sheet */
    public function cadaverDataSheet()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $this->renderPage('custodian/cadaver-data-sheet', 'cadaver-data-sheet', 'Cadaver Data Sheet', $custodian);
    }

    /** GET /custodian/documents */
    public function documentsPage()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $this->renderPage('custodian/documents', 'documents', 'Documents', $custodian);
    }

    /** GET /custodian/coordination */
    public function coordinationPage()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $this->renderPage('custodian/coordination', 'coordination', 'Coordination', $custodian);
    }

    /** GET /custodian/timeline */
    public function timelinePage()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $this->renderPage('custodian/timeline', 'timeline', 'Timeline', $custodian);
    }

    /** GET /custodian/certificates */
    public function certificates()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $this->renderPage('custodian/certificates', 'certificates', 'Certificates', $custodian);
    }

    /** GET /custodian/authority-limits */
    public function authorityLimits()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $this->renderPage('custodian/authority-limits', 'authority-limits', 'Authority Limits', $custodian);
    }

    /**
     * Shared helper: build layout variables and call $this->view()
     */
    private function renderPage(string $viewName, string $activePage, string $pageTitle, $custodian)
    {
        $firstName = $custodian->first_name ?? $custodian->custodian_first_name ?? '';
        $lastName  = $custodian->last_name  ?? $custodian->custodian_last_name  ?? '';
        $fullName  = trim($firstName . ' ' . $lastName) ?: 'Custodian';
        $cidRaw    = $custodian->id ?? $custodian->custodian_id ?? 0;

        $this->view($viewName, [
            'ROOT'                  => ROOT,
            'page_title'            => $pageTitle,
            'active_page'           => $activePage,
            'custodian'             => $custodian,
            'custodian_name'        => $fullName,
            'custodian_id_display'  => 'CID-' . str_pad($cidRaw, 5, '0', STR_PAD_LEFT),
        ]);
    }

    // ═══════════════════════════════════════════════════
    // JSON API ENDPOINTS
    // ═══════════════════════════════════════════════════

    /**
     * GET /custodian/dashboard-data
     * Returns: donor info, case status, consent summary, timer
     */
    public function getDashboardData()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $donor = $this->model->getDonorForCustodian($custodian->id);
        $coCustodian = $this->model->getCoCustodian($custodian->donor_id, $custodian->id);
        $consent = $this->model->resolveActiveConsent($custodian->donor_id);
        $deathDecl = $this->model->getDeathDeclaration($custodian->donor_id);
        $donationCase = $this->model->getDonationCase($custodian->donor_id);

        $dashboard = [
            'custodian' => $custodian,
            'co_custodian' => $coCustodian,
            'donor' => $donor,
            'consent' => $consent,
            'death_declaration' => $deathDecl,
            'donation_case' => $donationCase,
            'is_deceased' => ($deathDecl !== null),
            'window_remaining' => null
        ];

        // Calculate remaining time in the window
        if ($deathDecl && $deathDecl->status === 'ACTIVE') {
            $expiresAt = strtotime($deathDecl->window_expires_at);
            $remaining = $expiresAt - time();
            $dashboard['window_remaining'] = max(0, $remaining);
        }

        $this->json(['success' => true, 'data' => $dashboard]);
    }

    /**
     * POST /custodian/declare-death
     * Body: date_of_death, time_of_death, place_of_death, cause_of_death, additional_notes
     */
    public function declareDeath()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        // Check if already declared
        $existing = $this->model->getDeathDeclaration($custodian->donor_id);
        if ($existing) {
            $this->json(['error' => 'Death has already been declared for this donor'], 409);
            return;
        }

        $data = [
            'donor_id' => $custodian->donor_id,
            'custodian_id' => $custodian->id,
            'date_of_death' => $_POST['date_of_death'] ?? '',
            'time_of_death' => $_POST['time_of_death'] ?? '',
            'place_of_death' => $_POST['place_of_death'] ?? '',
            'cause_of_death' => $_POST['cause_of_death'] ?? '',
            'additional_notes' => $_POST['additional_notes'] ?? null
        ];

        // Validate required fields
        foreach (['date_of_death', 'time_of_death', 'place_of_death', 'cause_of_death'] as $field) {
            if (empty($data[$field])) {
                $this->json(['error' => "Field '$field' is required"], 422);
                return;
            }
        }

        $deathId = $this->model->createDeathDeclaration($data);
        if (!$deathId) {
            $this->json(['error' => 'Failed to create death declaration'], 500);
            return;
        }

        // Resolve consent and auto-create donation case
        $consent = $this->model->resolveActiveConsent($custodian->donor_id);
        $caseId = $this->model->createDonationCase([
            'donor_id' => $custodian->donor_id,
            'death_declaration_id' => $deathId,
            'donation_type' => $consent['donation_type']
        ]);

        $this->json([
            'success' => true,
            'death_declaration_id' => $deathId,
            'donation_case_id' => $caseId,
            'donation_type' => $consent['donation_type']
        ]);
    }

    /**
     * GET /custodian/consent
     * Returns: active consent details + history
     */
    public function getConsent()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $consent = $this->model->resolveActiveConsent($custodian->donor_id);
        $this->json(['success' => true, 'data' => $consent]);
    }

    /**
     * GET /custodian/profile
     * Returns: donor profile + next of kin
     */
    public function getProfile()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $donor = $this->model->getDonorForCustodian($custodian->id);
        $nextOfKin = $this->model->getNextOfKin($custodian->donor_id);

        $this->json(['success' => true, 'data' => [
            'donor' => $donor,
            'next_of_kin' => $nextOfKin
        ]]);
    }

    /**
     * GET /custodian/custodians
     * Returns: both custodians for this donor
     */
    public function getCustodians()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $all = $this->model->getCustodiansByDonor($custodian->donor_id);
        $this->json(['success' => true, 'data' => $all]);
    }

    /**
     * POST /custodian/update-contact
     * Body: phone, email, address
     */
    public function updateContact()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $this->model->updateCustodianContact($custodian->id, [
            'phone' => $_POST['phone'] ?? null,
            'email' => $_POST['email'] ?? null,
            'address' => $_POST['address'] ?? null
        ]);

        $this->json(['success' => true, 'message' => 'Contact updated']);
    }

    /**
     * POST /custodian/legal-action
     * Body: action_type (CONFIRM|OBJECT), method (QUICK|PRINT_SIGN),
     *        reason_category, reason_text, remarks, signed files
     */
    public function submitLegalAction()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $donationCase = $this->model->getDonationCase($custodian->donor_id);
        if (!$donationCase) {
            $this->json(['error' => 'No active donation case'], 404);
            return;
        }

        if ($donationCase->legal_status !== 'PENDING') {
            $this->json(['error' => 'Legal action already taken'], 409);
            return;
        }

        // Handle signed document uploads
        $signedPath = null;
        $coSignedPath = null;
        $uploadDir = 'uploads/custodian/legal/' . $donationCase->case_number . '/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (isset($_FILES['signed_document']) && $_FILES['signed_document']['error'] === 0) {
            $ext = pathinfo($_FILES['signed_document']['name'], PATHINFO_EXTENSION);
            $signedPath = $uploadDir . 'signed_primary.' . $ext;
            move_uploaded_file($_FILES['signed_document']['tmp_name'], $signedPath);
        }

        if (isset($_FILES['co_signed_document']) && $_FILES['co_signed_document']['error'] === 0) {
            $ext = pathinfo($_FILES['co_signed_document']['name'], PATHINFO_EXTENSION);
            $coSignedPath = $uploadDir . 'signed_co.' . $ext;
            move_uploaded_file($_FILES['co_signed_document']['tmp_name'], $coSignedPath);
        }

        $data = [
            'donation_case_id' => $donationCase->id,
            'custodian_id' => $custodian->id,
            'action_type' => $_POST['action_type'] ?? 'CONFIRM',
            'method' => $_POST['method'] ?? 'QUICK',
            'reason_category' => $_POST['reason_category'] ?? null,
            'reason_text' => $_POST['reason_text'] ?? null,
            'remarks' => $_POST['remarks'] ?? null,
            'signed_document_path' => $signedPath,
            'co_signed_document_path' => $coSignedPath
        ];

        $id = $this->model->submitLegalAction($data);
        $this->json(['success' => true, 'legal_action_id' => $id]);
    }

    /**
     * GET /custodian/available-institutions
     * Query: track (BODY|ORGAN|CORNEA)
     */
    public function getAvailableInstitutions()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $donationCase = $this->model->getDonationCase($custodian->donor_id);
        if (!$donationCase) {
            $this->json(['error' => 'No active donation case'], 404);
            return;
        }

        $track = $_GET['track'] ?? 'BODY';
        $institutions = $this->model->getAvailableInstitutions($donationCase->id, $track);
        $currentInst = $this->model->getCurrentInstitution($donationCase->id, $track);

        $this->json(['success' => true, 'data' => [
            'available' => $institutions,
            'current' => $currentInst
        ]]);
    }

    /**
     * POST /custodian/select-institution
     * Body: institution_id, institution_type, track
     */
    public function selectInstitution()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $donationCase = $this->model->getDonationCase($custodian->donor_id);
        if (!$donationCase) {
            $this->json(['error' => 'No active donation case'], 404);
            return;
        }

        $result = $this->model->selectInstitution(
            $donationCase->id,
            $_POST['institution_id'] ?? 0,
            $_POST['institution_type'] ?? 'MEDICAL_SCHOOL',
            $_POST['track'] ?? 'BODY'
        );

        if ($result === false) {
            $this->json(['error' => 'Cannot select institution — another is currently under review or already accepted'], 409);
            return;
        }

        $this->json(['success' => true, 'case_institution_status_id' => $result]);
    }

    /**
     * POST /custodian/upload-document
     * Body: case_institution_status_id, document_type, file
     */
    public function uploadDocument()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $donationCase = $this->model->getDonationCase($custodian->donor_id);
        if (!$donationCase) {
            $this->json(['error' => 'No active donation case'], 404);
            return;
        }

        $statusId = $_POST['case_institution_status_id'] ?? 0;
        $docType = $_POST['document_type'] ?? '';

        if (!$statusId || !$docType) {
            $this->json(['error' => 'Missing required fields'], 422);
            return;
        }

        if (!isset($_FILES['file']) || $_FILES['file']['error'] !== 0) {
            $this->json(['error' => 'No file uploaded'], 422);
            return;
        }

        $uploadDir = 'uploads/custodian/docs/' . $donationCase->case_number . '/' . $statusId . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $fileName = $docType . '_' . time() . '.' . $ext;
        $filePath = $uploadDir . $fileName;
        move_uploaded_file($_FILES['file']['tmp_name'], $filePath);

        $docId = $this->model->uploadDocument([
            'donation_case_id' => $donationCase->id,
            'case_institution_status_id' => $statusId,
            'document_type' => $docType,
            'file_path' => $filePath
        ]);

        $this->json(['success' => true, 'document_id' => $docId, 'file_path' => $filePath]);
    }

    /**
     * GET /custodian/documents
     * Query: case_institution_status_id
     */
    public function getDocuments()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $statusId = $_GET['case_institution_status_id'] ?? 0;
        if ($statusId) {
            $docs = $this->model->getDocuments($statusId);
        } else {
            $donationCase = $this->model->getDonationCase($custodian->donor_id);
            $docs = $donationCase ? $this->model->getAllDocuments($donationCase->id) : [];
        }

        $this->json(['success' => true, 'data' => $docs]);
    }

    /**
     * POST /custodian/submit-institution
     * Body: case_institution_status_id
     */
    public function submitToInstitution()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $statusId = $_POST['case_institution_status_id'] ?? 0;
        if (!$statusId) {
            $this->json(['error' => 'Missing status ID'], 422);
            return;
        }

        $this->model->submitToInstitution($statusId);
        $this->json(['success' => true, 'message' => 'Documents submitted to institution']);
    }

    /**
     * POST /custodian/cadaver-sheet
     * Body: case_institution_status_id, form_data (JSON string), status
     */
    public function saveCadaverSheet()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $donationCase = $this->model->getDonationCase($custodian->donor_id);
        if (!$donationCase) {
            $this->json(['error' => 'No active donation case'], 404);
            return;
        }

        $formData = json_decode($_POST['form_data'] ?? '{}', true);

        $this->model->saveCadaverSheet([
            'donation_case_id' => $donationCase->id,
            'case_institution_status_id' => $_POST['case_institution_status_id'] ?? 0,
            'form_data' => $formData,
            'status' => $_POST['status'] ?? 'DRAFT'
        ]);

        $this->json(['success' => true, 'message' => 'Cadaver data sheet saved']);
    }

    /**
     * GET /custodian/cadaver-sheet-data
     * Query: case_institution_status_id
     */
    public function getCadaverSheet()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $statusId = $_GET['case_institution_status_id'] ?? 0;
        $sheet = $this->model->getCadaverSheet($statusId);

        $this->json(['success' => true, 'data' => $sheet]);
    }

    /**
     * GET /custodian/coordination
     * Returns: all institution statuses for the case
     */
    public function getCoordination()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $donationCase = $this->model->getDonationCase($custodian->donor_id);
        if (!$donationCase) {
            $this->json(['error' => 'No active donation case'], 404);
            return;
        }

        $statuses = $this->model->getInstitutionStatuses($donationCase->id);
        $this->json(['success' => true, 'data' => $statuses]);
    }

    /**
     * GET /custodian/timeline
     * Returns: chronological audit trail
     */
    public function getTimeline()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $donationCase = $this->model->getDonationCase($custodian->donor_id);
        if (!$donationCase) {
            $this->json(['error' => 'No active donation case'], 404);
            return;
        }

        $timeline = $this->model->getTimeline($donationCase->id);
        $this->json(['success' => true, 'data' => $timeline]);
    }
}
