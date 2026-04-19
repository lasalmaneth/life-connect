<?php
require '../app/Core/config.php';

try {
    $db = new PDO("mysql:host=" . DBHOST . ";dbname=" . DBNAME, DBUSER, DBPASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $email = 'test_kidney_all@test.com';
    $username = 'test_kidney_all';
    $password = password_hash('password123', PASSWORD_DEFAULT);

    // 1. Create User
    $stmt = $db->prepare("DELETE FROM users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    
    $stmt = $db->prepare("INSERT INTO users (username, email, password_hash, role, status) VALUES (?, ?, ?, 'CUSTODIAN', 'ACTIVE')");
    $stmt->execute([$username, $email, $password]);
    $userId = $db->lastInsertId();
    echo "User created ID: $userId<br>";

    // 2. Create Donor
    $nic = '999999999V';
    $db->prepare("DELETE FROM donors WHERE nic_number = ?")->execute([$nic]);
    $stmt = $db->prepare("INSERT INTO donors (user_id, first_name, last_name, nic_number, gender, date_of_birth, pledge_type) VALUES (?, 'Testing', 'Kidney Plus', ?, 'Male', '1980-01-01', 'ORGAN_ONLY')");
    $stmt->execute([$userId, $nic]);
    $donorId = $db->lastInsertId();
    echo "Donor created ID: $donorId<br>";

    // 3. Create Custodian
    $db->prepare("DELETE FROM custodians WHERE user_id = ?")->execute([$userId]);
    $stmt = $db->prepare("INSERT INTO custodians (user_id, donor_id, name, relationship, phone, email) VALUES (?, ?, 'Testing Kidney Plus', 'Sibling', '0771234567', ?)");
    $stmt->execute([$userId, $donorId, $email]);
    echo "Custodian created<br>";

    // 4. Create Pledges (Kidney after death: 9, Cornea: 4, Heart Valves: 7)
    $db->prepare("DELETE FROM donor_pledges WHERE donor_id = ?")->execute([$donorId]);
    foreach ([9, 4, 7] as $oid) {
        $stmt = $db->prepare("INSERT INTO donor_pledges (donor_id, organ_id, pledge_date, status, signed_form_path) VALUES (?, ?, '2025-01-01', 'APPROVED', 'dummy.pdf')");
        $stmt->execute([$donorId, $oid]);
    }
    echo "Pledges created<br>";

    echo "<b>SUCCESS! You can now log in with test_kidney_all / password123</b>";

} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
