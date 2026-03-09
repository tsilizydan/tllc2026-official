<?php
/**
 * TSILIZY LLC — Admin Tickets Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Auth;
use Core\Database;
use Core\Security;
use Core\Session;

class TicketController extends Controller
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
        
        $whereClause = "t.deleted_at IS NULL";
        $params = [];
        
        if ($status) {
            $whereClause .= " AND t.status = ?";
            $params[] = $status;
        }
        
        $tickets = Database::query(
            "SELECT t.*, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.email as user_email
             FROM tickets t
             LEFT JOIN users u ON t.user_id = u.id
             WHERE $whereClause
             ORDER BY t.priority DESC, t.created_at DESC
             LIMIT ? OFFSET ?",
            array_merge($params, [ADMIN_ITEMS_PER_PAGE, $offset])
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM tickets t WHERE $whereClause", $params)[0]['count'];
        
        $stats = [
            'open' => Database::query("SELECT COUNT(*) as c FROM tickets WHERE status = 'open' AND deleted_at IS NULL")[0]['c'],
            'in_progress' => Database::query("SELECT COUNT(*) as c FROM tickets WHERE status = 'in_progress' AND deleted_at IS NULL")[0]['c'],
            'waiting' => Database::query("SELECT COUNT(*) as c FROM tickets WHERE status = 'waiting' AND deleted_at IS NULL")[0]['c'],
            'closed' => Database::query("SELECT COUNT(*) as c FROM tickets WHERE status = 'closed' AND deleted_at IS NULL")[0]['c']
        ];
        
        View::layout('admin', ['page_title' => 'Tickets']);
        $this->view('admin/tickets/index', [
            'page_title' => 'Tickets',
            'tickets' => $tickets,
            'total' => $total,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE),
            'status' => $status,
            'stats' => $stats
        ]);
    }
    
    public function show(string $id): void
    {
        $ticket = Database::query(
            "SELECT t.*, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.email as user_email
             FROM tickets t
             LEFT JOIN users u ON t.user_id = u.id
             WHERE t.id = ? AND t.deleted_at IS NULL",
            [(int)$id]
        )[0] ?? null;
        
        if (!$ticket) { \Core\Router::notFound(); return; }
        
        $replies = Database::query(
            "SELECT tr.*, CONCAT(u.first_name, ' ', u.last_name) as user_name
             FROM ticket_replies tr
             LEFT JOIN users u ON tr.user_id = u.id
             WHERE tr.ticket_id = ?
             ORDER BY tr.created_at ASC",
            [(int)$id]
        );
        
        View::layout('admin', ['page_title' => 'Ticket #' . $ticket['reference']]);
        $this->view('admin/tickets/show', [
            'ticket' => $ticket,
            'replies' => $replies
        ]);
    }
    
    public function reply(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        $ticket = Database::query("SELECT * FROM tickets WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$ticket) { \Core\Router::notFound(); return; }
        
        if (empty(trim($_POST['message'] ?? ''))) {
            Session::flash('error', 'Le message ne peut pas être vide.');
            $this->back();
            return;
        }
        
        Database::execute(
            "INSERT INTO ticket_replies (ticket_id, user_id, message, created_at) VALUES (?, ?, ?, NOW())",
            [(int)$id, Auth::id(), Security::cleanHtml($_POST['message'])]
        );
        
        // Update status if specified
        if ($this->input('status')) {
            Database::execute("UPDATE tickets SET status = ?, updated_at = NOW() WHERE id = ?", [$this->input('status'), (int)$id]);
        } else {
            Database::execute("UPDATE tickets SET status = 'in_progress', updated_at = NOW() WHERE id = ?", [(int)$id]);
        }
        
        Session::flash('success', 'Réponse envoyée.');
        $this->back();
    }
    
    public function updateStatus(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        $status = $this->input('status');
        $validStatuses = ['open', 'in_progress', 'waiting', 'closed'];
        
        if (!in_array($status, $validStatuses)) {
            Session::flash('error', 'Statut invalide.');
            $this->back();
            return;
        }
        
        Database::execute("UPDATE tickets SET status = ?, updated_at = NOW() WHERE id = ?", [$status, (int)$id]);
        
        Session::flash('success', 'Statut mis à jour.');
        $this->back();
    }
    
    public function assign(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        $assignedTo = (int)$this->input('assigned_to');
        
        Database::execute(
            "UPDATE tickets SET assigned_to = ?, status = 'in_progress', updated_at = NOW() WHERE id = ?",
            [$assignedTo ?: null, (int)$id]
        );
        
        Session::flash('success', 'Ticket assigné.');
        $this->back();
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE tickets SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Ticket supprimé.');
        $this->redirect(SITE_URL . '/admin/tickets');
    }
}
