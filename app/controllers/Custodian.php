<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\CustodianModel;
use App\Models\UserModel;
class Custodian
{
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
        if (session_status() === PHP_SESSION_NONE)
            session_start();
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
        if (!$custodian)
            return;

        $donorId = $custodian->donor_id;
        $activeCase = $this->caseModel->getCaseByDonor($donorId);
        $certificates = $activeCase ? $this->model->getDonationCertificates($activeCase->id) : [];
        $appreciationLetters = $activeCase ? $this->model->getAppreciationLetters($activeCase->id) : [];

        // Fetch activity timeline (Limit to top 5 for dashboard)
        $fullTimeline = $activeCase ? $this->caseModel->getTimeline($activeCase->id) : [];
        $timeline = array_slice($fullTimeline, 0, 5);

        // Pre-calculate registered summary if alive
        $registeredSummary = 'NONE';
        if (!$activeCase) {
            $registry = $this->model->getConsentRegistry($donorId);
            $organs = [];
            $hasBody = false;

            foreach ($registry as $item) {
                if (($item->status ?? '') === 'WITHDRAWN')
                    continue;
                if ($item->type === 'BODY_CONSENT') {
                    $hasBody = true;
                } else {
                    $cleanName = str_replace('(After death)', '', $item->item_name);
                    $organs[] = trim($cleanName);
                }
            }

            $summaryParts = [];
            if (!empty($organs)) {
                $uniqueOrgans = array_unique($organs);
                if (count($uniqueOrgans) > 2) {
                    $summaryParts[] = count($uniqueOrgans) . " Organs & Tissues";
                } else {
                    $summaryParts[] = implode(', ', $uniqueOrgans);
                }
            }
            if ($hasBody) {
                $summaryParts[] = "Whole Body";
            }

            if (!empty($summaryParts)) {
                $registeredSummary = implode(' + ', $summaryParts);
            }
        }
            show($activeCase);
        $this->renderPage('custodian/dashboard', 'dashboard', 'Dashboard', $custodian, [
            'certificates' => $certificates,
            'appreciation_letters' => $appreciationLetters,
            'timeline' => $timeline,
            'active_case' => $activeCase,
            'registered_summary' => $registeredSummary
        ]);
    }

    /** GET /custodian/consent-registry */
    public function consentRegistry()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

        $donor = $this->model->getDonorForCustodian($custodian->id);
        $registry = $this->model->getConsentRegistry($donor->id);
        $outcomes = $this->model->getDonationOutcomes($donor->id);

        $this->renderPage('custodian/consent-registry', 'consent-registry', 'Consent Registry', $custodian, [
            'donor' => $donor,
            'consent_registry' => $registry,
            'donation_outcomes' => $outcomes
        ]);
    }


    /** GET /api/custodian/get-registry-details */
    public function getRegistryDetails()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

        $type = $_GET['type'] ?? '';
        $id = $_GET['id'] ?? 0;

        $details = $this->model->getRegistryRecordDetails($type, $id);
        if (!$details)
            return $this->json(['success' => false, 'error' => 'Record not found']);

        return $this->json(['success' => true, 'data' => $details]);
    }

    /** GET /custodian/donor-profile */
    public function donorProfile()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

        $this->renderPage('custodian/donor-profile', 'donor-profile', 'Donor Profile', $custodian);
    }

    /** GET /custodian/co-custodian */
    public function coCustodian()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

        $this->renderPage('custodian/co-custodian', 'co-custodian', 'Co-Custodian', $custodian);
    }

    /** GET /custodian/report-death */
    public function reportDeath()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

        $this->renderPage('custodian/report-death', 'report-death', 'Report Death', $custodian);
    }

    /** GET /custodian/legal-response */
    public function legalResponse()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

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
        if (!$custodian)
            return;

        $activeCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
        $docs = [];
        $hasSworn = false;
        $hasDatasheet = false;

        if ($activeCase) {
            $docs = $this->model->getDocuments($activeCase->id) ?? [];
            $swornRecord = $this->model->getSwornStatement($activeCase->id);
            $hasSworn = ($swornRecord && !empty($swornRecord->form_data));

            $dataSheetRecord = $this->model->getCadaverSheet($activeCase->id);
            $hasDatasheet = ($dataSheetRecord && !empty($dataSheetRecord->form_data));
        }

        // Get Recognition Documents
        $certificates = $activeCase ? $this->model->getDonationCertificates($activeCase->id) : [];
        $appreciationLetters = $activeCase ? $this->model->getAppreciationLetters($activeCase->id) : [];

        $this->renderPage('custodian/documents', 'documents', 'Documents', $custodian, [
            'docs' => $docs,
            'activeCase' => $activeCase,
            'hasSworn' => $hasSworn,
            'hasDatasheet' => $hasDatasheet,
            'certificates' => $certificates,
            'appreciation_letters' => $appreciationLetters
        ]);
    }

    /** GET /custodian/coordination */
    public function coordinationPage()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

        $this->renderPage('custodian/coordination', 'coordination', 'Coordination', $custodian);
    }

    /** GET /custodian/timeline */
    public function timelinePage()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

        $this->renderPage('custodian/timeline', 'timeline', 'Timeline', $custodian);
    }

    /** GET /custodian/certificates */
    public function certificates()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

        $donorId = $custodian->donor_id;
        $activeCase = $this->caseModel->getCaseByDonor($donorId);
        $certificates = $activeCase ? $this->model->getDonationCertificates($activeCase->id) : [];
        $appreciationLetters = $activeCase ? $this->model->getAppreciationLetters($activeCase->id) : [];

        // Determine if an appreciation letter is "Pending" (Accepted but not used yet)
        $isAppreciationPending = false;
        if ($activeCase && empty($appreciationLetters)) {
            // Check if any institution (Medical School) has accepted this case
            $statuses = $this->caseModel->getInstitutionStatuses($activeCase->id);
            foreach ($statuses as $s) {
                if ($s->institution_status === 'ACCEPTED' && $s->track === 'MEDICAL_SCHOOL_BODY') {
                    $isAppreciationPending = true;
                    break;
                }
            }
        }

        $this->renderPage('custodian/certificates', 'certificates', 'Certificates', $custodian, [
            'activeCase' => $activeCase,
            'certificates' => $certificates,
            'appreciation_letters' => $appreciationLetters,
            'is_appreciation_pending' => $isAppreciationPending
        ]);
    }

    public function activityHistory()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian) return;

        $donorId = $custodian->donor_id;
        $activeCase = $this->caseModel->getCaseByDonor($donorId);
        
        // Full activity timeline
        $timeline = $activeCase ? $this->caseModel->getTimeline($activeCase->id) : [];
        // show($timeline);
        // Historical / Archived cases for this donor
        $archived = $this->model->getArchivedCases($donorId);
        
        $this->renderPage('custodian/activity-history', 'activity-history', 'Activity History', $custodian, [
            'timeline' => $timeline,
            'archived' => $archived
        ]);
    }

    public function persistSelection()
    {
        $this->requireCustodian();
        $type = $_POST['type'] ?? 'HOSPITAL';
        $items = $_POST['items'] ?? '';
        $_SESSION["selected_recovery_items_$type"] = ($items !== '') ? explode(',', $items) : [];
        echo json_encode(['success' => true]);
        exit;
    }

    public function institutionRequests()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;
        // show($custodian);
        // $donorId = $custodian->donor_id;
        // show($donorId);
        // $cidRaw = $custodian->id ?? $custodian->cid;

        $availableInstitutions = [];
        $institutionStatuses = [];
        $institutionType = $_GET['type'] ?? null; // Allow explicit override (MEDICAL_SCHOOL | HOSPITAL)

        $activeCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
        if ($activeCase) {
            $track = $activeCase->resolved_operational_track ?? 'NONE';

            // If no explicit type requested, default based on donor's primary track
            if (!$institutionType) {
                $institutionType = (str_contains($track, 'BODY')) ? 'MEDICAL_SCHOOL' : 'HOSPITAL';
            }

            // --- SELECTION PERSISTENCE LOGIC ---
            if (isset($_GET['items'])) {
                // Save current selection to session
                $_SESSION['cp_pending_items'] = $_GET['items'];
            } elseif (!isset($_GET['refreshed'])) {
                // If items missing but session exists, redirect one-time to include them
                // This ensures the URL always matches the true selection state
                if (!empty($_SESSION['cp_pending_items'])) {
                    $redirectUrl = ROOT . "/custodian/institution-requests?type=" . $institutionType . "&items=" . $_SESSION['cp_pending_items'] . "&refreshed=1";
                    header("Location: " . $redirectUrl);
                    exit;
                }
            }

            $availableInstitutions = $this->caseModel->getAvailableInstitutions($activeCase->id, $track, $institutionType) ?: [];
            $institutionStatuses = $this->caseModel->getInstitutionStatuses($activeCase->id) ?: [];
        }

        // Get the specific active request for the current filtered type (Hospital or Medical School)
        $currentInstRequest = null;
        foreach ($institutionStatuses as $is) {
            if ($is->institution_type === $institutionType && in_array($is->institution_status, ['PENDING', 'ACCEPTED', 'SUBMITTED'])) {
                $currentInstRequest = $is;
                break;
            }
        }

        $death_declaration = $this->caseModel->getDeathDeclaration($custodian->donor_id);
        $cidRaw = $custodian->id ?? $custodian->cid;
        $isLeader = ($death_declaration && $death_declaration->declared_by_custodian_id == $cidRaw);

        $this->renderPage('custodian/institution-requests', 'institution-requests', 'Institution Requests', $custodian, [
            'availableInstitutions' => $availableInstitutions,
            'institutionStatuses' => $institutionStatuses,
            'institutionType' => $institutionType,
            'currentInstRequest' => $currentInstRequest,
            'death_declaration' => $death_declaration,
            'leaderInfo' => $death_declaration,
            'isLeader' => $isLeader,
            'activeCase' => $activeCase
        ]);
    }

    public function profile()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'] ?? '',
                'relationship' => $_POST['relationship'] ?? '',
                'phone' => $_POST['phone'] ?? '',
                'email' => $_POST['email'] ?? '',
                'address' => $_POST['address'] ?? '',
                'age' => $_POST['age'] ?? ''
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
        usort($custodians, function ($a, $b) use ($custodian) {
            if ($a->id == $custodian->id)
                return -1;
            if ($b->id == $custodian->id)
                return 1;
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
        if (!$custodian)
            return;

        $this->renderPage('custodian/authority-limits', 'authority-limits', 'Authority Limits', $custodian);
    }

    /** GET /custodian/security-setup */
    public function securitySetup()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

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
        if (!$custodian)
            return;

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
        } elseif (
            !preg_match('/[A-Z]/', $newPassword) ||
            !preg_match('/[a-z]/', $newPassword) ||
            !preg_match('/[0-9]/', $newPassword) ||
            !preg_match('/[^A-Za-z0-9]/', $newPassword)
        ) {
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
        $lastName = $custodian->last_name ?? $custodian->custodian_last_name ?? '';
        $fullName = trim($firstName . ' ' . $lastName) ?: 'Custodian';
        $cidRaw = $custodian->id ?? $custodian->custodian_id ?? 0;

        // Fetch core entities explicitly so all views have them
        $donor = $this->model->getDonorForCustodian($cidRaw);
        $coCustodian = null;

        $deathDecl = null;
        $donationCase = null;
        $activeCase = null;
        $currentRequest = null;
        $currentInstRequest = null;

        if ($donor) {
            $donorId = $donor->id ?? $donor->donor_id;
            $coCustodian = $this->model->getCoCustodian($donorId, $cidRaw);
            $deathDecl = $this->caseModel->getDeathDeclaration($donorId);
            $activeCase = $this->caseModel->getCaseByDonor($donorId);

            if ($activeCase) {
                // Check for system forms
                $hasSworn = ($this->model->getSwornStatement($activeCase->id) !== null);
                $hasDatasheet = ($this->model->getCadaverSheet($activeCase->id) !== null);

                // Get Recognition Documents
                $certificates = $this->model->getDonationCertificates($activeCase->id);
                $appreciationLetters = $this->model->getAppreciationLetters($activeCase->id);

                // Get current institution request (Pending/Accepted/Rejected by Medical School)
                $opTrack = $activeCase->resolved_operational_track ?? 'NONE';
                // Map operational track to CIS track enum (BODY, ORGAN, CORNEA)
                if (str_contains($opTrack, 'BODY')) {
                    $cisTrack = 'BODY';
                } elseif (str_contains($opTrack, 'CORNEA') || $opTrack === 'HOSPITAL_TISSUE') {
                    // For HOSPITAL_TISSUE, try ORGAN first; for cornea-specific, use CORNEA
                    $cisTrack = 'ORGAN';
                } else {
                    $cisTrack = 'ORGAN';
                }

                $currentInstRequest = $this->caseModel->getCurrentInstitution($activeCase->id, $cisTrack);

                // --- ROBUSTNESS FALLBACK ---
                // If the specific track mapping failed (e.g. data corrupt or track mismatch), 
                // look for ANY active request for this case to unlock the Documents page.
                if (!$currentInstRequest) {
                    $allReqs = $this->caseModel->getInstitutionStatuses($activeCase->id) ?: [];
                    foreach ($allReqs as $r) {
                        if (($r->is_current ?? 0) == 1) {
                            $currentInstRequest = $r;
                            break;
                        }
                    }
                }

                $currentRequest = $currentInstRequest;

                // Get ALL institution statuses for dashboard timeline/history
                $allInstitutionStatuses = $this->caseModel->getInstitutionStatuses($activeCase->id) ?: [];

                // --- SUBMITTED DOCUMENTS MAPPING ---
                $submittedDocs = [];
                if ($activeCase->bundle_status === 'SUBMITTED' && $currentInstRequest && !empty($currentInstRequest->submitted_checklist_json)) {
                    $rawChecklist = $currentInstRequest->submitted_checklist_json;
                    $docIds = is_string($rawChecklist) ? (json_decode($rawChecklist, true) ?: []) : (array) $rawChecklist;

                    $mapping = [
                        'sworn' => 'Sworn Statement of Legal Custodian',
                        'datasheet' => 'Cadaver Data Sheet (Clinical Details)',
                        'death_certificate' => 'Official Death Certificate',
                        'nic_copy_donor' => 'NIC Copy of Deceased',
                        'nic_copy_custodian' => 'NIC Copy of Legal Custodian',
                        'medical_summary' => 'Medical Information Summary',
                        'police_report' => 'Police Report / Post-Mortem Authorization',
                        'medico_legal' => 'Medico-Legal Clearance',
                        'hospital_records' => 'Hospital Medical Records (BHT)',
                        'pm_report' => 'Pathology Post-Mortem Report'
                    ];

                    foreach ($docIds as $id) {
                        $submittedDocs[] = $mapping[$id] ?? ucwords(str_replace('_', ' ', $id));
                    }
                }
                $extraData['submittedDocs'] = $submittedDocs;
            }
        }

        $isLeader = false;
        if ($deathDecl) {
            $isLeader = ($deathDecl->declared_by_custodian_id == $cidRaw);
        }

        $window = $this->getClinicalWindowStatus($activeCase, $deathDecl, $cidRaw);
        if ($window) {
            $extraData['clinical_deadline'] = $window['deadline'];
            $extraData['is_expired'] = $window['is_expired'];
            $extraData['seconds_remaining'] = $window['seconds_remaining'];
        }

        $viewData = array_merge([
            'ROOT' => ROOT,
            'page_title' => $pageTitle,
            'active_page' => $activePage,
            'custodian' => $custodian,
            'custodian_name' => $fullName,
            'custodian_id_display' => 'CID-' . str_pad($cidRaw, 5, '0', STR_PAD_LEFT),
            'donor' => $donor,
            'co_custodian' => $coCustodian,

            'death_declaration' => $deathDecl,
            'isLeader' => $isLeader,
            'leaderInfo' => $deathDecl, // Contains declared_by_name, phone, email
            'registered_summary' => $donor ? ($donor->pledge_type ?? 'NONE') : 'NONE',
            'donation_case' => $donationCase,
            'activeCase' => $activeCase,
            'certificates' => $certificates ?? [],
            'appreciation_letters' => $appreciationLetters ?? [],
            'currentInstRequest' => $currentInstRequest,
            'currentRequest' => $currentRequest, // Alias for legacy/shared views
            'allInstitutionStatuses' => $allInstitutionStatuses ?? [],
            'hasSworn' => $hasSworn ?? false,
            'hasDatasheet' => $hasDatasheet ?? false,
            'organQuestions' => [
                ['id' => 'medical_summary', 'title' => 'Medical Information Summary', 'q' => 'Is there a known medical history or a Medical Information Summary available?', 'desc' => 'Summary of past medical history.'],
                ['id' => 'police_report', 'title' => 'Police Report', 'q' => 'Was the death accidental or does it require a Police Report?', 'desc' => 'Required for accidental or legal cases.'],
                ['id' => 'medico_legal', 'title' => 'Medico-Legal Clearance', 'q' => 'Is Medico-Legal Clearance required for this case?', 'desc' => 'Legal permission for organ retrieval.'],
                ['id' => 'hospital_records', 'title' => 'Hospital Medical Records', 'q' => 'Are there detailed Hospital Medical Records available?', 'desc' => 'Complete patient file from the clinical ward.']
            ]
        ], $extraData);

        // --- PROGRESS STEPPER LOGIC ---
        $stepperData = [
            'type' => 'NONE',
            'current' => 0,
            'steps' => []
        ];

        if ($activeCase) {
            $mode = $activeCase->resolved_deceased_mode;
            $opTrack = $activeCase->resolved_operational_track ?? 'NONE';
            $kidneyDecision = $activeCase->kidney_decision ?? 'PENDING';
            $bodyDecision = $activeCase->body_cornea_decision ?? 'PENDING';
            $isBrainDead = ($deathDecl->is_brain_dead ?? 0) == 1;

            // Scenario A: Decision Required (Hybrid / Complex Starts)
            if ($opTrack === 'DECISION_REQUIRED' || $opTrack === 'BODY_CORNEA_DECISION_REQUIRED') {
                $stepperData['type'] = 'DECISION';
                $stepperData['current'] = 1;
                $stepperData['steps'] = [
                    ['l' => 'Path Choice', 'i' => 'fa-shuffle'],
                    ['l' => 'Selection', 'i' => 'fa-hospital'],
                    ['l' => 'Agreement', 'i' => 'fa-file-signature'],
                    ['l' => 'Execution', 'i' => 'fa-heart-pulse'],
                    ['l' => 'Completion', 'i' => 'fa-award']
                ];
            }
            // Scenario B: Kidney Only (Bedside Coordination)
            elseif ($mode === 'KIDNEY_ONLY' && $isBrainDead) {
                $stepperData['type'] = 'KIDNEY';
                $stepperData['current'] = 2; // Always at least 'In progress' if here
                $stepperData['steps'] = [
                    ['l' => 'Report Death', 'i' => 'fa-heart-crack'],
                    ['l' => 'Bedside Prep', 'i' => 'fa-bed-pulse'],
                    ['l' => 'Hospital Visit', 'i' => 'fa-user-doctor'],
                    ['l' => 'Retrieval', 'i' => 'fa-heart'],
                    ['l' => 'Honor', 'i' => 'fa-certificate']
                ];
                if ($activeCase->overall_status === 'SUCCESSFUL' || $activeCase->overall_status === 'COMPLETED')
                    $stepperData['current'] = 4;
                if (!empty($certificates))
                    $stepperData['current'] = 5;
                if (!empty($certificates) && (!empty($appreciation_letters) || ($activeCase->overall_status === 'COMPLETED')))
                    $stepperData['current'] = 6;
            }
            // Scenario C: Organ / Hospital Track
            elseif (str_contains($mode, 'ORGAN') || $opTrack === 'HOSPITAL_TISSUE') {
                $stepperData['type'] = 'ORGAN';
                $stepperData['steps'] = [
                    ['l' => 'Selection', 'i' => 'fa-hospital'],
                    ['l' => 'Documentation', 'i' => 'fa-folder-open'],
                    ['l' => 'Scheduling', 'i' => 'fa-calendar-check'],
                    ['l' => 'Retrieval', 'i' => 'fa-heart-pulse'],
                    ['l' => 'Certification', 'i' => 'fa-award']
                ];
                $step = 1;
                if ($currentInstRequest) {
                    $step = 2; // Selection done, Documentation active

                    // If documents were accepted, move to Scheduling
                    if (($currentInstRequest->document_status ?? '') === 'ACCEPTED') {
                        $step = 3;
                    }

                    // If overall case is successful, move to Retrieval (meaning it just happened) or Certification
                    if ($activeCase->overall_status === 'SUCCESSFUL' || $activeCase->overall_status === 'COMPLETED') {
                        $step = 4;
                    }

                    // If certificate is issued, move to Step 5
                    if (!empty($certificates)) {
                        $step = 5;
                    }

                    // If both documents are issued or case is COMPLETED, mark final step as DONE
                    if (!empty($certificates) && !empty($appreciation_letters)) {
                        $step = 6;
                    }
                }
                $stepperData['current'] = $step;
            }
            // Scenario D: Body (Medical School) Track
            else {
                $stepperData['type'] = 'BODY';
                $stepperData['steps'] = [
                    ['l' => 'Selection', 'i' => 'fa-building-columns'],
                    ['l' => 'Affidavits', 'i' => 'fa-file-signature'],
                    ['l' => 'Bundle', 'i' => 'fa-briefcase'],
                    ['l' => 'Handover', 'i' => 'fa-handshake'],
                    ['l' => 'Completion', 'i' => 'fa-award']
                ];
                $step = 1;
                if ($currentInstRequest) {
                    $step = 2;
                    if ($hasSworn && $hasDatasheet) {
                        $step = 3;
                        if ($activeCase->bundle_status === 'SUBMITTED' || ($currentInstRequest->document_status ?? '') === 'ACCEPTED')
                            $step = 4;
                        if ($activeCase->overall_status === 'SUCCESSFUL' || $activeCase->overall_status === 'COMPLETED')
                            $step = 5;
                        if (($activeCase->overall_status === 'SUCCESSFUL' || $activeCase->overall_status === 'COMPLETED') && !empty($certificates))
                            $step = 6;
                    }
                }
                $stepperData['current'] = $step;
            }
        }
        $viewData['stepperData'] = $stepperData;

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
        if (!$custodian)
            return;

        $donor = $this->model->getDonorForCustodian($custodian->id);
        $coCustodian = $this->model->getCoCustodian($custodian->donor_id, $custodian->id);
        $deathDecl = $this->caseModel->getDeathDeclaration($custodian->donor_id);
        $donationCase = $this->caseModel->getCaseByDonor($custodian->donor_id);

        $dashboard = [
            'custodian' => $custodian,
            'co_custodian' => $coCustodian,
            'donor' => $donor,
            'death_declaration' => $deathDecl,
            'donation_case' => $donationCase,
            'is_deceased' => ($deathDecl !== null),
            'window_remaining' => null,
            'resolved_track' => $donationCase ? $donationCase->resolved_operational_track : null,
            'snapshot_items' => ($donationCase && !empty($donationCase->operational_items_json)) ?
                (is_string($donationCase->operational_items_json) ? json_decode($donationCase->operational_items_json, true) : (array) $donationCase->operational_items_json) : null
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
     * Body: date_of_death, time_of_death, place_of_death, cause_of_death, additional_notes, is_brain_dead
     */
    public function declareDeath()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        try {
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
                'is_brain_dead' => (int) ($_POST['is_brain_dead'] ?? -1),
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
                throw new \Exception('Failed to create death declaration records.');
            }

            // --- RESOLUTION SNAPSHOT ---
            $resolver = new \App\Services\DonationResolver();
            $snapshot = $resolver->resolveAtDeath($custodian->donor_id, $data['is_brain_dead'], $data['date_of_death'] . ' ' . $data['time_of_death']);

            // Create Donation Case with Snapshot
            $caseData = array_merge([
                'donor_id' => $custodian->donor_id,
                'death_declaration_id' => $deathId,
                'donation_type' => $snapshot['resolved_deceased_mode'] ?? 'NONE',
                'resolved_deceased_mode' => $snapshot['resolved_deceased_mode'],
                'resolved_operational_track' => $snapshot['resolved_operational_track'],
                'operational_items_json' => $snapshot['operational_items_json'],
                'operational_time_limits_json' => $snapshot['operational_time_limits_json'],
                'kidney_decision' => $snapshot['kidney_decision'],
                'body_cornea_decision' => $snapshot['body_cornea_decision'],
                'resolved_at' => date('Y-m-d H:i:s')
            ]);

            $caseId = $this->caseModel->createDonationCase($caseData);
            if (!$caseId) {
                throw new \Exception('Failed to initialize the donation case snapshot.');
            }

            $this->json([
                'success' => true,
                'death_declaration_id' => $deathId,
                'donation_case_id' => $caseId,
                'donation_type' => $snapshot['resolved_deceased_mode'] ?? 'BODY',
                'operational_track' => $snapshot['resolved_operational_track'] ?? 'NONE',
                'guidance_message' => $snapshot['guidance_message'] ?? '',
                'show_kidney_popup' => $snapshot['show_kidney_popup'] ?? false,
                'redirect' => ROOT . '/custodian/dashboard'
            ]);

        } catch (\Throwable $e) {
            // Log error here if needed
            $this->json([
                'error' => 'An internal system error occurred during declaration: ' . $e->getMessage()
            ], 500);
        }
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
        if (!$custodian)
            return;

        $activeCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
        $this->json([
            'success' => true,
            'data' => [
                'donation_type' => $activeCase->resolved_deceased_mode ?? 'NONE',
                'track' => $activeCase->resolved_operational_track ?? 'NONE'
            ]
        ]);
    }

    /**
     * GET /custodian/profile
     * Returns: donor profile + next of kin
     */
    public function getProfile()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

        $donor = $this->model->getDonorForCustodian($custodian->id);
        $nextOfKin = $this->model->getNextOfKin($custodian->donor_id);

        $this->json([
            'success' => true,
            'data' => [
                'donor' => $donor,
                'next_of_kin' => $nextOfKin
            ]
        ]);
    }

    /**
     * GET /custodian/custodians
     * Returns: both custodians for this donor
     */
    public function getCustodians()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

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
        if (!$custodian)
            return;

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
        if (!$custodian)
            return;

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
        if (!$custodian)
            return;

        $donationCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
        if (!$donationCase) {
            $this->json(['error' => 'No active donation case'], 404);
            return;
        }

        $track = $_GET['track'] ?? 'BODY';
        $targetType = (str_contains($track, 'BODY') || str_contains($track, 'CORNEA')) ? 'MEDICAL_SCHOOL' : 'HOSPITAL';
        $institutions = $this->caseModel->getAvailableInstitutions($donationCase->id, $track, $targetType);
        $currentInst = $this->caseModel->getCurrentInstitution($donationCase->id, $track);

        $this->json([
            'success' => true,
            'data' => [
                'available' => $institutions,
                'current' => $currentInst
            ]
        ]);
    }

    /**
     * POST /custodian/select-institution
     * Body: institution_id, institution_type, track
     */
    public function selectInstitution()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

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
            $_POST['track'] ?? 'BODY',
            $_POST['selected_items'] ?? null
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
        if (!$custodian)
            return;

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
        if (!$custodian)
            return;

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
        if (!$custodian)
            return;

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
        if (!$custodian)
            return;

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

        $this->model->saveCadaverSheet(
            $donationCase->id,
            $formData,
            $_POST['status'] ?? 'DRAFT'
        );

        $this->json(['success' => true, 'message' => 'Cadaver data sheet saved']);
    }

    /**
     * GET /custodian/cadaver-sheet-data
     * Query: case_institution_status_id
     */
    public function getCadaverSheet()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

        $donationCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
        $sheet = $donationCase ? $this->model->getCadaverSheet($donationCase->id) : null;

        $this->json(['success' => true, 'data' => $sheet]);
    }

    /**
     * GET /custodian/coordination
     * Returns: all institution statuses for the case
     */
    public function getCoordination()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

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
        if (!$custodian)
            return;

        $donationCase = $this->caseModel->getCaseByDonor($custodian->donor_id);
        if (!$donationCase) {
            $this->json(['error' => 'No active donation case'], 404);
            return;
        }

        $timeline = $this->caseModel->getTimeline($donationCase->id);
        $this->json(['success' => true, 'data' => $timeline]);
    }

    // ─── DOCUMENT FORMS & BUNDLE ──────────────────────────────────────────

    public function documentForm()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

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

        $track = $activeCase->resolved_operational_track ?? 'BODY_ONLY';
        $cisTrack = str_contains($track, 'BODY') ? 'BODY' : 'ORGAN';
        $currentInst = $this->caseModel->getCurrentInstitution($activeCase->id, $cisTrack);
        $instName = $currentInst ? $currentInst->institution_name : 'Department of Anatomy, University of Colombo';
        $instAddress = $currentInst ? $currentInst->institution_address : 'Kynsey Road, Colombo 08';

        $formData = [];
        if ($type === 'sworn') {
            $record = $this->model->getSwornStatement($activeCase->id);
            $page_heading = 'Sworn Statement Form';
        } else {
            $record = $this->model->getCadaverSheet($activeCase->id);
            $page_heading = 'Cadaver Data Sheet Form';

            // If cadaver datasheet doesn't have form data yet, fetch the sworn statement to pre-fill common fields
            $swornRecord = $this->model->getSwornStatement($activeCase->id);
            if (empty($record->form_data) && $swornRecord && !empty($swornRecord->form_data)) {
                $rawSworn = $swornRecord->form_data;
                $formData = is_string($rawSworn) ? (json_decode($rawSworn, true) ?? []) : (array) $rawSworn;
            }
        }

        if ($record && !empty($record->form_data)) {
            $rawRec = $record->form_data;
            $formData = is_string($rawRec) ? (json_decode($rawRec, true) ?? []) : (array) $rawRec;
        }

        // --- AUTO-FILL REFINED DEMOGRAPHICS (Locked Donor Fields) ---
        $donor = $this->model->getDonorForCustodian($custodian->id);
        if ($donor) {
            // 1. Race -> From Nationality
            if (empty($formData['race']) && !empty($donor->nationality)) {
                $formData['race'] = $donor->nationality;
            }
            // 2. Religion -> From Body Donation Consent
            if (empty($formData['donor_religion'])) {
                $bodyConsent = $this->model->query("SELECT religion FROM body_donation_consents WHERE donor_id = :did ORDER BY consent_date DESC LIMIT 1", [':did' => $donorId])[0] ?? null;
                if ($bodyConsent && !empty($bodyConsent->religion)) {
                    $formData['donor_religion'] = $bodyConsent->religion;
                }
            }
        }

        // Pre-populate place of death from death declaration (Strictly Locked)
        if ($deathDecl) {
            $formData['place_of_death'] = $deathDecl->place_of_death;
        }

        // SECTION A: Person Handing Over (Cadaver) or Declarant (Sworn)
        if (empty($formData['custodian_name'])) {
             if ($type === 'sworn') {
                 // For Sworn Statement, the registered custodian IS the declarant
                 $formData['custodian_name'] = $custodian->name;
                 $formData['custodian_nic'] = $custodian->nic_number;
                 $formData['custodian_address'] = $custodian->address;
                 $formData['custodian_relationship'] = $custodian->relationship;
                 $formData['custodian_phone'] = $custodian->phone;
             } else {
                 // Section A starts empty and is manually filled for Datasheet
                 $formData['custodian_name'] = '';
                 $formData['custodian_nic'] = '';
                 $formData['custodian_address'] = '';
                 $formData['custodian_relationship'] = '';
                 $formData['custodian_phone'] = '';
             }
        }

        $this->renderPage('custodian/document-form', 'documents', $page_heading, $custodian, [
            'type' => $type,
            'formData' => $formData,
            'instName' => $instName,
            'instAddress' => $instAddress,
            'death_declaration' => $deathDecl,
            'activeCase' => $activeCase,
            'donor' => $donor
        ]);
    }

    public function saveDocumentForm()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian || $_SERVER['REQUEST_METHOD'] !== 'POST')
            return;
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
            $this->model->saveCadaverSheet($activeCase->id, $formData);
        }

        header('Location: ' . ROOT . '/custodian/document-form?type=' . urlencode($type) . '&saved=1');
        exit;
    }

    public function printDocument()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian)
            return;

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

        $track = $activeCase->resolved_operational_track ?? 'BODY_ONLY';
        $cisTrack = str_contains($track, 'BODY') ? 'BODY' : 'ORGAN';
        $currentInst = $this->caseModel->getCurrentInstitution($activeCase->id, $cisTrack);
        $instName = $currentInst ? $currentInst->institution_name : 'Department of Anatomy, University of Colombo';
        $instAddress = $currentInst ? $currentInst->institution_address : 'Kynsey Road, Colombo 08';

        $formData = [];
        if ($type === 'sworn') {
            $record = $this->model->getSwornStatement($activeCase->id);
        } else {
            $record = $this->model->getCadaverSheet($activeCase->id);
        }
        if ($record && !empty($record->form_data)) {
            $rawRec = $record->form_data;
            $formData = is_string($rawRec) ? (json_decode($rawRec, true) ?? []) : (array) $rawRec;
        }

        $cwd = $custodian->id ?? $custodian->custodian_id ?? 0;
        $donor = $this->model->getDonorForCustodian($cwd);
        $death_declaration = $this->caseModel->getDeathDeclaration($donor->id ?? $donor->donor_id);

        // Render printable view without dashboard layout
        require __DIR__ . '/../views/custodian/print-document.view.php';
        exit;
    }

    public function submitBundle()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian || $_SERVER['REQUEST_METHOD'] !== 'POST')
            return;

        $donor = $this->model->getDonorForCustodian($custodian->id);
        $donorId = $donor->id ?? $donor->donor_id ?? $custodian->donor_id;
        $activeCase = $this->caseModel->getCaseByDonor($donorId);

        if ($activeCase) {
            $deathDecl = $this->caseModel->getDeathDeclaration($donorId);
            $cidRaw = $custodian->id ?? $custodian->cid;
            $window = $this->getClinicalWindowStatus($activeCase, $deathDecl, $cidRaw);

            if ($window && $window['is_expired']) {
                $_SESSION['flash_error'] = "The clinical window for this donation has expired. Document submission is no longer permitted.";
                header('Location: ' . ROOT . '/custodian/documents');
                exit;
            }

            $statusRec = $this->model->query("SELECT id FROM case_institution_status WHERE donation_case_id = :id AND is_current = 1 AND institution_status = 'ACCEPTED'", [':id' => $activeCase->id]);
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

    /**
     * AJAX POST /custodian/skip-item
     * JSON: item_id
     */
    public function skipItem()
    {
        $custodian = $this->requireCustodian();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $itemId = (int)($_POST['item_id'] ?? 0);
        $activeCase = $this->caseModel->getActiveCase($this->donor->id);

        if (!$activeCase || !$itemId) {
            $this->json(['error' => 'Invalid request'], 400);
            return;
        }

        $rawItems = $activeCase->operational_items_json;
        $items = is_string($rawItems) ? json_decode($rawItems, true) : (array) $rawItems;
        if (isset($items[$itemId])) {
            $items[$itemId]['status'] = 'skipped';
            $this->caseModel->update($activeCase->id, ['operational_items_json' => json_encode($items)]);
            $this->json(['success' => true]);
        }
    }

    /**
     * AJAX POST /custodian/decide-kidney
     * Body: decision (PROCEED|DECLINED)
     */
    public function decideKidney()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $decision = $_POST['decision'] ?? 'PENDING';
        $activeCase = $this->caseModel->getCaseByDonor($custodian->donor_id);

        if (!$activeCase) {
            $this->json(['error' => 'Case not found'], 404);
            return;
        }

        $this->caseModel->update($activeCase->id, [
            'kidney_decision' => $decision,
            'kidney_decision_at' => date('Y-m-d H:i:s')
        ]);

        // Re-resolve track
        $resolver = new \App\Services\DonationResolver();
        $death_declaration = $this->caseModel->getDeathDeclaration($custodian->donor_id);
        $timeOfDeath = $death_declaration->date_of_death . ' ' . $death_declaration->time_of_death;
        $snapshot = $resolver->resolveAtDeath($custodian->donor_id, $death_declaration->is_brain_dead, $timeOfDeath, $decision, $activeCase->body_cornea_decision);

        $this->caseModel->update($activeCase->id, [
            'resolved_operational_track' => $snapshot['resolved_operational_track'],
            'resolved_deceased_mode' => $snapshot['resolved_deceased_mode']
        ]);

        $this->json(['success' => true, 'redirect' => ROOT . '/custodian/dashboard']);
    }

    /**
     * AJAX POST /custodian/decide-body-cornea
     * Body: choice (BODY_ONLY|CORNEA_ONLY|BOTH)
     */
    public function decideBodyCornea()
    {
        $custodian = $this->requireCustodian();
        if (!$custodian || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->json(['error' => 'Method not allowed'], 405);
            return;
        }

        $choice = $_POST['choice'] ?? 'PENDING';
        $activeCase = $this->caseModel->getCaseByDonor($custodian->donor_id);

        if (!$activeCase) {
            $this->json(['error' => 'Case not found'], 404);
            return;
        }

        $this->caseModel->update($activeCase->id, [
            'body_cornea_decision' => $choice
        ]);

        // Re-resolve track
        $resolver = new \App\Services\DonationResolver();
        $death_declaration = $this->caseModel->getDeathDeclaration($custodian->donor_id);
        $timeOfDeath = $death_declaration->date_of_death . ' ' . $death_declaration->time_of_death;
        $snapshot = $resolver->resolveAtDeath($custodian->donor_id, $death_declaration->is_brain_dead, $timeOfDeath, $activeCase->kidney_decision, $choice);

        $this->caseModel->update($activeCase->id, [
            'resolved_operational_track' => $snapshot['resolved_operational_track'],
            'resolved_deceased_mode' => $snapshot['resolved_deceased_mode']
        ]);

        $this->json(['success' => true, 'redirect' => ROOT . '/custodian/dashboard']);
    }

    private function getClinicalWindowStatus($activeCase, $deathDecl, $cidRaw)
    {
        if (!$activeCase || !$deathDecl)
            return null;

        $resolver = new \App\Services\DonationResolver();
        $timeOfDeath = $deathDecl->date_of_death . ' ' . $deathDecl->time_of_death;

        $donorId = $activeCase->donor_id;
        $isBrainDead = (int) ($deathDecl->is_brain_dead ?? 0);
        $snapshot = $resolver->resolveAtDeath($donorId, $isBrainDead, $timeOfDeath, $activeCase->kidney_decision, $activeCase->body_cornea_decision);

        $activeItems = $snapshot['items'] ?? [];
        $expirations = $snapshot['time_limits'] ?? [];
        $currentDeadline = $deathDecl->window_expires_at; // 48h default

        $track = $activeCase->resolved_operational_track;
        if (str_contains($track, 'HOSPITAL_TISSUE') || str_contains($track, 'ORGAN')) {
            foreach ($activeItems as $id => $item) {
                if (isset($item['type']) && $item['type'] === 'HOSPITAL_TISSUE' && isset($expirations[$id])) {
                    $currentDeadline = $expirations[$id];
                    break;
                }
            }
        }

        $now = time();
        $deadlineTs = strtotime($currentDeadline);
        return [
            'deadline' => $currentDeadline,
            'is_expired' => ($now > $deadlineTs),
            'seconds_remaining' => ($deadlineTs - $now)
        ];
    }
}

