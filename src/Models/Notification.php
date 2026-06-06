<?php

namespace App\Models;

use Core\Model;

class Notification extends Model {
    protected $table = 'notifications';

    public function forUser($userId, $limit = 20) {
        try {
            return $this->query(
                "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT " . (int)$limit,
                [$userId]
            );
        } catch (\Exception $e) {
            return [];
        }
    }

    public function unreadCount($userId) {
        try {
            return $this->count('user_id = ? AND is_read = 0', [$userId]);
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function markRead($id, $userId) {
        try {
            return $this->execute(
                "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?",
                [$id, $userId]
            );
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function markAllRead($userId) {
        try {
            return $this->execute(
                "UPDATE notifications SET is_read = 1 WHERE user_id = ?",
                [$userId]
            );
        } catch (\Exception $e) {
            return 0;
        }
    }

    public function send($userId, $type, $title, $message, $link = null) {
        try {
            return $this->create([
                'user_id' => $userId,
                'type'    => $type,
                'title'   => $title,
                'message' => $message,
                'link'    => $link,
            ]);
        } catch (\Exception $e) {
            // Silently fail if notifications table doesn't exist yet
            return null;
        }
    }
}
