<?php
/**
 * Migration: Add WITHDRAW_REQUEST to users.status enum
 */

// Use existing database connection logic if possible, or define locally.
// Assuming we are in a scratch directory, we need to load the core or just connect manually for simplicity.

$host = 'localhost';
$user = 'root';
$pass = ''; // Default XAMPP pass is empty
$dbname = 'life-connect';

try {
    $con = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connecting to database...\n";

    $sql = "ALTER TABLE `users` MODIFY COLUMN `status` ENUM('PENDING','ACTIVE','SUSPENDED','WITHDRAW_REQUEST') DEFAULT 'PENDING'";
    $con->exec($sql);

    echo "SUCCESS: Added 'WITHDRAW_REQUEST' status to users table.\n";
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
