-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 10, 2026 at 06:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `staff_id` varchar(20) NOT NULL,
  `designation` varchar(100) NOT NULL,
  `contact_number` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`user_id`, `first_name`, `last_name`, `staff_id`, `designation`, `contact_number`) VALUES
(1, 'Reema', 'Sahly', 'LC-ADM-101', 'User Management Administrator', '011-200-1101'),
(2, 'Sewmini', 'Chanchala', 'LC-ADM-102', 'Financial Administrator', '011-200-1102'),
(3, 'Lasal', 'Jayasinghe', 'LC-ADM-103', 'Aftercare Administrator', '011-200-1103'),
(4, 'Sahasna', 'Samarawickrama', 'LC-ADM-104', 'Donation Administrator', '011-200-1104');

-- --------------------------------------------------------

--
-- Table structure for table `aftercare_appointments`
--

CREATE TABLE `aftercare_appointments` (
  `appointment_id` int(11) NOT NULL,
  `patient_id` varchar(20) NOT NULL COMMENT 'Used for NIC reference',
  `patient_name` varchar(255) NOT NULL,
  `hospital_registration_no` varchar(50) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `appointment_type` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Scheduled','Completed','Cancelled') DEFAULT 'Scheduled',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `aftercare_patients`
--

CREATE TABLE `aftercare_patients` (
  `id` int(11) NOT NULL,
  `registration_number` varchar(20) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `patient_type` enum('RECIPIENT','DONOR') NOT NULL DEFAULT 'RECIPIENT',
  `hospital_registration_no` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `must_change_password` tinyint(1) NOT NULL DEFAULT 1,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `contact_details` varchar(255) DEFAULT NULL,
  `medical_details` text DEFAULT NULL,
  `status` enum('ACTIVE','SUSPENDED') NOT NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `after_death_consents`
--

CREATE TABLE `after_death_consents` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `suitability_any` tinyint(1) DEFAULT 1,
  `is_restricted` tinyint(1) DEFAULT 0,
  `religion` varchar(100) DEFAULT NULL,
  `special_instructions` text DEFAULT NULL,
  `preferred_hospital_id` int(11) DEFAULT NULL,
  `witness_name` varchar(255) DEFAULT NULL,
  `witness_nic` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `after_death_consents`
--

INSERT INTO `after_death_consents` (`id`, `donor_id`, `suitability_any`, `is_restricted`, `religion`, `special_instructions`, `preferred_hospital_id`, `witness_name`, `witness_nic`, `created_at`) VALUES
(3, 8043, 1, 0, '', '', NULL, '', '', '2026-04-09 04:58:55');

-- --------------------------------------------------------

--
-- Table structure for table `body_donation_consents`
--

CREATE TABLE `body_donation_consents` (
  `id` int(11) NOT NULL,
  `status` enum('ACTIVE','WITHDRAWN','PENDING') DEFAULT 'ACTIVE',
  `donor_id` int(11) NOT NULL,
  `medical_school_id` int(11) DEFAULT NULL,
  `religion` varchar(100) DEFAULT NULL,
  `special_requests` text DEFAULT NULL,
  `responsible_person` varchar(255) DEFAULT NULL,
  `responsible_contact` varchar(20) DEFAULT NULL,
  `transport_arrangement` text DEFAULT NULL,
  `witness1_name` varchar(255) NOT NULL,
  `witness1_nic` varchar(20) NOT NULL,
  `witness1_phone` varchar(15) NOT NULL,
  `witness1_address` text NOT NULL,
  `witness2_name` varchar(255) NOT NULL,
  `witness2_nic` varchar(20) NOT NULL,
  `witness2_phone` varchar(15) NOT NULL,
  `witness2_address` text NOT NULL,
  `consent_date` timestamp NULL DEFAULT current_timestamp(),
  `withdrawal_reason` text DEFAULT NULL,
  `withdrawal_pdf_path` varchar(255) DEFAULT NULL,
  `withdrawal_date` datetime DEFAULT NULL,
  `flag_reason` text DEFAULT NULL,
  `flagged_at` datetime DEFAULT NULL,
  `flagged_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `body_donation_consents`
--

INSERT INTO `body_donation_consents` (`id`, `status`, `donor_id`, `medical_school_id`, `religion`, `special_requests`, `responsible_person`, `responsible_contact`, `transport_arrangement`, `witness1_name`, `witness1_nic`, `witness1_phone`, `witness1_address`, `witness2_name`, `witness2_nic`, `witness2_phone`, `witness2_address`, `consent_date`, `withdrawal_reason`, `withdrawal_pdf_path`, `withdrawal_date`, `flag_reason`, `flagged_at`, `flagged_by`) VALUES
(9020, 'ACTIVE', 9020, 1, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', '', '', '', '2026-04-10 11:55:51', NULL, NULL, NULL, NULL, NULL, NULL),
(9021, 'ACTIVE', 9020, 2, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', '', '', '', '2026-04-10 11:55:51', NULL, NULL, NULL, NULL, NULL, NULL),
(9022, 'ACTIVE', 9020, 3, NULL, NULL, NULL, NULL, NULL, '', '', '', '', '', '', '', '', '2026-04-10 11:55:51', NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `body_usage_logs`
--

CREATE TABLE `body_usage_logs` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `medical_school_id` int(11) NOT NULL,
  `usage_type` varchar(100) NOT NULL COMMENT 'e.g., Anatomy, Research, Surgery Training',
  `description` text DEFAULT NULL,
  `usage_date` date NOT NULL,
  `disposal_method` varchar(100) DEFAULT NULL COMMENT 'e.g., Burial, Cremation, Returned to Kin',
  `disposal_date` date DEFAULT NULL,
  `status` enum('IN_USE','DISPOSED') DEFAULT 'IN_USE',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cadaver_data_sheets`
--

CREATE TABLE `cadaver_data_sheets` (
  `id` int(11) NOT NULL,
  `donation_case_id` int(11) NOT NULL,
  `case_institution_status_id` int(11) NOT NULL,
  `form_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'All form fields as JSON' CHECK (json_valid(`form_data`)),
  `pdf_path` varchar(255) DEFAULT NULL,
  `status` enum('DRAFT','GENERATED','SIGNED','SUBMITTED') DEFAULT 'DRAFT',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cadaver_data_sheets`
--

INSERT INTO `cadaver_data_sheets` (`id`, `donation_case_id`, `case_institution_status_id`, `form_data`, `pdf_path`, `status`, `created_at`) VALUES
(3, 10, 12, '{\"custodian_name\":\"Body Custodian Test\",\"custodian_nic\":\"BODY99999V\",\"custodian_address\":\"Body Test Street\",\"custodian_relationship\":\"Sibling\",\"custodian_phone\":\"0770000002\",\"donor_religion\":\"f\",\"place_of_death\":\"g\",\"race\":\"f\",\"occupation\":\"ggggggggggggg\",\"birth_place\":\"egg\",\"past_medical_history\":\"g\",\"past_surgical_history\":\"g\",\"other_diseases\":\"g\",\"relations_name\":[],\"relations_rel\":[],\"relations_nic\":[]}', NULL, 'DRAFT', '2026-04-10 15:18:11');

-- --------------------------------------------------------

--
-- Table structure for table `case_custodian_approvals`
--

CREATE TABLE `case_custodian_approvals` (
  `id` int(11) NOT NULL,
  `death_declaration_id` int(11) NOT NULL,
  `custodian_id` int(11) NOT NULL,
  `status` enum('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
  `approval_document_path` varchar(255) DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `case_documents`
--

CREATE TABLE `case_documents` (
  `id` int(11) NOT NULL,
  `case_id` int(11) NOT NULL,
  `document_type` varchar(100) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `status` enum('MISSING','UPLOADED','UNDER_REVIEW','VERIFIED','REJECTED') DEFAULT 'MISSING',
  `rejection_message` text DEFAULT NULL,
  `uploaded_at` timestamp NULL DEFAULT NULL,
  `category` enum('MANDATORY','CONDITIONAL','ADDITIONAL') DEFAULT 'MANDATORY',
  `is_required_by_triage` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `case_institution_requests`
--

CREATE TABLE `case_institution_requests` (
  `id` int(11) NOT NULL,
  `donation_case_id` int(11) NOT NULL,
  `institution_type` enum('MEDICAL_SCHOOL','HOSPITAL') NOT NULL,
  `institution_id` int(11) NOT NULL,
  `status` enum('DRAFT','SUBMITTED','PROVISIONALLY_ACCEPTED','DOCUMENTS_REQUIRED','PHYSICAL_EXAM_PENDING','APPROVED','REJECTED','WITHDRAWN') DEFAULT 'DRAFT',
  `reject_reason` text DEFAULT NULL,
  `submitted_at` timestamp NULL DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `case_institution_status`
--

CREATE TABLE `case_institution_status` (
  `id` int(11) NOT NULL,
  `donation_case_id` int(11) NOT NULL,
  `institution_type` enum('MEDICAL_SCHOOL','HOSPITAL','EYE_BANK') NOT NULL,
  `institution_id` int(11) NOT NULL COMMENT 'FK to medical_schools.id or hospitals.id',
  `track` enum('BODY','ORGAN','CORNEA') NOT NULL COMMENT 'Separates body/organ/cornea flows',
  `attempt_order` tinyint(4) NOT NULL COMMENT 'Sequence: 1st, 2nd, 3rd attempt',
  `is_current` tinyint(1) DEFAULT 0 COMMENT 'Only 1 active per case per track at a time',
  `custodian_action` enum('NOT_CONTACTED','CONTACTED','SUBMITTED') DEFAULT 'NOT_CONTACTED',
  `request_status` enum('PENDING','ACCEPTED','REJECTED') DEFAULT 'PENDING',
  `request_action_reason` text DEFAULT NULL,
  `request_action_at` datetime DEFAULT NULL,
  `request_action_by` int(11) DEFAULT NULL,
  `document_status` enum('NOT_STARTED','PENDING_REVIEW','NEED_MORE_DOCS','ACCEPTED','REJECTED') DEFAULT 'NOT_STARTED',
  `document_action_reason` text DEFAULT NULL,
  `document_action_at` datetime DEFAULT NULL,
  `document_action_by` int(11) DEFAULT NULL,
  `final_exam_status` enum('AWAITING','ACCEPTED','REJECTED') DEFAULT 'AWAITING',
  `final_exam_reason` text DEFAULT NULL,
  `final_exam_notes` text DEFAULT NULL,
  `final_exam_at` datetime DEFAULT NULL,
  `final_exam_by` int(11) DEFAULT NULL,
  `custodian_decline_reason` text DEFAULT NULL,
  `custodian_decline_date` datetime DEFAULT NULL,
  `institution_status` enum('PENDING','UNDER_REVIEW','ACCEPTED','REJECTED') DEFAULT 'PENDING',
  `rejection_message` text DEFAULT NULL COMMENT 'Institution reason for rejection',
  `submission_date` datetime DEFAULT NULL,
  `response_date` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `case_institution_status`
--

INSERT INTO `case_institution_status` (`id`, `donation_case_id`, `institution_type`, `institution_id`, `track`, `attempt_order`, `is_current`, `custodian_action`, `request_status`, `request_action_reason`, `request_action_at`, `request_action_by`, `document_status`, `document_action_reason`, `document_action_at`, `document_action_by`, `final_exam_status`, `final_exam_reason`, `final_exam_notes`, `final_exam_at`, `final_exam_by`, `custodian_decline_reason`, `custodian_decline_date`, `institution_status`, `rejection_message`, `submission_date`, `response_date`, `notes`, `created_at`, `updated_at`) VALUES
(11, 9, 'MEDICAL_SCHOOL', 2, 'BODY', 1, 1, 'NOT_CONTACTED', 'ACCEPTED', NULL, '2026-04-10 19:19:12', 9001, 'NOT_STARTED', NULL, NULL, NULL, 'AWAITING', NULL, NULL, NULL, NULL, NULL, NULL, 'ACCEPTED', NULL, NULL, NULL, NULL, '2026-04-10 13:48:55', '2026-04-10 13:51:33'),
(12, 10, 'MEDICAL_SCHOOL', 1, 'BODY', 0, 1, 'NOT_CONTACTED', 'PENDING', NULL, NULL, NULL, 'PENDING_REVIEW', NULL, '2026-04-10 20:48:42', NULL, 'AWAITING', NULL, NULL, NULL, NULL, NULL, NULL, 'ACCEPTED', NULL, NULL, NULL, NULL, '2026-04-10 15:13:10', '2026-04-10 15:18:42');

-- --------------------------------------------------------

--
-- Table structure for table `consent_records`
--

CREATE TABLE `consent_records` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `consent_type` varchar(100) NOT NULL,
  `consent_text` text NOT NULL,
  `consent_given` tinyint(1) NOT NULL DEFAULT 1,
  `consent_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consent_templates`
--

CREATE TABLE `consent_templates` (
  `id` int(11) NOT NULL,
  `consent_type` varchar(100) NOT NULL,
  `template_text` text NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `effective_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consent_withdrawals`
--

CREATE TABLE `consent_withdrawals` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `nic_number` varchar(20) NOT NULL,
  `dob` date NOT NULL,
  `address` text NOT NULL,
  `contact_number` varchar(20) NOT NULL,
  `prev_consent_date` date DEFAULT NULL,
  `organization` varchar(255) DEFAULT NULL,
  `witness1_name` varchar(255) DEFAULT NULL,
  `witness1_nic` varchar(20) DEFAULT NULL,
  `witness2_name` varchar(255) DEFAULT NULL,
  `witness2_nic` varchar(20) DEFAULT NULL,
  `signed_form_path` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'PENDING_UPLOAD',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `organ_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consent_withdrawals`
--

INSERT INTO `consent_withdrawals` (`id`, `donor_id`, `full_name`, `nic_number`, `dob`, `address`, `contact_number`, `prev_consent_date`, `organization`, `witness1_name`, `witness1_nic`, `witness2_name`, `witness2_nic`, `signed_form_path`, `status`, `created_at`, `organ_id`) VALUES
(4, 8043, 'Donor 001', '200367901131', '2003-06-27', '201/ Colombo road, Gampaha', '0000000000', NULL, NULL, 'five', '200367901131', 'six', '200367901131', 'uploads/withdrawals/withdrawal_8043_1775738861.pdf', 'COMPLETED', '2026-04-09 11:47:53', 2),
(5, 8043, 'Donor 001', '200367901131', '2003-06-27', '201/ Colombo road, Gampaha', '0000000000', NULL, NULL, 'five', '200367901131', 'six', '200367901131', 'uploads/withdrawals/withdrawal_8043_1775735298.pdf', 'COMPLETED', '2026-04-09 11:48:06', 3),
(6, 8043, 'Donor 001', '200367901131', '2003-06-27', '201/ Colombo road, Gampaha', '0000000000', NULL, NULL, 'five', '200367901131', 'six', '200367901131', 'uploads/withdrawals/withdrawal_8043_1775737227.pdf', 'COMPLETED', '2026-04-09 12:13:45', 3),
(9, 8043, 'Donor 001', '200367901131', '2003-06-27', '201/ Colombo road, Gampaha', '0000000000', NULL, NULL, 'five', '200367901131', 'six', '200367901131', 'uploads/withdrawals/withdrawal_8043_1775738747.pdf', 'COMPLETED', '2026-04-09 12:37:47', 1),
(10, 8043, 'Donor 001', '200367901131', '2003-06-27', '201/ Colombo road, Gampaha', '0000000000', NULL, NULL, 'five', '200367901131', 'six', '200367901131', 'uploads/withdrawals/withdrawal_8043_1775738287.pdf', 'COMPLETED', '2026-04-09 12:37:58', 5),
(11, 8043, 'Donor 001', '200367901131', '2003-06-27', '201/ Colombo road, Gampaha', '0000000000', NULL, NULL, 'Witness One', '123456789V', 'Witness Two', '987654321V', 'uploads/withdrawals/withdrawal_8043_1775748590.pdf', 'COMPLETED', '2026-04-09 14:49:17', 1),
(12, 8043, 'Donor 001', '200367901131', '2003-06-27', '201/ Colombo road, Gampaha', '0000000000', NULL, NULL, 'five', '200367901131', 'six', '200367901131', 'uploads/withdrawals/withdrawal_8043_1775747631.pdf', 'COMPLETED', '2026-04-09 15:13:35', 3),
(14, 8043, 'Donor 001', '200367901131', '2003-06-27', '201/ Colombo road, Gampaha', '0000000000', NULL, NULL, 'five', '200367901131', 'six', '200367901131', 'uploads/withdrawals/withdrawal_8043_1775753080.pdf', 'COMPLETED', '2026-04-09 16:44:32', 5),
(15, 8043, 'Donor 001', '200367901131', '2003-06-27', '201/ Colombo road, Gampaha', '0000000000', NULL, NULL, 'five', '200367901131', 'six', '200367901131', 'uploads/withdrawals/withdrawal_8043_1775754219.pdf', 'COMPLETED', '2026-04-09 17:03:29', 1),
(16, 8043, 'Donor 001', '200367901131', '2003-06-27', '201/ Colombo road, Gampaha', '0000000000', NULL, NULL, 'five', '200367901131', 'six', '200367901131', 'uploads/withdrawals/withdrawal_8043_1775755555.pdf', 'COMPLETED', '2026-04-09 17:25:48', 2),
(17, 8043, 'Donor 001', '200367901131', '2003-06-27', '201/ Colombo road, Gampaha', '0000000000', NULL, NULL, 'five', '200367901132', 'six', '00000000', 'uploads/withdrawals/withdrawal_8043_1775755570.pdf', 'COMPLETED', '2026-04-09 17:26:04', 3),
(18, 8043, 'Donor 001', '200367901131', '2003-06-27', '201/ Colombo road, Gampaha', '0000000000', NULL, NULL, 'five', '200367901132', 'six', '00000000', 'uploads/withdrawals/withdrawal_8043_1775756785.pdf', 'COMPLETED', '2026-04-09 17:46:16', 4),
(19, 8043, 'Donor 001', '200367901131', '2003-06-27', '201/ Colombo road, Gampaha', '0000000000', NULL, NULL, 'five', '200367901132', 'six', '00000000', NULL, 'PENDING_UPLOAD', '2026-04-09 22:17:22', 1);

-- --------------------------------------------------------

--
-- Table structure for table `custodians`
--

CREATE TABLE `custodians` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `organ_id` int(11) DEFAULT NULL,
  `relationship` varchar(100) NOT NULL COMMENT 'e.g. Spouse, Brother-in-law',
  `custodian_number` tinyint(4) DEFAULT NULL COMMENT '1 = primary, 2 = co-custodian',
  `status` varchar(20) DEFAULT 'PENDING',
  `name` varchar(255) NOT NULL,
  `nic_number` varchar(20) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custodians`
--

INSERT INTO `custodians` (`id`, `user_id`, `donor_id`, `organ_id`, `relationship`, `custodian_number`, `status`, `name`, `nic_number`, `phone`, `email`, `address`, `created_at`) VALUES
(24, 9029, 9016, NULL, 'Spouse', 2, 'PENDING', 'Anna Test', '789123456V', '0770000003', 'test2@test.com', 'No 1, Dummy Lane', '2026-04-10 05:12:19'),
(25, 9030, 9016, NULL, 'Child', 3, 'PENDING', 'John Test Jr', '991234567V', '0770000004', 'test3@test.com', 'No 2, Dummy Lane', '2026-04-10 05:12:19'),
(31, 8018, 8043, 5, 'son', NULL, 'PENDING', 'ten', '000000000', '00000000', NULL, NULL, '2026-04-09 12:32:36'),
(32, 8019, 8043, 5, 'son', NULL, 'PENDING', 'six', '00000000', NULL, NULL, NULL, '2026-04-09 12:32:36'),
(33, 8018, 8043, 4, 'son', NULL, 'PENDING', 'ten', '000000000', '00000000', NULL, NULL, '2026-04-09 15:25:39'),
(34, 8019, 8043, 4, 'son', NULL, 'PENDING', 'six', '00000000', NULL, NULL, NULL, '2026-04-09 15:25:39'),
(35, 9102, 9020, NULL, 'Sibling', 1, 'PENDING', 'Body Custodian Test', 'BODY99999V', '0770000002', 'custodian_body@test.com', 'Body Test Street', '2026-04-10 11:55:51'),
(36, 9104, 9021, NULL, 'Child', 1, 'PENDING', 'Organ Custodian Test', 'ORGAN9999V', '0770000004', 'custodian_organ@test.com', 'Organ Test Street', '2026-04-10 11:55:51');

-- --------------------------------------------------------

--
-- Table structure for table `custodian_documents`
--

CREATE TABLE `custodian_documents` (
  `id` int(11) NOT NULL,
  `donation_case_id` int(11) NOT NULL,
  `case_institution_status_id` int(11) NOT NULL COMMENT 'Links to specific institution attempt',
  `document_type` varchar(100) NOT NULL COMMENT 'e.g. MCCD, Affidavit, CadaverSheet, NIC_Copy',
  `file_path` varchar(255) NOT NULL,
  `status` enum('UPLOADED','SUBMITTED','ACCEPTED','REJECTED') DEFAULT 'UPLOADED',
  `uploaded_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `custodian_documents`
--

INSERT INTO `custodian_documents` (`id`, `donation_case_id`, `case_institution_status_id`, `document_type`, `file_path`, `status`, `uploaded_at`) VALUES
(1, 10, 12, 'Document Bundle', 'uploads/custodian/docs/DC-TEST-001/12/Document_Bundle_1775834322.pdf', 'UPLOADED', '2026-04-10 15:18:42');

-- --------------------------------------------------------

--
-- Table structure for table `custodian_legal_actions`
--

CREATE TABLE `custodian_legal_actions` (
  `id` int(11) NOT NULL,
  `donation_case_id` int(11) NOT NULL,
  `custodian_id` int(11) NOT NULL,
  `action_type` enum('CONFIRM','OBJECT') NOT NULL,
  `method` enum('QUICK','PRINT_SIGN') NOT NULL,
  `reason_category` varchar(50) DEFAULT NULL COMMENT 'For objections: religious, cultural, emotional, legal, other',
  `reason_text` text DEFAULT NULL COMMENT 'Detailed reason for objection',
  `remarks` text DEFAULT NULL COMMENT 'Optional remarks for confirmations',
  `signed_document_path` varchar(255) DEFAULT NULL,
  `co_signed_document_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `death_cases`
--

CREATE TABLE `death_cases` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `custodian_id` int(11) NOT NULL,
  `date_of_death` date NOT NULL,
  `time_of_death` time NOT NULL,
  `place_of_death` varchar(255) NOT NULL,
  `cause_of_death` varchar(255) NOT NULL,
  `additional_notes` text DEFAULT NULL,
  `deadline` datetime NOT NULL,
  `status` enum('DECLARED','PENDING_SUBMISSION','SUBMITTED','REVIEWED','COMPLETED','EXPIRED') DEFAULT 'DECLARED',
  `triage_responses` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`triage_responses`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `submission_state` enum('DRAFT','SUBMITTED','ARCHIVED') DEFAULT 'DRAFT',
  `bundle_status` enum('PENDING','SUBMITTED','REVIEWED','VERIFIED','REJECTED') DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `death_declarations`
--

CREATE TABLE `death_declarations` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `declared_by_custodian_id` int(11) NOT NULL,
  `date_of_death` date NOT NULL,
  `time_of_death` time NOT NULL,
  `place_of_death` varchar(255) NOT NULL,
  `cause_of_death` text NOT NULL,
  `additional_notes` text DEFAULT NULL,
  `window_expires_at` datetime NOT NULL COMMENT 'death datetime + 48 hours',
  `status` enum('ACTIVE','EXPIRED','COMPLETED') DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `death_declarations`
--

INSERT INTO `death_declarations` (`id`, `donor_id`, `declared_by_custodian_id`, `date_of_death`, `time_of_death`, `place_of_death`, `cause_of_death`, `additional_notes`, `window_expires_at`, `status`, `created_at`) VALUES
(10, 9020, 35, '2026-04-10', '00:00:00', 'national', 'aaa', 'bbb', '2026-04-12 00:00:00', 'ACTIVE', '2026-04-10 12:27:04'),
(11, 9020, 35, '2026-04-10', '10:00:00', 'Home', 'Natural', NULL, '0000-00-00 00:00:00', 'ACTIVE', '2026-04-10 15:13:10');

-- --------------------------------------------------------

--
-- Table structure for table `donations`
--

CREATE TABLE `donations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) DEFAULT 'LKR',
  `status` enum('PENDING','SUCCESS','FAILED') DEFAULT 'PENDING',
  `transaction_id` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donations`
--

INSERT INTO `donations` (`id`, `user_id`, `amount`, `currency`, `status`, `transaction_id`, `note`, `created_at`) VALUES
(3, 8020, 1000.00, 'LKR', 'SUCCESS', NULL, '', '2026-04-09 11:58:27'),
(4, 8020, 2500.00, 'LKR', 'SUCCESS', NULL, '', '2026-04-09 11:59:29'),
(5, 8020, 2500.00, 'LKR', 'SUCCESS', NULL, '', '2026-04-09 11:59:41'),
(6, 8020, 2500.00, 'LKR', 'SUCCESS', NULL, '', '2026-04-09 12:01:40'),
(7, 8020, 5000.00, 'LKR', 'SUCCESS', NULL, '', '2026-04-09 12:01:57'),
(8, 8020, 10000.00, 'LKR', 'SUCCESS', NULL, '', '2026-04-09 12:02:17'),
(9, 8020, 2500.00, 'LKR', 'SUCCESS', NULL, '', '2026-04-09 12:07:43'),
(10, 8020, 10000.00, 'LKR', 'SUCCESS', NULL, '', '2026-04-09 12:08:00'),
(11, 8020, 10000.00, 'LKR', 'SUCCESS', NULL, '', '2026-04-09 12:15:23'),
(12, 8020, 10000.00, 'LKR', 'SUCCESS', NULL, '', '2026-04-09 12:21:10'),
(13, 8020, 10000.00, 'LKR', 'SUCCESS', NULL, '', '2026-04-09 12:47:21'),
(14, 8020, 10000.00, 'LKR', 'SUCCESS', NULL, '', '2026-04-09 15:15:23');

-- --------------------------------------------------------

--
-- Table structure for table `donation_cases`
--

CREATE TABLE `donation_cases` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `death_declaration_id` int(11) NOT NULL,
  `case_number` varchar(20) NOT NULL COMMENT 'e.g. DC-2026-001',
  `donation_type` enum('BODY','ORGAN','BODY_AND_CORNEA') NOT NULL,
  `legal_status` enum('PENDING','CONFIRMED','OBJECTED') DEFAULT 'PENDING',
  `overall_status` enum('OPEN','IN_PROGRESS','COMPLETED','CANCELLED') DEFAULT 'OPEN',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `bundle_status` enum('PENDING','SUBMITTED','REVIEWED','VERIFIED','REJECTED') DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donation_cases`
--

INSERT INTO `donation_cases` (`id`, `donor_id`, `death_declaration_id`, `case_number`, `donation_type`, `legal_status`, `overall_status`, `created_at`, `bundle_status`) VALUES
(9, 9020, 10, 'DC-2026-001', 'BODY', 'PENDING', 'IN_PROGRESS', '2026-04-10 12:27:04', 'PENDING'),
(10, 9020, 11, 'DC-TEST-001', 'BODY', 'PENDING', 'IN_PROGRESS', '2026-04-10 15:13:10', 'SUBMITTED');

-- --------------------------------------------------------

--
-- Table structure for table `donation_certificates`
--

CREATE TABLE `donation_certificates` (
  `id` int(11) NOT NULL,
  `donation_case_id` int(11) NOT NULL,
  `case_institution_request_id` int(11) NOT NULL,
  `certificate_number` varchar(100) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `issued_by_name` varchar(255) DEFAULT NULL,
  `issued_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donors`
--

CREATE TABLE `donors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `active_roles` varchar(255) DEFAULT '[]',
  `pledge_type` enum('LIVING','DECEASED_ORGAN','DECEASED_BODY','NONE') DEFAULT 'NONE',
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `nic_number` varchar(20) NOT NULL,
  `nationality` varchar(100) DEFAULT 'Sri Lankan',
  `nic_image_path` varchar(255) DEFAULT NULL,
  `gender` enum('MALE','FEMALE','OTHER') NOT NULL,
  `date_of_birth` date NOT NULL,
  `blood_group` enum('A+','A-','B+','B-','AB+','AB-','O+','O-') DEFAULT NULL,
  `address` text NOT NULL,
  `district` varchar(50) NOT NULL,
  `divisional_secretariat` varchar(100) NOT NULL,
  `grama_niladhari_division` varchar(100) NOT NULL,
  `verification_status` enum('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
  `consent_status` enum('GIVEN','WITHDRAWN','PENDING') DEFAULT 'PENDING',
  `consent_date` date DEFAULT NULL,
  `opt_out_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donors`
--

INSERT INTO `donors` (`id`, `user_id`, `category_id`, `active_roles`, `pledge_type`, `first_name`, `last_name`, `nic_number`, `nationality`, `nic_image_path`, `gender`, `date_of_birth`, `blood_group`, `address`, `district`, `divisional_secretariat`, `grama_niladhari_division`, `verification_status`, `consent_status`, `consent_date`, `opt_out_reason`, `created_at`) VALUES
(8043, 8020, 3, '[\"financial\",\"organ\"]', 'NONE', 'Donor', '001', '200367901131', 'Sri Lankan', NULL, 'FEMALE', '2003-06-27', 'O+', 'aUVjU29iNFRINTRrajY0eGVHVnBZc1Y0VWgzdEs5aHNQdE9ObTlWaVJPOD06Our6bVOFswmBHtVHTc7WIC0=', '', '', '', 'PENDING', 'PENDING', NULL, NULL, '2026-04-09 04:08:13'),
(8044, 8022, 3, '[\"financial\",\"organ\"]', 'NONE', 'Donor', '002', '200367901132', 'Sri Lankan', NULL, 'FEMALE', '2003-06-27', 'AB+', 'UythRlJIS0ZFRSsvQnkyUzIwaHJvT2JLMzduOUxmRFovOTNqNFlNTGdFQT06OsIR9rho+o5yD5IvnHY4AWk=', '', '', '', 'PENDING', 'PENDING', NULL, NULL, '2026-04-09 06:34:24'),
(9020, 9101, 0, '[]', 'DECEASED_BODY', 'Body', 'DonorTest', 'DONORBODY99V', 'Sri Lankan', NULL, 'MALE', '0000-00-00', NULL, '', '', '', '', 'PENDING', 'PENDING', NULL, NULL, '2026-04-10 11:55:51'),
(9021, 9103, 0, '[]', 'DECEASED_ORGAN', 'Organ', 'DonorTest', 'DONORORGAN99', 'Sri Lankan', NULL, 'MALE', '0000-00-00', NULL, '', '', '', '', 'PENDING', 'PENDING', NULL, NULL, '2026-04-10 11:55:51');

-- --------------------------------------------------------

--
-- Table structure for table `donor_categories`
--

CREATE TABLE `donor_categories` (
  `id` int(11) NOT NULL,
  `category_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donor_categories`
--

INSERT INTO `donor_categories` (`id`, `category_name`) VALUES
(2, 'FINANCIAL'),
(3, 'JUST_DONOR'),
(1, 'NON');

-- --------------------------------------------------------

--
-- Table structure for table `donor_patient_match`
--

CREATE TABLE `donor_patient_match` (
  `match_id` int(11) NOT NULL,
  `donor_pledge_id` int(11) NOT NULL,
  `request_id` int(11) NOT NULL,
  `match_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('MATCH','MATCH WITH WARNING','PENDING','APPROVED','REJECTED') DEFAULT 'MATCH',
  `warning_details` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `donor_pledges`
--

CREATE TABLE `donor_pledges` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `organ_id` int(11) NOT NULL,
  `pledge_date` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('PENDING','UPLOADED','APPROVED','IN_PROGRESS','COMPLETED','WITHDRAWN') DEFAULT 'PENDING',
  `conditions` text DEFAULT NULL,
  `medications` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `preferred_hospital_id` int(11) DEFAULT NULL,
  `signed_form_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donor_pledges`
--

INSERT INTO `donor_pledges` (`id`, `donor_id`, `organ_id`, `pledge_date`, `status`, `conditions`, `medications`, `allergies`, `preferred_hospital_id`, `signed_form_path`) VALUES
(6, 9017, 1, '2026-04-09 19:33:31', '', NULL, NULL, NULL, NULL, NULL),
(38, 8043, 5, '2026-04-09 04:58:55', 'WITHDRAWN', '', NULL, NULL, NULL, NULL),
(39, 8043, 6, '2026-04-09 05:34:43', 'WITHDRAWN', '', NULL, NULL, NULL, NULL),
(40, 8043, 7, '2026-04-09 05:38:06', 'WITHDRAWN', '', NULL, NULL, NULL, NULL),
(41, 8044, 1, '2026-04-09 07:22:07', 'WITHDRAWN', '', NULL, '', NULL, NULL),
(42, 8044, 2, '2026-04-09 07:24:32', 'WITHDRAWN', '', NULL, '', 1, NULL),
(43, 8044, 1, '2026-04-09 07:27:17', 'WITHDRAWN', '', NULL, '', NULL, NULL),
(44, 8044, 1, '2026-04-09 07:54:39', 'WITHDRAWN', '', NULL, '', NULL, NULL),
(45, 8044, 2, '2026-04-09 08:14:37', 'WITHDRAWN', '', NULL, '', 1, NULL),
(46, 8044, 1, '2026-04-09 08:17:59', 'WITHDRAWN', '', NULL, '', NULL, NULL),
(47, 8044, 1, '2026-04-09 08:37:01', 'WITHDRAWN', '', NULL, '', NULL, NULL),
(48, 8044, 1, '2026-04-09 08:39:32', 'WITHDRAWN', '', NULL, '', NULL, NULL),
(49, 8044, 1, '2026-04-09 08:41:03', 'WITHDRAWN', '', NULL, '', NULL, NULL),
(50, 8044, 1, '2026-04-09 08:43:09', 'WITHDRAWN', '', NULL, '', NULL, NULL),
(51, 8044, 1, '2026-04-09 08:52:27', 'WITHDRAWN', '', NULL, '', NULL, NULL),
(53, 8044, 1, '2026-04-09 10:19:09', 'WITHDRAWN', '', NULL, '', NULL, NULL),
(54, 8043, 1, '2026-04-09 10:26:15', 'UPLOADED', '', NULL, '', NULL, 'uploads/pledges/pledge_8043_1_1775756641.pdf'),
(57, 8043, 3, '2026-04-09 10:55:27', 'WITHDRAWN', '', NULL, '', NULL, 'uploads/pledges/pledge_8043_3_1775754339.pdf'),
(59, 8043, 3, '2026-04-09 11:20:34', 'WITHDRAWN', '', NULL, '', NULL, 'uploads/pledges/pledge_8043_3_1775754339.pdf'),
(61, 8043, 3, '2026-04-09 12:13:36', 'WITHDRAWN', '', NULL, '', NULL, 'uploads/pledges/pledge_8043_3_1775754339.pdf'),
(63, 8043, 5, '2026-04-09 12:32:36', 'WITHDRAWN', '', NULL, NULL, NULL, NULL),
(65, 8043, 3, '2026-04-09 12:53:27', 'WITHDRAWN', '', NULL, '', NULL, 'uploads/pledges/pledge_8043_3_1775754339.pdf'),
(67, 8043, 3, '2026-04-09 15:20:34', 'WITHDRAWN', '', NULL, '', NULL, 'uploads/pledges/pledge_8043_3_1775754339.pdf'),
(68, 8043, 4, '2026-04-09 15:25:39', 'WITHDRAWN', '', NULL, NULL, NULL, NULL),
(69, 8043, 2, '2026-04-09 17:26:27', 'UPLOADED', '', NULL, '', NULL, 'uploads/pledges/pledge_8043_2_1775755596.pdf'),
(70, 8043, 1, '2026-04-09 17:43:51', 'UPLOADED', '', NULL, '', NULL, 'uploads/pledges/pledge_8043_1_1775756641.pdf'),
(71, 9021, 1, '2026-04-10 11:55:51', '', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `donor_timeline`
--

CREATE TABLE `donor_timeline` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `event_type` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT 'fa-circle-dot',
  `color_variant` varchar(20) DEFAULT 'blue',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `financial_donors`
--

CREATE TABLE `financial_donors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `nic_number` varchar(20) DEFAULT NULL,
  `donation_frequency` enum('ONETIME','MONTHLY','ANNUALLY') DEFAULT 'ONETIME'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `registration_number` varchar(50) NOT NULL,
  `transplant_id` varchar(50) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `district` varchar(50) NOT NULL,
  `facility_type` varchar(50) NOT NULL,
  `cmo_name` varchar(255) NOT NULL,
  `cmo_nic` varchar(20) NOT NULL,
  `medical_license_number` varchar(50) NOT NULL,
  `verification_status` enum('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`id`, `user_id`, `registration_number`, `transplant_id`, `name`, `address`, `contact_number`, `district`, `facility_type`, `cmo_name`, `cmo_nic`, `medical_license_number`, `verification_status`) VALUES
(1, 66, 'REG-HSP-1', NULL, 'Hospital Jaffna 1', 'Address 1', NULL, 'Jaffna', 'General', 'Dr. CMO 1', '197022504217', 'LIC-1', 'APPROVED');

-- --------------------------------------------------------

--
-- Table structure for table `lab_reports`
--

CREATE TABLE `lab_reports` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) DEFAULT NULL,
  `hospital_registration_no` varchar(50) NOT NULL,
  `test_type` varchar(100) NOT NULL COMMENT 'e.g., Blood Group, HIV, Hepatitis B, TB, etc.',
  `test_date` date NOT NULL,
  `result_status` varchar(50) NOT NULL COMMENT 'Negative, Positive, Pending, Inconclusive',
  `result_notes` longtext DEFAULT NULL,
  `blood_type` varchar(5) DEFAULT NULL,
  `recipient_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `living_donor_consents`
--

CREATE TABLE `living_donor_consents` (
  `id` int(11) NOT NULL,
  `donor_pledge_id` int(11) NOT NULL,
  `height` decimal(5,2) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `previous_surgeries` text DEFAULT NULL,
  `smoking_alcohol_status` varchar(255) DEFAULT NULL,
  `is_recipient_known` enum('Yes','No') DEFAULT 'No',
  `blood_compatibility` varchar(255) DEFAULT NULL,
  `tissue_typing` text DEFAULT NULL,
  `medical_clearance_status` varchar(100) DEFAULT 'Pending',
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_relationship` varchar(100) DEFAULT NULL,
  `emergency_phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `living_donor_consents`
--

INSERT INTO `living_donor_consents` (`id`, `donor_pledge_id`, `height`, `weight`, `previous_surgeries`, `smoking_alcohol_status`, `is_recipient_known`, `blood_compatibility`, `tissue_typing`, `medical_clearance_status`, `emergency_contact_name`, `emergency_relationship`, `emergency_phone`, `created_at`, `updated_at`) VALUES
(37, 67, 0.00, 0.00, '', 'None', 'No', '', '', 'Pending', 'Sewmini', 'daughter ', '0123456789', '2026-04-09 15:20:34', '2026-04-09 15:20:34'),
(38, 69, 0.00, 0.00, '', 'None', 'No', '', '', 'Pending', 'Sewmini', 'daughter ', '0123456789', '2026-04-09 17:26:27', '2026-04-09 17:26:27'),
(39, 70, 0.00, 0.00, '', 'None', 'No', '', '', 'Pending', 'Sewmini', 'daughter ', '0123456789', '2026-04-09 17:43:51', '2026-04-09 17:43:51');

-- --------------------------------------------------------

--
-- Table structure for table `medical_schools`
--

CREATE TABLE `medical_schools` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `university_affiliation` varchar(255) NOT NULL,
  `ugc_accreditation_number` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `district` varchar(50) NOT NULL,
  `contact_person_name` varchar(255) NOT NULL,
  `contact_person_title` varchar(50) DEFAULT NULL,
  `contact_person_email` varchar(255) DEFAULT NULL,
  `contact_person_phone` varchar(15) NOT NULL,
  `verification_status` enum('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
  `registration_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medical_schools`
--

INSERT INTO `medical_schools` (`id`, `user_id`, `school_name`, `university_affiliation`, `ugc_accreditation_number`, `address`, `district`, `contact_person_name`, `contact_person_title`, `contact_person_email`, `contact_person_phone`, `verification_status`, `registration_date`) VALUES
(1, 9000, 'Colombo Medical Faculty', 'Colombo', 'UGC-1', 'Colombo', 'Colombo', 'Dr. Perera', NULL, NULL, '0123456789', 'APPROVED', NULL),
(2, 9001, 'Kelaniya Medical Faculty', 'Kelaniya', 'UGC-2', 'Kelaniya', 'Gampaha', 'Dr. Silva', NULL, NULL, '0123456789', 'APPROVED', NULL),
(3, 9002, 'Sri J Med Faculty', 'Sri J', 'UGC-3', 'Nugegoda', 'Colombo', 'Dr. Fernando', NULL, NULL, '0123456789', 'APPROVED', NULL),
(12, 9105, 'Name', 'Univ', 'UGC1', 'Addr', 'Dist', 'ContactName', NULL, NULL, '0777777771', 'PENDING', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `next_of_kin`
--

CREATE TABLE `next_of_kin` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `relationship` varchar(50) NOT NULL,
  `nic_number` varchar(20) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT 'GENERAL',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notification_queue`
--

CREATE TABLE `notification_queue` (
  `id` int(11) NOT NULL,
  `recipient_email` varchar(255) DEFAULT NULL,
  `recipient_phone` varchar(20) DEFAULT NULL,
  `notification_type` varchar(50) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('pending','sent','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `sent_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `organs`
--

CREATE TABLE `organs` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `organs`
--

INSERT INTO `organs` (`id`, `name`, `description`, `is_available`) VALUES
(1, 'Kidney', 'Before Death (Living Donor)', 1),
(2, 'Part of Liver', 'Before Death (Living Donor)', 1),
(3, 'Bone Marrow', 'Before Death (Living Donor)', 1),
(4, 'Cornea', 'After Death Donation', 1),
(5, 'Skin', 'After Death Donation', 1),
(6, 'Bones', 'After Death Donation', 1),
(7, 'Heart Valves', 'After Death Donation', 1),
(8, 'Tendons', 'After Death Donation', 1),
(9, 'Full Body(After death)', 'Educational Purpose    ', 1);

-- --------------------------------------------------------

--
-- Table structure for table `organ_requests`
--

CREATE TABLE `organ_requests` (
  `id` int(11) NOT NULL,
  `hospital_id` int(11) NOT NULL,
  `organ_id` int(11) NOT NULL,
  `recipient_age` tinyint(3) UNSIGNED DEFAULT NULL,
  `blood_group` varchar(3) DEFAULT NULL,
  `gender` enum('Male','Female','Other') DEFAULT NULL,
  `hla_typing` varchar(255) DEFAULT NULL,
  `transplant_reason` text DEFAULT NULL,
  `priority_level` enum('NORMAL','URGENT','CRITICAL') DEFAULT 'NORMAL',
  `edited_reason` text DEFAULT NULL,
  `status` enum('PENDING','OPEN','MATCHED','CLOSED') DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT current_timestamp()
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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recipient_patient`
--

CREATE TABLE `recipient_patient` (
  `id` int(11) NOT NULL,
  `registration_number` varchar(20) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `hospital_registration_no` varchar(50) NOT NULL,
  `age` int(11) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `blood_group` varchar(10) DEFAULT NULL,
  `contact_details` varchar(255) DEFAULT NULL,
  `medical_details` text DEFAULT NULL,
  `status` enum('ACTIVE','SUSPENDED') NOT NULL DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registration_audit_log`
--

CREATE TABLE `registration_audit_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(100) NOT NULL,
  `record_id` int(11) DEFAULT NULL,
  `new_values` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `registration_otps`
--

CREATE TABLE `registration_otps` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `attempts` int(11) DEFAULT 0,
  `purpose` varchar(50) DEFAULT 'registration'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registration_otps`
--

INSERT INTO `registration_otps` (`id`, `email`, `otp`, `expires_at`, `verified`, `created_at`, `attempts`, `purpose`) VALUES
(3, 'sewminichanchala0627@gmail.com', '060011', '2026-04-08 15:11:39', 1, '2026-04-08 13:01:39', 0, 'registration'),
(4, 'sewminichanchala0627@gmail.com', '573317', '2026-04-09 06:17:35', 1, '2026-04-09 04:07:35', 0, 'registration'),
(7, 'sewchanchala25@gmail.com', '916436', '2026-04-09 08:43:56', 1, '2026-04-09 06:33:56', 0, 'registration');

-- --------------------------------------------------------

--
-- Table structure for table `success_stories`
--

CREATE TABLE `success_stories` (
  `story_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `success_date` date NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_requests`
--

CREATE TABLE `support_requests` (
  `id` int(11) NOT NULL,
  `patient_nic` varchar(20) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `patient_type` varchar(100) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
  `submitted_date` date NOT NULL,
  `reviewed_date` date DEFAULT NULL,
  `reviewed_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sworn_statements`
--

CREATE TABLE `sworn_statements` (
  `id` int(11) NOT NULL,
  `donation_case_id` int(11) NOT NULL,
  `form_data` longtext NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sworn_statements`
--

INSERT INTO `sworn_statements` (`id`, `donation_case_id`, `form_data`, `created_at`) VALUES
(2, 9, '{\"custodian_name\":\"Body Custodian Test\",\"custodian_nic\":\"BODY99999V\",\"custodian_address\":\"Body Test Street\",\"custodian_relationship\":\"Sibling\",\"custodian_phone\":\"0770000002\",\"donor_religion\":\"\",\"place_of_death\":\"\",\"race\":\"\",\"occupation\":\"tbbbbbbbbbbb\",\"birth_place\":\"\",\"past_medical_history\":\"\",\"past_surgical_history\":\"\",\"other_diseases\":\"\",\"relations_name\":[\"sss\",\"abc\"],\"relations_rel\":[\"ssssssssssssss\",\"Brother\"],\"relations_nic\":[\"200451313123\",\"200451313123\"]}', '2026-04-10 14:30:34'),
(3, 10, '{\"custodian_name\":\"Body Custodian Test\",\"custodian_nic\":\"BODY99999V\",\"custodian_address\":\"Body Test Street\",\"custodian_relationship\":\"Sibling\",\"custodian_phone\":\"0770000002\",\"donor_religion\":\"\",\"place_of_death\":\"\",\"race\":\"\",\"occupation\":\"ggggggggggggg\",\"birth_place\":\"\",\"past_medical_history\":\"\",\"past_surgical_history\":\"\",\"other_diseases\":\"\",\"relations_name\":[\"fffffffffffgg\"],\"relations_rel\":[\"hhhhhhhhhhhh\"],\"relations_nic\":[\"ffffffffff\"]}', '2026-04-10 15:17:34');

-- --------------------------------------------------------

--
-- Table structure for table `test_results`
--

CREATE TABLE `test_results` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `test_name` varchar(100) NOT NULL,
  `result_value` varchar(255) DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `test_date` date NOT NULL,
  `verified_by_hospital_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `upcoming_appointments`
--

CREATE TABLE `upcoming_appointments` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `test_date` date DEFAULT NULL,
  `hospital_registration_no` varchar(50) DEFAULT NULL,
  `test_type` varchar(100) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` enum('F_ADMIN','AC_ADMIN','D_ADMIN','U_ADMIN','DONOR','HOSPITAL','MEDICAL_SCHOOL','CUSTODIAN') NOT NULL,
  `status` enum('PENDING','ACTIVE','SUSPENDED') DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `review_message` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password_hash`, `email`, `phone`, `role`, `status`, `created_at`, `updated_at`, `review_message`) VALUES
(1, 'reema_sahly', '$2y$10$acZZrVLId4qa6Y6FeCXPo.4Bzu7v6xvY2xIJIx1Z5wBXt2Yt94EEC', 'reema.sahly@lifeconnect.lk', '', 'U_ADMIN', 'ACTIVE', '2026-02-15 07:44:30', '2026-04-10 16:22:59', NULL),
(2, 'sewmini_chanchala', '$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm', 'sewmini.chanchala@lifeconnect.lk', NULL, 'F_ADMIN', 'ACTIVE', '2026-02-15 07:44:30', '2026-04-10 16:22:59', NULL),
(3, 'lasal_jayasinghe', '$2y$10$d5XJQTSAsMm1xwcM4dzyJuY0nP2DUB0EXv/.dlfhuJ3qNiZYo5LTK', 'lasal.jayasinghe@lifeconnect.lk', NULL, 'AC_ADMIN', 'ACTIVE', '2026-02-15 07:44:30', '2026-04-10 16:22:59', NULL),
(4, 'sahasna_samarawickrama', '$2y$10$d5XJQTSAsMm1xwcM4dzyJuY0nP2DUB0EXv/.dlfhuJ3qNiZYo5LTK', 'sahasna.samarawickrama@lifeconnect.lk', NULL, 'D_ADMIN', 'ACTIVE', '2026-02-15 07:44:30', '2026-04-10 16:22:59', NULL),
(5, 'admin_5', '$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm', 'admin5@lifeconnect.lk', NULL, '', 'ACTIVE', '2026-02-15 07:44:30', '2026-02-15 07:44:30', NULL),
(66, 'hospital_1', '$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm', 'hospital_1@hosp.lk', NULL, 'HOSPITAL', 'ACTIVE', '2026-02-15 07:44:31', '2026-02-15 07:44:31', NULL),
(128, '200335212710', '$2y$10$kDb7SFNHX18/FpSHEzIYguXbfz1XjTWIvFTy0zgY4yMIstKWdQtKe', 'drte@gmail.com', '098766543', 'CUSTODIAN', 'ACTIVE', '2026-02-24 09:01:54', '2026-02-24 09:01:54', NULL),
(8011, '200451313123', '$2y$10$/AJR1u6BFUKtiBEFw3M0k.8aSLDM7bMSgXecrjmrvfwc3MECWonOu', 'example09@gmail.com', '0764532176', 'CUSTODIAN', 'ACTIVE', '2026-03-05 10:46:03', '2026-03-05 10:46:03', NULL),
(8012, '0000000000', '$2y$10$OO0UXk5ndZC9GhB2XIrT8.LMcZIZYF7/IubnPNm/svvQ3hl034XaO', '', '0123456789', 'CUSTODIAN', 'ACTIVE', '2026-04-07 09:04:08', '2026-04-07 09:04:08', NULL),
(8018, '000000000', '$2y$10$2WEmDZKH8Zt6rqaHcT4B/uZKiG6IyfRvrQRu2rYCzzuoBM8cvOUnO', NULL, '00000000', 'CUSTODIAN', 'ACTIVE', '2026-04-08 18:52:01', '2026-04-08 18:52:01', NULL),
(8019, '00000000', '$2y$10$in4vql/DivtkMSRZP8fmKe0UX4f.tL.iL1bKdiBLkliOpDaj3WYhW', NULL, NULL, 'CUSTODIAN', 'ACTIVE', '2026-04-08 18:52:02', '2026-04-08 18:52:02', NULL),
(8020, 'donor_1', '$2y$10$KvJJBQwe0nUwNTrkCaFo8e8U/oHV9q/wax8QQm1fysHIPqdZM48Uu', 'sewminichanchala0627@gmail.com', '0000000000', 'DONOR', 'ACTIVE', '2026-04-09 04:08:13', '2026-04-09 04:08:55', NULL),
(8021, '200367901131', '$2y$10$veVAxJ1iCiUrj/Wwm6V4S./Pm1r/xmbB22glH2TQG/L96WrOVCDZO', 'amal12@gmail.com', '0123456789', 'CUSTODIAN', 'ACTIVE', '2026-04-09 05:24:32', '2026-04-09 05:24:32', NULL),
(8022, 'donor_2', '$2y$10$MlG.juKG6Dbq5quENj1jWukb04Ribt5ZLe3xiid9AOxNmUnf2kMhq', 'sewchanchala25@gmail.com', '0000000000', 'DONOR', 'ACTIVE', '2026-04-09 06:34:24', '2026-04-09 06:35:42', NULL),
(9000, 'med_school_1', '$2y$10$37cYik39AwmALgUXxnCS2e6ApdCUwS/.Vsz.XFrU/BhmMEEYcGPYi', NULL, NULL, 'MEDICAL_SCHOOL', 'ACTIVE', '2026-04-10 11:48:22', '2026-04-10 12:05:01', NULL),
(9001, 'med_school_2', '$2y$10$37cYik39AwmALgUXxnCS2e6ApdCUwS/.Vsz.XFrU/BhmMEEYcGPYi', NULL, NULL, 'MEDICAL_SCHOOL', 'ACTIVE', '2026-04-10 11:52:16', '2026-04-10 12:05:01', NULL),
(9002, 'med_school_3', '$2y$10$37cYik39AwmALgUXxnCS2e6ApdCUwS/.Vsz.XFrU/BhmMEEYcGPYi', NULL, NULL, 'MEDICAL_SCHOOL', 'ACTIVE', '2026-04-10 11:52:16', '2026-04-10 12:05:01', NULL),
(9029, 'test_cust_2', 'dummy', 'test2@test.com', '0770000003', 'CUSTODIAN', 'ACTIVE', '2026-04-10 05:11:32', '2026-04-10 05:11:32', NULL),
(9030, 'test_cust_3', 'dummy', 'test3@test.com', '0770000004', 'CUSTODIAN', 'ACTIVE', '2026-04-10 05:11:32', '2026-04-10 05:11:32', NULL),
(9100, 'hospital_1_user', '$2y$10$37cYik39AwmALgUXxnCS2e6ApdCUwS/.Vsz.XFrU/BhmMEEYcGPYi', NULL, NULL, 'HOSPITAL', 'ACTIVE', '2026-04-10 11:52:16', '2026-04-10 12:05:01', NULL),
(9101, 'DONORBODY99V', '$2y$10$ba026nWbqKbKbRz7ATUYFu9NpshKzNd5XhqM1L0yfVNekCM6C4yvi', 'donorbody@test.com', '0770000001', 'DONOR', 'ACTIVE', '2026-04-10 11:55:51', '2026-04-10 11:55:51', NULL),
(9102, 'BODY99999V', '$2y$10$ba026nWbqKbKbRz7ATUYFu9NpshKzNd5XhqM1L0yfVNekCM6C4yvi', 'custodian_body@test.com', '0770000002', 'CUSTODIAN', 'ACTIVE', '2026-04-10 11:55:51', '2026-04-10 11:55:51', NULL),
(9103, 'DONORORGAN99', '$2y$10$jvLHFAcjHFCdTj1vZZYcEuxehLlNeiwWTT6I02I9oe2vBXyE5gJTq', 'donororgan@test.com', '0770000003', 'DONOR', 'ACTIVE', '2026-04-10 11:55:51', '2026-04-10 11:55:51', NULL),
(9104, 'ORGAN9999V', '$2y$10$jvLHFAcjHFCdTj1vZZYcEuxehLlNeiwWTT6I02I9oe2vBXyE5gJTq', 'custodian_organ@test.com', '0770000004', 'CUSTODIAN', 'ACTIVE', '2026-04-10 11:55:51', '2026-04-10 11:55:51', NULL),
(9105, 'test_ms_code', '$2y$10$O98eKwl/uhRreCeSAvh75OcP4IgEguo19jHrxOvWB8l/rMejDmEUC', 'ms_code@test.com', '0777777771', 'MEDICAL_SCHOOL', 'ACTIVE', '2026-04-10 12:07:59', '2026-04-10 12:07:59', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `witnesses`
--

CREATE TABLE `witnesses` (
  `id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `organ_id` int(11) DEFAULT NULL,
  `witness_number` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `nic_number` varchar(20) NOT NULL,
  `contact_number` varchar(15) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `witnesses`
--

INSERT INTO `witnesses` (`id`, `donor_id`, `organ_id`, `witness_number`, `name`, `nic_number`, `contact_number`, `address`) VALUES
(53, 8043, 5, 1, 'five', '000000000', NULL, NULL),
(54, 8043, 5, 2, 'six', '00000000', NULL, NULL),
(55, 8043, 3, 1, 'five', '200367901131', '', ''),
(56, 8043, 3, 2, 'six', '200367901131', '', ''),
(57, 8043, 2, 1, 'five', '200367901131', '', ''),
(58, 8043, 2, 2, 'six', '200367901131', '', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `aftercare_appointments`
--
ALTER TABLE `aftercare_appointments`
  ADD PRIMARY KEY (`appointment_id`),
  ADD KEY `patient_id` (`patient_id`),
  ADD KEY `hospital_reg` (`hospital_registration_no`);

--
-- Indexes for table `aftercare_patients`
--
ALTER TABLE `aftercare_patients`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_aftercare_reg` (`registration_number`),
  ADD UNIQUE KEY `uniq_aftercare_nic` (`nic`),
  ADD KEY `idx_aftercare_hosp` (`hospital_registration_no`);

--
-- Indexes for table `after_death_consents`
--
ALTER TABLE `after_death_consents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`),
  ADD KEY `preferred_hospital_id` (`preferred_hospital_id`);

--
-- Indexes for table `body_donation_consents`
--
ALTER TABLE `body_donation_consents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bdc_medschool` (`medical_school_id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `body_usage_logs`
--
ALTER TABLE `body_usage_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_usage_donor` (`donor_id`),
  ADD KEY `fk_usage_school` (`medical_school_id`);

--
-- Indexes for table `cadaver_data_sheets`
--
ALTER TABLE `cadaver_data_sheets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_cds_case` (`donation_case_id`),
  ADD KEY `idx_cds_inst` (`case_institution_status_id`);

--
-- Indexes for table `case_custodian_approvals`
--
ALTER TABLE `case_custodian_approvals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `death_declaration_id` (`death_declaration_id`),
  ADD KEY `custodian_id` (`custodian_id`);

--
-- Indexes for table `case_documents`
--
ALTER TABLE `case_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `case_id` (`case_id`);

--
-- Indexes for table `case_institution_requests`
--
ALTER TABLE `case_institution_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donation_case_id` (`donation_case_id`);

--
-- Indexes for table `case_institution_status`
--
ALTER TABLE `case_institution_status`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_inst_case` (`donation_case_id`),
  ADD KEY `idx_inst_current` (`donation_case_id`,`track`,`is_current`);

--
-- Indexes for table `consent_records`
--
ALTER TABLE `consent_records`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `consent_templates`
--
ALTER TABLE `consent_templates`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `consent_withdrawals`
--
ALTER TABLE `consent_withdrawals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `custodians`
--
ALTER TABLE `custodians`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_donor_custodian` (`donor_id`,`custodian_number`),
  ADD KEY `idx_custodian_user` (`user_id`),
  ADD KEY `idx_custodian_donor` (`donor_id`),
  ADD KEY `fk_cust_organ` (`organ_id`);

--
-- Indexes for table `custodian_documents`
--
ALTER TABLE `custodian_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_doc_case` (`donation_case_id`),
  ADD KEY `idx_doc_inst` (`case_institution_status_id`);

--
-- Indexes for table `custodian_legal_actions`
--
ALTER TABLE `custodian_legal_actions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_legal_case` (`donation_case_id`),
  ADD KEY `idx_legal_custodian` (`custodian_id`);

--
-- Indexes for table `death_cases`
--
ALTER TABLE `death_cases`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`),
  ADD KEY `custodian_id` (`custodian_id`);

--
-- Indexes for table `death_declarations`
--
ALTER TABLE `death_declarations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_death_donor` (`donor_id`),
  ADD KEY `idx_death_custodian` (`declared_by_custodian_id`);

--
-- Indexes for table `donations`
--
ALTER TABLE `donations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_donations_user` (`user_id`);

--
-- Indexes for table `donation_cases`
--
ALTER TABLE `donation_cases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_case_number` (`case_number`),
  ADD KEY `idx_case_donor` (`donor_id`),
  ADD KEY `idx_case_death` (`death_declaration_id`);

--
-- Indexes for table `donation_certificates`
--
ALTER TABLE `donation_certificates`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `certificate_number` (`certificate_number`),
  ADD KEY `donation_case_id` (`donation_case_id`),
  ADD KEY `case_institution_request_id` (`case_institution_request_id`);

--
-- Indexes for table `donors`
--
ALTER TABLE `donors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nic_number` (`nic_number`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `fk_donor_category` (`category_id`);

--
-- Indexes for table `donor_categories`
--
ALTER TABLE `donor_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `donor_patient_match`
--
ALTER TABLE `donor_patient_match`
  ADD PRIMARY KEY (`match_id`),
  ADD UNIQUE KEY `uq_pledge_req` (`donor_pledge_id`,`request_id`);

--
-- Indexes for table `donor_pledges`
--
ALTER TABLE `donor_pledges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`),
  ADD KEY `organ_id` (`organ_id`),
  ADD KEY `fk_pledge_hosp` (`preferred_hospital_id`);

--
-- Indexes for table `donor_timeline`
--
ALTER TABLE `donor_timeline`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`),
  ADD KEY `created_at` (`created_at`);

--
-- Indexes for table `financial_donors`
--
ALTER TABLE `financial_donors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `registration_number` (`registration_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lab_reports`
--
ALTER TABLE `lab_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`),
  ADD KEY `hospital_reg` (`hospital_registration_no`),
  ADD KEY `recipient_id` (`recipient_id`);

--
-- Indexes for table `living_donor_consents`
--
ALTER TABLE `living_donor_consents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_pledge_id` (`donor_pledge_id`);

--
-- Indexes for table `medical_schools`
--
ALTER TABLE `medical_schools`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ugc_number` (`ugc_accreditation_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `next_of_kin`
--
ALTER TABLE `next_of_kin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notification_queue`
--
ALTER TABLE `notification_queue`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `organs`
--
ALTER TABLE `organs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `organ_requests`
--
ALTER TABLE `organ_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_organ_requests_hospital` (`hospital_id`),
  ADD KEY `idx_organ_requests_organ` (`organ_id`);

--
-- Indexes for table `recipients`
--
ALTER TABLE `recipients`
  ADD PRIMARY KEY (`recipient_id`),
  ADD KEY `nic` (`nic`),
  ADD KEY `hospital_reg` (`hospital_registration_no`);

--
-- Indexes for table `recipient_patient`
--
ALTER TABLE `recipient_patient`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uniq_recipient_reg` (`registration_number`),
  ADD UNIQUE KEY `uniq_recipient_nic` (`nic`),
  ADD KEY `idx_recipient_hosp` (`hospital_registration_no`);

--
-- Indexes for table `registration_audit_log`
--
ALTER TABLE `registration_audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `registration_otps`
--
ALTER TABLE `registration_otps`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `success_stories`
--
ALTER TABLE `success_stories`
  ADD PRIMARY KEY (`story_id`),
  ADD KEY `fk_story_user_id` (`user_id`);

--
-- Indexes for table `support_requests`
--
ALTER TABLE `support_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `patient_nic` (`patient_nic`);

--
-- Indexes for table `sworn_statements`
--
ALTER TABLE `sworn_statements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donation_case_id` (`donation_case_id`);

--
-- Indexes for table `test_results`
--
ALTER TABLE `test_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `upcoming_appointments`
--
ALTER TABLE `upcoming_appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `donor_id` (`donor_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `witnesses`
--
ALTER TABLE `witnesses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_donor_organ_witness` (`donor_id`,`organ_id`,`witness_number`),
  ADD KEY `donor_id` (`donor_id`),
  ADD KEY `fk_wit_organ` (`organ_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `aftercare_appointments`
--
ALTER TABLE `aftercare_appointments`
  MODIFY `appointment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `aftercare_patients`
--
ALTER TABLE `aftercare_patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `after_death_consents`
--
ALTER TABLE `after_death_consents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `body_donation_consents`
--
ALTER TABLE `body_donation_consents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9023;

--
-- AUTO_INCREMENT for table `body_usage_logs`
--
ALTER TABLE `body_usage_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8004;

--
-- AUTO_INCREMENT for table `cadaver_data_sheets`
--
ALTER TABLE `cadaver_data_sheets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `case_custodian_approvals`
--
ALTER TABLE `case_custodian_approvals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `case_documents`
--
ALTER TABLE `case_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `case_institution_requests`
--
ALTER TABLE `case_institution_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `case_institution_status`
--
ALTER TABLE `case_institution_status`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `consent_records`
--
ALTER TABLE `consent_records`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consent_templates`
--
ALTER TABLE `consent_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `consent_withdrawals`
--
ALTER TABLE `consent_withdrawals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `custodians`
--
ALTER TABLE `custodians`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `custodian_documents`
--
ALTER TABLE `custodian_documents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `custodian_legal_actions`
--
ALTER TABLE `custodian_legal_actions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `death_cases`
--
ALTER TABLE `death_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `death_declarations`
--
ALTER TABLE `death_declarations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `donations`
--
ALTER TABLE `donations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `donation_cases`
--
ALTER TABLE `donation_cases`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `donation_certificates`
--
ALTER TABLE `donation_certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donors`
--
ALTER TABLE `donors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9022;

--
-- AUTO_INCREMENT for table `donor_categories`
--
ALTER TABLE `donor_categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `donor_patient_match`
--
ALTER TABLE `donor_patient_match`
  MODIFY `match_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `donor_pledges`
--
ALTER TABLE `donor_pledges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `donor_timeline`
--
ALTER TABLE `donor_timeline`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `financial_donors`
--
ALTER TABLE `financial_donors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `lab_reports`
--
ALTER TABLE `lab_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `living_donor_consents`
--
ALTER TABLE `living_donor_consents`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `medical_schools`
--
ALTER TABLE `medical_schools`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `next_of_kin`
--
ALTER TABLE `next_of_kin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notification_queue`
--
ALTER TABLE `notification_queue`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `organs`
--
ALTER TABLE `organs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `organ_requests`
--
ALTER TABLE `organ_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recipients`
--
ALTER TABLE `recipients`
  MODIFY `recipient_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recipient_patient`
--
ALTER TABLE `recipient_patient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registration_audit_log`
--
ALTER TABLE `registration_audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `registration_otps`
--
ALTER TABLE `registration_otps`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `success_stories`
--
ALTER TABLE `success_stories`
  MODIFY `story_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `support_requests`
--
ALTER TABLE `support_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sworn_statements`
--
ALTER TABLE `sworn_statements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `test_results`
--
ALTER TABLE `test_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `upcoming_appointments`
--
ALTER TABLE `upcoming_appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9106;

--
-- AUTO_INCREMENT for table `witnesses`
--
ALTER TABLE `witnesses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admins`
--
ALTER TABLE `admins`
  ADD CONSTRAINT `fk_admins_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `after_death_consents`
--
ALTER TABLE `after_death_consents`
  ADD CONSTRAINT `after_death_consents_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `after_death_consents_ibfk_2` FOREIGN KEY (`preferred_hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `body_donation_consents`
--
ALTER TABLE `body_donation_consents`
  ADD CONSTRAINT `fk_bdc_donor` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_bdc_medschool` FOREIGN KEY (`medical_school_id`) REFERENCES `medical_schools` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `body_usage_logs`
--
ALTER TABLE `body_usage_logs`
  ADD CONSTRAINT `fk_usage_donor` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_usage_school` FOREIGN KEY (`medical_school_id`) REFERENCES `medical_schools` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
