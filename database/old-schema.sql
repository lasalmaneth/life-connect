-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 04, 2026 at 05:42 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `life-connect`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `user_id` int(11) NOT NULL,
  `admin_type` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','moderator') DEFAULT 'admin',
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `password`, `role`, `permissions`, `status`, `created_at`, `updated_at`, `last_login`) VALUES
(1, 'System Administrator', 'admin@lifeconnect.lk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin', NULL, 'active', '2025-10-22 18:50:19', '2025-10-22 18:50:19', NULL),
(2, 'User manage Admin', 'Admin@123@gmail.com', '$2y$10$Wn/r5Sz12G6xcMvxPFTNreHUrrPYkD4qFVHc.1wCABDoFif4X679O', 'admin', NULL, 'active', '2025-11-09 10:37:50', '2025-11-09 10:37:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `aftercare_appointments`
--

CREATE TABLE `aftercare_appointments` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` varchar(50) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `appointment_type` enum('Follow-up','Check-up','Emergency','Consultation') DEFAULT 'Follow-up',
  `description` text NOT NULL,
  `status` enum('Scheduled','Completed','Cancelled','Rescheduled') DEFAULT 'Scheduled',
  `hospital_registration_no` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `aftercare_appointments`
--

INSERT INTO `aftercare_appointments` (`appointment_id`, `patient_id`, `patient_name`, `appointment_date`, `appointment_type`, `description`, `status`, `hospital_registration_no`, `created_at`, `updated_at`) VALUES
(1, '2001XXXXXXX', 'D. Perera', '2025-11-05 09:00:00', 'Follow-up', 'Monthly checkup', 'Scheduled', NULL, '2026-01-21 15:16:23', '2026-01-21 15:16:23'),
(2, '1999XXXXXXX', 'S. Lakshan', '2025-12-10 10:30:00', 'Check-up', 'Annual review', 'Scheduled', NULL, '2026-01-21 15:16:23', '2026-02-25 07:41:01'),
(3, '200331312807', 'Namal', '2026-01-03 21:47:00', 'Check-up', 'check up for full body', 'Completed', NULL, '2026-01-21 16:17:34', '2026-01-22 07:17:53');

-- --------------------------------------------------------

--
-- Table structure for table `deceased_organ_donors`
--

CREATE TABLE `deceased_organ_donors` (
  `donor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `nic` varchar(20) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text NOT NULL,
  `district` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `kin_name` varchar(255) NOT NULL,
  `kin_relationship` varchar(100) NOT NULL,
  `kin_phone` varchar(20) DEFAULT NULL,
  `kin_email` varchar(255) DEFAULT NULL,
  `kin_address` text DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `organs_selected` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`organs_selected`)),
  `consent_given` tinyint(1) DEFAULT 0,
  `status` enum('ACTIVE','PENDING','SUSPENDED','REJECTED') DEFAULT 'PENDING',
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donation_admin`
--

CREATE TABLE `donation_admin` (
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `donor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `date_of_birth` date NOT NULL,
  `blood_group` varchar(5) NOT NULL,
  `nic_number` varchar(20) NOT NULL,
  `nic_image_path` varchar(255) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `grama_niladhari_division` varchar(150) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `divisional_secretariat` varchar(150) DEFAULT NULL,
  `verification_status` enum('Pending','Verified','Rejected') DEFAULT 'Pending',
  `registration_date` date DEFAULT curdate(),
  `consent_status` enum('Valid','Expired') DEFAULT 'Valid',
  `consent_date` date DEFAULT curdate(),
  `password` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','pending') DEFAULT 'pending',
  `last_login` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`donor_id`, `user_id`, `first_name`, `last_name`, `gender`, `date_of_birth`, `blood_group`, `nic_number`, `nic_image_path`, `contact_number`, `email`, `address`, `grama_niladhari_division`, `district`, `divisional_secretariat`, `verification_status`, `registration_date`, `consent_status`, `consent_date`, `password`, `status`, `last_login`) VALUES
(3, 23, 'NoneDonor02', '', 'Female', '2000-11-06', '', '200367873312', NULL, '0123456789', 'amal12@gmail.com', '201/ Colombo road, Gampaha', 'Gampaha', 'Gampaha', 'Gampaha', 'Pending', '2025-11-09', 'Valid', '2025-11-09', NULL, 'pending', NULL),
(4, 24, 'LiveDonor02', '', 'Female', '1998-01-01', 'A-', '200345678823', NULL, '0123456789', 'amal12@gmail.com', '201/ Colombo road, Gampaha', 'Gampaha', 'Gampaha', 'Gampaha', 'Pending', '2025-11-09', 'Valid', '2025-11-09', NULL, 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `donor_organ`
--

CREATE TABLE `donor_organ` (
  `donor_organ_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `organ_id` int(11) NOT NULL,
  `status` enum('Pending','In Progress','Approved','Cancelled','Completed') DEFAULT 'Pending',
  `pledged_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donor_organ`
--

INSERT INTO `donor_organ` (`donor_organ_id`, `donor_id`, `organ_id`, `status`, `pledged_date`) VALUES
(41, 4, 3, 'Pending', '2025-11-10'),
(42, 4, 1, 'Pending', '2025-11-10');

-- --------------------------------------------------------

--
-- Table structure for table `financial_donor`
--

CREATE TABLE `financial_donor` (
  `donor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `nic` varchar(20) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text NOT NULL,
  `district` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `financial_donor`
--

INSERT INTO `financial_donor` (`donor_id`, `user_id`, `full_name`, `email`, `dob`, `nic`, `gender`, `address`, `district`, `phone`, `registration_date`) VALUES
(1, 1, 'Emily Rose', 'donutLizard36@khasov.tech', '2001-12-31', '200331312806', 'Male', '50 532wfqehfeqrfqerferff', 'Colombo', '0717868767', '2025-10-23 03:35:55'),
(2, 2, 'hfgskdvjadfjdfghf', '2023is040@stu.ucsc.cmb.ac.lk', '2002-08-09', '200331312807', 'Male', 'qwdsfghryut', 'Colombo', '0717868768', '2025-10-23 04:01:27'),
(3, 3, 'ert4676t7ryt', 'donutLizard36@khasov.tech', '2001-05-08', '200331312809', 'Male', '3455tuyrjtehftsgdrsfd', 'Matale', '0867884838', '2025-10-23 04:08:11'),
(4, 4, 'sahasnaj', 'donutLizard36@khasov.tech', '2004-01-06', '200451313123', 'Female', '152 mirigama', 'Gampaha', '0717868768', '2025-10-23 04:48:02'),
(10, 13, 'Sewmini', 'amal12@gmail.com', '2000-11-15', '200367901139', 'Female', '201/ Colombo road, Gampaha', 'Kalutara', '0123456789', '2025-11-08 07:08:08'),
(11, 14, 'Three', 'amal12@gmail.com', '1994-09-15', '200367901143', 'Male', '201/ Colombo road, Gampaha', 'Matale', '0123456789', '2025-11-08 13:59:08'),
(12, 15, 'four', 'amal12@gmail.com', '1994-06-16', '200367901165', 'Male', '201/ Colombo road, Gampaha', 'Kandy', '0123456789', '2025-11-08 14:08:10'),
(13, 16, 'Five', 'amal12@gmail.com', '1998-03-18', '200367901189', 'Female', '201/ Colombo road, Gampaha', 'Jaffna', '0123456789', '2025-11-08 14:11:03'),
(14, 17, 'Six', 'amal12@gmail.com', '1998-11-26', '200367901142', 'Female', '201/ Colombo road, Gampaha', 'Kandy', '0123456789', '2025-11-08 14:19:22'),
(15, 18, 'Seven', 'amal12@gmail.com', '2020-07-03', '200367901111', 'Female', '201/ Colombo road, Gampaha', 'Colombo', '0123456789', '2025-11-08 16:38:16'),
(16, 19, 'nine', 'amal12@gmail.com', '1988-11-02', '200890892231', 'Female', '201/ Colombo road, Gampaha', 'Gampaha', '0123456789', '2025-11-09 09:24:38'),
(17, 20, 'ten', 'amal12@gmail.com', '1997-01-02', '200267541143', 'Female', '201/ Colombo road, Gampaha', 'Gampaha', '0123456789', '2025-11-09 10:18:03');

-- --------------------------------------------------------

--
-- Table structure for table `hospital`
--

CREATE TABLE `hospital` (
  `registration_no` varchar(100) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `h_name` varchar(150) NOT NULL,
  `h_email` varchar(150) DEFAULT NULL,
  `contact_number` varchar(10) DEFAULT NULL,
  `h_location` varchar(255) DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `cmo_name` varchar(100) DEFAULT NULL,
  `cmo_nic` varchar(20) DEFAULT NULL,
  `verification_status` enum('Pending','Verified','Rejected') DEFAULT 'Pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospital`
--

INSERT INTO `hospital` (`registration_no`, `user_id`, `h_name`, `h_email`, `contact_number`, `h_location`, `district`, `type`, `cmo_name`, `cmo_nic`, `verification_status`) VALUES
('HOSP001', NULL, 'National Hospital of Sri Lanka', 'admin@nhsl.gov.lk', '0112345678', 'Colombo 07', 'Colombo', 'Government', 'Dr. John Smith', '123456789V', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `legal_custodian`
--

CREATE TABLE `legal_custodian` (
  `custodian_id` int(11) NOT NULL,
  `deceased_donor_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `relationship` varchar(100) DEFAULT NULL,
  `nic` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `legal_custodian`
--

INSERT INTO `legal_custodian` (`custodian_id`, `deceased_donor_id`, `name`, `phone`, `email`, `relationship`, `nic`) VALUES
(1, 2, 'Sewmini', '0123456789', 'amal12@gmail.com', 'Child', '');

-- --------------------------------------------------------

--
-- Table structure for table `live_donor`
--

CREATE TABLE `live_donor` (
  `live_donor_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `live_donor`
--

INSERT INTO `live_donor` (`live_donor_id`, `donor_id`) VALUES
(1, 4);

-- --------------------------------------------------------

--
-- Table structure for table `live_organ_donor`
--

CREATE TABLE `live_organ_donor` (
  `donor_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `nic` varchar(20) NOT NULL,
  `gender` enum('Male','Female','Other') NOT NULL,
  `address` text NOT NULL,
  `district` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `medical_history` text DEFAULT NULL,
  `organs_selected` text DEFAULT NULL,
  `consent_given` tinyint(1) DEFAULT 0,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `matching`
--

CREATE TABLE `matching` (
  `match_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `donor_organ_id` int(11) NOT NULL,
  `match_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `medical_school`
--

CREATE TABLE `medical_school` (
  `school_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `institution_name` varchar(150) NOT NULL,
  `university_affiliation` varchar(150) DEFAULT NULL,
  `ugc_accreditation_number` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `district` varchar(100) DEFAULT NULL,
  `contact_person_name` varchar(100) DEFAULT NULL,
  `contact_person_title` varchar(100) DEFAULT NULL,
  `contact_email` varchar(150) DEFAULT NULL,
  `contact_phone` varchar(20) DEFAULT NULL,
  `verification_status` enum('Pending','Verified','Rejected') DEFAULT 'Pending',
  `registration_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `non_donor`
--

CREATE TABLE `non_donor` (
  `non_donor_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `opt_out_date` date DEFAULT curdate(),
  `reason` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `non_donor`
--

INSERT INTO `non_donor` (`non_donor_id`, `donor_id`, `opt_out_date`, `reason`) VALUES
(1, 3, '2025-11-09', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `date_sent` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organ`
--

CREATE TABLE `organ` (
  `organ_id` int(11) NOT NULL,
  `organ_name` varchar(100) NOT NULL,
  `organ_description` text DEFAULT NULL,
  `organ_icon` varchar(10) DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `who_can_benefit` text DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organ`
--

INSERT INTO `organ` (`organ_id`, `organ_name`, `organ_description`, `organ_icon`, `benefits`, `who_can_benefit`, `is_available`) VALUES
(1, 'Kidneys', 'Kidneys filter waste and excess fluid from the blood. Kidney donation is one of the most common organ donations.', '🫘', 'Save lives of patients with kidney failure, Most common organ donation with high success rates, Recipients can live without dialysis', 'People with chronic kidney disease, end-stage renal disease, or kidney failure', 1),
(3, 'A Part of Liver', 'The liver performs many essential functions including detoxification and protein synthesis. Liver donation can save lives.', '🫀', 'Save lives of patients with liver failure, Liver has regenerative capabilities, High success rate in transplantation', 'Patients with cirrhosis, liver cancer, hepatitis, or acute liver failure', 1),
(4, 'A part of Lungs', 'Lungs are responsible for gas exchange, bringing oxygen into the body and removing carbon dioxide.', '🫁', 'Restore normal breathing for recipients, Life-saving for end-stage lung disease patients, Improve quality of life dramatically', 'People with cystic fibrosis, COPD, pulmonary fibrosis, or other severe lung diseases', 1),
(5, 'Eyes (Corneas)', 'The cornea is the clear front part of the eye. Cornea donation can restore vision.', '👁️', 'Restore vision for blind patients, High success rate, Simple and safe procedure', 'People with corneal blindness, corneal scarring, or corneal diseases', 1);

-- --------------------------------------------------------

--
-- Table structure for table `organ_request`
--

CREATE TABLE `organ_request` (
  `request_id` int(11) NOT NULL,
  `registration_no` varchar(100) NOT NULL,
  `organ_type` varchar(100) NOT NULL,
  `urgency` varchar(20) NOT NULL DEFAULT 'medium',
  `notes` text DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `request_date` date DEFAULT curdate(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organ_request`
--

INSERT INTO `organ_request` (`request_id`, `registration_no`, `organ_type`, `urgency`, `notes`, `status`, `request_date`, `created_at`, `updated_at`) VALUES
(1, 'HOSP001', 'lung', 'high', 'urgent immediately', 'Pending', '2026-02-17', '2026-02-17 06:50:39', '2026-02-17 06:50:49'),
(2, 'HOSP001', 'heart', 'medium', 'Block in the heart\'s right canal upper thread with minor injuries. ', 'Pending', '2026-02-17', '2026-02-17 06:51:50', '2026-02-17 06:51:50');

-- --------------------------------------------------------

--
-- Table structure for table `organ_requests`
--

CREATE TABLE `organ_requests` (
  `request_id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `organ_type` varchar(50) NOT NULL,
  `urgency` varchar(20) NOT NULL,
  `notes` text DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `request_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organ_requests`
--

INSERT INTO `organ_requests` (`request_id`, `hospital_id`, `organ_type`, `urgency`, `notes`, `status`, `request_date`, `created_at`, `updated_at`) VALUES
(2, 1, 'liver', 'high', 'Liver failure case', 'Pending', '2024-01-16', '2025-10-22 23:49:54', '2025-10-22 23:49:54'),
(3, 1, 'heart', 'medium', 'Heart transplant needed', 'Pending', '2024-01-17', '2025-10-22 23:49:54', '2025-10-22 23:49:54'),
(5, 1, 'kidney', 'urgent', 'Patient needs immediate kidney transplant', 'Pending', '2024-01-15', '2025-10-23 00:01:49', '2025-10-23 00:01:49'),
(6, 1, 'liver', 'high', 'Liver failure case', 'Pending', '2024-01-16', '2025-10-23 00:01:49', '2025-10-23 00:01:49'),
(7, 1, 'heart', 'medium', 'Heart transplant needed', 'Pending', '2024-01-17', '2025-10-23 00:01:49', '2025-10-23 00:01:49'),
(8, 1, 'heart', 'high', 'higher', 'Pending', '2025-10-23', '2025-10-23 01:47:35', '2025-10-23 01:47:35');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `user_type` varchar(50) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recipients`
--

CREATE TABLE `recipients` (
  `recipient_id` int(11) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `organ_received` varchar(50) NOT NULL,
  `surgery_date` date NOT NULL,
  `treatment_notes` text DEFAULT NULL,
  `hospital_registration_no` varchar(20) NOT NULL,
  `status` varchar(20) DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipients`
--

INSERT INTO `recipients` (`recipient_id`, `nic`, `name`, `organ_received`, `surgery_date`, `treatment_notes`, `hospital_registration_no`, `status`, `created_at`, `updated_at`) VALUES
(1, '199912345678', 'John Doe', 'kidney', '2024-01-10', 'Post-surgery recovery going well', 'HOSP001', 'Active', '2025-10-22 23:49:54', '2025-10-23 04:12:04'),
(2, '199876543210', 'Jane Smith', 'liver', '2024-01-12', 'Patient responding well to treatment', 'HOSP001', 'Active', '2025-10-22 23:49:54', '2025-10-23 04:12:04'),
(3, '199811122233', 'Bob Johnson', 'heart', '2024-01-14', 'Heart transplant successful', 'HOSP001', 'Active', '2025-10-22 23:49:54', '2025-10-23 04:12:04'),
(4, '199912345678', 'John Doe', 'kidney', '2024-01-10', 'Post-surgery recovery going well', 'HOSP001', 'Active', '2025-10-23 00:01:49', '2025-10-23 04:12:04'),
(5, '199876543210', 'Jane Smith', 'liver', '2024-01-12', 'Patient responding well to treatment', 'HOSP001', 'Active', '2025-10-23 00:01:49', '2025-10-23 04:12:04'),
(6, '199811122233', 'Bob Johnson', 'heart', '2024-01-14', 'Heart transplant successful', 'HOSP001', 'Active', '2025-10-23 00:01:49', '2025-10-23 04:12:04'),
(7, '200331312806', 'eweff', 'liver', '2025-10-23', 'cere', 'HOSP001', 'Active', '2025-10-23 01:47:01', '2025-10-23 04:12:04'),
(8, '200331312806', 'Ranil Wickramasinghe', 'liver', '2025-10-23', 'cere', 'HOSP001', 'Active', '2025-10-23 01:55:42', '2026-02-17 06:56:13');

-- --------------------------------------------------------

--
-- Table structure for table `remember_tokens`
--

CREATE TABLE `remember_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `success_stories`
--

CREATE TABLE `success_stories` (
  `story_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `success_date` date NOT NULL,
  `hospital_registration_no` varchar(20) NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `success_stories`
--

INSERT INTO `success_stories` (`story_id`, `title`, `description`, `success_date`, `hospital_registration_no`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Successful Kidney Transplant', 'Patient John Doe successfully received a kidney transplant and is recovering well. The surgery was performed without complications.', '2024-01-10', 'HOSP001', 'Pending', '2025-10-22 23:49:54', '2025-10-23 03:55:26'),
(2, 'Liver Transplant Success', 'Jane Smith received a liver transplant and is now leading a normal life. Her recovery has been remarkable.', '2024-01-12', 'HOSP001', 'Pending', '2025-10-22 23:49:54', '2025-10-23 03:55:26'),
(3, 'Heart Transplant Miracle', 'Bob Johnson received a heart transplant and is now able to participate in normal activities. The procedure was a complete success.', '2024-01-14', 'HOSP001', 'Pending', '2025-10-22 23:49:54', '2025-10-23 03:55:26'),
(4, 'Successful Kidney Transplant', 'Patient John Doe successfully received a kidney transplant and is recovering well. The surgery was performed without complications.', '2024-01-10', 'HOSP001', 'Pending', '2025-10-23 00:01:49', '2025-10-23 03:55:26'),
(5, 'Liver Transplant Success', 'Jane Smith received a liver transplant and is now leading a normal life. Her recovery has been remarkable.', '2024-01-12', 'HOSP001', 'Pending', '2025-10-23 00:01:49', '2025-10-23 03:55:26'),
(6, 'Heart Transplant Miracle', 'Bob Johnson received a heart transplant and is now able to participate in normal activities. The procedure was a complete success.', '2024-01-14', 'HOSP001', 'Pending', '2025-10-23 00:01:49', '2025-10-23 03:55:26'),
(12, 'Kidney transplant reveal', 'Yesterday National Hospital in Galle revealed a good session....', '2025-11-04', 'HOSP001', 'Pending', '2025-11-13 14:33:55', '2025-11-13 14:33:55'),
(13, 'Kidney transplant reveal', 'Yesterday National Hospital in Galle revealed a good session....', '2025-11-04', 'HOSP001', 'Pending', '2025-11-13 14:34:02', '2025-11-13 14:34:02');

-- --------------------------------------------------------

--
-- Table structure for table `support_requests`
--

CREATE TABLE `support_requests` (
  `id` int(11) NOT NULL,
  `patient_nic` varchar(20) NOT NULL,
  `patient_name` varchar(100) NOT NULL,
  `patient_type` enum('Post Donation Patient','Recipient Patient','Live Donor') NOT NULL,
  `reason` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('PENDING','IN REVIEW','APPROVED','REJECTED') DEFAULT 'PENDING',
  `submitted_date` date DEFAULT curdate(),
  `reviewed_date` date DEFAULT NULL,
  `reviewed_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `support_requests`
--

INSERT INTO `support_requests` (`id`, `patient_nic`, `patient_name`, `patient_type`, `reason`, `description`, `status`, `submitted_date`, `reviewed_date`, `reviewed_by`, `created_at`, `updated_at`) VALUES
(1, '2001XXXXXXX', 'M. Jayasinghe', 'Post Donation Patient', 'Travel cost support', 'Need financial support for travel to hospital for follow-up appointments', 'REJECTED', '2025-10-14', '2026-01-21', 'Aftercare Admin', '2026-01-21 11:32:22', '2026-01-21 11:36:40'),
(2, '1998XXXXXXX', 'R. Fernando', 'Recipient Patient', 'Test fee support', 'Requesting support for blood test fees', 'APPROVED', '2025-10-13', '2026-02-24', 'Aftercare Admin', '2026-01-21 11:32:22', '2026-02-24 09:08:25'),
(3, '200331312806', 'Lasal', 'Post Donation Patient', 'travel', 'I need pickme fares', 'APPROVED', '2026-01-21', '2026-01-21', 'Aftercare Admin', '2026-01-21 11:35:57', '2026-01-21 15:00:32'),
(4, '200331313806', 'Mahinda Rajapaksha', 'Live Donor', 'Needed some funds to do some medical tests', 'I am willing to do SAP+ Test to verify matching procedure with the recipient patient', 'APPROVED', '2026-01-21', '2026-01-22', 'Aftercare Admin', '2026-01-21 15:03:37', '2026-01-22 08:11:41'),
(5, '200567813288', 'Lasith', 'Recipient Patient', '3r2cr3', '3tv5ygu76ghytf', 'APPROVED', '2026-01-22', '2026-01-22', 'Aftercare Admin', '2026-01-22 07:12:43', '2026-01-22 07:13:06'),
(6, '200331312706', 'Umesh', 'Post Donation Patient', 'travel costs', 'needed to reimburse the travel costs', 'PENDING', '2026-02-24', NULL, NULL, '2026-02-24 09:09:06', '2026-02-24 09:09:06');

-- --------------------------------------------------------

--
-- Table structure for table `tribute`
--

CREATE TABLE `tribute` (
  `tribute_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `user_password` varchar(255) NOT NULL,
  `role` enum('LiveDonor','DeceasedDonor','FinancialDonor','NonDonor','Hospital','UserMgtAdmin','FinanceAdmin','DonationAdmin','AftercareAdmin','RecipientPatient','MedicalSchool','SystemAdmin') NOT NULL,
  `status` enum('ACTIVE','PENDING','SUSPENDED','REJECTED') DEFAULT 'PENDING',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `user_name`, `user_password`, `role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'lasal', '$2y$10$JhAx.a9i5zpo7Ag25p8b5e/0XLNkk9ornjqyRY3o6NzYUE9.P45bC', 'FinancialDonor', 'ACTIVE', '2025-10-23 03:35:55', '2025-10-23 03:35:55'),
(2, 'sahasna', '$2y$10$o/fE1g388iKFS0hWlI7DoubO22N8.he08bLR8ilkHcYfV3araYrJy', 'FinancialDonor', 'ACTIVE', '2025-10-23 04:01:27', '2025-10-23 04:01:27'),
(3, 'reema', '$2y$10$FsEP.7iMAuEVAa9WL9Xwhu8m2IAh0O9lZ.kFABb9rh7ATd7/VENBK', 'FinancialDonor', 'ACTIVE', '2025-10-23 04:08:11', '2025-10-23 04:08:11'),
(4, 'ucsc', '$2y$10$G0hYPvovxTVZgoNlcI9bDe7WcR2CZPb/oxL5HrTTbf/9UKZaBnae2', '', 'ACTIVE', '2025-10-23 04:48:02', '2025-11-08 06:43:49'),
(5, 'User Management Admin', '$2y$10$E1HNmZtbu6Aj4u0Ru3DNpeKzduzmcOqb9ckoHdimq5uquwhly.LkG', 'UserMgtAdmin', 'ACTIVE', '2025-11-09 10:40:48', '2025-11-09 12:33:52'),
(6, 'Donation Admin', '$2y$10$E1HNmZtbu6Aj4u0Ru3DNpeKzduzmcOqb9ckoHdimq5uquwhly.LkG', 'DonationAdmin', 'ACTIVE', '2025-11-09 11:48:58', '2025-11-09 12:35:31'),
(7, 'Aftercare Admin', '$2y$10$E1HNmZtbu6Aj4u0Ru3DNpeKzduzmcOqb9ckoHdimq5uquwhly.LkG', 'AftercareAdmin', 'ACTIVE', '2025-11-09 11:50:05', '2025-11-09 14:38:15'),
(8, 'Finance Admin', '$2y$10$E1HNmZtbu6Aj4u0Ru3DNpeKzduzmcOqb9ckoHdimq5uquwhly.LkG', 'FinanceAdmin', 'ACTIVE', '2025-11-09 11:50:48', '2025-11-09 12:35:48'),
(9, 'LiveDonor01', '$2y$10$hKUVl70J3uNjp2KqVcf85OlNuHlVkh9p7jpZFL9PnT/OxLZ773mDa', 'LiveDonor', 'ACTIVE', '2025-11-09 12:01:26', '2025-11-09 12:01:26'),
(10, 'NoneDonor01', '$2y$10$hKUVl70J3uNjp2KqVcf85OlNuHlVkh9p7jpZFL9PnT/OxLZ773mDa', 'NonDonor', 'ACTIVE', '2025-11-09 14:34:42', '2025-11-09 14:34:42'),
(11, 'Hospital01', '$2y$10$hKUVl70J3uNjp2KqVcf85OlNuHlVkh9p7jpZFL9PnT/OxLZ773mDa', 'Hospital', 'ACTIVE', '2025-11-09 14:37:56', '2025-11-09 14:37:56'),
(12, 'MedicalSchool01', '$2y$10$hKUVl70J3uNjp2KqVcf85OlNuHlVkh9p7jpZFL9PnT/OxLZ773mDa', 'MedicalSchool', 'ACTIVE', '2025-11-09 14:39:40', '2025-11-09 14:39:40'),
(23, 'NoneDonor02', '$2y$10$OH3xAVj./aXm4q.n3OhTnuxBzYeCSO49MDrLxOkWGyJbamrrW4FVu', 'NonDonor', 'PENDING', '2025-11-09 14:49:49', '2025-11-09 14:49:49'),
(24, 'LiveDonor02', '$2y$10$iU1cJDRpGW643JFa1z1AIuiHswhdi4lbcVa6z0737vDWoD.MjLbW2', 'LiveDonor', 'PENDING', '2025-11-09 14:52:29', '2025-11-09 14:52:29');

-- --------------------------------------------------------

--
-- Table structure for table `witness`
--

CREATE TABLE `witness` (
  `witness_id` int(11) NOT NULL,
  `live_donor_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `nic_number` varchar(20) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `witness`
--

INSERT INTO `witness` (`witness_id`, `live_donor_id`, `name`, `nic_number`, `contact_number`, `address`) VALUES
(1, 1, 'one', '200367901100', '0123456789', NULL),
(2, 1, 'two', '200367901111', '0123456789', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `aftercare_appointments`
--
ALTER TABLE `aftercare_appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `appointment_date` (`appointment_date`),
  ADD KEY `status` (`status`),
  ADD KEY `hospital_registration_no` (`hospital_registration_no`);

--
-- Indexes for table `deceased_organ_donors`
--
ALTER TABLE `deceased_organ_donors`
  ADD PRIMARY KEY (`donor_id`),
  ADD UNIQUE KEY `nic` (`nic`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_nic` (`nic`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_kin_name` (`kin_name`);

--
-- Indexes for table `donation_admin`
--
ALTER TABLE `donation_admin`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`donor_id`),
  ADD UNIQUE KEY `nic_number` (`nic_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_verification_status` (`verification_status`);

--
-- Indexes for table `donor_organ`
--
ALTER TABLE `donor_organ`
  ADD PRIMARY KEY (`donor_organ_id`),
  ADD KEY `donor_id` (`donor_id`),
  ADD KEY `organ_id` (`organ_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `financial_donor`
--
ALTER TABLE `financial_donor`
  ADD PRIMARY KEY (`donor_id`),
  ADD UNIQUE KEY `nic` (`nic`);

--
-- Indexes for table `hospital`
--
ALTER TABLE `hospital`
  ADD PRIMARY KEY (`registration_no`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `legal_custodian`
--
ALTER TABLE `legal_custodian`
  ADD PRIMARY KEY (`custodian_id`),
  ADD KEY `deceased_donor_id` (`deceased_donor_id`);

--
-- Indexes for table `live_donor`
--
ALTER TABLE `live_donor`
  ADD PRIMARY KEY (`live_donor_id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `live_organ_donor`
--
ALTER TABLE `live_organ_donor`
  ADD PRIMARY KEY (`donor_id`),
  ADD UNIQUE KEY `nic` (`nic`);

--
-- Indexes for table `matching`
--
ALTER TABLE `matching`
  ADD PRIMARY KEY (`match_id`),
  ADD KEY `request_id` (`request_id`),
  ADD KEY `donor_organ_id` (`donor_organ_id`);

--
-- Indexes for table `medical_school`
--
ALTER TABLE `medical_school`
  ADD PRIMARY KEY (`school_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `non_donor`
--
ALTER TABLE `non_donor`
  ADD PRIMARY KEY (`non_donor_id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `organ`
--
ALTER TABLE `organ`
  ADD PRIMARY KEY (`organ_id`);

--
-- Indexes for table `organ_request`
--
ALTER TABLE `organ_request`
  ADD PRIMARY KEY (`request_id`),
  ADD KEY `registration_no` (`registration_no`);

--
-- Indexes for table `organ_requests`
--
ALTER TABLE `organ_requests`
  ADD PRIMARY KEY (`request_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indexes for table `recipients`
--
ALTER TABLE `recipients`
  ADD PRIMARY KEY (`recipient_id`);

--
-- Indexes for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_token` (`token`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indexes for table `success_stories`
--
ALTER TABLE `success_stories`
  ADD PRIMARY KEY (`story_id`);

--
-- Indexes for table `support_requests`
--
ALTER TABLE `support_requests`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tribute`
--
ALTER TABLE `tribute`
  ADD PRIMARY KEY (`tribute_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `user_name` (`user_name`),
  ADD KEY `idx_user_name` (`user_name`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `witness`
--
ALTER TABLE `witness`
  ADD PRIMARY KEY (`witness_id`),
  ADD KEY `live_donor_id` (`live_donor_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `aftercare_appointments`
--
ALTER TABLE `aftercare_appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `deceased_organ_donors`
--
ALTER TABLE `deceased_organ_donors`
  MODIFY `donor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `donor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `donor_organ`
--
ALTER TABLE `donor_organ`
  MODIFY `donor_organ_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `financial_donor`
--
ALTER TABLE `financial_donor`
  MODIFY `donor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `legal_custodian`
--
ALTER TABLE `legal_custodian`
  MODIFY `custodian_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `live_donor`
--
ALTER TABLE `live_donor`
  MODIFY `live_donor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `live_organ_donor`
--
ALTER TABLE `live_organ_donor`
  MODIFY `donor_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `matching`
--
ALTER TABLE `matching`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medical_school`
--
ALTER TABLE `medical_school`
  MODIFY `school_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `non_donor`
--
ALTER TABLE `non_donor`
  MODIFY `non_donor_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organ`
--
ALTER TABLE `organ`
  MODIFY `organ_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `organ_request`
--
ALTER TABLE `organ_request`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `organ_requests`
--
ALTER TABLE `organ_requests`
  MODIFY `request_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recipients`
--
ALTER TABLE `recipients`
  MODIFY `recipient_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `remember_tokens`
--
ALTER TABLE `remember_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `success_stories`
--
ALTER TABLE `success_stories`
  MODIFY `story_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `support_requests`
--
ALTER TABLE `support_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tribute`
--
ALTER TABLE `tribute`
  MODIFY `tribute_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `witness`
--
ALTER TABLE `witness`
  MODIFY `witness_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `aftercare_appointments`
--
ALTER TABLE `aftercare_appointments`
  ADD CONSTRAINT `aftercare_appointments_ibfk_1` FOREIGN KEY (`hospital_registration_no`) REFERENCES `hospital` (`registration_no`) ON DELETE SET NULL;

--
-- Constraints for table `deceased_organ_donors`
--
ALTER TABLE `deceased_organ_donors`
  ADD CONSTRAINT `deceased_organ_donors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `donation_admin`
--
ALTER TABLE `donation_admin`
  ADD CONSTRAINT `donation_admin_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `admin` (`user_id`);

--
-- Constraints for table `donors`
--
ALTER TABLE `donors`
  ADD CONSTRAINT `donors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `donor_organ`
--
ALTER TABLE `donor_organ`
  ADD CONSTRAINT `donor_organ_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`donor_id`),
  ADD CONSTRAINT `donor_organ_ibfk_2` FOREIGN KEY (`organ_id`) REFERENCES `organ` (`organ_id`);

--
-- Constraints for table `hospital`
--
ALTER TABLE `hospital`
  ADD CONSTRAINT `hospital_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `live_donor`
--
ALTER TABLE `live_donor`
  ADD CONSTRAINT `live_donor_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`donor_id`);

--
-- Constraints for table `medical_school`
--
ALTER TABLE `medical_school`
  ADD CONSTRAINT `medical_school_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `non_donor`
--
ALTER TABLE `non_donor`
  ADD CONSTRAINT `non_donor_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`donor_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
