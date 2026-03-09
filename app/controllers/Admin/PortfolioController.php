<?php
/**
 * TSILIZY LLC — Admin Portfolio Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Security;
use Core\Session;

class PortfolioController extends Controller
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
        
        $items = Database::query(
            "SELECT * FROM portfolio WHERE deleted_at IS NULL ORDER BY order_index ASC, created_at DESC LIMIT ? OFFSET ?",
            [ADMIN_ITEMS_PER_PAGE, $offset]
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM portfolio WHERE deleted_at IS NULL")[0]['count'];
        
        View::layout('admin', ['page_title' => 'Portfolio']);
        $this->view('admin/portfolio/index', [
            'page_title' => 'Portfolio',
            'items' => $items,
            'total' => $total,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE)
        ]);
    }
    
    public function create(): void
    {
        View::layout('admin', ['page_title' => 'Nouveau projet']);
        $this->view('admin/portfolio/form', ['item' => null, 'mode' => 'create']);
    }
    
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        $errors = $this->validate(['title' => 'required|min:3|max:255', 'description' => 'required|min:20']);
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs.');
            Session::set('validation_errors', $errors);
            Session::flashInput();
            $this->back();
            return;
        }
        
        $slug = Security::slug($this->input('title'));
        $existing = Database::query("SELECT id FROM portfolio WHERE slug = ? AND deleted_at IS NULL", [$slug]);
        if (!empty($existing)) $slug .= '-' . time();
        
        $image = null;
        if ($file = $this->file('image')) $image = $this->uploadFile($file, 'portfolio');
        
        Database::insert(
            "INSERT INTO portfolio (title, slug, description, short_description, client_name, project_url, image, category, tags, status, is_featured, completed_at, order_index, seo_title, seo_description, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $this->input('title'), $slug, Security::cleanHtml($_POST['description']), $this->input('short_description'),
                $this->input('client_name'), $this->input('project_url'), $image, $this->input('category'),
                $this->input('tags'), $this->input('status') ?? 'active', isset($_POST['is_featured']) ? 1 : 0,
                $this->input('completed_at') ?: null, (int)$this->input('order_index'),
                $this->input('seo_title'), $this->input('seo_description')
            ]
        );
        
        Session::flash('success', 'Projet créé avec succès.');
        $this->redirect(SITE_URL . '/admin/portfolio');
    }
    
    public function edit(string $id): void
    {
        $item = Database::query("SELECT * FROM portfolio WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$item) { \Core\Router::notFound(); return; }
        
        View::layout('admin', ['page_title' => 'Modifier le projet']);
        $this->view('admin/portfolio/form', ['item' => $item, 'mode' => 'edit']);
    }
    
    public function update(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        $item = Database::query("SELECT * FROM portfolio WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$item) { \Core\Router::notFound(); return; }
        
        $image = $item['image'];
        if ($file = $this->file('image')) {
            $newImage = $this->uploadFile($file, 'portfolio');
            if ($newImage) $image = $newImage;
        }
        
        Database::execute(
            "UPDATE portfolio SET title = ?, description = ?, short_description = ?, client_name = ?, project_url = ?, image = ?, category = ?, tags = ?, status = ?, is_featured = ?, completed_at = ?, order_index = ?, seo_title = ?, seo_description = ?, updated_at = NOW() WHERE id = ?",
            [
                $this->input('title'), Security::cleanHtml($_POST['description']), $this->input('short_description'),
                $this->input('client_name'), $this->input('project_url'), $image, $this->input('category'),
                $this->input('tags'), $this->input('status') ?? 'active', isset($_POST['is_featured']) ? 1 : 0,
                $this->input('completed_at') ?: null, (int)$this->input('order_index'),
                $this->input('seo_title'), $this->input('seo_description'), (int)$id
            ]
        );
        
        Session::flash('success', 'Projet modifié avec succès.');
        $this->redirect(SITE_URL . '/admin/portfolio');
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE portfolio SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Projet supprimé.');
        $this->redirect(SITE_URL . '/admin/portfolio');
    }
}
