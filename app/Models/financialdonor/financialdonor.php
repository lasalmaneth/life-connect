<?php
// FILE: app/models/Donor.php

class FinancialDonor {
    private $pdo;
    
    public function __construct($pdo) {
        $this->pdo = $pdo;
    }
    
    /**
     * Get donor information by user_id (PRIMARY METHOD TO USE)
     */
    public function getFinancialDonorByUserId($user_id) {
        try {
            $sql = "SELECT 
                        d.id as donor_id,
                        d.user_id,
                        d.full_name,
                        d.nic_number as nic,
                        u.phone,
                        u.email
                    FROM financial_donors d
                    JOIN users u ON d.user_id = u.id
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
                        d.full_name,
                        d.nic_number as nic,
                        u.phone,
                        u.email
                    FROM financial_donors d
                    JOIN users u ON d.user_id = u.id
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
     * Update Financial donor profile (only contact and location information)
     */
    public function updateDonorProfile($donor_id, $data) {
        try {
            $sql = "UPDATE financial_donors SET
                        full_name = :full_name
                    WHERE id = :donor_id";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':full_name', $data['full_name']);
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
    public function isNicExists($nic, $exclude_donor_id = null) {
        try {
            $sql = "SELECT COUNT(*) as count FROM financial_donors WHERE nic_number = :nic";
            
            if ($exclude_donor_id !== null) {
                $sql .= " AND id != :donor_id";
            }
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':nic', $nic);
            
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

    /**
     * Get all donations for a donor (for donation history)
     */
    public function getDonationsByDonorId($donor_id) {
        try {
            $sql = "SELECT 
                        donation_id,
                        donor_id,
                        amount,
                        date,
                        note,
                        status
                    FROM donations
                    WHERE financial_donor_id = :donor_id
                    ORDER BY created_at DESC";
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindParam(':donor_id', $donor_id, PDO::PARAM_INT);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching donations: " . $e->getMessage());
            return [];
        }
    }
}