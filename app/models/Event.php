<?php
/**
 * TSILIZY LLC — Event Model
 */

namespace App\Models;

use Core\Model;
use Core\Database;

class Event extends Model
{
    protected static string $table = 'events';
    protected static array $fillable = [
        'title',
        'slug',
        'description',
        'short_description',
        'location',
        'address',
        'start_date',
        'end_date',
        'image',
        'gallery',
        'category',
        'max_attendees',
        'registration_deadline',
        'is_online',
        'meeting_url',
        'price',
        'status',
        'is_featured',
        'seo_title',
        'seo_description'
    ];

    /**
     * Get upcoming events
     */
    public static function upcoming(int $limit = 6): array
    {
        $sql = "SELECT * FROM events WHERE status = 'published' AND start_date >= NOW() AND deleted_at IS NULL ORDER BY start_date ASC LIMIT ?";
        return Database::query($sql, [$limit]);
    }

    /**
     * Get past events
     */
    public static function past(int $limit = 10): array
    {
        $sql = "SELECT * FROM events WHERE status = 'published' AND start_date < NOW() AND deleted_at IS NULL ORDER BY start_date DESC LIMIT ?";
        return Database::query($sql, [$limit]);
    }

    /**
     * Get featured events
     */
    public static function featured(int $limit = 3): array
    {
        $sql = "SELECT * FROM events WHERE status = 'published' AND is_featured = 1 AND start_date >= NOW() AND deleted_at IS NULL ORDER BY start_date ASC LIMIT ?";
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
     * Check if registration is open
     */
    public static function isRegistrationOpen(array $event): bool
    {
        if ($event['status'] !== 'published') return false;
        if ($event['registration_deadline'] && strtotime($event['registration_deadline']) < time()) return false;
        return true;
    }

    /**
     * Get attendee count
     */
    public static function getAttendeeCount(int $eventId): int
    {
        $result = Database::query("SELECT COUNT(*) as count FROM event_registrations WHERE event_id = ? AND status = 'confirmed'", [$eventId]);
        return $result[0]['count'] ?? 0;
    }
}
