<?php
/**
 * TSILIZY LLC — Admin Page Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;
use Core\Auth;

class PageController extends Controller
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
        
        $pages = Database::query(
            "SELECT p.*, u.first_name, u.last_name FROM pages p 
             LEFT JOIN users u ON p.created_by = u.id 
             WHERE p.deleted_at IS NULL ORDER BY p.order_index ASC, p.created_at DESC 
             LIMIT ? OFFSET ?",
            [ADMIN_ITEMS_PER_PAGE, $offset]
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM pages WHERE deleted_at IS NULL")[0]['count'];
        
        View::layout('admin', ['page_title' => 'Pages']);
        $this->view('admin/pages/index', [
            'pages' => $pages,
            'total' => $total,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE)
        ]);
    }
    
    public function create(): void
    {
        View::layout('admin', ['page_title' => 'Nouvelle page']);
        $this->view('admin/pages/form', ['page' => null, 'mode' => 'create']);
    }
    
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        $title = $this->input('title');
        $slug = $this->input('slug') ?: $this->slugify($title);
        
        Database::insert(
            "INSERT INTO pages (title, slug, content, excerpt, featured_image, status, seo_title, seo_description, template, order_index, created_by, created_at, updated_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $title, $slug, $this->input('content'), $this->input('excerpt'),
                $this->input('featured_image'), $this->input('status') ?: 'draft',
                $this->input('seo_title'), $this->input('seo_description'),
                $this->input('template') ?: 'default', (int)$this->input('order_index'),
                Auth::id()
            ]
        );
        
        Session::flash('success', 'Page créée avec succès.');
        $this->redirect(SITE_URL . '/admin/pages');
    }
    
    public function edit(string $id): void
    {
        $page = Database::query("SELECT * FROM pages WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$page) { \Core\Router::notFound(); return; }
        
        View::layout('admin', ['page_title' => 'Modifier la page']);
        $this->view('admin/pages/form', ['page' => $page, 'mode' => 'edit']);
    }
    
    public function update(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        Database::execute(
            "UPDATE pages SET title = ?, slug = ?, content = ?, excerpt = ?, featured_image = ?, status = ?, seo_title = ?, seo_description = ?, template = ?, order_index = ?, updated_at = NOW() WHERE id = ?",
            [
                $this->input('title'), $this->input('slug'), $this->input('content'),
                $this->input('excerpt'), $this->input('featured_image'),
                $this->input('status'), $this->input('seo_title'), $this->input('seo_description'),
                $this->input('template') ?: 'default', (int)$this->input('order_index'),
                (int)$id
            ]
        );
        
        Session::flash('success', 'Page modifiée avec succès.');
        $this->redirect(SITE_URL . '/admin/pages');
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE pages SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Page supprimée.');
        $this->redirect(SITE_URL . '/admin/pages');
    }
    
    private function slugify(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        return strtolower(trim($text, '-'));
    }
}
