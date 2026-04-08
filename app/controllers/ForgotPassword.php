<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\UserModel;
use App\Models\PasswordResetOtpModel;
use PHPMailer\PHPMailer\PHPMailer;   // Use the same mailer logic if possible

class ForgotPassword {
    use Controller;

    public function index() {
        $this->view('forgot-password');
    }

    public function sendOtp() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $identifier = trim($data['identifier'] ?? '');

        if (!$identifier) {
            echo json_encode(['success' => false, 'message' => 'Email or username is required']);
            return;
        }

        // Check if user exists
        $userModel = new UserModel();
        $user = $userModel->getUserByEmailOrUsername($identifier);

        if (!$user || empty($user->email)) {
            echo json_encode([
                'success' => false, 
                'message' => 'No account found with that email or username.'
            ]);
            return;
        }

        $otpModel = new PasswordResetOtpModel();
        
        // Generate OTP
        $otp = sprintf("%06d", mt_rand(100000, 999999));
        
        try {
            $otpModel->createOtp($user->email, $otp, 15); // Valid 15 mins
            \App\Core\Mailer::sendOtpEmail($user->email, $otp);
        } catch (\Exception $e) {
            error_log('ForgotPassword::sendOtp error: ' . $e->getMessage());
            echo json_encode([
                'success' => false,
                'message' => 'Failed to send OTP. Please try again later.'
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'message' => 'OTP has been sent to your registered email address.'
        ]);
    }

    public function verifyOtp() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $identifier = trim($data['identifier'] ?? '');
        $otp = trim($data['otp'] ?? '');

        if (!$identifier || !$otp) {
            echo json_encode(['success' => false, 'message' => 'Identifier and OTP are required']);
            return;
        }

        $userModel = new UserModel();
        $user = $userModel->getUserByEmailOrUsername($identifier);

        if (!$user || empty($user->email)) {
            // Fake delay to prevent enumeration
            usleep(500000);
            echo json_encode(['success' => false, 'message' => 'Invalid OTP.']);
            return;
        }

        $otpModel = new PasswordResetOtpModel();
        $res = $otpModel->verifyOtp($user->email, $otp);

        if ($res['success']) {
            if (session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['reset_authorized_for'] = $user->email;

            echo json_encode(['success' => true, 'message' => 'OTP verified. You can now reset your password.']);
        } else {
            echo json_encode(['success' => false, 'message' => $res['message']]);
        }
    }

    public function reset() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request']);
            return;
        }

        if (session_status() === PHP_SESSION_NONE) session_start();

        $data = json_decode(file_get_contents('php://input'), true);
        $identifier = trim($data['identifier'] ?? '');
        $password = $data['password'] ?? '';
        $confirm = $data['confirm'] ?? '';

        if (!$identifier || !$password || $password !== $confirm || strlen($password) < 8) {
            echo json_encode(['success' => false, 'message' => 'Invalid input or passwords do not match. Password must be 8+ characters.']);
            return;
        }

        $userModel = new UserModel();
        $user = $userModel->getUserByEmailOrUsername($identifier);

        if (!$user || !isset($_SESSION['reset_authorized_for']) || $_SESSION['reset_authorized_for'] !== $user->email) {
            $otpModel = new PasswordResetOtpModel();
            if (!$user || !$otpModel->isEmailVerified($user->email)) {
                echo json_encode(['success' => false, 'message' => 'Unauthorized reset request. Please verify OTP first.']);
                return;
            }
        }

        // Hash and update
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $userModel->updatePassword($user->id, $hash);

        // Mark OTP as used
        $otpModel = new PasswordResetOtpModel();
        $otpModel->markAsUsed($user->email);

        unset($_SESSION['reset_authorized_for']);

        echo json_encode(['success' => true, 'message' => 'Password reset successfully! You can now log in.']);
    }
}
