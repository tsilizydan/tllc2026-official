<?php
/**
 * TSILIZY LLC — Portfolio Model
 */

namespace App\Models;

use Core\Model;

class Portfolio extends Model
{
    protected static string $table = 'portfolio';
    protected static array $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'client_name',
        'project_url',
        'image',
        'gallery',
        'category',
        'tags',
        'status',
        'is_featured',
        'completed_at',
        'order_index',
        'seo_title',
        'seo_description'
    ];

    /**
     * Get active portfolio items
     */
    public static function active(): array
    {
        return self::where('status', 'active', 'order_index', 'ASC');
    }

    /**
     * Get featured items
     */
    public static function featured(int $limit = 6): array
    {
        $sql = "SELECT * FROM portfolio WHERE status = 'active' AND is_featured = 1 AND deleted_at IS NULL ORDER BY order_index ASC LIMIT ?";
        return \Core\Database::query($sql, [$limit]);
    }

    /**
     * Find by slug
     */
    public static function findBySlug(string $slug): ?array
    {
        return self::findBy('slug', $slug);
    }

    /**
     * Get by category
     */
    public static function byCategory(string $category): array
    {
        return self::where('category', $category, 'order_index', 'ASC');
    }

    /**
     * Get categories
     */
    public static function getCategories(): array
    {
        return \Core\Database::query(
            "SELECT DISTINCT category FROM portfolio WHERE category IS NOT NULL AND deleted_at IS NULL ORDER BY category"
        );
    }
}
