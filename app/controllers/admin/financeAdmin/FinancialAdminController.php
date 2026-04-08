<?php
class FinancialAdminController {
    use Controller;

    private $donationModel;
    private $donorModel;

    public function __construct(){
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $this->setNoCacheHeaders();
   
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['role'])){
            redirect('login');
            exit;
        }
        
        $allowedRoles = ['FinanceAdmin', 'Admin', 'SystemAdmin'];
        
        if(!in_array($_SESSION['role'], $allowedRoles)){
            redirect('login');
            exit;
        }
    
        $this->donationModel = new FinancialDonationModel();
        $this->donorModel = new FinancialDonorAdminModel();
    }

    private function setNoCacheHeaders(){
        header("Cache-Control: no-cache, no-store, must-revalidate");
        header("Pragma: no-cache");
        header("Expires: 0");
        header("X-Content-Type-Options: nosniff");
        header("X-Frame-Options: DENY");
        header("X-XSS-Protection: 1; mode=block");
    }
    
    public function index(){
        $this->setNoCacheHeaders();

        $totalDonationsReceived = $this->donationModel->getTotalDonations();
        $totalDonors = $this->donorModel->getTotalDonors();
        $highestContributor = $this->donationModel->getHighestContributor();
        $donationsPastMonth = $this->donationModel->getDonationsPastMonth();
        $donationsPast3Months = $this->donationModel->getDonationsPast3Months();
        $donationsThisYear = $this->donationModel->getDonationsThisYear();

        $this->view('admin/financeAdmin/financialAdmin', [
            'totalDonationsReceived' => $totalDonationsReceived ?? 0,
            'totalDonors' => $totalDonors ?? 0,
            'highestContributor' => $highestContributor,
            'donationsPastMonth' => $donationsPastMonth ?? 0,
            'donationsPast3Months' => $donationsPast3Months ?? 0,
            'donationsThisYear' => $donationsThisYear ?? 0
        ]);
    }

    // Fetch all donations with filters (AJAX)
    public function getAllDonations(){
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['role'])){
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Unauthorized', 'message' => 'Please log in']);
            http_response_code(401);
            return;
        }
        
        $allowedRoles = ['FinanceAdmin', 'Admin', 'SystemAdmin'];
        if(!in_array($_SESSION['role'], $allowedRoles)){
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden', 'message' => 'Access denied']);
            http_response_code(403);
            return;
        }
        
        // Get filters from request
        $filters = [];
        if(isset($_GET['status'])) {
            $filters['status'] = $_GET['status'];
            error_log("Filter status: " . $_GET['status']); // Debug
        }
        if(isset($_GET['date_from'])) {
            $filters['date_from'] = $_GET['date_from'];
            error_log("Filter date_from: " . $_GET['date_from']); // Debug
        }
        if(isset($_GET['date_to'])) {
            $filters['date_to'] = $_GET['date_to'];
            error_log("Filter date_to: " . $_GET['date_to']); // Debug
        }
        if(isset($_GET['amount_min'])) {
            $filters['amount_min'] = $_GET['amount_min'];
            error_log("Filter amount_min: " . $_GET['amount_min']); // Debug
        }
        if(isset($_GET['amount_max'])) {
            $filters['amount_max'] = $_GET['amount_max'];
            error_log("Filter amount_max: " . $_GET['amount_max']); // Debug
        }
        
        error_log("Filters applied: " . print_r($filters, true)); // Debug
        
        $donations = $this->donationModel->getAllDonations($filters);
        
        error_log("Donations found: " . count($donations)); // Debug
        
        header('Content-Type: application/json');
        echo json_encode($donations);
    }

    // Update donation status (AJAX)
    public function updateDonationStatus(){
        if(!isset($_SESSION['user_id']) || !isset($_SESSION['role'])){
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            http_response_code(401);
            return;
        }
        
        $allowedRoles = ['FinanceAdmin', 'Admin', 'SystemAdmin'];
        if(!in_array($_SESSION['role'], $allowedRoles)){
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Access denied']);
            http_response_code(403);
            return;
        }

        if($_SERVER['REQUEST_METHOD'] !== 'POST'){
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $donationId = $data['donation_id'] ?? null;
        $status = $data['status'] ?? null;

        if(!$donationId || !$status){
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Missing required fields']);
            return;
        }

        // Validate status
        $validStatuses = ['Pending', 'Completed', 'Failed', 'Cancelled'];
        if(!in_array($status, $validStatuses)){
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Invalid status']);
            return;
        }

        $result = $this->donationModel->updateStatus($donationId, $status);
        
        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'message' => 'Status updated successfully'
        ]);
    }

    public function logout(){
        $fromLogo = isset($_GET['from']) && $_GET['from'] === 'logo';
        
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        
        if($fromLogo) {
            redirect('home');
        } else {
            redirect('login');
        }
    }

    public function logoLogout(){
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        redirect('home');
    }
}