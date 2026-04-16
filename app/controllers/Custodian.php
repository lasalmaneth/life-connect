<?php 

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CustodianModel;
use App\Models\UserModel;
class Custodian {
    use Controller;

    private $model;
    private $caseModel;

    public function __construct()
    {
        $this->model = new CustodianModel();
        $this->caseModel = new \App\Models\DonationCaseModel();
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
        // show($custodian);
        if (!$custodian) {
            // if ($this->isAjax()) {
            //     $this->json(['error' => 'Custodian record not found'], 404);
            // }
            redirect('login');
            return null;
        }

        
        // --- SECURITY SETUP GUARD ---
        // Source of truth for security flag is the 'users' table
        // $userModel = new UserModel();
        // $userSession = $userModel->getUserById($_SESSION['user_id']);
        // show($userSession);

        // if ($userSession && !empty($userSession->must_change_credentials)) {
        //     $url = $_GET['url'] ?? '';
        //     if (strpos($url, 'custodian/security-setup') === false && strpos($url, 'custodian/update-security') === false) {
        //         redirect('custodian/security-setup');
        //         return null;
        //     }
        // }

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

        $donorId = $custodian->donor_id;
        $activeCase = $this->caseModel->getCaseByDonor($donorId);
        $certificates = $activeCase ? $this->model->getDonationCertificates($activeCase->id) : [];
        $appreciationLetters = $activeCase ? $this->model->getAppreciationLetters($activeCase->id) : [];
        
        // Fetch activity timeline (Limit to top 5 for dashboard)
        $fullTimeline = $activeCase ? $this->caseModel->getTimeline($activeCase->id) : [];
        $timeline = array_slice($fullTimeline, 0, 5);

        $this->renderPage('custodian/dashboard', 'dashboard', 'Dashboard', $custodian, [
            'certificates' => $certificates,
            'appreciation_letters' => $appreciationLetters,
            'timeline' => $timeline
        ]);
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
        header('Location: /custodian/documents');
        exit;
    }

    /** GET /custodian/documents */
    public function documentsPage()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $donorId = $custodian->donor_id;
        $cidRaw = $custodian->id ?? $custodian->cid;
        $activeCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
        $docs = [];
        $hasSworn = false;
        
        $hasDatasheet = false;
        
        if ($activeCase) {
            $docs = $this->model->getDocuments($activeCase->id) ?? [];
            $swornRecord = $this->model->getSwornStatement($activeCase->id);
            if ($swornRecord && !empty($swornRecord->form_data)) {
                $hasSworn = true;
            }
            $dataSheetRecord = $this->model->getCadaverDataSheet($activeCase->id);
            if ($dataSheetRecord && !empty($dataSheetRecord->form_data)) {
                $hasDatasheet = true;
            }
        }

        // Resolve track and current institution
        $consent = $this->model->resolveActiveConsent($custodian->donor_id);
        $track = $consent['donation_type'] ?? 'BODY';
        $currentInst = ($activeCase) ? $this->caseModel->getCurrentInstitution($activeCase->id, $track) : null;

        // Fetch review status here to avoid calling model from view
        $review = $this->model->getDocumentReviewStatus($activeCase->id ?? 0);

        // Get Recognition Documents
        $certificates = $this->model->getDonationCertificates($activeCase->id ?? 0);
        $appreciationLetters = $this->model->getAppreciationLetters($activeCase->id ?? 0);

        $this->renderPage('custodian/documents', 'documents', 'Documents', $custodian, [
            'docs' => $docs,
            'activeCase' => $activeCase,
            'hasSworn' => $hasSworn,
            'hasDatasheet' => $hasDatasheet,
            'review' => $review,
            'currentInst' => $currentInst,
            'consent' => $consent,
            'certificates' => $certificates,
            'appreciation_letters' => $appreciationLetters
        ]);
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

    public function activityHistory()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $donorId = $custodian->donor_id;
        $activeCase = $this->caseModel->getCaseByDonor($donorId);
        
        // Full activity timeline
        $timeline = $activeCase ? $this->caseModel->getTimeline($activeCase->id) : [];

        // Historical / Archived cases for this donor
        $archived = $this->model->getArchivedCases($donorId);
        
        $this->renderPage('custodian/activity-history', 'activity-history', 'Activity History', $custodian, [
            'timeline' => $timeline,
            'archived' => $archived
        ]);
    }

    public function institutionRequests()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;
        // show($custodian);
        // $donorId = $custodian->donor_id;
        // show($donorId);
        // $cidRaw = $custodian->id ?? $custodian->cid;

        $availableInstitutions = [];
        $institutionStatuses = [];
        $institutionType = 'MEDICAL_SCHOOL';
        $activeCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
        // show($activeCase);
        if ($activeCase) {
            $consent = $this->model->resolveActiveConsent($activeCase->donor_id);
            // show($consent);
            $track = $consent['donation_type'] ?? 'BODY';
            $institutionType = ($track === 'BODY' || $track === 'BODY_AND_CORNEA') ? 'MEDICAL_SCHOOL' : 'HOSPITAL';
            //  show($institutionType);
            $availableInstitutions = $this->caseModel->getAvailableInstitutions($activeCase->id, $track) ?: [];
            // show($availableInstitutions);
            $institutionStatuses = $this->caseModel->getInstitutionStatuses($activeCase->id) ?: [];
            //  show($institutionStatuses);
        }

        $this->renderPage('custodian/institution-requests', 'institution-requests', 'Institution Requests', $custodian, [
            'availableInstitutions' => $availableInstitutions,
            'institutionStatuses'   => $institutionStatuses,
            'institutionType'       => $institutionType
        ]);
    }

    public function profile()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name'         => $_POST['name'] ?? '',
                'relationship' => $_POST['relationship'] ?? '',
                'phone'        => $_POST['phone'] ?? '',
                'email'        => $_POST['email'] ?? '',
                'address'      => $_POST['address'] ?? ''
            ];

            if ($this->model->updateCustodianContact($custodian->id, $data)) {
                $_SESSION['success'] = "Profile updated successfully.";
                redirect('custodian/profile');
                return;
            } else {
                $_SESSION['error'] = "Failed to update profile.";
            }
        }

        $custodians = $this->model->getCustodiansByDonor($custodian->donor_id);

        // Put "Me" at the top of the list
        usort($custodians, function($a, $b) use ($custodian) {
            if ($a->id == $custodian->id) return -1;
            if ($b->id == $custodian->id) return 1;
            return 0;
        });

        $this->renderPage('custodian/profile', 'profile', 'Custodian Profile', $custodian, [
            'custodians' => $custodians
        ]);
    }

    /** GET /custodian/authority-limits */
    public function authorityLimits()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $this->renderPage('custodian/authority-limits', 'authority-limits', 'Authority Limits', $custodian);
    }

    /** GET /custodian/security-setup */
    public function securitySetup()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        // Fetch user object to get current username
        $userModel = new \App\Models\UserModel();
        $user = $userModel->getUserById($_SESSION['user_id']);

        $this->view('custodian/security-setup', [
            'ROOT' => ROOT,
            'custodian' => $custodian,
            'user' => $user,
            'page_title' => 'Account Security Setup'
        ]);
    }

    /** POST /custodian/update-security */
    public function updateSecurity()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('custodian/security-setup');
            return;
        }

        $userModel = new \App\Models\UserModel();
        $user = $userModel->getUserById($_SESSION['user_id']);

        $currentUsername = $_POST['current_username'] ?? '';
        $newUsername = trim($_POST['new_username'] ?? '');
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $errors = [];

        // 1. Validate Current Password
        if (!password_verify($currentPassword, $user->password_hash)) {
            $errors[] = "Current password is incorrect.";
        }

        // 2. Validate New Password (Registration strength: 8 chars, ULNS)
        if (strlen($newPassword) < 8) {
            $errors[] = "New password must be at least 8 characters.";
        } elseif (!preg_match('/[A-Z]/', $newPassword) ||
                  !preg_match('/[a-z]/', $newPassword) ||
                  !preg_match('/[0-9]/', $newPassword) ||
                  !preg_match('/[^A-Za-z0-9]/', $newPassword)) {
            $errors[] = "New password must include uppercase, lowercase, number and special character.";
        }

        if ($newPassword === ($user->username)) {
            $errors[] = "New password cannot be the same as your default NIC-based credentials.";
        }

        if ($newPassword !== $confirmPassword) {
            $errors[] = "New passwords do not match.";
        }

        // 3. Validate New Username (Optional)
        $finalUsername = $user->username;
        if (!empty($newUsername) && $newUsername !== $user->username) {
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $newUsername)) {
                $errors[] = "Username can contain only letters, numbers and underscores.";
            } elseif ($userModel->usernameExists($newUsername)) {
                $errors[] = "The new username is already taken.";
            } else {
                $finalUsername = $newUsername;
            }
        }

        if (!empty($errors)) {
            $_SESSION['security_errors'] = $errors;
            redirect('custodian/security-setup');
            return;
        }

        // 4. Update Database
        $newHash = password_hash($newPassword, PASSWORD_DEFAULT);
        if ($userModel->updateCredentials($user->id, $finalUsername, $newHash)) {
            $userModel->clearMustChangeFlag($user->id);
            $_SESSION['username'] = $finalUsername; // Update session
            $_SESSION['success_message'] = "Account details updated successfully.";
            redirect('custodian/dashboard');
        } else {
            $_SESSION['security_errors'] = ["Failed to update account details. Please try again."];
            redirect('custodian/security-setup');
        }
    }

    /**
     * Shared helper: build layout variables and call $this->view()
     */
    private function renderPage(string $viewName, string $activePage, string $pageTitle, $custodian, array $extraData = [])
    {
        $firstName = $custodian->first_name ?? $custodian->custodian_first_name ?? '';
        $lastName  = $custodian->last_name  ?? $custodian->custodian_last_name  ?? '';
        $fullName  = trim($firstName . ' ' . $lastName) ?: 'Custodian';
        $cidRaw    = $custodian->id ?? $custodian->custodian_id ?? 0;

        // Fetch core entities explicitly so all views have them
        $donor = $this->model->getDonorForCustodian($cidRaw);
        $coCustodian = null;
        $consent = null;
        $deathDecl = null;
        $donationCase = null;
        $activeCase = null;
        $currentRequest = null;
        $currentInstRequest = null;

        if ($donor) {
            $donorId = $donor->id ?? $donor->donor_id;
            $coCustodian = $this->model->getCoCustodian($donorId, $cidRaw);
            $consent = $this->model->resolveActiveConsent($donorId);
            $deathDecl = $this->caseModel->getDeathDeclaration($donorId);
            $donationCase = $this->caseModel->getCaseByDonor($donorId);
            
            if ($donationCase) {
                $activeCase = $donationCase;
                // Check for system forms
                $hasSworn = ($this->model->getSwornStatement($donationCase->id) !== null);
                $hasDatasheet = ($this->model->getCadaverDataSheet($donationCase->id) !== null);

                // Get Recognition Documents
                $certificates = $this->model->getDonationCertificates($activeCase->id);
                $appreciationLetters = $this->model->getAppreciationLetters($activeCase->id);

                // Get current institution request (Pending/Accepted/Rejected by Medical School)
                $track = $consent['donation_type'] ?? 'BODY';
                $currentInstRequest = $this->caseModel->getCurrentInstitution($donationCase->id, $track);
                
                // CRITICAL: Bridge for stepper logic
                $currentRequest = $currentInstRequest;
            }
        }

        // --- ENFORCE PROCESS LEADER LOGIC ---
        // If death reported, the reporter is the leader. 
        // If not reported, any custodian can become leader by reporting it.
        $isLeader = true;
        if ($deathDecl) {
            $isLeader = ($deathDecl->declared_by_custodian_id == $cidRaw);
        }

        $viewData = array_merge([
            'ROOT'                  => ROOT,
            'page_title'            => $pageTitle,
            'active_page'           => $activePage,
            'custodian'             => $custodian,
            'custodian_name'        => $fullName,
            'custodian_id_display'  => 'CID-' . str_pad($cidRaw, 5, '0', STR_PAD_LEFT),
            'donor'                 => $donor,
            'co_custodian'          => $coCustodian,
            'consent'               => $consent,
            'death_declaration'     => $deathDecl,
            'isLeader'              => $isLeader,
            'leaderInfo'            => $deathDecl, // Contains declared_by_name, phone, email
            'donation_case'         => $donationCase,
            'activeCase'            => $activeCase,
            'certificates'          => $certificates ?? [],
            'appreciation_letters'  => $appreciationLetters ?? [],
            'currentRequest'        => $currentRequest,
            'currentInstRequest'    => $currentInstRequest,
            'hasSworn'              => $hasSworn ?? false,
            'hasDatasheet'          => $hasDatasheet ?? false,
        ], $extraData);

        $this->view($viewName, $viewData);
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
        $deathDecl = $this->caseModel->getDeathDeclaration($custodian->donor_id);
        $donationCase = $this->caseModel->getCaseByDonor($custodian->donor_id);

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
        $existing = $this->caseModel->getDeathDeclaration($custodian->donor_id);
        if ($existing) {
            $this->json(['error' => 'Death has already been declared for this donor'], 409);
            return;
        }

        $data = [
            'donor_id' => $custodian->donor_id,
            'declared_by_custodian_id' => $custodian->id,
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

        $deathId = $this->caseModel->createDeathDeclaration($data);
        if (!$deathId) {
            $this->json(['error' => 'Failed to create death declaration'], 500);
            return;
        }

        // Resolve consent and auto-create donation case
        $consent = $this->model->resolveActiveConsent($custodian->donor_id);
        $caseId = $this->caseModel->createDonationCase([
            'donor_id' => $custodian->donor_id,
            'death_declaration_id' => $deathId,
            'donation_type' => $consent['donation_type']
        ]);

        $this->json([
            'success' => true,
            'death_declaration_id' => $deathId,
            'donation_case_id' => $caseId,
            'donation_type' => $consent['donation_type'],
            'redirect' => ROOT . '/custodian/dashboard'
        ]);
    }

    /**
     * AJAX GET /custodian/check-username
     * Query: username
     */
    public function checkUsername()
    {
        $this->requireCustodian();
        $username = trim($_GET['username'] ?? '');
        $currentUserId = $_SESSION['user_id'];

        if (empty($username)) {
            $this->json(['available' => false, 'message' => 'Username cannot be empty']);
        }

        $userModel = new \App\Models\UserModel();
        $currentUser = $userModel->getUserById($currentUserId);

        // If it's the current username, it's available for the current user
        if ($username === $currentUser->username) {
            $this->json(['available' => true, 'message' => 'Current username']);
        }

        if ($userModel->usernameExists($username)) {
            $this->json(['available' => false, 'message' => 'Username is already taken']);
        }

        $this->json(['available' => true, 'message' => 'Username is available']);
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

        $donationCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
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
        $uploadDir = 'assets/uploads/custodian/legal/' . $donationCase->case_number . '/';

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

        $donationCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
        if (!$donationCase) {
            $this->json(['error' => 'No active donation case'], 404);
            return;
        }

        $track = $_GET['track'] ?? 'BODY';
        $institutions = $this->caseModel->getAvailableInstitutions($donationCase->id, $track);
        $currentInst = $this->caseModel->getCurrentInstitution($donationCase->id, $track);

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
            // if ($this->isAjax()) {
            //     $this->json(['error' => 'Method not allowed'], 405);
            // } else {
                redirect('custodian/institution-requests');
            
            return;
        }

        $donationCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
        // if (!$donationCase) {
        //     if ($this->isAjax()) {
        //         $this->json(['error' => 'No active donation case'], 404);
        //     } else {
        //         $_SESSION['error_message'] = "No active donation case found.";
        //         redirect('custodian/institution-requests');
        //     }
        //     return;
        // }

        $result = $this->model->selectInstitution(
            $donationCase->id,
            $_POST['institution_id'] ?? 0,
            $_POST['institution_type'] ?? 'MEDICAL_SCHOOL',
            $_POST['track'] ?? 'BODY'
        );
        // show($result);
        if ($result === false) {
            $err = 'Cannot select institution: it is either not permitted by the donor\'s consent profile, or another request is currently active.';
            // if ($this->isAjax()) {
            //     $this->json(['error' => $err], 409);
            // } else {
                $_SESSION['error_message'] = $err;
                redirect('custodian/institution-requests');
            
            return;
        }

        // if ($this->isAjax()) {
        //     $this->json(['success' => true, 'case_institution_status_id' => $result]);
        // } else {
            $_SESSION['success_message'] = "Institution request sent successfully.";
            redirect('custodian/institution-requests');
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

        $donationCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
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

        $uploadDir = 'assets/uploads/custodian/docs/' . $donationCase->case_number . '/' . $statusId . '/';
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
            $donationCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
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

        $donationCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
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

        $donationCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
        if (!$donationCase) {
            $this->json(['error' => 'No active donation case'], 404);
            return;
        }

        $statuses = $this->caseModel->getInstitutionStatuses($donationCase->id);
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

        $donationCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
        if (!$donationCase) {
            $this->json(['error' => 'No active donation case'], 404);
            return;
        }

        $timeline = $this->caseModel->getTimeline($donationCase->id);
        $this->json(['success' => true, 'data' => $timeline]);
    }

    // ─── DOCUMENT FORMS & BUNDLE ──────────────────────────────────────────

    public function documentForm() {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $donorId = $custodian->donor_id;
        $cidRaw = $custodian->id ?? $custodian->cid;
        $deathDecl = $this->caseModel->getDeathDeclaration($donorId);

        if ($deathDecl && $deathDecl->declared_by_custodian_id != $cidRaw) {
            header('Location: ' . ROOT . '/custodian/dashboard');
            exit;
        }
        $type = $_GET['type'] ?? '';
        if (!in_array($type, ['sworn', 'datasheet'])) {
            header('Location: ' . ROOT . '/custodian/documents');
            exit;
        }

        $activeCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
        if (!$activeCase) {
             header('Location: ' . ROOT . '/custodian/documents');
             exit;
        }

        // Prevent accessing datasheet if sworn isn't filled
        if ($type === 'datasheet') {
            $swornRecord = $this->model->getSwornStatement($activeCase->id);
            if (!$swornRecord || empty($swornRecord->form_data)) {
                 $_SESSION['flash_error'] = "You must fill out the Sworn Statement before the Data Sheet.";
                 header('Location: ' . ROOT . '/custodian/documents');
                 exit;
            }
        }

        $consent = $this->model->resolveActiveConsent($activeCase->donor_id);
        $track = $consent['donation_type'] ?? 'BODY';
        $currentInst = $this->caseModel->getCurrentInstitution($activeCase->id, $track);
        $instName = $currentInst ? $currentInst->institution_name : 'Department of Anatomy, University of Colombo';
        $instAddress = $currentInst ? $currentInst->institution_address : 'Kynsey Road, Colombo 08';

        $formData = [];
        if ($type === 'sworn') {
            $record = $this->model->getSwornStatement($activeCase->id);
            $page_heading = 'Sworn Statement Form';
        } else {
            $record = $this->model->getCadaverDataSheet($activeCase->id);
            $page_heading = 'Cadaver Data Sheet Form';
            
            // If cadaver datasheet doesn't have form data yet, fetch the sworn statement to pre-fill common fields
            $swornRecord = $this->model->getSwornStatement($activeCase->id);
            if (empty($record->form_data) && $swornRecord && !empty($swornRecord->form_data)) {
                $formData = json_decode($swornRecord->form_data, true) ?? [];
            }
        }
        
        if ($record && !empty($record->form_data)) {
            $formData = json_decode($record->form_data, true) ?? [];
        }

        // Pre-populate place of death from death declaration if missing
        if (empty($formData['place_of_death']) && $deathDecl) {
            $formData['place_of_death'] = $deathDecl->place_of_death;
        }

        $this->renderPage('custodian/document-form', 'documents', $page_heading, $custodian, [
            'type' => $type,
            'formData' => $formData,
            'instName' => $instName,
            'instAddress' => $instAddress,
            'death_declaration' => $deathDecl,
            'activeCase' => $activeCase,
            'donor' => $this->model->getDonorForCustodian($custodian->id)
        ]);
    }

    public function saveDocumentForm() {
        $custodian = $this->requireCustodian();
        if (!$custodian || $_SERVER['REQUEST_METHOD'] !== 'POST') return;
        $type = $_POST['type'] ?? '';
        $activeCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
        
        if (!$activeCase || !in_array($type, ['sworn', 'datasheet'])) {
             header('Location: ' . ROOT . '/custodian/documents');
             exit;
        }

        $formData = [
            'custodian_name' => trim($_POST['custodian_name'] ?? ''),
            'custodian_nic' => trim($_POST['custodian_nic'] ?? ''),
            'custodian_address' => trim($_POST['custodian_address'] ?? ''),
            'custodian_relationship' => trim($_POST['custodian_relationship'] ?? ''),
            'custodian_phone' => trim($_POST['custodian_phone'] ?? ''),
            'donor_religion' => trim($_POST['donor_religion'] ?? ''),
            'place_of_death' => trim($_POST['place_of_death'] ?? ''),
            'race' => trim($_POST['race'] ?? ''),
            'occupation' => trim($_POST['occupation'] ?? ''),
            'birth_place' => trim($_POST['birth_place'] ?? ''),
            'past_medical_history' => trim($_POST['past_medical_history'] ?? ''),
            'past_surgical_history' => trim($_POST['past_surgical_history'] ?? ''),
            'other_diseases' => trim($_POST['other_diseases'] ?? ''),
            'relations_name' => $_POST['relations_name'] ?? [],
            'relations_rel' => $_POST['relations_rel'] ?? [],
            'relations_nic' => $_POST['relations_nic'] ?? []
        ];

        if ($type === 'sworn') {
            $this->model->saveSwornStatement($activeCase->id, $formData);
        } else {
            $this->model->saveCadaverDataSheet($activeCase->id, $formData);
        }
        
        header('Location: ' . ROOT . '/custodian/document-form?type=' . urlencode($type) . '&saved=1');
        exit;
    }

    public function printDocument() {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $type = $_GET['type'] ?? '';
        if (!in_array($type, ['sworn', 'datasheet'])) {
            header('Location: ' . ROOT . '/custodian/documents');
            exit;
        }

        $activeCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
        if (!$activeCase) {
             header('Location: ' . ROOT . '/custodian/documents');
             exit;
        }
        
        $consent = $this->model->resolveActiveConsent($activeCase->donor_id);
        $track = $consent['donation_type'] ?? 'BODY';
        $currentInst = $this->caseModel->getCurrentInstitution($activeCase->id, $track);
        $instName = $currentInst ? $currentInst->institution_name : 'Department of Anatomy, University of Colombo';
        $instAddress = $currentInst ? $currentInst->institution_address : 'Kynsey Road, Colombo 08';

        $formData = [];
        if ($type === 'sworn') {
            $record = $this->model->getSwornStatement($activeCase->id);
        } else {
            $record = $this->model->getCadaverDataSheet($activeCase->id);
        }
        if ($record && !empty($record->form_data)) {
            $formData = json_decode($record->form_data, true) ?? [];
        }

        $cwd = $custodian->id ?? $custodian->custodian_id ?? 0;
        $donor = $this->model->getDonorForCustodian($cwd);
        $death_declaration = $this->caseModel->getDeathDeclaration($donor->id ?? $donor->donor_id);

        // Render printable view without dashboard layout
        require __DIR__ . '/../views/custodian/print-document.view.php';
        exit;
    }

    public function submitBundle() {
        $custodian = $this->requireCustodian();
        if (!$custodian || $_SERVER['REQUEST_METHOD'] !== 'POST') return;

        $donor = $this->model->getDonorForCustodian($custodian->id);
        $donorId = $donor->id ?? $donor->donor_id ?? $custodian->donor_id;
        $activeCase = $this->caseModel->getCaseByDonor($donorId);

        if ($activeCase) {
            $statusRec = $this->model->query("SELECT id FROM case_institution_status WHERE donation_case_id = :id AND is_current = 1 AND request_status = 'ACCEPTED'", [':id' => $activeCase->id]);
            $statusId = $statusRec[0]->id ?? null;

            if ($statusId) {
                // Capture checklist JSON
                $docsJson = json_encode($_POST['docs'] ?? []);

                // Process uploaded file
                if (isset($_FILES['bundle_file']) && $_FILES['bundle_file']['error'] === 0) {
                    $uploadDir = 'assets/uploads/custodian/docs/' . $activeCase->case_number . '/' . $statusId . '/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0777, true);
                    }
                    $ext = pathinfo($_FILES['bundle_file']['name'], PATHINFO_EXTENSION);
                    $fileName = 'Consolidated_Bundle_' . time() . '.' . $ext;
                    $filePath = $uploadDir . $fileName;
                    move_uploaded_file($_FILES['bundle_file']['tmp_name'], $filePath);

                    $this->model->uploadDocument([
                        'donation_case_id' => $activeCase->id,
                        'case_institution_status_id' => $statusId,
                        'document_type' => 'Consolidated_Bundle',
                        'file_path' => $filePath
                    ]);
                }

                // Update workflow status and save checklist snapshot
                $this->model->submitBundle($activeCase->id, $docsJson);

                $_SESSION['flash_success'] = "Submission securely transmitted. The medical school will now verify the bundle.";
            } else {
                $_SESSION['flash_error'] = "No accepted case found to submit documentation for.";
            }
        }
        header('Location: ' . ROOT . '/custodian/documents');
        exit;
    }
}
