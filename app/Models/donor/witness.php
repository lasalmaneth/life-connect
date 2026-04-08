<?php
// FILE: app/models/donor/Witness.php

class Witness {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Get all witnesses for a specific donor
     */
    public function getWitnessesByDonorId($donor_id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    witness_id,
                    donor_id,
                    name,
                    nic_number,
                    contact_number,
                    address
                FROM witnesses 
                WHERE donor_id = ?
                ORDER BY witness_id
            ");
            $stmt->execute([$donor_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching witnesses: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a single witness by ID
     */
    public function getWitnessById($witness_id) {
        try {
            $stmt = $this->pdo->prepare("
                SELECT 
                    witness_id,
                    donor_id,
                    name,
                    nic_number,
                    contact_number,
                    address
                FROM witnesses 
                WHERE witness_id = ?
            ");
            $stmt->execute([$witness_id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching witness: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Add a new witness
     */
    public function addWitness($donor_id, $data) {
        try {
            $stmt = $this->pdo->prepare("
                INSERT INTO witnesses (
                    donor_id,
                    name,
                    nic_number,
                    contact_number,
                    address
                ) VALUES (?, ?, ?, ?, ?)
            ");
            
            return $stmt->execute([
                $donor_id,
                $data['name'],
                $data['nic_number'],
                $data['contact_number'],
                $data['address'] ?? null
            ]);
        } catch (PDOException $e) {
            error_log("Error adding witness: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update witness information
     */
    public function updateWitness($witness_id, $data) {
        try {
            $stmt = $this->pdo->prepare("
                UPDATE witnesses 
                SET 
                    name = ?,
                    nic_number = ?,
                    contact_number = ?,
                    address = ?
                WHERE id = ?
            ");
            
            return $stmt->execute([
                $data['name'],
                $data['nic_number'],
                $data['contact_number'],
                $data['address'] ?? null,
                $witness_id
            ]);
        } catch (PDOException $e) {
            error_log("Error updating witness: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a witness
     */
    public function deleteWitness($witness_id) {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM witnesses WHERE id = ?");
            return $stmt->execute([$witness_id]);
        } catch (PDOException $e) {
            error_log("Error deleting witness: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Count witnesses for a donor
     */
    public function countWitnessesByDonorId($donor_id) {
        try {
            $stmt = $this->pdo->prepare("SELECT COUNT(*) as count FROM witnesses WHERE donor_id = ?");
            $stmt->execute([$donor_id]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)$result['count'];
        } catch (PDOException $e) {
            error_log("Error counting witnesses: " . $e->getMessage());
            return 0;
        }
    }
}