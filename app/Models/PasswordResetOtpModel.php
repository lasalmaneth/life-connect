<?php

namespace App\Models;

use App\Core\Database;

class PasswordResetOtpModel {
    use Database;

    public function createOtp($email, $otp, $expiresInMinutes = 10) {
        $email = strtolower(trim($email));
        $purpose = 'password_reset';
        
        $this->ensurePurposeColumn();

        try {
            $this->query("DELETE FROM registration_otps WHERE email = :email AND verified = 0 AND purpose = :purpose", [
                ':email' => $email,
                ':purpose' => $purpose
            ]);
            
            $expiresAt = date('Y-m-d H:i:s', strtotime("+$expiresInMinutes minutes"));
            
            $query = "INSERT INTO registration_otps (email, otp, expires_at, verified, purpose) VALUES (:email, :otp, :expires_at, 0, :purpose)";
            return $this->insert($query, [
                ':email' => $email,
                ':otp' => $otp,
                ':expires_at' => $expiresAt,
                ':purpose' => $purpose
            ]);
        } catch (\PDOException $e) {
            throw $e;
        }
    }

    public function verifyOtp($email, $otp) {
        $email = strtolower(trim($email));
        $purpose = 'password_reset';

        try {
            $query = "SELECT * FROM registration_otps WHERE email = :email AND verified = 0 AND purpose = :purpose ORDER BY id DESC LIMIT 1";
            $results = $this->query($query, [
                ':email' => $email,
                ':purpose' => $purpose
            ]);

            if ($results && count($results) > 0) {
                $record = $results[0];
                
                if (isset($record->attempts) && $record->attempts >= 5) {
                    return ['success' => false, 'message' => 'Too many failed attempts. Please request a new code.'];
                }

                if ($record->otp === $otp) {
                    if (strtotime($record->expires_at) > time()) {
                        $this->query("UPDATE registration_otps SET verified = 1, attempts = 0 WHERE id = :id", [':id' => $record->id]);
                        return ['success' => true];
                    } else {
                        return ['success' => false, 'message' => 'OTP has expired.'];
                    }
                } else {
                    $newAttempts = ($record->attempts ?? 0) + 1;
                    $this->query("UPDATE registration_otps SET attempts = :attempts WHERE id = :id", [
                        ':attempts' => $newAttempts,
                        ':id' => $record->id
                    ]);
                    $remaining = 5 - $newAttempts;
                    return ['success' => false, 'message' => "Invalid OTP. $remaining attempts remaining.", 'remaining_attempts' => $remaining];
                }
            }
            return ['success' => false, 'message' => 'No active OTP found for this email.'];
        } catch (\PDOException $e) {
            throw $e;
        }
    }
    
    public function markAsUsed($email) {
        $email = strtolower(trim($email));
        $purpose = 'password_reset';
        $this->query("DELETE FROM registration_otps WHERE email = :email AND purpose = :purpose", [
            ':email' => $email,
            ':purpose' => $purpose
        ]);
        return true;
    }

    public function isEmailVerified($email) {
        $email = strtolower(trim($email));
        $purpose = 'password_reset';
        $query = "SELECT * FROM registration_otps WHERE email = :email AND verified = 1 AND purpose = :purpose ORDER BY id DESC LIMIT 1";
        $results = $this->query($query, [
            ':email' => $email,
            ':purpose' => $purpose
        ]);
        
        return ($results && count($results) > 0);
    }

    private function ensurePurposeColumn() {
        try {
            $res = $this->query("SHOW COLUMNS FROM registration_otps LIKE 'purpose'");
            if(empty($res)) {
                $this->query("ALTER TABLE registration_otps ADD COLUMN purpose VARCHAR(50) DEFAULT 'registration'");
            }
        } catch (\Exception $e) {
        }
    }
}
