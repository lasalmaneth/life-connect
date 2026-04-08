<?php 

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\FinancialDonationModel;

class Donor {
    use Controller, Database;

    /**
     * Map organ names to specific image assets or FontAwesome icons
     */
    private function getOrganIcon($organName)
    {
        $name = strtolower(trim($organName));
        if (str_contains($name, 'heart')) return '<img src="' . ROOT . '/assets/icons/heart.png" style="width: 28px; height: 28px; object-fit: contain;">';
        if (str_contains($name, 'kidney')) return '<img src="' . ROOT . '/assets/icons/kidneys.png" style="width: 28px; height: 28px; object-fit: contain;">';
        if (str_contains($name, 'lung')) return '<img src="' . ROOT . '/assets/icons/lungs.png" style="width: 28px; height: 28px; object-fit: contain;">';
        if (str_contains($name, 'liver')) return '<img src="' . ROOT . '/assets/icons/liver.png" style="width: 28px; height: 28px; object-fit: contain;">';
        if (str_contains($name, 'marrow') || str_contains($name, 'bone')) return '<img src="' . ROOT . '/assets/icons/bone_marrow.png" style="width: 28px; height: 28px; object-fit: contain;">';
        if (str_contains($name, 'pancreas')) return '<img src="' . ROOT . '/assets/icons/pancreas.png" style="width: 28px; height: 28px; object-fit: contain;">';
        if (str_contains($name, 'intestine')) return '<img src="' . ROOT . '/assets/icons/intestines.png" style="width: 28px; height: 28px; object-fit: contain;">';
        
        // FontAwesome fallbacks for ones without specific images
        if (str_contains($name, 'cornea') || str_contains($name, 'eye')) return '<i class="fas fa-eye" style="color: #3b82f6;"></i>';
        if (str_contains($name, 'blood')) return '<i class="fas fa-tint" style="color: #dc2626;"></i>';
        if (str_contains($name, 'body') || str_contains($name, 'full')) return '<i class="fas fa-user" style="color: #64748b;"></i>';
        if (str_contains($name, 'tissue')) return '<i class="fas fa-layer-group" style="color: #f43f5e;"></i>';
        
        return '<i class="fas fa-hand-holding-heart" style="color: #f43f5e;"></i>';
    }

    /**
     * Get common donor data shared across all pages.
     * Returns: associative array with donor data and roles
     */
    private function getCommonData()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $donorId = $this->getDonorId();
        $donorModel = new \App\Models\DonorModel();
        
        $donorData = $donorModel->getDonorById($donorId);
        $donorData = json_decode(json_encode($donorData), true);

        $donorFullName = htmlspecialchars(($donorData['first_name'] ?? '') . ' ' . ($donorData['last_name'] ?? ''));
        
        // Use 'id' from donors table (d.id in query)
        $rawId = $donorData['id'] ?? $donorData['donor_id'] ?? 0;
        $donorIdDisplay = 'D_' . str_pad($rawId, 5, '0', STR_PAD_LEFT);
        $donorRole = "Registered Donor";

        // Parse active roles
        $activeRoles = [];
        if (!empty($donorData['active_roles'])) {
            $activeRoles = json_decode($donorData['active_roles'], true) ?: [];
        }

        // Determine current portal mode (Organ vs Financial)
        $currentMode = $_SESSION['donor_portal_mode'] ?? null;
        if (!$currentMode && !empty($activeRoles)) {
            $currentMode = 'mode-' . $activeRoles[0] . '-donation';
            $_SESSION['donor_portal_mode'] = $currentMode;
        }

        return [
            'donorId' => $donorId,
            'donorData' => $donorData,
            'donorFullName' => $donorFullName,
            'donorIdDisplay' => $donorIdDisplay,
            'donorRole' => $donorRole,
            'activeRoles' => $activeRoles,
            'currentMode' => $currentMode ?: 'mode-organ-donation'
        ];
    }

    /**
     * Overview Page (default)
     */
    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) redirect('login');
        if ($_SESSION['role'] != 'DONOR') redirect('login');

        $common = $this->getCommonData();
        $donorId = $common['donorId'];
        $donorData = $common['donorData'];
        $donorFullName = $common['donorFullName'];
        $donorIdDisplay = $common['donorIdDisplay'];
        $donorRole = $common['donorRole'];
        $activeRoles = $common['activeRoles'];
        $currentMode = $common['currentMode'];

        // Check if first login (no roles selected yet)
        $isFirstLogin = empty($activeRoles);

        $donorModel = new \App\Models\DonorModel();
        $donorStats = $donorModel->getDonorStats($donorId);
        $pledgedOrgans = $donorModel->getPledgedOrgans($donorId);
        
        // Map icons for overview
        foreach ($pledgedOrgans as &$organ) {
            $organ['organ_icon'] = !empty($organ['organ_icon']) && str_starts_with($organ['organ_icon'], '<i') ? $organ['organ_icon'] : $this->getOrganIcon($organ['organ_name']);
        }
        unset($organ);

        $notifications = $donorModel->getNotifications($donorId);
        
        // NEW: Fetch medical investigations for dashboard
        $investigationModel = new \App\Models\UpcomingAppointmentModel();
        $appointments = $investigationModel->getAppointmentsByDonorId($donorId);
        $upcoming_appointments = array_slice(array_filter($appointments, function($a) {
            return !empty($a->test_date) && strtotime($a->test_date) >= strtotime('today');
        }), 0, 2);

        $test_results = $donorModel->getTestResults($donorId);
        $latest_health = !empty($test_results) ? $test_results[0] : null;

        $districts = $this->getDistricts();

        $this->view('donor/index', [
            'donor_data' => $donorData,
            'donor_full_name' => $donorFullName,
            'donor_id_display' => $donorIdDisplay,
            'donor_role' => $donorRole,
            'donor_stats' => $donorStats,
            'pledged_organs' => $pledgedOrgans,
            'notifications' => $notifications,
            'upcoming_appointments' => $upcoming_appointments,
            'latest_health' => $latest_health,
            'districts' => $districts,
            'active_roles' => $activeRoles,
            'is_first_login' => $isFirstLogin,
            'current_mode' => $currentMode,
            'active_page' => 'overview',
            'page_title' => 'Overview',
            'page_css' => [],
            'ROOT' => ROOT
        ]);
    }

    /**
     * AJAX Action: Set Portal Mode
     */
    public function setPortalMode()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        header('Content-Type: application/json');

        $mode = $_POST['mode'] ?? null;
        if ($mode) {
            $_SESSION['donor_portal_mode'] = $mode;
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
        exit;
    }

    /**
     * AJAX Action: Update User Roles
     */
    public function updateRoles()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $roles = $_POST['roles'] ?? [];
        if (!is_array($roles)) $roles = [$roles];

        $donorId = $this->getDonorId();
        $donorModel = new \App\Models\DonorModel();
        
        if ($donorModel->updateActiveRoles($donorId, $roles)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update roles']);
        }
        exit;
    }

    /**
     * Donations Page
     */
    public function donations()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) redirect('login');

        $common = $this->getCommonData();
        $donorId = $common['donorId'];
        $donorData = $common['donorData'];
        $donorFullName = $common['donorFullName'];
        $donorIdDisplay = $common['donorIdDisplay'];
        $donorRole = $common['donorRole'];
        $activeRoles = $common['activeRoles'];
        $currentMode = $common['currentMode'];

        // Handle POST Actions for organ pledging
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $organModel = new \App\Models\OrganModel();
            $action = $_POST['action'];
            $success = false;
            $message = "";

            if ($action === 'select_organ') {
                // Support multiple organ ids sent as array
                $organIdsInput = $_POST['organ_ids'] ?? (isset($_POST['id']) ? [$_POST['id']] : []);
                $organIdsInput = is_array($organIdsInput) ? array_map('intval', $organIdsInput) : [];

                if (empty($organIdsInput)) {
                    $_SESSION['error_message'] = "No organs selected.";
                    redirect('donor/donations');
                }

                $details = [
                    'hospital_id' => !empty($_POST['hospital_id']) ? $_POST['hospital_id'] : null,
                    'conditions' => $_POST['conditions'] ?? null,
                    'medications' => $_POST['medications'] ?? null,
                    'allergies' => $_POST['allergies'] ?? null
                ];

                $rep1_name = $_POST['rep1_name'] ?? $_POST['cust1_name'] ?? '';
                $rep1_nic = $_POST['rep1_nic'] ?? $_POST['cust1_nic'] ?? '';
                $reps = [];
                if (!empty($rep1_name) && !empty($rep1_nic)) {
                    $reps[] = [
                        'name' => $rep1_name,
                        'nic' => $rep1_nic,
                        'relationship' => $_POST['rep1_rel'] ?? $_POST['cust1_rel'] ?? '',
                        'phone' => $_POST['rep1_phone'] ?? $_POST['cust1_phone'] ?? '',
                        'address' => $_POST['rep1_address'] ?? $_POST['cust1_address'] ?? ''
                    ];
                    
                    $rep2_name = $_POST['rep2_name'] ?? $_POST['cust2_name'] ?? '';
                    $rep2_nic = $_POST['rep2_nic'] ?? $_POST['cust2_nic'] ?? '';
                    if (!empty($rep2_name) && !empty($rep2_nic)) {
                        $reps[] = [
                            'name' => $rep2_name,
                            'nic' => $rep2_nic,
                            'relationship' => $_POST['rep2_rel'] ?? $_POST['cust2_rel'] ?? '',
                            'phone' => $_POST['rep2_phone'] ?? $_POST['cust2_phone'] ?? '',
                            'address' => $_POST['rep2_address'] ?? $_POST['cust2_address'] ?? ''
                        ];
                    }
                }

                $fullyPledged = true;

                foreach ($organIdsInput as $oId) {
                    if ($oId <= 0) continue;
                    
                    if ($organModel->addDonorPledge($donorId, $oId, $details)) {
                        // User Preference: Store as Custodians, not Witnesses for Living Donations
                        if (!empty($reps)) {
                            $custodianModel = new \App\Models\DonorCustodianModel();
                            $custodianModel->addOrganCustodians($donorId, $oId, $reps);
                        }
                    } else {
                        $fullyPledged = false;
                    }
                }

                if ($fullyPledged) {
                    $success = true;
                    $message = "Organ(s) pledged successfully!";
                } else {
                    $message = "Pledge partially or completely failed. Some organs may already be pledged.";
                }

            } elseif ($action === 'unselect_organ') {
                $organId = (int)($_POST['id'] ?? 0);
                if ($organModel->removeDonorPledge($donorId, $organId)) {
                    // Update: clear organ-specific representatives
                    $witnessModel = new \App\Models\WitnessModel();
                    $custModel = new \App\Models\DonorCustodianModel();
                    $witnessModel->deleteWitnessesByOrganPledge($donorId, $organId);
                    $custModel->deleteCustodiansByOrganPledge($donorId, $organId);

                    $success = true;
                    $message = "Pledge withdrawn. All associated legal representative data has been cleared.";
                }
            } elseif ($action === 'submit_body_consent') {
                $bodyDonationModel = new \App\Models\BodyDonationModel();
                $witnessModel = new \App\Models\WitnessModel();
                $custodianModel = new \App\Models\DonorCustodianModel();

                $bodyData = [
                    'witness1_name' => trim($_POST['witness1_name'] ?? ''),
                    'witness1_nic' => trim($_POST['witness1_nic'] ?? ''),
                    'witness1_phone' => trim($_POST['witness1_phone'] ?? ''),
                    'witness1_address' => trim($_POST['witness1_address'] ?? ''),
                    'witness2_name' => trim($_POST['witness2_name'] ?? ''),
                    'witness2_nic' => trim($_POST['witness2_nic'] ?? ''),
                    'witness2_phone' => trim($_POST['witness2_phone'] ?? ''),
                    'witness2_address' => trim($_POST['witness2_address'] ?? ''),
                    'medical_school_id' => !empty($_POST['medical_school_id']) ? (int)$_POST['medical_school_id'] : null
                ];

                if ($bodyDonationModel->createConsent($donorId, $bodyData)) {
                    // 1. Save Custodians to specialized table
                    $custodians = [
                        [
                            'name'         => trim($_POST['cust1_name'] ?? ''),
                            'nic'          => trim($_POST['cust1_nic'] ?? ''),
                            'relationship' => trim($_POST['cust1_rel'] ?? ''),
                            'phone'        => trim($_POST['cust1_phone'] ?? ''),
                            'email'        => trim($_POST['cust1_email'] ?? ''),
                            'address'      => trim($_POST['cust1_address'] ?? '')
                        ],
                        [
                            'name'         => trim($_POST['cust2_name'] ?? ''),
                            'nic'          => trim($_POST['cust2_nic'] ?? ''),
                            'relationship' => trim($_POST['cust2_rel'] ?? ''),
                            'phone'        => trim($_POST['cust2_phone'] ?? ''),
                            'email'        => trim($_POST['cust2_email'] ?? ''),
                            'address'      => trim($_POST['cust2_address'] ?? '')
                        ]
                    ];
                    $custodianModel->addOrganCustodians($donorId, 9, $custodians);

                    // 3. Mark the organ as Pledged
                    $organModel = new \App\Models\OrganModel();
                    $organModel->addDonorPledge($donorId, 9, [
                        'hospital_id' => null,
                        'conditions' => '',
                        'medications' => '',
                        'allergies' => ''
                    ]);

                    $success = true;
                    $message = "Full body donation consent form submitted!";
                } else {
                    $message = "Failed to submit consent form.";
                }
            } elseif ($action === 'submit_after_death_pledge') {
                $organModel = new \App\Models\OrganModel();
                $witnessModel = new \App\Models\WitnessModel();
                $custodianModel = new \App\Models\DonorCustodianModel();

                $organIdsInput = $_POST['organ_ids'] ?? [];
                $organIdsInput = is_array($organIdsInput) ? array_map('intval', $organIdsInput) : [];

                if (empty($organIdsInput)) {
                    $_SESSION['error_message'] = "No organs selected.";
                    redirect('donor/donations');
                    exit;
                }

                $details = [
                    'hospital_id' => !empty($_POST['hospital_id']) ? $_POST['hospital_id'] : null,
                    'conditions' => $_POST['conditions'] ?? null,
                    'medications' => $_POST['medications'] ?? null,
                    'allergies' => $_POST['allergies'] ?? null
                ];

                $custodians = [
                    ['name'=>trim($_POST['c1_name']??''), 'nic'=>trim($_POST['c1_nic']??''), 'relationship'=>trim($_POST['c1_rel']??''), 'phone'=>trim($_POST['c1_phone']??''), 'email'=>trim($_POST['c1_email']??''), 'address'=>trim($_POST['c1_address']??'')],
                    ['name'=>trim($_POST['c2_name']??''), 'nic'=>trim($_POST['c2_nic']??''), 'relationship'=>trim($_POST['c2_rel']??''), 'phone'=>trim($_POST['c2_phone']??''), 'email'=>trim($_POST['c2_email']??''), 'address'=>trim($_POST['c2_address']??'')]
                ];

                $successCount = 0;
                foreach ($organIdsInput as $oId) {
                    if ($oId <= 0) continue;
                    if ($organModel->addDonorPledge($donorId, $oId, $details)) {
                        $custodianModel->addOrganCustodians($donorId, $oId, $custodians);
                        $successCount++;
                    }
                }

                if ($successCount > 0) {
                    $success = true;
                    $message = "$successCount After Death organ(s) pledged successfully!";
                } else {
                    $message = "Recording pledges failed. Please try again.";
                }
            } elseif ($action === 'upload_signed_pledge') {
                $organId = (int)($_POST['id'] ?? 0);
                if ($organId > 0 && isset($_FILES['pledge_pdf']) && $_FILES['pledge_pdf']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['pledge_pdf'];
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    
                    if (strtolower($ext) !== 'pdf') {
                        $message = "Only PDF files are allowed.";
                    } else if ($file['size'] > 5 * 1024 * 1024) {
                        $message = "File size exceeds 5MB limit.";
                    } else {
                        $folder = "uploads/pledges/";
                        if (!file_exists($folder)) mkdir($folder, 0777, true);
                        
                        $filename = "pledge_" . $donorId . "_" . $organId . "_" . time() . ".pdf";
                        $destination = $folder . $filename;
                        
                        if (move_uploaded_file($file['tmp_name'], $destination)) {
                            // Update status to UPLOADED or stay PENDING depending on business rules
                            // But user said "upload to complete the process"
                            $query = "UPDATE donor_pledges SET signed_form_path = :path, status = 'UPLOADED' 
                                     WHERE donor_id = :donor_id AND organ_id = :organ_id";
                            $this->query($query, [
                                ':path' => $destination,
                                ':donor_id' => $donorId,
                                ':organ_id' => $organId
                            ]);
                            $success = true;
                            $message = "Signed document uploaded successfully! Your pledge is now being processed.";
                        } else {
                            $message = "Failed to save the uploaded file.";
                        }
                    }
                } else {
                    $message = "No file uploaded or an error occurred during upload.";
                }
            }

            if ($success) $_SESSION['success_message'] = $message;
            else if ($message) $_SESSION['error_message'] = $message;
            redirect('donor/donations');
        }

        // Load donation-specific data
        $organModel = new \App\Models\OrganModel();
        $donorModel = new \App\Models\DonorModel();
        
        $allOrgans = $organModel->getAllAvailableOrgans();
        $allOrgans = json_decode(json_encode($allOrgans), true);
        $pledgedOrgans = $donorModel->getPledgedOrgans($donorId);
        $pledgedIds = array_column($pledgedOrgans, 'organ_id');

        $availableLiving     = [];
        $availableAfterDeath = [];
        $availableFullBody   = [];
        
        $selectedLiving      = [];
        $selectedAfterDeath  = [];
        $selectedFullBody    = [];

        foreach ($allOrgans as $organ) {
            $icon = $this->getOrganIcon($organ['name']);
            $item = [
                'organ_id'      => $organ['id'],
                'organ_name'    => $organ['name'],
                'organ_icon'    => $icon,
                'description'   => $organ['description'] ?? '',
            ];

            if (in_array($organ['id'], $pledgedIds)) continue;
            
            if (stripos($item['description'], 'living donor') !== false) {
                $availableLiving[] = $item;
            } elseif (stripos($item['description'], 'educational') !== false) {
                $availableFullBody[] = $item;
            } else {
                $availableAfterDeath[] = $item;
            }
        }

        foreach ($pledgedOrgans as $organ) {
            $organ['organ_icon']    = $this->getOrganIcon($organ['organ_name']);
            $organ['description']   = $organ['description'] ?? '';

            if (stripos($organ['description'], 'living donor') !== false) {
                $selectedLiving[] = $organ;
            } elseif (stripos($organ['description'], 'educational') !== false) {
                $selectedFullBody[] = $organ;
            } else {
                $selectedAfterDeath[] = $organ;
            }
        }
        
        $bodyDonationModel = new \App\Models\BodyDonationModel();
        $medicalSchoolModel = new \App\Models\MedicalSchoolModel();
        
        $bodyConsent = $bodyDonationModel->getConsentByDonorId($donorId);
        $medicalSchools = $medicalSchoolModel->getAllApprovedMedicalSchools();

        // Fetch hospital requests grouped by organ_id (for consent hospital selection)
        $organRequests = $this->query(
            "SELECT orq.organ_id, orq.priority_level, h.id AS hospital_id, h.name AS hospital_name, 
                    h.address, h.district, h.facility_type
             FROM organ_requests orq
             JOIN hospitals h ON orq.hospital_id = h.id
             WHERE orq.status = 'OPEN' AND h.verification_status = 'APPROVED'
             ORDER BY orq.organ_id, orq.priority_level DESC"
        );
        $hospitalsByOrgan = [];
        if ($organRequests) {
            foreach ($organRequests as $row) {
                $hospitalsByOrgan[$row->organ_id][] = [
                    'hospital_id'   => $row->hospital_id,
                    'hospital_name' => $row->hospital_name,
                    'address'       => $row->address,
                    'district'      => $row->district,
                    'facility_type' => $row->facility_type,
                    'priority'      => $row->priority_level,
                ];
            }
        }

        $districts = $this->getDistricts();

        $this->view('donor/donations', [
            'donor_data'          => $donorData,
            'donor_full_name'     => $donorFullName,
            'donor_id_display'    => $donorIdDisplay,
            'donor_role'          => $donorRole,
            'available_living'    => $availableLiving,
            'available_after_death'=> $availableAfterDeath,
            'available_full_body' => $availableFullBody,
            'selected_living'     => $selectedLiving,
            'selected_after_death'=> $selectedAfterDeath,
            'selected_full_body'  => $selectedFullBody,
            'hospitals_by_organ'  => $hospitalsByOrgan,
            'medical_schools'     => $medicalSchools,
            'body_consent'        => $bodyConsent,
            'districts'           => $districts,
            'active_roles'        => $activeRoles,
            'current_mode'        => $currentMode,
            'active_page'         => 'donations',
            'page_title'          => 'My Donations',
            'page_css'            => ['organ.css'],
            'ROOT'                => ROOT
        ]);
    }


    /**
     * Upcoming Appointments Page
     */
    public function appointments()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) redirect('login');

        $common = $this->getCommonData();
        $donorId = $common['donorId'];
        $donorData = $common['donorData'];
        $donorFullName = $common['donorFullName'];
        $donorIdDisplay = $common['donorIdDisplay'];
        $donorRole = $common['donorRole'];
        $activeRoles = $common['activeRoles'];
        $currentMode = $common['currentMode'];

        $appointmentModel = new \App\Models\UpcomingAppointmentModel();

        // All appointments (history + upcoming) for the list/calendar
        $all_appointments  = $appointmentModel->getAppointmentsByDonorId($donorId) ?: [];

        // Only future-dated appointments for the "Upcoming" panel
        $upcoming_appointments = $appointmentModel->getUpcomingByDonorId($donorId);

        $this->view('donor/appointments', [
            'donor_data'            => $donorData,
            'donor_full_name'       => $donorFullName,
            'donor_id_display'      => $donorIdDisplay,
            'donor_role'            => $donorRole,
            'active_roles'          => $activeRoles,
            'current_mode'          => $currentMode,
            'all_appointments'      => $all_appointments,
            'upcoming_appointments' => $upcoming_appointments,
            'active_page'           => 'appointments',
            'page_title'            => 'Upcoming Appointments',
            'page_css'              => [],
            'ROOT'                  => ROOT
        ]);
    }

    /**
     * AJAX: Approve or Reject an appointment
     * POST /donor/appointment-action
     */
    public function appointmentAction()
    {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $donorId = $this->getDonorId();
        $id      = (int)($_POST['id'] ?? 0);
        $action  = $_POST['action'] ?? '';
        $reason  = trim($_POST['reason'] ?? '');

        if (!$id || !in_array($action, ['approve', 'reject'])) {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            exit;
        }

        $model = new \App\Models\UpcomingAppointmentModel();
        $apt   = $model->getAppointmentById($id);

        // Verify ownership and that it is still Pending
        if (!$apt || $apt->donor_id != $donorId) {
            echo json_encode(['success' => false, 'message' => 'Appointment not found']);
            exit;
        }
        if ($apt->status !== 'Pending') {
            echo json_encode(['success' => false, 'message' => 'This appointment has already been ' . $apt->status]);
            exit;
        }

        if ($action === 'approve') {
            $model->approveAppointment($id);
            echo json_encode(['success' => true, 'message' => 'Appointment approved successfully']);
        } else {
            if (empty($reason)) {
                echo json_encode(['success' => false, 'message' => 'Rejection reason is required']);
                exit;
            }
            $model->rejectAppointment($id, $reason);
            echo json_encode(['success' => true, 'message' => 'Appointment rejected']);
        }
        exit;
    }

    /**
     * Test Results Page
     */
    public function testResults()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) redirect('login');

        $common = $this->getCommonData();
        $donorId = $common['donorId'];
        $donorData = $common['donorData'];
        $donorFullName = $common['donorFullName'];
        $donorIdDisplay = $common['donorIdDisplay'];
        $donorRole = $common['donorRole'];
        $activeRoles = $common['activeRoles'];
        $currentMode = $common['currentMode'];

        $donorModel = new \App\Models\DonorModel();
        
        // Fetch dynamic test results
        $test_results = $donorModel->getTestResults($donorId);
        

        $this->view('donor/test-results', [
            'donor_data' => $donorData,
            'donor_full_name' => $donorFullName,
            'donor_id_display' => $donorIdDisplay,
            'donor_role' => $donorRole,
            'active_roles' => $activeRoles,
            'current_mode' => $currentMode,
            'test_results' => $test_results,
            'active_page' => 'test-results',
            'page_title' => 'Test Results',
            'page_css' => ['testresult.css'],
            'ROOT' => ROOT
        ]);
    }

    /**
     * Family & Witnesses Page (Custodians + Witnesses)
     */
    public function familyCustodians()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) redirect('login');

        $common = $this->getCommonData();
        $donorId = $common['donorId'];
        $donorData = $common['donorData'];
        $donorFullName = $common['donorFullName'];
        $donorIdDisplay = $common['donorIdDisplay'];
        $donorRole = $common['donorRole'];
        $activeRoles = $common['activeRoles'];
        $currentMode = $common['currentMode'];

        // Handle POST Actions
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $witnessModel = new \App\Models\WitnessModel();
            $custodianModel = new \App\Models\DonorCustodianModel();
            $action = $_POST['action'];
            $success = false;
            $message = "";

            switch ($action) {
                case 'add_witness':
                case 'edit_witness':
                    $data = ['name' => trim($_POST['name'] ?? ''), 'nic' => trim($_POST['nic'] ?? ''), 'phone' => trim($_POST['contact'] ?? ''), 'address' => trim($_POST['address'] ?? '')];
                    if ($action === 'add_witness') {
                        if ($witnessModel->addWitness($donorId, $data)) { $success = true; $message = "Witness added successfully!"; }
                    } else {
                        $witnessId = (int)($_POST['witness_id'] ?? 0);
                        if ($witnessModel->updateWitness($witnessId, $donorId, $data)) { $success = true; $message = "Witness updated successfully!"; }
                    }
                    break;

                case 'remove_witness':
                    $witnessId = (int)($_POST['witness_id'] ?? 0);
                    if ($witnessModel->countWitnessesByDonorId($donorId) > 2) {
                        if ($witnessModel->deleteWitness($witnessId, $donorId)) { $success = true; $message = "Witness removed successfully."; }
                    } else $message = "Minimum 2 witnesses required.";
                    break;

                case 'add_custodian':
                    $data = [
                        'name' => trim($_POST['name'] ?? ''),
                        'relationship' => trim($_POST['relationship'] ?? ''),
                        'nic' => trim($_POST['nic'] ?? ''),
                        'phone' => trim($_POST['contact'] ?? ''),
                        'email' => trim($_POST['email'] ?? ''),
                        'address' => trim($_POST['address'] ?? '')
                    ];
                    $result = $custodianModel->addCustodian($donorId, $data);
                    if ($result) {
                        $success = true;
                        $message = "Custodian added successfully! A login account has been created (NIC as username & default password).";
                    } else {
                        $message = "Cannot add custodian. A user with this NIC might already exist or there was a system error.";
                    }
                    break;

                case 'edit_custodian':
                    $custodianId = (int)($_POST['custodian_id'] ?? 0);
                    $data = [
                        'name' => trim($_POST['name'] ?? ''),
                        'relationship' => trim($_POST['relationship'] ?? ''),
                        'nic' => trim($_POST['nic'] ?? ''),
                        'phone' => trim($_POST['contact'] ?? ''),
                        'email' => trim($_POST['email'] ?? ''),
                        'address' => trim($_POST['address'] ?? '')
                    ];
                    if ($custodianModel->updateCustodian($custodianId, $donorId, $data)) {
                        $success = true;
                        $message = "Custodian updated successfully!";
                    }
                    break;

                case 'remove_custodian':
                    $custodianId = (int)($_POST['custodian_id'] ?? 0);
                    if ($custodianModel->deleteCustodian($custodianId, $donorId)) {
                        $success = true;
                        $message = "Custodian removed successfully.";
                    }
                    break;
            }

            if ($success) $_SESSION['success_message'] = $message;
            else if ($message) $_SESSION['error_message'] = $message;
            redirect('donor/family-custodians');
        }

        $witnessModel = new \App\Models\WitnessModel();
        $custodianModel = new \App\Models\DonorCustodianModel();
        
        $witnesses = $witnessModel->getWitnessesByDonorId($donorId);
        $custodians = $custodianModel->getCustodiansByDonorId($donorId);
        $custodianCount = count($custodians);
        
        $districts = $this->getDistricts();

        $this->view('donor/family', [
            'donor_data' => $donorData,
            'donor_full_name' => $donorFullName,
            'donor_id_display' => $donorIdDisplay,
            'donor_role' => $donorRole,
            'active_roles' => $activeRoles,
            'current_mode' => $currentMode,
            'witnesses' => $witnesses,
            'witness_count' => count($witnesses),
            'custodians' => $custodians,
            'custodian_count' => $custodianCount,
            'districts' => $districts,
            'active_page' => 'family',
            'page_title' => 'Family & Custodians',
            'page_css' => ['family-custodians.css'],
            'ROOT' => ROOT
        ]);
    }

    /**
     * Approved Labs Page
     */
    public function approvedLabs()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) redirect('login');

        $common = $this->getCommonData();
        $donorId = $common['donorId'];
        $donorData = $common['donorData'];
        $donorFullName = $common['donorFullName'];
        $donorIdDisplay = $common['donorIdDisplay'];
        $donorRole = $common['donorRole'];
        $activeRoles = $common['activeRoles'];
        $currentMode = $common['currentMode'];

        $hospitalModel = new \App\Models\HospitalModel();
        $allLabs = $hospitalModel->getAllHospitals();
        $allLabs = json_decode(json_encode($allLabs), true);
        
        $allDistrictsSet = [];
        foreach ($allLabs as $lab) {
            if (!empty($lab['district'])) $allDistrictsSet[$lab['district']] = true;
        }
        
        $selectedDistrict = isset($_GET['district']) ? trim($_GET['district']) : 'All';
        $filteredLabs = ($selectedDistrict === 'All') ? $allLabs : array_filter($allLabs, fn($l) => strcasecmp($l['district'] ?? '', $selectedDistrict) === 0);
        $districts = $this->getDistricts();

        $this->view('donor/approved-labs', [
            'donor_data' => $donorData,
            'donor_full_name' => $donorFullName,
            'donor_id_display' => $donorIdDisplay,
            'donor_role' => $donorRole,
            'hospitals' => $allLabs,
            'filteredLabs' => $filteredLabs,
            'allDistrictsSet' => array_keys($allDistrictsSet),
            'selectedDistrict' => $selectedDistrict,
            'districts' => $districts,
            'active_page' => 'labs',
            'page_title' => 'Approved Labs',
            'page_css' => ['approvedlabs.css'],
            'ROOT' => ROOT
        ]);
    }

    /**
     * Documents Page
     */
    public function consentHistory()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) redirect('login');

        $common = $this->getCommonData();
        $donorId = $common['donorId'];
        $donorData = $common['donorData'];
        $donorFullName = $common['donorFullName'];
        $donorIdDisplay = $common['donorIdDisplay'];
        $donorRole = $common['donorRole'];
        $activeRoles = $common['activeRoles'];
        $currentMode = $common['currentMode'];

        $organModel = new \App\Models\OrganModel();
        $bodyModel = new \App\Models\BodyDonationModel();
        
        // Fetch organ pledges history
        $organ_history = $organModel->getPledgeHistory($donorId);

        // Fetch body donation history
        $body_history = $bodyModel->getConsentHistoryByDonorId($donorId);

        // Combine and format
        $combined = [];
        if ($organ_history) {
            foreach ($organ_history as $o) {
                $combined[] = [
                    'date'    => $o->pledge_date,
                    'type'    => 'ORGAN',
                    'name'    => $o->organ_name,
                    'details' => $o->status === 'WITHDRAWN' ? 'Pledge Withdrawn' : 'Organ Pledged',
                    'status'  => $o->status,
                    'signed_form_path' => $o->signed_form_path ?? null
                ];
            }
        }
        if ($body_history) {
            foreach ($body_history as $b) {
                $combined[] = [
                    'date'    => $b->consent_date,
                    'type'    => 'BODY',
                    'name'    => 'Full Body Donation',
                    'details' => $b->status === 'WITHDRAWN' ? 'Consent Withdrawn' : 'Consent Given',
                    'status'  => $b->status,
                    'signed_form_path' => null
                ];
            }
        }

        // Sort by date DESC
        usort($combined, function($a, $b) {
            return strtotime($b['date'] ?? '') - strtotime($a['date'] ?? '');
        });

        $this->view('donor/consent-history', [
            'donor_data'      => $donorData,
            'donor_full_name' => $donorFullName,
            'donor_id_display'=> $donorIdDisplay,
            'donor_role'      => $donorRole,
            'active_roles'    => $activeRoles,
            'current_mode'    => $currentMode,
            'consent_history' => $combined,
            'active_page'     => 'consent-history',
            'page_title'      => 'Consent History',
            'ROOT'            => ROOT
        ]);
    }

    public function documents()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) redirect('login');

        $common = $this->getCommonData();
        $donorId = $common['donorId'];
        $donorData = $common['donorData'];
        $donorFullName = $common['donorFullName'];
        $donorIdDisplay = $common['donorIdDisplay'];
        $donorRole = $common['donorRole'];
        $activeRoles = $common['activeRoles'];
        $currentMode = $common['currentMode'];

        $bodyDonationModel = new \App\Models\BodyDonationModel();

        // Handle POST Actions for body consent
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'submit_body_consent') {
            $data = [
                'witness1_name' => trim($_POST['witness1_name'] ?? ''),
                'witness1_nic' => trim($_POST['witness1_nic'] ?? ''),
                'witness1_phone' => trim($_POST['witness1_phone'] ?? ''),
                'witness1_address' => trim($_POST['witness1_address'] ?? ''),
                'witness2_name' => trim($_POST['witness2_name'] ?? ''),
                'witness2_nic' => trim($_POST['witness2_nic'] ?? ''),
                'witness2_phone' => trim($_POST['witness2_phone'] ?? ''),
                'witness2_address' => trim($_POST['witness2_address'] ?? ''),
                'medical_school_id' => !empty($_POST['medical_school_id']) ? (int)$_POST['medical_school_id'] : null
            ];
            if ($bodyDonationModel->createConsent($donorId, $data)) {
                $_SESSION['success_message'] = "Full body donation consent form submitted!";
            } else {
                $_SESSION['error_message'] = "Failed to submit consent form.";
            }
            redirect('donor/documents');
        }

        $medicalSchoolModel = new \App\Models\MedicalSchoolModel();
        $donorModel = new \App\Models\DonorModel();
        $organModel = new \App\Models\OrganModel();
        
        $bodyConsent = $bodyDonationModel->getConsentByDonorId($donorId);
        $medicalSchools = $medicalSchoolModel->getAllApprovedMedicalSchools();
        $bodyUsageStatus = $donorModel->getBodyUsageStatus($donorId);
        $uploadedPledges = $organModel->getUploadedPledges($donorId);
        $districts = $this->getDistricts();

        $this->view('donor/documents', [
            'donor_data' => $donorData,
            'donor_full_name' => $donorFullName,
            'donor_id_display' => $donorIdDisplay,
            'donor_role' => $donorRole,
            'active_roles' => $activeRoles,
            'current_mode' => $currentMode,
            'body_consent' => $bodyConsent,
            'medical_schools' => $medicalSchools,
            'body_usage_status' => $bodyUsageStatus,
            'uploaded_pledges' => $uploadedPledges,
            'districts' => $districts,
            'active_page' => 'documents',
            'page_title' => 'Documents',
            'page_css' => ['document.css'],
            'ROOT' => ROOT
        ]);
    }

    public function downloadPdf()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) redirect('login');

        $donorId = $this->getDonorId();
        $type = $_GET['type'] ?? '';
        
        if ($type === 'body_donation_consent') {
            $this->downloadConsentPDF($donorId);
        } else if ($type === 'donor_card') {
            $this->viewDigitalCard();
        } else if ($type === 'lab_report') {
            // Lab report download logic if implemented
            $this->redirect('donor/test-results');
        }
        exit;
    }

    /**
     * Aftercare Support Page
     */
    public function aftercare()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) redirect('login');

        $common = $this->getCommonData();
        $donorId = $common['donorId'];
        $donorData = $common['donorData'];
        $donorFullName = $common['donorFullName'];
        $donorIdDisplay = $common['donorIdDisplay'];
        $donorRole = $common['donorRole'];
        $activeRoles = $common['activeRoles'];
        $currentMode = $common['currentMode'];

        // Fetch Aftercare Appointments for this donor
        $nic = $donorData['nic_number'] ?? '';
        $appointments = [];
        if (!empty($nic)) {
            $stmt = $this->query("SELECT * FROM aftercare_appointments WHERE patient_id = :nic ORDER BY appointment_date ASC", [':nic' => $nic]);
            if ($stmt) {
                $appointments = $stmt;
            }
        }

        // Fetch Support Requests for this donor
        $supportRequests = [];
        if (!empty($nic)) {
            $stmt = $this->query("SELECT * FROM support_requests WHERE patient_nic = :patient_nic ORDER BY created_at DESC", [':patient_nic' => $nic]);
            if ($stmt) {
                $supportRequests = $stmt;
            }
        }

        $this->view('donor/aftercare', [
            'donor_data' => $donorData,
            'donor_full_name' => $donorFullName,
            'donor_id_display' => $donorIdDisplay,
            'donor_role' => $donorRole,
            'active_roles' => $activeRoles,
            'current_mode' => $currentMode,
            'appointments' => $appointments,
            'support_requests' => $supportRequests,
            'active_page' => 'aftercare',
            'page_title' => 'Aftercare Support',
            'page_css' => ['../aftercare/aftercare.css'],
            'ROOT' => ROOT
        ]);
    }

    /**
     * Get the list of Sri Lankan districts.
     */
    private function getDistricts()
    {
        return ["Ampara", "Anuradhapura", "Badulla", "Batticaloa", "Colombo", "Galle", "Gampaha", "Hambantota", "Jaffna", "Kalutara", "Kandy", "Kegalle", "Kilinochchi", "Kurunegala", "Mannar", "Matale", "Matara", "Moneragala", "Mullaitivu", "Nuwara Eliya", "Polonnaruwa", "Puttalam", "Ratnapura", "Trincomalee", "Vavuniya"];
    }


    private function getDonorId()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) redirect('login');

        if (isset($_SESSION['donor_id'])) {
            $donorId = $_SESSION['donor_id'];
            $donorModel = new \App\Models\DonorModel();
            $donor = $donorModel->getDonorById($donorId);
            if ($donor && $donor->user_id == $_SESSION['user_id']) return $donorId;
            unset($_SESSION['donor_id']);
        }

        $donorModel = new \App\Models\DonorModel();
        $donor = $donorModel->getDonorByUserId($_SESSION['user_id']);
        if ($donor) {
            $id = $donor->id ?? $donor->donor_id;
            $_SESSION['donor_id'] = $id;
            return $id;
        }
        redirect('register/donor');
    }

    private function downloadConsentPDF($donorId)
    {
        require_once '../app/Libraries/fpdf.php';
        $donorModel = new \App\Models\DonorModel();
        $bodyDonationModel = new \App\Models\BodyDonationModel();
        $donor = $donorModel->getDonorById($donorId);
        $consent = $bodyDonationModel->getConsentByDonorId($donorId);
        if (!$donor || !$consent) die("Consent data not found.");

        $schoolName = "an approved medical school";
        if (!empty($consent->medical_school_id)) {
            $medicalSchoolModel = new \App\Models\MedicalSchoolModel();
            $schoolRes = $medicalSchoolModel->query("SELECT school_name FROM medical_schools WHERE id = :id", [':id' => $consent->medical_school_id]);
            if ($schoolRes) $schoolName = $schoolRes[0]->school_name;
        }

        $pdf = new \FPDF();
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'FULL BODY DONATION CONSENT FORM', 0, 1, 'C');
        $pdf->SetFont('Arial', '', 12);
        $pdf->Cell(0, 10, 'LifeConnect Sri Lanka', 0, 1, 'C');
        $pdf->Line(10, 30, 200, 30);
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(0, 10, 'I. DONOR DECLARATION', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 11);
        $text = "I, " . strtoupper($donor->first_name . ' ' . $donor->last_name) . " (NIC: " . $donor->nic_number . "), resident of " . $donor->address . ", hereby voluntarily donate my body after death to " . strtoupper($schoolName) . " for the purpose of medical education and research.";
        $pdf->MultiCell(0, 7, $text);
        $pdf->Ln(5);
        $pdf->Cell(50, 8, 'Full Name:', 0, 0); $pdf->Cell(0, 8, $donor->first_name . ' ' . $donor->last_name, 0, 1);
        $pdf->Cell(50, 8, 'NIC Number:', 0, 0); $pdf->Cell(0, 8, $donor->nic_number, 0, 1);
        $pdf->Cell(50, 8, 'Date of Birth:', 0, 0); $pdf->Cell(0, 8, $donor->date_of_birth, 0, 1);
        $pdf->Cell(50, 8, 'Address:', 0, 0); $pdf->Cell(0, 8, $donor->address, 0, 1);
        $pdf->Ln(5);
        $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(0, 10, 'II. WITNESSES', 0, 1, 'L');
        $pdf->SetFont('Arial', '', 11); $pdf->MultiCell(0, 7, "We confirm that the donor signed in our presence.");
        $pdf->Ln(3);
        $pdf->SetFont('Arial', 'B', 11); $pdf->Cell(0, 8, 'Witness 1: ' . $consent->witness1_name . ' (NIC: ' . $consent->witness1_nic . ')', 0, 1);
        $pdf->Cell(0, 8, 'Witness 2: ' . $consent->witness2_name . ' (NIC: ' . $consent->witness2_nic . ')', 0, 1);
        $pdf->Ln(10);
        $pdf->SetFont('Arial', 'B', 12); $pdf->Cell(0, 10, 'III. SIGNATURES', 0, 1, 'L');
        $pdf->Ln(5);
        $pdf->Cell(60, 5, '...............................', 0, 0, 'C'); $pdf->Cell(60, 5, '...............................', 0, 0, 'C'); $pdf->Cell(60, 5, '...............................', 0, 1, 'C');
        $pdf->Cell(60, 5, 'Donor', 0, 0, 'C'); $pdf->Cell(60, 5, 'Witness 1', 0, 0, 'C'); $pdf->Cell(60, 5, 'Witness 2', 0, 1, 'C');
        $pdf->Output('I', 'Body_Donation_Consent.pdf');
    }
    /**
     * Redirect old financial-donor routes to unified donor portal
     */
    public function financialRedirect()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) redirect('login');
        redirect('donor/financial-history');
    }

    /**
     * Financial Donation History page (inside donor portal)
     */
    public function financialHistory()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) redirect('login');
        if ($_SESSION['role'] !== 'DONOR') redirect('login');

        $common = $this->getCommonData();
        $donorId = $common['donorId'];
        $donorData = $common['donorData'];
        $donorFullName = $common['donorFullName'];
        $donorIdDisplay = $common['donorIdDisplay'];
        $donorRole = $common['donorRole'];
        $activeRoles = $common['activeRoles'];
        $currentMode = $common['currentMode'];

        $userId        = $_SESSION['user_id'];
        $donationModel = new FinancialDonationModel();
        $history       = $donationModel->getDonationsByUserId($userId);

        $totalDonated = 0;
        if ($history) {
            foreach ($history as $d) {
                $totalDonated += (float)($d->amount ?? 0);
            }
        }

        $this->view('donor/financial/history', [
            'donor_data'        => $donorData,
            'donor_full_name'   => $donorFullName,
            'donor_id_display'  => $donorIdDisplay,
            'donor_role'        => $donorRole,
            'active_roles'      => $activeRoles,
            'current_mode'      => $currentMode,
            'history'           => $history ?: [],
            'total_donated'     => $totalDonated,
            'active_page'       => 'financial-history',
            'page_title'        => 'Financial Donation History',
            'page_css'          => [],
            'ROOT'              => ROOT,
        ]);
    }

    /**
     * Make a Financial Donation page (inside donor portal)
     */
    public function financialDonate()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) redirect('login');
        if ($_SESSION['role'] !== 'DONOR') redirect('login');

        $common = $this->getCommonData();
        $donorId = $common['donorId'];
        $donorData = $common['donorData'];
        $donorFullName = $common['donorFullName'];
        $donorIdDisplay = $common['donorIdDisplay'];
        $donorRole = $common['donorRole'];
        $activeRoles = $common['activeRoles'];
        $currentMode = $common['currentMode'];

        $userId           = $_SESSION['user_id'];
        $donationModel    = new FinancialDonationModel();
        $donation_history = $donationModel->getDonationsByUserId($userId);

        $totalPrevious = 0;
        if ($donation_history) {
            foreach ($donation_history as $d) {
                $totalPrevious += (float)($d->amount ?? 0);
            }
        }

        $this->view('donor/financial/donate', [
            'donor_data'             => $donorData,
            'donor_full_name'        => $donorFullName,
            'donor_id_display'       => $donorIdDisplay,
            'donor_role'             => $donorRole,
            'active_roles'           => $activeRoles,
            'current_mode'           => $currentMode,
            'donation_history'       => $donation_history ?: [],
            'total_previous_donated' => $totalPrevious,
            'active_page'            => 'financial-donate',
            'page_title'             => 'Make a Donation',
            'page_css'               => [],
            'ROOT'                   => ROOT,
        ]);
    }

    /**
     * Process Financial Donation (AJAX POST) — uses users.id directly
     */
    public function processFinancialDonation()
    {
        header('Content-Type: application/json');
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') throw new \Exception('Invalid request method');

            if (session_status() === PHP_SESSION_NONE) session_start();
            if (!isset($_SESSION['user_id'])) throw new \Exception('Unauthorized');

            $userId = $_SESSION['user_id'];
            $amount = (float)($_POST['amount'] ?? 0);
            if ($amount < 100) throw new \Exception('Minimum donation is Rs. 100');

            $donationModel = new FinancialDonationModel();
            $success = $donationModel->createDonation([
                'user_id' => $userId,
                'amount'  => $amount,
                'note'    => $_POST['message'] ?? '',
                'status'  => 'SUCCESS',
            ]);

            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                throw new \Exception('Failed to save donation record');
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * View Donor Card as printable HTML
     */
    public function viewDigitalCard()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) redirect('login');

        $common = $this->getCommonData();
        $donorId = $common['donorId'];
        $donorData = $common['donorData'];
        $donorFullName = $common['donorFullName'];
        $donorIdDisplay = $common['donorIdDisplay'];
        $donorRole = $common['donorRole'];
        $activeRoles = $common['activeRoles'];
        $currentMode = $common['currentMode'];

        $this->view('donor/digital_card', [
            'donor_data' => $donorData,
            'donor_full_name' => $donorFullName,
            'donor_id_display' => $donorIdDisplay,
            'donor_role' => $donorRole,
            'active_roles' => $activeRoles,
            'current_mode' => $currentMode,
            'ROOT' => ROOT
        ]);
        exit;
    }

}
