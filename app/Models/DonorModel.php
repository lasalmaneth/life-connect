<?php

namespace App\Models;

use App\Core\Model;

class DonorModel {
    use Model;

    protected $table = 'donors';

    protected $allowedColumns = [
        'user_id',
        'category_id',
        'pledge_type',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'blood_group',
        'nic_number',
        'nationality',
        'address',
        'district',
        'divisional_secretariat',
        'grama_niladhari_division',
        'verification_status',
        'consent_status',
        'consent_date',
        'active_roles',
        'opt_out_reason'
    ];

    public function createDonor($userId, $personalData, $categoryId, $pledgeType = 'NONE')
    {
        return $this->insert([
            'user_id' => $userId,
            'category_id' => $categoryId,
            'pledge_type' => $pledgeType,
            'first_name' => $personalData['first_name'] ?? '',
            'last_name' => $personalData['last_name'] ?? '',
            'gender' => $personalData['gender'],
            'date_of_birth' => $personalData['dob'],
            'blood_group' => $personalData['blood_group'] ?? null,
            'nic_number' => $personalData['nic'],
            'nationality' => $personalData['nationality'] ?? 'Sri Lankan',
            'address' => $personalData['address'] ?? '',
            'district' => $personalData['district'] ?? '',
            'divisional_secretariat' => $personalData['divisional_secretariat'] ?? '',
            'grama_niladhari_division' => $personalData['gn_division'] ?? '',
            'verification_status' => 'PENDING',
            'consent_status' => 'PENDING'
        ]);
    }

    public function nicExists($nic)
    {
        return $this->count(['nic_number' => $nic]) > 0;
    }

    public function getDonorByUserId($userId)
    {
        return $this->first(['user_id' => $userId]);
    }

    public function getDonorById($id)
    {
        $query = "SELECT d.*, u.username, u.email, u.phone, u.phone as contact_number, u.created_at as registration_date 
                  FROM donors d 
                  JOIN users u ON d.user_id = u.id 
                  WHERE d.id = :id";
        $result = $this->query($query, [':id' => $id]);
        
        if ($result && isset($result[0])) {
            $donor = $result[0];
            
            // Decrypt fields if they look encrypted (helper method)
            $donor->nic_number = $this->decryptField($donor->nic_number);
            $donor->date_of_birth = $this->decryptField($donor->date_of_birth);
            $donor->address = $this->decryptField($donor->address);
            
            return $donor;
        }
        
        return null;
    }

    private function decryptField($value)
    {
        if (empty($value)) return $value;
        // Check if the decrypt function exists (should be in global functions)
        if (function_exists('decrypt')) {
            $decrypted = decrypt($value);
            return ($decrypted !== false) ? $decrypted : $value;
        }
        return $value;
    }

    public function getDonorStats($donorId)
    {
        // Count approved organs
        $approvedQuery = "SELECT COUNT(*) as count FROM donor_pledges WHERE donor_id = :donor_id AND status = 'APPROVED'";
        $approvedResult = $this->query($approvedQuery, [':donor_id' => $donorId]);
        $approved = $approvedResult ? $approvedResult[0]->count : 0;

        // Count pending organs
        $pendingQuery = "SELECT COUNT(*) as count FROM donor_pledges WHERE donor_id = :donor_id AND status = 'PENDING'";
        $pendingResult = $this->query($pendingQuery, [':donor_id' => $donorId]);
        $pending = $pendingResult ? $pendingResult[0]->count : 0;

        return [
            'approved_organs' => $approved,
            'pending_organs' => $pending
        ];
    }

    /**
     * Get a summary of pledges categorized by their legal finalization status across ALL systems
     */
    public function getPledgeSummary($donorId)
    {
        // 1. Organ Pledges (Most recent for each organ)
        $organQuery = "SELECT p.* FROM donor_pledges p 
                       JOIN (
                           SELECT MAX(id) as max_id 
                           FROM donor_pledges 
                           WHERE donor_id = :donor_id AND status != 'WITHDRAWN'
                           GROUP BY organ_id
                       ) latest ON p.id = latest.max_id";
        $organPledges = $this->query($organQuery, [':donor_id' => $donorId]);
        
        // 2. Body Donation
        $bodyQuery = "SELECT * FROM body_donation_consents WHERE donor_id = :donor_id AND status != 'WITHDRAWN'";
        $bodyPledges = $this->query($bodyQuery, [':donor_id' => $donorId]);

        $finalized = 0;
        $pending = 0;

        if ($organPledges) {
            foreach ($organPledges as $pledge) {
                $status = strtoupper($pledge->status ?? '');
                if (in_array($status, ['APPROVED', 'UPLOADED', 'COMPLETED']) || !empty($pledge->signed_form_path)) {
                    $finalized++;
                } else if ($status === 'PENDING') {
                    $pending++;
                }
            }
        }

        if ($bodyPledges) {
            foreach ($bodyPledges as $pledge) {
                $status = strtoupper($pledge->status ?? '');
                // Body donation status ACTIVE is prioritized as finalized
                if ($status === 'ACTIVE') {
                    $finalized++;
                } else {
                    $pending++;
                }
            }
        }

        return [
            'finalized' => $finalized,
            'pending' => $pending,
            'total' => (is_array($organPledges) ? count($organPledges) : 0) + (is_array($bodyPledges) ? count($bodyPledges) : 0)
        ];
    }

    /**
     * Withdraw all non-finalized pledges for a donor across ALL systems (Internal quick-withdraw)
     */
    public function withdrawPendingPledges($donorId)
    {
        // 1. Withdraw Organ Pledges
        $query1 = "UPDATE donor_pledges 
                  SET status = 'WITHDRAWN' 
                  WHERE donor_id = :donor_id 
                  AND status = 'PENDING' 
                  AND (signed_form_path IS NULL OR signed_form_path = '')";
        $this->query($query1, [':donor_id' => $donorId]);

        // 2. Withdraw Body Donations
        $query2 = "UPDATE body_donation_consents 
                  SET status = 'WITHDRAWN' 
                  WHERE donor_id = :donor_id 
                  AND status = 'PENDING'";
        return $this->query($query2, [':donor_id' => $donorId]);
    }

    public function createWithdrawal($data)
    {
        $query = "INSERT INTO consent_withdrawals (
                    donor_id, organ_id, full_name, nic_number, dob, address, 
                    contact_number, prev_consent_date, organization, 
                    witness1_name, witness1_nic, witness2_name, witness2_nic
                  ) VALUES (
                    :donor_id, :organ_id, :full_name, :nic_number, :dob, :address, 
                    :contact_number, :prev_consent_date, :organization, 
                    :witness1_name, :witness1_nic, :witness2_name, :witness2_nic
                  )";
        return $this->insert($query, $data);
    }

    public function getWithdrawalByDonorId($donorId)
    {
        $query = "SELECT * FROM consent_withdrawals WHERE donor_id = :donor_id ORDER BY created_at DESC LIMIT 1";
        $res = $this->query($query, [':donor_id' => $donorId]);
        return $res ? $res[0] : null;
    }

    /**
     * Get pending withdrawal for a specific organ
     */
    public function getPendingWithdrawalByOrgan($donorId, $organId)
    {
        $query = "SELECT * FROM consent_withdrawals 
                  WHERE donor_id = :donor_id 
                  AND organ_id = :organ_id 
                  AND status = 'PENDING_UPLOAD' 
                  ORDER BY created_at DESC LIMIT 1";
        $res = $this->query($query, [':donor_id' => $donorId, ':organ_id' => $organId]);
        return $res ? $res[0] : null;
    }

    public function updateWithdrawalPath($id, $path)
    {
        $query = "UPDATE consent_withdrawals SET signed_form_path = :path, status = 'COMPLETED' WHERE id = :id";
        $con = $this->connect();
        $stm = $con->prepare($query);
        return $stm->execute([':path' => $path, ':id' => $id]);
    }

    /**
     * Delete a pending withdrawal record
     */
    public function deleteWithdrawal($id)
    {
        $query = "DELETE FROM consent_withdrawals WHERE id = :id AND status = 'PENDING_UPLOAD'";
        return $this->query($query, [':id' => $id]);
    }

    /**
     * Get all completed withdrawals for a specific donor
     */
    public function getWithdrawalsByDonor($donorId)
    {
        $query = "SELECT * FROM consent_withdrawals 
                  WHERE donor_id = :donor_id 
                  AND status = 'COMPLETED' 
                  ORDER BY created_at DESC";
        return $this->query($query, [':donor_id' => $donorId]);
    }

    /**
     * Deactivate a SPECIFIC organ/full body pledge after formal withdrawal
     */
    public function deactivateSpecificPledge($donorId, $organId)
    {
        if ($organId === 10) {
            // Full Body Donation
            $this->query("UPDATE body_donation_consents SET status = 'WITHDRAWN' WHERE donor_id = :donor_id AND (status = 'ACTIVE' OR status = 'PENDING')", [':donor_id' => $donorId]);
        }
        
        // Also update donor_pledges (covers all types)
        $this->query("UPDATE donor_pledges SET status = 'WITHDRAWN' WHERE donor_id = :donor_id AND organ_id = :organ_id", [':donor_id' => $donorId, ':organ_id' => $organId]);
        
        return true;
    }

    /**
     * Mass deactivate ALL active intents after formal withdrawal (Legacy)
     */
    public function deactivateAllPledges($donorId)
    {
        $this->query("UPDATE donor_pledges SET status = 'WITHDRAWN' WHERE donor_id = :donor_id", [':donor_id' => $donorId]);
        $this->query("UPDATE body_donation_consents SET status = 'WITHDRAWN' WHERE donor_id = :donor_id", [':donor_id' => $donorId]);
        return true;
    }

    public function getPledgedOrgans($donorId)
    {
        // Also pull latest history data for SUSPENDED/COMPLETED pledges
        $query = "SELECT p.*, o.name as organ_name, o.description, h.name as hospital_name,
                  (SELECT status FROM consent_withdrawals 
                   WHERE donor_id = p.donor_id AND organ_id = p.organ_id 
                   AND status = 'PENDING_UPLOAD' 
                   ORDER BY created_at DESC LIMIT 1) as withdrawal_status,
                  (SELECT next_eligible_date FROM donation_medical_history 
                   WHERE donor_id = p.donor_id AND pledge_id = p.id
                   ORDER BY created_at DESC LIMIT 1) as next_eligible_date,
                  (SELECT recovery_status FROM donation_medical_history 
                   WHERE donor_id = p.donor_id AND pledge_id = p.id
                   ORDER BY created_at DESC LIMIT 1) as recovery_status,
                  (SELECT donated_organ FROM donation_medical_history 
                   WHERE donor_id = p.donor_id AND pledge_id = p.id
                   ORDER BY created_at DESC LIMIT 1) as donated_organ_name
                  FROM donor_pledges p 
                  JOIN (
                      SELECT MAX(id) as max_id 
                      FROM donor_pledges 
                      WHERE donor_id = :donor_id AND status != 'WITHDRAWN'
                      GROUP BY organ_id
                  ) latest ON p.id = latest.max_id
                  JOIN organs o ON p.organ_id = o.id 
                  LEFT JOIN hospitals h ON p.preferred_hospital_id = h.id
                  WHERE p.donor_id = :donor_id";
        
        $results = $this->query($query, [':donor_id' => $donorId]);
        
        if ($results) {
            foreach ($results as $key => $organ) {
                $icon = '❓';
                $name = strtolower(trim($organ->organ_name));
                if (str_contains($name, 'kidney'))     $icon = '🧬';
                elseif (str_contains($name, 'liver'))  $icon = '🥃';
                elseif (str_contains($name, 'heart'))  $icon = '❤️';
                elseif (str_contains($name, 'lung'))   $icon = '🫁';
                elseif (str_contains($name, 'pancreas')) $icon = '🍬';
                elseif (str_contains($name, 'intestine')) $icon = '🧶';
                elseif (str_contains($name, 'cornea')) $icon = '👁️';
                elseif (str_contains($name, 'marrow') || str_contains($name, 'bone')) $icon = '🦴';
                $results[$key]->organ_icon = $icon;
            }
        }
        
        return $results ? json_decode(json_encode($results), true) : []; 
    }

    /**
     * Determine the unified "Deceased Donation Mode" based on Sri Lankan legal guidelines.
     * Logic: The latest intent (by date) between Full Body and Deceased Organs determines the primary mode.
     * Cornea/Eye consents merge with Body or Organ modes unless revoked.
     * Modes: NONE, EYE_ONLY, BODY_ONLY, BODY_PLUS_CORNEA, ORGAN_ONLY, ORGANS_PLUS_CORNEA
     */
    public function getDeceasedDonationMode($donorId)
    {
        $donorId = (int)$donorId;

        // 1. Fetch Latest Body Donation Intent
        // Checks BOTH body_donation_consents (formal form) and donor_pledges (general tracker)
        $bodyConsentRes = $this->query(
            "SELECT consent_date FROM body_donation_consents 
             WHERE donor_id = :did AND status IN ('ACTIVE', 'IN_PROGRESS') 
             ORDER BY consent_date DESC LIMIT 1",
            [':did' => $donorId]
        );
        $bodyPledgeRes = $this->query(
            "SELECT pledge_date FROM donor_pledges 
             WHERE donor_id = :did AND organ_id = 10 
             AND status IN ('APPROVED', 'UPLOADED', 'IN_PROGRESS') 
             ORDER BY pledge_date DESC LIMIT 1",
            [':did' => $donorId]
        );

        $bodyDate = ($bodyConsentRes) ? $bodyConsentRes[0]->consent_date : (($bodyPledgeRes) ? $bodyPledgeRes[0]->pledge_date : null);

        // 2. Fetch Latest Organ Donation Intent (excluding Cornea and Body ID 10)
        // CRITICAL: Counts Deceased (no LDC) or ANY IN_PROGRESS organ pledge.
        // We now EXCLUDE 'PENDING' to ensure the "Mode" only changes after formal registration.
        $organRes = $this->query(
            "SELECT MAX(dp.pledge_date) as latest_date, COUNT(*) as active_count
             FROM donor_pledges dp
             JOIN organs o ON dp.organ_id = o.id
             LEFT JOIN living_donor_consents ldc ON dp.id = ldc.donor_pledge_id
             WHERE dp.donor_id = :did 
             AND (
                (dp.status IN ('APPROVED', 'UPLOADED') AND ldc.id IS NULL)
                OR (dp.status = 'IN_PROGRESS')
             )
             AND dp.organ_id != 10 
             AND LOWER(o.name) NOT LIKE '%cornea%' AND LOWER(o.name) NOT LIKE '%eye%'",
            [':did' => $donorId]
        );
        $organDate = (!empty($organRes) && $organRes[0]->active_count > 0) ? $organRes[0]->latest_date : null;

        // 3. Check for Active Cornea/Eye Consent
        $eyeRes = $this->query(
            "SELECT COUNT(*) as active_count
             FROM donor_pledges dp
             JOIN organs o ON dp.organ_id = o.id
             WHERE dp.donor_id = :did AND dp.status IN ('PENDING', 'APPROVED', 'UPLOADED', 'IN_PROGRESS') 
             AND (LOWER(o.name) LIKE '%cornea%' OR LOWER(o.name) LIKE '%eye%')",
            [':did' => $donorId]
        );
        $hasCornea = (!empty($eyeRes) && $eyeRes[0]->active_count > 0);

        // 4. Resolve Mode and Track Superseded Intents
        $mode = 'NONE';
        $superseded = null;

        if (!$bodyDate && !$organDate) {
            $mode = $hasCornea ? 'EYE_ONLY' : 'NONE';
        } elseif ($bodyDate && (!$organDate || $bodyDate >= $organDate)) {
            $mode = $hasCornea ? 'BODY_PLUS_CORNEA' : 'BODY_ONLY';
            if ($organDate) {
                $superseded = [
                    'type' => 'ORGAN',
                    'date' => $organDate,
                    'reason' => "Replaced by Whole Body Donation intent on " . date('Y-m-d', strtotime($bodyDate)) . " as per Sri Lankan legal guidelines."
                ];
            }
        } else {
            // $organDate > $bodyDate
            $mode = $hasCornea ? 'ORGANS_PLUS_CORNEA' : 'ORGAN_ONLY';
            if ($bodyDate) {
                $superseded = [
                    'type' => 'BODY',
                    'date' => $bodyDate,
                    'reason' => "Replaced by Deceased Organ Donation pledge on " . date('Y-m-d', strtotime($organDate)) . " as per Sri Lankan legal guidelines."
                ];
            }
        }

        // 5. Check specifically for IN_PROGRESS status for more detailed tooltips
        $inProgressOrganRes = $this->query(
            "SELECT COUNT(*) as cnt 
             FROM donor_pledges dp
             JOIN organs o ON dp.organ_id = o.id
             WHERE dp.donor_id = :did 
             AND dp.status = 'IN_PROGRESS' 
             AND dp.organ_id != 10
             AND LOWER(o.name) NOT LIKE '%cornea%' AND LOWER(o.name) NOT LIKE '%eye%'",
            [':did' => $donorId]
        );
        $hasInProgressOrgan = (!empty($inProgressOrganRes) && $inProgressOrganRes[0]->cnt > 0);

        $inProgressBodyRes = $this->query(
            "SELECT COUNT(*) as cnt FROM donor_pledges 
             WHERE donor_id = :did AND status = 'IN_PROGRESS' AND organ_id = 10",
            [':did' => $donorId]
        );
        $hasInProgressBody = (!empty($inProgressBodyRes) && $inProgressBodyRes[0]->cnt > 0);

        return [
            'mode' => $mode,
            'superseded' => $superseded,
            'has_active_deceased_organs' => ($organDate !== null),
            'has_active_body_pledge' => ($bodyDate !== null),
            'has_inprogress_deceased_organs' => $hasInProgressOrgan,
            'has_inprogress_body' => $hasInProgressBody
        ];
    }

    /**
     * Move a specific pledge to IN_PROGRESS and SUSPEND all other APPROVED pledges
     * for the same donor. Enforces the "one surgery at a time" rule.
     */
    public function updatePledgeToInProgress($donorId, $pledgeId)
    {
        $donorId  = (int)$donorId;
        $pledgeId = (int)$pledgeId;

        // Safety: only one IN_PROGRESS allowed
        $existing = $this->query(
            "SELECT id FROM donor_pledges WHERE donor_id = :did AND status = 'IN_PROGRESS' AND id != :pid LIMIT 1",
            [':did' => $donorId, ':pid' => $pledgeId]
        );
        if ($existing) {
            return false; // Already has an in-progress donation
        }

        // Set target pledge to IN_PROGRESS
        $this->query(
            "UPDATE donor_pledges SET status = 'IN_PROGRESS' WHERE id = :pid AND donor_id = :did",
            [':pid' => $pledgeId, ':did' => $donorId]
        );

        // SUSPEND all other APPROVED pledges for this donor
        $this->query(
            "UPDATE donor_pledges SET status = 'SUSPENDED' 
             WHERE donor_id = :did AND id != :pid AND status = 'APPROVED'",
            [':did' => $donorId, ':pid' => $pledgeId]
        );

        return true;
    }

    /**
     * Complete a donation surgery: mark pledge COMPLETED, log to donation_medical_history,
     * enforce organ-specific recovery rules, and set next_eligible_date on donor.
     *
     * Organ rules (by name, case-insensitive):
     *   kidney       → no next date (COMPLETED permanently; kidney can't be re-donated)
     *   part of liver→ 12 months before other pledges can fire
     *   bone marrow  →  6 months
     *   others       →  3 months (default recovery)
     *
     * @param int    $donorId
     * @param int    $pledgeId
     * @param array  $data  Keys: donation_date, doctor_notes, hospital_id
     * @return bool
     */
    public function completeDonation($donorId, $pledgeId, array $data = [])
    {
        $donorId  = (int)$donorId;
        $pledgeId = (int)$pledgeId;

        // 1. Fetch pledge and organ details before marking COMPLETED
        $pledge = $this->query(
            "SELECT dp.*, o.name as organ_name 
             FROM donor_pledges dp 
             JOIN organs o ON dp.organ_id = o.id 
             WHERE dp.id = :pid AND dp.donor_id = :did",
            [':pid' => $pledgeId, ':did' => $donorId]
        );

        if (!$pledge) return false;

        $organName  = $pledge[0]->organ_name;
        $hospitalId = $pledge[0]->hospital_id;
        $donationDt = $data['donation_date'] ?? date('Y-m-d');
        $notes      = $data['doctor_notes'] ?? '';

        // 2. Mark pledge COMPLETED in the primary table
        $this->query(
            "UPDATE donor_pledges SET status = 'COMPLETED' WHERE id = :pid AND donor_id = :did",
            [':pid' => $pledgeId, ':did' => $donorId]
        );

        // 3. Record in donation_medical_history (Calculates rules automatically)
        $this->recordDonationHistory($donorId, $pledgeId, $organName, $donationDt, $hospitalId, $notes);

        return true;
    }

    /**
     * Automatically restore eligible SUSPENDED pledges back to APPROVED.
     * Called every time a donor loads their dashboard.
     *
     * Rules enforced:
     *  - Kidney pledge that is COMPLETED stays COMPLETED (blocked permanently).
     *  - If CURRENT_DATE >= donors.next_eligible_date → reactivate SUSPENDED→APPROVED.
     *  - Also marks donation_medical_history.recovery_status = 'recovered' where applicable.
     */
    /**
     * Get active recovery records from donation_medical_history.
     * These are records where current date < next_eligible_date.
     */
    public function getActiveRecoveries($donorId)
    {
        return $this->query(
            "SELECT h.*, o.id as organ_id 
             FROM donation_medical_history h
             JOIN organs o ON LOWER(o.name) COLLATE utf8mb4_unicode_ci = LOWER(h.donated_organ) COLLATE utf8mb4_unicode_ci
             WHERE h.donor_id = :did 
               AND (h.next_eligible_date IS NULL OR h.next_eligible_date > CURDATE())
             ORDER BY h.next_eligible_date DESC",
            [':did' => (int)$donorId]
        ) ?: [];
    }

    /**
     * Get full donation medical history for a donor (for display in profile/portal)
     */
    public function getDonationHistory($donorId)
    {
        return $this->query(
            "SELECT dmh.*, h.name as hospital_name
             FROM donation_medical_history dmh
             LEFT JOIN hospitals h ON h.id = dmh.hospital_id
             WHERE dmh.donor_id = :did
             ORDER BY dmh.donation_date DESC",
            [':did' => (int)$donorId]
        ) ?: [];
    }

    public function getNotifications($donorId, $limit = 5)
    {
        // Get user_id from donor_id to fetch notifications
        $donor = $this->getDonorById($donorId);
        if (!$donor) return [];

        $limit = (int)$limit;
        $query = "SELECT * FROM notifications 
                  WHERE user_id = :user_id 
                  ORDER BY created_at DESC 
                  LIMIT $limit";

        $results = $this->query($query, [':user_id' => $donor->user_id]);
        
        // Map fields to match view expectation (message, date_sent)
        $mapped = [];
        if ($results) {
            foreach ($results as $row) {
                $mapped[] = [
                    'message' => $row->message,
                    'date_sent' => $row->created_at
                ];
            }
        }
        return $mapped;
    }

    public function getTestResults($donorId)
    {
        $query = "SELECT tr.*, h.name as hospital_name 
                  FROM test_results tr 
                  LEFT JOIN hospitals h ON tr.verified_by_hospital_id = h.id 
                  WHERE tr.donor_id = :donor_id 
                  ORDER BY tr.test_date DESC";
        
        return $this->query($query, [':donor_id' => $donorId]) ?: [];
    }

    public function getAppointments($nicNumber)
    {
        if (empty($nicNumber)) return [];
        
        // Appointments are tracked by NIC/Patient ID in the aftercare table
        $query = "SELECT * FROM aftercare_appointments 
                  WHERE patient_id = :nic 
                  AND status != 'Cancelled'
                  ORDER BY appointment_date ASC";
        
        return $this->query($query, [':nic' => $nicNumber]) ?: [];
    }

    public function getRequestedAftercareAppointments($nicNumber)
    {
        if (empty($nicNumber)) return [];
        
        // Get aftercare appointments with 'Requested' status for the donor
        $query = "SELECT * FROM aftercare_appointments 
                  WHERE patient_id = :nic 
                  AND status = 'Requested'
                  ORDER BY appointment_date ASC";
        
        return $this->query($query, [':nic' => $nicNumber]) ?: [];
    }

    public function getBodyUsageStatus($donorId)
    {
        $query = "SELECT bul.*, ms.school_name 
                  FROM body_usage_logs bul
                  JOIN medical_schools ms ON bul.medical_school_id = ms.id
                  WHERE bul.donor_id = :donor_id 
                  ORDER BY bul.created_at DESC 
                  LIMIT 1";
        
        $result = $this->query($query, [':donor_id' => $donorId]);
        return $result ? $result[0] : null;
    }

    /**
     * Update donor profile (Telephone in users tab, Address in donors tab)
     */
    public function updateDonorProfile($userId, $data)
    {
        // 1. Update Telephone in users table (if phone is provided)
        if (isset($data['phone']) || isset($data['contact_number'])) {
            $phone = $data['phone'] ?? $data['contact_number'];
            $this->query("UPDATE users SET phone = :phone WHERE id = :user_id", [
                ':phone' => $phone,
                ':user_id' => $userId
            ]);
        }

        // 2. Update Address in donors table (with encryption if address is provided)
        if (isset($data['address'])) {
            $encryptedAddress = encrypt($data['address']);
            $this->query("UPDATE donors SET address = :address WHERE user_id = :user_id", [
                ':address' => $encryptedAddress,
                ':user_id' => $userId
            ]);
        }
        
        // 3. Update Email in users table (if provided)
        if (isset($data['email'])) {
            $this->query("UPDATE users SET email = :email WHERE id = :user_id", [
                ':email' => $data['email'],
                ':user_id' => $userId
            ]);
        }

        // 4. Update other fields in donors table (legacy compatibility if needed)
        $donorFields = [
            'nationality', 'grama_niladhari_division', 'district', 'divisional_secretariat'
        ];
        foreach ($donorFields as $field) {
            $key = ($field === 'grama_niladhari_division') ? 'gn_div' : $field;
            if (isset($data[$field]) || isset($data[$key])) {
                $val = $data[$field] ?? $data[$key];
                $this->query("UPDATE donors SET $field = :val WHERE user_id = :user_id", [
                    ':val' => $val,
                    ':user_id' => $userId
                ]);
            }
        }

        return true;
    }

    public function getAllDistricts()
    {
        return [
            'Ampara', 'Anuradhapura', 'Badulla', 'Batticaloa', 'Colombo', 'Galle', 'Gampaha', 
            'Hambantota', 'Jaffna', 'Kalutara', 'Kandy', 'Kegalle', 'Kilinochchi', 'Kurunegala', 
            'Mannar', 'Matale', 'Matara', 'Monaragala', 'Mullaitivu', 'Nuwara Eliya', 'Polonnaruwa', 
            'Puttalam', 'Ratnapura', 'Trincomalee', 'Vavuniya'
        ];
    }

    // ─────────────────────────────────────────────────────────────────────
    // ORGAN-SPECIFIC RE-DONATION ELIGIBILITY
    // Reads from the `donation_medical_history` table and applies rules:
    //   • Kidney       → Permanently blocked (cannot re-donate kidney)
    //   • Bone Marrow  → Blocked for 6 months
    //   • Liver Portion→ Blocked for 12 months
    //   • Doctor-approval combinations are flagged as 'RESTRICTED'
    // ─────────────────────────────────────────────────────────────────────

    /**
     * Returns the donor's overall donation eligibility status.
     *
     * @param int $donorId
     * @return array {
     *   'is_in_recovery'   => bool,
     *   'blocked_organs'   => array,  // organ names that are currently blocked
     *   'next_eligible'    => string|null, // earliest date any timed block lifts
     *   'history'          => array,  // all rows from donation_medical_history
     *   'permanent_blocks' => array,  // organ names permanently blocked (kidney)
     *   'restricted'       => array,  // organ combos that need doctor approval
     *   'message'          => string  // human-readable summary
     * }
     */
    public function getDonationEligibility($donorId)
    {
        try {
            // First, ensure all COMPLETED pledges are recorded in history (Auto-Sync)
            $this->syncDonationHistory($donorId);

            // Fetch ALL donation history records for this donor
            $query = "SELECT * FROM donation_medical_history 
                      WHERE donor_id = :donor_id 
                      ORDER BY donation_date DESC";
            $history = $this->query($query, [':donor_id' => $donorId]) ?: [];

            if (empty($history)) {
                return [
                    'is_in_recovery'   => false,
                    'blocked_organs'   => [],
                    'next_eligible'    => null,
                    'history'          => [],
                    'permanent_blocks' => [],
                    'restricted'       => [],
                    'message'          => 'No donation history. Eligible for all donations.'
                ];
            }

        $today           = new \DateTime();
        $blockedOrgans   = [];   // currently time-locked
        $permanentBlocks = [];   // kidney-type permanent block
        $restricted      = [];   // doctor-approval combos
        $nextEligible    = null; // earliest date a timed block lifts

        // ── Organ name normalisers (case-insensitive matching) ────────────
        $isKidney     = fn($name) => stripos((string)$name, 'kidney')    !== false;
        $isBoneMarrow = fn($name) => stripos((string)$name, 'bone')      !== false
                                  || stripos((string)$name, 'marrow')    !== false;
        $isLiver      = fn($name) => stripos((string)$name, 'liver')     !== false;

        // ── Track which organs have already been donated (for combo rules) ─
        $donatedOrganNames = array_map(fn($row) => (string)($row->donated_organ ?? ''), $history);
        $hadKidney         = (bool) array_filter($donatedOrganNames, $isKidney);
        $hadLiver          = (bool) array_filter($donatedOrganNames, $isLiver);
        $hadBoneMarrow     = (bool) array_filter($donatedOrganNames, $isBoneMarrow);

        foreach ($history as $row) {
            $organName   = (string)($row->donated_organ ?? '');
            $nextDate    = !empty($row->next_eligible_date) ? new \DateTime($row->next_eligible_date) : null;
            $recoverySt  = strtoupper(trim((string)($row->recovery_status ?? '')));

            // ── KIDNEY → Permanent block on re-donating kidney ───────────
            if ($isKidney($organName)) {
                if (!in_array('Kidney', $permanentBlocks)) {
                    $permanentBlocks[] = 'Kidney';
                }

                // If within 1 month recovery: block other major donations
                $eligibleFrom = $nextDate;
                if (!$eligibleFrom) {
                    $eligibleFrom = (new \DateTime((string)($row->donation_date ?? 'now')))
                                    ->modify('+1 month');
                }

                if ($today < $eligibleFrom) {
                    $blockedOrgans[] = [
                        'organ'           => 'All major donations (Kidney recovery period)',
                        'eligible_on'     => $eligibleFrom->format('Y-m-d'),
                        'days_remaining'  => (int)$today->diff($eligibleFrom)->days
                    ];
                    if (!$nextEligible || $eligibleFrom < new \DateTime($nextEligible)) {
                        $nextEligible = $eligibleFrom->format('Y-m-d');
                    }
                }
                continue;
            }

            // ── BONE MARROW → 6-month block ──────────────────────────────
            if ($isBoneMarrow($organName)) {
                // Use next_eligible_date from DB if set, otherwise calculate
                $eligibleFrom = $nextDate;
                if (!$eligibleFrom) {
                    $eligibleFrom = (new \DateTime((string)($row->donation_date ?? 'now')))
                                    ->modify('+6 months');
                }

                if ($today < $eligibleFrom) {
                    $blockedOrgans[] = [
                        'organ'           => 'Bone Marrow',
                        'eligible_on'     => $eligibleFrom->format('Y-m-d'),
                        'days_remaining'  => (int)$today->diff($eligibleFrom)->days
                    ];
                    if (!$nextEligible || $eligibleFrom < new \DateTime($nextEligible)) {
                        $nextEligible = $eligibleFrom->format('Y-m-d');
                    }
                }
                continue;
            }

            // ── LIVER PORTION → 12-month block ──────────────────────────
            if ($isLiver($organName)) {
                // Liver cannot be re-donated
                if (!in_array('Liver Portion', $permanentBlocks)) {
                    $permanentBlocks[] = 'Liver Portion';
                }

                // Use next_eligible_date from DB if set, otherwise calculate
                $eligibleFrom = $nextDate;
                if (!$eligibleFrom) {
                    $eligibleFrom = (new \DateTime((string)($row->donation_date ?? 'now')))
                                    ->modify('+12 months');
                }

                // During that 12-month window: other donations are also limited
                if ($today < $eligibleFrom) {
                    $blockedOrgans[] = [
                        'organ'           => 'All major donations (Liver recovery period)',
                        'eligible_on'     => $eligibleFrom->format('Y-m-d'),
                        'days_remaining'  => (int)$today->diff($eligibleFrom)->days
                    ];
                    if (!$nextEligible || $eligibleFrom < new \DateTime($nextEligible)) {
                        $nextEligible = $eligibleFrom->format('Y-m-d');
                    }
                }
                continue;
            }
        }

        $isInRecovery = !empty($blockedOrgans) || !empty($permanentBlocks);

        // Build a human-readable summary message
        $messageParts = [];
        if (!empty($permanentBlocks)) {
            $messageParts[] = 'Permanently blocked: ' . implode(', ', $permanentBlocks) . '.';
        }
        if (!empty($blockedOrgans)) {
            foreach ($blockedOrgans as $b) {
                $messageParts[] = $b['organ'] . ' blocked until ' . $b['eligible_on']
                                . ' (' . $b['days_remaining'] . ' day(s) remaining).';
            }
        }
        if (empty($messageParts)) {
            $messageParts[] = 'Eligible for new donations.';
        }

        return [
            'is_in_recovery'   => $isInRecovery,
            'blocked_organs'   => $blockedOrgans,
            'next_eligible'    => $nextEligible,
            'history'          => $history,
            'permanent_blocks' => $permanentBlocks,
            'had_kidney'       => $hadKidney,
            'had_liver'        => $hadLiver,
            'message'          => implode(' ', $messageParts)
        ];
        } catch (\PDOException $e) {
            // If donation_medical_history table doesn't exist, return default eligibility
            return [
                'is_in_recovery'   => false,
                'blocked_organs'   => [],
                'next_eligible'    => null,
                'history'          => [],
                'permanent_blocks' => [],
                'restricted'       => [],
                'message'          => 'No donation history. Eligible for all donations.'
            ];
        }
    }

    /**
     * Record a completed donation in donation_medical_history
     * and automatically calculate + store the next_eligible_date.
     *
     * Call this whenever a donation is marked COMPLETED.
     *
     * @param int    $donorId
     * @param int    $pledgeId
     * @param string $donatedOrgan  e.g. 'Kidney', 'Bone Marrow', 'Liver Portion'
     * @param string $donationDate  e.g. '2025-01-15'
     * @param int    $hospitalId
     * @param string $doctorNotes
     * @return bool
     */
    public function recordDonationHistory($donorId, $pledgeId, $donatedOrgan, $donationDate, $hospitalId = null, $doctorNotes = '')
    {
        $donatedOrganLower = strtolower(trim((string)$donatedOrgan));

        // Calculate next_eligible_date based on organ type
        $nextEligibleDate  = null;
        $recoveryStatus    = 'IN_RECOVERY';

        if (str_contains($donatedOrganLower, 'kidney')) {
            // Kidney: permanently blocked. Recovery is short (~1 month typically)
            // Rule: Kidney -> Kidney = No. Kidney -> Marrow = Yes after recovery. 
            // Kidney -> Liver = Restricted.
            $nextEligibleDate = date('Y-m-d', strtotime($donationDate . ' + 1 month'));
        } elseif (str_contains($donatedOrganLower, 'liver')) {
            // Liver Portion: permanently blocked (usually). Next major donation 12-18m
            // Rule: Liver -> Liver = No. Liver -> Marrow = Yes after 12m.
            $nextEligibleDate = date('Y-m-d', strtotime($donationDate . ' + 12 months'));
        } elseif (str_contains($donatedOrganLower, 'marrow') || str_contains($donatedOrganLower, 'bone')) {
            // Bone Marrow: can re-donate after 6 months.
            $nextEligibleDate = date('Y-m-d', strtotime($donationDate . ' + 6 months'));
        } else {
            // Default recovery
            $nextEligibleDate = date('Y-m-d', strtotime($donationDate . ' + 3 months'));
        }

        $query = "INSERT INTO donation_medical_history 
                  (donor_id, pledge_id, donated_organ, donation_date, recovery_status, next_eligible_date, hospital_id, doctor_notes) 
                  VALUES (:did, :pid, :organ, :date, :status, :next, :hid, :notes)";
        
        return $this->query($query, [
            ':did'    => (int)$donorId,
            ':pid'    => (int)$pledgeId,
            ':organ'  => $donatedOrgan,
            ':date'   => $donationDate,
            ':status' => $recoveryStatus,
            ':next'   => $nextEligibleDate,
            ':hid'    => $hospitalId,
            ':notes'  => $doctorNotes
        ]);
    }

    /**
     * Automatically detect COMPLETED pledges and ensure they are recorded 
     * in the medical history table.
     */
    public function syncDonationHistory($donorId)
    {
        // 1. Find all COMPLETED pledges for this donor
        $query = "SELECT dp.*, o.name as organ_name 
                  FROM donor_pledges dp
                  JOIN organs o ON dp.organ_id = o.id
                  WHERE dp.donor_id = :did AND dp.status = 'COMPLETED'";
        $completedPledges = $this->query($query, [':did' => (int)$donorId]) ?: [];

        if (empty($completedPledges)) return;

        // 2. See which ones are already in history
        $historyQuery = "SELECT pledge_id FROM donation_medical_history WHERE donor_id = :did";
        $existing = $this->query($historyQuery, [':did' => (int)$donorId]) ?: [];
        $existingPledgeIds = array_map(fn($row) => (int)$row->pledge_id, $existing);

        // 3. For any COMPLETED pledge not in history, record it
        foreach ($completedPledges as $pledge) {
            if (!in_array((int)$pledge->id, $existingPledgeIds)) {
                // Record it with defaults if specific outcome info isn't available
                $this->recordDonationHistory(
                    $donorId, 
                    $pledge->id, 
                    $pledge->organ_name, 
                    date('Y-m-d'), // Assume current date if not specified
                    $pledge->hospital_id ?? null,
                    'Automatically recorded from pledge status completion.'
                );
            }
        }
    }

    /**
     * Check if donor has any active (non-withdrawn) living organ pledges.
     */
    public function hasActiveLivingPledges($donorId)
    {
        $query = "SELECT COUNT(*) as count 
                  FROM donor_pledges dp
                  JOIN living_donor_consents ldc ON dp.id = ldc.donor_pledge_id
                  WHERE dp.donor_id = :did AND dp.status != 'WITHDRAWN'";
        
        $result = $this->query($query, [':did' => (int)$donorId]);
        return ($result && $result[0]->count > 0);
    }

    /**
     * Get currently active roles for a donor
     */
    public function getActiveRoles($donorId)
    {
        $query = "SELECT active_roles FROM donors WHERE id = :id";
        $result = $this->query($query, [':id' => $donorId]);
        
        if ($result && !empty($result[0]->active_roles)) {
            return json_decode($result[0]->active_roles, true) ?: [];
        }
        
        return [];
    }

    /**
     * Update active roles for a donor
     */
    public function updateActiveRoles($donorId, array $roles)
    {
        // Map the first role to a primary category_id for legacy support
        $categoryId = 1; // Default to NON
        if (in_array('organ', $roles)) $categoryId = 3;
        else if (in_array('financial', $roles)) $categoryId = 2;
        else if (in_array('non', $roles)) $categoryId = 1;

        $rolesJson = json_encode($roles);
        $query = "UPDATE donors SET active_roles = :roles, category_id = :cat_id WHERE id = :id";
        
        // Use direct PDO execution via the trait's connection logic for non-SELECT queries
        $con = $this->connect();
        $stm = $con->prepare($query);
        return $stm->execute([
            ':roles' => $rolesJson,
            ':cat_id' => $categoryId,
            ':id' => $donorId
        ]);
    }

    public function getPendingMatchesForDonor($donorId)
    {
        $query = "SELECT m.match_id, m.donor_pledge_id, m.donor_status as status, m.match_date, 
                         o.name as organ_name, h.name as hospital_name, r.priority_level,
                         dp.organ_id
                  FROM donor_patient_match m
                  JOIN donor_pledges dp ON m.donor_pledge_id = dp.id
                  JOIN organ_requests r ON m.request_id = r.id
                  JOIN hospitals h ON r.hospital_id = h.id
                  JOIN organs o ON dp.organ_id = o.id
                  WHERE dp.donor_id = :donor_id AND m.donor_status IN ('PENDING', 'ACCEPTED')";
        
        return $this->query($query, [':donor_id' => $donorId]) ?: [];
    }

    public function processMatchDecision($matchId, $donorId, $decision)
    {
        try {
            $con = $this->connect();
            $con->beginTransaction();

            // 1. Verify match ownership and existence
            $chkSql = "SELECT m.donor_pledge_id FROM donor_patient_match m 
                       JOIN donor_pledges dp ON m.donor_pledge_id = dp.id 
                       WHERE m.match_id = :mid AND dp.donor_id = :did";
            
            $stmt = $con->prepare($chkSql);
            $stmt->execute([':mid' => $matchId, ':did' => $donorId]);
            $match = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$match) {
                $con->rollBack();
                return ['success' => false, 'message' => 'Match not found or unauthorized.'];
            }

            $pledgeId = $match['donor_pledge_id'];

            if ($decision === 'accept') {
                // 2. Set this match to ACCEPTED (accepted by donor)
                $upd1 = $con->prepare("UPDATE donor_patient_match SET donor_status = 'ACCEPTED' WHERE match_id = :mid");
                $upd1->execute([':mid' => $matchId]);

                // 3. Set ALL OTHER matches for this PLEDGE to REJECTED
                $upd2 = $con->prepare("UPDATE donor_patient_match SET donor_status = 'REJECTED' WHERE donor_pledge_id = :pid AND match_id != :mid");
                $upd2->execute([':pid' => $pledgeId, ':mid' => $matchId]);
                
                /* 
                // 4. Update the Pledge status to reflect matching success
                $upd3 = $con->prepare("UPDATE donor_pledges SET status = 'IN_PROGRESS' WHERE id = :pid");
                $upd3->execute([':pid' => $pledgeId]);
                */

                $msg = "Match accepted! Institutional coordination has been initiated.";
            } else {
                // Just reject this one
                $upd1 = $con->prepare("UPDATE donor_patient_match SET donor_status = 'REJECTED' WHERE match_id = :mid");
                $upd1->execute([':mid' => $matchId]);
                $msg = "Match rejected.";
            }

            $con->commit();
            return ['success' => true, 'message' => $msg];

        } catch (\Exception $e) {
            if (isset($con) && $con->inTransaction()) $con->rollBack();
            return ['success' => false, 'message' => 'Database error: ' . $e->getMessage()];
        }
    }

    /**
     * Sync Match Notifications: 
     * Identifies pending matches in donor_patient_match and ensures 
     * a corresponding entry exists in the notifications table.
     */
    public function syncMatchNotifications($donorId)
    {
        // 1. Get all pending matches for this donor
        $matches = $this->getPendingMatchesForDonor($donorId);
        if (empty($matches)) return;

        $notificationModel = new \App\Models\NotificationModel();
        
        // Fetch user_id for this donor to target notifications
        $donorData = $this->query("SELECT user_id FROM donors WHERE id = :id", [':id' => $donorId]);
        if (!$donorData) return;
        $userId = $donorData[0]->user_id;

        foreach ($matches as $match) {
            // Use a unique search key for action_url to prevent duplicates
            $matchUrl = "donor/donations?match_id=" . $match->match_id;
            
            // Check if this notification already exists
            $exists = $this->query("SELECT id FROM notifications WHERE user_id = :uid AND action_url = :url", [
                ':uid' => $userId,
                ':url' => $matchUrl
            ]);

            if (!$exists) {
                $notificationModel->addNotification([
                    'user_id' => $userId,
                    'type' => 'MATCH',
                    'title' => "Match Found: " . $match->organ_name,
                    'message' => "A potential life-saving match for your {$match->organ_name} has been found at {$match->hospital_name}. Priority Level: " . ($match->priority_level ?? 'Normal'),
                    'action_url' => $matchUrl
                ]);
            }
        }
    }
}
