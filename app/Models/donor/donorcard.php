<?php
// FILE: app/models/donor/DonorCard.php

class DonorCard {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Get donor card details including selected organs
     */
    public function getDonorCardData($donor_id) {
        try {
            // Get donor basic information
            $stmt = $this->pdo->prepare("
                SELECT 
                    d.id as donor_id,
                    d.first_name,
                    d.last_name,
                    d.date_of_birth,
                    d.blood_group,
                    d.nic_number,
                    u.id as user_id
                FROM donors d
                INNER JOIN users u ON d.user_id = u.id
                WHERE d.id = ?
            ");
            $stmt->execute([$donor_id]);
            $donor = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$donor) {
                return null;
            }

            // Get selected organs
            $stmt = $this->pdo->prepare("
                SELECT 
                    o.name as organ_name,
                    o.organ_icon,
                    dp.status,
                    dp.created_at as pledged_date
                FROM donor_pledges dp
                INNER JOIN organs o ON dp.organ_id = o.id
                WHERE dp.donor_id = ?
                ORDER BY o.name
            ");
            $stmt->execute([$donor_id]);
            $organs = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get witnesses
            $stmt = $this->pdo->prepare("
                SELECT 
                    name,
                    nic_number,
                    contact_number
                FROM witnesses
                WHERE donor_id = ?
                LIMIT 2
            ");
            $stmt->execute([$donor_id]);
            $witnesses = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return [
                'donor' => $donor,
                'organs' => $organs,
                'witnesses' => $witnesses
            ];

        } catch (PDOException $e) {
            error_log("Error fetching donor card data: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get appreciation certificate data
     */
    public function getCertificateData($donor_id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    d.id as donor_id,
                    d.first_name,
                    d.last_name,
                    d.nic_number,
                    COUNT(dp.organ_id) as organ_count,
                    MIN(dp.created_at) as registration_date
                FROM donors d
                LEFT JOIN donor_pledges dp ON d.id = dp.donor_id
                WHERE d.id = ?
                GROUP BY d.id
            ");
            $stmt->execute([$donor_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            error_log("Error fetching certificate data: " . $e->getMessage());
            return null;
        }
    }
}