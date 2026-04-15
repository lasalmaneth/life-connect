<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\FinancialDonorModel;
use App\Models\admin\FinancialDonationModel;

class FinancialDonor
{
    use Controller;

    public function index()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId || $_SESSION['role'] !== 'FINANCIAL_DONOR') {
            redirect('login');
        }

        $donorModel = new FinancialDonorModel();
        $donorData = $donorModel->getFinancialDonorByUserId($userId);

        if (!$donorData) {
            redirect('login');
        }

        $donationModel = new FinancialDonationModel();
        $donation_history = $donationModel->getDonationsByDonorId($donorData['donor_id']);

        $this->view('financial_donor/index', [
            'donor_data' => $donorData,
            'donation_history' => $donation_history
        ]);
    }

    public function history()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) redirect('login');

        $donorModel = new FinancialDonorModel();
        $donorData = $donorModel->getFinancialDonorByUserId($userId);

        $donationModel = new FinancialDonationModel();
        $history = $donationModel->getDonationsByDonorId($donorData['donor_id']);

        $this->view('financial_donor/document', [
            'donor_data' => $donorData,
            'history' => $history
        ]);
    }

    public function donate()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) redirect('login');

        $donorModel = new FinancialDonorModel();
        $donorData = $donorModel->getFinancialDonorByUserId($userId);

        $donationModel = new FinancialDonationModel();
        $donation_history = $donationModel->getDonationsByDonorId($donorData['donor_id']);

        $this->view('financial_donor/donate', [
            'donor_data' => $donorData,
            'donation_history' => $donation_history
        ]);
    }

    public function getAnalytics()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $userId = $_SESSION['user_id'] ?? null;

        if (!$userId) {
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $donorModel = new FinancialDonorModel();
        $donorData = $donorModel->getFinancialDonorByUserId($userId);

        $donationModel = new FinancialDonationModel();
        $history = $donationModel->getDonationsByDonorId($donorData['donor_id']);

        // Format for Chart.js
        $analytics = [
            'labels' => [],
            'data' => []
        ];

        foreach (array_reverse($history) as $donation) {
            $analytics['labels'][] = date('M d', strtotime($donation->created_at));
            $analytics['data'][] = $donation->amount;
        }

        echo json_encode($analytics);
    }

    public function processDonation()
    {
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new \Exception('Invalid request method');
            }

            if (session_status() === PHP_SESSION_NONE) session_start();
            $userId = $_SESSION['user_id'] ?? null;
            
            if (!$userId) {
                throw new \Exception('Unauthorized access');
            }

            $donorModel = new FinancialDonorModel();
            $donorData = $donorModel->getFinancialDonorByUserId($userId);
            
            if (!$donorData) {
                throw new \Exception('Donor profile not found');
            }

            $amount = $_POST['amount'] ?? 0;
            if ($amount < 100) {
                throw new \Exception('Minimum donation is Rs. 100');
            }

            $donationModel = new FinancialDonationModel();
            $success = $donationModel->createDonation([
                'donor_id' => $donorData['donor_id'],
                'amount' => $amount,
                'note' => $_POST['message'] ?? '',
                'status' => 'SUCCESS'
            ]);

            if ($success) {
                echo json_encode(['success' => true]);
            } else {
                throw new \Exception('Database could not save donation record');
            }

        } catch (\Exception $e) {
            echo json_encode([
                'success' => false, 
                'message' => $e->getMessage()
            ]);
        }
    }
}
