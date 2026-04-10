<?php
/**
 * GENERATE TWO DUMMY ACCOUNTS WITH ALIVE DONORS (Consent Only):
 * 1. Body Donation Active Consent
 * 2. Organ Pledges Active Consent
 * 
 * This lets you test the 'Mark Dead' and subsequent flow from scratch.
 */

try {
    $dsn = "mysql:host=localhost;dbname=life-connect;charset=utf8mb4";
    $user = "root";
    $pass = "";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $body_nic = 'BODY99999V';
    $organ_nic = 'ORGAN9999V';

    $body_password = password_hash($body_nic, PASSWORD_DEFAULT);
    
    // Clear out old data for this NIC
    $pdo->exec("SET FOREIGN_KEY_CHECKS=0");
    $pdo->exec("DELETE FROM cadaver_data_sheets WHERE donation_case_id IN (SELECT id FROM donation_cases WHERE case_number LIKE 'DC-2026-%')");
    $pdo->exec("DELETE FROM sworn_statements WHERE donation_case_id IN (SELECT id FROM donation_cases WHERE case_number LIKE 'DC-2026-%')");
    $pdo->exec("DELETE FROM donation_cases WHERE case_number LIKE 'DC-2026-%'");
    $pdo->exec("DELETE FROM death_declarations WHERE declared_by_custodian_id IN (SELECT id FROM custodians WHERE nic_number IN ('BODY99999V', 'ORGAN9999V'))");
    $pdo->exec("DELETE FROM custodians WHERE nic_number IN ('BODY99999V', 'ORGAN9999V')");
    $pdo->exec("DELETE FROM users WHERE username IN ('$body_nic', 'DONORBODY99V', '$organ_nic', 'DONORORGAN99')");
    $pdo->exec("DELETE FROM donors WHERE nic_number IN ('DONORBODY99V', 'DONORORGAN99')");
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");

    // ==========================================
    // ACCOUNT 1: BODY DONATION DUMMY
    // ==========================================
    
    // Create Donor 
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email, phone, role, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute(['DONORBODY99V', $body_password, 'donorbody@test.com', '0770000001', 'DONOR', 'ACTIVE']);
    $donor_user_id = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO donors (user_id, nic_number, first_name, last_name, pledge_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$donor_user_id, 'DONORBODY99V', 'Body', 'DonorTest', 'DECEASED_BODY']);
    $donor_id_body = $pdo->lastInsertId();

    // Give Body Consent to MULTIPLE Medical Schools
    $stmt = $pdo->prepare("INSERT INTO body_donation_consents (donor_id, medical_school_id, status) VALUES (?, ?, ?)");
    $stmt->execute([$donor_id_body, 1, 'ACTIVE']); // Med Faculty 1
    $stmt->execute([$donor_id_body, 2, 'ACTIVE']); // Med Faculty 2
    $stmt->execute([$donor_id_body, 3, 'ACTIVE']); // Med Faculty 3

    // Create Custodian User
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email, phone, role, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$body_nic, $body_password, 'custodian_body@test.com', '0770000002', 'CUSTODIAN', 'ACTIVE']);
    $custodian_user_id = $pdo->lastInsertId();

    // Create Custodian
    $stmt = $pdo->prepare("INSERT INTO custodians (user_id, donor_id, relationship, custodian_number, name, nic_number, phone, email, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$custodian_user_id, $donor_id_body, 'Sibling', 1, 'Body Custodian Test', $body_nic, '0770000002', 'custodian_body@test.com', 'Body Test Street']);


    // ==========================================
    // ACCOUNT 2: ORGAN DONATION DUMMY
    // ==========================================
    $organ_password = password_hash($organ_nic, PASSWORD_DEFAULT);

    // Create Donor 
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email, phone, role, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute(['DONORORGAN99', $organ_password, 'donororgan@test.com', '0770000003', 'DONOR', 'ACTIVE']);
    $donor_user_id2 = $pdo->lastInsertId();

    $stmt = $pdo->prepare("INSERT INTO donors (user_id, nic_number, first_name, last_name, pledge_type) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$donor_user_id2, 'DONORORGAN99', 'Organ', 'DonorTest', 'DECEASED_ORGAN']);
    $donor_id_organ = $pdo->lastInsertId();

    // Give Organ Consent (Pledges) - Kidney
    $stmt = $pdo->prepare("INSERT INTO donor_pledges (donor_id, organ_id, status) VALUES (?, ?, ?)");
    $stmt->execute([$donor_id_organ, 1, 'ACTIVE']);

    // Create Custodian User
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email, phone, role, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$organ_nic, $organ_password, 'custodian_organ@test.com', '0770000004', 'CUSTODIAN', 'ACTIVE']);
    $custodian_user_id2 = $pdo->lastInsertId();

    // Create Custodian
    $stmt = $pdo->prepare("INSERT INTO custodians (user_id, donor_id, relationship, custodian_number, name, nic_number, phone, email, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$custodian_user_id2, $donor_id_organ, 'Child', 1, 'Organ Custodian Test', $organ_nic, '0770000004', 'custodian_organ@test.com', 'Organ Test Street']);

    echo "=================================================\n";
    echo "Dummy Accounts Created Successfully!\n";
    echo "=================================================\n\n";

    echo "1. BODY DONATION (Active Consent - ALIVE)\n";
    echo "Username/NIC : $body_nic\n";
    echo "Password     : $body_nic\n\n";

    echo "2. ORGAN DONATION (Active Consent - ALIVE)\n";
    echo "Username/NIC : $organ_nic\n";
    echo "Password     : $organ_nic\n\n";

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
