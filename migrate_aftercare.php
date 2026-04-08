<?php
/**
 * Migration Script - Make hospital_registration_no nullable
 * Run this file once in your browser to apply the migration
 */

// Database connection settings
$host = 'localhost';
$dbname = 'life-connect';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Execute the migration
    $sql = "ALTER TABLE `aftercare_appointments` 
            MODIFY COLUMN `hospital_registration_no` varchar(50) DEFAULT NULL";
    
    $pdo->exec($sql);
    
    echo "<h2 style='color: green;'>✓ Migration Successful!</h2>";
    echo "<p>The 'hospital_registration_no' column has been made nullable.</p>";
    echo "<p><a href='donor/aftercare'>Return to Aftercare Portal</a></p>";
    
} catch(PDOException $e) {
    echo "<h2 style='color: red;'>✗ Migration Failed</h2>";
    echo "<p>Error: " . $e->getMessage() . "</p>";
}
?>
