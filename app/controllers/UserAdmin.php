<?php 

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\AdminModel;
use Exception;
use PDO;

class UserAdmin {
    use Controller;
    use Database;

    public function profile()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['user_id'])) {
            redirect('login');
        }

        $userId = $_SESSION['user_id'];
        $userModel = new \App\Models\UserModel();
        $user = $userModel->getUserById($userId);
        
        $message = '';
        $messageType = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';

            if (empty($username)) {
                $message = "Username determines your identity.";
                $messageType = "error";
            } else {
                // Update username if changed
                if ($username !== $user->username) {
                    if ($userModel->usernameExists($username)) {
                        $message = "Username already taken.";
                        $messageType = "error";
                    } else {
                        // We need a method to update user details, not just status
                        // For now, let's assume we can update directly via query or add a method to UserModel
                        $this->query("UPDATE users SET username = :username WHERE id = :id", [
                            'username' => $username,
                            'id' => $userId
                        ]);
                        $_SESSION['username'] = $username;
                        $user->username = $username;
                        $message = "Profile updated successfully.";
                        $messageType = "success";
                    }
                }

                // Update password if provided
                if (!empty($currentPassword) && !empty($newPassword)) {
                    if (password_verify($currentPassword, $user->password_hash)) {
                        if ($newPassword === $confirmPassword) {
                            if (strlen($newPassword) >= 8) {
                                $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
                                $this->query("UPDATE users SET password_hash = :hash WHERE id = :id", [
                                    'hash' => $passwordHash,
                                    'id' => $userId
                                ]);
                                $message = "Password updated successfully.";
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
            $users = $adminModel->getUsers($searchTerm, $role, $status);
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

            $adminModel = new AdminModel();
            $adminModel->updateUserStatus($userId, $status);

            echo json_encode(['success' => true, 'message' => 'User status updated']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getPendingDocuments() {
        header('Content-Type: application/json');
        try {
            $adminModel = new AdminModel();
            $docs = $adminModel->getPendingDocuments();
            echo json_encode(['success' => true, 'documents' => $docs]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function updateEntityVerification() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $type = $input['entity_type'] ?? null;
            $id = $input['id'] ?? null;
            $status = $input['status'] ?? null;

            if (!$type || !$id || !$status) {
                echo json_encode(['success' => false, 'message' => 'Missing parameters']);
                return;
            }

            $adminModel = new AdminModel();
            $adminModel->updateEntityVerification($type, $id, $status);

            echo json_encode(['success' => true, 'message' => "Verification status updated for $type"]);
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

    public function sendNotification() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $userId = $input['user_id'] ?? null;
            $title = $input['title'] ?? null;
            $message = $input['message'] ?? null;
            $type = $input['type'] ?? 'GENERAL';

            if (!$userId || !$title || !$message) {
                echo json_encode(['success' => false, 'message' => 'Missing parameters']);
                return;
            }

            $adminModel = new AdminModel();
            $adminModel->sendNotification($userId, $title, $message, $type);

            echo json_encode(['success' => true, 'message' => 'Notification sent']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function bulkUpdateUserStatus() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $userIds = $input['user_ids'] ?? [];
            $status = $input['status'] ?? null;

            if (empty($userIds) || !$status) {
                echo json_encode(['success' => false, 'message' => 'Missing parameters']);
                return;
            }

            $adminModel = new AdminModel();
            $adminModel->bulkUpdateUserStatus($userIds, $status);

            echo json_encode(['success' => true, 'message' => count($userIds) . ' users updated']);
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
            $id = $_GET['id'] ?? null;
            $role = $_GET['role'] ?? null;

            if (!$id || !$role) {
                echo json_encode(['success' => false, 'message' => 'Missing user ID or role']);
                return;
            }

            $adminModel = new AdminModel();
            $user = $adminModel->getDetailedUserById($id, $role);
            if ($user) {
                echo json_encode(['success' => true, 'user' => $user]);
            } else {
                echo json_encode(['success' => false, 'message' => 'User not found']);
            }
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function reviewUser() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $userId = $input['id'] ?? null;
            $role = $input['role'] ?? null;
            $action = $input['action'] ?? null; // 'APPROVE' or 'REJECT'
            $data = $input['data'] ?? []; // first_name, last_name, phone

            if (!$userId || !$role || !$action) {
                echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
                return;
            }

            $adminModel = new AdminModel();
            
            // Update the user details
            if (!empty($data)) {
                $adminModel->updateUserDetails($userId, $role, $data);
            }

            $newStatus = 'UNCHANGED';
            if ($action === 'APPROVE') {
                $newStatus = 'ACTIVE';
                $adminModel->updateUserStatus($userId, $newStatus);
            } elseif ($action === 'REJECT') {
                $newStatus = 'SUSPENDED';
                $adminModel->updateUserStatus($userId, $newStatus);
            }

            echo json_encode(['success' => true, 'message' => 'User details saved successfully', 'status' => $newStatus]);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function bulkUpdateEntityVerification() {
        header('Content-Type: application/json');
        try {
            $input = json_decode(file_get_contents('php://input'), true);
            $entities = $input['entities'] ?? [];
            $status = $input['status'] ?? null;

            if (empty($entities) || !$status) {
                echo json_encode(['success' => false, 'message' => 'Missing parameters']);
                return;
            }

            $adminModel = new AdminModel();
            $adminModel->bulkUpdateEntityVerification($entities, $status);

            echo json_encode(['success' => true, 'message' => count($entities) . ' documents updated']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
