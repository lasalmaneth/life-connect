<?php

class MedicalSchoolModel {
    use Database;

    public function usernameExists($username) {
        $query = "SELECT COUNT(*) as count FROM users WHERE username = :username";
        $result = $this->query($query, ['username' => $username]);
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

    public function createMedicalSchool($userId, $data) {
        $query = "INSERT INTO medical_schools 
                  (user_id, school_name, university_affiliation, ugc_accreditation_number, 
                   address, district, contact_person_name, contact_person_phone, verification_status)
                  VALUES 
                  (:user_id, :school_name, :university_affiliation, :ugc_accreditation_number, 
                   :address, :district, :contact_person_name, :contact_person_phone, 'PENDING')";
        $params = [
            'user_id' => $userId,
            'school_name' => $data['institution_name'] ?? $data['school_name'],
            'university_affiliation' => $data['university_affiliation'],
            'ugc_accreditation_number' => $data['ugc_accreditation_number'],
            'address' => $data['address'],
            'district' => $data['district'],
            'contact_person_name' => $data['contact_person_name'],
            'contact_person_phone' => $data['contact_phone'] ?? $data['contact_person_phone']
        ];

        $con = $this->connect();
        $stm = $con->prepare($query);
        if (!$stm->execute($params)) {
            throw new Exception("Failed to create medical school record.");
        }
    }
}

