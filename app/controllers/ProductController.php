<?php
/**
 * TSILIZY LLC — Product Controller (Public)
 */

namespace App\Controllers;

use Core\Controller;
use Core\Database;

class ProductController extends Controller
{
    /**
     * List all products
     */
    public function index(): void
    {
        $products = Database::query(
            "SELECT * FROM products WHERE status = 'active' AND deleted_at IS NULL ORDER BY is_featured DESC, created_at DESC"
        );
        
        $this->view('public/products/index', [
            'page_title' => 'Produits',
            'seo_title' => 'Nos Produits - ' . SITE_NAME,
            'seo_description' => 'Découvrez notre gamme de produits de qualité.',
            'products' => $products
        ]);
    }
    
    /**
     * Show single product
     */
    public function show(string $slug): void
    {
        $product = Database::query(
            "SELECT * FROM products WHERE slug = ? AND status = 'active' AND deleted_at IS NULL",
            [$slug]
        )[0] ?? null;
        
        if (!$product) {
            \Core\Router::notFound();
            return;
        }
        
        // Get related products
        $related = Database::query(
            "SELECT * FROM products WHERE id != ? AND category = ? AND status = 'active' AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 4",
            [$product['id'], $product['category'] ?? '']
        );
        
        $this->view('public/products/show', [
            'page_title' => $product['name'],
            'seo_title' => $product['name'] . ' - ' . SITE_NAME,
            'seo_description' => $product['short_description'] ?? '',
            'product' => $product,
            'related' => $related
        ]);
    }
}
