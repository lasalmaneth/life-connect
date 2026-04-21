<?php 

namespace App\Controllers\admin;

use App\Core\Controller;
use App\Core\Database;
use App\Models\AdminModel;
use Exception;
use PDO;

class UserAdmin {
    use Controller;
    use Database;

    public function ajaxUpdateProfile()
    {
        header('Content-Type: application/json');
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            exit;
        }

        $userId = $_SESSION['user_id'];
        $data = json_decode(file_get_contents('php://input'), true);

        $firstName = $data['first_name'] ?? '';
        $lastName = $data['last_name'] ?? '';
        $contactNumber = $data['contact_number'] ?? '';
        $address = $data['address'] ?? '';
        $email = $data['email'] ?? '';
        $designation = $data['designation'] ?? '';

        if (empty($firstName) || empty($lastName) || empty($email)) {
            echo json_encode(['success' => false, 'message' => 'Please fill all required fields.']);
            exit;
        }

        try {
            // Update users table (email and phone)
            $this->query("UPDATE users SET email = :email, phone = :phone WHERE id = :id", [
                'email' => $email,
                'phone' => $contactNumber,
                'id' => $userId
            ]);

            // Update admins table (first_name, last_name, contact_number, address, designation)
            $this->query("UPDATE admins SET first_name = :fname, last_name = :lname, contact_number = :contact, address = :addr, designation = :des WHERE user_id = :id", [
                'fname' => $firstName,
                'lname' => $lastName,
                'contact' => $contactNumber,
                'addr' => $address,
                'des' => $designation,
                'id' => $userId
            ]);

            $newName = $firstName . ' ' . $lastName;
            
            // Update session data for immediate UI reflected changes
            $_SESSION['user_name'] = $newName; 
            $_SESSION['email'] = $email;

            echo json_encode([
                'success' => true, 
                'message' => 'Profile updated successfully',
                'new_name' => $newName
            ]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
        }
        exit;
    }

    public function profile()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        $userId = $_SESSION['user_id'];
        $userModel = new \App\Models\UserModel();
        $adminModel = new \App\Models\AdminModel();
        
        $user = $userModel->getUserById($userId);
        // Fetch detailed admin info
        $adminInfo = $this->query("SELECT * FROM admins WHERE user_id = :id", ['id' => $userId]);
        $admin = !empty($adminInfo) ? $adminInfo[0] : null;

        $message = '';
        $messageType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $email = $_POST['email'] ?? '';
            $firstName = $_POST['first_name'] ?? '';
            $lastName = $_POST['last_name'] ?? '';
            $contactNumber = $_POST['contact_number'] ?? '';
            $address = $_POST['address'] ?? '';
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($username) || empty($email)) {
                $message = "Username and Email are required.";
                $messageType = "error";
            } else {
                // 1. Update users table (username, email)
                $this->query("UPDATE users SET username = :username, email = :email WHERE id = :id", [
                    'username' => $username,
                    'email' => $email,
                    'id' => $userId
                ]);
                $_SESSION['username'] = $username;
                $user->username = $username;
                $user->email = $email;

                // 2. Update admins table (personal details)
                if ($admin) {
                    $this->query("UPDATE admins SET first_name = :fname, last_name = :lname, contact_number = :contact, address = :addr WHERE user_id = :id", [
                        'fname' => $firstName,
                        'lname' => $lastName,
                        'contact' => $contactNumber,
                        'addr' => $address,
                        'id' => $userId
                    ]);
                    $admin->first_name = $firstName;
                    $admin->last_name = $lastName;
                    $admin->contact_number = $contactNumber;
                    $admin->address = $address;
                }

                $message = "Profile details updated successfully.";
                $messageType = "success";

                // 3. Update password if provided
                if (!empty($currentPassword) && !empty($newPassword)) {
                    if (password_verify($currentPassword, $user->password_hash)) {
                        if ($newPassword === $confirmPassword) {
                            if (strlen($newPassword) >= 8) {
                                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                                $this->query("UPDATE users SET password_hash = :hash WHERE id = :id", [
                                    'hash' => $passwordHash,
                                    'id' => $userId
                                ]);
                                $message = "Profile and password updated successfully.";
                                $messageType = "success";
                            } else {
                                $message = "New password must be at least 8 characters.";
                                $messageType = "error";
                            }
                        } else {
                            $message = "New passwords do not match.";
                            $messageType = "error";
                        }
                    } else {
                        $message = "Current password is incorrect.";
                        $messageType = "error";
                    }
                }
            }
        }

        $this->view('admin/profile', [
            'user' => $user,
            'admin' => $admin,
            'ROOT' => ROOT,
            'message' => $message,
            'messageType' => $messageType
        ]);
    }

    public function checkUsername() {
        header('Content-Type: application/json');
        
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id'])) {
            echo json_encode(['success' => false, 'message' => 'Unauthorized']);
            return;
        }

        $username = $_GET['username'] ?? '';
        $currentUsername = $_SESSION['username'] ?? '';

        if (empty($username)) {
            echo json_encode(['success' => false, 'message' => 'Username required']);
            return;
        }

        // If the username is the same as the current one, it's valid (not taken by someone else)
        if ($username === $currentUsername) {
            echo json_encode(['success' => true, 'exists' => false]);
            return;
        }

        $userModel = new \App\Models\UserModel();
        $exists = $userModel->usernameExists($username);

        echo json_encode(['success' => true, 'exists' => $exists]);
    }

    public function index(){
        $this->view('admin/index', ['ROOT' => ROOT]);
    }

    public function getDashboardStats() {
        header('Content-Type: application/json');
        try {
            $adminModel = new AdminModel();
            $stats = $adminModel->getDashboardStats();
            echo json_encode(['success' => true, 'stats' => $stats]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getUsers() {
        header('Content-Type: application/json');
        try {
            $searchTerm = $_GET['search'] ?? '';
            $role = $_GET['role'] ?? '';
            $status = $_GET['status'] ?? '';
            
            $adminModel = new AdminModel();
            $users = $adminModel->getUsers($searchTerm, $role, $status) ?: [];
            echo json_encode(['success' => true, 'users' => $users]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateUserStatus() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $userId = $input['user_id'] ?? null;
            $status = $input['status'] ?? null;

            if (!$userId || !$status) {
                echo json_encode(['success' => false, 'message' => 'Missing parameters']);
                return;
            }

            // Role restriction: Only User Management Admins (U_ADMIN) can update status
            $currentRole = strtoupper($_SESSION['role'] ?? 'NONE');
            if ($currentRole !== 'U_ADMIN' && $currentRole !== 'ADMIN') {
                ob_clean();
                echo json_encode(['success' => false, 'message' => "Unauthorized: Role '$currentRole' is not permitted to change user status"]);
                return;
            }

            $adminModel = new AdminModel();
            
            // Get user info
            $user = $adminModel->getUserById($userId);
            if (!$user) {
                echo json_encode(['success' => false, 'message' => 'User not found']);
                return;
            }

            // Target protection: Prevent changing status of ANY Admin account
            $targetRole = strtoupper($user->role ?? '');
            $adminRoles = ['ADMIN', 'U_ADMIN', 'F_ADMIN', 'AC_ADMIN', 'D_ADMIN'];
            if (in_array($targetRole, $adminRoles)) {
                echo json_encode(['success' => false, 'message' => "Restricted: Administrative accounts (Role: $targetRole) are protected and their status cannot be modified here."]);
                return;
            }

            $oldStatus = strtoupper($user->status ?? 'UNKNOWN');
            $message = $input['review_message'] ?? null;
            $adminModel->updateUserStatus($userId, $status, $message);

            // ALWAYS log to admin audit trail (admin action record)
            $adminId = $_SESSION['user_id'] ?? null;
            $adminModel->logAdminAction(
                $adminId,
                $userId,
                'STATUS_CHANGE',
                $oldStatus,
                strtoupper($status),
                $message
            );

            // ONLY notify the user if they can actually log in and read it
            $newStatusUpper = strtoupper($status);
            if ($newStatusUpper === 'ACTIVE') {
                $notifTitle = $newStatusUpper === 'ACTIVE' && $oldStatus === 'SUSPENDED'
                    ? 'Account Reactivated'
                    : 'Account Approved';
                $notifMsg = $newStatusUpper === 'ACTIVE' && $oldStatus === 'SUSPENDED'
                    ? 'Your account has been reactivated. You can now log in.'
                    : 'Your account has been approved. Welcome to LifeConnect!';
                $adminModel->sendNotification($userId, $notifTitle, $notifMsg, 'SYSTEM', $adminId);
            }

            ob_clean();
            echo json_encode(['success' => true, 'message' => 'User status updated']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getNotifications() {
        header('Content-Type: application/json');
        try {
            $adminModel = new AdminModel();
            $notifications = $adminModel->getNotifications();
            echo json_encode(['success' => true, 'notifications' => $notifications]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getAuditLogs() {
        header('Content-Type: application/json');
        try {
            $adminModel = new AdminModel();
            $auditLogs = $adminModel->getAuditLogs();
            echo json_encode(['success' => true, 'auditLogs' => $auditLogs]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }


    public function getUser() {
        header('Content-Type: application/json');
        try {
            $id = $_GET['id'] ?? null;
            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'Missing user ID']);
                return;
            }

            $adminModel = new AdminModel();
            $user = $adminModel->getUserById($id);
            if ($user) {
                echo json_encode(['success' => true, 'user' => $user]);
            } else {
                echo json_encode(['success' => false, 'message' => 'User not found']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateUser() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $userId = $input['id'] ?? null;
            $data = $input['data'] ?? [];

            if (!$userId || empty($data)) {
                echo json_encode(['success' => false, 'message' => 'Missing parameters']);
                return;
            }

            $adminModel = new AdminModel();
            $adminModel->updateUser($userId, $data);

            echo json_encode(['success' => true, 'message' => 'User details updated']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getDetailedUser() {
        header('Content-Type: application/json');
        try {
            $id = (int)($_GET['id'] ?? 0);
            $role = trim((string)($_GET['role'] ?? ''));

            if ($id <= 0) {
                echo json_encode(['success' => false, 'message' => 'Missing or invalid user ID']);
                return;
            }

            $adminModel = new AdminModel();
            $base = $adminModel->getUserById($id);
            if (!$base) {
                echo json_encode(['success' => false, 'message' => 'User not found']);
                return;
            }

            $roleLower = strtolower($role);
            if ($role === '' || $roleLower === 'undefined' || $roleLower === 'null' || $roleLower === 'nan') {
                $role = (string)($base->role ?? '');
            }

            // Trust DB role if mismatch (prevents client-side role formatting from breaking lookups)
            if (!empty($base->role) && strcasecmp((string)$role, (string)$base->role) !== 0) {
                $role = (string)$base->role;
            }

            $user = $adminModel->getDetailedUserById($id, $role);
            if ($user) {
                echo json_encode(['success' => true, 'user' => $user]);
            } else {
                // Fallback to base user if role-specific join fails silently
                echo json_encode(['success' => true, 'user' => $base]);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function reviewUser() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $role = $input['role'] ?? null;
            $data = $input['data'] ?? []; // first_name, last_name, phone

            $adminModel = new AdminModel();
            
            // Role restriction: Only User Management Admins (U_ADMIN) can perform reviews
            $currentRole = strtoupper($_SESSION['role'] ?? 'NONE');
            if ($currentRole !== 'U_ADMIN' && $currentRole !== 'ADMIN') {
                ob_clean();
                echo json_encode(['success' => false, 'message' => "Unauthorized: Role '$currentRole' is not permitted to perform reviews"]);
                return;
            }

            $userId = $input['user_id'] ?? $input['id'] ?? null;
            if (!$userId) {
                echo json_encode(['success' => false, 'message' => 'Missing User ID for review (checked user_id and id keys)']);
                return;
            }
            $action = $input['action'] ?? null;
            $newStatus = $input['new_status'] ?? 'UNCHANGED';
            $reviewMessage = $input['review_message'] ?? null;
            
            // Standardize actions to statuses
            if ($newStatus === 'UNCHANGED') {
                if ($action === 'APPROVE') {
                    $newStatus = 'ACTIVE';
                } elseif ($action === 'REJECT') {
                    $newStatus = 'SUSPENDED';
                }
            }

            if ($newStatus !== 'UNCHANGED') {
                $newStatus = strtoupper($newStatus);

                // Get user's current status before updating
                $user = $adminModel->getUserById($userId);
                $oldStatus = $user ? strtoupper($user->status) : 'UNKNOWN';

                $adminModel->updateUserStatus($userId, $newStatus, $reviewMessage);

                // ALWAYS write to audit log
                $adminId = $_SESSION['user_id'] ?? null;
                $adminModel->logAdminAction(
                    $adminId,
                    $userId,
                    'ACCOUNT_REVIEW',
                    $oldStatus,
                    $newStatus,
                    $reviewMessage
                );

                // ONLY notify the user when status = ACTIVE (they can now log in and read it)
                if ($newStatus === 'ACTIVE') {
                    $notifTitle = ($oldStatus === 'SUSPENDED')
                        ? 'Account Reactivated'
                        : 'Account Approved — Welcome to LifeConnect';
                    $notifMsg = ($oldStatus === 'SUSPENDED')
                        ? 'Your account has been reactivated following an administrative review. You can now log in.'
                        : ($reviewMessage ?: 'Your account has been approved. Welcome aboard!');
                    $adminModel->sendNotification($userId, $notifTitle, $notifMsg, 'SYSTEM', $adminId);
                }

                if ($user) {
                    echo json_encode(['success' => true, 'message' => "User account set to $newStatus and details saved."]);
                } else {
                    echo json_encode(['success' => false, 'message' => "Update failed: User ID $userId not found in database."]);
                }
            } else {
                echo json_encode(['success' => true, 'message' => 'User details updated (no status change)']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Controller error: ' . $e->getMessage()]);
        }
    }

    public function getFeedbacks() {
        header('Content-Type: application/json');
        try {
            $adminModel = new AdminModel();
            $feedbacks = $adminModel->getFeedbacks();
            echo json_encode(['success' => true, 'feedbacks' => $feedbacks]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateFeedbackStatus() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? null;
            $status = $input['status'] ?? null;

            if (!$id || !$status) {
                echo json_encode(['success' => false, 'message' => 'Missing parameters']);
                return;
            }

            $adminModel = new AdminModel();
            $adminModel->updateFeedbackStatus($id, $status);

            echo json_encode(['success' => true, 'message' => 'Feedback status updated']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function deleteFeedback() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $id = $input['id'] ?? null;

            if (!$id) {
                echo json_encode(['success' => false, 'message' => 'Missing ID']);
                return;
            }

            $adminModel = new AdminModel();
            $adminModel->deleteFeedback($id);

            echo json_encode(['success' => true, 'message' => 'Feedback record permanently deleted']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
