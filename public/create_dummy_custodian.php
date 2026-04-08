<?php
$nic = '999999999V';
$password = password_hash($nic, PASSWORD_DEFAULT);
$donor_id = 2; // Using existing donor 2

try {
    $dsn = "mysql:host=localhost;dbname=life-connect;charset=utf8mb4";
    $user = "root";
    $pass = "";
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Delete if already exists
    $pdo->exec("DELETE FROM custodians WHERE nic_number = '$nic'");
    $pdo->exec("DELETE FROM users WHERE username = '$nic'");

    // Insert into users
    $stmt = $pdo->prepare("INSERT INTO users (username, password_hash, email, phone, role, status) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nic, $password, 'dummy_custodian@test.com', '0779999999', 'CUSTODIAN', 'ACTIVE']);
    $user_id = $pdo->lastInsertId();

    // Insert into custodians
    $stmt = $pdo->prepare("INSERT INTO custodians (user_id, donor_id, relationship, custodian_number, name, nic_number, phone, email, address) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $donor_id, 'Sibling', 1, 'Dummy Custodian', $nic, '0779999999', 'dummy_custodian@test.com', '123 Fake Street']);

    echo "Created Dummy Custodian successfully:\nUsername: $nic\nPassword: $nic\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
