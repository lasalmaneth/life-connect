<?php

namespace App\Core;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

// Require files manually since no composer
require_once __DIR__ . '/../libs/PHPMailer/Exception.php';
require_once __DIR__ . '/../libs/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/../libs/PHPMailer/SMTP.php';
require_once __DIR__ . '/mail_config.php';

class Mailer {

    public static function sendOtpEmail($toEmail, $otpCode) {
        $debugLog = [];
        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->SMTPDebug = SMTP::DEBUG_SERVER;
            $mail->Debugoutput = function($str, $level) use (&$debugLog) {
                $debugLog[] = "[$level] $str";
                error_log("PHPMailer Debug level $level; message: $str");
            };
            $mail->isSMTP();
            $mail->Host       = MAIL_HOST;
            $mail->SMTPAuth   = true;
            $mail->Username   = MAIL_USERNAME;
            $mail->Password   = MAIL_PASSWORD;
            $mail->SMTPSecure = MAIL_ENCRYPTION;
            $mail->Port       = MAIL_PORT;

            // Bypass SSL verification for local development (common requirement for Windows/XAMPP)
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            // Recipients
            $mail->setFrom(MAIL_FROM_EMAIL, MAIL_FROM_NAME);
            $mail->addAddress($toEmail);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'LifeConnect - Email Verification OTP';

            // HTML Template
            $mail->Body    = "
                <div style='font-family: Arial, sans-serif; max-width: 600px; margin: auto; padding: 20px; border: 1px solid #e2e8f0; border-radius: 8px;'>
                    <h2 style='color: #ef4444;'>LifeConnect Sri Lanka</h2>
                    <p>Hello,</p>
                    <p>Your one-time verification code is <strong>$otpCode</strong>. This code is required to complete your registration process.</p>
                    <div style='background-color: #f8fafc; padding: 20px; text-align: center; font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #1e293b; border-radius: 4px; margin: 20px 0;'>
                        $otpCode
                    </div>
                    <p style='color: #64748b; font-size: 14px;'>This code is valid for 10 minutes. If you did not request this code, please ignore this email.</p>
                    <hr style='border: none; border-top: 1px solid #e2e8f0; margin: 20px 0;'>
                    <p style='color: #94a3b8; font-size: 12px; text-align: center;'>&copy; " . date('Y') . " LifeConnect. All rights reserved.</p>
                </div>
            ";

            $mail->AltBody = "Hello,\n\nYour 6-digit verification code is: $otpCode\n\nThis code expires in 10 minutes. Please enter it to complete your registration.\n\n- LifeConnect Sri Lanka";

            $mail->send();
            return ['success' => true, 'debug' => $debugLog];

        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $mail->ErrorInfo);
            return ['success' => false, 'error' => $mail->ErrorInfo, 'debug' => $debugLog];
        }
    }
}
