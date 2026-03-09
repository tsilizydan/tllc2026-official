<?php
/**
 * TSILIZY LLC — Admin Message Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;
use Core\Auth;

class MessageController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    /**
     * List all messages (inbox)
     */
    public function index(): void
    {
        $page = (int)($this->query('page') ?? 1);
        $filter = $this->query('filter') ?? 'all'; // all, sent, unread
        $offset = ($page - 1) * ADMIN_ITEMS_PER_PAGE;
        
        $whereClause = "1=1";
        $params = [];
        
        if ($filter === 'sent') {
            $whereClause = "m.sender_id = ?";
            $params[] = Auth::id();
        } elseif ($filter === 'unread') {
            $whereClause = "(m.recipient_id = ? OR m.recipient_id IS NULL) AND m.is_read = 0";
            $params[] = Auth::id();
        } else {
            $whereClause = "(m.recipient_id = ? OR m.recipient_id IS NULL OR m.sender_id = ?)";
            $params[] = Auth::id();
            $params[] = Auth::id();
        }
        
        $messages = Database::query(
            "SELECT m.*, 
                    CONCAT(s.first_name, ' ', s.last_name) as sender_name,
                    CONCAT(r.first_name, ' ', r.last_name) as recipient_name,
                    s.email as sender_email
             FROM messages m
             LEFT JOIN users s ON m.sender_id = s.id
             LEFT JOIN users r ON m.recipient_id = r.id
             WHERE $whereClause AND m.parent_id IS NULL
             ORDER BY m.created_at DESC
             LIMIT ? OFFSET ?",
            array_merge($params, [ADMIN_ITEMS_PER_PAGE, $offset])
        );
        
        $countParams = $filter === 'all' ? [Auth::id(), Auth::id()] : ($filter === 'sent' ? [Auth::id()] : [Auth::id()]);
        $total = Database::query("SELECT COUNT(*) as count FROM messages m WHERE $whereClause AND m.parent_id IS NULL", $countParams)[0]['count'];
        
        $unreadCount = Database::query(
            "SELECT COUNT(*) as c FROM messages WHERE (recipient_id = ? OR recipient_id IS NULL) AND is_read = 0",
            [Auth::id()]
        )[0]['c'] ?? 0;
        
        View::layout('admin', ['page_title' => 'Messages']);
        $this->view('admin/messages/index', [
            'messages' => $messages,
            'total' => $total,
            'unread_count' => $unreadCount,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE),
            'filter' => $filter
        ]);
    }
    
    /**
     * Compose new message
     */
    public function compose(): void
    {
        $users = Database::query(
            "SELECT id, first_name, last_name, email FROM users WHERE deleted_at IS NULL AND status = 'active' ORDER BY first_name"
        );
        
        View::layout('admin', ['page_title' => 'Nouveau message']);
        $this->view('admin/messages/compose', ['users' => $users, 'reply_to' => null]);
    }
    
    /**
     * Send message
     */
    public function send(): void
    {
        if (!$this->validateCsrf()) return;
        
        $subject = $this->input('subject');
        $content = $this->input('content');
        $recipientId = $this->input('recipient_id');
        $isBroadcast = $this->input('broadcast') ? 1 : 0;
        $parentId = $this->input('parent_id') ?: null;
        
        if (empty($subject) || empty($content)) {
            Session::flash('error', 'Sujet et contenu requis.');
            $this->back();
            return;
        }
        
        if ($isBroadcast) {
            // Send to all users
            Database::insert(
                "INSERT INTO messages (sender_id, recipient_id, subject, content, is_broadcast, parent_id, created_at) VALUES (?, NULL, ?, ?, 1, ?, NOW())",
                [Auth::id(), $subject, $content, $parentId]
            );
            Session::flash('success', 'Message diffusé à tous les utilisateurs.');
        } else {
            if (empty($recipientId)) {
                Session::flash('error', 'Veuillez sélectionner un destinataire.');
                $this->back();
                return;
            }
            Database::insert(
                "INSERT INTO messages (sender_id, recipient_id, subject, content, is_broadcast, parent_id, created_at) VALUES (?, ?, ?, ?, 0, ?, NOW())",
                [Auth::id(), (int)$recipientId, $subject, $content, $parentId]
            );
            Session::flash('success', 'Message envoyé.');
        }
        
        $this->redirect(SITE_URL . '/admin/messages');
    }
    
    /**
     * View message thread
     */
    public function show(string $id): void
    {
        $message = Database::query(
            "SELECT m.*, 
                    CONCAT(s.first_name, ' ', s.last_name) as sender_name,
                    CONCAT(r.first_name, ' ', r.last_name) as recipient_name,
                    s.email as sender_email
             FROM messages m
             LEFT JOIN users s ON m.sender_id = s.id
             LEFT JOIN users r ON m.recipient_id = r.id
             WHERE m.id = ?",
            [(int)$id]
        )[0] ?? null;
        
        if (!$message) {
            \Core\Router::notFound();
            return;
        }
        
        // Mark as read if recipient
        if ($message['recipient_id'] == Auth::id() || $message['recipient_id'] === null) {
            Database::execute("UPDATE messages SET is_read = 1, read_at = NOW() WHERE id = ? AND is_read = 0", [(int)$id]);
        }
        
        // Get replies
        $replies = Database::query(
            "SELECT m.*, CONCAT(s.first_name, ' ', s.last_name) as sender_name
             FROM messages m
             LEFT JOIN users s ON m.sender_id = s.id
             WHERE m.parent_id = ?
             ORDER BY m.created_at ASC",
            [(int)$id]
        );
        
        View::layout('admin', ['page_title' => 'Message: ' . $message['subject']]);
        $this->view('admin/messages/show', ['message' => $message, 'replies' => $replies]);
    }
    
    /**
     * Reply to message
     */
    public function reply(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        $original = Database::query("SELECT * FROM messages WHERE id = ?", [(int)$id])[0] ?? null;
        if (!$original) {
            Session::flash('error', 'Message introuvable.');
            $this->back();
            return;
        }
        
        $content = $this->input('content');
        $recipientId = $original['sender_id'] == Auth::id() ? $original['recipient_id'] : $original['sender_id'];
        
        Database::insert(
            "INSERT INTO messages (sender_id, recipient_id, subject, content, parent_id, created_at) VALUES (?, ?, ?, ?, ?, NOW())",
            [Auth::id(), $recipientId, 'Re: ' . $original['subject'], $content, (int)$id]
        );
        
        Session::flash('success', 'Réponse envoyée.');
        $this->redirect(SITE_URL . '/admin/messages/' . $id);
    }
    
    /**
     * Delete message
     */
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("DELETE FROM messages WHERE id = ? OR parent_id = ?", [(int)$id, (int)$id]);
        Session::flash('success', 'Message supprimé.');
        $this->redirect(SITE_URL . '/admin/messages');
    }
}
