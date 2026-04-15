<?php

namespace App\Models;

use App\Core\Model;

class UserModel {
    use Model;

    protected $table = 'users';

    protected $allowedColumns = [
        'username',
        'password_hash',
        'role',
        'email',
        'phone',
        'status',
        'review_message',
        'must_change_credentials',
        'created_at'
    ];

    public function createUser($username, $password, $role, $email = null, $phone = null, $status = 'PENDING')
    {
        return $this->insert([
            'username' => $username,
            'password_hash' => $password, 
            'role' => $role,
            'email' => $email,
            'phone' => $phone,
            'status' => $status,
            'created_at' => date('Y-m-d H:i:s')
        ]);
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
        return $this->count(['username' => $username]) > 0;
    }

    public function emailExists($email)
    {
        return $this->count(['email' => $email]) > 0;
    }

    public function getUserById($userId)
    {
        return $this->first(['id' => $userId]);
    }

    public function updateStatus($userId, $status)
    {
        return $this->update($userId, ['status' => $status]);
    }

    public function getStatusByIdentifier($identifier)
    {
        $hasReviewMessage = $this->usersHasColumn('review_message');
        $reviewSelect = $hasReviewMessage ? 'review_message' : "'' AS review_message";

        // Try direct lookup
        $res = $this->first(['username' => $identifier], [], "status, {$reviewSelect}, username, email, role");
        if (!$res) {
            $res = $this->first(['email' => $identifier], [], "status, {$reviewSelect}, username, email, role");
        }
        
        if ($res) return $res;

        // Try lookup via linked records (NIC / Register Number / UGC Number)
        $joins = [
            ['table' => 'donors d', 'on' => 'u.id = d.user_id', 'type' => 'LEFT'],
            ['table' => 'hospitals h', 'on' => 'u.id = h.user_id', 'type' => 'LEFT'],
            ['table' => 'medical_schools m', 'on' => 'u.id = m.user_id', 'type' => 'LEFT']
        ];
        
        $where = [
            'd.nic_number' => $identifier,
            'OR h.registration_number' => $identifier,
            'OR m.ugc_number' => $identifier
        ];

        $results = $this->queryJoin($joins, $where, "u.status, " . ($hasReviewMessage ? 'u.review_message' : "''") . " AS review_message, u.username, u.email, u.role", '', 1, 0, 'users u');
        return $results[0] ?? null;
    }

    public function getUserByEmailOrUsername($identifier) {
        $res = $this->first(['email' => $identifier]);
        return $res ?: $this->first(['username' => $identifier]);
    }

    public function updatePassword($userId, $passwordHash) {
        return $this->update($userId, ['password_hash' => $passwordHash]);
    }

    public function updateCredentials($userId, $username, $passwordHash) {
        return $this->update($userId, [
            'username' => $username,
            'password_hash' => $passwordHash
        ]);
    }

    public function clearMustChangeFlag($userId) {
        return $this->update($userId, ['must_change_credentials' => 0]);
    }
}
