<?php

namespace App\Models;

use App\Core\Database;

class RegistrationOtpModel {
    use Database;

    public function createOtp($email, $otp, $expiresInMinutes = 10) {
        $email = strtolower(trim($email));
        try {
            // Only delete UNVERIFIED OTPs to prevent wiping out a successful verification
            $this->query("DELETE FROM registration_otps WHERE email = :email AND verified = 0", [':email' => $email]);
            
            $expiresAt = date('Y-m-d H:i:s', strtotime("+$expiresInMinutes minutes"));
            
            $query = "INSERT INTO registration_otps (email, otp, expires_at, verified) VALUES (:email, :otp, :expires_at, 0)";
            return $this->insert($query, [
                ':email' => $email,
                ':otp' => $otp,
                ':expires_at' => $expiresAt
            ]);
        } catch (\PDOException $e) {
            if (stripos($e->getMessage(), 'attempts') !== false) {
                $this->repairTable();
                return $this->createOtp($email, $otp, $expiresInMinutes);
            }
            throw $e;
        }
    }

    public function verifyOtp($email, $otp) {
        $email = strtolower(trim($email));
        try {
            $query = "SELECT * FROM registration_otps WHERE email = :email AND verified = 0 ORDER BY id DESC LIMIT 1";
            $results = $this->query($query, [':email' => $email]);

            if ($results && count($results) > 0) {
                $record = $results[0];
                
                // Check if blocked by too many attempts (max 5)
                if (isset($record->attempts) && $record->attempts >= 5) {
                    return ['success' => false, 'message' => 'Too many failed attempts. Please resend a new code.'];
                }

                // Check code match
                if ($record->otp === $otp) {
                    // Check expiry
                    if (strtotime($record->expires_at) > time()) {
                        // Valid! Mark as verified
                        $this->query("UPDATE registration_otps SET verified = 1, attempts = 0 WHERE id = :id", [':id' => $record->id]);
                        return ['success' => true];
                    } else {
                        return ['success' => false, 'message' => 'OTP has expired.'];
                    }
                } else {
                    // Wrong OTP - Increment attempts
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
            if (stripos($e->getMessage(), 'attempts') !== false) {
                $this->repairTable();
                return $this->verifyOtp($email, $otp);
            }
            throw $e;
        }
    }

    public function isEmailVerified($email) {
        $email = strtolower(trim($email));
        $query = "SELECT * FROM registration_otps WHERE email = :email AND verified = 1 ORDER BY id DESC LIMIT 1";
        $results = $this->query($query, [':email' => $email]);
        
        if ($results && count($results) > 0) {
            return true;
        }
        return false;
    }

    private function repairTable() {
        try {
            $this->query("ALTER TABLE registration_otps ADD COLUMN attempts INT DEFAULT 0 AFTER expires_at");
        } catch (\Exception $e) {
            // Already exists or other error, ignore
        }
    }
}
