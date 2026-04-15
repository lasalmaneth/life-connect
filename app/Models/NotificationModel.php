<?php

namespace App\Models;

use App\Core\Database;

class NotificationModel
{
    use Database;

    protected $table = 'notifications';

    private function hasColumn(string $column): bool
    {
        static $cache = [];
        if (array_key_exists($column, $cache)) {
            return (bool) $cache[$column];
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $column)) {
            $cache[$column] = false;
            return false;
        }

        try {
            $res = $this->query("SHOW COLUMNS FROM notifications LIKE '{$column}'");
            $cache[$column] = !empty($res);
        } catch (\Throwable $e) {
            $cache[$column] = false;
        }

        return (bool) $cache[$column];
    }

    /**
     * Get all notifications for a specific user with sender details
     */
    public function getNotificationsForUser($userId, $limit = null)
    {
        $limitStr = $limit ? " LIMIT " . (int) $limit : "";
        $hasSenderId = $this->hasColumn('sender_id');
        $hasSenderType = $this->hasColumn('sender_type');
        $hasActionUrl = $this->hasColumn('action_url');

        if ($hasSenderId) {
            $query = "SELECT n.*";
            if ($hasActionUrl === false) {
                $query .= ", NULL as action_url";
            }
            if ($hasSenderType === false) {
                $query .= ", NULL as sender_type";
            }
            $query .= ", h.name as hospital_name
                      FROM notifications n
                      LEFT JOIN hospitals h ON n.sender_id = h.id
                      WHERE n.user_id = :user_id
                      ORDER BY n.created_at DESC
                      $limitStr";
        } else {
            $query = "SELECT n.*";
            if ($hasActionUrl === false) {
                $query .= ", NULL as action_url";
            }
            if ($hasSenderType === false) {
                $query .= ", NULL as sender_type";
            }
            $query .= "
                      FROM notifications n
                      WHERE n.user_id = :user_id
                      ORDER BY n.created_at DESC
                      $limitStr";
        }

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
        return $result ? (int) $result[0]->count : 0;
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
        // Support both minimal and extended notifications schemas.
        $hasSenderId = $this->hasColumn('sender_id');
        $hasSenderType = $this->hasColumn('sender_type');
        $hasActionUrl = $this->hasColumn('action_url');

        $cols = ['user_id', 'type', 'title', 'message'];
        $vals = [':user_id', ':type', ':title', ':message'];

        if ($hasSenderId) {
            $cols[] = 'sender_id';
            $vals[] = ':sender_id';
        }
        if ($hasSenderType) {
            $cols[] = 'sender_type';
            $vals[] = ':sender_type';
        }
        if ($hasActionUrl) {
            $cols[] = 'action_url';
            $vals[] = ':action_url';
        }

        $query = "INSERT INTO notifications (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ")";

        $params = [
            ':user_id' => $data['user_id'],
            ':type' => $data['type'] ?? 'GENERAL',
            ':title' => $data['title'],
            ':message' => $data['message'],
            ':sender_id' => $data['sender_id'] ?? null,
            ':sender_type' => $data['sender_type'] ?? 'ADMIN',
            ':action_url' => $data['action_url'] ?? $data['link'] ?? null
        ];

        if (!$hasSenderId)
            unset($params[':sender_id']);
        if (!$hasSenderType)
            unset($params[':sender_type']);
        if (!$hasActionUrl)
            unset($params[':action_url']);

        return $this->insert($query, $params);
    }
}
