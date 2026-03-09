<?php
/**
 * TSILIZY LLC — Admin Partners Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Security;
use Core\Session;

class PartnerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    public function index(): void
    {
        $partners = Database::query("SELECT * FROM partners WHERE deleted_at IS NULL ORDER BY order_index ASC, created_at DESC");
        
        View::layout('admin', ['page_title' => 'Partenaires']);
        $this->view('admin/partners/index', ['page_title' => 'Partenaires', 'partners' => $partners]);
    }
    
    public function create(): void
    {
        View::layout('admin', ['page_title' => 'Nouveau partenaire']);
        $this->view('admin/partners/form', ['partner' => null, 'mode' => 'create']);
    }
    
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        $errors = $this->validate(['name' => 'required|min:2|max:255']);
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs.');
            Session::set('validation_errors', $errors);
            Session::flashInput();
            $this->back();
            return;
        }
        
        $slug = Security::slug($this->input('name'));
        $logo = null;
        if ($file = $this->file('logo')) $logo = $this->uploadFile($file, 'partners');
        
        Database::insert(
            "INSERT INTO partners (name, slug, description, logo, website, category, status, is_featured, order_index, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $this->input('name'), $slug, $this->input('description'), $logo, $this->input('website'),
                $this->input('category'), $this->input('status') ?? 'active',
                isset($_POST['is_featured']) ? 1 : 0, (int)$this->input('order_index')
            ]
        );
        
        Session::flash('success', 'Partenaire créé avec succès.');
        $this->redirect(SITE_URL . '/admin/partenaires');
    }
    
    public function edit(string $id): void
    {
        $partner = Database::query("SELECT * FROM partners WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$partner) { \Core\Router::notFound(); return; }
        
        View::layout('admin', ['page_title' => 'Modifier le partenaire']);
        $this->view('admin/partners/form', ['partner' => $partner, 'mode' => 'edit']);
    }
    
    public function update(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        $partner = Database::query("SELECT * FROM partners WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$partner) { \Core\Router::notFound(); return; }
        
        $logo = $partner['logo'];
        if ($file = $this->file('logo')) {
            $newLogo = $this->uploadFile($file, 'partners');
            if ($newLogo) $logo = $newLogo;
        }
        
        Database::execute(
            "UPDATE partners SET name = ?, description = ?, logo = ?, website = ?, category = ?, status = ?, is_featured = ?, order_index = ?, updated_at = NOW() WHERE id = ?",
            [
                $this->input('name'), $this->input('description'), $logo, $this->input('website'),
                $this->input('category'), $this->input('status') ?? 'active',
                isset($_POST['is_featured']) ? 1 : 0, (int)$this->input('order_index'), (int)$id
            ]
        );
        
        Session::flash('success', 'Partenaire modifié avec succès.');
        $this->redirect(SITE_URL . '/admin/partenaires');
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE partners SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Partenaire supprimé.');
        $this->redirect(SITE_URL . '/admin/partenaires');
    }
}
