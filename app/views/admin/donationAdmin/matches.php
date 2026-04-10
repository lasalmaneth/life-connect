<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/../../../core/Database.php';
require_once __DIR__ . '/../../../core/config.php';

$pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    switch ($_POST['action']) {
        case 'get_matches':
            getMatches($pdo);
            break;
        case 'update_match_status':
            updateMatchStatus($pdo);
            break;
        case 'get_match_details':
            getMatchDetails($pdo);
            break;
        case 'run_matching':
            runMatchingAlgorithm($pdo);
            break;
        default:
            echo json_encode(array('error' => 'Invalid action'));
            break;
    }
    exit();
}

function getMatches($pdo) {
    try {
        $sql = "SELECT m.match_id, m.match_date, m.status as match_status, m.warning_details,
                       dp.id as pledge_id, d.first_name as donor_name, d.last_name, d.blood_group as donor_blood_group,
                       orq.id as request_id, orq.blood_group as required_blood_group, orq.priority_level,
                       org.name as organ_name, h.name as hospital_name
                FROM donor_patient_match m
                JOIN donor_pledges dp ON m.donor_pledge_id = dp.id
                JOIN donors d ON dp.donor_id = d.id
                JOIN organ_requests orq ON m.request_id = orq.id
                JOIN organs org ON orq.organ_id = org.id
                JOIN hospitals h ON orq.hospital_id = h.id
                ORDER BY m.match_date DESC";
        $matches = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(array('success' => true, 'matches' => $matches));
    } catch (PDOException $e) {
        echo json_encode(array('error' => 'Database error: ' . $e->getMessage()));
    }
}

function runMatchingAlgorithm($pdo) {
    try {
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
                
                // For Rh factor simplicity in comparison
                $isDonorO = strpos($dBlood, 'O') !== false;
                $isDonorA = strpos($dBlood, 'A') !== false && strpos($dBlood, 'B') === false;
                $isDonorB = strpos($dBlood, 'B') !== false && strpos($dBlood, 'A') === false;
                $isDonorAB = strpos($dBlood, 'AB') !== false;

                if ($organId == 1 || $organId == 2) { // Kidney or Liver
                    if (empty($rBlood) || empty($dBlood)) {
                        // Skip if blood group not proper yet
                        continue; 
                    }
                    if ($isDonorO) { // Universal
                        $isMatch = true;
                    } else if ($isDonorA && (strpos($rBlood, 'A') !== false)) {
                        $isMatch = true;
                    } else if ($isDonorB && (strpos($rBlood, 'B') !== false)) {
                        $isMatch = true;
                    } else if ($isDonorAB && strpos($rBlood, 'AB') !== false) {
                        $isMatch = true;
                    }
                } else if ($organId == 3) { // Bone Marrow
                    // HLA is more important. For logic simulation -> match.
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
                    $chk->execute(array($donor['pledge_id'], $req['request_id']));
                    
                    if ($chk->rowCount() == 0) {
                        $ins = $pdo->prepare("INSERT INTO donor_patient_match (donor_pledge_id, request_id, status, warning_details) VALUES (?, ?, ?, ?)");
                        $ins->execute(array($donor['pledge_id'], $req['request_id'], $status, $warning));
                        
                        // Update request to MATCHED
                        $pdo->prepare("UPDATE organ_requests SET status = 'MATCHED' WHERE id = ?")->execute(array($req['request_id']));
                        $matchesCreated++;
                    }
                }
            }
        }
        
        $pdo->commit();
        echo json_encode(array(
            'success' => true, 
            'message' => 'Matching algorithm completed successfully',
            'matches_created' => $matchesCreated
        ));
    } catch (Exception $e) {
        $pdo->rollBack();
        echo json_encode(array('error' => 'Database error: ' . $e->getMessage()));
    }
}

function updateMatchStatus($pdo) {} // Placeholder
function getMatchDetails($pdo) {} // Placeholder

ob_end_flush();
?>