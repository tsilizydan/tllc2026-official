<?php
/**
 * TSILIZY LLC — Job Offer Model
 */

namespace App\Models;

use Core\Model;
use Core\Database;

class JobOffer extends Model
{
    protected static string $table = 'job_offers';
    protected static array $fillable = [
        'title',
        'slug',
        'description',
        'requirements',
        'benefits',
        'location',
        'department',
        'employment_type',
        'experience_level',
        'salary_min',
        'salary_max',
        'salary_currency',
        'is_remote',
        'application_deadline',
        'status',
        'is_featured',
        'seo_title',
        'seo_description'
    ];

    /**
     * Get active job offers
     */
    public static function active(): array
    {
        $sql = "SELECT * FROM job_offers WHERE status = 'open' AND deleted_at IS NULL ORDER BY created_at DESC";
        return Database::query($sql);
    }

    /**
     * Get featured jobs
     */
    public static function featured(int $limit = 3): array
    {
        $sql = "SELECT * FROM job_offers WHERE status = 'open' AND is_featured = 1 AND deleted_at IS NULL ORDER BY created_at DESC LIMIT ?";
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
     * Get by department
     */
    public static function byDepartment(string $department): array
    {
        return self::where('department', $department, 'created_at', 'DESC');
    }

    /**
     * Get application count
     */
    public static function getApplicationCount(int $jobId): int
    {
        $result = Database::query("SELECT COUNT(*) as count FROM job_applications WHERE job_offer_id = ?", [$jobId]);
        return $result[0]['count'] ?? 0;
    }

    /**
     * Get departments list
     */
    public static function getDepartments(): array
    {
        return Database::query("SELECT DISTINCT department FROM job_offers WHERE department IS NOT NULL AND deleted_at IS NULL ORDER BY department");
    }
}
