<?php

namespace App\Models;

use App\Core\Model;

class PasswordResetOtpModel {
    use Model;

    protected $table = 'registration_otps';

    public function createOtp($email, $otp, $expiresInMinutes = 10) {
        $email = strtolower(trim($email));
        $purpose = 'password_reset';
        
        $this->ensurePurposeColumn();

        // Delete existing unverified OTPs for this email and purpose
        $this->deleteWhere([
            'email' => $email,
            'verified' => 0,
            'purpose' => $purpose
        ], 'registration_otps');
        
        $expiresAt = date('Y-m-d H:i:s', strtotime("+$expiresInMinutes minutes"));
        
        return $this->insert([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'verified' => 0,
            'purpose' => $purpose
        ]);
    }

    public function verifyOtp($email, $otp) {
        $email = strtolower(trim($email));
        $purpose = 'password_reset';

        $record = $this->first([
            'email' => $email,
            'verified' => 0,
            'purpose' => $purpose
        ], [], '*', 'id DESC');

        if ($record) {
            if (isset($record->attempts) && $record->attempts >= 5) {
                return ['success' => false, 'message' => 'Too many failed attempts. Please request a new code.'];
            }

            if ($record->otp === $otp) {
                if (strtotime($record->expires_at) > time()) {
                    $this->update($record->id, ['verified' => 1, 'attempts' => 0]);
                    return ['success' => true];
                } else {
                    return ['success' => false, 'message' => 'OTP has expired.'];
                }
            } else {
                $newAttempts = ($record->attempts ?? 0) + 1;
                $this->update($record->id, ['attempts' => $newAttempts]);
                $remaining = 5 - $newAttempts;
                return ['success' => false, 'message' => "Invalid OTP. $remaining attempts remaining.", 'remaining_attempts' => $remaining];
            }
        }
        return ['success' => false, 'message' => 'No active OTP found for this email.'];
    }
    
    public function markAsUsed($email) {
        $email = strtolower(trim($email));
        $purpose = 'password_reset';
        return $this->updateWhere(['verified' => 1], ['email' => $email, 'purpose' => $purpose]);
    }

    public function isEmailVerified($email) {
        $email = strtolower(trim($email));
        $purpose = 'password_reset';
        $res = $this->first(['email' => $email, 'verified' => 1, 'purpose' => $purpose], [], '*', 'id DESC');
        return (bool)$res;
    }

    private function ensurePurposeColumn() {
        try {
            $res = $this->query("SHOW COLUMNS FROM registration_otps LIKE 'purpose'");
            if(empty($res)) {
                $this->query("ALTER TABLE registration_otps ADD COLUMN purpose VARCHAR(50) DEFAULT 'registration' AFTER email");
            }
        } catch (\Exception $e) {
        }
    }
}
