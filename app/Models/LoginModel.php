<?php

namespace App\Models;

use App\Core\Model;

class LoginModel {
    use Model;

    protected $table = 'users';

    public function getUserByUsername($identifier) {
        $res = $this->first(['username' => $identifier]);
        return $res ?: $this->first(['email' => $identifier]);
    }
}
