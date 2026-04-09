<?php

namespace App\Models;

use App\Core\Database;

class UserModel {
    use Database;

    protected $table = 'users';

    public function createUser($username, $password, $role, $email = null, $phone = null, $status = 'PENDING')
    {
        $query = "INSERT INTO users (username, password_hash, role, email, phone, status, created_at) 
                  VALUES (:username, :password, :role, :email, :phone, :status, NOW())";
        
        $params = [
            ':username' => $username,
            ':password' => $password, 
            ':role' => $role,
            ':email' => $email,
            ':phone' => $phone,
            ':status' => $status
        ];
        
        return $this->insert($query, $params);
    }

    private function usersHasColumn($column)
    {
        static $cache = [];
        if (array_key_exists($column, $cache)) {
            return $cache[$column];
        }

        $result = $this->query(
            "SELECT COUNT(*) AS cnt
             FROM information_schema.COLUMNS
             WHERE TABLE_SCHEMA = :db
               AND TABLE_NAME = 'users'
               AND COLUMN_NAME = :col",
            [':db' => DBNAME, ':col' => $column]
        );

        $cache[$column] = ($result && (int)$result[0]->cnt > 0);
        return $cache[$column];
    }

    public function usernameExists($username)
    {
        $query = "SELECT COUNT(*) as count FROM users WHERE username = :username";
        $result = $this->query($query, [':username' => $username]);
        return $result && $result[0]->count > 0;
    }

    public function emailExists($email)
    {
        $query = "SELECT COUNT(*) as count FROM users WHERE email = :email";
        $result = $this->query($query, [':email' => $email]);
        return $result && $result[0]->count > 0;
    }

    public function getUserById($userId)
    {
        $query = "SELECT * FROM users WHERE id = :user_id";
        $result = $this->query($query, [':user_id' => $userId]);
        return $result ? $result[0] : null;
    }

    public function updateStatus($userId, $status)
    {
        $query = "UPDATE users SET status = :status WHERE id = :user_id";
        return $this->query($query, [
            ':status' => $status,
            ':user_id' => $userId
        ]);
    }

    public function getStatusByIdentifier($identifier)
    {
        $hasReviewMessage = $this->usersHasColumn('review_message');
        $reviewSelect = $hasReviewMessage ? 'review_message' : "'' AS review_message";

        // Try fetching by username or email directly from users table
        $query = "SELECT status, {$reviewSelect}, username, email, role FROM users WHERE username = :id OR email = :id LIMIT 1";
        $result = $this->query($query, [':id' => $identifier]);
        
        if ($result && count($result) > 0) {
            return $result[0];
        }

        // Try fetching by NIC/RegNo from donors, hospitals, or medical schools
          $queryNIC = "SELECT u.status, " . ($hasReviewMessage ? 'u.review_message' : "''") . " AS review_message, u.username, u.email, u.role
                     FROM users u 
                     LEFT JOIN donors d ON u.id = d.user_id 
                     LEFT JOIN hospitals h ON u.id = h.user_id
                     LEFT JOIN medical_schools m ON u.id = m.user_id
                     WHERE d.nic = :nic 
                        OR h.registration_number = :nic 
                        OR m.ugc_number = :nic 
                     LIMIT 1";
        
        $resultNIC = $this->query($queryNIC, [':nic' => $identifier]);
        if ($resultNIC && count($resultNIC) > 0) {
            return $resultNIC[0];
        }

        return null;
    }

    public function getUserByEmailOrUsername($identifier) {
        $query = "SELECT * FROM users WHERE email = :id OR username = :id LIMIT 1";
        $result = $this->query($query, [':id' => $identifier]);
        if ($result && count($result) > 0) {
            return $result[0];
        }
        return null;
    }

    public function updatePassword($userId, $passwordHash) {
        $query = "UPDATE users SET password_hash = :hash WHERE id = :id";
        return $this->query($query, [
            ':hash' => $passwordHash,
            ':id' => $userId
        ]);
    }
}
