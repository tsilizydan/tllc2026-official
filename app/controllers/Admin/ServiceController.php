<?php
/**
 * TSILIZY LLC — Admin Services Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Auth;
use Core\Database;
use Core\Security;
use Core\Session;

class ServiceController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->requirePermission('services.manage');
    }
    
    /**
     * Display services list
     */
    public function index(): void
    {
        $page = (int)($this->query('page') ?? 1);
        $offset = ($page - 1) * ADMIN_ITEMS_PER_PAGE;
        
        $services = Database::query(
            "SELECT * FROM services WHERE deleted_at IS NULL ORDER BY order_index ASC, created_at DESC LIMIT ? OFFSET ?",
            [ADMIN_ITEMS_PER_PAGE, $offset]
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM services WHERE deleted_at IS NULL")[0]['count'];
        
        View::layout('admin', ['page_title' => 'Services']);
        $this->view('admin/services/index', [
            'page_title' => 'Services',
            'services' => $services,
            'total' => $total,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE)
        ]);
    }
    
    /**
     * Show create form
     */
    public function create(): void
    {
        View::layout('admin', ['page_title' => 'Nouveau service']);
        $this->view('admin/services/form', [
            'page_title' => 'Nouveau service',
            'service' => null,
            'mode' => 'create'
        ]);
    }
    
    /**
     * Store new service
     */
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        $errors = $this->validate([
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:20'
        ]);
        
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs.');
            Session::set('validation_errors', $errors);
            Session::flashInput();
            $this->back();
            return;
        }
        
        $slug = Security::slug($this->input('title'));
        
        // Ensure unique slug
        $existing = Database::query("SELECT id FROM services WHERE slug = ? AND deleted_at IS NULL", [$slug]);
        if (!empty($existing)) {
            $slug .= '-' . time();
        }
        
        // Handle image upload
        $image = null;
        if ($file = $this->file('image')) {
            $image = $this->uploadFile($file, 'services');
        }
        
        // Handle features JSON
        $features = null;
        if ($this->input('features')) {
            $features = json_encode(array_filter(explode("\n", $this->input('features'))));
        }
        
        $sql = "INSERT INTO services (title, slug, description, short_description, icon, image, price, duration, features, status, is_featured, order_index, seo_title, seo_description, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";
        
        $serviceId = Database::insert($sql, [
            $this->input('title'),
            $slug,
            Security::cleanHtml($_POST['description']),
            $this->input('short_description'),
            $this->input('icon'),
            $image,
            $this->input('price') ?: null,
            $this->input('duration'),
            $features,
            $this->input('status') ?? 'active',
            isset($_POST['is_featured']) ? 1 : 0,
            (int)$this->input('order_index'),
            $this->input('seo_title'),
            $this->input('seo_description')
        ]);
        
        if ($serviceId) {
            $this->logAction('service.created', 'services', $serviceId);
            Session::flash('success', 'Service créé avec succès.');
            $this->redirect(SITE_URL . '/admin/services');
        } else {
            Session::flash('error', 'Erreur lors de la création.');
            $this->back();
        }
    }
    
    /**
     * Show edit form
     */
    public function edit(string $id): void
    {
        $service = Database::query("SELECT * FROM services WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        
        if (!$service) {
            \Core\Router::notFound();
            return;
        }
        
        View::layout('admin', ['page_title' => 'Modifier le service']);
        $this->view('admin/services/form', [
            'page_title' => 'Modifier le service',
            'service' => $service,
            'mode' => 'edit'
        ]);
    }
    
    /**
     * Update service
     */
    public function update(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        $service = Database::query("SELECT * FROM services WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        
        if (!$service) {
            \Core\Router::notFound();
            return;
        }
        
        $errors = $this->validate([
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:20'
        ]);
        
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs.');
            Session::set('validation_errors', $errors);
            Session::flashInput();
            $this->back();
            return;
        }
        
        // Handle image upload
        $image = $service['image'];
        if ($file = $this->file('image')) {
            $newImage = $this->uploadFile($file, 'services');
            if ($newImage) $image = $newImage;
        }
        
        // Handle features JSON
        $features = null;
        if ($this->input('features')) {
            $features = json_encode(array_filter(explode("\n", $this->input('features'))));
        }
        
        $sql = "UPDATE services SET title = ?, description = ?, short_description = ?, icon = ?, image = ?, price = ?, duration = ?, features = ?, status = ?, is_featured = ?, order_index = ?, seo_title = ?, seo_description = ?, updated_at = NOW() WHERE id = ?";
        
        Database::execute($sql, [
            $this->input('title'),
            Security::cleanHtml($_POST['description']),
            $this->input('short_description'),
            $this->input('icon'),
            $image,
            $this->input('price') ?: null,
            $this->input('duration'),
            $features,
            $this->input('status') ?? 'active',
            isset($_POST['is_featured']) ? 1 : 0,
            (int)$this->input('order_index'),
            $this->input('seo_title'),
            $this->input('seo_description'),
            (int)$id
        ]);
        
        $this->logAction('service.updated', 'services', (int)$id);
        Session::flash('success', 'Service modifié avec succès.');
        $this->redirect(SITE_URL . '/admin/services');
    }
    
    /**
     * Delete service
     */
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        Database::execute("UPDATE services SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        $this->logAction('service.deleted', 'services', (int)$id);
        
        Session::flash('success', 'Service supprimé.');
        $this->redirect(SITE_URL . '/admin/services');
    }
    
    /**
     * Log admin action
     */
    private function logAction(string $action, string $entityType, int $entityId): void
    {
        Database::execute(
            "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, ip_address, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
            [Auth::id(), $action, $entityType, $entityId, $_SERVER['REMOTE_ADDR'] ?? null]
        );
    }
}
