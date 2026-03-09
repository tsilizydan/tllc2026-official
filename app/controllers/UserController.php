<?php
/**
 * TSILIZY LLC — User Account Controller
 */

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;
use Core\Auth;
use Core\CSRF;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAuth();
    }
    
    /**
     * User dashboard
     */
    public function dashboard(): void
    {
        $user = Auth::user();
        
        // Get user's recent invoices
        $invoices = Database::query(
            "SELECT * FROM invoices WHERE client_email = ? ORDER BY created_at DESC LIMIT 5",
            [$user['email']]
        );
        
        // Get user's contracts
        $contracts = Database::query(
            "SELECT * FROM contracts WHERE client_id = ? ORDER BY created_at DESC LIMIT 5",
            [$user['id']]
        );
        
        // Get user's notifications
        $notifications = Database::query(
            "SELECT * FROM notifications WHERE (user_id = ? OR user_id IS NULL) AND is_read = 0 ORDER BY created_at DESC LIMIT 5",
            [$user['id']]
        );
        
        View::layout('user', ['page_title' => 'Tableau de bord']);
        $this->view('user/dashboard', [
            'user' => $user,
            'invoices' => $invoices,
            'contracts' => $contracts,
            'notifications' => $notifications
        ]);
    }
    
    /**
     * User profile page
     */
    public function profile(): void
    {
        $user = Auth::user();
        
        View::layout('user', ['page_title' => 'Mon profil']);
        $this->view('user/profile', ['user' => $user]);
    }
    
    /**
     * Update profile
     */
    public function updateProfile(): void
    {
        if (!$this->validateCsrf()) return;
        
        $user = Auth::user();
        
        Database::execute(
            "UPDATE users SET first_name = ?, last_name = ?, phone = ?, company = ?, address = ?, updated_at = NOW() WHERE id = ?",
            [
                $this->input('first_name'),
                $this->input('last_name'),
                $this->input('phone'),
                $this->input('company'),
                $this->input('address'),
                $user['id']
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
        
        $user = Auth::user();
        $currentPassword = $this->input('current_password');
        $newPassword = $this->input('new_password');
        $confirmPassword = $this->input('confirm_password');
        
        // Verify current password
        if (!password_verify($currentPassword, $user['password'])) {
            Session::flash('error', 'Mot de passe actuel incorrect.');
            $this->back();
            return;
        }
        
        if ($newPassword !== $confirmPassword) {
            Session::flash('error', 'Les mots de passe ne correspondent pas.');
            $this->back();
            return;
        }
        
        if (strlen($newPassword) < 8) {
            Session::flash('error', 'Le mot de passe doit contenir au moins 8 caractères.');
            $this->back();
            return;
        }
        
        Database::execute(
            "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?",
            [password_hash($newPassword, PASSWORD_DEFAULT), $user['id']]
        );
        
        Session::flash('success', 'Mot de passe modifié avec succès.');
        $this->redirect(SITE_URL . '/mon-compte/profil');
    }
    
    /**
     * User invoices list
     */
    public function invoices(): void
    {
        $user = Auth::user();
        
        $invoices = Database::query(
            "SELECT * FROM invoices WHERE client_email = ? ORDER BY created_at DESC",
            [$user['email']]
        );
        
        View::layout('user', ['page_title' => 'Mes factures']);
        $this->view('user/invoices', ['invoices' => $invoices]);
    }
    
    /**
     * User messages
     */
    public function messages(): void
    {
        $user = Auth::user();
        
        $messages = Database::query(
            "SELECT m.*, CONCAT(s.first_name, ' ', s.last_name) as sender_name
             FROM messages m
             LEFT JOIN users s ON m.sender_id = s.id
             WHERE m.recipient_id = ? OR (m.is_broadcast = 1)
             ORDER BY m.created_at DESC",
            [$user['id']]
        );
        
        View::layout('user', ['page_title' => 'Mes messages']);
        $this->view('user/messages', ['messages' => $messages]);
    }
}
