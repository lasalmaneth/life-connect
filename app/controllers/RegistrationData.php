<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;
use App\Models\DonorModel;

class RegistrationData
{
    use Controller;

    public function checkAvailability()
    {
        header('Content-Type: application/json');

        $type = $_GET['type'] ?? '';
        $value = trim($_GET['value'] ?? '');

        if (empty($type) || empty($value)) {
            echo json_encode(['success' => false, 'available' => false, 'message' => 'Missing arguments']);
            return;
        }

        $available = true;
        $message = "Looks good";

        try {
            if ($type === 'username') {
                $userModel = new UserModel();
                if ($userModel->usernameExists($value)) {
                    $available = false;
                    $message = "Username already taken";
                } else {
                    $message = "Username available";
                }
            } elseif ($type === 'email') {
                $userModel = new UserModel();
                if ($userModel->emailExists($value)) {
                    $available = false;
                    $message = "Email already registered";
                } else {
                    $message = "Email available";
                }
            } elseif ($type === 'nic') {
                $donorModel = new DonorModel();
                if ($donorModel->nicExists($value)) {
                    $available = false;
                    $message = "NIC already registered";
                } else {
                    $message = "NIC available";
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Invalid type']);
                return;
            }

            echo json_encode([
                'success' => true,
                'available' => $available,
                'message' => $message
            ]);

        } catch (\Throwable $e) {
            echo json_encode([
                'success' => false, 
                'message' => 'System Error: ' . $e->getMessage()
            ]);
        }
    }

    public function sendOtp()
    {
        header('Content-Type: application/json');

        $data = json_decode(file_get_contents('php://input'), true);
        $email = strtolower(trim($data['email'] ?? ''));

        if (!$email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                'success' => false,
                'message' => 'Invalid email address'
            ]);
            return;
        }

        $otp = sprintf("%06d", mt_rand(1, 999999));
        $otpModel = new \App\Models\RegistrationOtpModel();

        try {
            // 1. Save OTP first
            if (!$otpModel->createOtp($email, $otp, 10)) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Database error generating OTP'
                ]);
                return;
            }

            // 2. Then try to send email
            $mailResult = \App\Core\Mailer::sendOtpEmail($email, $otp);

            if ($mailResult['success']) {
                echo json_encode([
                    'success' => true,
                    'message' => 'Verification code sent to your email.',
                    'debug' => $mailResult['debug'] ?? []
                ]);
                return;
            }

            // 3. Mail failed, but OTP is already saved in DB
            $isLocal =
                in_array($_SERVER['SERVER_NAME'] ?? '', ['localhost', '127.0.0.1']) ||
                in_array($_SERVER['REMOTE_ADDR'] ?? '', ['127.0.0.1', '::1']);

            $response = [
                'success' => false,
                'mail_failed' => true,
                'message' => 'OTP was generated, but the email could not be sent.',
                'debug' => $mailResult['debug'] ?? []
            ];

            if (!empty($mailResult['error'])) {
                $response['error'] = $mailResult['error'];
            }

            // Safe dev fallback only for local development
            if ($isLocal) {
                $response['dev_mode'] = true;
                $response['dev_otp'] = $otp;
                $response['message'] = 'OTP was generated, but email sending failed on local development.';
                error_log("LifeConnect DEV OTP for {$email}: {$otp}");
            }

            echo json_encode($response);
        } catch (\PDOException $e) {
            $msg = $e->getMessage();

            if (
                stripos($msg, 'registration_otps') !== false ||
                stripos($msg, 'table') !== false
            ) {
                echo json_encode([
                    'success' => false,
                    'message' => 'Database Sync Required! Please run the update_db.sql file in phpMyAdmin.'
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Database Exception: ' . $msg
                ]);
            }
        } catch (\Throwable $e) {
            error_log('RegistrationData::sendOtp error: ' . $e->getMessage());

            echo json_encode([
                'success' => false,
                'message' => 'System Error: ' . $e->getMessage()
            ]);
        }
    }

    public function verifyOtp()
    {
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        $email = strtolower(trim($data['email'] ?? ''));
        $otp = trim($data['otp'] ?? '');

        if (!$email || !$otp) {
            echo json_encode(['success' => false, 'message' => 'Email and OTP required']);
            return;
        }

        $otpModel = new \App\Models\RegistrationOtpModel();
        
        try {
            $result = $otpModel->verifyOtp($email, $otp);
            if ($result['success']) {
                echo json_encode([
                    'success' => true, 
                    'message' => 'Email successfully verified'
                ]);
            } else {
                echo json_encode([
                    'success' => false, 
                    'message' => $result['message'],
                    'remaining' => $result['remaining_attempts'] ?? 0
                ]);
            }
        } catch (\PDOException $e) {
            echo json_encode(['success' => false, 'message' => 'Database Sync Required: Please run the update_db.sql script.']);
        } catch (\Throwable $e) {
            error_log($e->getMessage());
            echo json_encode(['success' => false, 'message' => 'System Error: ' . $e->getMessage()]);
        }
    }

    public function checkStatus()
    {
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents('php://input'), true);
        $identifier = trim($data['identifier'] ?? '');

        if (!$identifier) {
            echo json_encode(['success' => false, 'message' => 'Please provide username, email, or NIC']);
            return;
        }

        $userModel = new UserModel();
        $statusRecord = $userModel->getStatusByIdentifier($identifier);

        if ($statusRecord) {
            echo json_encode([
                'success' => true, 
                'status' => strtoupper($statusRecord->status),
                'review_message' => $statusRecord->review_message
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No registration found for that detail.']);
        }
    }
}
