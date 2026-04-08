<?php
// FILE: app/models/Donor.php

class Donor {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Get donor information by user_id (PRIMARY METHOD TO USE)
     */
    public function getDonorByUserId($user_id) {
        try {
            $sql = "SELECT 
                        d.id as donor_id,
                        d.user_id,
                        d.first_name,
                        d.last_name,
                        d.gender,
                        d.date_of_birth,
                        d.blood_group,
                        d.nic_number,
                        d.nic_image_path,
                        d.address,
                        d.grama_niladhari_division,
                        d.district,
                        d.divisional_secretariat,
                        d.verification_status,
                        d.consent_status
                    FROM donors d
                    WHERE d.user_id = :user_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching donor by user_id: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get donor information by donor_id
     */
    public function getDonorById($donor_id) {
        try {
            $sql = "SELECT 
                        d.id as donor_id,
                        d.user_id,
                        d.first_name,
                        d.last_name,
                        d.gender,
                        d.date_of_birth,
                        d.blood_group,
                        d.nic_number,
                        d.nic_image_path,
                        d.address,
                        d.grama_niladhari_division,
                        d.district,
                        d.divisional_secretariat,
                        d.verification_status,
                        d.consent_status
                    FROM donors d
                    WHERE d.id = :donor_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':donor_id', $donor_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching donor: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all districts for dropdown
     */
    public function getAllDistricts() {
        return [
            'Ampara', 'Anuradhapura', 'Badulla', 'Batticaloa', 'Colombo',
            'Galle', 'Gampaha', 'Hambantota', 'Jaffna', 'Kalutara',
            'Kandy', 'Kegalle', 'Kilinochchi', 'Kurunegala', 'Mannar',
            'Matale', 'Matara', 'Monaragala', 'Mullaitivu', 'Nuwara Eliya',
            'Polonnaruwa', 'Puttalam', 'Ratnapura', 'Trincomalee', 'Vavuniya'
        ];
    }
    
    /**
     * Get all blood groups for dropdown
     */
    public function getAllBloodGroups() {
        return ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'];
    }
    
    /**
     * Update donor profile (only contact and location information)
     */
    public function updateDonorProfile($donor_id, $data) {
        try {
            $sql = "UPDATE donors SET
                        contact_number = :contact_number,
                        email = :email,
                        address = :address,
                        grama_niladhari_division = :grama_niladhari_division,
                        district = :district,
                        divisional_secretariat = :divisional_secretariat
                    WHERE id = :donor_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':contact_number', $data['contact_number']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':grama_niladhari_division', $data['grama_niladhari_division']);
            $stmt->bindParam(':district', $data['district']);
            $stmt->bindParam(':divisional_secretariat', $data['divisional_secretariat']);
            $stmt->bindParam(':donor_id', $donor_id, PDO::PARAM_INT);
            
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error updating donor profile: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if NIC exists for another donor
     */
    public function isNicExists($nic_number, $exclude_donor_id = null) {
        try {
            $sql = "SELECT COUNT(*) as count FROM donors WHERE nic_number = :nic_number";
            
            if ($exclude_donor_id !== null) {
                $sql .= " AND id != :donor_id";
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nic_number', $nic_number);
            
            if ($exclude_donor_id !== null) {
                $stmt->bindParam(':donor_id', $exclude_donor_id, PDO::PARAM_INT);
            }
            
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Error checking NIC: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get donor statistics
     */
    public function getDonorStats($donor_id) {
        try {
            $sql = "SELECT 
                        COUNT(dp.id) as total_organs,
                        SUM(CASE WHEN dp.status = 'APPROVED' THEN 1 ELSE 0 END) as approved_organs,
                        SUM(CASE WHEN dp.status = 'PENDING' THEN 1 ELSE 0 END) as pending_organs
                    FROM donor_pledges dp
                    WHERE dp.donor_id = :donor_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':donor_id', $donor_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching donor stats: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get pledged organs for a donor
     */
    public function getPledgedOrgans($donor_id) {
        try {
            $sql = "SELECT o.name as organ_name, o.organ_icon, dp.status, dp.created_at as pledged_date
                    FROM donor_pledges dp 
                    JOIN organs o ON dp.organ_id = o.id 
                    WHERE dp.donor_id = :donor_id
                    ORDER BY dp.created_at DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':donor_id', $donor_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching pledged organs: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get recent notifications for a donor
     */
    public function getNotifications($donor_id, $limit = 5) {
        try {
            $sql = "SELECT message, created_at as date_sent 
                    FROM notifications 
                    WHERE user_id = :donor_id 
                    ORDER BY created_at DESC 
                    LIMIT :limit";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':donor_id', $donor_id, PDO::PARAM_INT);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching notifications: " . $e->getMessage());
            return [];
        }
    }
}