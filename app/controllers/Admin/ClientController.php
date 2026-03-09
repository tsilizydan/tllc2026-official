<?php
/**
 * TSILIZY LLC — Admin Client Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;

class ClientController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    public function index(): void
    {
        $page = (int)($this->query('page') ?? 1);
        $search = $this->query('search');
        $offset = ($page - 1) * ADMIN_ITEMS_PER_PAGE;
        
        $whereClause = "deleted_at IS NULL";
        $params = [];
        
        if ($search) {
            $whereClause .= " AND (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR company LIKE ?)";
            $searchTerm = "%$search%";
            $params = [$searchTerm, $searchTerm, $searchTerm, $searchTerm];
        }
        
        $clients = Database::query(
            "SELECT *, CONCAT(first_name, ' ', last_name) as full_name FROM clients WHERE $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?",
            array_merge($params, [ADMIN_ITEMS_PER_PAGE, $offset])
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM clients WHERE $whereClause", $params)[0]['count'];
        
        // Stats
        $stats = [
            'total' => Database::query("SELECT COUNT(*) as c FROM clients WHERE deleted_at IS NULL")[0]['c'] ?? 0,
            'active' => Database::query("SELECT COUNT(*) as c FROM clients WHERE deleted_at IS NULL AND status = 'active'")[0]['c'] ?? 0,
            'invoices_total' => Database::query("SELECT SUM(total) as t FROM invoices WHERE deleted_at IS NULL AND status = 'paid'")[0]['t'] ?? 0
        ];
        
        View::layout('admin', ['page_title' => 'Clients']);
        $this->view('admin/clients/index', [
            'clients' => $clients,
            'total' => $total,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE),
            'search' => $search,
            'stats' => $stats
        ]);
    }
    
    public function create(): void
    {
        View::layout('admin', ['page_title' => 'Nouveau client']);
        $this->view('admin/clients/form', ['client' => null, 'mode' => 'create']);
    }
    
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        $errors = $this->validate(['first_name' => 'required', 'email' => 'required|email']);
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez remplir tous les champs requis.');
            $this->back();
            return;
        }
        
        // Check email unique
        $existing = Database::query("SELECT id FROM clients WHERE email = ? AND deleted_at IS NULL", [$this->input('email')]);
        if (!empty($existing)) {
            Session::flash('error', 'Un client avec cet email existe déjà.');
            $this->back();
            return;
        }
        
        Database::insert(
            "INSERT INTO clients (first_name, last_name, email, phone, company, address, city, postal_code, country, vat_number, notes, status, created_at, updated_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $this->input('first_name'), $this->input('last_name'), $this->input('email'),
                $this->input('phone'), $this->input('company'), $this->input('address'),
                $this->input('city'), $this->input('postal_code'), $this->input('country') ?: 'France',
                $this->input('vat_number'), $this->input('notes'), 'active'
            ]
        );
        
        Session::flash('success', 'Client créé avec succès.');
        $this->redirect(SITE_URL . '/admin/clients');
    }
    
    public function show(string $id): void
    {
        $client = Database::query("SELECT * FROM clients WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$client) { \Core\Router::notFound(); return; }
        
        // Get client invoices
        $invoices = Database::query(
            "SELECT * FROM invoices WHERE client_email = ? AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 10",
            [$client['email']]
        );
        
        // Get client contracts
        $contracts = Database::query(
            "SELECT * FROM contracts WHERE user_id = ? AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 5",
            [(int)$id]
        );
        
        View::layout('admin', ['page_title' => $client['first_name'] . ' ' . $client['last_name']]);
        $this->view('admin/clients/show', ['client' => $client, 'invoices' => $invoices, 'contracts' => $contracts]);
    }
    
    public function edit(string $id): void
    {
        $client = Database::query("SELECT * FROM clients WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$client) { \Core\Router::notFound(); return; }
        
        View::layout('admin', ['page_title' => 'Modifier le client']);
        $this->view('admin/clients/form', ['client' => $client, 'mode' => 'edit']);
    }
    
    public function update(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        Database::execute(
            "UPDATE clients SET first_name = ?, last_name = ?, email = ?, phone = ?, company = ?, address = ?, city = ?, postal_code = ?, country = ?, vat_number = ?, notes = ?, status = ?, updated_at = NOW() WHERE id = ?",
            [
                $this->input('first_name'), $this->input('last_name'), $this->input('email'),
                $this->input('phone'), $this->input('company'), $this->input('address'),
                $this->input('city'), $this->input('postal_code'), $this->input('country') ?: 'France',
                $this->input('vat_number'), $this->input('notes'), $this->input('status'),
                (int)$id
            ]
        );
        
        Session::flash('success', 'Client modifié avec succès.');
        $this->redirect(SITE_URL . '/admin/clients');
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE clients SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Client supprimé.');
        $this->redirect(SITE_URL . '/admin/clients');
    }
}
