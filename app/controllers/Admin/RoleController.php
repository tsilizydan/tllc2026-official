<?php
/**
 * TSILIZY LLC — Admin Role Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;

class RoleController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    public function index(): void
    {
        $roles = Database::query(
            "SELECT r.*, 
                (SELECT COUNT(*) FROM user_roles WHERE role_id = r.id) as users_count,
                (SELECT COUNT(*) FROM role_permissions WHERE role_id = r.id) as permissions_count
             FROM roles r ORDER BY r.id ASC"
        );
        
        View::layout('admin', ['page_title' => 'Rôles & Permissions']);
        $this->view('admin/roles/index', ['roles' => $roles]);
    }
    
    public function create(): void
    {
        $permissions = Database::query("SELECT * FROM permissions ORDER BY `group`, name");
        $permissionGroups = $this->groupPermissions($permissions);
        
        View::layout('admin', ['page_title' => 'Nouveau rôle']);
        $this->view('admin/roles/form', [
            'role' => null,
            'mode' => 'create',
            'permissionGroups' => $permissionGroups,
            'rolePermissions' => []
        ]);
    }
    
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        $name = $this->input('name');
        $slug = $this->input('slug') ?: $this->slugify($name);
        
        // Check unique slug
        $existing = Database::query("SELECT id FROM roles WHERE slug = ?", [$slug]);
        if (!empty($existing)) {
            Session::flash('error', 'Un rôle avec ce slug existe déjà.');
            $this->back();
            return;
        }
        
        $roleId = Database::insert(
            "INSERT INTO roles (name, slug, description, created_at, updated_at) VALUES (?, ?, ?, NOW(), NOW())",
            [$name, $slug, $this->input('description')]
        );
        
        // Assign permissions
        $permissionIds = $_POST['permissions'] ?? [];
        foreach ($permissionIds as $permId) {
            Database::insert("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)", [(int)$roleId, (int)$permId]);
        }
        
        Session::flash('success', 'Rôle créé avec succès.');
        $this->redirect(SITE_URL . '/admin/roles');
    }
    
    public function edit(string $id): void
    {
        $role = Database::query("SELECT * FROM roles WHERE id = ?", [(int)$id])[0] ?? null;
        if (!$role) { \Core\Router::notFound(); return; }
        
        $permissions = Database::query("SELECT * FROM permissions ORDER BY `group`, name");
        $permissionGroups = $this->groupPermissions($permissions);
        
        $rolePermissions = Database::query("SELECT permission_id FROM role_permissions WHERE role_id = ?", [(int)$id]);
        $rolePermissionIds = array_column($rolePermissions, 'permission_id');
        
        View::layout('admin', ['page_title' => 'Modifier le rôle']);
        $this->view('admin/roles/form', [
            'role' => $role,
            'mode' => 'edit',
            'permissionGroups' => $permissionGroups,
            'rolePermissions' => $rolePermissionIds
        ]);
    }
    
    public function update(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        // Prevent editing super_admin slug
        $role = Database::query("SELECT * FROM roles WHERE id = ?", [(int)$id])[0] ?? null;
        if ($role && $role['slug'] === 'super_admin') {
            Session::flash('error', 'Le rôle super administrateur ne peut pas être modifié.');
            $this->back();
            return;
        }
        
        Database::execute(
            "UPDATE roles SET name = ?, description = ?, updated_at = NOW() WHERE id = ?",
            [$this->input('name'), $this->input('description'), (int)$id]
        );
        
        // Update permissions
        Database::execute("DELETE FROM role_permissions WHERE role_id = ?", [(int)$id]);
        $permissionIds = $_POST['permissions'] ?? [];
        foreach ($permissionIds as $permId) {
            Database::insert("INSERT INTO role_permissions (role_id, permission_id) VALUES (?, ?)", [(int)$id, (int)$permId]);
        }
        
        Session::flash('success', 'Rôle modifié avec succès.');
        $this->redirect(SITE_URL . '/admin/roles');
    }
    
    public function users(string $id): void
    {
        $role = Database::query("SELECT * FROM roles WHERE id = ?", [(int)$id])[0] ?? null;
        if (!$role) { \Core\Router::notFound(); return; }
        
        $users = Database::query(
            "SELECT u.* FROM users u 
             JOIN user_roles ur ON u.id = ur.user_id 
             WHERE ur.role_id = ? AND u.deleted_at IS NULL 
             ORDER BY u.first_name",
            [(int)$id]
        );
        
        View::layout('admin', ['page_title' => 'Utilisateurs - ' . $role['name']]);
        $this->view('admin/roles/users', ['role' => $role, 'users' => $users]);
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        $role = Database::query("SELECT * FROM roles WHERE id = ?", [(int)$id])[0] ?? null;
        if ($role && in_array($role['slug'], ['super_admin', 'admin', 'user'])) {
            Session::flash('error', 'Ce rôle système ne peut pas être supprimé.');
            $this->back();
            return;
        }
        
        Database::execute("DELETE FROM role_permissions WHERE role_id = ?", [(int)$id]);
        Database::execute("DELETE FROM user_roles WHERE role_id = ?", [(int)$id]);
        Database::execute("DELETE FROM roles WHERE id = ?", [(int)$id]);
        
        Session::flash('success', 'Rôle supprimé.');
        $this->redirect(SITE_URL . '/admin/roles');
    }
    
    private function groupPermissions(array $permissions): array
    {
        $groups = [];
        foreach ($permissions as $p) {
            $groups[$p['group']][] = $p;
        }
        return $groups;
    }
    
    private function slugify(string $text): string
    {
        return strtolower(preg_replace('/[^a-zA-Z0-9]+/', '_', $text));
    }
}
