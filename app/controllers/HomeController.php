<?php
/**
 * TSILIZY LLC — Home Controller
 */

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Database;

class HomeController extends Controller
{
    /**
     * Display the homepage
     */
    public function index(): void
    {
        // Track page view for analytics
        $this->trackPageView('/', 'Accueil');
        
        // Get featured services from database
        $services = [];
        try {
            $services = Database::query("SELECT * FROM services WHERE status = 'active' AND deleted_at IS NULL ORDER BY order_index ASC LIMIT 6");
        } catch (\Exception $e) {}
        
        // Get featured portfolio items from database with placeholders
        $portfolio = [];
        try {
            $portfolio = Database::query("SELECT * FROM portfolios WHERE status = 'published' AND deleted_at IS NULL ORDER BY is_featured DESC, created_at DESC LIMIT 6");
        } catch (\Exception $e) {}
        
        // Fallback placeholder portfolio if database is empty
        if (empty($portfolio)) {
            $portfolio = [
                ['id' => 1, 'title' => 'Projet Digital', 'slug' => 'projet-digital', 'description' => 'Transformation digitale complète', 'category' => 'Digital', 'featured_image' => null],
                ['id' => 2, 'title' => 'Application Mobile', 'slug' => 'application-mobile', 'description' => 'Application iOS et Android', 'category' => 'Mobile', 'featured_image' => null],
                ['id' => 3, 'title' => 'Site E-commerce', 'slug' => 'site-ecommerce', 'description' => 'Boutique en ligne performante', 'category' => 'Web', 'featured_image' => null]
            ];
        }
        
        // Get approved reviews from database with placeholders
        $reviews = [];
        try {
            $reviews = Database::query("SELECT * FROM reviews WHERE status = 'approved' AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 3");
        } catch (\Exception $e) {}
        
        // Fallback placeholder reviews if database is empty
        if (empty($reviews)) {
            $reviews = [
                ['id' => 1, 'author_name' => 'Marie Dupont', 'rating' => 5, 'content' => 'Excellent travail ! L\'équipe a su répondre à toutes nos attentes avec professionnalisme.', 'company' => 'Tech Solutions'],
                ['id' => 2, 'author_name' => 'Jean Martin', 'rating' => 5, 'content' => 'Service impeccable et résultats au-delà de nos espérances. Je recommande vivement.', 'company' => 'Startup Innovante'],
                ['id' => 3, 'author_name' => 'Sophie Bernard', 'rating' => 4, 'content' => 'Très satisfaite de la collaboration. Communication fluide et livrables de qualité.', 'company' => 'Cabinet Conseil']
            ];
        }
        
        // Get upcoming events
        $events = [];
        try {
            $events = Database::query("SELECT * FROM events WHERE status = 'upcoming' AND deleted_at IS NULL ORDER BY start_datetime ASC LIMIT 3");
        } catch (\Exception $e) {}
        
        // Get active announcements
        $announcements = [];
        try {
            $announcements = Database::query("SELECT * FROM announcements WHERE status = 'published' AND deleted_at IS NULL ORDER BY is_pinned DESC, created_at DESC LIMIT 3");
        } catch (\Exception $e) {}
        
        $this->view('public/home', [
            'page_title' => 'Accueil',
            'services' => $services,
            'portfolio' => $portfolio,
            'reviews' => $reviews,
            'events' => $events,
            'announcements' => $announcements
        ]);
    }
    
    /**
     * Track page view for analytics
     */
    private function trackPageView(string $path, string $title): void
    {
        // Only track if database is available
        try {
            $sql = "INSERT INTO analytics (page_path, page_title, ip_address, user_agent, user_id, visitor_id, session_id, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            
            \Core\Database::execute($sql, [
                $path,
                $title,
                $_SERVER['REMOTE_ADDR'] ?? null,
                $_SERVER['HTTP_USER_AGENT'] ?? null,
                \Core\Auth::id(),
                $_COOKIE['visitor_id'] ?? null,
                session_id()
            ]);
        } catch (\Exception $e) {
            // Silently fail - analytics shouldn't break the page
        }
    }
}
