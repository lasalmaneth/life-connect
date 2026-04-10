<?php
$pdo = new PDO('mysql:host=localhost;dbname=life-connect;charset=utf8mb4', 'root', '');
$hash = password_hash('abc', PASSWORD_DEFAULT);
$pdo->exec("INSERT INTO users (id, username, password_hash, role, status) VALUES (9000, 'med_school_1', '$hash', 'MEDICAL_SCHOOL', 'ACTIVE') ON DUPLICATE KEY UPDATE password_hash='$hash'");
$pdo->exec("INSERT INTO users (id, username, password_hash, role, status) VALUES (9001, 'med_school_2', '$hash', 'MEDICAL_SCHOOL', 'ACTIVE') ON DUPLICATE KEY UPDATE password_hash='$hash'");
$pdo->exec("INSERT INTO users (id, username, password_hash, role, status) VALUES (9002, 'med_school_3', '$hash', 'MEDICAL_SCHOOL', 'ACTIVE') ON DUPLICATE KEY UPDATE password_hash='$hash'");
$pdo->exec("INSERT INTO users (id, username, password_hash, role, status) VALUES (9100, 'hospital_1_user', '$hash', 'HOSPITAL', 'ACTIVE') ON DUPLICATE KEY UPDATE password_hash='$hash'");

$pdo->exec("INSERT INTO medical_schools (id, user_id, school_name, university_affiliation, ugc_accreditation_number, address, district, contact_person_name, contact_person_phone, verification_status) 
VALUES (1, 9000, 'Colombo Medical Faculty', 'Colombo', 'UGC-1', 'Colombo', 'Colombo', 'Dr. Perera', '0123456789', 'APPROVED') ON DUPLICATE KEY UPDATE id=id");

$pdo->exec("INSERT INTO medical_schools (id, user_id, school_name, university_affiliation, ugc_accreditation_number, address, district, contact_person_name, contact_person_phone, verification_status) 
VALUES (2, 9001, 'Kelaniya Medical Faculty', 'Kelaniya', 'UGC-2', 'Kelaniya', 'Gampaha', 'Dr. Silva', '0123456789', 'APPROVED') ON DUPLICATE KEY UPDATE id=id");

$pdo->exec("INSERT INTO medical_schools (id, user_id, school_name, university_affiliation, ugc_accreditation_number, address, district, contact_person_name, contact_person_phone, verification_status) 
VALUES (3, 9002, 'Sri J Med Faculty', 'Sri J', 'UGC-3', 'Nugegoda', 'Colombo', 'Dr. Fernando', '0123456789', 'APPROVED') ON DUPLICATE KEY UPDATE id=id");
echo "Inserted medical school\n";
