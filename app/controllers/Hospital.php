<?php 

namespace App\Controllers;

use App\Core\Controller;
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
                'phone' => $hospital->phone ?? 'Not specified',
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
        $recipients = $hospitalModel->getRecipients($hospital_registration) ?: [];
        $success_stories = $hospitalModel->getSuccessStories($hospital_registration) ?: [];
        $aftercare_appointments = $hospitalModel->getAftercareAppointments($hospital_registration) ?: [];
        $lab_reports = $hospitalModel->getLabReports($hospital_registration) ?: [];

        // Calculate Stats
        $stats = [
            'total_organ_requests' => count($organ_requests),
            'pending_requests' => count(array_filter($organ_requests, function($req) { return $req->status === 'Pending'; })),
            'approved_requests' => count(array_filter($organ_requests, function($req) { return $req->status === 'Approved'; })),
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
            'recipients' => $recipients,
            'success_stories' => $success_stories,
            'aftercare_appointments' => $aftercare_appointments,
            'lab_reports' => $lab_reports,
            'stats' => $stats
        ];

        $this->view('hospital/index', $data);
    }

    private function handlePost($regNo) {
        $hospitalModel = new HospitalModel();
        $action = $_POST['action'] ?? '';

        switch ($action) {
            case 'add_organ_request':
                $data = [
                    'registration_no' => $regNo,
                    'organ_type' => $_POST['organ_type'],
                    'urgency' => $_POST['urgency'],
                    'notes' => $_POST['notes']
                ];
                if ($hospitalModel->addOrganRequest($data)) {
                    $_SESSION['flash_success'] = "Organ request added successfully.";
                }
                break;

            case 'edit_organ_request':
                $requestId = $_POST['request_id'];
                $data = [
                    'urgency' => $_POST['urgency'],
                    'urgency_reason' => $_POST['urgency_reason'],
                    'notes' => $_POST['notes']
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
                }
                break;

            case 'delete_lab_report':
                if ($hospitalModel->deleteLabReport($_POST['report_id'])) {
                    $_SESSION['flash_success'] = "Lab report deleted successfully.";
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
                $data = [
                    'donor_id' => $_POST['donor_id'] ?? null,
                    'hospital_registration_no' => $regNo,
                    'test_type' => $_POST['test_type'],
                    'test_date' => $_POST['test_date'],
                    'result_status' => $_POST['status'] ?? 'Pending',
                    'result_notes' => $_POST['notes'] ?? '',
                ];
                if ($hospitalModel->addLabReport($data)) {
                    $_SESSION['flash_success'] = "Appointment scheduled successfully.";
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

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Handle create patient account logic locally or pass to handlePost
            $action = $_POST['action'] ?? '';
            if ($action === 'create_aftercare_account') {
                $nic = $_POST['nic'];
                $userId = 'PAT-' . date('Ym') . '-' . rand(1000, 9999);
                $password = password_hash($nic, PASSWORD_DEFAULT); // Default password as NIC
                
                // Add to database directly using correct mapped columns (DONOR role used as PATIENT is not in schema ENUM)
                $query = "INSERT INTO users (username, password_hash, email, role, status, created_at) VALUES (:username, :password, :email, 'DONOR', 'ACTIVE', NOW())";
                $hospitalModel->query($query, [
                    'username' => $userId,
                    'email' => $userId . '@lifeconnect.lk', // Pseudo email as ID
                    'password' => $password
                ]);
                
                $_SESSION['flash_success'] = "Aftercare portal account created successfully! User ID: $userId";
                redirect('hospital/addpatient');
            }
        }

        $hospital_details = [
            'name' => $hospital->name,
            'registration' => $hospital->registration_number,
            'role' => 'HOSPITAL',
            'email' => $_SESSION['email'] ?? 'admin@lifeconnect.lk',
        ];

        // Fetch user accounts starting with 'PAT-' to show created patients
        $patient_accounts = $hospitalModel->query("SELECT id, username as user_id, created_at FROM users WHERE role = 'DONOR' AND username LIKE 'PAT-%' ORDER BY created_at DESC", []) ?: [];

        $data = [
            'hospital_name' => $hospital->name,
            'hospital_details' => $hospital_details,
            'patient_accounts' => $patient_accounts
        ];

        $this->view('hospital/addpatient', $data);
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
            $format = isset($_GET['format']) ? strtolower(trim($_GET['format'])) : 'pdf';
            
            // Get all recipients for this hospital
            $recipients = $hospitalModel->getRecipients($hospital_registration) ?: [];

            switch($format) {
                case 'csv':
                    $this->generateCSV($recipients, $hospital_name);
                    break;
                case 'pdf':
                    $this->generatePDF($recipients, $hospital_name);
                    break;
                case 'xlsx':
                case 'excel':
                    $this->generateExcel($recipients, $hospital_name);
                    break;
                case 'svg':
                    $this->generateSVG($recipients, $hospital_name);
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

    /**
     * Generate PDF export
     */
    private function generatePDF($recipients, $hospital_name) {
        // Include FPDF library
        require_once __DIR__ . '/../Libraries/fpdf.php';
        
        $pdf = new \FPDF();
        $pdf->AddPage();

        $logoPath = __DIR__ . '/../../public/assets/images/logo.png';
        $logoPath = file_exists($logoPath) ? $logoPath : null;

        // Add watermark first (so content renders on top)
        $this->addWatermark($pdf, $logoPath);

        // Add logo at the top
        if ($logoPath) {
            $pdf->Image($logoPath, 10, 8, 18);
        }
        
        // Use Helvetica core font (available without custom files)
        $pdf->SetFont('Helvetica', 'B', 16);
        
        // Header with logo offset
        $pdf->SetXY(35, 10);
        $pdf->Cell(0, 10, 'Recipient Patient Report', 0, 1, 'C');
        $pdf->SetFont('Helvetica', '', 10);
        $pdf->Cell(0, 5, $hospital_name, 0, 1, 'C');
        $pdf->Cell(0, 5, 'Generated: ' . date('Y-m-d H:i:s'), 0, 1, 'C');
        $pdf->Ln(5);
        
        // Table Header
        $pdf->SetFont('Helvetica', 'B', 10);
        $pdf->SetFillColor(0, 91, 170);
        $pdf->SetTextColor(255, 255, 255);
        
        $pdf->Cell(25, 8, 'NIC', 1, 0, 'C', true);
        $pdf->Cell(40, 8, 'Name', 1, 0, 'L', true);
        $pdf->Cell(30, 8, 'Organ', 1, 0, 'C', true);
        $pdf->Cell(28, 8, 'Surgery Date', 1, 0, 'C', true);
        $pdf->Cell(25, 8, 'Status', 1, 1, 'C', true);
        
        // Table Data
        $pdf->SetFont('Helvetica', '', 9);
        $pdf->SetTextColor(0, 0, 0);
        
        foreach($recipients as $recipient) {
            $pdf->Cell(25, 8, substr($recipient->nic, -6), 1, 0, 'C');
            $pdf->Cell(40, 8, substr($recipient->name, 0, 20), 1, 0, 'L');
            $pdf->Cell(30, 8, substr($recipient->organ_received, 0, 10), 1, 0, 'C');
            $pdf->Cell(28, 8, date('m/d/Y', strtotime($recipient->surgery_date)), 1, 0, 'C');
            $pdf->Cell(25, 8, $recipient->status, 1, 1, 'C');
        }
        
        // Footer
        $pdf->Ln(5);
        $pdf->SetFont('Helvetica', 'B', 8);
        $pdf->Cell(0, 5, 'Total Recipients: ' . count($recipients), 0, 1);
        
        // Output
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="Recipient_Report_' . date('Y-m-d_H-i-s') . '.pdf"');
        $pdf->Output('D', 'Recipient_Report_' . date('Y-m-d_H-i-s') . '.pdf');
        exit;
    }

    /**
     * Add watermark to PDF
     */
    private function addWatermark($pdf, $logoPath = null) {
        // FPDF doesn't support transparency; rely on light color + (optional) PNG alpha.
        $pdf->SetTextColor(235, 235, 235);
        $pdf->SetFont('Helvetica', 'B', 40);

        // Tile logo (if present)
        if ($logoPath) {
            $logoPositions = [
                [20, 70], [120, 110], [40, 180], [130, 240]
            ];
            foreach ($logoPositions as $pos) {
                $pdf->Image($logoPath, $pos[0], $pos[1], 55);
            }
        }

        // Tile text watermark
        $textPositions = [
            [15, 95], [15, 160], [15, 225], [15, 290]
        ];
        foreach ($textPositions as $pos) {
            $pdf->SetXY($pos[0], $pos[1]);
            $pdf->Cell(0, 18, 'LifeConnect Sri Lanka', 0, 0, 'L');
        }

        // Reset for normal rendering
        $pdf->SetTextColor(0, 0, 0);
    }

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
            
            if (empty($searchQuery)) {
                // Return all approved donors when no search query
                $query = "SELECT id, nic_number, first_name, last_name, blood_group 
                          FROM donors 
                          WHERE verification_status = 'APPROVED'
                          ORDER BY first_name, last_name
                          LIMIT 100";
                
                $results = $hospitalModel->query($query, []);
                echo json_encode($results ?: []);
                return;
            }

            // Search donors by NIC or name
            $query = "SELECT id, nic_number, first_name, last_name, blood_group 
                      FROM donors 
                      WHERE verification_status = 'APPROVED' 
                      AND (nic_number LIKE :search OR first_name LIKE :search OR last_name LIKE :search)
                      ORDER BY first_name, last_name
                      LIMIT 10";
            
            $searchParam = '%' . $searchQuery . '%';
            $results = $hospitalModel->query($query, [':search' => $searchParam]);

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
}
