<?php
/**
 * TSILIZY LLC — Authentication
 */

namespace Core;

class Auth
{
    /**
     * Attempt to log in a user
     */
    public static function attempt(string $email, string $password): bool
    {
        // Check rate limiting
        $rateLimitKey = 'login_' . $email;
        if (!Security::checkRateLimit($rateLimitKey, MAX_LOGIN_ATTEMPTS, LOGIN_LOCKOUT_TIME)) {
            return false;
        }

        // Find user by email
        $sql = "SELECT u.*, r.name as role_name, r.slug as role_slug 
                FROM users u 
                LEFT JOIN user_roles ur ON u.id = ur.user_id 
                LEFT JOIN roles r ON ur.role_id = r.id 
                WHERE u.email = ? AND u.deleted_at IS NULL";
        
        $result = Database::query($sql, [$email]);
        $user = $result[0] ?? null;

        if (!$user) {
            return false;
        }

        // Check if user is active
        if ($user['status'] !== 'active') {
            return false;
        }

        // Verify password
        if (!Security::verifyPassword($password, $user['password'])) {
            return false;
        }

        // Clear rate limit on successful login
        Security::clearRateLimit($rateLimitKey);

        // Log the user in
        self::login($user);

        // Update last login
        $sql = "UPDATE users SET last_login_at = ? WHERE id = ?";
        Database::execute($sql, [date('Y-m-d H:i:s'), $user['id']]);

        return true;
    }

    /**
     * Log a user in
     */
    public static function login(array $user): void
    {
        Session::regenerate();
        
        Session::set('user_id', $user['id']);
        Session::set('user_email', $user['email']);
        Session::set('user_name', $user['first_name'] . ' ' . $user['last_name']);
        Session::set('user_role', $user['role_slug'] ?? 'user');
        Session::set('user_avatar', $user['avatar'] ?? null);
        Session::set('logged_in', true);
        Session::set('login_time', time());
    }

    /**
     * Log the current user out
     */
    public static function logout(): void
    {
        Session::destroy();
    }

    /**
     * Check if a user is logged in
     */
    public static function check(): bool
    {
        if (!Session::get('logged_in')) {
            return false;
        }

        // Check session expiry
        $loginTime = Session::get('login_time', 0);
        if ((time() - $loginTime) > SESSION_LIFETIME) {
            self::logout();
            return false;
        }

        return true;
    }

    /**
     * Get the current user's ID
     */
    public static function id(): ?int
    {
        return Session::get('user_id');
    }

    /**
     * Get the current user's data
     */
    public static function user(): ?array
    {
        if (!self::check()) {
            return null;
        }

        return [
            'id' => Session::get('user_id'),
            'email' => Session::get('user_email'),
            'name' => Session::get('user_name'),
            'role' => Session::get('user_role'),
            'avatar' => Session::get('user_avatar')
        ];
    }

    /**
     * Get the full user record from database
     */
    public static function userFull(): ?array
    {
        if (!self::check()) {
            return null;
        }

        $userId = self::id();
        $sql = "SELECT u.*, r.name as role_name, r.slug as role_slug 
                FROM users u 
                LEFT JOIN user_roles ur ON u.id = ur.user_id 
                LEFT JOIN roles r ON ur.role_id = r.id 
                WHERE u.id = ? AND u.deleted_at IS NULL";
        
        $result = Database::query($sql, [$userId]);
        return $result[0] ?? null;
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin(): bool
    {
        if (!self::check()) {
            return false;
        }

        $role = Session::get('user_role');
        return in_array($role, ['admin', 'super_admin']);
    }

    /**
     * Check if user is super admin
     */
    public static function isSuperAdmin(): bool
    {
        if (!self::check()) {
            return false;
        }

        return Session::get('user_role') === 'super_admin';
    }

    /**
     * Check if user has a specific permission
     */
    public static function can(string $permission): bool
    {
        if (!self::check()) {
            return false;
        }

        // Super admin can do everything
        if (self::isSuperAdmin()) {
            return true;
        }

        // Check user permissions
        $userId = self::id();
        $sql = "SELECT p.slug 
                FROM permissions p 
                INNER JOIN role_permissions rp ON p.id = rp.permission_id 
                INNER JOIN user_roles ur ON rp.role_id = ur.role_id 
                WHERE ur.user_id = ? AND p.slug = ?";
        
        $result = Database::query($sql, [$userId, $permission]);
        return !empty($result);
    }

    /**
     * Get all permissions for current user
     */
    public static function permissions(): array
    {
        if (!self::check()) {
            return [];
        }

        if (self::isSuperAdmin()) {
            $sql = "SELECT slug FROM permissions";
            $result = Database::query($sql);
            return array_column($result, 'slug');
        }

        $userId = self::id();
        $sql = "SELECT DISTINCT p.slug 
                FROM permissions p 
                INNER JOIN role_permissions rp ON p.id = rp.permission_id 
                INNER JOIN user_roles ur ON rp.role_id = ur.role_id 
                WHERE ur.user_id = ?";
        
        $result = Database::query($sql, [$userId]);
        return array_column($result, 'slug');
    }

    /**
     * Register a new user
     */
    public static function register(array $data): ?int
    {
        // Hash password
        $data['password'] = Security::hashPassword($data['password']);
        $data['status'] = 'active';
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        // Insert user
        $sql = "INSERT INTO users (first_name, last_name, email, password, phone, status, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $userId = Database::insert($sql, [
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['password'],
            $data['phone'] ?? null,
            $data['status'],
            $data['created_at'],
            $data['updated_at']
        ]);

        if (!$userId) {
            return null;
        }

        // Assign default role (user)
        $sql = "INSERT INTO user_roles (user_id, role_id) 
                SELECT ?, id FROM roles WHERE slug = 'user'";
        Database::execute($sql, [$userId]);

        return $userId;
    }

    /**
     * Generate password reset token
     */
    public static function createPasswordReset(string $email): ?string
    {
        $sql = "SELECT id FROM users WHERE email = ? AND deleted_at IS NULL";
        $result = Database::query($sql, [$email]);
        
        if (empty($result)) {
            return null;
        }

        $token = Security::token();
        $expiresAt = date('Y-m-d H:i:s', time() + 3600); // 1 hour

        // Delete any existing tokens for this email
        $sql = "DELETE FROM password_resets WHERE email = ?";
        Database::execute($sql, [$email]);

        // Create new token
        $sql = "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)";
        Database::execute($sql, [$email, $token, $expiresAt]);

        return $token;
    }

    /**
     * Verify password reset token
     */
    public static function verifyPasswordReset(string $token): ?string
    {
        $sql = "SELECT email FROM password_resets WHERE token = ? AND expires_at > ?";
        $result = Database::query($sql, [$token, date('Y-m-d H:i:s')]);
        
        return $result[0]['email'] ?? null;
    }

    /**
     * Reset password
     */
    public static function resetPassword(string $email, string $password): bool
    {
        $hashedPassword = Security::hashPassword($password);
        
        $sql = "UPDATE users SET password = ?, updated_at = ? WHERE email = ?";
        $updated = Database::execute($sql, [$hashedPassword, date('Y-m-d H:i:s'), $email]);

        if ($updated) {
            // Delete the reset token
            $sql = "DELETE FROM password_resets WHERE email = ?";
            Database::execute($sql, [$email]);
        }

        return $updated;
    }
}
