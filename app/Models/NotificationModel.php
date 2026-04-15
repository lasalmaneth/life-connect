<?php

namespace App\Models;

use App\Core\Database;

class NotificationModel {
    use Database;

    protected $table = 'notifications';

    /**
     * Get all notifications for a specific user with sender details
     */
    public function getNotificationsForUser($userId, $limit = null)
    {
        $limitStr = $limit ? " LIMIT " . (int)$limit : "";
        $query = "SELECT n.*, 
                         s.username as sender_name,
                         s.role as sender_role
                  FROM notifications n
                  LEFT JOIN users s ON n.sender_id = s.id 
                  WHERE n.user_id = :user_id 
                  ORDER BY n.created_at DESC 
                  $limitStr";
        
        return $this->query($query, [':user_id' => $userId]);
    }

    /**
     * Get unread notification count for a user
     */
    public function getUnreadCount($userId)
    {
        $query = "SELECT COUNT(*) as count FROM notifications 
                  WHERE user_id = :user_id AND is_read = 0";
        $result = $this->query($query, [':user_id' => $userId]);
        return $result ? (int)$result[0]->count : 0;
    }

    /**
     * Mark a single notification as read
     */
    public function markAsRead($id)
    {
        $query = "UPDATE notifications SET is_read = 1 WHERE id = :id";
        $con = $this->connect();
        $stm = $con->prepare($query);
        return $stm->execute([':id' => $id]);
    }

    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId)
    {
        $query = "UPDATE notifications SET is_read = 1 WHERE user_id = :user_id";
        $con = $this->connect();
        $stm = $con->prepare($query);
        return $stm->execute([':user_id' => $userId]);
    }

    /**
     * Delete a notification
     */
    public function deleteNotification($id)
    {
        $query = "DELETE FROM notifications WHERE id = :id";
        $con = $this->connect();
        $stm = $con->prepare($query);
        return $stm->execute([':id' => $id]);
    }

    /**
     * Add a new notification
     */
    public function addNotification($data)
    {
        $query = "INSERT INTO notifications (user_id, sender_id, sender_type, type, title, message, action_url) 
                  VALUES (:user_id, :sender_id, :sender_type, :type, :title, :message, :action_url)";
        
        $params = [
            ':user_id'     => $data['user_id'],
            ':sender_id'   => $data['sender_id'] ?? null,
            ':sender_type' => $data['sender_type'] ?? 'ADMIN',
            ':type'        => $data['type'] ?? 'GENERAL',
            ':title'       => $data['title'],
            ':message'     => $data['message'],
            ':action_url'  => $data['action_url'] ?? $data['link'] ?? null
        ];

        return $this->insert($query, $params);
    }
}
