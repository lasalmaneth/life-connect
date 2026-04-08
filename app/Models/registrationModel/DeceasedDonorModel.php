<?php

class DeceasedDonorModel {
    use Database;

    private $db;

    public function __construct()
    {
        $this->db = $this->connect();
    }

    public function beginTransaction()
    {
        $this->db->beginTransaction();
    }

    public function commit()
    {
        $this->db->commit();
    }

    public function rollback()
    {
        $this->db->rollback();
    }

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
                  (:user_id, 3, 'DECEASED_ORGAN', :first_name, :last_name, :gender, :dob, :blood_group, 
                   :nic, :address, :gn_div, :district, :div_sec, 
                   'PENDING', 'PENDING')";
        
        $params = [
            'user_id' => $userId,
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'gender' => $data['gender'],
            'dob' => $data['dob'],
            'blood_group' => $data['blood_group'],
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

    public function createDeceasedDonor($userId, $donorId, $personalData, $nextOfKin, $selectedOrgans) {
        // In the new schema, deceased donors are stored in the `donors` table with pledge_type = 'DECEASED_ORGAN'.
        // Next of kin info goes into the `next_of_kin` table.
        // Organ pledges go into the `donor_pledges` table.
        
        // Insert next of kin
        $kinQuery = "INSERT INTO next_of_kin 
                      (donor_id, name, relationship, nic_number, contact_number, email)
                      VALUES 
                      (:donor_id, :name, :relationship, :nic, :phone, :email)";
        
        $kinParams = [
            'donor_id' => $donorId,
            'name' => $nextOfKin['name'],
            'relationship' => $nextOfKin['relationship'],
            'nic' => $nextOfKin['nic'],
            'phone' => $nextOfKin['phone'],
            'email' => $nextOfKin['email']
        ];
        
        $con = $this->connect();
        $kinStm = $con->prepare($kinQuery);
        if (!$kinStm->execute($kinParams)) {
            throw new Exception("Failed to create next of kin record.");
        }
        
        // Create organ pledges
        $this->createOrganPledges($donorId, $selectedOrgans);
        
        return $donorId;
    }

    public function createOrganPledges($donorId, $selectedOrgans) {
        $con = $this->connect();
        
        foreach ($selectedOrgans as $organName) {
            // Get organ_id from organs table (new schema)
            $query = "SELECT id FROM organs WHERE name = :organ_name LIMIT 1";
            $stm = $con->prepare($query);
            $stm->execute(['organ_name' => $organName]);
            $organ = $stm->fetch(PDO::FETCH_ASSOC);
            
            if ($organ) {
                // Insert into donor_pledges table (new schema)
                $insertQuery = "INSERT INTO donor_pledges 
                               (donor_id, organ_id, status)
                               VALUES (:donor_id, :organ_id, 'PENDING')";
                
                $insertStm = $con->prepare($insertQuery);
                $insertParams = [
                    'donor_id' => $donorId,
                    'organ_id' => $organ['id']
                ];
                
                if (!$insertStm->execute($insertParams)) {
                    throw new Exception("Failed to create organ pledge for " . $organName);
                }
            }
        }
    }
}

