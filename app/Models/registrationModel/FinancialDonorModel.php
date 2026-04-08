<?php

class FinancialDonorModel {
    use Database;

    public function createUser($data) {
        $query = "INSERT INTO users (username, password_hash, role, status)
                  VALUES (:username, :password, :role, 'ACTIVE')";
        $params = [
            'username' => $data['username'],
            'password' => $data['password'],
            'role' => $data['role']
        ];

        // use PDO directly since your query() always fetches, not insert
        $con = $this->connect();
        $stm = $con->prepare($query);
        if ($stm->execute($params)) {
            return $con->lastInsertId();
        } else {
            throw new Exception("Failed to create user account.");
        }
    }

    public function createFinancialDonor($userId, $data) {
        $query = "INSERT INTO financial_donors 
                  (user_id, full_name, nic_number, donation_frequency)
                  VALUES (:user_id, :full_name, :nic_number, :donation_frequency)";
        $params = [
            'user_id' => $userId,
            'full_name' => $data['full_name'],
            'nic_number' => $data['nic'] ?? $data['nic_number'] ?? null,
            'donation_frequency' => $data['donation_frequency'] ?? 'ONETIME'
        ];

        $con = $this->connect();
        $stm = $con->prepare($query);
        if (!$stm->execute($params)) {
            throw new Exception("Failed to create donor record.");
        }
    }
}