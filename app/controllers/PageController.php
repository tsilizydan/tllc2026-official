<?php
/**
 * TSILIZY LLC — Page Controller (Dynamic Pages)
 */

namespace App\Controllers;

use Core\Controller;
use App\Models\Page;

class PageController extends Controller
{
    /**
     * About page
     */
    public function about(): void
    {
        $this->view('public/about', [
            'page_title' => 'À Propos',
            'seo_title' => 'À Propos - ' . SITE_NAME,
            'seo_description' => 'Découvrez TSILIZY LLC, notre mission, notre vision et nos valeurs.'
        ]);
    }
    
    /**
     * Display dynamic page by slug
     */
    public function show(string $slug): void
    {
        require_once BASE_PATH . '/app/models/Page.php';
        
        $page = Page::findBySlug($slug);
        
        if (!$page || $page['status'] !== 'published') {
            \Core\Router::notFound();
            return;
        }
        
        $this->view('public/page', [
            'page_title' => $page['title'],
            'seo_title' => ($page['seo_title'] ?? $page['title']) . ' | ' . SITE_NAME,
            'seo_description' => $page['seo_description'] ?? $page['excerpt'],
            'page' => $page
        ]);
    }
}

