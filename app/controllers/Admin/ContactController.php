<?php
/**
 * TSILIZY LLC — Admin Contacts Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Auth;
use Core\Database;
use Core\Session;

class ContactController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    /**
     * Display contacts list
     */
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
        
        $contacts = Database::query(
            "SELECT * FROM contacts WHERE $whereClause ORDER BY created_at DESC LIMIT ? OFFSET ?",
            array_merge($params, [ADMIN_ITEMS_PER_PAGE, $offset])
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM contacts WHERE $whereClause", $params)[0]['count'];
        
        View::layout('admin', ['page_title' => 'Messages']);
        $this->view('admin/contacts/index', [
            'page_title' => 'Messages',
            'contacts' => $contacts,
            'total' => $total,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE),
            'status' => $status
        ]);
    }
    
    /**
     * Show contact message
     */
    public function show(string $id): void
    {
        $contact = Database::query("SELECT * FROM contacts WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        
        if (!$contact) {
            \Core\Router::notFound();
            return;
        }
        
        // Mark as read
        if ($contact['status'] === 'new') {
            Database::execute("UPDATE contacts SET status = 'read', updated_at = NOW() WHERE id = ?", [(int)$id]);
            $contact['status'] = 'read';
        }
        
        View::layout('admin', ['page_title' => 'Message de ' . $contact['name']]);
        $this->view('admin/contacts/show', [
            'page_title' => 'Message de ' . $contact['name'],
            'contact' => $contact
        ]);
    }
    
    /**
     * Reply to contact (mark as replied)
     */
    public function reply(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        Database::execute(
            "UPDATE contacts SET status = 'replied', replied_by = ?, replied_at = NOW(), updated_at = NOW() WHERE id = ?",
            [Auth::id(), (int)$id]
        );
        
        // In production, send email here
        
        Session::flash('success', 'Message marqué comme répondu.');
        $this->redirect(SITE_URL . '/admin/contacts');
    }
    
    /**
     * Delete contact
     */
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        Database::execute("UPDATE contacts SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        
        Session::flash('success', 'Message supprimé.');
        $this->redirect(SITE_URL . '/admin/contacts');
    }
    
    /**
     * Bulk action
     */
    public function bulk(): void
    {
        if (!$this->validateCsrf()) return;
        
        $action = $this->input('action');
        $ids = $_POST['ids'] ?? [];
        
        if (empty($ids)) {
            Session::flash('error', 'Aucun élément sélectionné.');
            $this->back();
            return;
        }
        
        $placeholders = implode(',', array_fill(0, count($ids), '?'));
        
        switch ($action) {
            case 'delete':
                Database::execute("UPDATE contacts SET deleted_at = NOW() WHERE id IN ($placeholders)", $ids);
                Session::flash('success', count($ids) . ' message(s) supprimé(s).');
                break;
            case 'mark_read':
                Database::execute("UPDATE contacts SET status = 'read', updated_at = NOW() WHERE id IN ($placeholders)", $ids);
                Session::flash('success', count($ids) . ' message(s) marqué(s) comme lu(s).');
                break;
        }
        
        $this->redirect(SITE_URL . '/admin/contacts');
    }
}
