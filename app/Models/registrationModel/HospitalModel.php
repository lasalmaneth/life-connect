<?php

// Ensure DB constants and Database trait are available when this model is required directly
// (some views may include models directly without going through `public/index.php` which loads `init.php`).
if (!defined('DBHOST')) {
    require_once __DIR__ . '/../../core/config.php';
}
if (!trait_exists('Database')) {
    require_once __DIR__ . '/../../core/Database.php';
}

class HospitalModel {
    use Database;

    public function usernameExists($username) {
        $query = "SELECT COUNT(*) as count FROM users WHERE username = :username";
        $result = $this->query($query, ['username' => $username]);
        if ($result && isset($result[0]) && $result[0]->count > 0) {
            return true;
        }
        return false;
    }

    public function registrationNoExists($regNo) {
        $query = "SELECT COUNT(*) as count FROM hospitals WHERE registration_number = :reg_no";
        $result = $this->query($query, ['reg_no' => $regNo]);
        if ($result && isset($result[0]) && $result[0]->count > 0) {
            return true;
        }
        return false;
    }

    public function createUser($data) {
        $query = "INSERT INTO users (username, password_hash, role, status)
                  VALUES (:username, :password, :role, 'PENDING')";
        $params = [
            'username' => $data['username'],
            'password' => $data['password'],
            'role' => $data['role']
        ];

        $con = $this->connect();
        $stm = $con->prepare($query);
        if ($stm->execute($params)) {
            return $con->lastInsertId();
        } else {
            throw new Exception("Failed to create user account.");
        }
    }

    public function createHospital($userId, $data) {
        $query = "INSERT INTO hospitals 
                  (user_id, registration_number, name, address, district, facility_type, 
                   cmo_name, cmo_nic, medical_license_number, verification_status)
                  VALUES 
                  (:user_id, :registration_number, :name, :address, :district, :facility_type, 
                   :cmo_name, :cmo_nic, :medical_license_number, 'PENDING')";
        $params = [
            'user_id' => $userId,
            'registration_number' => $data['registration_no'] ?? $data['registration_number'],
            'name' => $data['h_name'] ?? $data['name'],
            'address' => $data['h_location'] ?? $data['address'],
            'district' => $data['district'],
            'facility_type' => $data['type'] ?? $data['facility_type'],
            'cmo_name' => $data['cmo_name'],
            'cmo_nic' => $data['cmo_nic'],
            'medical_license_number' => $data['medical_license_number'] ?? ''
        ];

        $con = $this->connect();
        $stm = $con->prepare($query);
        if (!$stm->execute($params)) {
            throw new Exception("Failed to create hospital record.");
        }
    }
}

