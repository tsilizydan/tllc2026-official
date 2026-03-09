<?php
/**
 * TSILIZY LLC — Invoice Model
 */

namespace App\Models;

use Core\Model;
use Core\Database;

class Invoice extends Model
{
    protected static string $table = 'invoices';
    protected static array $fillable = [
        'user_id',
        'reference',
        'subtotal',
        'tax',
        'discount',
        'total',
        'status',
        'due_date',
        'paid_at',
        'notes'
    ];

    /**
     * Get user's invoices
     */
    public static function forUser(int $userId): array
    {
        return Database::query(
            "SELECT * FROM invoices WHERE user_id = ? AND deleted_at IS NULL ORDER BY created_at DESC",
            [$userId]
        );
    }

    /**
     * Get pending invoices
     */
    public static function pending(): array
    {
        return Database::query(
            "SELECT i.*, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.email as user_email 
             FROM invoices i 
             LEFT JOIN users u ON i.user_id = u.id 
             WHERE i.status IN ('pending', 'sent') AND i.deleted_at IS NULL 
             ORDER BY i.due_date ASC"
        );
    }

    /**
     * Get overdue invoices
     */
    public static function overdue(): array
    {
        return Database::query(
            "SELECT i.*, CONCAT(u.first_name, ' ', u.last_name) as user_name 
             FROM invoices i 
             LEFT JOIN users u ON i.user_id = u.id 
             WHERE i.status IN ('pending', 'sent') AND i.due_date < NOW() AND i.deleted_at IS NULL"
        );
    }

    /**
     * Generate reference
     */
    public static function generateReference(): string
    {
        $year = date('Y');
        $count = Database::query("SELECT COUNT(*) as count FROM invoices WHERE YEAR(created_at) = ?", [$year])[0]['count'] ?? 0;
        return 'FAC-' . $year . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Mark as paid
     */
    public static function markPaid(int $invoiceId): bool
    {
        return Database::execute(
            "UPDATE invoices SET status = 'paid', paid_at = NOW(), updated_at = NOW() WHERE id = ?",
            [$invoiceId]
        );
    }

    /**
     * Get stats
     */
    public static function getStats(): array
    {
        $pending = Database::query("SELECT SUM(total) as sum FROM invoices WHERE status IN ('pending', 'sent') AND deleted_at IS NULL")[0]['sum'] ?? 0;
        $paid = Database::query("SELECT SUM(total) as sum FROM invoices WHERE status = 'paid' AND YEAR(paid_at) = YEAR(NOW()) AND deleted_at IS NULL")[0]['sum'] ?? 0;
        $overdue = Database::query("SELECT SUM(total) as sum FROM invoices WHERE status IN ('pending', 'sent') AND due_date < NOW() AND deleted_at IS NULL")[0]['sum'] ?? 0;
        
        return compact('pending', 'paid', 'overdue');
    }
}
