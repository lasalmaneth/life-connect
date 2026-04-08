<?php

namespace App\Models;

use App\Core\Database;

class LoginModel {
    use Database;

    public function getUserByUsername($username) {
        $query = "SELECT * FROM users WHERE username = :username LIMIT 1";
        $data = ['username' => $username];
        $result = $this->query($query, $data);

        if ($result && count($result) > 0) {
            return $result[0]; // Return single user object
        }
        return false;
    }
}
