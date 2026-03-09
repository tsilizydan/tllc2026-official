<?php
/**
 * TSILIZY LLC — Portfolio Controller (Public)
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Portfolio;

class PortfolioController extends Controller
{
    /**
     * Display portfolio list
     */
    public function index(): void
    {
        require_once BASE_PATH . '/app/models/Portfolio.php';
        
        $category = $this->query('categorie');
        
        if ($category) {
            $items = Portfolio::byCategory($category);
        } else {
            $items = Portfolio::active();
        }
        
        $categories = Portfolio::getCategories();
        
        $this->view('public/portfolio/index', [
            'page_title' => 'Portfolio',
            'seo_title' => 'Nos Réalisations | ' . SITE_NAME,
            'seo_description' => 'Découvrez notre portfolio de projets réalisés avec succès pour nos clients.',
            'items' => $items,
            'categories' => array_column($categories, 'category'),
            'current_category' => $category
        ]);
    }
    
    /**
     * Display single portfolio item
     */
    public function show(string $slug): void
    {
        require_once BASE_PATH . '/app/models/Portfolio.php';
        
        $item = Portfolio::findBySlug($slug);
        
        if (!$item) {
            \Core\Router::notFound();
            return;
        }
        
        // Get related projects
        $related = Portfolio::byCategory($item['category']);
        $related = array_filter($related, fn($p) => $p['id'] != $item['id']);
        $related = array_slice($related, 0, 3);
        
        $this->view('public/portfolio/show', [
            'page_title' => $item['title'],
            'seo_title' => ($item['seo_title'] ?? $item['title']) . ' | ' . SITE_NAME,
            'seo_description' => $item['seo_description'] ?? $item['short_description'],
            'item' => $item,
            'related' => $related
        ]);
    }
}
