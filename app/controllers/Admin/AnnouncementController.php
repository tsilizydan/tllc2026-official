<?php
/**
 * TSILIZY LLC — Admin Announcements Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;
use Core\Auth;

class AnnouncementController extends Controller
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
        
        $announcements = Database::query(
            "SELECT a.*, u.first_name, u.last_name FROM announcements a 
             LEFT JOIN users u ON a.created_by = u.id 
             WHERE a.deleted_at IS NULL ORDER BY a.is_pinned DESC, a.created_at DESC 
             LIMIT ? OFFSET ?",
            [ADMIN_ITEMS_PER_PAGE, $offset]
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM announcements WHERE deleted_at IS NULL")[0]['count'];
        
        View::layout('admin', ['page_title' => 'Annonces']);
        $this->view('admin/announcements/index', [
            'announcements' => $announcements,
            'total' => $total,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE)
        ]);
    }
    
    public function create(): void
    {
        View::layout('admin', ['page_title' => 'Nouvelle annonce']);
        $this->view('admin/announcements/form', ['announcement' => null, 'mode' => 'create']);
    }
    
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        $title = $this->input('title');
        $slug = $this->slugify($title) . '-' . time();
        
        Database::insert(
            "INSERT INTO announcements (title, slug, content, excerpt, featured_image, type, status, is_pinned, publish_at, expires_at, created_by, created_at, updated_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $title, $slug, $this->input('content'), $this->input('excerpt'),
                $this->input('featured_image'), $this->input('type') ?: 'info',
                $this->input('status') ?: 'draft', $this->input('is_pinned') ? 1 : 0,
                $this->input('publish_at') ?: null, $this->input('expires_at') ?: null,
                Auth::id()
            ]
        );
        
        Session::flash('success', 'Annonce créée avec succès.');
        $this->redirect(SITE_URL . '/admin/annonces');
    }
    
    public function edit(string $id): void
    {
        $announcement = Database::query("SELECT * FROM announcements WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$announcement) { \Core\Router::notFound(); return; }
        
        View::layout('admin', ['page_title' => 'Modifier l\'annonce']);
        $this->view('admin/announcements/form', ['announcement' => $announcement, 'mode' => 'edit']);
    }
    
    public function update(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        Database::execute(
            "UPDATE announcements SET title = ?, content = ?, excerpt = ?, featured_image = ?, type = ?, status = ?, is_pinned = ?, publish_at = ?, expires_at = ?, updated_at = NOW() WHERE id = ?",
            [
                $this->input('title'), $this->input('content'), $this->input('excerpt'),
                $this->input('featured_image'), $this->input('type') ?: 'info',
                $this->input('status'), $this->input('is_pinned') ? 1 : 0,
                $this->input('publish_at') ?: null, $this->input('expires_at') ?: null,
                (int)$id
            ]
        );
        
        Session::flash('success', 'Annonce modifiée avec succès.');
        $this->redirect(SITE_URL . '/admin/annonces');
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE announcements SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Annonce supprimée.');
        $this->redirect(SITE_URL . '/admin/annonces');
    }
    
    private function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        return strtolower(trim($text, '-'));
    }
}
