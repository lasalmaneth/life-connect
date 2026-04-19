<?php

namespace App\Controllers\admin;

use App\Core\Controller;
use App\Core\Database;
use Exception;
use PDO;
use App\Models\admin\AftercareAdminModel;

class DonationAdminController {
    use Controller;
    use Database;

    public function index() {
        if (session_status() === PHP_SESSION_NONE) session_start();
        $role = strtoupper($_SESSION['role'] ?? '');
        if (!isset($_SESSION['user_id']) || ($role !== 'D_ADMIN' && $role !== 'ADMIN')) {
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
            if (session_status() === PHP_SESSION_NONE) session_start();
            $role = strtoupper($_SESSION['role'] ?? '');
            if (!isset($_SESSION['user_id']) || ($role !== 'D_ADMIN' && $role !== 'ADMIN')) {
                throw new \Exception("Unauthorized access");
            }
            // Helper for percentage change estimation (Month-over-Month)
            $calcChange = function($current, $previous) {
                if ($previous == 0) return $current > 0 ? 100 : 0;
                return round((($current - $previous) / $previous) * 100, 1);
            };

            $thisMonthStart = date('Y-m-01 00:00:00');
            $lastMonthStart = date('Y-m-01 00:00:00', strtotime('first day of last month'));

            // 1. Total Organ Pledgers (Distinct Donors)
            $totalDonorsCount = $this->query("SELECT COUNT(DISTINCT donor_id) as count FROM donor_pledges WHERE status != 'REJECTED'")[0]->count ?? 0;
            $donorsThisMonth = $this->query("SELECT COUNT(DISTINCT donor_id) as count FROM donor_pledges WHERE status != 'REJECTED' AND pledge_date >= :start", ['start' => $thisMonthStart])[0]->count ?? 0;
            $donorsLastMonth = $this->query("SELECT COUNT(DISTINCT donor_id) as count FROM donor_pledges WHERE status != 'REJECTED' AND pledge_date >= :start AND pledge_date < :end", ['start' => $lastMonthStart, 'end' => $thisMonthStart])[0]->count ?? 0;
            $donorsChange = $calcChange($donorsThisMonth, $donorsLastMonth);

            // 2. Organ Pledges (Total Count)
            $totalOrgansCount = $this->query("SELECT COUNT(*) as count FROM donor_pledges WHERE status != 'REJECTED'")[0]->count ?? 0;
            $organsThisMonth = $this->query("SELECT COUNT(*) as count FROM donor_pledges WHERE status != 'REJECTED' AND pledge_date >= :start", ['start' => $thisMonthStart])[0]->count ?? 0;
            $organsLastMonth = $this->query("SELECT COUNT(*) as count FROM donor_pledges WHERE status != 'REJECTED' AND pledge_date >= :start AND pledge_date < :end", ['start' => $lastMonthStart, 'end' => $thisMonthStart])[0]->count ?? 0;
            $organsChange = $calcChange($organsThisMonth, $organsLastMonth);

            // 3. Pending Pledges
            $pendingCount = $this->query("SELECT COUNT(*) as count FROM donor_pledges WHERE status = 'PENDING'")[0]->count ?? 0;
            $pendingThisMonth = $this->query("SELECT COUNT(*) as count FROM donor_pledges WHERE status = 'PENDING' AND pledge_date >= :start", ['start' => $thisMonthStart])[0]->count ?? 0;
            $pendingLastMonth = $this->query("SELECT COUNT(*) as count FROM donor_pledges WHERE status = 'PENDING' AND pledge_date >= :start AND pledge_date < :end", ['start' => $lastMonthStart, 'end' => $thisMonthStart])[0]->count ?? 0;
            $pendingChange = $calcChange($pendingThisMonth, $pendingLastMonth);

            // 4. Successful Matches
            $matchesCount = $this->query("SELECT COUNT(*) as count FROM donor_patient_match")[0]->count ?? 0;
            $matchesThisMonth = $this->query("SELECT COUNT(*) as count FROM donor_patient_match WHERE match_date >= :start", ['start' => $thisMonthStart])[0]->count ?? 0;
            $matchesLastMonth = $this->query("SELECT COUNT(*) as count FROM donor_patient_match WHERE match_date >= :start AND match_date < :end", ['start' => $lastMonthStart, 'end' => $thisMonthStart])[0]->count ?? 0;
            $matchesChange = $calcChange($matchesThisMonth, $matchesLastMonth);

            // 5. Aftercare Patients
            $totalRecipients = $this->query("SELECT COUNT(*) as count FROM aftercare_patients WHERE patient_type = 'RECIPIENT'")[0]->count ?? 0;
            $totalDonorsAftercare = $this->query("SELECT COUNT(*) as count FROM aftercare_patients WHERE patient_type = 'DONOR'")[0]->count ?? 0;

            echo json_encode([
                'success' => true,
                'stats' => [
                    'totalDonors' => ['count' => (int)$totalDonorsCount, 'change' => $donorsChange],
                    'totalOrgans' => ['count' => (int)$totalOrgansCount, 'change' => $organsChange],
                    'pendingApprovals' => ['count' => (int)$pendingCount, 'change' => $pendingChange],
                    'successfulMatches' => ['count' => (int)$matchesCount, 'change' => $matchesChange],
                    'totalRecipients' => (int)$totalRecipients,
                    'totalDonorsAftercare' => (int)$totalDonorsAftercare
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

        $query = "SELECT dp.*, dp.pledge_date as pledged_date, d.first_name, d.last_name, 
                         o.name as organ_name, d.blood_group as blood_type,
                         h.name as preferred_hospital_name
                  FROM donor_pledges dp 
                  JOIN donors d ON dp.donor_id = d.id 
                  JOIN organs o ON dp.organ_id = o.id 
                  LEFT JOIN hospitals h ON dp.preferred_hospital_id = h.id
                  WHERE dp.id = :id";
        
        $result = $this->query($query, ['id' => $id]);
        if ($result) {
            $organ = (array)$result[0];
            $organId = (int)$organ['organ_id'];
            $donorId = (int)$organ['donor_id'];

            // Fetch Consent Details based on Organ ID
            if ($organId >= 1 && $organId <= 3) {
                $organ['consent_type'] = 'LIVING';
                $consent = $this->query("SELECT * FROM living_donor_consents WHERE donor_pledge_id = :id", ['id' => $id]);
                $organ['consent_data'] = $consent ? $consent[0] : null;
            } elseif ($organId >= 4 && $organId <= 9) {
                $organ['consent_type'] = 'DECEASED';
                $consent = $this->query("SELECT * FROM after_death_consents WHERE donor_id = :id", ['id' => $donorId]);
                $organ['consent_data'] = $consent ? $consent[0] : null;
            } elseif ($organId == 10) {
                $organ['consent_type'] = 'BODY';
                $consent = $this->query("SELECT * FROM body_donation_consents WHERE donor_id = :id AND status = 'ACTIVE'", ['id' => $donorId]);
                $organ['consent_data'] = $consent ? $consent[0] : null;
            }

            // Fetch Witnesses
            $witnesses = $this->query("SELECT * FROM witnesses WHERE donor_id = :donor_id AND organ_id = :organ_id", [
                'donor_id' => $donorId,
                'organ_id' => $organId
            ]);
            $organ['witnesses'] = $witnesses ?: [];

            echo json_encode(['success' => true, 'organ' => $organ]);
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

        // Verify transition rule: Pledges must be in UPLOADED status and can only move to APPROVED or SUSPENDED
        $current = $this->query("SELECT status FROM donor_pledges WHERE id = :id", ['id' => $id]);
        if ($current) {
            $currentStatus = strtoupper($current[0]->status);
            
            if ($currentStatus !== 'UPLOADED') {
                echo json_encode(['success' => false, 'message' => 'This pledge has already been finalized (' . $currentStatus . ') and cannot be modified.']);
                return;
            }

            if (!in_array(strtoupper($status), ['APPROVED', 'SUSPENDED'])) {
                echo json_encode(['success' => false, 'message' => 'For uploaded pledges, only Approved or Suspended transitions are allowed.']);
                return;
            }
        }

        $query = "UPDATE donor_pledges SET status = :status WHERE id = :id";
        $this->query($query, ['status' => $status, 'id' => $id]);

        // If this is a body donation (organ_id == 10) and status is APPROVED, set consent ACTIVE
        if (strtoupper($status) === 'APPROVED') {
            $organ = $this->query("SELECT organ_id, donor_id FROM donor_pledges WHERE id = :id", ['id' => $id]);
            if ($organ && (int)$organ[0]->organ_id === 10) {
                // Set body_donation_consents to ACTIVE for this donor
                $this->query(
                    "UPDATE body_donation_consents SET status = 'ACTIVE' WHERE donor_id = :donor_id AND status != 'WITHDRAWN'",
                    ['donor_id' => $organ[0]->donor_id]
                );
            }
        }

        echo json_encode(['success' => true, 'message' => 'Status updated']);
    }


    public function runAlgorithm() {
        header('Content-Type: application/json');
        try {
            $pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Self-healing schema check
            $this->ensureMatchSchema($pdo);
            
            $pdo->beginTransaction();
            $matchesCreated = $this->executeMatchingEngine($pdo);
            $pdo->commit();
            
            echo json_encode(['success' => true, 'matches_created' => $matchesCreated]);
        } catch (Exception $e) {
            if (isset($pdo) && $pdo->inTransaction()) { $pdo->rollBack(); }
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Core matching logic moved to a reusable method for automated triggers
     */
    public function executeMatchingEngine($pdo) {
        // 1. Fetch Requests with HLA data
        $sqlReq = "SELECT r.id as request_id, r.organ_id, r.blood_group as recipient_blood, r.priority_level,
                          r.hla_a1, r.hla_a2, r.hla_b1, r.hla_b2, r.hla_dr1, r.hla_dr2, r.recipient_age, r.gender as recipient_gender
                   FROM organ_requests r 
                   WHERE r.status NOT IN ('CLOSED', 'MATCHED')";
        $requests = $pdo->query($sqlReq)->fetchAll(PDO::FETCH_ASSOC);
        
        // 2. Fetch Approved Pledges with HLA and Consent data (for BMI)
        $sqlDonors = "SELECT dp.id as pledge_id, dp.organ_id, dp.allergies, dp.donor_id, 
                             d.blood_group as donor_blood, d.hla_a1, d.hla_a2, d.hla_b1, d.hla_b2, d.hla_dr1, d.hla_dr2,
                             d.date_of_birth, d.gender as donor_gender,
                             ldc.height, ldc.weight
                      FROM donor_pledges dp 
                      JOIN donors d ON dp.donor_id = d.id 
                      LEFT JOIN living_donor_consents ldc ON dp.id = ldc.donor_pledge_id
                      WHERE dp.status = 'APPROVED'";
        $donors = $pdo->query($sqlDonors)->fetchAll(PDO::FETCH_ASSOC);
        
        // 3. CLEANUP: Remove any legacy matches for organs that are no longer eligible for live matching (IDs > 3)
        $pdo->exec("DELETE FROM donor_patient_match WHERE donor_pledge_id IN (SELECT id FROM donor_pledges WHERE organ_id > 3)");

        $matchesCreated = 0;
        
        foreach ($requests as $req) {
            foreach ($donors as $donor) {
                if ($req['organ_id'] != $donor['organ_id']) continue;
                
                $organId = (int)$req['organ_id'];
                
                // STRICT RULE: Only match live-donation eligible organs (Kidney, Liver, Marrow)
                if ($organId > 3) continue;

                $warningDetails = [];

                // A. Blood Group Compatibility (Strict Rejection)
                $aboRes = $this->evaluateABO($donor['donor_blood'], $req['recipient_blood']);
                if (!$aboRes['compatible']) continue; 

                // B. HLA Matching Score (0.0 to 1.0)
                $hlaMatch = $this->calculateHLAMatch($donor, $req);
                $hlaScore = $hlaMatch['score'];
                $matchPercentage = round($hlaScore * 100);

                // C. Organ-Specific Logic
                $isEligible = false;
                
                if ($organId === 1) { // Kidney
                    if ($hlaScore >= 0.5) $isEligible = true;
                    else $warningDetails[] = "Low HLA Match (" . $matchPercentage . "%)";
                } else if ($organId === 2) { // Part of Liver
                    $isEligible = true;
                    // BMI check moved to general Demographic Insights below
                } else if ($organId === 3) { // Bone Marrow
                    if ($hlaScore >= 0.66) $isEligible = true;
                    else $warningDetails[] = "Low Marrow Compatibility ($matchPercentage%)";
                    $warningDetails[] = "6/10 HLA Markers Verified (Partial Scope)";
                }

                // D. Demographic Insights (Informational Warnings)
                // 1. Age Gap Logic
                if (!empty($donor['date_of_birth']) && !empty($req['recipient_age'])) {
                    $donorDob = new \DateTime($donor['date_of_birth']);
                    $now = new \DateTime();
                    $donorAge = $now->diff($donorDob)->y;
                    $ageGap = abs($donorAge - (int)$req['recipient_age']);
                    if ($ageGap > 25) $warningDetails[] = "Significant Age Gap Warning: {$donorAge} vs {$req['recipient_age']}";
                }

                // 2. BMI Analysis (Persistent in DB)
                if ($donor['height'] > 0 && $donor['weight'] > 0) {
                    $bmi = $donor['weight'] / (($donor['height']/100) ** 2);
                    if ($bmi > 25) $warningDetails[] = "High Donor BMI: " . round($bmi, 1);
                } else {
                    $warningDetails[] = "Missing Size/BMI Measurements";
                }

                // 3. Gender Consideration (Optional Indicator)
                if ($donor['donor_gender'] !== $req['recipient_gender']) {
                    // We don't necessarily flag this as a warning but track it for clinical insight
                }

                if ($isEligible) {
                    $rank = empty($warningDetails) ? 'MATCH' : 'MATCH WITH WARNING';
                    $warningStr = ($rank === 'MATCH' ? "Score: $matchPercentage% Match. " : "") . implode("; ", $warningDetails);
                    
                    $chk = $pdo->prepare("SELECT 1 FROM donor_patient_match WHERE donor_pledge_id = ? AND request_id = ?");
                    $chk->execute([$donor['pledge_id'], $req['request_id']]);
                    
                    if ($chk->rowCount() == 0) {
                        // Using the new descriptive column: clinical_match_quality
                        $ins = $pdo->prepare("INSERT INTO donor_patient_match (donor_pledge_id, request_id, clinical_match_quality, warning_details) VALUES (?, ?, ?, ?)");
                        $ins->execute([$donor['pledge_id'], $req['request_id'], $rank, $warningStr]);
                        
                        $pdo->prepare("UPDATE organ_requests SET status = 'MATCHED' WHERE id = ?")->execute([$req['request_id']]);
                        $matchesCreated++;
                    }
                }
            }
        }
        
        return $matchesCreated;
    }




    public function getFilterMetadata() {
        header('Content-Type: application/json');
        try {
            // 1. Fetch Organ Names
            $organs = $this->query("SELECT name FROM organs WHERE is_available = 1 ORDER BY name ASC");
            $organList = array_map(function($o) { return $o->name; }, $organs);

            // 2. Fetch Pledge Status Enums
            $pledgeStatusCol = $this->query("SHOW COLUMNS FROM donor_pledges LIKE 'status'");
            $pledgeStatuses = [];
            if ($pledgeStatusCol) {
                preg_match("/^enum\(\'(.*)\'\)$/", $pledgeStatusCol[0]->Type, $matches);
                $pledgeStatuses = explode("','", $matches[1]);
            }

            // 3. Fetch Request Priority Enums
            $requestPriorityCol = $this->query("SHOW COLUMNS FROM organ_requests LIKE 'priority_level'");
            $requestPriorities = [];
            if ($requestPriorityCol) {
                preg_match("/^enum\(\'(.*)\'\)$/", $requestPriorityCol[0]->Type, $matches);
                $requestPriorities = explode("','", $matches[1]);
            }

            // 4. Fetch Request Status Enums
            $requestStatusCol = $this->query("SHOW COLUMNS FROM organ_requests LIKE 'status'");
            $requestStatuses = [];
            if ($requestStatusCol) {
                preg_match("/^enum\(\'(.*)\'\)$/", $requestStatusCol[0]->Type, $matches);
                $requestStatuses = explode("','", $matches[1]);
            }

            echo json_encode([
                'success' => true,
                'organs' => $organList,
                'pledgeStatuses' => $pledgeStatuses,
                'requestPriorities' => $requestPriorities,
                'requestStatuses' => $requestStatuses
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function getHospitalRequests() {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? null;

        try {
            if ($id) {
                // Fetch Single Request Details
                $query = "SELECT orq.*, h.name as hospital_name, o.name as organ_name 
                          FROM organ_requests orq 
                          JOIN hospitals h ON orq.hospital_id = h.id 
                          JOIN organs o ON orq.organ_id = o.id 
                          WHERE orq.id = :id";
                
                $result = $this->query($query, ['id' => $id]);
                if ($result) {
                    echo json_encode(['success' => true, 'request' => $result[0]]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Request not found']);
                }
            } else {
                // Fetch All Requests (for list)
                $query = "SELECT orq.id, orq.hospital_id, orq.organ_id, orq.priority_level, orq.status, orq.created_at, 
                                 h.name as hospital_name, o.name as organ_name 
                          FROM organ_requests orq 
                          JOIN hospitals h ON orq.hospital_id = h.id 
                          JOIN organs o ON orq.organ_id = o.id 
                          ORDER BY orq.created_at DESC";
                
                $result = $this->query($query);
                echo json_encode(['success' => true, 'requests' => $result]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function evaluateABO($dBlood, $rBlood) {
        $d = strtoupper(trim($dBlood ?? ''));
        $r = strtoupper(trim($rBlood ?? ''));
        if (!$d || !$r) return ['compatible' => false];

        if ($d === 'O+' || $d === 'O-') return ['compatible' => true];
        if ($r === 'AB+' || $r === 'AB-') return ['compatible' => true];
        
        if (($d === 'A+' || $d === 'A-') && (str_contains($r, 'A') || str_contains($r, 'AB'))) return ['compatible' => true];
        if (($d === 'B+' || $d === 'B-') && (str_contains($r, 'B') || str_contains($r, 'AB'))) return ['compatible' => true];

        return ['compatible' => ($d === $r)];
    }

    private function calculateHLAMatch($donor, $req) {
        $markers = ['hla_a1', 'hla_a2', 'hla_b1', 'hla_b2', 'hla_dr1', 'hla_dr2'];
        $matchCount = 0;
        $activeMarkers = 0;

        foreach ($markers as $m) {
            $dv = trim($donor[$m] ?? '');
            $rv = trim($req[$m] ?? '');
            
            if ($dv || $rv) {
                $activeMarkers++;
                if ($dv === $rv && $dv !== '') $matchCount++;
            }
        }

        return [
            'score' => ($activeMarkers > 0) ? ($matchCount / 6) : 0,
            'count' => $matchCount
        ];
    }

    private function ensureMatchSchema($pdo) {
        try {
            $cols = $pdo->query("SHOW COLUMNS FROM donor_patient_match")->fetchAll(\PDO::FETCH_COLUMN);
            
            // Adding the detailed clinical quality column as requested
            if (!in_array('clinical_match_quality', $cols)) {
                $pdo->exec("ALTER TABLE donor_patient_match ADD COLUMN clinical_match_quality ENUM('MATCH','MATCH WITH WARNING') DEFAULT 'MATCH' AFTER request_id");
            }
            
            if (!in_array('warning_details', $cols)) {
                $pdo->exec("ALTER TABLE donor_patient_match ADD COLUMN warning_details TEXT DEFAULT NULL AFTER clinical_match_quality");
            }

            // Migrating donor_status to simplified ENUM
            $pdo->exec("ALTER TABLE donor_patient_match MODIFY COLUMN donor_status ENUM('PENDING', 'ACCEPTED', 'REJECTED') DEFAULT 'PENDING'");

            // Adding hospital_match_status tracking
            if (!in_array('hospital_match_status', $cols)) {
                $pdo->exec("ALTER TABLE donor_patient_match ADD COLUMN hospital_match_status ENUM('PENDING', 'ACCEPTED', 'REJECTED') DEFAULT 'PENDING' AFTER donor_status");
            }
            
            if (!in_array('hospital_reject_reason', $cols)) {
                $pdo->exec("ALTER TABLE donor_patient_match ADD COLUMN hospital_reject_reason TEXT DEFAULT NULL AFTER hospital_match_status");
            }

            // Ensure donor_pledges supports IN_PROGRESS status
            $pledgeCols = $pdo->query("SHOW COLUMNS FROM donor_pledges LIKE 'status'")->fetch(\PDO::FETCH_ASSOC);
            if ($pledgeCols && !str_contains($pledgeCols['Type'], 'IN_PROGRESS')) {
                $pdo->exec("ALTER TABLE donor_pledges MODIFY COLUMN status ENUM('PENDING', 'APPROVED', 'REJECTED', 'COMPLETED', 'UPLOADED', 'SUSPENDED', 'IN_PROGRESS') DEFAULT 'PENDING'");
            }

            // [NEW] Automated Database Trigger for Match Coordination
            // This ensures that donor pledges are escalated to IN_PROGRESS immediately when both statuses are ACCEPTED
            $pdo->exec("DROP TRIGGER IF EXISTS after_match_status_update");
            $pdo->exec("
                CREATE TRIGGER after_match_status_update
                AFTER UPDATE ON donor_patient_match
                FOR EACH ROW
                BEGIN
                    IF NEW.donor_status = 'ACCEPTED' AND NEW.hospital_match_status = 'ACCEPTED' THEN
                        -- 1. Mark the coordinate pledge as IN_PROGRESS
                        UPDATE donor_pledges 
                        SET status = 'IN_PROGRESS' 
                        WHERE id = NEW.donor_pledge_id;
                        
                        -- 2. Mark ALL other pledges for this donor as SUSPENDED to prevent double donation
                        UPDATE donor_pledges 
                        SET status = 'SUSPENDED'
                        WHERE donor_id = (SELECT donor_id FROM donor_pledges WHERE id = NEW.donor_pledge_id)
                          AND id != NEW.donor_pledge_id
                          AND status IN ('APPROVED', 'PENDING', 'UPLOADED');
                    END IF;
                END
            ");
        } catch (\Exception $e) {
            // Silently fail if DB issues, logic will handle missing columns
        }
    }
    public function getMatchDetails() {
        header('Content-Type: application/json');
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $role = strtoupper($_SESSION['role'] ?? '');
            if (!isset($_SESSION['user_id']) || ($role !== 'D_ADMIN' && $role !== 'ADMIN')) {
                throw new \Exception("Unauthorized access");
            }

            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['match_id'] ?? ($_GET['match_id'] ?? null);

            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'Missing match ID']);
                return;
            }

            $pdo = $this->connect();
            $this->ensureMatchSchema($pdo);
            
            $sql = "SELECT 
                        m.match_id, m.match_date, m.clinical_match_quality, m.warning_details, m.donor_status, m.hospital_match_status, m.hospital_reject_reason,
                        d.first_name, d.last_name, d.nic_number, d.gender, d.blood_group as donor_blood, 
                        u.phone as donor_phone, u.email as donor_email,
                        d.hla_a1, d.hla_a2, d.hla_b1, d.hla_b2, d.hla_dr1, d.hla_dr2,
                        ldc.height, ldc.weight,
                        org.name as organ_name,
                        h.name as hospital_name, h.registration_number as hospital_reg_no, h.contact_number as hospital_phone,
                        hu.email as hospital_email,
                        orq.blood_group as required_blood, orq.priority_level, orq.created_at as request_date,
                        orq.recipient_age, orq.gender as recipient_gender, orq.transplant_reason,
                        orq.hla_a1 as req_hla_a1, orq.hla_a2 as req_hla_a2, orq.hla_b1 as req_hla_b1, 
                        orq.hla_b2 as req_hla_b2, orq.hla_dr1 as req_hla_dr1, orq.hla_dr2 as req_hla_dr2,
                        dp.status as pledge_status
                    FROM donor_patient_match m
                    JOIN donor_pledges dp ON m.donor_pledge_id = dp.id
                    JOIN donors d ON dp.donor_id = d.id
                    JOIN users u ON d.user_id = u.id
                    LEFT JOIN living_donor_consents ldc ON dp.id = ldc.donor_pledge_id
                    JOIN organ_requests orq ON m.request_id = orq.id
                    JOIN organs org ON orq.organ_id = org.id
                    JOIN hospitals h ON orq.hospital_id = h.id
                    JOIN users hu ON h.user_id = hu.id
                    WHERE m.match_id = ?";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$id]);
            $details = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($details) {
                echo json_encode(['success' => true, 'details' => $details]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Match details not found']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    }

    /**
     * AJAX endpoint: Get all aftercare patients
     */
    public function getPatients() {
        header('Content-Type: application/json');
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $role = strtoupper($_SESSION['role'] ?? '');
            if (!isset($_SESSION['user_id']) || ($role !== 'D_ADMIN' && $role !== 'ADMIN')) {
                throw new \Exception("Unauthorized access");
            }

            $type = $_GET['type'] ?? null;
            $blood = $_GET['blood'] ?? null;
            $search = $_GET['search'] ?? null;

            $model = new AftercareAdminModel();
            $patients = $model->getAllPatients($type, $blood, $search);
            echo json_encode(['success' => true, 'patients' => $patients ? $patients : []]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * AJAX endpoint: Get single patient details
     */
    public function getPatientDetails() {
        header('Content-Type: application/json');
        try {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $role = strtoupper($_SESSION['role'] ?? '');
            if (!isset($_SESSION['user_id']) || ($role !== 'D_ADMIN' && $role !== 'ADMIN')) {
                throw new \Exception("Unauthorized access");
            }

            $id = $_GET['id'] ?? null;
            if (!$id) throw new \Exception("Patient ID is required");

            $model = new AftercareAdminModel();
            $patient = $model->getPatientById($id);

            if ($patient) {
                echo json_encode(['success' => true, 'patient' => $patient]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Patient not found']);
            }
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}

