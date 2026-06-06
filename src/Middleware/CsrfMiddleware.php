<?php

namespace App\Middleware;

class CsrfMiddleware {

    public static function generate() {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    public static function verify() {
        $token = $_POST['_csrf'] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
        if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
            http_response_code(403);
            die(json_encode(['error' => 'CSRF token mismatch. Please refresh the page.']));
        }
    }

    public static function field() {
        $token = self::generate();
        return "<input type=\"hidden\" name=\"_csrf\" value=\"{$token}\">";
    }

    public static function meta() {
        $token = self::generate();
        return "<meta name=\"csrf-token\" content=\"{$token}\">";
    }
}
