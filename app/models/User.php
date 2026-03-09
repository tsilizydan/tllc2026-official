<?php
/**
 * TSILIZY LLC — User Model
 */

namespace App\Models;

use Core\Model;
use Core\Database;

class User extends Model
{
    protected static string $table = 'users';
    protected static array $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'avatar',
        'status',
        'email_verified_at',
        'last_login_at',
        'last_login_ip'
    ];

    /**
     * Get user's full name
     */
    public static function fullName(array $user): string
    {
        return trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
    }

    /**
     * Get user by email
     */
    public static function findByEmail(string $email): ?array
    {
        return self::findBy('email', $email);
    }

    /**
     * Get user's roles
     */
    public static function getRoles(int $userId): array
    {
        return Database::query(
            "SELECT r.* FROM roles r 
             INNER JOIN user_roles ur ON r.id = ur.role_id 
             WHERE ur.user_id = ?",
            [$userId]
        );
    }

    /**
     * Get user's permissions
     */
    public static function getPermissions(int $userId): array
    {
        return Database::query(
            "SELECT DISTINCT p.* FROM permissions p
             INNER JOIN role_permissions rp ON p.id = rp.permission_id
             INNER JOIN user_roles ur ON rp.role_id = ur.role_id
             WHERE ur.user_id = ?",
            [$userId]
        );
    }

    /**
     * Check if user has permission
     */
    public static function hasPermission(int $userId, string $permission): bool
    {
        $result = Database::query(
            "SELECT COUNT(*) as count FROM permissions p
             INNER JOIN role_permissions rp ON p.id = rp.permission_id
             INNER JOIN user_roles ur ON rp.role_id = ur.role_id
             WHERE ur.user_id = ? AND p.name = ?",
            [$userId, $permission]
        );
        return ($result[0]['count'] ?? 0) > 0;
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin(int $userId): bool
    {
        $result = Database::query(
            "SELECT COUNT(*) as count FROM user_roles ur
             INNER JOIN roles r ON ur.role_id = r.id
             WHERE ur.user_id = ? AND r.name = 'Super Admin'",
            [$userId]
        );
        return ($result[0]['count'] ?? 0) > 0;
    }

    /**
     * Assign role to user
     */
    public static function assignRole(int $userId, int $roleId): bool
    {
        return Database::execute(
            "INSERT IGNORE INTO user_roles (user_id, role_id) VALUES (?, ?)",
            [$userId, $roleId]
        );
    }

    /**
     * Remove role from user
     */
    public static function removeRole(int $userId, int $roleId): bool
    {
        return Database::execute(
            "DELETE FROM user_roles WHERE user_id = ? AND role_id = ?",
            [$userId, $roleId]
        );
    }

    /**
     * Get active users
     */
    public static function active(): array
    {
        return self::where('status', 'active', 'created_at', 'DESC');
    }

    /**
     * Get recent users
     */
    public static function recent(int $limit = 10): array
    {
        return Database::query(
            "SELECT * FROM users WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT ?",
            [$limit]
        );
    }
}
