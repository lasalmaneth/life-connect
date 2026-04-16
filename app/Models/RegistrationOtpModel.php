<?php

namespace App\Models;

use App\Core\Model;

class RegistrationOtpModel {
    use Model;

    protected $table = 'registration_otps';

    public function createOtp($email, $otp, $expiresInMinutes = 10) {
        $email = strtolower(trim($email));
        
        // Delete existing unverified OTPs for this email for registration purpose
        $this->deleteWhere([
            'email' => $email,
            'verified' => 0,
            'purpose' => 'registration'
        ], 'registration_otps');

        // Also delete legacy ones with NULL purpose
        $this->deleteWhere([
            'email' => $email,
            'verified' => 0,
            'purpose IS NULL' => null
        ], 'registration_otps');
        
        $expiresAt = date('Y-m-d H:i:s', strtotime("+$expiresInMinutes minutes"));
        
        return $this->insert([
            'email' => $email,
            'otp' => $otp,
            'expires_at' => $expiresAt,
            'verified' => 0,
            'purpose' => 'registration'
        ]);
    }

    public function verifyOtp($email, $otp) {
        $email = strtolower(trim($email));

        $record = $this->first([
            'email' => $email,
            'verified' => 0,
            'purpose' => 'registration'
        ], [], '*', 'id DESC');

        if (!$record) {
            // Fallback for NULL purpose
            $record = $this->first([
                'email' => $email,
                'verified' => 0,
                'purpose IS NULL' => null
            ], [], '*', 'id DESC');
        }

        if ($record) {
            if (isset($record->attempts) && $record->attempts >= 5) {
                return ['success' => false, 'message' => 'Too many failed attempts. Please resend a new code.'];
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

    public function isEmailVerified($email) {
        $email = strtolower(trim($email));
        $res = $this->first([
            'email' => $email, 
            'verified' => 1
        ], [
            'purpose' => 'password_reset' // We want registration verified, not reset
        ], '*', 'id DESC');
        
        return (bool)$res;
    }
}
