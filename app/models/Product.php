<?php
/**
 * TSILIZY LLC — Product Model
 */

namespace App\Models;

use Core\Model;
use Core\Database;

class Product extends Model
{
    protected static string $table = 'products';
    protected static array $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock',
        'category',
        'image',
        'gallery',
        'status',
        'is_featured',
        'order_index',
        'seo_title',
        'seo_description'
    ];

    /**
     * Get active products
     */
    public static function active(): array
    {
        return self::where('status', 'active', 'order_index', 'ASC');
    }

    /**
     * Get featured products
     */
    public static function featured(int $limit = 6): array
    {
        $sql = "SELECT * FROM products WHERE status = 'active' AND is_featured = 1 AND deleted_at IS NULL ORDER BY order_index ASC LIMIT ?";
        return Database::query($sql, [$limit]);
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
     * Check if in stock
     */
    public static function inStock(array $product): bool
    {
        return ($product['stock'] ?? 0) > 0;
    }

    /**
     * Get sale percentage
     */
    public static function getSalePercentage(array $product): int
    {
        if (!$product['sale_price'] || !$product['price']) return 0;
        return (int)round((($product['price'] - $product['sale_price']) / $product['price']) * 100);
    }
}
