<?php 

namespace App\Controllers\admin;

use App\Core\Controller;
use App\Models\admin\FinancialDonationModel;
use App\Models\SupportRequestModel;
use App\Models\admin\VoucherModel;

class FinancialAdminController {
    use Controller;

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $model = new FinancialDonationModel();
        $supportModel = new SupportRequestModel();
        $voucherModel = new VoucherModel();

        $this->view('admin/finance', [
            'total' => $model->getTotalDonations(),
            'highest' => $model->getHighestContributor(),
            'kpis' => $model->getFinancialKPIs(),
            'support_requests' => $supportModel->getAllRequests(),
            'support_stats' => $supportModel->getStats(),
            'vouchers' => $voucherModel->getAllVouchers(),
            'voucher_stats' => $voucherModel->getStats()
        ]);
    }

    public function getAllDonations() {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        $model = new FinancialDonationModel();
        echo json_encode(['success' => true, 'donations' => $model->getAllDonations()]);
    }

    public function updateSupportStatus() {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) session_start();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            $status = $_POST['status'] ?? null;
            $reviewer = $_SESSION['username'] ?? 'Financial Admin';

            if ($id && $status) {
                try {
                    $model = new SupportRequestModel();
                    
                    // If approving, also issue a voucher
                    if ($status === 'APPROVED') {
                        $voucherModel = new VoucherModel();
                        $results = $model->query("SELECT * FROM support_requests WHERE id = :id", [':id' => $id]);
                        $request = (is_array($results) && count($results) > 0) ? $results[0] : null;
                        
                        if ($request) {
                            $voucherModel->createVoucher($id, $request->patient_nic, $request->amount);
                        }
                    }
                } catch (\Exception $e) {
                    error_log("FinancialAdminController error: " . $e->getMessage());
                    echo json_encode(['success' => false, 'message' => "Voucher creation failed: " . $e->getMessage()]);
                    return;
                }

                $model->updateStatus($id, $status, $reviewer);
                echo json_encode(['success' => true]);
                return;
            }
        }
        echo json_encode(['success' => false, 'message' => 'Invalid request']);
    }

    public function logout() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        session_destroy();
        redirect('login');
    }

    public function logoLogout() {
        $this->logout();
    }
}
