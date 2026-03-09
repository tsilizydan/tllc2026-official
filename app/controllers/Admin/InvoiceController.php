<?php
/**
 * TSILIZY LLC — Admin Invoices Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Security;
use Core\Session;

class InvoiceController extends Controller
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
        
        $whereClause = "i.deleted_at IS NULL";
        $params = [];
        
        if ($status) {
            $whereClause .= " AND i.status = ?";
            $params[] = $status;
        }
        
        $invoices = Database::query(
            "SELECT i.*, i.client_name as user_name, i.client_email as user_email
             FROM invoices i
             WHERE $whereClause
             ORDER BY i.created_at DESC
             LIMIT ? OFFSET ?",
            array_merge($params, [ADMIN_ITEMS_PER_PAGE, $offset])
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM invoices i WHERE $whereClause", $params)[0]['count'];
        
        $stats = [
            'pending' => Database::query("SELECT SUM(total) as s FROM invoices WHERE status IN ('pending', 'sent') AND deleted_at IS NULL")[0]['s'] ?? 0,
            'paid' => Database::query("SELECT SUM(total) as s FROM invoices WHERE status = 'paid' AND deleted_at IS NULL")[0]['s'] ?? 0,
            'overdue' => Database::query("SELECT COUNT(*) as c FROM invoices WHERE status IN ('pending', 'sent') AND due_date < NOW() AND deleted_at IS NULL")[0]['c'] ?? 0
        ];
        
        View::layout('admin', ['page_title' => 'Factures']);
        $this->view('admin/invoices/index', [
            'page_title' => 'Factures',
            'invoices' => $invoices,
            'total' => $total,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE),
            'status' => $status,
            'stats' => $stats
        ]);
    }
    
    public function create(): void
    {
        // Fetch from clients table, fallback to users if clients table empty
        $clients = Database::query("SELECT id, first_name, last_name, email, company FROM clients WHERE status = 'active' AND deleted_at IS NULL ORDER BY first_name");
        if (empty($clients)) {
            $clients = Database::query("SELECT id, first_name, last_name, email, '' as company FROM users WHERE status = 'active' AND deleted_at IS NULL ORDER BY first_name");
        }
        
        View::layout('admin', ['page_title' => 'Nouvelle facture']);
        $this->view('admin/invoices/form', ['invoice' => null, 'mode' => 'create', 'users' => $clients]);
    }
    
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        $clientName = $this->input('client_name');
        if (empty($clientName)) {
            Session::flash('error', 'Le nom du client est requis.');
            $this->back();
            return;
        }
        
        // Get issue and due dates
        $issueDate = $this->input('issue_date') ?: date('Y-m-d');
        $dueDate = $this->input('due_date') ?: date('Y-m-d', strtotime('+30 days'));
        
        // Generate reference
        $year = date('Y');
        $count = Database::query("SELECT COUNT(*) as count FROM invoices WHERE YEAR(created_at) = ?", [$year])[0]['count'] ?? 0;
        $reference = 'FAC-' . $year . '-' . str_pad($count + 1, 4, '0', STR_PAD_LEFT);
        
        // Process items
        $items = $_POST['items'] ?? [];
        $itemsJson = json_encode(array_values(array_filter($items, fn($i) => !empty($i['description']))));
        if ($itemsJson === '[]') {
            $itemsJson = '[{"description":"Prestation","quantity":1,"unit_price":0}]';
        }
        
        $subtotal = (float)$this->input('subtotal') ?: 0;
        $taxRate = (float)$this->input('tax_rate') ?: 20;
        $taxAmount = $subtotal * ($taxRate / 100);
        $discount = (float)$this->input('discount') ?: 0;
        $total = (float)$this->input('total') ?: ($subtotal + $taxAmount - $discount);
        
        try {
            $invoiceId = Database::insert(
                "INSERT INTO invoices (reference, client_name, client_email, client_address, client_vat, items, subtotal, tax_rate, tax_amount, discount, total, currency, notes, status, issue_date, due_date, created_by, created_at, updated_at) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
                [
                    $reference,
                    $clientName,
                    $this->input('client_email') ?: null,
                    $this->input('client_address') ?: null,
                    $this->input('client_vat') ?: null,
                    $itemsJson,
                    $subtotal,
                    $taxRate,
                    $taxAmount,
                    $discount,
                    $total,
                    'EUR',
                    $this->input('notes') ?: null,
                    $this->input('status') ?: 'draft',
                    $issueDate,
                    $dueDate,
                    \Core\Auth::id()
                ]
            );
            
            Session::flash('success', "Facture $reference créée avec succès.");
        } catch (\Exception $e) {
            Session::flash('error', 'Erreur lors de la création: ' . $e->getMessage());
        }
        
        $this->redirect(SITE_URL . '/admin/factures');
    }
    
    public function show(string $id): void
    {
        $invoice = Database::query(
            "SELECT i.*, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.email as user_email, u.phone as user_phone, u.id as user_id
             FROM invoices i
             LEFT JOIN users u ON i.user_id = u.id
             WHERE i.id = ? AND i.deleted_at IS NULL",
            [(int)$id]
        )[0] ?? null;
        
        if (!$invoice) { \Core\Router::notFound(); return; }
        
        View::layout('admin', ['page_title' => 'Facture ' . $invoice['reference']]);
        $this->view('admin/invoices/show', ['invoice' => $invoice]);
    }
    
    public function markPaid(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE invoices SET status = 'paid', paid_at = NOW(), updated_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Facture marquée comme payée.');
        $this->back();
    }
    
    public function send(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE invoices SET status = 'sent', updated_at = NOW() WHERE id = ?", [(int)$id]);
        // TODO: Send email to client
        Session::flash('success', 'Facture envoyée au client.');
        $this->back();
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE invoices SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Facture supprimée.');
        $this->redirect(SITE_URL . '/admin/factures');
    }
}
