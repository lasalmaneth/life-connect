-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: life-connect
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `aftercare_appointments`
--

DROP TABLE IF EXISTS `aftercare_appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `aftercare_appointments` (
  `appointment_id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_id` varchar(20) NOT NULL COMMENT 'Used for NIC reference',
  `patient_name` varchar(255) NOT NULL,
  `hospital_registration_no` varchar(50) NOT NULL,
  `appointment_date` datetime NOT NULL,
  `appointment_type` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('Scheduled','Completed','Cancelled') DEFAULT 'Scheduled',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`appointment_id`),
  KEY `patient_id` (`patient_id`),
  KEY `hospital_reg` (`hospital_registration_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `aftercare_appointments`
--

LOCK TABLES `aftercare_appointments` WRITE;
/*!40000 ALTER TABLE `aftercare_appointments` DISABLE KEYS */;
/*!40000 ALTER TABLE `aftercare_appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `body_donation_consents`
--

DROP TABLE IF EXISTS `body_donation_consents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `body_donation_consents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donor_id` int(11) NOT NULL,
  `medical_school_id` int(11) DEFAULT NULL,
  `witness1_name` varchar(255) NOT NULL,
  `witness1_nic` varchar(20) NOT NULL,
  `witness1_phone` varchar(15) NOT NULL,
  `witness1_address` text NOT NULL,
  `witness2_name` varchar(255) NOT NULL,
  `witness2_nic` varchar(20) NOT NULL,
  `witness2_phone` varchar(15) NOT NULL,
  `witness2_address` text NOT NULL,
  `consent_date` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `donor_id` (`donor_id`),
  KEY `fk_bdc_medschool` (`medical_school_id`),
  CONSTRAINT `fk_bdc_donor` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_bdc_medschool` FOREIGN KEY (`medical_school_id`) REFERENCES `medical_schools` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=8011 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `body_donation_consents`
--

LOCK TABLES `body_donation_consents` WRITE;
/*!40000 ALTER TABLE `body_donation_consents` DISABLE KEYS */;
INSERT INTO `body_donation_consents` VALUES (1,63,2,'Pasindu Anjana','200345321534','071000000','Ath pokuna, Anuradhapura.','Tharini Lelwala','200498765190','07654111011','Rahula Junction, Matara','2026-02-16 12:29:06'),(2,64,2,'Witness One','198011111111','0771111111','Address 1','Witness Two','198022222222','0772222222','Address 2','2026-02-16 13:17:00'),(8001,8001,7,'Witness1','NIC1','0770000000','Address1','Witness2','NIC2','0770000001','Address2','2026-03-02 16:35:05'),(8002,8002,7,'Witness1','NIC1','0770000000','Address1','Witness2','NIC2','0770000001','Address2','2026-03-02 16:35:05'),(8003,8003,7,'Witness1','NIC1','0770000000','Address1','Witness2','NIC2','0770000001','Address2','2026-03-02 16:35:05'),(8004,8004,7,'Witness1','NIC1','0770000000','Address1','Witness2','NIC2','0770000001','Address2','2026-03-02 16:35:05'),(8005,8005,7,'Witness1','NIC1','0770000000','Address1','Witness2','NIC2','0770000001','Address2','2026-03-02 16:35:05'),(8006,8006,7,'Witness1','NIC1','0770000000','Address1','Witness2','NIC2','0770000001','Address2','2026-03-02 16:35:05'),(8007,8007,7,'Witness1','NIC1','0770000000','Address1','Witness2','NIC2','0770000001','Address2','2026-03-02 16:35:05'),(8008,8008,7,'Witness1','NIC1','0770000000','Address1','Witness2','NIC2','0770000001','Address2','2026-03-02 16:35:05'),(8009,8009,7,'Witness1','NIC1','0770000000','Address1','Witness2','NIC2','0770000001','Address2','2026-03-02 16:35:05'),(8010,8010,7,'Witness1','NIC1','0770000000','Address1','Witness2','NIC2','0770000001','Address2','2026-03-02 16:35:05');
/*!40000 ALTER TABLE `body_donation_consents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `body_usage_logs`
--

DROP TABLE IF EXISTS `body_usage_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `body_usage_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donor_id` int(11) NOT NULL,
  `medical_school_id` int(11) NOT NULL,
  `usage_type` varchar(100) NOT NULL COMMENT 'e.g., Anatomy, Research, Surgery Training',
  `description` text DEFAULT NULL,
  `usage_date` date NOT NULL,
  `disposal_method` varchar(100) DEFAULT NULL COMMENT 'e.g., Burial, Cremation, Returned to Kin',
  `disposal_date` date DEFAULT NULL,
  `status` enum('IN_USE','DISPOSED') DEFAULT 'IN_USE',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_usage_donor` (`donor_id`),
  KEY `fk_usage_school` (`medical_school_id`),
  CONSTRAINT `fk_usage_donor` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_usage_school` FOREIGN KEY (`medical_school_id`) REFERENCES `medical_schools` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8004 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `body_usage_logs`
--

LOCK TABLES `body_usage_logs` WRITE;
/*!40000 ALTER TABLE `body_usage_logs` DISABLE KEYS */;
INSERT INTO `body_usage_logs` VALUES (2,64,2,'',NULL,'0000-00-00',NULL,NULL,'','2026-02-16 13:31:44'),(8001,8008,7,'Anatomy Education','Undergraduate Dissection Batch 2026','2026-01-10',NULL,NULL,'IN_USE','2026-03-02 16:35:05'),(8002,8009,7,'Medical Research','Histology Research','2026-02-01',NULL,NULL,'IN_USE','2026-03-02 16:35:05'),(8003,8010,7,'Surgical Training','Completed','2025-10-15','Cremation','2026-01-20','DISPOSED','2026-03-02 16:35:05');
/*!40000 ALTER TABLE `body_usage_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cadaver_data_sheets`
--

DROP TABLE IF EXISTS `cadaver_data_sheets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cadaver_data_sheets` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donation_case_id` int(11) NOT NULL,
  `case_institution_status_id` int(11) NOT NULL,
  `form_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'All form fields as JSON' CHECK (json_valid(`form_data`)),
  `pdf_path` varchar(255) DEFAULT NULL,
  `status` enum('DRAFT','GENERATED','SIGNED','SUBMITTED') DEFAULT 'DRAFT',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_cds_case` (`donation_case_id`),
  KEY `idx_cds_inst` (`case_institution_status_id`),
  CONSTRAINT `fk_cds_case` FOREIGN KEY (`donation_case_id`) REFERENCES `donation_cases` (`id`),
  CONSTRAINT `fk_cds_inst` FOREIGN KEY (`case_institution_status_id`) REFERENCES `case_institution_status` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cadaver_data_sheets`
--

LOCK TABLES `cadaver_data_sheets` WRITE;
/*!40000 ALTER TABLE `cadaver_data_sheets` DISABLE KEYS */;
/*!40000 ALTER TABLE `cadaver_data_sheets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `case_institution_status`
--

DROP TABLE IF EXISTS `case_institution_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `case_institution_status` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donation_case_id` int(11) NOT NULL,
  `institution_type` enum('MEDICAL_SCHOOL','HOSPITAL','EYE_BANK') NOT NULL,
  `institution_id` int(11) NOT NULL COMMENT 'FK to medical_schools.id or hospitals.id',
  `track` enum('BODY','ORGAN','CORNEA') NOT NULL COMMENT 'Separates body/organ/cornea flows',
  `attempt_order` tinyint(4) NOT NULL COMMENT 'Sequence: 1st, 2nd, 3rd attempt',
  `is_current` tinyint(1) DEFAULT 0 COMMENT 'Only 1 active per case per track at a time',
  `custodian_action` enum('NOT_CONTACTED','CONTACTED','SUBMITTED') DEFAULT 'NOT_CONTACTED',
  `institution_status` enum('PENDING','UNDER_REVIEW','ACCEPTED','REJECTED') DEFAULT 'PENDING',
  `rejection_message` text DEFAULT NULL COMMENT 'Institution reason for rejection',
  `submission_date` datetime DEFAULT NULL,
  `response_date` datetime DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_inst_case` (`donation_case_id`),
  KEY `idx_inst_current` (`donation_case_id`,`track`,`is_current`),
  CONSTRAINT `fk_inst_case` FOREIGN KEY (`donation_case_id`) REFERENCES `donation_cases` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `case_institution_status`
--

LOCK TABLES `case_institution_status` WRITE;
/*!40000 ALTER TABLE `case_institution_status` DISABLE KEYS */;
/*!40000 ALTER TABLE `case_institution_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custodian_documents`
--

DROP TABLE IF EXISTS `custodian_documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custodian_documents` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donation_case_id` int(11) NOT NULL,
  `case_institution_status_id` int(11) NOT NULL COMMENT 'Links to specific institution attempt',
  `document_type` varchar(100) NOT NULL COMMENT 'e.g. MCCD, Affidavit, CadaverSheet, NIC_Copy',
  `file_path` varchar(255) NOT NULL,
  `status` enum('UPLOADED','SUBMITTED','ACCEPTED','REJECTED') DEFAULT 'UPLOADED',
  `uploaded_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_doc_case` (`donation_case_id`),
  KEY `idx_doc_inst` (`case_institution_status_id`),
  CONSTRAINT `fk_doc_case` FOREIGN KEY (`donation_case_id`) REFERENCES `donation_cases` (`id`),
  CONSTRAINT `fk_doc_inst` FOREIGN KEY (`case_institution_status_id`) REFERENCES `case_institution_status` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custodian_documents`
--

LOCK TABLES `custodian_documents` WRITE;
/*!40000 ALTER TABLE `custodian_documents` DISABLE KEYS */;
/*!40000 ALTER TABLE `custodian_documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custodian_legal_actions`
--

DROP TABLE IF EXISTS `custodian_legal_actions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custodian_legal_actions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donation_case_id` int(11) NOT NULL,
  `custodian_id` int(11) NOT NULL,
  `action_type` enum('CONFIRM','OBJECT') NOT NULL,
  `method` enum('QUICK','PRINT_SIGN') NOT NULL,
  `reason_category` varchar(50) DEFAULT NULL COMMENT 'For objections: religious, cultural, emotional, legal, other',
  `reason_text` text DEFAULT NULL COMMENT 'Detailed reason for objection',
  `remarks` text DEFAULT NULL COMMENT 'Optional remarks for confirmations',
  `signed_document_path` varchar(255) DEFAULT NULL,
  `co_signed_document_path` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_legal_case` (`donation_case_id`),
  KEY `idx_legal_custodian` (`custodian_id`),
  CONSTRAINT `fk_legal_case` FOREIGN KEY (`donation_case_id`) REFERENCES `donation_cases` (`id`),
  CONSTRAINT `fk_legal_custodian` FOREIGN KEY (`custodian_id`) REFERENCES `custodians` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custodian_legal_actions`
--

LOCK TABLES `custodian_legal_actions` WRITE;
/*!40000 ALTER TABLE `custodian_legal_actions` DISABLE KEYS */;
/*!40000 ALTER TABLE `custodian_legal_actions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `custodians`
--

DROP TABLE IF EXISTS `custodians`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `custodians` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `organ_id` int(11) DEFAULT NULL,
  `relationship` varchar(100) NOT NULL COMMENT 'e.g. Spouse, Brother-in-law',
  `custodian_number` tinyint(4) NOT NULL COMMENT '1 = primary, 2 = co-custodian',
  `name` varchar(255) NOT NULL,
  `nic_number` varchar(20) NOT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_donor_custodian` (`donor_id`,`custodian_number`),
  KEY `idx_custodian_user` (`user_id`),
  KEY `idx_custodian_donor` (`donor_id`),
  KEY `fk_cust_organ` (`organ_id`),
  CONSTRAINT `fk_cust_organ` FOREIGN KEY (`organ_id`) REFERENCES `organs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_custodian_donor` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_custodian_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `custodians`
--

LOCK TABLES `custodians` WRITE;
/*!40000 ALTER TABLE `custodians` DISABLE KEYS */;
/*!40000 ALTER TABLE `custodians` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `death_declarations`
--

DROP TABLE IF EXISTS `death_declarations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `death_declarations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donor_id` int(11) NOT NULL,
  `declared_by_custodian_id` int(11) NOT NULL,
  `date_of_death` date NOT NULL,
  `time_of_death` time NOT NULL,
  `place_of_death` varchar(255) NOT NULL,
  `cause_of_death` text NOT NULL,
  `additional_notes` text DEFAULT NULL,
  `window_expires_at` datetime NOT NULL COMMENT 'death datetime + 48 hours',
  `status` enum('ACTIVE','EXPIRED','COMPLETED') DEFAULT 'ACTIVE',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_death_donor` (`donor_id`),
  KEY `idx_death_custodian` (`declared_by_custodian_id`),
  CONSTRAINT `fk_death_custodian` FOREIGN KEY (`declared_by_custodian_id`) REFERENCES `custodians` (`id`),
  CONSTRAINT `fk_death_donor` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `death_declarations`
--

LOCK TABLES `death_declarations` WRITE;
/*!40000 ALTER TABLE `death_declarations` DISABLE KEYS */;
/*!40000 ALTER TABLE `death_declarations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donation_cases`
--

DROP TABLE IF EXISTS `donation_cases`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donation_cases` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donor_id` int(11) NOT NULL,
  `death_declaration_id` int(11) NOT NULL,
  `case_number` varchar(20) NOT NULL COMMENT 'e.g. DC-2026-001',
  `donation_type` enum('BODY','ORGAN','BODY_AND_CORNEA') NOT NULL,
  `legal_status` enum('PENDING','CONFIRMED','OBJECTED') DEFAULT 'PENDING',
  `overall_status` enum('OPEN','IN_PROGRESS','COMPLETED','CANCELLED') DEFAULT 'OPEN',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_case_number` (`case_number`),
  KEY `idx_case_donor` (`donor_id`),
  KEY `idx_case_death` (`death_declaration_id`),
  CONSTRAINT `fk_case_death` FOREIGN KEY (`death_declaration_id`) REFERENCES `death_declarations` (`id`),
  CONSTRAINT `fk_case_donor` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donation_cases`
--

LOCK TABLES `donation_cases` WRITE;
/*!40000 ALTER TABLE `donation_cases` DISABLE KEYS */;
/*!40000 ALTER TABLE `donation_cases` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donations`
--

DROP TABLE IF EXISTS `donations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donations` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) DEFAULT 'LKR',
  `status` enum('PENDING','SUCCESS','FAILED') DEFAULT 'PENDING',
  `transaction_id` varchar(100) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_donations_user` (`user_id`),
  CONSTRAINT `fk_donations_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donations`
--

LOCK TABLES `donations` WRITE;
/*!40000 ALTER TABLE `donations` DISABLE KEYS */;
INSERT INTO `donations` VALUES (2,6,5000.00,'LKR','SUCCESS',NULL,'','2026-04-02 08:11:53');
/*!40000 ALTER TABLE `donations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donor_categories`
--

DROP TABLE IF EXISTS `donor_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donor_categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `category_name` (`category_name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donor_categories`
--

LOCK TABLES `donor_categories` WRITE;
/*!40000 ALTER TABLE `donor_categories` DISABLE KEYS */;
INSERT INTO `donor_categories` VALUES (2,'FINANCIAL'),(3,'JUST_DONOR'),(1,'NON');
/*!40000 ALTER TABLE `donor_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donor_pledges`
--

DROP TABLE IF EXISTS `donor_pledges`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donor_pledges` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donor_id` int(11) NOT NULL,
  `organ_id` int(11) NOT NULL,
  `pledge_date` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('PENDING','APPROVED','COMPLETED') DEFAULT 'PENDING',
  `conditions` text DEFAULT NULL,
  `medications` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `preferred_hospital_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `donor_id` (`donor_id`),
  KEY `organ_id` (`organ_id`),
  KEY `fk_pledge_hosp` (`preferred_hospital_id`),
  CONSTRAINT `fk_pledge_hosp` FOREIGN KEY (`preferred_hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donor_pledges`
--

LOCK TABLES `donor_pledges` WRITE;
/*!40000 ALTER TABLE `donor_pledges` DISABLE KEYS */;
INSERT INTO `donor_pledges` VALUES (1,1,1,'2026-04-02 09:20:16','PENDING','None','','',1),(2,1,2,'2026-04-02 09:41:54','PENDING','None','','',9);
/*!40000 ALTER TABLE `donor_pledges` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `donors`
--

DROP TABLE IF EXISTS `donors`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `donors` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `pledge_type` enum('LIVING','DECEASED_ORGAN','DECEASED_BODY','NONE') DEFAULT 'NONE',
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `nic_number` varchar(20) NOT NULL,
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
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `nic_number` (`nic_number`),
  KEY `user_id` (`user_id`),
  KEY `fk_donor_category` (`category_id`),
  CONSTRAINT `fk_donor_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8042 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `donors`
--

LOCK TABLES `donors` WRITE;
/*!40000 ALTER TABLE `donors` DISABLE KEYS */;
INSERT INTO `donors` VALUES (1,6,1,'NONE','FirstName1','LastName1','198000000001',NULL,'FEMALE','1990-02-18','A+','1 Sample Street, Kurunegala','Kurunegala','DS Division','GN Division','','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(2,7,1,'NONE','FirstName2','LastName2','198000000002',NULL,'FEMALE','1973-01-18','B+','2 Sample Street, Trincomalee','Trincomalee','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(3,8,3,'DECEASED_ORGAN','FirstName3','LastName3','198000000003',NULL,'FEMALE','1979-09-04','B-','3 Sample Street, Galle','Galle','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(4,9,3,'LIVING','FirstName4','LastName4','198000000004',NULL,'OTHER','1999-04-25','B+','4 Sample Street, Matale','Matale','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(5,10,3,'DECEASED_ORGAN','FirstName5','LastName5','198000000005',NULL,'FEMALE','1997-08-13','A-','5 Sample Street, Matara','Matara','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(6,11,3,'DECEASED_ORGAN','FirstName6','LastName6','198000000006',NULL,'MALE','1999-04-26','O+','6 Sample Street, Puttalam','Puttalam','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(7,12,3,'DECEASED_ORGAN','FirstName7','LastName7','198000000007',NULL,'OTHER','1983-05-20','O-','7 Sample Street, Gampaha','Gampaha','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(8,13,3,'LIVING','FirstName8','LastName8','198000000008',NULL,'OTHER','1993-03-02','AB+','8 Sample Street, Moneragala','Moneragala','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(9,14,3,'DECEASED_ORGAN','FirstName9','LastName9','198000000009',NULL,'OTHER','1995-02-28','A+','9 Sample Street, Kandy','Kandy','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(10,15,1,'NONE','FirstName10','LastName10','198000000010',NULL,'MALE','1972-11-18','A-','10 Sample Street, Matara','Matara','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(11,16,1,'NONE','FirstName11','LastName11','198000000011',NULL,'FEMALE','1990-01-10','B-','11 Sample Street, Mullaitivu','Mullaitivu','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(12,17,3,'DECEASED_ORGAN','FirstName12','LastName12','198000000012',NULL,'OTHER','1985-09-26','A+','12 Sample Street, Badulla','Badulla','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(13,18,3,'LIVING','FirstName13','LastName13','198000000013',NULL,'OTHER','1975-04-21','AB-','13 Sample Street, Kilinochchi','Kilinochchi','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(14,19,3,'DECEASED_ORGAN','FirstName14','LastName14','198000000014',NULL,'OTHER','1980-06-28','AB-','14 Sample Street, Puttalam','Puttalam','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(15,20,1,'NONE','FirstName15','LastName15','198000000015',NULL,'FEMALE','1986-06-12','O-','15 Sample Street, Hambantota','Hambantota','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(16,21,3,'LIVING','FirstName16','LastName16','198000000016',NULL,'FEMALE','1994-09-15','B-','16 Sample Street, Matara','Matara','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(17,22,3,'DECEASED_ORGAN','FirstName17','LastName17','198000000017',NULL,'OTHER','1996-12-09','AB+','17 Sample Street, Moneragala','Moneragala','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(18,23,1,'NONE','FirstName18','LastName18','198000000018',NULL,'FEMALE','1974-08-20','A-','18 Sample Street, Vavuniya','Vavuniya','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(19,24,3,'DECEASED_ORGAN','FirstName19','LastName19','198000000019',NULL,'OTHER','1992-09-17','A-','19 Sample Street, Kandy','Kandy','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(20,25,3,'LIVING','FirstName20','LastName20','198000000020',NULL,'OTHER','1993-06-11','B-','20 Sample Street, Mannar','Mannar','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(21,26,3,'DECEASED_ORGAN','FirstName21','LastName21','198000000021',NULL,'MALE','1982-10-10','O-','21 Sample Street, Matara','Matara','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(22,27,3,'DECEASED_ORGAN','FirstName22','LastName22','198000000022',NULL,'MALE','1995-06-23','O+','22 Sample Street, Kilinochchi','Kilinochchi','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(23,28,1,'NONE','FirstName23','LastName23','198000000023',NULL,'FEMALE','1988-04-10','A+','23 Sample Street, Moneragala','Moneragala','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(24,29,3,'DECEASED_ORGAN','FirstName24','LastName24','198000000024',NULL,'OTHER','1991-04-01','B-','24 Sample Street, Matara','Matara','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(25,30,1,'NONE','FirstName25','LastName25','198000000025',NULL,'FEMALE','1987-01-18','A+','25 Sample Street, Kandy','Kandy','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(26,31,3,'DECEASED_ORGAN','FirstName26','LastName26','198000000026',NULL,'MALE','1973-10-17','O+','26 Sample Street, Kurunegala','Kurunegala','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(27,32,1,'NONE','FirstName27','LastName27','198000000027',NULL,'MALE','1976-11-27','A-','27 Sample Street, Matale','Matale','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(28,33,3,'LIVING','FirstName28','LastName28','198000000028',NULL,'OTHER','1983-07-13','AB-','28 Sample Street, Polonnaruwa','Polonnaruwa','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(29,34,3,'DECEASED_ORGAN','FirstName29','LastName29','198000000029',NULL,'MALE','1995-12-24','O+','29 Sample Street, Ampara','Ampara','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(30,35,3,'DECEASED_ORGAN','FirstName30','LastName30','198000000030',NULL,'OTHER','1981-10-02','O-','30 Sample Street, Trincomalee','Trincomalee','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(31,36,3,'DECEASED_ORGAN','FirstName31','LastName31','198000000031',NULL,'FEMALE','1974-09-03','AB+','31 Sample Street, Ampara','Ampara','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(32,37,1,'NONE','FirstName32','LastName32','198000000032',NULL,'MALE','1999-11-20','AB-','32 Sample Street, Puttalam','Puttalam','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(33,38,3,'LIVING','FirstName33','LastName33','198000000033',NULL,'MALE','1994-06-15','AB-','33 Sample Street, Anuradhapura','Anuradhapura','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(34,39,1,'NONE','FirstName34','LastName34','198000000034',NULL,'OTHER','1977-03-07','O+','34 Sample Street, Kegalle','Kegalle','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(35,40,1,'NONE','FirstName35','LastName35','198000000035',NULL,'MALE','1991-09-06','O+','35 Sample Street, Matale','Matale','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(36,41,3,'LIVING','FirstName36','LastName36','198000000036',NULL,'MALE','1980-11-17','AB-','36 Sample Street, Kandy','Kandy','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(37,42,1,'NONE','FirstName37','LastName37','198000000037',NULL,'FEMALE','1982-10-08','O+','37 Sample Street, Vavuniya','Vavuniya','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(38,43,3,'LIVING','FirstName38','LastName38','198000000038',NULL,'MALE','1998-01-27','O-','38 Sample Street, Matale','Matale','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(39,44,3,'DECEASED_ORGAN','FirstName39','LastName39','198000000039',NULL,'MALE','1989-09-18','B-','39 Sample Street, Anuradhapura','Anuradhapura','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(40,45,3,'LIVING','FirstName40','LastName40','198000000040',NULL,'FEMALE','1976-07-09','O-','40 Sample Street, Ampara','Ampara','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(41,46,3,'LIVING','FirstName41','LastName41','198000000041',NULL,'MALE','1997-02-05','AB+','41 Sample Street, Trincomalee','Trincomalee','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(42,47,3,'DECEASED_ORGAN','FirstName42','LastName42','198000000042',NULL,'MALE','1970-06-16','A-','42 Sample Street, Matale','Matale','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(43,48,3,'DECEASED_ORGAN','FirstName43','LastName43','198000000043',NULL,'FEMALE','1995-08-11','O+','43 Sample Street, Kalutara','Kalutara','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(44,49,1,'NONE','FirstName44','LastName44','198000000044',NULL,'MALE','1971-08-27','AB+','44 Sample Street, Batticaloa','Batticaloa','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(45,50,3,'DECEASED_ORGAN','FirstName45','LastName45','198000000045',NULL,'OTHER','1993-06-19','AB-','45 Sample Street, Matale','Matale','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(46,51,3,'LIVING','FirstName46','LastName46','198000000046',NULL,'MALE','1998-04-07','A+','46 Sample Street, Colombo','Colombo','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(47,52,1,'NONE','FirstName47','LastName47','198000000047',NULL,'FEMALE','1978-07-04','AB+','47 Sample Street, Ampara','Ampara','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(48,53,3,'LIVING','FirstName48','LastName48','198000000048',NULL,'MALE','1989-03-01','AB-','48 Sample Street, Kalutara','Kalutara','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(49,54,1,'NONE','FirstName49','LastName49','198000000049',NULL,'OTHER','1998-02-17','B-','49 Sample Street, Kegalle','Kegalle','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:30'),(50,55,1,'NONE','FirstName50','LastName50','198000000050',NULL,'FEMALE','1988-03-13','O+','50 Sample Street, Ampara','Ampara','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:31'),(51,56,3,'DECEASED_ORGAN','FirstName51','LastName51','198000000051',NULL,'MALE','1982-11-22','A+','51 Sample Street, Kilinochchi','Kilinochchi','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:31'),(52,57,3,'LIVING','FirstName52','LastName52','198000000052',NULL,'OTHER','1999-08-09','A-','52 Sample Street, Kegalle','Kegalle','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:31'),(53,58,3,'DECEASED_ORGAN','FirstName53','LastName53','198000000053',NULL,'OTHER','1992-04-13','B-','53 Sample Street, Matara','Matara','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:31'),(54,59,1,'NONE','FirstName54','LastName54','198000000054',NULL,'OTHER','1976-07-06','B-','54 Sample Street, Moneragala','Moneragala','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:31'),(55,60,1,'NONE','FirstName55','LastName55','198000000055',NULL,'MALE','1987-11-14','A+','55 Sample Street, Mullaitivu','Mullaitivu','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:31'),(56,61,1,'NONE','FirstName56','LastName56','198000000056',NULL,'MALE','1973-12-14','A+','56 Sample Street, Matara','Matara','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:31'),(57,62,3,'LIVING','FirstName57','LastName57','198000000057',NULL,'MALE','2000-11-02','A-','57 Sample Street, Mullaitivu','Mullaitivu','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:31'),(58,63,3,'LIVING','FirstName58','LastName58','198000000058',NULL,'MALE','1971-06-24','B-','58 Sample Street, Batticaloa','Batticaloa','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:31'),(59,64,3,'LIVING','FirstName59','LastName59','198000000059',NULL,'MALE','1983-06-09','B+','59 Sample Street, Hambantota','Hambantota','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:31'),(60,65,3,'LIVING','FirstName60','LastName60','198000000060',NULL,'FEMALE','1973-06-13','AB+','60 Sample Street, Matara','Matara','DS Division','GN Division','APPROVED','PENDING',NULL,NULL,'2026-02-15 07:44:31'),(61,124,2,'NONE','Pasindu','Anjana','200335212710',NULL,'MALE','0000-00-00','O+','','','','','PENDING','PENDING',NULL,NULL,'2026-02-16 09:24:24'),(62,125,3,'LIVING','Dilanka','Supun','200323912777',NULL,'MALE','0000-00-00','O+','','','','','PENDING','PENDING',NULL,NULL,'2026-02-16 10:36:10'),(63,126,1,'NONE','Sahasna','Samarawickrama','200451313123',NULL,'FEMALE','2004-01-13',NULL,'','','','','PENDING','PENDING',NULL,NULL,'2026-02-16 11:31:34'),(64,127,3,'DECEASED_BODY','Late','Donor','195012345678',NULL,'MALE','1950-01-01',NULL,'123 Silent Lane','Colombo','Colombo','Cinnmon Gardens','APPROVED','GIVEN',NULL,NULL,'2026-02-16 13:17:00'),(8001,8001,3,'LIVING','Saman','Kumara','198034567891',NULL,'MALE','1980-05-15','O+','123 Main St','Ampara','Div Sec','GN Div','APPROVED','PENDING',NULL,NULL,'2026-03-02 16:35:05'),(8002,8002,3,'LIVING','Nimal','Perera','198034567892',NULL,'MALE','1982-06-20','A+','456 Side St','Ampara','Div Sec','GN Div','APPROVED','PENDING',NULL,NULL,'2026-03-02 16:35:05'),(8003,8003,3,'LIVING','Kamal','Silva','198034567893',NULL,'MALE','1975-01-10','B+','789 Elm St','Ampara','Div Sec','GN Div','APPROVED','GIVEN',NULL,NULL,'2026-03-02 16:35:05'),(8004,8004,3,'LIVING','Sunil','Fernando','198034567894',NULL,'MALE','1988-08-08','AB+','321 Oak St','Ampara','Div Sec','GN Div','APPROVED','GIVEN',NULL,NULL,'2026-03-02 16:35:05'),(8005,8005,3,'LIVING','Amal','Dias','198034567895',NULL,'MALE','1990-11-22','O-','654 Pine St','Ampara','Div Sec','GN Div','APPROVED','GIVEN',NULL,NULL,'2026-03-02 16:35:05'),(8006,8006,3,'LIVING','Bimal','Rathnayake','198034567896',NULL,'MALE','1985-04-30','A-','987 Cedar','Ampara','Div Sec','GN Div','APPROVED','WITHDRAWN',NULL,NULL,'2026-03-02 16:35:05'),(8007,8007,3,'DECEASED_BODY','Nayana','Jayasinghe','198034567897',NULL,'FEMALE','1950-12-12','B-','123 Ash','Ampara','Div Sec','GN Div','APPROVED','GIVEN',NULL,NULL,'2026-03-02 16:35:05'),(8008,8008,3,'DECEASED_BODY','Sriyani','Mendis','198034567898',NULL,'FEMALE','1955-03-03','O+','456 Birch','Ampara','Div Sec','GN Div','APPROVED','GIVEN',NULL,NULL,'2026-03-02 16:35:05'),(8009,8009,3,'DECEASED_BODY','Malini','Fonseka','198034567899',NULL,'FEMALE','1960-07-07','A+','789 Walnut','Ampara','Div Sec','GN Div','APPROVED','GIVEN',NULL,NULL,'2026-03-02 16:35:05'),(8010,8010,3,'DECEASED_BODY','Geetha','Kumarasinghe','198034567900',NULL,'FEMALE','1965-09-09','B+','321 Spruce','Ampara','Div Sec','GN Div','APPROVED','GIVEN',NULL,NULL,'2026-03-02 16:35:05'),(8011,91,2,'NONE','Financial','Donor 1','NIC-7223',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8012,92,2,'NONE','Financial','Donor 2','NIC-1441',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8013,93,2,'NONE','Financial','Donor 3','NIC-1811',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8014,94,2,'NONE','Financial','Donor 4','NIC-6505',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8015,95,2,'NONE','Financial','Donor 5','NIC-4495',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8016,96,2,'NONE','Financial','Donor 6','NIC-7664',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8017,97,2,'NONE','Financial','Donor 7','NIC-2036',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8018,98,2,'NONE','Financial','Donor 8','NIC-7795',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8019,99,2,'NONE','Financial','Donor 9','NIC-1800',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8020,100,2,'NONE','Financial','Donor 10','NIC-8669',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8021,101,2,'NONE','Financial','Donor 11','NIC-3467',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8022,102,2,'NONE','Financial','Donor 12','NIC-9147',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8023,103,2,'NONE','Financial','Donor 13','NIC-7780',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8024,104,2,'NONE','Financial','Donor 14','NIC-3492',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8025,105,2,'NONE','Financial','Donor 15','NIC-8175',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8026,106,2,'NONE','Financial','Donor 16','NIC-1837',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8027,107,2,'NONE','Financial','Donor 17','NIC-1369',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8028,108,2,'NONE','Financial','Donor 18','NIC-3051',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8029,109,2,'NONE','Financial','Donor 19','NIC-5168',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8030,110,2,'NONE','Financial','Donor 20','NIC-6110',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8031,111,2,'NONE','Financial','Donor 21','NIC-7598',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8032,112,2,'NONE','Financial','Donor 22','NIC-3631',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8033,113,2,'NONE','Financial','Donor 23','NIC-6128',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8034,114,2,'NONE','Financial','Donor 24','NIC-8977',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8035,115,2,'NONE','Financial','Donor 25','NIC-2693',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8036,116,2,'NONE','Financial','Donor 26','NIC-6417',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8037,117,2,'NONE','Financial','Donor 27','NIC-1902',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8038,118,2,'NONE','Financial','Donor 28','NIC-6969',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8039,119,2,'NONE','Financial','Donor 29','NIC-2891',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43'),(8040,120,2,'NONE','Financial','Donor 30','NIC-4302',NULL,'OTHER','1990-01-01',NULL,'','','','','APPROVED','PENDING',NULL,NULL,'2026-04-02 07:10:43');
/*!40000 ALTER TABLE `donors` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `hospitals`
--

DROP TABLE IF EXISTS `hospitals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `hospitals` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `registration_number` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `district` varchar(50) NOT NULL,
  `facility_type` varchar(50) NOT NULL,
  `cmo_name` varchar(255) NOT NULL,
  `cmo_nic` varchar(20) NOT NULL,
  `medical_license_number` varchar(50) NOT NULL,
  `verification_status` enum('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
  PRIMARY KEY (`id`),
  UNIQUE KEY `registration_number` (`registration_number`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_hospital_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hospitals`
--

LOCK TABLES `hospitals` WRITE;
/*!40000 ALTER TABLE `hospitals` DISABLE KEYS */;
INSERT INTO `hospitals` VALUES (1,66,'REG-HSP-1','Hospital Jaffna 1','Address 1','Jaffna','General','Dr. CMO 1','197022504217','LIC-1','APPROVED'),(2,67,'REG-HSP-2','Hospital Jaffna 2','Address 2','Jaffna','General','Dr. CMO 2','197046647025','LIC-2','APPROVED'),(3,68,'REG-HSP-3','Hospital Anuradhapura 3','Address 3','Anuradhapura','General','Dr. CMO 3','197021463398','LIC-3','APPROVED'),(4,69,'REG-HSP-4','Hospital Mannar 4','Address 4','Mannar','General','Dr. CMO 4','197042392408','LIC-4','APPROVED'),(5,70,'REG-HSP-5','Hospital Moneragala 5','Address 5','Moneragala','General','Dr. CMO 5','197086223207','LIC-5','APPROVED'),(6,71,'REG-HSP-6','Hospital Kurunegala 6','Address 6','Kurunegala','General','Dr. CMO 6','197096766773','LIC-6','APPROVED'),(7,72,'REG-HSP-7','Hospital Kilinochchi 7','Address 7','Kilinochchi','General','Dr. CMO 7','197012142306','LIC-7','APPROVED'),(8,73,'REG-HSP-8','Hospital Batticaloa 8','Address 8','Batticaloa','General','Dr. CMO 8','197014725805','LIC-8','APPROVED'),(9,74,'REG-HSP-9','Hospital Batticaloa 9','Address 9','Batticaloa','General','Dr. CMO 9','197087197801','LIC-9','APPROVED'),(10,75,'REG-HSP-10','Hospital Jaffna 10','Address 10','Jaffna','General','Dr. CMO 10','197097566524','LIC-10','APPROVED'),(11,76,'REG-HSP-11','Hospital Anuradhapura 11','Address 11','Anuradhapura','General','Dr. CMO 11','197024660757','LIC-11','APPROVED'),(12,77,'REG-HSP-12','Hospital Nuwara Eliya 12','Address 12','Nuwara Eliya','General','Dr. CMO 12','197032956800','LIC-12','APPROVED'),(13,78,'REG-HSP-13','Hospital Galle 13','Address 13','Galle','General','Dr. CMO 13','197088253116','LIC-13','APPROVED'),(14,79,'REG-HSP-14','Hospital Gampaha 14','Address 14','Gampaha','General','Dr. CMO 14','197086963689','LIC-14','APPROVED'),(15,80,'REG-HSP-15','Hospital Matara 15','Address 15','Matara','General','Dr. CMO 15','197072664868','LIC-15','APPROVED');
/*!40000 ALTER TABLE `hospitals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `medical_schools`
--

DROP TABLE IF EXISTS `medical_schools`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `medical_schools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `school_name` varchar(255) NOT NULL,
  `university_affiliation` varchar(255) NOT NULL,
  `ugc_accreditation_number` varchar(50) NOT NULL,
  `address` text NOT NULL,
  `district` varchar(50) NOT NULL,
  `contact_person_name` varchar(255) NOT NULL,
  `contact_person_phone` varchar(15) NOT NULL,
  `verification_status` enum('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ugc_number` (`ugc_accreditation_number`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_medschool_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `medical_schools`
--

LOCK TABLES `medical_schools` WRITE;
/*!40000 ALTER TABLE `medical_schools` DISABLE KEYS */;
INSERT INTO `medical_schools` VALUES (1,81,'Med Faculty 1','University 1','UGC-ACC-1','Campus Address 1','Ampara','Dean 1','0113617535','APPROVED'),(2,82,'Med Faculty 2','University 2','UGC-ACC-2','Campus Address 2','Kurunegala','Dean 2','0116191983','APPROVED'),(3,83,'Med Faculty 3','University 3','UGC-ACC-3','Campus Address 3','Matara','Dean 3','0112725826','APPROVED'),(4,84,'Med Faculty 4','University 4','UGC-ACC-4','Campus Address 4','Trincomalee','Dean 4','0118814900','APPROVED'),(5,85,'Med Faculty 5','University 5','UGC-ACC-5','Campus Address 5','Batticaloa','Dean 5','0111060772','APPROVED'),(6,86,'Med Faculty 6','University 6','UGC-ACC-6','Campus Address 6','Ratnapura','Dean 6','0112782849','APPROVED'),(7,87,'Med Faculty 7','University 7','UGC-ACC-7','Campus Address 7','Hambantota','Dean 7','0111368121','APPROVED'),(8,88,'Med Faculty 8','University 8','UGC-ACC-8','Campus Address 8','Mannar','Dean 8','0116270425','APPROVED'),(9,89,'Med Faculty 9','University 9','UGC-ACC-9','Campus Address 9','Anuradhapura','Dean 9','0118272666','APPROVED'),(10,90,'Med Faculty 10','University 10','UGC-ACC-10','Campus Address 10','Batticaloa','Dean 10','0119289340','APPROVED');
/*!40000 ALTER TABLE `medical_schools` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `next_of_kin`
--

DROP TABLE IF EXISTS `next_of_kin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `next_of_kin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donor_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `relationship` varchar(50) NOT NULL,
  `nic_number` varchar(20) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `donor_id` (`donor_id`),
  CONSTRAINT `fk_kin_donor` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `next_of_kin`
--

LOCK TABLES `next_of_kin` WRITE;
/*!40000 ALTER TABLE `next_of_kin` DISABLE KEYS */;
INSERT INTO `next_of_kin` VALUES (1,3,'Kin for 3','Relative','198011473475','0714445553',NULL),(2,5,'Kin for 5','Relative','198053395856','0714445555',NULL),(3,6,'Kin for 6','Relative','198045406006','0714445556',NULL),(4,7,'Kin for 7','Relative','198024763294','0714445557',NULL),(5,9,'Kin for 9','Relative','198038819440','0714445559',NULL),(6,12,'Kin for 12','Relative','198088756073','07144455512',NULL),(7,14,'Kin for 14','Relative','198071784162','07144455514',NULL),(8,17,'Kin for 17','Relative','198079725877','07144455517',NULL),(9,19,'Kin for 19','Relative','198014889152','07144455519',NULL),(10,21,'Kin for 21','Relative','198019565063','07144455521',NULL),(11,22,'Kin for 22','Relative','198018719431','07144455522',NULL),(12,24,'Kin for 24','Relative','198027867572','07144455524',NULL),(13,26,'Kin for 26','Relative','198062418499','07144455526',NULL),(14,29,'Kin for 29','Relative','198076218584','07144455529',NULL),(15,30,'Kin for 30','Relative','198082625247','07144455530',NULL),(16,31,'Kin for 31','Relative','198097793068','07144455531',NULL),(17,39,'Kin for 39','Relative','198020345704','07144455539',NULL),(18,42,'Kin for 42','Relative','198011686590','07144455542',NULL),(19,43,'Kin for 43','Relative','198011869421','07144455543',NULL),(20,45,'Kin for 45','Relative','198011016166','07144455545',NULL),(21,51,'Kin for 51','Relative','198043455282','07144455551',NULL),(22,53,'Kin for 53','Relative','198088503373','07144455553',NULL),(23,64,'Next of Kin','Son','199033333333','0773333333',NULL);
/*!40000 ALTER TABLE `next_of_kin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) DEFAULT 'GENERAL',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_notification_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organ_requests`
--

DROP TABLE IF EXISTS `organ_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organ_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `hospital_id` int(11) NOT NULL,
  `organ_id` int(11) NOT NULL,
  `priority_level` enum('NORMAL','URGENT','CRITICAL') DEFAULT 'NORMAL',
  `status` enum('OPEN','MATCHED','CLOSED') DEFAULT 'OPEN',
  `patient_details` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_request_hospital` (`hospital_id`),
  KEY `fk_request_organ` (`organ_id`),
  CONSTRAINT `fk_request_hospital` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`),
  CONSTRAINT `fk_request_organ` FOREIGN KEY (`organ_id`) REFERENCES `organs` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organ_requests`
--

LOCK TABLES `organ_requests` WRITE;
/*!40000 ALTER TABLE `organ_requests` DISABLE KEYS */;
INSERT INTO `organ_requests` VALUES (1,1,1,'NORMAL','OPEN',NULL,'2026-02-15 07:44:31'),(2,2,6,'NORMAL','OPEN',NULL,'2026-02-15 07:44:31'),(3,3,3,'NORMAL','OPEN',NULL,'2026-02-15 07:44:31'),(4,4,7,'NORMAL','OPEN',NULL,'2026-02-15 07:44:31'),(5,5,7,'NORMAL','OPEN',NULL,'2026-02-15 07:44:31'),(6,6,2,'NORMAL','OPEN',NULL,'2026-02-15 07:44:31'),(7,7,1,'NORMAL','OPEN',NULL,'2026-02-15 07:44:31'),(8,8,2,'NORMAL','OPEN',NULL,'2026-02-15 07:44:31'),(9,9,2,'NORMAL','OPEN',NULL,'2026-02-15 07:44:31'),(10,10,4,'NORMAL','OPEN',NULL,'2026-02-15 07:44:31'),(11,11,7,'NORMAL','OPEN',NULL,'2026-02-15 07:44:31'),(12,12,8,'NORMAL','OPEN',NULL,'2026-02-15 07:44:31'),(13,13,1,'NORMAL','OPEN',NULL,'2026-02-15 07:44:31'),(14,14,7,'NORMAL','OPEN',NULL,'2026-02-15 07:44:31'),(15,15,8,'NORMAL','OPEN',NULL,'2026-02-15 07:44:31');
/*!40000 ALTER TABLE `organ_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `organs`
--

DROP TABLE IF EXISTS `organs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `organs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_available` tinyint(1) DEFAULT 1,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `organs`
--

LOCK TABLES `organs` WRITE;
/*!40000 ALTER TABLE `organs` DISABLE KEYS */;
INSERT INTO `organs` (`id`, `name`, `description`, `is_available`) VALUES
  (1, 'Kidney', 'Before Death (Living Donor)', 1),
  (2, 'Part of Liver', 'Before Death (Living Donor)', 1),
  (3, 'Bone Marrow', 'Before Death (Living Donor)', 1),
  (4, 'Cornea', 'After Death Donation', 1),
  (5, 'Skin', 'After Death Donation', 1),
  (6, 'Bones', 'After Death Donation', 1),
  (7, 'Heart Valves', 'After Death Donation', 1),
  (8, 'Tendons', 'After Death Donation', 1),
  (9, 'Full Body(After death)', 'Educational Purpose', 1);
/*!40000 ALTER TABLE `organs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recipients`
--

DROP TABLE IF EXISTS `recipients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `recipients` (
  `recipient_id` int(11) NOT NULL AUTO_INCREMENT,
  `nic` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `organ_received` varchar(50) NOT NULL,
  `surgery_date` date NOT NULL,
  `treatment_notes` text DEFAULT NULL,
  `hospital_registration_no` varchar(20) NOT NULL,
  `status` varchar(20) DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`recipient_id`),
  KEY `nic` (`nic`),
  KEY `hospital_reg` (`hospital_registration_no`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recipients`
--

LOCK TABLES `recipients` WRITE;
/*!40000 ALTER TABLE `recipients` DISABLE KEYS */;
/*!40000 ALTER TABLE `recipients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `success_stories`
--

DROP TABLE IF EXISTS `success_stories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `success_stories` (
  `story_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `success_date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`story_id`),
  KEY `fk_story_user_id` (`user_id`),
  CONSTRAINT `fk_story_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `success_stories`
--

LOCK TABLES `success_stories` WRITE;
/*!40000 ALTER TABLE `success_stories` DISABLE KEYS */;
INSERT INTO `success_stories` VALUES (1,'Success Story from Hospital 3','This is an inspirational success story.','2026-02-15','HOSP003','Approved','2026-03-05 10:19:22','2026-03-05 10:19:22'),(2,'Success Story from Hospital 6','Patient recovered wonderfully.','2026-02-16','HOSP006','Pending','2026-03-05 10:19:22','2026-03-05 10:19:22');
/*!40000 ALTER TABLE `success_stories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `success_stories_new`
--

DROP TABLE IF EXISTS `success_stories_new`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `success_stories_new` (
  `story_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `success_date` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`story_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `fk_story_new_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `success_stories_new`
--

LOCK TABLES `success_stories_new` WRITE;
/*!40000 ALTER TABLE `success_stories_new` DISABLE KEYS */;
/*!40000 ALTER TABLE `success_stories_new` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `support_requests`
--

DROP TABLE IF EXISTS `support_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `support_requests` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `patient_nic` varchar(20) NOT NULL,
  `patient_name` varchar(255) NOT NULL,
  `patient_type` varchar(100) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('PENDING','APPROVED','REJECTED') DEFAULT 'PENDING',
  `submitted_date` date NOT NULL,
  `reviewed_date` date DEFAULT NULL,
  `reviewed_by` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `patient_nic` (`patient_nic`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `support_requests`
--

LOCK TABLES `support_requests` WRITE;
/*!40000 ALTER TABLE `support_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `support_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `test_results`
--

DROP TABLE IF EXISTS `test_results`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `test_results` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donor_id` int(11) NOT NULL,
  `test_name` varchar(100) NOT NULL,
  `result_value` varchar(255) DEFAULT NULL,
  `document_path` varchar(255) DEFAULT NULL,
  `test_date` date NOT NULL,
  `verified_by_hospital_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `donor_id` (`donor_id`),
  CONSTRAINT `fk_test_donor` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `test_results`
--

LOCK TABLES `test_results` WRITE;
/*!40000 ALTER TABLE `test_results` DISABLE KEYS */;
/*!40000 ALTER TABLE `test_results` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `upcoming_appointments`
--

DROP TABLE IF EXISTS `upcoming_appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `upcoming_appointments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donor_id` int(11) NOT NULL,
  `hospital_registration_no` varchar(50) DEFAULT NULL,
  `test_type` varchar(100) DEFAULT NULL,
  `test_date` date DEFAULT NULL,
  `status` varchar(50) DEFAULT 'Pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `donor_id` (`donor_id`),
  CONSTRAINT `upcoming_appointments_ibfk_1` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `upcoming_appointments`
--

LOCK TABLES `upcoming_appointments` WRITE;
/*!40000 ALTER TABLE `upcoming_appointments` DISABLE KEYS */;
INSERT INTO `upcoming_appointments` VALUES (1,1,'2026-04-05','09:00:00','National Hospital Colombo','Approved',NULL,'2026-04-01 20:32:39'),(2,1,'2026-04-10','11:30:00','Asiri Central Hospital','Rejected','Recent medical condition detected','2026-04-01 20:32:39'),(3,1,'2026-04-18','08:45:00','Lanka Hospital','Approved',NULL,'2026-04-01 20:32:39'),(4,2,'2026-04-07','10:15:00','National Hospital Colombo','Approved',NULL,'2026-04-01 20:32:39'),(5,2,'2026-04-12','01:00:00','Nawaloka Hospital','Rejected','Blood test results not suitable','2026-04-01 20:32:39'),(6,2,'2026-04-20','09:30:00','Durdans Hospital','Approved',NULL,'2026-04-01 20:32:39');
/*!40000 ALTER TABLE `upcoming_appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `role` enum('F_ADMIN','AC_ADMIN','D_ADMIN','U_ADMIN','DONOR','HOSPITAL','MEDICAL_SCHOOL','CUSTODIAN') NOT NULL,
  `status` enum('PENDING','ACTIVE','SUSPENDED') DEFAULT 'PENDING',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8012 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'chamal_0','$2y$10$acZZrVLId4qa6Y6FeCXPo.4Bzu7v6xvY2xIJIx1Z5wBXt2Yt94EEC','admin1@lifeconnect.lk','','U_ADMIN','ACTIVE','2026-02-15 07:44:30','2026-03-03 04:04:00'),(2,'admin_2','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','admin2@lifeconnect.lk',NULL,'F_ADMIN','ACTIVE','2026-02-15 07:44:30','2026-03-03 04:04:07'),(3,'admin_3','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','admin3@lifeconnect.lk',NULL,'','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(4,'admin_4','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','admin4@lifeconnect.lk',NULL,'','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(5,'admin_5','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','admin5@lifeconnect.lk',NULL,'','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(6,'donor_1','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_1@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(7,'donor_2','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_2@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(8,'donor_3','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_3@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(9,'donor_4','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_4@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(10,'donor_5','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_5@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(11,'donor_6','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_6@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(12,'donor_7','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_7@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(13,'donor_8','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_8@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(14,'donor_9','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_9@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(15,'donor_10','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_10@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(16,'donor_11','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_11@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(17,'donor_12','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_12@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(18,'donor_13','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_13@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(19,'donor_14','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_14@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(20,'donor_15','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_15@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(21,'donor_16','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_16@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(22,'donor_17','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_17@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(23,'donor_18','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_18@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(24,'donor_19','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_19@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(25,'donor_20','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_20@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(26,'donor_21','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_21@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(27,'donor_22','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_22@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(28,'donor_23','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_23@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(29,'donor_24','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_24@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(30,'donor_25','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_25@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(31,'donor_26','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_26@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(32,'donor_27','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_27@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(33,'donor_28','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_28@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(34,'donor_29','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_29@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(35,'donor_30','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_30@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(36,'donor_31','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_31@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(37,'donor_32','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_32@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(38,'donor_33','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_33@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(39,'donor_34','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_34@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(40,'donor_35','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_35@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(41,'donor_36','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_36@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(42,'donor_37','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_37@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(43,'donor_38','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_38@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(44,'donor_39','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_39@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(45,'donor_40','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_40@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(46,'donor_41','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_41@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(47,'donor_42','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_42@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(48,'donor_43','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_43@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(49,'donor_44','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_44@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(50,'donor_45','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_45@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(51,'donor_46','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_46@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(52,'donor_47','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_47@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(53,'donor_48','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_48@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(54,'donor_49','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_49@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:30','2026-02-15 07:44:30'),(55,'donor_50','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_50@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-02-16 05:46:42'),(56,'donor_51','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_51@example.com',NULL,'DONOR','PENDING','2026-02-15 07:44:31','2026-02-15 09:22:50'),(57,'donor_52','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_52@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(58,'donor_53','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_53@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(59,'donor_54','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_54@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(60,'donor_55','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_55@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(61,'donor_56','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_56@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(62,'donor_57','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_57@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(63,'donor_58','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_58@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(64,'donor_59','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_59@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(65,'donor_60','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor_60@example.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(66,'hospital_1','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','hospital_1@hosp.lk',NULL,'HOSPITAL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(67,'hospital_2','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','hospital_2@hosp.lk',NULL,'HOSPITAL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(68,'hospital_3','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','hospital_3@hosp.lk',NULL,'HOSPITAL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(69,'hospital_4','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','hospital_4@hosp.lk',NULL,'HOSPITAL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(70,'hospital_5','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','hospital_5@hosp.lk',NULL,'HOSPITAL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(71,'hospital_6','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','hospital_6@hosp.lk',NULL,'HOSPITAL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(72,'hospital_7','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','hospital_7@hosp.lk',NULL,'HOSPITAL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(73,'hospital_8','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','hospital_8@hosp.lk',NULL,'HOSPITAL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(74,'hospital_9','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','hospital_9@hosp.lk',NULL,'HOSPITAL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(75,'hospital_10','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','hospital_10@hosp.lk',NULL,'HOSPITAL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(76,'hospital_11','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','hospital_11@hosp.lk',NULL,'HOSPITAL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(77,'hospital_12','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','hospital_12@hosp.lk',NULL,'HOSPITAL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(78,'hospital_13','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','hospital_13@hosp.lk',NULL,'HOSPITAL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(79,'hospital_14','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','hospital_14@hosp.lk',NULL,'HOSPITAL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(80,'hospital_15','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','hospital_15@hosp.lk',NULL,'HOSPITAL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(81,'medschool_1','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','medschool_1@uni.lk',NULL,'MEDICAL_SCHOOL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(82,'medschool_2','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','medschool_2@uni.lk',NULL,'MEDICAL_SCHOOL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(83,'medschool_3','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','medschool_3@uni.lk',NULL,'MEDICAL_SCHOOL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(84,'medschool_4','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','medschool_4@uni.lk',NULL,'MEDICAL_SCHOOL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(85,'medschool_5','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','medschool_5@uni.lk',NULL,'MEDICAL_SCHOOL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(86,'medschool_6','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','medschool_6@uni.lk',NULL,'MEDICAL_SCHOOL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(87,'medschool_7','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','medschool_7@uni.lk',NULL,'MEDICAL_SCHOOL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(88,'medschool_8','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','medschool_8@uni.lk',NULL,'MEDICAL_SCHOOL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(89,'medschool_9','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','medschool_9@uni.lk',NULL,'MEDICAL_SCHOOL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(90,'medschool_10','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','medschool_10@uni.lk',NULL,'MEDICAL_SCHOOL','ACTIVE','2026-02-15 07:44:31','2026-02-15 07:44:31'),(91,'findonor_1','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_1@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(92,'findonor_2','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_2@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(93,'findonor_3','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_3@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(94,'findonor_4','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_4@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(95,'findonor_5','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_5@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(96,'findonor_6','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_6@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(97,'findonor_7','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_7@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(98,'findonor_8','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_8@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(99,'findonor_9','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_9@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(100,'findonor_10','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_10@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(101,'findonor_11','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_11@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(102,'findonor_12','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_12@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(103,'findonor_13','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_13@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(104,'findonor_14','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_14@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(105,'findonor_15','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_15@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(106,'findonor_16','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_16@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(107,'findonor_17','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_17@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(108,'findonor_18','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_18@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(109,'findonor_19','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_19@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(110,'findonor_20','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_20@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(111,'findonor_21','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_21@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(112,'findonor_22','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_22@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(113,'findonor_23','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_23@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(114,'findonor_24','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_24@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(115,'findonor_25','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_25@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(116,'findonor_26','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_26@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(117,'findonor_27','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_27@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(118,'findonor_28','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_28@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(119,'findonor_29','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_29@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(120,'findonor_30','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','findonor_30@fin.com',NULL,'DONOR','ACTIVE','2026-02-15 07:44:31','2026-04-02 07:10:11'),(123,'chamal_10','$2y$10$CwNB5eQjwUo9.qyn.9njqexOmJpFuYkp7EhpPINDrYFQYM99ARMGq','chamalchamuditha@gmail.com','0710000000','DONOR','ACTIVE','2026-02-16 09:13:28','2026-04-02 07:10:11'),(124,'pasindu_0','$2y$10$xZQ22vDFf6BpK498Z0JyzO3..w9zhnWM74pYlNqdrFuqOKaiTrnQa','pasindu@ucsc.lk','0716379044','DONOR','ACTIVE','2026-02-16 09:24:24','2026-04-02 07:10:11'),(125,'dilanka_1','$2y$10$.2o03he9ryirTzxbmQfSfOMnflTtDDVRGnIn2PldWhwFtGMEflfGC','dilanka1@gmail.com','0716377098','DONOR','PENDING','2026-02-16 10:36:10','2026-02-16 10:36:10'),(126,'sahasna_1','$2y$10$emsCpYNMiuoHbI7VCxE3qOr5S02d7.qhmdJ0nVgVOh1f2zPM1Esfi','sahasna@gmail.com','0716377987','DONOR','ACTIVE','2026-02-16 11:31:34','2026-02-19 19:03:00'),(127,'deceased_donor_1','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','deceased1@example.com',NULL,'DONOR','ACTIVE','2026-02-16 13:17:00','2026-02-16 13:17:00'),(128,'200335212710','$2y$10$kDb7SFNHX18/FpSHEzIYguXbfz1XjTWIvFTy0zgY4yMIstKWdQtKe','drte@gmail.com','098766543','CUSTODIAN','ACTIVE','2026-02-24 09:01:54','2026-02-24 09:01:54'),(8001,'donor_8001','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor8001@example.com',NULL,'DONOR','ACTIVE','2026-03-02 16:35:05','2026-03-02 16:35:05'),(8002,'donor_8002','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor8002@example.com',NULL,'DONOR','ACTIVE','2026-03-02 16:35:05','2026-03-02 16:35:05'),(8003,'donor_8003','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor8003@example.com',NULL,'DONOR','ACTIVE','2026-03-02 16:35:05','2026-03-02 16:35:05'),(8004,'donor_8004','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor8004@example.com',NULL,'DONOR','ACTIVE','2026-03-02 16:35:05','2026-03-02 16:35:05'),(8005,'donor_8005','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor8005@example.com',NULL,'DONOR','ACTIVE','2026-03-02 16:35:05','2026-03-02 16:35:05'),(8006,'donor_8006','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor8006@example.com',NULL,'DONOR','ACTIVE','2026-03-02 16:35:05','2026-03-02 16:35:05'),(8007,'donor_8007','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor8007@example.com',NULL,'DONOR','ACTIVE','2026-03-02 16:35:05','2026-03-02 16:35:05'),(8008,'donor_8008','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor8008@example.com',NULL,'DONOR','ACTIVE','2026-03-02 16:35:05','2026-03-02 16:35:05'),(8009,'donor_8009','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor8009@example.com',NULL,'DONOR','ACTIVE','2026-03-02 16:35:05','2026-03-02 16:35:05'),(8010,'donor_8010','$2y$10$71qxyMwb3CqfrSkYr0oDW.Ri080Ss9TEJbIt7/QsKbj/gcVh//ZSm','donor8010@example.com',NULL,'DONOR','ACTIVE','2026-03-02 16:35:05','2026-03-02 16:35:05'),(8011,'200451313123','$2y$10$/AJR1u6BFUKtiBEFw3M0k.8aSLDM7bMSgXecrjmrvfwc3MECWonOu','example09@gmail.com','0764532176','CUSTODIAN','ACTIVE','2026-03-05 10:46:03','2026-03-05 10:46:03');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `witnesses`
--

DROP TABLE IF EXISTS `witnesses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `witnesses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `donor_id` int(11) NOT NULL,
  `organ_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `nic_number` varchar(20) NOT NULL,
  `contact_number` varchar(15) NOT NULL,
  `address` text DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `donor_id` (`donor_id`),
  KEY `fk_wit_organ` (`organ_id`),
  CONSTRAINT `fk_wit_organ` FOREIGN KEY (`organ_id`) REFERENCES `organs` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_witness_donor` FOREIGN KEY (`donor_id`) REFERENCES `donors` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `witnesses`
--

LOCK TABLES `witnesses` WRITE;
/*!40000 ALTER TABLE `witnesses` DISABLE KEYS */;
INSERT INTO `witnesses` VALUES (1,4,NULL,'Witness A for 4','199067034499','0771112224',NULL),(2,8,NULL,'Witness A for 8','199023475793','0771112228',NULL),(3,13,NULL,'Witness A for 13','199082321041','07711122213',NULL),(4,16,NULL,'Witness A for 16','199051062002','07711122216',NULL),(5,20,NULL,'Witness A for 20','199013017655','07711122220',NULL),(6,28,NULL,'Witness A for 28','199027831914','07711122228',NULL),(7,33,NULL,'Witness A for 33','199019841157','07711122233',NULL),(8,36,NULL,'Witness A for 36','199075362239','07711122236',NULL),(9,38,NULL,'Witness A for 38','199023816899','07711122238',NULL),(10,40,NULL,'Witness A for 40','199029343361','07711122240',NULL),(11,41,NULL,'Witness A for 41','199068887791','07711122241',NULL),(12,46,NULL,'Witness A for 46','199081480301','07711122246',NULL),(13,48,NULL,'Witness A for 48','199067907502','07711122248',NULL),(14,52,NULL,'Witness A for 52','199083669424','07711122252',NULL),(15,57,NULL,'Witness A for 57','199057432145','07711122257',NULL),(16,58,NULL,'Witness A for 58','199068243865','07711122258',NULL),(17,59,NULL,'Witness A for 59','199037916675','07711122259',NULL);
/*!40000 ALTER TABLE `witnesses` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-02 15:12:29
