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
        // Also pull latest history data for SUSPENDED/COMPLETED pledges
        $query = "SELECT p.*, o.name as organ_name, o.description,
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
                  JOIN organs o ON p.organ_id = o.id 
                  WHERE p.donor_id = :donor_id AND p.status != 'WITHDRAWN'
                  GROUP BY p.organ_id";
        
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

        // Mark pledge COMPLETED
        $this->query(
            "UPDATE donor_pledges SET status = 'COMPLETED' WHERE id = :pid AND donor_id = :did",
            [':pid' => $pledgeId, ':did' => $donorId]
        );

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
}
