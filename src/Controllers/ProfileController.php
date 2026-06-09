<?php

namespace App\Controllers;

use Core\Controller;
use App\Models\User;
use App\Models\Hotel;
use App\Middleware\CsrfMiddleware;

class ProfileController extends Controller {

    public function show() {
        if (!isset($_SESSION['user_id'])) {
            $this->redirect('/login');
        }

        $userModel = new User();
        $user      = $userModel->find($_SESSION['user_id']);

        // Fetch hotel for hotel admins
        $hotel = null;
        if ($_SESSION['role_id'] == 2) {
            $hotelModel = new Hotel();
            $hotel = $hotelModel->findByAdminUser($_SESSION['user_id']);
        }

        return $this->view('profile', [
            'title' => 'My Profile',
            'user'  => $user,
            'hotel' => $hotel,
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

        // ── Profile photo upload ──────────────────────────────────
        if ($action === 'avatar') {
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $path = $this->uploadImage($_FILES['avatar'], 'uploads/avatars');
                if ($path) {
                    // Delete old avatar if any
                    $root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
                    if (!empty($user['avatar']) && file_exists($root . '/' . $user['avatar'])) {
                        @unlink($root . '/' . $user['avatar']);
                    }
                    $userModel->update($_SESSION['user_id'], ['avatar' => $path]);
                    $_SESSION['success'] = 'Profile photo updated.';
                } else {
                    $_SESSION['error'] = 'Failed to upload image. Use JPG, PNG, or WebP under 2MB.';
                }
            } else {
                $_SESSION['error'] = 'No image selected or upload error.';
            }
            $this->redirect('/profile');
        }

        // ── Hotel logo upload (hotel admins only) ─────────────────
        if ($action === 'hotel_logo' && $_SESSION['role_id'] == 2) {
            if (isset($_FILES['hotel_logo']) && $_FILES['hotel_logo']['error'] === UPLOAD_ERR_OK) {
                $hotelModel = new Hotel();
                $hotel      = $hotelModel->findByAdminUser($_SESSION['user_id']);

                if ($hotel) {
                    $path = $this->uploadImage($_FILES['hotel_logo'], 'uploads/hotels');
                    if ($path) {
                        // Delete old logo if any
                        $root = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
                        if (!empty($hotel['cover_image']) && file_exists($root . '/' . $hotel['cover_image'])) {
                            @unlink($root . '/' . $hotel['cover_image']);
                        }
                        $hotelModel->update($hotel['id'], ['cover_image' => $path]);
                        $_SESSION['success'] = 'Hotel logo updated.';
                    } else {
                        $_SESSION['error'] = 'Failed to upload logo. Use JPG, PNG, or WebP under 2MB.';
                    }
                } else {
                    $_SESSION['error'] = 'No hotel found for your account.';
                }
            } else {
                $_SESSION['error'] = 'No image selected or upload error.';
            }
            $this->redirect('/profile');
        }

        // ── Update name + phone ───────────────────────────────────
        if ($action === 'profile') {
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
            $_SESSION['name']    = $name;
            $_SESSION['success'] = 'Profile updated successfully.';
        }

        // ── Change password ───────────────────────────────────────
        if ($action === 'password') {
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

    // Upload helper — stores under public/uploads/*
    private function uploadImage($file, $folder) {
        $allowed   = ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/svg+xml'];
        $maxBytes  = 2 * 1024 * 1024; // 2 MB

        if (!in_array($file['type'], $allowed))  return false;
        if ($file['size'] > $maxBytes)            return false;

        $ext     = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $name    = uniqid('img_', true) . '.' . $ext;
        $root    = rtrim($_SERVER['DOCUMENT_ROOT'], '/');
        $destDir = $root . '/' . $folder;

        if (!is_dir($destDir)) {
            mkdir($destDir, 0755, true);
        }

        $destPath = $destDir . '/' . $name;
        if (!move_uploaded_file($file['tmp_name'], $destPath)) return false;

        return $folder . '/' . $name;
    }
}
