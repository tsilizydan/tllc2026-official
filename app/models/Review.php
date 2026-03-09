<?php
/**
 * TSILIZY LLC — Review Model
 */

namespace App\Models;

use Core\Model;
use Core\Database;

class Review extends Model
{
    protected static string $table = 'reviews';
    protected static array $fillable = [
        'user_id',
        'name',
        'email',
        'company',
        'rating',
        'title',
        'content',
        'avatar',
        'status',
        'is_featured'
    ];

    /**
     * Get approved reviews
     */
    public static function approved(): array
    {
        return Database::query(
            "SELECT * FROM reviews WHERE status = 'approved' AND deleted_at IS NULL ORDER BY created_at DESC"
        );
    }

    /**
     * Get featured reviews
     */
    public static function featured(int $limit = 6): array
    {
        return Database::query(
            "SELECT * FROM reviews WHERE status = 'approved' AND is_featured = 1 AND deleted_at IS NULL ORDER BY created_at DESC LIMIT ?",
            [$limit]
        );
    }

    /**
     * Get pending reviews
     */
    public static function pending(): array
    {
        return Database::query(
            "SELECT * FROM reviews WHERE status = 'pending' AND deleted_at IS NULL ORDER BY created_at ASC"
        );
    }

    /**
     * Get average rating
     */
    public static function getAverageRating(): float
    {
        $result = Database::query("SELECT AVG(rating) as avg FROM reviews WHERE status = 'approved' AND deleted_at IS NULL");
        return round($result[0]['avg'] ?? 0, 1);
    }

    /**
     * Approve review
     */
    public static function approve(int $reviewId): bool
    {
        return Database::execute("UPDATE reviews SET status = 'approved', updated_at = NOW() WHERE id = ?", [$reviewId]);
    }

    /**
     * Reject review
     */
    public static function reject(int $reviewId): bool
    {
        return Database::execute("UPDATE reviews SET status = 'rejected', updated_at = NOW() WHERE id = ?", [$reviewId]);
    }
}
