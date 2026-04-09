<?php
/**
 * Migration Script - Make hospital_registration_no nullable
 * Run this file once in your browser to apply the migration
 */

declare(strict_types=1);

ini_set('display_errors', '1');
error_reporting(E_ALL);
set_time_limit(0);

header('Content-Type: text/html; charset=utf-8');

// Database connection settings
$host = 'localhost';
$dbname = 'life-connect';
$username = 'root';
$password = '';

try {
    echo "<p>Connecting to database...</p>";
    @ob_flush();
    @flush();

    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ]
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // If another connection is using the table, ALTER TABLE can wait indefinitely.
    // This makes it fail with a clear error instead of “loading forever”.
    $pdo->exec("SET SESSION lock_wait_timeout = 15");
    
    // Execute the migration
    $sql = "ALTER TABLE `aftercare_appointments` 
            MODIFY COLUMN `hospital_registration_no` varchar(50) DEFAULT NULL";

    echo "<p>Applying migration (ALTER TABLE)...</p>";
    @ob_flush();
    @flush();

    $pdo->exec($sql);
    
    echo "<h2 style='color: green;'>✓ Migration Successful!</h2>";
    echo "<p>The 'hospital_registration_no' column has been made nullable.</p>";
    echo "<p><a href='donor/aftercare'>Return to Aftercare Portal</a></p>";
    
} catch(PDOException $e) {
    echo "<h2 style='color: red;'>✗ Migration Failed</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
