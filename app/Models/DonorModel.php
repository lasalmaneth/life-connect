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
     * Withdraw all non-finalized pledges for a donor across ALL systems
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

    public function getPledgedOrgans($donorId)
    {
        $query = "SELECT p.*, o.name as organ_name, o.description 
                  FROM donor_pledges p 
                  JOIN organs o ON p.organ_id = o.id 
                  WHERE p.donor_id = :donor_id AND p.status != 'WITHDRAWN'";
        
        $results = $this->query($query, [':donor_id' => $donorId]);
        
        // Add icons manually for now as they aren't in the DB
        // In a real app, these could be in the organs table
        if ($results) {
            foreach ($results as $key => $organ) {
                $icon = '❓';
                switch (strtolower($organ->organ_name)) {
                    case 'kidney': $icon = '🧬'; break;
                    case 'liver': $icon = '🥃'; break;
                    case 'heart': $icon = '❤️'; break;
                    case 'lung': $icon = '🫁'; break;
                    case 'pancreas': $icon = '🍬'; break; // Abstract representation
                    case 'intestine': $icon = '🧶'; break;
                    case 'cornea': $icon = '👁️'; break;
                    case 'bone marrow': $icon = '🦴'; break;
                }
                $results[$key]->organ_icon = $icon;
            }
        }
        
        // Convert objects to arrays for view compatibility if needed, but objects are standard in this app
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
