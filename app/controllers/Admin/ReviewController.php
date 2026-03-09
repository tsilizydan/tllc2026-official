<?php
/**
 * TSILIZY LLC — Admin Reviews Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;

class ReviewController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    public function index(): void
    {
        $page = (int)($this->query('page') ?? 1);
        $status = $this->query('status');
        $offset = ($page - 1) * ADMIN_ITEMS_PER_PAGE;
        
        $whereClause = "deleted_at IS NULL";
        $params = [];
        
        if ($status) {
            $whereClause .= " AND status = ?";
            $params[] = $status;
        }
        
        $reviews = Database::query(
            "SELECT * FROM reviews WHERE $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?",
            array_merge($params, [ADMIN_ITEMS_PER_PAGE, $offset])
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM reviews WHERE $whereClause", $params)[0]['count'];
        
        $stats = [
            'pending' => Database::query("SELECT COUNT(*) as c FROM reviews WHERE status = 'pending' AND deleted_at IS NULL")[0]['c'],
            'approved' => Database::query("SELECT COUNT(*) as c FROM reviews WHERE status = 'approved' AND deleted_at IS NULL")[0]['c'],
            'rejected' => Database::query("SELECT COUNT(*) as c FROM reviews WHERE status = 'rejected' AND deleted_at IS NULL")[0]['c']
        ];
        
        View::layout('admin', ['page_title' => 'Avis clients']);
        $this->view('admin/reviews/index', [
            'page_title' => 'Avis clients',
            'reviews' => $reviews,
            'total' => $total,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE),
            'status' => $status,
            'stats' => $stats
        ]);
    }
    
    public function approve(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE reviews SET status = 'approved', updated_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Avis approuvé.');
        $this->back();
    }
    
    public function reject(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE reviews SET status = 'rejected', updated_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Avis rejeté.');
        $this->back();
    }
    
    public function toggleFeatured(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE reviews SET is_featured = NOT is_featured, updated_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Statut mis à jour.');
        $this->back();
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE reviews SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Avis supprimé.');
        $this->redirect(SITE_URL . '/admin/avis');
    }
}
