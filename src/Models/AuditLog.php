<?php

namespace App\Models;

use Core\Model;

class AuditLog extends Model {
    protected $table = 'audit_logs';

    public static function record($action, $model = null, $modelId = null, $oldValues = null, $newValues = null) {
        $db = \Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare(
            "INSERT INTO audit_logs (user_id, action, model, model_id, old_values, new_values, ip_address, user_agent)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $_SESSION['user_id'] ?? null,
            $action,
            $model,
            $modelId,
            $oldValues ? json_encode($oldValues) : null,
            $newValues ? json_encode($newValues) : null,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null,
        ]);
    }

    public function forUser($userId, $limit = 50) {
        return $this->query(
            "SELECT * FROM audit_logs WHERE user_id = ? ORDER BY created_at DESC LIMIT ?",
            [$userId, $limit]
        );
    }

    public function recent($limit = 100) {
        return $this->query(
            "SELECT al.*, u.name as user_name
             FROM audit_logs al
             LEFT JOIN users u ON u.id = al.user_id
             ORDER BY al.created_at DESC
             LIMIT ?",
            [$limit]
        );
    }
}
