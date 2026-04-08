<?php

class LiveOrganDonorModel {
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

    // Create user account
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

    // Create donor record
    public function createDonor($userId, $data) {
        $query = "INSERT INTO donors 
                  (user_id, category_id, pledge_type, first_name, last_name, gender, date_of_birth, blood_group, 
                   nic_number, address, grama_niladhari_division, 
                   district, divisional_secretariat, verification_status, consent_status)
                  VALUES 
                  (:user_id, 3, 'LIVING', :first_name, :last_name, :gender, :dob, :blood_group, 
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

    // Create witnesses for living donor
    public function createWitnesses($donorId, $witnessData) {
        $query = "INSERT INTO witnesses 
                  (donor_id, name, nic_number, contact_number)
                  VALUES (:donor_id, :name, :nic, :phone)";
        
        $con = $this->connect();
        $stm = $con->prepare($query);
        
        foreach ($witnessData as $witness) {
            $params = [
                'donor_id' => $donorId,
                'name' => $witness['name'],
                'nic' => $witness['nic'],
                'phone' => $witness['phone']
            ];
            
            if (!$stm->execute($params)) {
                throw new Exception("Failed to create witness record.");
            }
        }
    }

    // Create organ pledges
    public function createOrganPledges($donorId, $selectedOrgans) {
        $con = $this->connect();
        
        foreach ($selectedOrgans as $organName) {
            // Get organ_id from organs table (new schema)
            $query = "SELECT id FROM organs WHERE name = :organ_name";
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