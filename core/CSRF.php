<?php
/**
 * TSILIZY LLC — CSRF Protection
 */

namespace Core;

class CSRF
{
    /**
     * Generate a new CSRF token
     */
    public static function generate(): string
    {
        if (!Session::has(CSRF_TOKEN_NAME)) {
            $token = bin2hex(random_bytes(32));
            Session::set(CSRF_TOKEN_NAME, $token);
        }

        return Session::get(CSRF_TOKEN_NAME);
    }

    /**
     * Validate a CSRF token
     */
    public static function validate(?string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $sessionToken = Session::get(CSRF_TOKEN_NAME);

        if (empty($sessionToken)) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    /**
     * Regenerate the CSRF token
     */
    public static function regenerate(): string
    {
        $token = bin2hex(random_bytes(32));
        Session::set(CSRF_TOKEN_NAME, $token);
        return $token;
    }

    /**
     * Get the current CSRF token
     */
    public static function get(): string
    {
        return Session::get(CSRF_TOKEN_NAME, '');
    }

    /**
     * Output hidden input field with CSRF token
     */
    public static function field(): string
    {
        $token = self::generate();
        return '<input type="hidden" name="' . CSRF_TOKEN_NAME . '" value="' . $token . '">';
    }

    /**
     * Output meta tag with CSRF token (for AJAX)
     */
    public static function meta(): string
    {
        $token = self::generate();
        return '<meta name="csrf-token" content="' . $token . '">';
    }
}
