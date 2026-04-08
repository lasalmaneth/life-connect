<?php

namespace App\Models;

use App\Core\Database;

/**
 * Handles custodian CRUD from the Donor Portal side.
 * Reads/writes to the `custodians` table.
 */
class DonorCustodianModel {
    use Database;

    protected $table = 'custodians';

    /**
     * Get custodians for a donor (max 2)
     */
    public function getCustodiansByDonorId($donorId)
    {
        $query = "SELECT c.*, o.name as organ_name 
                  FROM custodians c 
                  LEFT JOIN organs o ON c.organ_id = o.id 
                  WHERE c.donor_id = :donor_id 
                  ORDER BY (c.organ_id IS NULL) DESC, c.organ_id ASC, c.custodian_number ASC";
        return $this->query($query, [':donor_id' => $donorId]) ?: [];
    }

    /**
     * Count custodians for a donor
     */
    public function countByDonorId($donorId)
    {
        $query = "SELECT COUNT(*) as count FROM custodians WHERE donor_id = :donor_id";
        $result = $this->query($query, [':donor_id' => $donorId]);
        return $result ? (int)$result[0]->count : 0;
    }

    /**
     * Add a custodian for a specific organ pledge (living donation).
     * These are stored with organ_id to distinguish from general custodians.
     * Allows more than 2 total if they're organ-specific.
     */
    public function addOrganCustodians($donorId, $organId, array $custodians)
    {
        // Remove any existing organ-specific custodians for this organ
        $this->query(
            "DELETE FROM custodians WHERE donor_id = :donor_id AND organ_id = :organ_id",
            [':donor_id' => $donorId, ':organ_id' => $organId]
        );

        $saved = 0;
        foreach ($custodians as $index => $data) {
            if (empty($data['nic'])) continue;

            $userId = $this->findOrCreateCustodianUser($data);
            if (!$userId) continue;

            $query = "INSERT INTO custodians (
                        user_id, donor_id, organ_id, relationship, custodian_number,
                        name, nic_number, phone, email, address
                      ) VALUES (
                        :user_id, :donor_id, :organ_id, :relationship, :custodian_number,
                        :name, :nic, :phone, :email, :address
                      )";
            $result = $this->insert($query, [
                ':user_id'          => $userId,
                ':donor_id'         => $donorId,
                ':organ_id'         => $organId,
                ':relationship'     => $data['relationship'] ?? '',
                ':custodian_number' => $index + 1,
                ':name'             => $data['name'] ?? '',
                ':nic'              => $data['nic'] ?? '',
                ':phone'            => !empty($data['phone']) ? $data['phone'] : null,
                ':email'            => !empty($data['email']) ? $data['email'] : null,
                ':address'          => !empty($data['address']) ? $data['address'] : null,
            ]);
            if ($result) $saved++;
        }
        return $saved === count($custodians);
    }

    /**
     * Get custodians for a specific organ pledge.
     */
    public function getCustodiansByOrgan($donorId, $organId)
    {
        return $this->query(
            "SELECT * FROM custodians WHERE donor_id = :did AND organ_id = :oid ORDER BY custodian_number ASC",
            [':did' => $donorId, ':oid' => $organId]
        ) ?: [];
    }

    /**
     * Add a custodian (auto-assigns custodian_number 1 or 2)
     */
    public function addCustodian($donorId, $data)
    {
        $count = $this->countByDonorId($donorId);

        $custodianNumber = $count + 1;

        // Check if a user account exists for this NIC, or create one
        $userId = $this->findOrCreateCustodianUser($data);
        if (!$userId) return false;

        $query = "INSERT INTO custodians (
                    user_id, donor_id, relationship, custodian_number,
                    name, nic_number, phone, email, address
                  ) VALUES (
                    :user_id, :donor_id, :relationship, :custodian_number,
                    :name, :nic, :phone, :email, :address
                  )";
        return $this->insert($query, [
            ':user_id' => $userId,
            ':donor_id' => $donorId,
            ':relationship' => $data['relationship'] ?? '',
            ':custodian_number' => $custodianNumber,
            ':name' => $data['name'] ?? '',
            ':nic' => $data['nic'] ?? '',
            ':phone' => !empty($data['phone']) ? $data['phone'] : null,
            ':email' => !empty($data['email']) ? $data['email'] : null,
            ':address' => !empty($data['address']) ? $data['address'] : null
        ]);
    }

    /**
     * Update a custodian
     */
    public function updateCustodian($id, $donorId, $data)
    {
        $query = "UPDATE custodians SET
                    name = :name,
                    relationship = :relationship,
                    nic_number = :nic,
                    phone = :phone,
                    email = :email,
                    address = :address
                  WHERE id = :id AND donor_id = :donor_id";
        return $this->query($query, [
            ':id' => $id,
            ':donor_id' => $donorId,
            ':name' => $data['name'] ?? '',
            ':relationship' => $data['relationship'] ?? '',
            ':nic' => $data['nic'] ?? '',
            ':phone' => !empty($data['phone']) ? $data['phone'] : null,
            ':email' => !empty($data['email']) ? $data['email'] : null,
            ':address' => !empty($data['address']) ? $data['address'] : null
        ]);
    }

    /**
     * Delete a custodian
     */
    public function deleteCustodian($id, $donorId)
    {
        $query = "DELETE FROM custodians WHERE id = :id AND donor_id = :donor_id";
        $this->query($query, [':id' => $id, ':donor_id' => $donorId]);

        // Re-number remaining custodians
        $remaining = $this->getCustodiansByDonorId($donorId);
        foreach ($remaining as $index => $c) {
            $newNumber = $index + 1;
            if ($c->custodian_number != $newNumber) {
                $this->query(
                    "UPDATE custodians SET custodian_number = :num WHERE id = :id",
                    [':num' => $newNumber, ':id' => $c->id]
                );
            }
        }
        return true;
    }

    /**
     * Find an existing CUSTODIAN user by NIC, or create a new one.
     * The custodian can later change their password on first login.
     */
    private function findOrCreateCustodianUser($data)
    {
        $nic = $data['nic'] ?? '';
        if (empty($nic)) return false;

        // Check if user already exists with this NIC as username
        $checkQuery = "SELECT id FROM users WHERE username = :nic AND role = 'CUSTODIAN'";
        $existing = $this->query($checkQuery, [':nic' => $nic]);
        if ($existing && count($existing) > 0) {
            return $existing[0]->id;
        }

        // Create a new user account for the custodian
        // Default password is the NIC number (they should change it on first login)
        $defaultPassword = password_hash($nic, PASSWORD_DEFAULT);

        $insertQuery = "INSERT INTO users (username, password_hash, email, phone, role, status)
                        VALUES (:username, :password, :email, :phone, 'CUSTODIAN', 'ACTIVE')";
        return $this->insert($insertQuery, [
            ':username' => $nic,
            ':password' => $defaultPassword,
            ':email'    => !empty($data['email']) ? $data['email'] : null,
            ':phone'    => !empty($data['phone']) ? $data['phone'] : null
        ]);
    }
}
