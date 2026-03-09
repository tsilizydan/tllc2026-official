<?php
/**
 * TSILIZY LLC — Contract Model
 */

namespace App\Models;

use Core\Model;
use Core\Database;

class Contract extends Model
{
    protected static string $table = 'contracts';
    protected static array $fillable = [
        'user_id',
        'reference',
        'title',
        'content',
        'start_date',
        'end_date',
        'value',
        'status',
        'signed_at',
        'signed_by'
    ];

    /**
     * Get user's contracts
     */
    public static function forUser(int $userId): array
    {
        return Database::query(
            "SELECT * FROM contracts WHERE user_id = ? AND deleted_at IS NULL ORDER BY created_at DESC",
            [$userId]
        );
    }

    /**
     * Get active contracts
     */
    public static function active(): array
    {
        return Database::query(
            "SELECT c.*, CONCAT(u.first_name, ' ', u.last_name) as user_name 
             FROM contracts c 
             LEFT JOIN users u ON c.user_id = u.id 
             WHERE c.status = 'active' AND c.deleted_at IS NULL 
             ORDER BY c.end_date ASC"
        );
    }

    /**
     * Get expiring soon (next 30 days)
     */
    public static function expiringSoon(): array
    {
        return Database::query(
            "SELECT c.*, CONCAT(u.first_name, ' ', u.last_name) as user_name 
             FROM contracts c 
             LEFT JOIN users u ON c.user_id = u.id 
             WHERE c.status = 'active' AND c.end_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 30 DAY) AND c.deleted_at IS NULL 
             ORDER BY c.end_date ASC"
        );
    }

    /**
     * Generate reference
     */
    public static function generateReference(): string
    {
        $year = date('Y');
        $count = Database::query("SELECT COUNT(*) as count FROM contracts WHERE YEAR(created_at) = ?", [$year])[0]['count'] ?? 0;
        return 'CTR-' . $year . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }
}
