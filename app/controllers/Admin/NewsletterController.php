<?php
/**
 * TSILIZY LLC — Admin Newsletter Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;

class NewsletterController extends Controller
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
        
        $subscribers = Database::query(
            "SELECT * FROM newsletter_subscribers ORDER BY created_at DESC LIMIT ? OFFSET ?",
            [ADMIN_ITEMS_PER_PAGE, $offset]
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM newsletter_subscribers")[0]['count'];
        $activeCount = Database::query("SELECT COUNT(*) as count FROM newsletter_subscribers WHERE status = 'subscribed'")[0]['count'];
        
        View::layout('admin', ['page_title' => 'Newsletter']);
        $this->view('admin/newsletter/index', [
            'subscribers' => $subscribers,
            'total' => $total,
            'active_count' => $activeCount,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE)
        ]);
    }
    
    public function export(): void
    {
        $subscribers = Database::query(
            "SELECT email, status, created_at FROM newsletter_subscribers WHERE status = 'subscribed' ORDER BY created_at DESC"
        );
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="newsletter_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Email', 'Statut', 'Date inscription']);
        
        foreach ($subscribers as $sub) {
            fputcsv($output, [$sub['email'], $sub['status'], $sub['created_at']]);
        }
        
        fclose($output);
        exit;
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("DELETE FROM newsletter_subscribers WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Abonné supprimé.');
        $this->redirect(SITE_URL . '/admin/newsletter');
    }
}
