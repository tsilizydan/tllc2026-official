<?php
/**
 * TSILIZY LLC — Services Controller (Public)
 */

namespace App\Controllers;

use Core\Controller;
use Core\View;
use App\Models\Service;

class ServiceController extends Controller
{
    /**
     * Display services list
     */
    public function index(): void
    {
        require_once BASE_PATH . '/app/models/Service.php';
        
        $services = Service::active();
        
        $this->view('public/services/index', [
            'page_title' => 'Nos Services',
            'seo_title' => 'Nos Services | ' . SITE_NAME,
            'seo_description' => 'Découvrez nos services de conseil, développement, design et marketing pour accompagner votre entreprise vers le succès.',
            'services' => $services
        ]);
    }
    
    /**
     * Display single service
     */
    public function show(string $slug): void
    {
        require_once BASE_PATH . '/app/models/Service.php';
        
        $service = Service::findBySlug($slug);
        
        if (!$service) {
            \Core\Router::notFound();
            return;
        }
        
        // Get related services
        $relatedServices = Service::featured(3);
        $relatedServices = array_filter($relatedServices, fn($s) => $s['id'] != $service['id']);
        $relatedServices = array_slice($relatedServices, 0, 3);
        
        $this->view('public/services/show', [
            'page_title' => $service['title'],
            'seo_title' => ($service['seo_title'] ?? $service['title']) . ' | ' . SITE_NAME,
            'seo_description' => $service['seo_description'] ?? $service['short_description'],
            'service' => $service,
            'related_services' => $relatedServices
        ]);
    }
}
