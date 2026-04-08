<?php

class NonDonorModel {
    use Database;

    public function usernameExists($username) {
        $query = "SELECT COUNT(*) as count FROM users WHERE username = :username";
        $result = $this->query($query, ['username' => $username]);
        if ($result && isset($result[0]) && $result[0]->count > 0) {
            return true;
        }
        return false;
    }

    public function nicExists($nic) {
        $query = "SELECT COUNT(*) as count FROM donors WHERE nic_number = :nic";
        $result = $this->query($query, ['nic' => $nic]);
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

    public function createDonor($userId, $data) {
        $query = "INSERT INTO donors 
                  (user_id, category_id, pledge_type, first_name, last_name, gender, date_of_birth, blood_group, 
                   nic_number, address, grama_niladhari_division, 
                   district, divisional_secretariat, verification_status, consent_status)
                  VALUES 
                  (:user_id, 1, 'NONE', :first_name, :last_name, :gender, :dob, :blood_group, 
                   :nic, :address, :gn_div, :district, :div_sec, 
                   'PENDING', 'PENDING')";
        
        $params = [
            'user_id' => $userId,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'gender' => $data['gender'],
            'dob' => $data['dob'],
            'blood_group' => $data['blood_group'] ?? '',
            'nic' => $data['nic'],
            'address' => $data['address'],
            'gn_div' => $data['grama_niladhari_division'],
            'district' => $data['district'],
            'div_sec' => $data['divisional_secretariat']
        ];

        $con = $this->connect();
        $stm = $con->prepare($query);
        if ($stm->execute($params)) {
            return $con->lastInsertId();
        } else {
            throw new Exception("Failed to create donor record.");
        }
    }

    public function createNonDonor($donorId, $reason) {
        // In the new schema, non-donors are handled via donors.pledge_type = 'NONE'
        // and donors.opt_out_reason stores the reason.
        $query = "UPDATE donors SET opt_out_reason = :reason WHERE id = :donor_id";
        
        $params = [
            'donor_id' => $donorId,
            'reason' => $reason
        ];

        $con = $this->connect();
        $stm = $con->prepare($query);
        if (!$stm->execute($params)) {
            throw new Exception("Failed to update non-donor record.");
        }
    }
}

