<?php
/**
 * TSILIZY LLC — Admin Notification Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;
use Core\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    /**
     * List all notifications
     */
    public function index(): void
    {
        $page = (int)($this->query('page') ?? 1);
        $offset = ($page - 1) * ADMIN_ITEMS_PER_PAGE;
        
        $notifications = Database::query(
            "SELECT n.*, CONCAT(u.first_name, ' ', u.last_name) as user_name
             FROM notifications n
             LEFT JOIN users u ON n.user_id = u.id
             ORDER BY n.created_at DESC
             LIMIT ? OFFSET ?",
            [ADMIN_ITEMS_PER_PAGE, $offset]
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM notifications")[0]['count'];
        $unread = Database::query("SELECT COUNT(*) as count FROM notifications WHERE is_read = 0")[0]['count'];
        
        View::layout('admin', ['page_title' => 'Notifications']);
        $this->view('admin/notifications/index', [
            'notifications' => $notifications,
            'total' => $total,
            'unread' => $unread,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE)
        ]);
    }
    
    /**
     * Get notifications for current user (AJAX)
     */
    public function fetch(): void
    {
        $notifications = Database::query(
            "SELECT * FROM notifications 
             WHERE user_id = ? OR user_id IS NULL 
             ORDER BY created_at DESC LIMIT 10",
            [Auth::id()]
        );
        
        $unreadCount = Database::query(
            "SELECT COUNT(*) as count FROM notifications 
             WHERE (user_id = ? OR user_id IS NULL) AND is_read = 0",
            [Auth::id()]
        )[0]['count'] ?? 0;
        
        header('Content-Type: application/json');
        echo json_encode([
            'notifications' => $notifications,
            'unread_count' => (int)$unreadCount
        ]);
        exit;
    }
    
    /**
     * Mark notification as read
     */
    public function markRead(string $id): void
    {
        Database::execute("UPDATE notifications SET is_read = 1 WHERE id = ?", [(int)$id]);
        
        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
        
        $this->back();
    }
    
    /**
     * Mark all as read for current user
     */
    public function markAllRead(): void
    {
        Database::execute(
            "UPDATE notifications SET is_read = 1 WHERE user_id = ? OR user_id IS NULL",
            [Auth::id()]
        );
        
        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
        
        Session::flash('success', 'Toutes les notifications ont été marquées comme lues.');
        $this->back();
    }
    
    /**
     * Create a new notification (admin only)
     */
    public function create(): void
    {
        View::layout('admin', ['page_title' => 'Nouvelle notification']);
        $users = Database::query("SELECT id, first_name, last_name, email FROM users WHERE deleted_at IS NULL ORDER BY first_name");
        $this->view('admin/notifications/form', ['notification' => null, 'users' => $users]);
    }
    
    /**
     * Store new notification
     */
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        $userId = $this->input('user_id') ?: null; // null = broadcast to all
        $title = $this->input('title');
        $message = $this->input('message');
        $type = $this->input('type') ?? 'info';
        $link = $this->input('link');
        
        Database::insert(
            "INSERT INTO notifications (user_id, type, title, message, link, is_read, created_at) VALUES (?, ?, ?, ?, ?, 0, NOW())",
            [$userId, $type, $title, $message, $link]
        );
        
        Session::flash('success', 'Notification envoyée.');
        $this->redirect(SITE_URL . '/admin/notifications');
    }
    
    /**
     * Delete notification
     */
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("DELETE FROM notifications WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Notification supprimée.');
        $this->redirect(SITE_URL . '/admin/notifications');
    }
    
    /**
     * Clear old notifications
     */
    public function clearOld(): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("DELETE FROM notifications WHERE is_read = 1 AND created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        Session::flash('success', 'Anciennes notifications supprimées.');
        $this->redirect(SITE_URL . '/admin/notifications');
    }
    
    /**
     * Helper: Create notification programmatically
     */
    public static function notify(?int $userId, string $title, string $message = '', string $type = 'info', ?string $link = null): int
    {
        return Database::insert(
            "INSERT INTO notifications (user_id, type, title, message, link, is_read, created_at) VALUES (?, ?, ?, ?, ?, 0, NOW())",
            [$userId, $type, $title, $message, $link]
        );
    }
    
    private function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}
