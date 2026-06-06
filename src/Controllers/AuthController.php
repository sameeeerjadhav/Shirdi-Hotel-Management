<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;

class AuthController extends Controller {
    public function showLogin() {
        return $this->view('auth/login', [
            'title' => 'Login - CHNMS'
        ]);
    }

    public function login() {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role_id'] = $user['role_id'];
            $_SESSION['name'] = $user['name'];

            if ($user['role_id'] == 1) { // Super Admin
                $this->redirect('/admin/dashboard');
            } else if ($user['role_id'] == 2) { // Hotel Admin
                $this->redirect('/hotel/dashboard');
            } else { // Guest
                $this->redirect('/');
            }
        } else {
            // Error handling
            $_SESSION['error'] = 'Invalid email or password';
            $this->redirect('/login');
        }
    }

    public function logout() {
        session_destroy();
        $this->redirect('/login');
    }
}
