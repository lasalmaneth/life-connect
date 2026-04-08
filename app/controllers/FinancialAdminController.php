<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\FinancialDonationModel;

class FinancialAdminController {
    use Controller;

    public function index() {
        $model = new FinancialDonationModel();
        $this->view('admin/finance', [
            'total' => $model->getTotalDonations(),
            'highest' => $model->getHighestContributor(),
            'kpis' => $model->getFinancialKPIs()
        ]);
    }

    public function getAllDonations() {
        header('Content-Type: application/json');
        $model = new FinancialDonationModel();
        echo json_encode(['success' => true, 'donations' => $model->getAllDonations()]);
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
