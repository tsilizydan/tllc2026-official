<?php
/**
 * TSILIZY LLC — Admin Contract Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;
use Core\PDF;

class ContractController extends Controller
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
        
        $contracts = Database::query(
            "SELECT c.*, u.name as client_name FROM contracts c 
             LEFT JOIN users u ON c.user_id = u.id 
             WHERE c.deleted_at IS NULL ORDER BY c.created_at DESC LIMIT ? OFFSET ?",
            [ADMIN_ITEMS_PER_PAGE, $offset]
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM contracts WHERE deleted_at IS NULL")[0]['count'];
        
        View::layout('admin', ['page_title' => 'Contrats']);
        $this->view('admin/contracts/index', [
            'contracts' => $contracts,
            'total' => $total,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE)
        ]);
    }
    
    public function create(): void
    {
        $users = Database::query("SELECT id, name, email FROM users WHERE deleted_at IS NULL ORDER BY name");
        View::layout('admin', ['page_title' => 'Nouveau contrat']);
        $this->view('admin/contracts/form', ['contract' => null, 'users' => $users, 'mode' => 'create']);
    }
    
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        $ref = 'CTR-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        
        Database::insert(
            "INSERT INTO contracts (reference, user_id, title, description, value, start_date, end_date, status, created_at, updated_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $ref, $this->input('user_id') ?: null, $this->input('title'),
                $_POST['description'] ?? '', $this->input('value') ?: null,
                $this->input('start_date') ?: null, $this->input('end_date') ?: null,
                $this->input('status') ?? 'draft'
            ]
        );
        
        Session::flash('success', 'Contrat créé.');
        $this->redirect(SITE_URL . '/admin/contrats');
    }
    
    public function edit(string $id): void
    {
        $contract = Database::query("SELECT * FROM contracts WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$contract) { \Core\Router::notFound(); return; }
        
        $users = Database::query("SELECT id, name, email FROM users WHERE deleted_at IS NULL ORDER BY name");
        View::layout('admin', ['page_title' => 'Modifier le contrat']);
        $this->view('admin/contracts/form', ['contract' => $contract, 'users' => $users, 'mode' => 'edit']);
    }
    
    public function update(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        Database::execute(
            "UPDATE contracts SET user_id = ?, title = ?, description = ?, value = ?, start_date = ?, end_date = ?, status = ?, updated_at = NOW() WHERE id = ?",
            [
                $this->input('user_id') ?: null, $this->input('title'),
                $_POST['description'] ?? '', $this->input('value') ?: null,
                $this->input('start_date') ?: null, $this->input('end_date') ?: null,
                $this->input('status'), (int)$id
            ]
        );
        
        Session::flash('success', 'Contrat modifié.');
        $this->redirect(SITE_URL . '/admin/contrats');
    }
    
    public function pdf(string $id): void
    {
        $contract = Database::query(
            "SELECT c.*, u.name as user_name, u.email as user_email FROM contracts c 
             LEFT JOIN users u ON c.user_id = u.id WHERE c.id = ?",
            [(int)$id]
        )[0] ?? null;
        
        if (!$contract) { \Core\Router::notFound(); return; }
        
        PDF::contract($contract)->inline('Contrat-' . $contract['reference'] . '.pdf');
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE contracts SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Contrat supprimé.');
        $this->redirect(SITE_URL . '/admin/contrats');
    }
}
