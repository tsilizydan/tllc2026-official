<?php
/**
 * TSILIZY LLC — Contact Model
 */

namespace App\Models;

use Core\Model;

class Contact extends Model
{
    protected static string $table = 'contacts';
    protected static array $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'ip_address'
    ];

    /**
     * Get new contacts
     */
    public static function newMessages(): array
    {
        return self::where('status', 'new', 'created_at', 'DESC');
    }

    /**
     * Get unread count
     */
    public static function unreadCount(): int
    {
        return self::count("status = 'new'");
    }

    /**
     * Mark as read
     */
    public static function markAsRead(int $id): bool
    {
        return self::update($id, ['status' => 'read']);
    }

    /**
     * Mark as replied
     */
    public static function markAsReplied(int $id, int $userId): bool
    {
        $sql = "UPDATE contacts SET status = 'replied', replied_by = ?, replied_at = NOW(), updated_at = NOW() WHERE id = ?";
        return \Core\Database::execute($sql, [$userId, $id]);
    }
}
