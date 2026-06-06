<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Middleware\CsrfMiddleware;

class ProfileController extends Controller {

    public function show() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $userModel = new User();
        $user      = $userModel->find($_SESSION['user_id']);

        return $this->view('profile', [
            'title' => 'My Profile - CHNMS',
            'user'  => $user,
        ]);
    }

    public function update() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }
        CsrfMiddleware::verify();

        $userModel = new User();
        $user      = $userModel->find($_SESSION['user_id']);

        $action = $_POST['action'] ?? 'profile';

        if ($action === 'profile') {
            // Update name and phone
            $name  = trim(strip_tags($_POST['name']  ?? ''));
            $phone = trim(strip_tags($_POST['phone'] ?? ''));

            if (empty($name)) {
                $_SESSION['error'] = 'Name cannot be empty.';
                $this->redirect('/profile');
            }

            $userModel->update($_SESSION['user_id'], [
                'name'  => $name,
                'phone' => $phone,
            ]);
            $_SESSION['name'] = $name; // Update session name immediately
            $_SESSION['success'] = 'Profile updated successfully.';

        } elseif ($action === 'password') {
            // Change password
            $current = $_POST['current_password'] ?? '';
            $new     = $_POST['new_password']     ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if (!password_verify($current, $user['password_hash'])) {
                $_SESSION['error'] = 'Current password is incorrect.';
                $this->redirect('/profile');
            }
            if (strlen($new) < 8) {
                $_SESSION['error'] = 'New password must be at least 8 characters.';
                $this->redirect('/profile');
            }
            if ($new !== $confirm) {
                $_SESSION['error'] = 'New passwords do not match.';
                $this->redirect('/profile');
            }

            $userModel->update($_SESSION['user_id'], [
                'password_hash' => password_hash($new, PASSWORD_DEFAULT),
            ]);
            $_SESSION['success'] = 'Password changed successfully.';
        }

        $this->redirect('/profile');
    }
}
