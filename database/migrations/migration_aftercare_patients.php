<?php
/**
 * Migration Script - Create aftercare_patients + recipient_patient tables
 * Run this file once in your browser to apply the migration.
 */

require_once __DIR__ . '/../../app/Core/config.php';

$host = defined('DBHOST') ? DBHOST : 'localhost';
$dbname = defined('DBNAME') ? DBNAME : 'life-connect';
$username = defined('DBUSER') ? DBUSER : 'root';
$password = defined('DBPASS') ? DBPASS : '';
$root = defined('ROOT') ? ROOT : '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "CREATE TABLE IF NOT EXISTS `aftercare_patients` (
        `id` int NOT NULL AUTO_INCREMENT,
        `registration_number` varchar(20) NOT NULL,
        `nic` varchar(20) NOT NULL,
        `full_name` varchar(255) NOT NULL,
        `patient_type` enum('RECIPIENT','DONOR') NOT NULL DEFAULT 'RECIPIENT',
        `hospital_registration_no` varchar(50) NOT NULL,
        `password_hash` varchar(255) NOT NULL,
        `must_change_password` tinyint(1) NOT NULL DEFAULT 1,
        `age` int DEFAULT NULL,
        `gender` varchar(20) DEFAULT NULL,
        `blood_group` varchar(10) DEFAULT NULL,
        `contact_details` varchar(255) DEFAULT NULL,
        `medical_details` text,
        `status` enum('ACTIVE','SUSPENDED') NOT NULL DEFAULT 'ACTIVE',
        `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_aftercare_reg` (`registration_number`),
        UNIQUE KEY `uniq_aftercare_nic` (`nic`),
        KEY `idx_aftercare_hosp` (`hospital_registration_no`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $sql2 = "CREATE TABLE IF NOT EXISTS `recipient_patient` (
        `id` int NOT NULL AUTO_INCREMENT,
        `registration_number` varchar(20) NOT NULL,
        `nic` varchar(20) NOT NULL,
        `full_name` varchar(255) NOT NULL,
        `hospital_registration_no` varchar(50) NOT NULL,
        `age` int DEFAULT NULL,
        `gender` varchar(20) DEFAULT NULL,
        `blood_group` varchar(10) DEFAULT NULL,
        `contact_details` varchar(255) DEFAULT NULL,
        `medical_details` text,
        `status` enum('ACTIVE','SUSPENDED') NOT NULL DEFAULT 'ACTIVE',
        `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (`id`),
        UNIQUE KEY `uniq_recipient_reg` (`registration_number`),
        UNIQUE KEY `uniq_recipient_nic` (`nic`),
        KEY `idx_recipient_hosp` (`hospital_registration_no`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4";

    $pdo->exec($sql);
    $pdo->exec($sql2);

    echo "<h2 style='color: green;'>✓ Migration Successful!</h2>";
    echo "<p>Tables <code>aftercare_patients</code> and <code>recipient_patient</code> are ready.</p>";
    $loginHref = $root ? rtrim($root, '/') . '/aftercare/login' : 'aftercare/login';
    echo "<p><a href='" . htmlspecialchars($loginHref) . "'>Go to Aftercare Login</a></p>";

} catch (PDOException $e) {
    echo "<h2 style='color: red;'>✗ Migration Failed</h2>";
    echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}
