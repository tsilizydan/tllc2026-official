<?php
/**
 * TSILIZY LLC — Ticket Model
 */

namespace App\Models;

use Core\Model;
use Core\Database;

class Ticket extends Model
{
    protected static string $table = 'tickets';
    protected static array $fillable = [
        'user_id',
        'reference',
        'subject',
        'message',
        'category',
        'priority',
        'status',
        'assigned_to'
    ];

    /**
     * Get user's tickets
     */
    public static function forUser(int $userId): array
    {
        $sql = "SELECT * FROM tickets WHERE user_id = ? AND deleted_at IS NULL ORDER BY created_at DESC";
        return Database::query($sql, [$userId]);
    }

    /**
     * Get open tickets
     */
    public static function open(): array
    {
        $sql = "SELECT t.*, CONCAT(u.first_name, ' ', u.last_name) as user_name 
                FROM tickets t 
                LEFT JOIN users u ON t.user_id = u.id 
                WHERE t.status IN ('open', 'in_progress') AND t.deleted_at IS NULL 
                ORDER BY t.priority DESC, t.created_at ASC";
        return Database::query($sql);
    }

    /**
     * Get ticket with replies
     */
    public static function withReplies(int $ticketId): array
    {
        $sql = "SELECT tr.*, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.id as user_id
                FROM ticket_replies tr 
                LEFT JOIN users u ON tr.user_id = u.id 
                WHERE tr.ticket_id = ? 
                ORDER BY tr.created_at ASC";
        return Database::query($sql, [$ticketId]);
    }

    /**
     * Add reply
     */
    public static function addReply(int $ticketId, int $userId, string $message): int
    {
        $sql = "INSERT INTO ticket_replies (ticket_id, user_id, message, created_at) VALUES (?, ?, ?, NOW())";
        return Database::insert($sql, [$ticketId, $userId, $message]);
    }

    /**
     * Update status
     */
    public static function updateStatus(int $ticketId, string $status): bool
    {
        return Database::execute("UPDATE tickets SET status = ?, updated_at = NOW() WHERE id = ?", [$status, $ticketId]);
    }

    /**
     * Generate reference
     */
    public static function generateReference(): string
    {
        return 'TKT-' . strtoupper(bin2hex(random_bytes(4)));
    }

    /**
     * Get stats
     */
    public static function getStats(): array
    {
        $stats = [];
        foreach (['open', 'in_progress', 'waiting', 'closed'] as $status) {
            $result = Database::query("SELECT COUNT(*) as count FROM tickets WHERE status = ? AND deleted_at IS NULL", [$status]);
            $stats[$status] = $result[0]['count'] ?? 0;
        }
        return $stats;
    }
}
