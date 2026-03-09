<?php
/**
 * TSILIZY LLC — Admin User Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Auth;
use Core\Database;
use Core\Security;
use Core\Session;

class UserController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    /**
     * Display users list
     */
    public function index(): void
    {
        $page = (int)($this->query('page') ?? 1);
        $search = $this->query('search');
        $status = $this->query('status');
        
        // Build query
        $conditions = [];
        $params = [];
        
        if ($search) {
            $conditions[] = "(first_name LIKE ? OR last_name LIKE ? OR email LIKE ?)";
            $params = array_merge($params, ["%$search%", "%$search%", "%$search%"]);
        }
        
        if ($status) {
            $conditions[] = "status = ?";
            $params[] = $status;
        }
        
        $whereClause = !empty($conditions) ? implode(' AND ', $conditions) : null;
        
        // Get users with roles
        $offset = ($page - 1) * ADMIN_ITEMS_PER_PAGE;
        $whereSQL = $whereClause ? "WHERE u.deleted_at IS NULL AND $whereClause" : "WHERE u.deleted_at IS NULL";
        
        $sql = "SELECT u.*, r.name as role_name 
                FROM users u 
                LEFT JOIN user_roles ur ON u.id = ur.user_id 
                LEFT JOIN roles r ON ur.role_id = r.id 
                $whereSQL
                ORDER BY u.created_at DESC 
                LIMIT " . ADMIN_ITEMS_PER_PAGE . " OFFSET $offset";
        
        $users = Database::query($sql, $params);
        
        // Get total count
        $countSQL = "SELECT COUNT(*) as count FROM users u $whereSQL";
        $total = Database::query($countSQL, $params)[0]['count'] ?? 0;
        
        View::layout('admin', ['page_title' => 'Utilisateurs']);
        $this->view('admin/users/index', [
            'page_title' => 'Utilisateurs',
            'users' => $users,
            'total' => $total,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE),
            'search' => $search,
            'status' => $status
        ]);
    }
    
    /**
     * Show create form
     */
    public function create(): void
    {
        $roles = Database::query("SELECT * FROM roles ORDER BY name");
        
        View::layout('admin', ['page_title' => 'Créer un utilisateur']);
        $this->view('admin/users/create', [
            'page_title' => 'Créer un utilisateur',
            'roles' => $roles
        ]);
    }
    
    /**
     * Store new user
     */
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        $errors = $this->validate([
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'email' => 'required|email',
            'password' => 'required|min:8',
            'role_id' => 'required|numeric'
        ]);
        
        // Check email uniqueness
        $existing = Database::query("SELECT id FROM users WHERE email = ? AND deleted_at IS NULL", [$this->input('email')]);
        if (!empty($existing)) {
            $errors['email'] = 'Cette adresse email est déjà utilisée.';
        }
        
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs.');
            Session::set('validation_errors', $errors);
            Session::flashInput();
            $this->back();
            return;
        }
        
        Database::beginTransaction();
        
        try {
            // Create user
            $sql = "INSERT INTO users (first_name, last_name, email, password, phone, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, 'active', NOW(), NOW())";
            $userId = Database::insert($sql, [
                $this->input('first_name'),
                $this->input('last_name'),
                $this->input('email'),
                Security::hashPassword($_POST['password']),
                $this->input('phone')
            ]);
            
            // Assign role
            Database::execute(
                "INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)",
                [$userId, (int)$this->input('role_id')]
            );
            
            // Log action
            $this->logAction('user.created', 'users', $userId);
            
            Database::commit();
            
            Session::flash('success', 'Utilisateur créé avec succès.');
            $this->redirect(SITE_URL . '/admin/utilisateurs');
            
        } catch (\Exception $e) {
            Database::rollback();
            Session::flash('error', 'Erreur lors de la création.');
            $this->back();
        }
    }
    
    /**
     * Show user details
     */
    public function show(string $id): void
    {
        $user = $this->getUser((int)$id);
        if (!$user) {
            \Core\Router::notFound();
            return;
        }
        
        View::layout('admin', ['page_title' => 'Détails utilisateur']);
        $this->view('admin/users/show', [
            'page_title' => 'Détails utilisateur',
            'user' => $user
        ]);
    }
    
    /**
     * Show edit form
     */
    public function edit(string $id): void
    {
        $user = $this->getUser((int)$id);
        if (!$user) {
            \Core\Router::notFound();
            return;
        }
        
        $roles = Database::query("SELECT * FROM roles ORDER BY name");
        
        View::layout('admin', ['page_title' => 'Modifier utilisateur']);
        $this->view('admin/users/edit', [
            'page_title' => 'Modifier utilisateur',
            'user' => $user,
            'roles' => $roles
        ]);
    }
    
    /**
     * Update user
     */
    public function update(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        $user = $this->getUser((int)$id);
        if (!$user) {
            \Core\Router::notFound();
            return;
        }
        
        $errors = $this->validate([
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'email' => 'required|email'
        ]);
        
        // Check email uniqueness (excluding current user)
        $existing = Database::query("SELECT id FROM users WHERE email = ? AND id != ? AND deleted_at IS NULL", [$this->input('email'), (int)$id]);
        if (!empty($existing)) {
            $errors['email'] = 'Cette adresse email est déjà utilisée.';
        }
        
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs.');
            Session::set('validation_errors', $errors);
            Session::flashInput();
            $this->back();
            return;
        }
        
        // Update user
        $sql = "UPDATE users SET first_name = ?, last_name = ?, email = ?, phone = ?, status = ?, updated_at = NOW() WHERE id = ?";
        Database::execute($sql, [
            $this->input('first_name'),
            $this->input('last_name'),
            $this->input('email'),
            $this->input('phone'),
            $this->input('status'),
            (int)$id
        ]);
        
        // Update password if provided
        if (!empty($_POST['password'])) {
            Database::execute("UPDATE users SET password = ? WHERE id = ?", [Security::hashPassword($_POST['password']), (int)$id]);
        }
        
        // Update role if provided
        if ($this->input('role_id')) {
            Database::execute("DELETE FROM user_roles WHERE user_id = ?", [(int)$id]);
            Database::execute("INSERT INTO user_roles (user_id, role_id) VALUES (?, ?)", [(int)$id, (int)$this->input('role_id')]);
        }
        
        $this->logAction('user.updated', 'users', (int)$id);
        
        Session::flash('success', 'Utilisateur modifié avec succès.');
        $this->redirect(SITE_URL . '/admin/utilisateurs');
    }
    
    /**
     * Delete user (soft delete)
     */
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        // Prevent self-deletion
        if ((int)$id === Auth::id()) {
            Session::flash('error', 'Vous ne pouvez pas supprimer votre propre compte.');
            $this->back();
            return;
        }
        
        Database::execute("UPDATE users SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        $this->logAction('user.deleted', 'users', (int)$id);
        
        Session::flash('success', 'Utilisateur supprimé.');
        $this->redirect(SITE_URL . '/admin/utilisateurs');
    }
    
    /**
     * Suspend user
     */
    public function suspend(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        Database::execute("UPDATE users SET status = 'suspended', updated_at = NOW() WHERE id = ?", [(int)$id]);
        $this->logAction('user.suspended', 'users', (int)$id);
        
        Session::flash('success', 'Utilisateur suspendu.');
        $this->back();
    }
    
    /**
     * Ban user
     */
    public function ban(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        Database::execute("UPDATE users SET status = 'banned', updated_at = NOW() WHERE id = ?", [(int)$id]);
        $this->logAction('user.banned', 'users', (int)$id);
        
        Session::flash('success', 'Utilisateur banni.');
        $this->back();
    }
    
    /**
     * Get user by ID with role
     */
    private function getUser(int $id): ?array
    {
        $sql = "SELECT u.*, r.id as role_id, r.name as role_name 
                FROM users u 
                LEFT JOIN user_roles ur ON u.id = ur.user_id 
                LEFT JOIN roles r ON ur.role_id = r.id 
                WHERE u.id = ? AND u.deleted_at IS NULL";
        $result = Database::query($sql, [$id]);
        return $result[0] ?? null;
    }
    
    /**
     * Log admin action
     */
    private function logAction(string $action, string $entityType, int $entityId, ?array $oldValues = null, ?array $newValues = null): void
    {
        $sql = "INSERT INTO audit_logs (user_id, action, entity_type, entity_id, old_values, new_values, ip_address, user_agent, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        Database::execute($sql, [
            Auth::id(),
            $action,
            $entityType,
            $entityId,
            $oldValues ? json_encode($oldValues) : null,
            $newValues ? json_encode($newValues) : null,
            $_SERVER['REMOTE_ADDR'] ?? null,
            $_SERVER['HTTP_USER_AGENT'] ?? null
        ]);
    }
}
