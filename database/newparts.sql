-- Add fields for enhanced body donation workflow to case_institution_status
ALTER TABLE case_institution_status 
ADD COLUMN submitted_checklist_json TEXT DEFAULT NULL COMMENT 'JSON of items ticked by Custodian',
ADD COLUMN missing_documents_json TEXT DEFAULT NULL COMMENT 'JSON of items marked missing by Medical School',
ADD COLUMN rejection_reason_code VARCHAR(50) DEFAULT NULL COMMENT 'e.g. DOCS_MISSING, UNREADABLE',
ADD COLUMN rejection_reason_text TEXT DEFAULT NULL COMMENT 'Detailed rejection message',
ADD COLUMN handover_date DATE DEFAULT NULL,
ADD COLUMN handover_time TIME DEFAULT NULL,
ADD COLUMN handover_message TEXT DEFAULT NULL;

-- Ensure case_institution_status has correct enum values if not already present
-- (Note: MariaDB/MySQL doesn't support easy ALTER for enums, but we assume the existing ones cover it or we use VARCHAR)

-- CONTACT MESSAGES TABLE
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    subject VARCHAR(100) DEFAULT 'General Inquiry',
    message TEXT NOT NULL,
    status ENUM('PENDING', 'READ', 'ARCHIVED') DEFAULT 'PENDING',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ANATOMICAL USAGE ENHANCEMENTS
-- 1. Fields for Current Condition and Primary Dept on the case record
ALTER TABLE case_institution_status 
ADD COLUMN IF NOT EXISTS current_condition ENUM('Good', 'Moderate', 'Limited Use') DEFAULT 'Good',
ADD COLUMN IF NOT EXISTS assigned_department VARCHAR(100) DEFAULT 'Anatomy Department';

-- 2. Enhanced Usage Logging Columns
-- Note: usage_type already exists, but we'll add more specific tracking
ALTER TABLE body_usage_logs
ADD COLUMN IF NOT EXISTS usage_department VARCHAR(100) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS subject_area VARCHAR(100) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS handled_by VARCHAR(100) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS duration VARCHAR(50) DEFAULT NULL,
ADD COLUMN IF NOT EXISTS other_notes TEXT DEFAULT NULL;

-- 3. Recognition (Appreciation Letters)
CREATE TABLE IF NOT EXISTS appreciation_letters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usage_log_id INT NOT NULL,
    ref_number VARCHAR(50) UNIQUE NOT NULL,
    issued_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    issued_by_id INT DEFAULT NULL,
    status ENUM('ISSUED', 'REVOKED') DEFAULT 'ISSUED'
);

-- CUSTODIAN SECURITY FLOW
ALTER TABLE `users` ADD COLUMN `must_change_credentials` TINYINT(1) DEFAULT 0 AFTER `status`;

-- SUCCESS STORIES ENHANCEMENTS (VERSATILE DESIGN)
ALTER TABLE success_stories 
RENAME COLUMN hospital_registration_no TO institution_reg_no;

ALTER TABLE success_stories
ADD COLUMN IF NOT EXISTS institution_id INT AFTER user_id,
ADD COLUMN IF NOT EXISTS institution_type ENUM('MEDICAL_SCHOOL', 'HOSPITAL') NOT NULL DEFAULT 'MEDICAL_SCHOOL' AFTER institution_id,
ADD COLUMN IF NOT EXISTS story_type ENUM('IMPACT', 'INSPIRATIONAL', 'CASE') NOT NULL DEFAULT 'IMPACT' AFTER institution_type,
ADD COLUMN IF NOT EXISTS author_name VARCHAR(255) AFTER description,
ADD COLUMN IF NOT EXISTS donors_count INT DEFAULT 0 AFTER author_name,
ADD COLUMN IF NOT EXISTS students_helped INT DEFAULT 0 AFTER donors_count,
ADD COLUMN IF NOT EXISTS review_message TEXT AFTER status;
