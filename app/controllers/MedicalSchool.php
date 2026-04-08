<?php 

namespace App\Controllers;

use App\Core\Controller;
use App\Models\MedicalSchoolModel;

class MedicalSchool {
    use Controller;

    private function checkAuth()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'MEDICAL_SCHOOL'){
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

    public function index()
    {
        $auth = $this->checkAuth();
        $stats = $auth['model']->getDashboardStats($auth['school']->id);
        $activeTransfers = $auth['model']->getPostDeathSubmissions($auth['school']->id);
        $usageMatrix = $auth['model']->getActiveLabUsageMatrix($auth['school']->id);
        $quotaMetrics = $auth['model']->getIntakeQuotaMetrics($auth['school']->id);

        $this->view('medical_schools/dashboard', [
            'school' => $auth['school'],
            'stats' => $stats,
            'activeTransfers' => $activeTransfers,
            'usageMatrix' => $usageMatrix,
            'quotaMetrics' => $quotaMetrics
        ]);
    }

    public function consents()
    {
        $auth = $this->checkAuth();
        $donors = $auth['model']->getPreDeathConsents($auth['school']->id);

        $this->view('medical_schools/consentforms', [
            'school' => $auth['school'],
            'donors' => $donors
        ]);
    }

    public function withdrawals()
    {
        $auth = $this->checkAuth();
        $donors = $auth['model']->getWithdrawnConsents($auth['school']->id);

        $this->view('medical_schools/withdrawals', [
            'school' => $auth['school'],
            'donors' => $donors
        ]);
    }

    public function submissions()
    {
        $auth = $this->checkAuth();
        $donors = $auth['model']->getPostDeathSubmissions($auth['school']->id);

        $this->view('medical_schools/submissions', [
            'school' => $auth['school'],
            'donors' => $donors
        ]);
    }

    public function bodyAcceptance()
    {
        $auth = $this->checkAuth();
        $donors = $auth['model']->getAcceptedBodies($auth['school']->id);

        $this->view('medical_schools/bodyacceptance', [
            'school' => $auth['school'],
            'donors' => $donors
        ]);
    }

    public function usageLogs()
    {
        $auth = $this->checkAuth();
        $donors = $auth['model']->getAcceptedBodies($auth['school']->id); // Using same list for logs picking

        $this->view('medical_schools/usagelogs', [
            'school' => $auth['school'],
            'donors' => $donors
        ]);
    }

    public function certificates()
    {
        $auth = $this->checkAuth();
        $this->view('medical_schools/certificates', [
            'school' => $auth['school']
        ]);
    }

    public function archived()
    {
        $auth = $this->checkAuth();
        $donors = $auth['model']->getArchivedRecords($auth['school']->id);

        $this->view('medical_schools/archived', [
            'school' => $auth['school'],
            'donors' => $donors
        ]);
    }

    public function reports()
    {
        $auth = $this->checkAuth();
        $this->view('medical_schools/reports', [
            'school' => $auth['school']
        ]);
    }

    public function getDonorDetails()
    {
        $id = $_GET['id'] ?? null;
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) session_start();
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'MEDICAL_SCHOOL'){
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            die();
        }

        $schoolModel = new MedicalSchoolModel();
        $school = $schoolModel->getSchoolByUserId($_SESSION['user_id']);
        if (!$school) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'School not found']);
            die();
        }

        $donor = $schoolModel->getDonorDetailsById($id, $school->id);
        
        if ($donor) {
            echo json_encode(['success' => true, 'data' => $donor]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Donor not found or access denied']);
        }
        die();
    }

    public function saveUsageLog()
    {
        $auth = $this->checkAuth();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = [
                'donor_id' => $_POST['donor_id'],
                'medical_school_id' => $auth['school']->id,
                'usage_type' => $_POST['usage_type'],
                'description' => $_POST['description'],
                'usage_date' => $_POST['usage_date'],
                'disposal_method' => $_POST['disposal_method'] ?? null,
                'disposal_date' => !empty($_POST['disposal_date']) ? $_POST['disposal_date'] : null,
                'status' => !empty($_POST['disposal_date']) ? 'DISPOSED' : 'IN_USE'
            ];

            if (isset($_POST['log_id']) && !empty($_POST['log_id'])) {
                if ($auth['model']->updateUsageLog($_POST['log_id'], $data)) {
                    $this->redirect('medical-school/usage-logs');
                } else {
                    die('Failed to update log');
                }
            } else {
                if ($auth['model']->addUsageLog($data)) {
                    $this->redirect('medical-school/usage-logs');
                } else {
                    die('Failed to save log');
                }
            }
        }
    }

    public function getUsageHistory()
    {
        $id = $_GET['id'] ?? null;
        if (ob_get_length()) ob_clean();
        header('Content-Type: application/json');

        if (session_status() === PHP_SESSION_NONE) session_start();
        if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'MEDICAL_SCHOOL'){
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            die();
        }

        $schoolModel = new MedicalSchoolModel();
        $school = $schoolModel->getSchoolByUserId($_SESSION['user_id']);
        if (!$school) {
            http_response_code(404);
            echo json_encode(['error' => 'School not found']);
            die();
        }

        $logs = $schoolModel->getUsageLogs($id, $school->id);
        echo json_encode($logs ? $logs : []);
        die();
    }
}
