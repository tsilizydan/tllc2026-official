<?php
/**
 * TSILIZY LLC — User Dashboard Controller
 */

namespace App\Controllers;

use Core\Controller;
use Core\Auth;
use Core\Database;
use Core\Session;
use Core\Security;

class DashboardController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }

    /**
     * Show user dashboard
     */
    public function index(): void
    {
        $userId = Auth::id();
        
        // Get user's tickets
        $tickets = Database::query(
            "SELECT * FROM tickets WHERE user_id = ? AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 5",
            [$userId]
        );
        
        // Get user's contracts
        $contracts = Database::query(
            "SELECT * FROM contracts WHERE user_id = ? AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 5",
            [$userId]
        );
        
        // Get user's invoices
        $invoices = Database::query(
            "SELECT * FROM invoices WHERE user_id = ? AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 5",
            [$userId]
        );
        
        $this->view('dashboard/index', [
            'page_title' => 'Mon Compte',
            'tickets' => $tickets,
            'contracts' => $contracts,
            'invoices' => $invoices
        ]);
    }

    /**
     * Show profile page
     */
    public function profile(): void
    {
        $this->view('dashboard/profile', [
            'page_title' => 'Mon Profil'
        ]);
    }

    /**
     * Update profile
     */
    public function updateProfile(): void
    {
        if (!$this->validateCsrf()) return;
        
        $errors = $this->validate([
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'email' => 'required|email'
        ]);
        
        $userId = Auth::id();
        
        // Check email uniqueness
        $existing = Database::query(
            "SELECT id FROM users WHERE email = ? AND id != ? AND deleted_at IS NULL",
            [$this->input('email'), $userId]
        );
        
        if (!empty($existing)) {
            $errors['email'] = 'Cette adresse email est déjà utilisée.';
        }
        
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs.');
            Session::set('validation_errors', $errors);
            $this->back();
            return;
        }
        
        Database::execute(
            "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, updated_at = NOW() WHERE id = ?",
            [
                $this->input('first_name'),
                $this->input('last_name'),
                $this->input('email'),
                $this->input('phone'),
                $userId
            ]
        );
        
        Session::flash('success', 'Profil mis à jour avec succès.');
        $this->redirect(SITE_URL . '/mon-compte/profil');
    }

    /**
     * Change password
     */
    public function changePassword(): void
    {
        if (!$this->validateCsrf()) return;
        
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Verify current password
        $user = Auth::user();
        if (!password_verify($currentPassword, $user['password'])) {
            Session::flash('error', 'Le mot de passe actuel est incorrect.');
            $this->back();
            return;
        }
        
        // Validate new password
        if (strlen($newPassword) < 8) {
            Session::flash('error', 'Le nouveau mot de passe doit contenir au moins 8 caractères.');
            $this->back();
            return;
        }
        
        if ($newPassword !== $confirmPassword) {
            Session::flash('error', 'Les mots de passe ne correspondent pas.');
            $this->back();
            return;
        }
        
        Database::execute(
            "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?",
            [Security::hashPassword($newPassword), Auth::id()]
        );
        
        Session::flash('success', 'Mot de passe modifié avec succès.');
        $this->redirect(SITE_URL . '/mon-compte/profil');
    }

    /**
     * User's tickets
     */
    public function tickets(): void
    {
        $tickets = Database::query(
            "SELECT * FROM tickets WHERE user_id = ? AND deleted_at IS NULL ORDER BY created_at DESC",
            [Auth::id()]
        );
        
        $this->view('dashboard/tickets', [
            'page_title' => 'Mes Tickets',
            'tickets' => $tickets
        ]);
    }

    /**
     * Create ticket
     */
    public function createTicket(): void
    {
        $this->view('dashboard/ticket-create', [
            'page_title' => 'Nouveau Ticket'
        ]);
    }

    /**
     * Store ticket
     */
    public function storeTicket(): void
    {
        if (!$this->validateCsrf()) return;
        
        $errors = $this->validate([
            'subject' => 'required|min:5|max:255',
            'message' => 'required|min:20'
        ]);
        
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs.');
            Session::set('validation_errors', $errors);
            Session::flashInput();
            $this->back();
            return;
        }
        
        $reference = 'TKT-' . strtoupper(bin2hex(random_bytes(4)));
        
        Database::execute(
            "INSERT INTO tickets (user_id, reference, subject, message, category, priority, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 'medium', 'open', NOW(), NOW())",
            [
                Auth::id(),
                $reference,
                $this->input('subject'),
                Security::cleanHtml($_POST['message']),
                $this->input('category') ?? 'general'
            ]
        );
        
        Session::flash('success', "Ticket créé avec succès. Référence: $reference");
        $this->redirect(SITE_URL . '/mon-compte/tickets');
    }

    /**
     * View ticket
     */
    public function showTicket(string $id): void
    {
        $ticket = Database::query(
            "SELECT * FROM tickets WHERE id = ? AND user_id = ? AND deleted_at IS NULL",
            [(int)$id, Auth::id()]
        )[0] ?? null;
        
        if (!$ticket) {
            \Core\Router::notFound();
            return;
        }
        
        $replies = Database::query(
            "SELECT tr.*, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.id = ? as is_current_user
             FROM ticket_replies tr
             LEFT JOIN users u ON tr.user_id = u.id
             WHERE tr.ticket_id = ?
             ORDER BY tr.created_at ASC",
            [Auth::id(), (int)$id]
        );
        
        $this->view('dashboard/ticket-show', [
            'page_title' => 'Ticket #' . $ticket['reference'],
            'ticket' => $ticket,
            'replies' => $replies
        ]);
    }

    /**
     * Reply to ticket
     */
    public function replyTicket(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        $ticket = Database::query(
            "SELECT * FROM tickets WHERE id = ? AND user_id = ? AND deleted_at IS NULL",
            [(int)$id, Auth::id()]
        )[0] ?? null;
        
        if (!$ticket) {
            \Core\Router::notFound();
            return;
        }
        
        if (empty(trim($_POST['message'] ?? ''))) {
            Session::flash('error', 'Le message ne peut pas être vide.');
            $this->back();
            return;
        }
        
        Database::execute(
            "INSERT INTO ticket_replies (ticket_id, user_id, message, created_at) VALUES (?, ?, ?, NOW())",
            [(int)$id, Auth::id(), Security::cleanHtml($_POST['message'])]
        );
        
        // Reopen if closed
        if ($ticket['status'] === 'closed') {
            Database::execute("UPDATE tickets SET status = 'open', updated_at = NOW() WHERE id = ?", [(int)$id]);
        }
        
        Session::flash('success', 'Réponse envoyée.');
        $this->back();
    }

    /**
     * User's contracts
     */
    public function contracts(): void
    {
        $contracts = Database::query(
            "SELECT * FROM contracts WHERE user_id = ? AND deleted_at IS NULL ORDER BY created_at DESC",
            [Auth::id()]
        );
        
        $this->view('dashboard/contracts', [
            'page_title' => 'Mes Contrats',
            'contracts' => $contracts
        ]);
    }

    /**
     * User's invoices
     */
    public function invoices(): void
    {
        $invoices = Database::query(
            "SELECT * FROM invoices WHERE user_id = ? AND deleted_at IS NULL ORDER BY created_at DESC",
            [Auth::id()]
        );
        
        $this->view('dashboard/invoices', [
            'page_title' => 'Mes Factures',
            'invoices' => $invoices
        ]);
    }
}
