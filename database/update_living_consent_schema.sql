-- Add Nationality to main donors table
ALTER TABLE `donors` ADD COLUMN `nationality` VARCHAR(100) DEFAULT 'Sri Lankan' AFTER `nic_number`;

-- Table to store detailed living donation consent information
CREATE TABLE IF NOT EXISTS `living_donor_consents` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `donor_pledge_id` INT NOT NULL,
  
  -- Section B (Medical Information)
  `height` DECIMAL(5,2) DEFAULT NULL, -- in cm
  `weight` DECIMAL(5,2) DEFAULT NULL, -- in kg
  `previous_surgeries` TEXT DEFAULT NULL,
  `smoking_alcohol_status` VARCHAR(255) DEFAULT NULL,
  
  -- Section C (Recipient Details)
  `recipient_name` VARCHAR(255) DEFAULT NULL,
  `recipient_relationship` VARCHAR(100) DEFAULT NULL,
  `recipient_hospital` VARCHAR(255) DEFAULT NULL,
  
  -- Section D (Compatibility Info - Medical Staff)
  `blood_compatibility` VARCHAR(255) DEFAULT NULL,
  `tissue_typing` TEXT DEFAULT NULL,
  `medical_clearance_status` VARCHAR(100) DEFAULT 'Pending',
  
  -- Section F (Emergency Contact)
  `emergency_contact_name` VARCHAR(255) DEFAULT NULL,
  `emergency_relationship` VARCHAR(100) DEFAULT NULL,
  `emergency_phone` VARCHAR(20) DEFAULT NULL,
  
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  -- Using ON DELETE CASCADE so that if a pledge is deleted, the detailed consent is also removed
  FOREIGN KEY (`donor_pledge_id`) REFERENCES `donor_pledges`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
