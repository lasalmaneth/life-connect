<?php

namespace App\Controllers\admin;

use App\Core\Controller;
use App\Core\Database;
use Exception;
use PDO;

class DonationAdminController {
    use Controller;
    use Database;

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'D_ADMIN') {
            redirect('login');
        }

        // Fetch Organ Request Stats for Bar Charts
        $open_requests = $this->query("SELECT COUNT(*) as count FROM organ_requests WHERE status IN ('OPEN', 'PENDING')")[0]->count ?? 0;
        $matched_requests = $this->query("SELECT COUNT(*) as count FROM organ_requests WHERE status = 'MATCHED'")[0]->count ?? 0;
        $closed_requests = $this->query("SELECT COUNT(*) as count FROM organ_requests WHERE status = 'CLOSED'")[0]->count ?? 0;
        $total_requests = $open_requests + $matched_requests + $closed_requests;

        // Fetch Priority Stats for Pillar Charts
        $normal_requests = $this->query("SELECT COUNT(*) as count FROM organ_requests WHERE priority_level = 'NORMAL'")[0]->count ?? 0;
        $urgent_requests = $this->query("SELECT COUNT(*) as count FROM organ_requests WHERE priority_level = 'URGENT'")[0]->count ?? 0;
        $critical_requests = $this->query("SELECT COUNT(*) as count FROM organ_requests WHERE priority_level = 'CRITICAL'")[0]->count ?? 0;

        $data = [
            'request_stats' => [
                'open' => (int)$open_requests,
                'matched' => (int)$matched_requests,
                'closed' => (int)$closed_requests,
                'total' => (int)$total_requests
            ],
            'priority_stats' => [
                'normal' => (int)$normal_requests,
                'urgent' => (int)$urgent_requests,
                'critical' => (int)$critical_requests
            ]
        ];

        $this->view('admin/donationAdmin/donation', $data);
    }

    public function getDashboardStats() {
        header('Content-Type: application/json');
        try {
            $totalDonors = $this->query("SELECT COUNT(DISTINCT donor_id) as count FROM donor_pledges WHERE status != 'REJECTED'")[0]->count ?? 0;
            $totalOrgans = $this->query("SELECT COUNT(*) as count FROM donor_pledges WHERE status != 'REJECTED'")[0]->count ?? 0;
            $pendingApprovals = $this->query("SELECT COUNT(*) as count FROM donor_pledges WHERE status = 'PENDING'")[0]->count ?? 0;
            $completedDonations = $this->query("SELECT COUNT(*) as count FROM donor_pledges WHERE status = 'COMPLETED'")[0]->count ?? 0;

            echo json_encode([
                'success' => true,
                'stats' => [
                    'totalDonors' => (int)$totalDonors,
                    'totalOrgans' => (int)$totalOrgans,
                    'pendingApprovals' => (int)$pendingApprovals,
                    'completedDonations' => (int)$completedDonations
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getPledges() {
        header('Content-Type: application/json');
        try {
            $query = "SELECT dp.id, dp.donor_id, dp.organ_id, dp.status, dp.pledge_date as pledged_date, 
                             d.first_name, d.last_name, o.name as organ_name, d.blood_group as blood_type
                      FROM donor_pledges dp 
                       JOIN donors d ON dp.donor_id = d.id 
                      JOIN organs o ON dp.organ_id = o.id 
                      ORDER BY dp.pledge_date DESC";
            
            $result = $this->query($query);
            echo json_encode(['success' => true, 'pledges' => $result]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getOrganDetails() {
        header('Content-Type: application/json');
        $id = $_GET['organ_id'] ?? null;
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID required']);
            return;
        }

        $query = "SELECT dp.*, dp.pledge_date as pledged_date, d.first_name, d.last_name, o.name as organ_name, d.blood_group as blood_type 
                  FROM donor_pledges dp 
                  JOIN donors d ON dp.donor_id = d.id 
                  JOIN organs o ON dp.organ_id = o.id 
                  WHERE dp.id = :id";
        
        $result = $this->query($query, ['id' => $id]);
        if ($result) {
            echo json_encode(['success' => true, 'organ' => $result[0]]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Not found']);
        }
    }

    public function updateOrganStatus() {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['organ_id'] ?? null;
        $status = $input['status'] ?? null;

        if (!$id || !$status) {
            echo json_encode(['success' => false, 'message' => 'Missing data']);
            return;
        }

        $query = "UPDATE donor_pledges SET status = :status WHERE id = :id";
        $this->query($query, ['status' => $status, 'id' => $id]);
        
        echo json_encode(['success' => true, 'message' => 'Status updated']);
    }

    public function getHospitalRequests() {
        header('Content-Type: application/json');
        try {
            $query = "SELECT orq.id, orq.hospital_id, orq.organ_id, orq.priority_level, orq.status, orq.created_at, 
                             h.name as hospital_name, o.name as organ_name 
                      FROM organ_requests orq 
                      JOIN hospitals h ON orq.hospital_id = h.id 
                      JOIN organs o ON orq.organ_id = o.id 
                      ORDER BY orq.created_at DESC";
            
            $result = $this->query($query);
            echo json_encode(['success' => true, 'requests' => $result]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function runAlgorithm() {
        header('Content-Type: application/json');
        try {
            $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->beginTransaction();
            
            $sqlReq = "SELECT r.id as request_id, r.organ_id, r.blood_group, r.priority_level 
                       FROM organ_requests r 
                       WHERE r.status != 'CLOSED'";
            $requests = $pdo->query($sqlReq)->fetchAll(PDO::FETCH_ASSOC);
            
            $sqlDonors = "SELECT dp.id as pledge_id, dp.organ_id, dp.allergies, dp.donor_id, d.blood_group 
                          FROM donor_pledges dp 
                          JOIN donors d ON dp.donor_id = d.id 
                          WHERE dp.status = 'APPROVED'";
            $donors = $pdo->query($sqlDonors)->fetchAll(PDO::FETCH_ASSOC);
            
            $matchesCreated = 0;
            
            foreach ($requests as $req) {
                foreach ($donors as $donor) {
                    if ($req['organ_id'] != $donor['organ_id']) continue;
                    
                    $isMatch = false;
                    $organId = $req['organ_id'];
                    $rBlood = str_replace(' ', '', strtoupper($req['blood_group'] ?? ''));
                    $dBlood = str_replace(' ', '', strtoupper($donor['blood_group'] ?? ''));
                    
                    $isDonorO = strpos($dBlood, 'O') !== false;
                    $isDonorA = strpos($dBlood, 'A') !== false && strpos($dBlood, 'B') === false;
                    $isDonorB = strpos($dBlood, 'B') !== false && strpos($dBlood, 'A') === false;
                    $isDonorAB = strpos($dBlood, 'AB') !== false;

                    if ($organId == 1 || $organId == 2) { 
                        if (empty($rBlood) || empty($dBlood)) continue; 
                        if ($isDonorO) { $isMatch = true; } 
                        else if ($isDonorA && (strpos($rBlood, 'A') !== false || strpos($rBlood, 'AB') !== false)) { $isMatch = true; } 
                        else if ($isDonorB && (strpos($rBlood, 'B') !== false || strpos($rBlood, 'AB') !== false)) { $isMatch = true; } 
                        else if ($isDonorAB && strpos($rBlood, 'AB') !== false) { $isMatch = true; }
                    } else if ($organId == 3) {
                        $isMatch = true;
                    } else {
                        if ($dBlood == $rBlood) $isMatch = true;
                    }
                    
                    if ($isMatch) {
                        $status = 'MATCH';
                        $warning = null;
                        if (!empty($donor['allergies']) && strtoupper($donor['allergies']) !== 'NONE') {
                            $status = 'MATCH WITH WARNING';
                            $warning = 'Medical Warning: Allergies - ' . $donor['allergies'];
                        }
                        
                        $chk = $pdo->prepare("SELECT 1 FROM donor_patient_match WHERE donor_pledge_id = ? AND request_id = ?");
                        $chk->execute([$donor['pledge_id'], $req['request_id']]);
                        
                        if ($chk->rowCount() == 0) {
                            $ins = $pdo->prepare("INSERT INTO donor_patient_match (donor_pledge_id, request_id, status, warning_details) VALUES (?, ?, ?, ?)");
                            $ins->execute([$donor['pledge_id'], $req['request_id'], $status, $warning]);
                            
                            $pdo->prepare("UPDATE organ_requests SET status = 'MATCHED' WHERE id = ?")->execute([$req['request_id']]);
                            $matchesCreated++;
                        }
                    }
                }
            }
            
            $pdo->commit();
            echo json_encode([
                'success' => true, 
                'message' => 'Matching algorithm completed successfully',
                'matches_created' => $matchesCreated
            ]);
        } catch (Exception $e) {
            if (isset($pdo) && $pdo->inTransaction()) { $pdo->rollBack(); }
            echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
        }
    }
}
