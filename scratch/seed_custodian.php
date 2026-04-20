<?php
// Seed a fresh donor and custodian for testing
define('DBHOST','localhost');
define('DBNAME','life-connect');
define('DBUSER','root');
define('DBPASS','');

try {
    $con = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $donorNic = "200010101010";
    $custodianUsername = "test_custodian";
    $password = "password123";
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);

    // 1. Create Custodian User
    $stm = $con->prepare("INSERT INTO users (username, password_hash, role, status) VALUES (?, ?, 'CUSTODIAN', 'ACTIVE')");
    $stm->execute([$custodianUsername, $passwordHash]);
    $custodianUserId = $con->lastInsertId();

    // 2. Create Donor User (Placeholder)
    $stm = $con->prepare("INSERT INTO users (username, password_hash, role, status) VALUES (?, ?, 'DONOR', 'ACTIVE')");
    $stm->execute(['test_donor_101', $passwordHash]);
    $donorUserId = $con->lastInsertId();

    // 3. Create Donor
    $stm = $con->prepare("INSERT INTO donors (user_id, category_id, first_name, last_name, nic_number, gender, date_of_birth, address, district, divisional_secretariat, grama_niladhari_division, verification_status) VALUES (?, 1, 'John', 'TestDonor', ?, 'MALE', '1990-01-01', 'Test Address', 'Colombo', 'Colombo', 'Division A', 'APPROVED')");
    $stm->execute([$donorUserId, $donorNic]);
    $donorId = $con->lastInsertId();

    // 4. Create Custodian
    $stm = $con->prepare("INSERT INTO custodians (user_id, donor_id, relationship, name, nic_number, email, status) VALUES (?, ?, 'Brother', 'Custodian Admin', '901010101V', 'custodian_test@lifeconnect.com', 'APPROVED')");
    $stm->execute([$custodianUserId, $donorId]);

    // 5. Create Donation Case (Directly SUCCESSFUL for Appreciation testing)
    $caseNumber = "DC-2026-TEST";
    $stm = $con->prepare("INSERT INTO donation_cases (donor_id, death_declaration_id, case_number, donation_type, overall_status) VALUES (?, 0, ?, 'BODY', 'SUCCESSFUL')");
    $stm->execute([$donorId, $caseNumber]);

    echo "SUCCESS: Created Test Donor and Custodian.\n";
    echo "Login Username: $custodianUsername\n";
    echo "Login Password: $password\n";
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
