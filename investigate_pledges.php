<?php
// Investigation script for Donor Pledge Summary
define('ROOT', 'http://localhost/life-connect');
require_once 'app/core/Database.php';
require_once 'app/Models/Model.php';
require_once 'app/Models/DonorModel.php';

$mockDonorId = 1; // Adjust based on your test user

$donorModel = new \App\Models\DonorModel();

echo "--- Pledge Summary Test ---\n";
$summary = $donorModel->getPledgeSummary($mockDonorId);
print_r($summary);

echo "\n--- Pledged Organs Raw Test ---\n";
$pledges = $donorModel->getPledgedOrgans($mockDonorId);
print_r($pledges);

echo "\n--- Body Donation Check ---\n";
$bodyQuery = "SELECT * FROM body_donation_consents WHERE donor_id = :id AND status != 'WITHDRAWN'";
$body = $donorModel->query($bodyQuery, [':id' => $mockDonorId]);
print_r($body);
