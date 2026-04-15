<?php

namespace App\Models;

use App\Core\Model;

class RegistrationModel {
    use Model;

    // ========================================
    // USER MANAGEMENT
    // ========================================

    /**
     * Create a new user account
     */
    public function createUser($username, $password, $role, $email = null, $phone = null, $status = 'pending')
    {
        return $this->insert([
            'username' => $username,
            'password_hash' => $password, 
            'role' => $role,
            'email' => $email,
            'phone' => $phone,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s')
        ], 'users');
    }

    /**
     * Check if username already exists
     */
    public function usernameExists($username)
    {
        return $this->count(['username' => $username], [], 'users') > 0;
    }

    /**
     * Check if email already exists
     */
    public function emailExists($email)
    {
        return $this->count(['email' => $email], [], 'users') > 0;
    }

    /**
     * Check if NIC already exists
     */
    public function nicExists($nic)
    {
        return $this->count(['nic_number' => $nic], [], 'donors') > 0;
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

        // Split full name into first and last name
        $nameParts = explode(' ', $personalData['full_name'], 2);
        
        return $this->insert([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'pledge_type' => $pledgeType,
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? '',
            'gender' => $personalData['gender'],
            'date_of_birth' => $personalData['dob'],
            'blood_group' => $personalData['blood_group'],
            'nic_number' => $personalData['nic'],
            'address' => $personalData['address'],
            'grama_niladhari_division' => $personalData['gn_division'],
            'district' => $personalData['district'],
            'divisional_secretariat' => $personalData['divisional_secretariat'],
            'verification_status' => 'PENDING',
            'consent_status' => 'PENDING'
        ], 'donors');
    }

    /**
     * Create live donor — handled via pledge_type in donors table now
     */
    public function createLiveDonor($donorId)
    {
        return $donorId;
    }

    /**
     * Create deceased donor — handled via pledge_type in donors table now
     */
    public function createDeceasedDonor($donorId)
    {
        return $donorId;
    }

    /**
     * Add donor organs
     */
    public function addDonorOrgans($donorId, $organIds)
    {
        if (empty($organIds)) return true;
        
        foreach ($organIds as $organId) {
            $this->insert([
                'donor_id' => $donorId,
                'organ_id' => $organId,
                'status' => 'PENDING'
            ], 'donor_pledges');
        }
        
        return true;
    }

    /**
     * Get organ ID by name
     */
    public function getOrganIdByName($organName)
    {
        $res = $this->first(['name' => $organName], [], 'id', '', 'organs');
        return $res ? $res->id : null;
    }

    /**
     * Get all available organs
     */
    public function getAvailableOrgans()
    {
        return $this->where(['is_available' => 1], [], 'id, name', '', 'organs');
    }

    // ========================================
    // WITNESS MANAGEMENT
    // ========================================

    /**
     * Add witnesses for live donor
     */
    public function addWitnesses($donorId, $witnesses)
    {
        foreach ($witnesses as $witness) {
            $this->insert([
                'donor_id' => $donorId,
                'name' => $witness['name'],
                'nic_number' => $witness['nic'],
                'contact_number' => $witness['phone']
            ], 'witnesses');
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
        return $this->insert([
            'donor_id' => $donorId,
            'name' => $kinData['name'],
            'relationship' => $kinData['relationship'],
            'nic_number' => $kinData['nic'] ?? '',
            'contact_number' => $kinData['phone'],
            'email' => $kinData['email']
        ], 'next_of_kin');
    }

    // ========================================
    // FINANCIAL DONORS
    // ========================================

    /**
     * Create financial donor record
     */
    public function createFinancialDonor($userId)
    {
        return $this->insert([
            'user_id' => $userId,
            'full_name' => '',
            'donation_frequency' => 'ONETIME'
        ], 'financial_donors');
    }

    // ========================================
    // NON-DONORS (OPT-OUT)
    // ========================================

    /**
     * Create non-donor record
     */
    public function createNonDonor($donorId, $reason = null)
    {
        return $this->update(['id' => $donorId], ['opt_out_reason' => $reason], 'donors');
    }

    // ========================================
    // CONSENT MANAGEMENT
    // ========================================

    /**
     * Record consent
     */
    public function recordConsent($donorId, $consentType, $consentText, $ipAddress = null)
    {
        return $this->insert([
            'donor_id' => $donorId,
            'consent_type' => $consentType,
            'consent_text' => $consentText,
            'consent_given' => 1,
            'consent_date' => date('Y-m-d H:i:s'),
            'ip_address' => $ipAddress
        ], 'consent_records');
    }

    /**
     * Get consent template
     */
    public function getConsentTemplate($consentType)
    {
        $res = $this->first(['consent_type' => $consentType, 'is_active' => 1], ['order' => 'effective_date DESC'], 'template_text', '', 'consent_templates');
        return $res ? $res->template_text : null;
    }

    // ========================================
    // HOSPITAL REGISTRATION
    // ========================================

    /**
     * Register hospital
     */
    public function registerHospital($userId, $hospitalData, $cmoData)
    {
        return $this->insert([
            'user_id' => $userId,
            'registration_number' => $hospitalData['registration_number'],
            'name' => $hospitalData['name'],
            'address' => $hospitalData['address'],
            'district' => $hospitalData['district'],
            'facility_type' => $hospitalData['type'],
            'cmo_name' => $cmoData['name'],
            'cmo_nic' => $cmoData['nic'],
            'medical_license_number' => $cmoData['license_number'],
            'verification_status' => 'PENDING'
        ], 'hospitals');
    }

    /**
     * Check if hospital registration number exists
     */
    public function hospitalRegNoExists($regNo)
    {
        return $this->count(['registration_number' => $regNo], [], 'hospitals') > 0;
    }

    // ========================================
    // MEDICAL SCHOOL REGISTRATION
    // ========================================

    /**
     * Register medical school
     */
    public function registerMedicalSchool($userId, $schoolData, $contactData)
    {
        return $this->insert([
            'user_id' => $userId,
            'school_name' => $schoolData['name'],
            'university_affiliation' => $schoolData['university'],
            'ugc_accreditation_number' => $schoolData['ugc_number'],
            'address' => $schoolData['address'],
            'district' => $schoolData['district'] ?? null,
            'contact_person_name' => $contactData['name'],
            'contact_person_title' => $contactData['title'] ?? '',
            'contact_person_email' => $contactData['email'],
            'contact_person_phone' => $contactData['phone'],
            'verification_status' => 'Pending',
            'registration_date' => date('Y-m-d')
        ], 'medical_schools');
    }

    /**
     * Check if UGC number exists
     */
    public function ugcNumberExists($ugcNumber)
    {
        return $this->count(['ugc_accreditation_number' => $ugcNumber], [], 'medical_schools') > 0;
    }

    // ========================================
    // NOTIFICATION QUEUE
    // ========================================

    /**
     * Queue notification
     */
    public function queueNotification($email, $phone, $type, $subject, $message)
    {
        return $this->insert([
            'recipient_email' => $email,
            'recipient_phone' => $phone,
            'notification_type' => $type,
            'subject' => $subject,
            'message' => $message,
            'status' => 'pending'
        ], 'notification_queue');
    }

    // ========================================
    // AUDIT LOGGING
    // ========================================

    /**
     * Log registration action
     */
    public function logRegistration($userId, $action, $tableName, $recordId, $data = null)
    {
        return $this->insert([
            'user_id' => $userId,
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'new_values' => json_encode($data),
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null
        ], 'registration_audit_log');
    }

    // ========================================
    // VERIFICATION & STATUS UPDATES
    // ========================================

    /**
     * Update user status
     */
    public function updateUserStatus($userId, $status)
    {
        return $this->update(['id' => $userId], ['status' => $status], 'users');
    }

    /**
     * Update donor verification status
     */
    public function updateDonorVerificationStatus($donorId, $status)
    {
        return $this->update(['id' => $donorId], ['verification_status' => $status], 'donors');
    }

    // ========================================
    // RETRIEVAL METHODS
    // ========================================

    /**
     * Get user by ID
     */
    public function getUserById($userId)
    {
        return $this->first(['id' => $userId], [], '*', '', 'users');
    }

    /**
     * Get donor by user ID
     */
    public function getDonorByUserId($userId)
    {
        return $this->first(['user_id' => $userId], [], '*', '', 'donors');
    }

    /**
     * Get donor with user details
     */
    public function getDonorWithUserDetails($donorId)
    {
        // Custom query logic would be handled by the model's query method if needed
        return $this->first(['id' => $donorId], [], '*', '', 'donors');
    }

    /**
     * Get pending registrations count
     */
    public function getPendingRegistrationsCount()
    {
        return $this->count(['status' => 'pending'], [], 'users');
    }
}
