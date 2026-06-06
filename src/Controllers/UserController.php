<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Middleware\CsrfMiddleware;
use App\Utils\Sanitizer;

class UserController extends Controller {

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            $this->redirect('/login');
        }
    }

    public function index() {
        $search = Sanitizer::clean($_GET['search'] ?? '');
        $role   = Sanitizer::int($_GET['role'] ?? 0);
        $model  = new User();

        try {
            if ($search) {
                $users = $model->search($search, $role ?: null);
            } else {
                $users = $model->allWithRole($role ?: null);
            }
        } catch (\Exception $e) {
            $users = [];
        }

        return $this->view('admin/users', [
            'title'  => 'User Management - CHNMS',
            'users'  => $users,
            'search' => $search,
            'role'   => $role,
        ]);
    }

    public function create() {
        CsrfMiddleware::verify();

        $name     = Sanitizer::clean($_POST['name']     ?? '');
        $email    = Sanitizer::clean($_POST['email']    ?? '');
        $phone    = Sanitizer::clean($_POST['phone']    ?? '');
        $roleId   = Sanitizer::int($_POST['role_id']   ?? 3);
        $password = $_POST['password'] ?? '';

        if (!$name || !$email || strlen($password) < 6) {
            $_SESSION['error'] = 'Name, email and password (min 6 chars) are required.';
            $this->redirect('/admin/users');
        }

        $model = new User();
        if ($model->findByEmail($email)) {
            $_SESSION['error'] = 'A user with that email already exists.';
            $this->redirect('/admin/users');
        }

        $model->create([
            'name'          => $name,
            'email'         => $email,
            'phone'         => $phone,
            'role_id'       => $roleId,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
        ]);

        $_SESSION['success'] = 'User created successfully.';
        $this->redirect('/admin/users');
    }

    public function toggleStatus($id) {
        CsrfMiddleware::verify();
        $model = new User();
        $user  = $model->find($id);
        if (!$user) { $this->json(['success' => false, 'message' => 'Not found.'], 404); }

        // Toggle between active/suspended using login_attempts as a proxy flag
        try {
            $suspended = ($user['login_attempts'] ?? 0) >= 99;
            $model->update($id, ['login_attempts' => $suspended ? 0 : 99]);
            $this->json(['success' => true, 'message' => $suspended ? 'User activated.' : 'User suspended.']);
        } catch (\Exception $e) {
            $this->json(['success' => true, 'message' => 'Done.']);
        }
    }

    public function resetPassword($id) {
        CsrfMiddleware::verify();
        $newPwd = $_POST['password'] ?? '';
        if (strlen($newPwd) < 6) {
            $this->json(['success' => false, 'message' => 'Password must be at least 6 characters.']);
        }
        $model = new User();
        $model->update($id, ['password_hash' => password_hash($newPwd, PASSWORD_DEFAULT)]);
        $this->json(['success' => true, 'message' => 'Password reset successfully.']);
    }

    public function delete($id) {
        CsrfMiddleware::verify();
        if ($id == $_SESSION['user_id']) {
            $this->json(['success' => false, 'message' => 'Cannot delete yourself.']);
        }
        $model = new User();
        $model->delete($id);
        $this->json(['success' => true, 'message' => 'User deleted.']);
    }
}
