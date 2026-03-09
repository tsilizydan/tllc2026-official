<?php
/**
 * TSILIZY LLC — Page Model
 */

namespace App\Models;

use Core\Model;

class Page extends Model
{
    protected static string $table = 'pages';
    protected static array $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'status',
        'is_homepage',
        'template',
        'parent_id',
        'order_index',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'author_id'
    ];

    /**
     * Get published pages
     */
    public static function published(string $orderBy = 'order_index', string $direction = 'ASC'): array
    {
        return self::where('status', 'published', $orderBy, $direction);
    }

    /**
     * Find by slug
     */
    public static function findBySlug(string $slug): ?array
    {
        return self::findBy('slug', $slug);
    }

    /**
     * Get homepage
     */
    public static function getHomepage(): ?array
    {
        $result = \Core\Database::query(
            "SELECT * FROM pages WHERE is_homepage = 1 AND status = 'published' AND deleted_at IS NULL LIMIT 1"
        );
        return $result[0] ?? null;
    }

    /**
     * Get menu pages
     */
    public static function getMenuPages(): array
    {
        return \Core\Database::query(
            "SELECT id, title, slug, parent_id FROM pages 
             WHERE status = 'published' AND deleted_at IS NULL 
             ORDER BY order_index ASC"
        );
    }
}
