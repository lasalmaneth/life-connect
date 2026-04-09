<?php

// Add this to the VERY TOP of your match.php
ob_start(); // Start output buffering
error_reporting(E_ALL);
ini_set('display_errors', 1);


// Include database connection
require_once __DIR__ . '/../../core/Database.php';
require_once __DIR__ . '/../../core/config.php';

// Simple database connection
$pdo = new PDO("mysql:host=".DBHOST.";dbname=".DBNAME, DBUSER, DBPASS);

// Handle AJAX requests
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
    // Get filters from request
    $search = isset($_POST['search']) ? $_POST['search'] : '';
    $status = isset($_POST['status']) ? $_POST['status'] : '';
    $organ = isset($_POST['organ']) ? $_POST['organ'] : '';
    $urgency = isset($_POST['urgency']) ? $_POST['urgency'] : '';
    
    // Build query with filters
    $sql = "SELECT 
                m.match_id,
                m.match_date,
                m.request_id,
                m.donor_organ_id,
                d.donor_id,
                CONCAT(d.first_name, ' ', d.last_name) as donor_name,
                d.blood_group as donor_blood_group,
                d.contact_number as donor_contact,
                d.email as donor_email,
                orq.request_id,
                orq.blood_group as required_blood_group,
                orq.urgency_level,
                orq.status,
                org.organ_name,
                h.registration_no,
                h.h_name as hospital_name,
                h.h_location as hospital_location,
                h.district as hospital_district
            FROM matching m
            LEFT JOIN donors d ON m.donor_id = d.donor_id
            LEFT JOIN organ_request orq ON m.request_id = orq.request_id
            LEFT JOIN organ org ON orq.organ_id = org.organ_id
            LEFT JOIN hospital h ON orq.registration_no = h.registration_no
            WHERE 1=1";
    
    $params = array();
    
    if (!empty($search)) {
        $sql .= " AND (d.first_name LIKE ? OR d.last_name LIKE ? OR h.h_name LIKE ? OR m.match_id LIKE ?)";
        $searchTerm = "%$search%";
        array_push($params, $searchTerm, $searchTerm, $searchTerm, $searchTerm);
    }
    
    if (!empty($status)) {
        $sql .= " AND orq.status = ?";
        $params[] = $status;
    }
    
    if (!empty($organ)) {
        $sql .= " AND org.organ_name = ?";
        $params[] = $organ;
    }
    
    if (!empty($urgency)) {
        $sql .= " AND orq.urgency_level = ?";
        $params[] = $urgency;
    }
    
    $sql .= " ORDER BY m.match_date DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $matches = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode(array('success' => true, 'matches' => $matches));
}

function updateMatchStatus($pdo) {
    if (!isset($_POST['match_id']) || !isset($_POST['status'])) {
        echo json_encode(array('error' => 'Missing required fields'));
        return;
    }
    
    $match_id = $_POST['match_id'];
    $status = $_POST['status'];
    $notes = isset($_POST['notes']) ? $_POST['notes'] : '';
    
    try {
        // Since there's no status column in matching table, update organ_request status
        // First get the request_id from the match
        $getRequestSql = "SELECT request_id FROM matching WHERE match_id = ?";
        $getRequestStmt = $pdo->prepare($getRequestSql);
        $getRequestStmt->execute(array($match_id));
        $request = $getRequestStmt->fetch(PDO::FETCH_ASSOC);
        
        if ($request) {
            $sql = "UPDATE organ_request SET status = ? WHERE request_id = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array($status, $request['request_id']));
            
            echo json_encode(array('success' => true, 'message' => 'Status updated successfully'));
        } else {
            echo json_encode(array('error' => 'Match not found'));
        }
    } catch (PDOException $e) {
        echo json_encode(array('error' => 'Database error: ' . $e->getMessage()));
    }
}

function getMatchDetails($pdo) {
    if (!isset($_POST['match_id'])) {
        echo json_encode(array('error' => 'Match ID required'));
        return;
    }
    
    $match_id = $_POST['match_id'];
    
    $sql = "SELECT 
                m.match_id,
                m.match_date,
                m.request_id,
                m.donor_organ_id,
                d.donor_id,
                CONCAT(d.first_name, ' ', d.last_name) as donor_name,
                d.gender as donor_gender,
                d.date_of_birth as donor_dob,
                d.blood_group as donor_blood_group,
                d.contact_number as donor_contact,
                d.email as donor_email,
                d.address as donor_address,
                d.district as donor_district,
                d.grama_niladhari_division,
                d.divisional_secretariat,
                orq.request_id,
                orq.blood_group as required_blood_group,
                orq.urgency_level,
                orq.gender as recipient_gender,
                orq.date_of_birth as recipient_dob,
                orq.district as recipient_district,
                orq.status,
                orq.request_date,
                org.organ_name,
                h.registration_no,
                h.h_name as hospital_name,
                h.h_location as hospital_location,
                h.district as hospital_district,
                h.contact_number as hospital_contact,
                h.h_email as hospital_email,
                h.cmo_name,
                h.cmo_nic
            FROM matching m
            LEFT JOIN donors d ON m.donor_id = d.donor_id
            LEFT JOIN organ_request orq ON m.request_id = orq.request_id
            LEFT JOIN organ org ON orq.organ_id = org.organ_id
            LEFT JOIN hospital h ON orq.registration_no = h.registration_no
            WHERE m.match_id = ?";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($match_id));
    $match = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($match) {
        echo json_encode(array('success' => true, 'match' => $match));
    } else {
        echo json_encode(array('error' => 'Match not found'));
    }
}

// Matching Algorithm Functions
function runMatchingAlgorithm($pdo) {
    try {
        $pdo->beginTransaction();
        
        // Find potential matches
        $sql = "
            SELECT DISTINCT
                do.donor_organ_id,
                orq.request_id,
                do.donor_id
            FROM donor_organ do
            INNER JOIN organ_request orq ON do.organ_id = orq.organ_id 
                AND do.blood_group = orq.blood_group
                AND do.status = 'available'
            WHERE orq.status IN ('pending', 'active')
            AND NOT EXISTS (
                SELECT 1 FROM matching m 
                WHERE m.donor_organ_id = do.donor_organ_id 
                AND m.request_id = orq.request_id
            )
        ";
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute();
        $potentialMatches = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $matchesCreated = 0;
        
        // Create matches
        foreach ($potentialMatches as $match) {
            $insertSql = "
                INSERT INTO matching (donor_organ_id, request_id, donor_id, match_date)
                VALUES (?, ?, ?, NOW())
            ";
            
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->execute(array(
                $match['donor_organ_id'],
                $match['request_id'],
                $match['donor_id']
            ));
            
            $matchesCreated++;
            
            // Update organ request status to 'matched'
            $updateRequestSql = "
                UPDATE organ_request 
                SET status = 'MATCHED' 
                WHERE request_id = ?
            ";
            $updateRequestStmt = $pdo->prepare($updateRequestSql);
            $updateRequestStmt->execute(array($match['request_id']));
            
            // Update donor organ status to 'matched'
            $updateDonorSql = "
                UPDATE donor_organ 
                SET status = 'MATCHED' 
                WHERE donor_organ_id = ?
            ";
            $updateDonorStmt = $pdo->prepare($updateDonorSql);
            $updateDonorStmt->execute(array($match['donor_organ_id']));
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

function autoMatchNewDonor($pdo, $donorOrganId) {
    $sql = "
        INSERT INTO matching (donor_organ_id, request_id, donor_id, match_date)
        SELECT 
            do.donor_organ_id,
            orq.request_id,
            do.donor_id,
            NOW()
        FROM donor_organ do
        INNER JOIN organ_request orq ON do.organ_id = orq.organ_id 
            AND do.blood_group = orq.blood_group
            AND do.status = 'available'
        WHERE do.donor_organ_id = ?
        AND orq.status IN ('pending', 'active')
        AND NOT EXISTS (
            SELECT 1 FROM matching m 
            WHERE m.donor_organ_id = do.donor_organ_id 
            AND m.request_id = orq.request_id
        )
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($donorOrganId));
    
    $matchesCreated = $stmt->rowCount();
    
    // Update status for matched records
    if ($matchesCreated > 0) {
        $updateRequestSql = "
            UPDATE organ_request 
            SET status = 'MATCHED' 
            WHERE request_id IN (
                SELECT request_id FROM matching 
                WHERE donor_organ_id = ?
            )
        ";
        $updateRequestStmt = $pdo->prepare($updateRequestSql);
        $updateRequestStmt->execute(array($donorOrganId));
        
        $updateDonorSql = "
            UPDATE donor_organ 
            SET status = 'MATCHED' 
            WHERE donor_organ_id = ?
        ";
        $updateDonorStmt = $pdo->prepare($updateDonorSql);
        $updateDonorStmt->execute(array($donorOrganId));
    }
    
    return $matchesCreated;
}

function autoMatchNewRequest($pdo, $requestId) {
    $sql = "
        INSERT INTO matching (donor_organ_id, request_id, donor_id, match_date)
        SELECT 
            do.donor_organ_id,
            orq.request_id,
            do.donor_id,
            NOW()
        FROM organ_request orq
        INNER JOIN donor_organ do ON do.organ_id = orq.organ_id 
            AND do.blood_group = orq.blood_group
            AND do.status = 'available'
        WHERE orq.request_id = ?
        AND orq.status IN ('pending', 'active')
        AND NOT EXISTS (
            SELECT 1 FROM matching m 
            WHERE m.donor_organ_id = do.donor_organ_id 
            AND m.request_id = orq.request_id
        )
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute(array($requestId));
    
    $matchesCreated = $stmt->rowCount();
    
    // Update status for matched records
    if ($matchesCreated > 0) {
        $updateRequestSql = "
            UPDATE organ_request 
            SET status = 'MATCHED' 
            WHERE request_id = ?
        ";
        $updateRequestStmt = $pdo->prepare($updateRequestSql);
        $updateRequestStmt->execute(array($requestId));
        
        $updateDonorSql = "
            UPDATE donor_organ 
            SET status = 'MATCHED' 
            WHERE donor_organ_id IN (
                SELECT donor_organ_id FROM matching 
                WHERE request_id = ?
            )
        ";
        $updateDonorStmt = $pdo->prepare($updateDonorSql);
        $updateDonorStmt->execute(array($requestId));
    }
    
    return $matchesCreated;
}
ob_end_flush(); // Send the output at the end
?>