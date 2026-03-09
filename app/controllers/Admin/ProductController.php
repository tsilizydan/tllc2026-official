<?php
/**
 * TSILIZY LLC — Admin Products Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Auth;
use Core\Database;
use Core\Security;
use Core\Session;

class ProductController extends Controller
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
        
        $products = Database::query(
            "SELECT * FROM products WHERE deleted_at IS NULL ORDER BY order_index ASC, created_at DESC LIMIT ? OFFSET ?",
            [ADMIN_ITEMS_PER_PAGE, $offset]
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM products WHERE deleted_at IS NULL")[0]['count'];
        
        View::layout('admin', ['page_title' => 'Produits']);
        $this->view('admin/products/index', [
            'page_title' => 'Produits',
            'products' => $products,
            'total' => $total,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE)
        ]);
    }
    
    public function create(): void
    {
        View::layout('admin', ['page_title' => 'Nouveau produit']);
        $this->view('admin/products/form', ['product' => null, 'mode' => 'create']);
    }
    
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        $errors = $this->validate(['title' => 'required|min:3|max:255', 'description' => 'required|min:20']);
        
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs.');
            Session::set('validation_errors', $errors);
            Session::flashInput();
            $this->back();
            return;
        }
        
        $slug = Security::slug($this->input('title'));
        $existing = Database::query("SELECT id FROM products WHERE slug = ? AND deleted_at IS NULL", [$slug]);
        if (!empty($existing)) $slug .= '-' . time();
        
        $image = null;
        if ($file = $this->file('image')) $image = $this->uploadFile($file, 'products');
        
        Database::insert(
            "INSERT INTO products (title, slug, description, short_description, price, sale_price, sku, stock, category, image, status, is_featured, order_index, seo_title, seo_description, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $this->input('title'), $slug, Security::cleanHtml($_POST['description']), $this->input('short_description'),
                $this->input('price') ?: null, $this->input('sale_price') ?: null, $this->input('sku'), (int)$this->input('stock'),
                $this->input('category'), $image, $this->input('status') ?? 'active',
                isset($_POST['is_featured']) ? 1 : 0, (int)$this->input('order_index'),
                $this->input('seo_title'), $this->input('seo_description')
            ]
        );
        
        Session::flash('success', 'Produit créé avec succès.');
        $this->redirect(SITE_URL . '/admin/produits');
    }
    
    public function edit(string $id): void
    {
        $product = Database::query("SELECT * FROM products WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$product) { \Core\Router::notFound(); return; }
        
        View::layout('admin', ['page_title' => 'Modifier le produit']);
        $this->view('admin/products/form', ['product' => $product, 'mode' => 'edit']);
    }
    
    public function update(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        $product = Database::query("SELECT * FROM products WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$product) { \Core\Router::notFound(); return; }
        
        $errors = $this->validate(['title' => 'required|min:3|max:255', 'description' => 'required|min:20']);
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs.');
            Session::set('validation_errors', $errors);
            $this->back();
            return;
        }
        
        $image = $product['image'];
        if ($file = $this->file('image')) {
            $newImage = $this->uploadFile($file, 'products');
            if ($newImage) $image = $newImage;
        }
        
        Database::execute(
            "UPDATE products SET title = ?, description = ?, short_description = ?, price = ?, sale_price = ?, sku = ?, stock = ?, category = ?, image = ?, status = ?, is_featured = ?, order_index = ?, seo_title = ?, seo_description = ?, updated_at = NOW() WHERE id = ?",
            [
                $this->input('title'), Security::cleanHtml($_POST['description']), $this->input('short_description'),
                $this->input('price') ?: null, $this->input('sale_price') ?: null, $this->input('sku'), (int)$this->input('stock'),
                $this->input('category'), $image, $this->input('status') ?? 'active',
                isset($_POST['is_featured']) ? 1 : 0, (int)$this->input('order_index'),
                $this->input('seo_title'), $this->input('seo_description'), (int)$id
            ]
        );
        
        Session::flash('success', 'Produit modifié avec succès.');
        $this->redirect(SITE_URL . '/admin/produits');
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE products SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Produit supprimé.');
        $this->redirect(SITE_URL . '/admin/produits');
    }
}
