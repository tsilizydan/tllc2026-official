<?php
/**
 * TSILIZY LLC — Service Model
 */

namespace App\Models;

use Core\Model;

class Service extends Model
{
    protected static string $table = 'services';
    protected static array $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'icon',
        'image',
        'price',
        'duration',
        'features',
        'status',
        'is_featured',
        'order_index',
        'seo_title',
        'seo_description'
    ];

    /**
     * Get active services
     */
    public static function active(): array
    {
        return self::where('status', 'active', 'order_index', 'ASC');
    }

    /**
     * Get featured services
     */
    public static function featured(int $limit = 6): array
    {
        $sql = "SELECT * FROM services WHERE status = 'active' AND is_featured = 1 AND deleted_at IS NULL ORDER BY order_index ASC LIMIT ?";
        return \Core\Database::query($sql, [$limit]);
    }

    /**
     * Find by slug
     */
    public static function findBySlug(string $slug): ?array
    {
        return self::findBy('slug', $slug);
    }
}
