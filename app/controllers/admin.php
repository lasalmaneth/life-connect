<?php
// admin.php - User Admin Controller
require_once __DIR__ . '/../core/config.php';

class UserAdmin {
    private $pdo;

    public function __construct() {
        $this->pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function handleRequest() {
        if (isset($_GET['action'])) {
            switch ($_GET['action']) {
                case 'getDonorsForVerification':
                    $this->getDonorsForVerification();
                    break;
                case 'updateVerificationStatus':
                    $this->updateVerificationStatus();
                    break;
                case 'getNICValidationStats':
                    $this->getNICValidationStats();
                    break;
                default:
                    $this->sendResponse(array('success' => false, 'message' => 'Invalid action'));
            }
        }
    }

    public function getDonorsForVerification() {
        try {
            $sql = "SELECT 
                        donor_id,
                        first_name,
                        last_name,
                        nic_number,
                        nic_image_path,
                        COALESCE(verification_status, 'Pending') as verification_status
                    FROM donors 
                    ORDER BY registration_date DESC";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $donors = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->sendResponse(array('success' => true, 'donors' => $donors));
            
        } catch (PDOException $e) {
            $this->sendResponse(array('success' => false, 'message' => 'Error loading donors: ' . $e->getMessage()));
        }
    }

    public function updateVerificationStatus() {
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            
            $donor_id = isset($input['donor_id']) ? $input['donor_id'] : null;
            $verification_status = isset($input['verification_status']) ? $input['verification_status'] : null;

            if (!$donor_id || !$verification_status) {
                $this->sendResponse(array('success' => false, 'message' => 'Missing required parameters'));
                return;
            }

            $sql = "UPDATE donors SET verification_status = :verification_status WHERE donor_id = :donor_id";
            $stmt = $this->pdo->prepare($sql);
            $result = $stmt->execute(array(
                'verification_status' => $verification_status,
                'donor_id' => $donor_id
            ));

            if ($result) {
                $this->sendResponse(array('success' => true, 'message' => 'Verification status updated successfully'));
            } else {
                $this->sendResponse(array('success' => false, 'message' => 'Failed to update verification status'));
            }

        } catch (PDOException $e) {
            $this->sendResponse(array('success' => false, 'message' => 'Database error: ' . $e->getMessage()));
        }
    }

    public function getNICValidationStats() {
        try {
            $sql = "SELECT 
                        COALESCE(verification_status, 'Pending') as verification_status,
                        COUNT(*) as count
                    FROM donors 
                    GROUP BY COALESCE(verification_status, 'Pending')";

            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            $stats = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $totalValidated = 0;
            $totalPending = 0;

            foreach ($stats as $stat) {
                if ($stat['verification_status'] === 'Verified') {
                    $totalValidated = $stat['count'];
                } else if ($stat['verification_status'] === 'Pending') {
                    $totalPending = $stat['count'];
                }
            }

            $this->sendResponse(array(
                'success' => true, 
                'stats' => array(
                    'totalValidated' => $totalValidated,
                    'totalPending' => $totalPending
                )
            ));
            
        } catch (PDOException $e) {
            $this->sendResponse(array('success' => false, 'message' => 'Error loading stats: ' . $e->getMessage()));
        }
    }

    private function sendResponse($data) {
        // Clear any previous output
        if (ob_get_length()) {
            ob_clean();
        }
        header('Content-Type: application/json');
        echo json_encode($data);
        exit; // Stop execution after sending JSON
    }
}

// Handle the request
if (isset($_GET['action'])) {
    $admin = new UserAdmin();
    $admin->handleRequest();
}
?>
