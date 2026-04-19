<?php
// Debug script to check donor search results
require_once __DIR__ . '/../app/Core/database.php';
require_once __DIR__ . '/../app/Models/HospitalModel.php';

// Mock session/environment
$hospitalId = 1; // Change if needed, check your hospitals table
$searchQuery = ''; 

$model = new HospitalModel();

echo "Hospital ID: $hospitalId\n";
$hospital = $model->query("SELECT registration_number FROM hospitals WHERE id = :id", [':id' => $hospitalId])[0] ?? null;
if (!$hospital) {
    die("Hospital not found with ID $hospitalId\n");
}
$regNo = $hospital->registration_number;
echo "Reg No: $regNo\n";

$params = [':hid' => $hospitalId];
$whereSearch = "";

$query = "SELECT 
                DISTINCT d.id,
                d.nic_number,
                d.first_name,
                d.last_name,
                d.blood_group,
                ua.Tests_done,
                ua.status as appt_status
            FROM donors d
            JOIN upcoming_appointments ua ON ua.donor_id = d.id
            WHERE ua.hospital_registration_no = :reg
                AND ua.Tests_done = 'yes'
                AND UPPER(TRIM(ua.status)) IN ('ACCEPTED', 'SCHEDULED', 'APPROVED', 'COMPLETED', 'SUCCESS')
                AND UPPER(TRIM(d.verification_status)) = 'APPROVED'";

echo "Running Query...\n";
$results = $model->query($query, [':reg' => $regNo]) ?: [];

echo "Results Count: " . count($results) . "\n";
foreach ($results as $r) {
    echo "ID: {$r->id} | Name: {$r->first_name} {$r->last_name} | Tests Done: {$r->Tests_done} | Status: {$r->appt_status}\n";
}

// Check if any donor exists for this hospital at all regardless of Tests_done
echo "\nChecking all appointments for this hospital:\n";
$all = $model->query("SELECT donor_id, Tests_done, status FROM upcoming_appointments WHERE hospital_registration_no = :reg", [':reg' => $regNo]);
foreach ($all as $a) {
    echo "Donor: {$a->donor_id} | Tests_done: {$a->Tests_done} | status: {$a->status}\n";
}
