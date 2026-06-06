<?php

namespace App\Models;

use Core\Model;

class Notification extends Model {
    protected $table = 'notifications';

    public function forUser($userId, $limit = 20) {
        return $this->query(
            "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT ?",
            [$userId, $limit]
        );
    }

    public function unreadCount($userId) {
        return $this->count('user_id = ? AND is_read = 0', [$userId]);
    }

    public function markRead($id, $userId) {
        return $this->execute(
            "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_id = ?",
            [$id, $userId]
        );
    }

    public function markAllRead($userId) {
        return $this->execute(
            "UPDATE notifications SET is_read = 1 WHERE user_id = ?",
            [$userId]
        );
    }

    public function send($userId, $type, $title, $message, $link = null) {
        return $this->create([
            'user_id' => $userId,
            'type'    => $type,
            'title'   => $title,
            'message' => $message,
            'link'    => $link,
        ]);
    }
}
