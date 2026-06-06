<?php

namespace App\Models;

use Core\Model;

class User extends Model {
    protected $table = 'users';

    public function findByEmail($email) {
        return $this->findBy('email', $email);
    }

    public function findById($id) {
        return $this->find($id);
    }

    // Get all users with role name — SAFE
    public function allWithRole($roleId = null) {
        $where  = $roleId ? 'u.role_id = ?' : '1=1';
        $params = $roleId ? [$roleId] : [];
        try {
            return $this->query(
                "SELECT u.id, u.name, u.email, u.phone, u.role_id, u.created_at,
                        r.name as role_name
                 FROM users u
                 LEFT JOIN roles r ON r.id = u.role_id
                 WHERE {$where}
                 ORDER BY u.created_at DESC",
                $params
            );
        } catch (\Exception $e) {
            // Roles table may not exist — return without role name
            return $this->query(
                "SELECT id, name, email, phone, role_id, created_at,
                        CASE role_id WHEN 1 THEN 'Super Admin' WHEN 2 THEN 'Hotel Admin' ELSE 'Guest' END as role_name
                 FROM users
                 ORDER BY created_at DESC"
            );
        }
    }

    // Search users
    public function search($term, $roleId = null) {
        $params = ['%' . $term . '%', '%' . $term . '%'];
        $roleClause = '';
        if ($roleId) {
            $roleClause = ' AND u.role_id = ?';
            $params[]   = $roleId;
        }
        try {
            return $this->query(
                "SELECT u.id, u.name, u.email, u.phone, u.role_id, u.created_at,
                        CASE u.role_id WHEN 1 THEN 'Super Admin' WHEN 2 THEN 'Hotel Admin' ELSE 'Guest' END as role_name
                 FROM users u
                 WHERE (u.name LIKE ? OR u.email LIKE ?){$roleClause}
                 ORDER BY u.created_at DESC",
                $params
            );
        } catch (\Exception $e) {
            return [];
        }
    }
}
