<?php
/**
 * TSILIZY LLC — Security Utilities
 */

namespace Core;

class Security
{
    /**
     * Clean input data (XSS protection)
     */
    public static function clean($data)
    {
        if (is_array($data)) {
            return array_map([self::class, 'clean'], $data);
        }

        if (is_string($data)) {
            // Remove null bytes
            $data = str_replace("\0", '', $data);
            
            // Convert special characters to HTML entities
            $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8', true);
            
            // Trim whitespace
            $data = trim($data);
        }

        return $data;
    }

    /**
     * Clean input but allow HTML (for TinyMCE content)
     */
    public static function cleanHtml(string $data): string
    {
        // Remove null bytes
        $data = str_replace("\0", '', $data);
        
        // Remove potentially dangerous tags
        $data = preg_replace('/<(script|iframe|object|embed|applet|meta|link|base)[^>]*>.*?<\/\1>/is', '', $data);
        $data = preg_replace('/<(script|iframe|object|embed|applet|meta|link|base)[^>]*\/?>/is', '', $data);
        
        // Remove JavaScript event handlers
        $data = preg_replace('/\s*on\w+\s*=\s*["\'][^"\']*["\']/is', '', $data);
        
        // Remove javascript: protocols
        $data = preg_replace('/javascript:[^\s]*/is', '', $data);
        
        return trim($data);
    }

    /**
     * Hash a password
     */
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT, ['cost' => HASH_COST]);
    }

    /**
     * Verify a password
     */
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Generate a secure random token
     */
    public static function token(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    /**
     * Generate a secure random string
     */
    public static function randomString(int $length = 16): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        
        return $randomString;
    }

    /**
     * Validate email format
     */
    public static function isValidEmail(string $email): bool
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    /**
     * Validate URL format
     */
    public static function isValidUrl(string $url): bool
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }

    /**
     * Sanitize filename
     */
    public static function sanitizeFilename(string $filename): string
    {
        // Remove path info
        $filename = basename($filename);
        
        // Remove special characters
        $filename = preg_replace('/[^a-zA-Z0-9_.-]/', '_', $filename);
        
        // Remove multiple underscores
        $filename = preg_replace('/_+/', '_', $filename);
        
        return $filename;
    }

    /**
     * Generate a slug from text
     */
    public static function slug(string $text): string
    {
        // Replace non-alphanumeric characters with hyphens
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        
        // Transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        
        // Remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        
        // Trim hyphens
        $text = trim($text, '-');
        
        // Remove duplicate hyphens
        $text = preg_replace('~-+~', '-', $text);
        
        // Lowercase
        $text = strtolower($text);
        
        return $text ?: 'n-a';
    }

    /**
     * Encrypt data
     */
    public static function encrypt(string $data, string $key = ''): string
    {
        $key = $key ?: self::getEncryptionKey();
        $iv = random_bytes(16);
        $encrypted = openssl_encrypt($data, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        
        return base64_encode($iv . $encrypted);
    }

    /**
     * Decrypt data
     */
    public static function decrypt(string $data, string $key = ''): ?string
    {
        $key = $key ?: self::getEncryptionKey();
        $data = base64_decode($data);
        
        if ($data === false || strlen($data) < 16) {
            return null;
        }
        
        $iv = substr($data, 0, 16);
        $encrypted = substr($data, 16);
        
        $decrypted = openssl_decrypt($encrypted, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
        
        return $decrypted !== false ? $decrypted : null;
    }

    /**
     * Get or generate encryption key
     */
    private static function getEncryptionKey(): string
    {
        $keyFile = BASE_PATH . '/storage/.encryption_key';
        
        if (file_exists($keyFile)) {
            return file_get_contents($keyFile);
        }
        
        $key = self::token(32);
        
        $dir = dirname($keyFile);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }
        
        file_put_contents($keyFile, $key);
        
        return $key;
    }

    /**
     * Rate limiting check
     */
    public static function checkRateLimit(string $key, int $maxAttempts, int $windowSeconds): bool
    {
        $cacheFile = BASE_PATH . '/storage/cache/rate_limit_' . md5($key) . '.json';
        $cacheDir = dirname($cacheFile);
        
        if (!is_dir($cacheDir)) {
            mkdir($cacheDir, 0755, true);
        }
        
        $data = ['attempts' => [], 'blocked_until' => null];
        
        if (file_exists($cacheFile)) {
            $data = json_decode(file_get_contents($cacheFile), true) ?? $data;
        }
        
        // Check if blocked
        if ($data['blocked_until'] && time() < $data['blocked_until']) {
            return false;
        }
        
        // Clean old attempts
        $data['attempts'] = array_filter($data['attempts'], function($time) use ($windowSeconds) {
            return $time > (time() - $windowSeconds);
        });
        
        // Add current attempt
        $data['attempts'][] = time();
        
        // Check if exceeded
        if (count($data['attempts']) > $maxAttempts) {
            $data['blocked_until'] = time() + $windowSeconds;
            file_put_contents($cacheFile, json_encode($data));
            return false;
        }
        
        file_put_contents($cacheFile, json_encode($data));
        return true;
    }

    /**
     * Clear rate limit
     */
    public static function clearRateLimit(string $key): void
    {
        $cacheFile = BASE_PATH . '/storage/cache/rate_limit_' . md5($key) . '.json';
        
        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }
}
