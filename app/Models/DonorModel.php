<?php

namespace App\Models;

use App\Core\Database;

class DonorModel {
    use Database;

    protected $table = 'donors';

    public function createDonor($userId, $personalData, $categoryId, $pledgeType = 'NONE')
    {
        $query = "INSERT INTO donors (
            user_id, category_id, pledge_type, first_name, last_name, gender, date_of_birth, 
            blood_group, nic_number, nationality, address, district, divisional_secretariat, 
            grama_niladhari_division, verification_status, consent_status, consent_date
        ) VALUES (
            :user_id, :category_id, :pledge_type, :first_name, :last_name, :gender, :dob,
            :blood_group, :nic, :nationality, :address, :district, :div_sec,
            :gn_div, 'PENDING', 'PENDING', NULL
        )";
        
        $params = [
            ':user_id' => $userId,
            ':category_id' => $categoryId,
            ':pledge_type' => $pledgeType,
            ':first_name' => $personalData['first_name'] ?? '',
            ':last_name' => $personalData['last_name'] ?? '',
            ':gender' => $personalData['gender'],
            ':dob' => $personalData['dob'],
            ':blood_group' => $personalData['blood_group'] ?? null,
            ':nic' => $personalData['nic'],
            ':nationality' => $personalData['nationality'] ?? 'Sri Lankan',
            ':address' => $personalData['address'] ?? '',
            ':district' => $personalData['district'] ?? '',
            ':div_sec' => $personalData['divisional_secretariat'] ?? '',
            ':gn_div' => $personalData['gn_division'] ?? ''
        ];
        
        return $this->insert($query, $params);
    }

    public function nicExists($nic)
    {
        $query = "SELECT COUNT(*) as count FROM donors WHERE nic_number = :nic";
        $result = $this->query($query, [':nic' => $nic]);
        return $result && $result[0]->count > 0;
    }

    public function getDonorByUserId($userId)
    {
        $query = "SELECT * FROM donors WHERE user_id = :user_id";
        $result = $this->query($query, [':user_id' => $userId]);
        return $result ? $result[0] : null;
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
        // 1. Organ Pledges (Most common)
        $organQuery = "SELECT * FROM donor_pledges WHERE donor_id = :donor_id AND status != 'WITHDRAWN'";
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
        $organId = (int)$organId;
        if ($organId === 9) {
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
        $query = "SELECT p.*, o.name as organ_name, o.description,
                  (SELECT status FROM consent_withdrawals 
                   WHERE donor_id = p.donor_id AND organ_id = p.organ_id 
                   AND status = 'PENDING_UPLOAD' 
                   ORDER BY created_at DESC LIMIT 1) as withdrawal_status
                  FROM donor_pledges p 
                  JOIN organs o ON p.organ_id = o.id 
                  WHERE p.donor_id = :donor_id AND p.status != 'WITHDRAWN'
                  GROUP BY p.organ_id";
        
        $results = $this->query($query, [':donor_id' => $donorId]);
        
        if ($results) {
            foreach ($results as $key => $organ) {
                $icon = '❓';
                switch (strtolower($organ->organ_name)) {
                    case 'kidney': $icon = '🧬'; break;
                    case 'liver': $icon = '🥃'; break;
                    case 'heart': $icon = '❤️'; break;
                    case 'lung': $icon = '🫁'; break;
                    case 'pancreas': $icon = '🍬'; break;
                    case 'intestine': $icon = '🧶'; break;
                    case 'cornea': $icon = '👁️'; break;
                    case 'bone marrow': $icon = '🦴'; break;
                }
                $results[$key]->organ_icon = $icon;
            }
        }
        
        return $results ? json_decode(json_encode($results), true) : []; 
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

    public function updateDonorProfile($donorId, $updateData)
    {
        // Extract fields
        $contactNumber = $updateData['contact_number'] ?? '';
        $address = $updateData['address'] ?? '';
        $gnDiv = $updateData['grama_niladhari_division'] ?? '';
        $district = $updateData['district'] ?? '';
        $divSec = $updateData['divisional_secretariat'] ?? '';
        $email = $updateData['email'] ?? '';

        // Update donors table
        $donorQuery = "UPDATE donors SET 
                       address = :address,
                       nationality = :nationality,
                       grama_niladhari_division = :gn_div,
                       district = :district,
                       divisional_secretariat = :div_sec
                       WHERE id = :id";
        
        $donorParams = [
            ':address' => $address,
            ':nationality' => $updateData['nationality'] ?? 'Sri Lankan',
            ':gn_div' => $gnDiv,
            ':district' => $district,
            ':div_sec' => $divSec,
            ':id' => $donorId
        ];

        $updateDonor = $this->query($donorQuery, $donorParams);

        // Update users table for contact info
        // First get user_id
        $donor = $this->getDonorById($donorId);
        if ($donor) {
            $userQuery = "UPDATE users SET 
                          phone = :phone,
                          email = :email
                          WHERE id = :user_id";
            
            $userParams = [
                ':phone' => $contactNumber,
                ':email' => $email,
                ':user_id' => $donor->user_id
            ];
            $this->query($userQuery, $userParams);
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
                // Kidney→Liver: Restricted (doctor approval)
                if (!in_array('Kidney → Liver Portion (requires doctor approval)', $restricted)) {
                    $restricted[] = 'Kidney → Liver Portion (requires doctor approval)';
                }
                // After recovery, bone marrow is allowed — no timed block needed
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

                // Liver→Kidney: Restricted (doctor approval)
                if (!in_array('Liver Portion → Kidney (requires doctor approval)', $restricted)) {
                    $restricted[] = 'Liver Portion → Kidney (requires doctor approval)';
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
        if (!empty($restricted)) {
            $messageParts[] = 'Restricted (needs doctor approval): '
                            . implode('; ', $restricted) . '.';
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
            'restricted'       => $restricted,
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
            // Kidney: permanently blocked — use NULL to mean "never"
            $nextEligibleDate = null;
            $recoveryStatus   = 'PERMANENT_BLOCK';
        } elseif (str_contains($donatedOrganLower, 'bone') || str_contains($donatedOrganLower, 'marrow')) {
            // Bone Marrow: 6 months
            $nextEligibleDate = (new \DateTime($donationDate))->modify('+6 months')->format('Y-m-d');
            $recoveryStatus   = 'IN_RECOVERY';
        } elseif (str_contains($donatedOrganLower, 'liver')) {
            // Liver Portion: 12 months
            $nextEligibleDate = (new \DateTime($donationDate))->modify('+12 months')->format('Y-m-d');
            $recoveryStatus   = 'IN_RECOVERY';
        } else {
            // Unknown organ — default to 6 months recovery
            $nextEligibleDate = (new \DateTime($donationDate))->modify('+6 months')->format('Y-m-d');
            $recoveryStatus   = 'IN_RECOVERY';
        }

        $query = "INSERT INTO donation_medical_history 
                    (donor_id, pledge_id, donated_organ, donation_date, recovery_status, next_eligible_date, doctor_notes, hospital_id)
                  VALUES 
                    (:donor_id, :pledge_id, :donated_organ, :donation_date, :recovery_status, :next_eligible_date, :doctor_notes, :hospital_id)";

        $con = $this->connect();
        $stmt = $con->prepare($query);
        return $stmt->execute([
            ':donor_id'          => $donorId,
            ':pledge_id'         => $pledgeId,
            ':donated_organ'     => $donatedOrgan,
            ':donation_date'     => $donationDate,
            ':recovery_status'   => $recoveryStatus,
            ':next_eligible_date'=> $nextEligibleDate,
            ':doctor_notes'      => $doctorNotes,
            ':hospital_id'       => $hospitalId
        ]);
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
}
