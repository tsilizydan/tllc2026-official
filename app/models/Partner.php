<?php
/**
 * TSILIZY LLC — Partner Model
 */

namespace App\Models;

use Core\Model;
use Core\Database;

class Partner extends Model
{
    protected static string $table = 'partners';
    protected static array $fillable = [
        'name',
        'slug',
        'description',
        'logo',
        'website',
        'category',
        'status',
        'is_featured',
        'order_index'
    ];

    /**
     * Get active partners
     */
    public static function active(): array
    {
        return Database::query(
            "SELECT * FROM partners WHERE status = 'active' AND deleted_at IS NULL ORDER BY order_index ASC"
        );
    }

    /**
     * Get featured partners
     */
    public static function featured(int $limit = 10): array
    {
        return Database::query(
            "SELECT * FROM partners WHERE status = 'active' AND is_featured = 1 AND deleted_at IS NULL ORDER BY order_index ASC LIMIT ?",
            [$limit]
        );
    }

    /**
     * Get by category
     */
    public static function byCategory(string $category): array
    {
        return Database::query(
            "SELECT * FROM partners WHERE category = ? AND status = 'active' AND deleted_at IS NULL ORDER BY order_index ASC",
            [$category]
        );
    }
}
