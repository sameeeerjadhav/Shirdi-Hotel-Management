<?php

namespace App\Utils;

class Sanitizer {

    public static function clean($value) {
        if (is_array($value)) {
            return array_map([self::class, 'clean'], $value);
        }
        return htmlspecialchars(strip_tags(trim((string) $value)), ENT_QUOTES, 'UTF-8');
    }

    public static function cleanPost(array $keys) {
        $result = [];
        foreach ($keys as $key) {
            $result[$key] = isset($_POST[$key]) ? self::clean($_POST[$key]) : null;
        }
        return $result;
    }

    public static function int($value) {
        return filter_var($value, FILTER_VALIDATE_INT) !== false ? (int) $value : 0;
    }

    public static function float($value) {
        return filter_var($value, FILTER_VALIDATE_FLOAT) !== false ? (float) $value : 0.0;
    }

    public static function email($value) {
        $email = filter_var(trim($value), FILTER_VALIDATE_EMAIL);
        return $email !== false ? $email : null;
    }

    public static function date($value) {
        $d = \DateTime::createFromFormat('Y-m-d', trim($value));
        return ($d && $d->format('Y-m-d') === trim($value)) ? trim($value) : null;
    }

    public static function phone($value) {
        return preg_replace('/[^0-9+\-\s]/', '', trim($value));
    }

    // Validate required fields and return errors
    public static function validate(array $data, array $rules) {
        $errors = [];
        foreach ($rules as $field => $rule) {
            $value = $data[$field] ?? null;
            foreach (explode('|', $rule) as $r) {
                if ($r === 'required' && (is_null($value) || $value === '')) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' is required.';
                    break;
                }
                if (strpos($r, 'min:') === 0) {
                    $min = (int) substr($r, 4);
                    if (strlen($value) < $min) {
                        $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " must be at least {$min} characters.";
                        break;
                    }
                }
                if ($r === 'email' && $value && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = 'Please enter a valid email address.';
                    break;
                }
                if ($r === 'numeric' && $value !== null && !is_numeric($value)) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' must be a number.';
                    break;
                }
            }
        }
        return $errors;
    }
}
