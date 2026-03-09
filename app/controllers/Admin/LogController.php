<?php
/**
 * TSILIZY LLC — Admin Log Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;

class LogController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    public function index(): void
    {
        $page = (int)($this->query('page') ?? 1);
        $offset = ($page - 1) * ADMIN_ITEMS_PER_PAGE;
        
        // Get filter parameters
        $userId = $this->query('user_id');
        $action = $this->query('action');
        $startDate = $this->query('start_date');
        $endDate = $this->query('end_date');
        
        // Build query conditions
        $conditions = ["1=1"];
        $params = [];
        
        if ($userId) {
            $conditions[] = "l.user_id = ?";
            $params[] = (int)$userId;
        }
        
        if ($action) {
            $conditions[] = "l.action = ?";
            $params[] = $action;
        }
        
        if ($startDate) {
            $conditions[] = "DATE(l.created_at) >= ?";
            $params[] = $startDate;
        }
        
        if ($endDate) {
            $conditions[] = "DATE(l.created_at) <= ?";
            $params[] = $endDate;
        }
        
        $whereClause = implode(' AND ', $conditions);
        
        $logs = Database::query(
            "SELECT l.*, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.email as user_email
             FROM activity_logs l
             LEFT JOIN users u ON l.user_id = u.id
             WHERE $whereClause
             ORDER BY l.created_at DESC
             LIMIT ? OFFSET ?",
            array_merge($params, [ADMIN_ITEMS_PER_PAGE, $offset])
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM activity_logs l WHERE $whereClause", $params)[0]['count'];
        
        // Get filter options
        $users = Database::query("SELECT id, first_name, last_name FROM users WHERE deleted_at IS NULL ORDER BY first_name");
        $actions = Database::query("SELECT DISTINCT action FROM activity_logs ORDER BY action");
        
        View::layout('admin', ['page_title' => 'Journaux d\'activité']);
        $this->view('admin/logs/index', [
            'logs' => $logs,
            'total' => $total,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE),
            'users' => $users,
            'actions' => array_column($actions, 'action'),
            'filters' => [
                'user_id' => $userId,
                'action' => $action,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ]);
    }
    
    public function export(): void
    {
        $userId = $this->query('user_id');
        $action = $this->query('action');
        $startDate = $this->query('start_date') ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $this->query('end_date') ?? date('Y-m-d');
        
        $conditions = ["DATE(l.created_at) BETWEEN ? AND ?"];
        $params = [$startDate, $endDate];
        
        if ($userId) {
            $conditions[] = "l.user_id = ?";
            $params[] = (int)$userId;
        }
        if ($action) {
            $conditions[] = "l.action = ?";
            $params[] = $action;
        }
        
        $whereClause = implode(' AND ', $conditions);
        
        $logs = Database::query(
            "SELECT l.*, CONCAT(u.first_name, ' ', u.last_name) as user_name
             FROM activity_logs l
             LEFT JOIN users u ON l.user_id = u.id
             WHERE $whereClause
             ORDER BY l.created_at DESC",
            $params
        );
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="logs_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Date', 'Utilisateur', 'Action', 'Description', 'IP', 'User Agent']);
        
        foreach ($logs as $log) {
            fputcsv($output, [
                $log['created_at'],
                $log['user_name'] ?? 'Système',
                $log['action'],
                $log['description'],
                $log['ip_address'],
                $log['user_agent']
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    public function clear(): void
    {
        if (!$this->validateCsrf()) return;
        
        // Keep last 30 days, delete older
        Database::execute("DELETE FROM activity_logs WHERE created_at < DATE_SUB(NOW(), INTERVAL 30 DAY)");
        
        Session::flash('success', 'Anciens journaux supprimés.');
        $this->redirect(SITE_URL . '/admin/journaux');
    }
    
    /**
     * Static helper to log actions
     */
    public static function log(string $action, string $description = '', ?int $userId = null): void
    {
        $userId = $userId ?? \Core\Auth::id();
        
        Database::insert(
            "INSERT INTO activity_logs (user_id, action, description, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
            [
                $userId,
                $action,
                $description,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null
            ]
        );
    }
}
