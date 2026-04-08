<?php

class RegistrationModel {
    private $db;
    
    public function __construct($db)
    {
        $this->db = $db;
    }

    // ========================================
    // USER MANAGEMENT
    // ========================================

    /**
     * Create a new user account
     */
    public function createUser($username, $password, $role, $email = null, $phone = null, $status = 'pending')
    {
        $query = "INSERT INTO users (username, password_hash, role, email, phone, status, created_at) 
                  VALUES (:username, :password, :role, :email, :phone, :status, NOW())";
        
        $params = [
            ':username' => $username,
            ':password' => $password, // Should already be hashed
            ':role' => $role,
            ':email' => $email,
            ':phone' => $phone,
            ':status' => $status
        ];
        
        return $this->db->insert($query, $params);
    }

    /**
     * Check if username already exists
     */
    public function usernameExists($username)
    {
        $query = "SELECT COUNT(*) as count FROM users WHERE username = :username";
        $result = $this->db->query($query, [':username' => $username]);
        return $result[0]['count'] > 0;
    }

    /**
     * Check if email already exists
     */
    public function emailExists($email)
    {
        $query = "SELECT COUNT(*) as count FROM users WHERE email = :email";
        $result = $this->db->query($query, [':email' => $email]);
        return $result[0]['count'] > 0;
    }

    /**
     * Check if NIC already exists
     */
    public function nicExists($nic)
    {
        $query = "SELECT COUNT(*) as count FROM donors WHERE nic_number = :nic";
        $result = $this->db->query($query, [':nic' => $nic]);
        return $result[0]['count'] > 0;
    }

    // ========================================
    // DONOR REGISTRATION
    // ========================================

    /**
     * Create base donor record
     */
    public function createDonor($userId, $personalData, $donorType)
    {
        // Map donor type to category_id and pledge_type
        $categoryId = 3; // JUST_DONOR
        $pledgeType = 'NONE';
        if ($donorType === 'live') { $pledgeType = 'LIVING'; }
        elseif ($donorType === 'deceased') { $pledgeType = 'DECEASED_ORGAN'; }
        elseif ($donorType === 'non') { $categoryId = 1; $pledgeType = 'NONE'; }

        $query = "INSERT INTO donors (
            user_id, category_id, pledge_type, first_name, last_name, gender, date_of_birth, 
            blood_group, nic_number, address, 
            grama_niladhari_division, district, divisional_secretariat,
            verification_status, consent_status
        ) VALUES (
            :user_id, :category_id, :pledge_type, :first_name, :last_name, :gender, :dob,
            :blood_group, :nic, :address,
            :gn_div, :district, :div_sec,
            'PENDING', 'PENDING'
        )";
        
        // Split full name into first and last name
        $nameParts = explode(' ', $personalData['full_name'], 2);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
        
        $params = [
            ':user_id' => $userId,
            ':category_id' => $categoryId,
            ':pledge_type' => $pledgeType,
            ':first_name' => $firstName,
            ':last_name' => $lastName,
            ':gender' => $personalData['gender'],
            ':dob' => $personalData['dob'],
            ':blood_group' => $personalData['blood_group'],
            ':nic' => $personalData['nic'],
            ':address' => $personalData['address'],
            ':gn_div' => $personalData['gn_division'],
            ':district' => $personalData['district'],
            ':div_sec' => $personalData['divisional_secretariat']
        ];
        
        return $this->db->insert($query, $params);
    }

    /**
     * Create live donor — handled via pledge_type in donors table now
     */
    public function createLiveDonor($donorId)
    {
        // In new schema, live donors are identified by donors.pledge_type = 'LIVING'
        // No separate table needed, returning donorId for compatibility
        return $donorId;
    }

    /**
     * Create deceased donor — handled via pledge_type in donors table now
     */
    public function createDeceasedDonor($donorId)
    {
        // In new schema, deceased donors are identified by donors.pledge_type = 'DECEASED_ORGAN'
        // No separate table needed, returning donorId for compatibility
        return $donorId;
    }

    /**
     * Add donor organs
     */
    public function addDonorOrgans($donorId, $organIds)
    {
        if (empty($organIds)) return true;
        
        $query = "INSERT INTO donor_pledges (donor_id, organ_id, status) 
                  VALUES (:donor_id, :organ_id, 'PENDING')";
        
        foreach ($organIds as $organId) {
            $this->db->insert($query, [
                ':donor_id' => $donorId,
                ':organ_id' => $organId
            ]);
        }
        
        return true;
    }

    /**
     * Get organ ID by name
     */
    public function getOrganIdByName($organName)
    {
        $query = "SELECT id FROM organs WHERE name = :name LIMIT 1";
        $result = $this->db->query($query, [':name' => $organName]);
        return $result ? $result[0]['id'] : null;
    }

    /**
     * Get all available organs
     */
    public function getAvailableOrgans()
    {
        $query = "SELECT id, name FROM organs WHERE is_available = 1";
        return $this->db->query($query);
    }

    // ========================================
    // WITNESS MANAGEMENT
    // ========================================

    /**
     * Add witnesses for live donor
     */
    public function addWitnesses($donorId, $witnesses)
    {
        $query = "INSERT INTO witnesses (donor_id, name, nic_number, contact_number) 
                  VALUES (:donor_id, :name, :nic, :phone)";
        
        foreach ($witnesses as $witness) {
            $this->db->insert($query, [
                ':donor_id' => $donorId,
                ':name' => $witness['name'],
                ':nic' => $witness['nic'],
                ':phone' => $witness['phone']
            ]);
        }
        
        return true;
    }

    // ========================================
    // LEGAL CUSTODIAN / NEXT OF KIN
    // ========================================

    /**
     * Add next of kin for deceased donor
     */
    public function addNextOfKin($donorId, $kinData)
    {
        $query = "INSERT INTO next_of_kin 
                  (donor_id, name, relationship, nic_number, contact_number, email) 
                  VALUES (:donor_id, :name, :relationship, :nic, :phone, :email)";
        
        return $this->db->insert($query, [
            ':donor_id' => $donorId,
            ':name' => $kinData['name'],
            ':relationship' => $kinData['relationship'],
            ':nic' => $kinData['nic'] ?? '',
            ':phone' => $kinData['phone'],
            ':email' => $kinData['email']
        ]);
    }

    // ========================================
    // FINANCIAL DONORS
    // ========================================

    /**
     * Create financial donor record
     */
    public function createFinancialDonor($userId)
    {
        $query = "INSERT INTO financial_donors (user_id, full_name, donation_frequency) 
                  VALUES (:user_id, '', 'ONETIME')";
        
        return $this->db->insert($query, [':user_id' => $userId]);
    }

    // ========================================
    // NON-DONORS (OPT-OUT)
    // ========================================

    /**
     * Create non-donor record
     */
    public function createNonDonor($donorId, $reason = null)
    {
        // In new schema, non-donors are handled via donors.pledge_type = 'NONE'
        $query = "UPDATE donors SET opt_out_reason = :reason WHERE id = :donor_id";
        
        return $this->db->update($query, [
            ':donor_id' => $donorId,
            ':reason' => $reason
        ]);
    }

    // ========================================
    // CONSENT MANAGEMENT
    // ========================================

    /**
     * Record consent
     */
    public function recordConsent($donorId, $consentType, $consentText, $ipAddress = null)
    {
        $query = "INSERT INTO consent_records 
                  (donor_id, consent_type, consent_text, consent_given, consent_date, ip_address) 
                  VALUES (:donor_id, :type, :text, 1, NOW(), :ip)";
        
        return $this->db->insert($query, [
            ':donor_id' => $donorId,
            ':type' => $consentType,
            ':text' => $consentText,
            ':ip' => $ipAddress
        ]);
    }

    /**
     * Get consent template
     */
    public function getConsentTemplate($consentType)
    {
        $query = "SELECT template_text FROM consent_templates 
                  WHERE consent_type = :type AND is_active = 1 
                  ORDER BY effective_date DESC LIMIT 1";
        
        $result = $this->db->query($query, [':type' => $consentType]);
        return $result ? $result[0]['template_text'] : null;
    }

    // ========================================
    // HOSPITAL REGISTRATION
    // ========================================

    /**
     * Register hospital
     */
    public function registerHospital($userId, $hospitalData, $cmoData)
    {
        $query = "INSERT INTO hospitals (
            user_id, registration_number, name, address, 
            district, facility_type, cmo_name, cmo_nic, medical_license_number,
            verification_status
        ) VALUES (
            :user_id, :reg_no, :name, :address,
            :district, :type, :cmo_name, :cmo_nic, :license,
            'PENDING'
        )";
        
        $params = [
            ':user_id' => $userId,
            ':reg_no' => $hospitalData['registration_number'],
            ':name' => $hospitalData['name'],
            ':address' => $hospitalData['address'],
            ':district' => $hospitalData['district'],
            ':type' => $hospitalData['type'],
            ':cmo_name' => $cmoData['name'],
            ':cmo_nic' => $cmoData['nic'],
            ':license' => $cmoData['license_number']
        ];
        
        return $this->db->insert($query, $params);
    }

    /**
     * Check if hospital registration number exists
     */
    public function hospitalRegNoExists($regNo)
    {
        $query = "SELECT COUNT(*) as count FROM hospitals WHERE registration_number = :reg_no";
        $result = $this->db->query($query, [':reg_no' => $regNo]);
        return $result[0]['count'] > 0;
    }

    // ========================================
    // MEDICAL SCHOOL REGISTRATION
    // ========================================

    /**
     * Register medical school
     */
    public function registerMedicalSchool($userId, $schoolData, $contactData)
    {
        $query = "INSERT INTO medical_schools (
            user_id, school_name, university_affiliation, ugc_accreditation_number,
            address, district, contact_person_name, contact_person_title,
            contact_person_email, contact_person_phone, verification_status, registration_date
        ) VALUES (
            :user_id, :name, :university, :ugc_number,
            :address, :district, :contact_name, :contact_title,
            :contact_email, :contact_phone, 'Pending', CURDATE()
        )";
        
        $params = [
            ':user_id' => $userId,
            ':name' => $schoolData['name'],
            ':university' => $schoolData['university'],
            ':ugc_number' => $schoolData['ugc_number'],
            ':address' => $schoolData['address'],
            ':district' => $schoolData['district'] ?? null,
            ':contact_name' => $contactData['name'],
            ':contact_title' => $contactData['title'],
            ':contact_email' => $contactData['email'],
            ':contact_phone' => $contactData['phone']
        ];
        
        return $this->db->insert($query, $params);
    }

    /**
     * Check if UGC number exists
     */
    public function ugcNumberExists($ugcNumber)
    {
        $query = "SELECT COUNT(*) as count FROM medical_schools 
                  WHERE ugc_accreditation_number = :ugc_number";
        $result = $this->db->query($query, [':ugc_number' => $ugcNumber]);
        return $result[0]['count'] > 0;
    }

    // ========================================
    // NOTIFICATION QUEUE
    // ========================================

    /**
     * Queue notification
     */
    public function queueNotification($email, $phone, $type, $subject, $message)
    {
        $query = "INSERT INTO notification_queue 
                  (recipient_email, recipient_phone, notification_type, subject, message, status) 
                  VALUES (:email, :phone, :type, :subject, :message, 'pending')";
        
        return $this->db->insert($query, [
            ':email' => $email,
            ':phone' => $phone,
            ':type' => $type,
            ':subject' => $subject,
            ':message' => $message
        ]);
    }

    // ========================================
    // AUDIT LOGGING
    // ========================================

    /**
     * Log registration action
     */
    public function logRegistration($userId, $action, $tableName, $recordId, $data = null)
    {
        $query = "INSERT INTO registration_audit_log 
                  (user_id, action, table_name, record_id, new_values, ip_address, user_agent) 
                  VALUES (:user_id, :action, :table, :record_id, :data, :ip, :agent)";
        
        return $this->db->insert($query, [
            ':user_id' => $userId,
            ':action' => $action,
            ':table' => $tableName,
            ':record_id' => $recordId,
            ':data' => json_encode($data),
            ':ip' => $_SERVER['REMOTE_ADDR'] ?? null,
            ':agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }

    // ========================================
    // VERIFICATION & STATUS UPDATES
    // ========================================

    /**
     * Update user status
     */
    public function updateUserStatus($userId, $status)
    {
        $query = "UPDATE users SET status = :status WHERE id = :user_id";
        return $this->db->update($query, [
            ':status' => $status,
            ':user_id' => $userId
        ]);
    }

    /**
     * Update donor verification status
     */
    public function updateDonorVerificationStatus($donorId, $status)
    {
        $query = "UPDATE donors SET verification_status = :status WHERE id = :donor_id";
        return $this->db->update($query, [
            ':status' => $status,
            ':donor_id' => $donorId
        ]);
    }

    // ========================================
    // RETRIEVAL METHODS
    // ========================================

    /**
     * Get user by ID
     */
    public function getUserById($userId)
    {
        $query = "SELECT * FROM users WHERE id = :user_id";
        $result = $this->db->query($query, [':user_id' => $userId]);
        return $result ? $result[0] : null;
    }

    /**
     * Get donor by user ID
     */
    public function getDonorByUserId($userId)
    {
        $query = "SELECT * FROM donors WHERE user_id = :user_id";
        $result = $this->db->query($query, [':user_id' => $userId]);
        return $result ? $result[0] : null;
    }

    /**
     * Get donor with user details
     */
    public function getDonorWithUserDetails($donorId)
    {
        $query = "SELECT d.*, u.username, u.email, u.phone, u.status, u.role
                  FROM donors d
                  JOIN users u ON d.user_id = u.id
                  WHERE d.id = :donor_id";
        
        $result = $this->db->query($query, [':donor_id' => $donorId]);
        return $result ? $result[0] : null;
    }

    /**
     * Get pending registrations count
     */
    public function getPendingRegistrationsCount()
    {
        $query = "SELECT COUNT(*) as count FROM users WHERE status = 'pending'";
        $result = $this->db->query($query);
        return $result[0]['count'];
    }
}
