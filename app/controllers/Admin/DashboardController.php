<?php
/**
 * TSILIZY LLC — Admin Dashboard Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\Auth;
use Core\Database;
use Core\View;

class DashboardController extends Controller
{
    /**
     * Constructor - require admin access
     */
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    /**
     * Display the admin dashboard
     */
    public function index(): void
    {
        // Get statistics
        $stats = $this->getStats();
        
        // Get recent activities
        $recentContacts = $this->getRecentContacts();
        $recentApplications = $this->getRecentApplications();
        $pendingReviews = $this->getPendingReviews();
        $openTickets = $this->getOpenTickets();
        
        // Get chart data
        $trafficData = $this->getTrafficData();
        $conversionData = $this->getConversionData();
        
        View::layout('admin', ['page_title' => 'Tableau de bord']);
        $this->view('admin/dashboard', [
            'page_title' => 'Tableau de bord',
            'stats' => $stats,
            'recent_contacts' => $recentContacts,
            'recent_applications' => $recentApplications,
            'pending_reviews' => $pendingReviews,
            'open_tickets' => $openTickets,
            'traffic_data' => $trafficData,
            'conversion_data' => $conversionData
        ]);
    }
    
    /**
     * Get overall statistics
     */
    private function getStats(): array
    {
        $stats = [];
        
        // Users count
        $result = Database::query("SELECT COUNT(*) as count FROM users WHERE deleted_at IS NULL");
        $stats['users'] = $result[0]['count'] ?? 0;
        
        // New users this month
        $result = Database::query("SELECT COUNT(*) as count FROM users WHERE deleted_at IS NULL AND created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)");
        $stats['new_users'] = $result[0]['count'] ?? 0;
        
        // Contact messages
        $result = Database::query("SELECT COUNT(*) as count FROM contacts WHERE deleted_at IS NULL AND status = 'new'");
        $stats['new_contacts'] = $result[0]['count'] ?? 0;
        
        // Open tickets
        $result = Database::query("SELECT COUNT(*) as count FROM tickets WHERE deleted_at IS NULL AND status IN ('open', 'in_progress')");
        $stats['open_tickets'] = $result[0]['count'] ?? 0;
        
        // Pending reviews
        $result = Database::query("SELECT COUNT(*) as count FROM reviews WHERE deleted_at IS NULL AND status = 'pending'");
        $stats['pending_reviews'] = $result[0]['count'] ?? 0;
        
        // Job applications
        $result = Database::query("SELECT COUNT(*) as count FROM job_applications WHERE status = 'pending'");
        $stats['pending_applications'] = $result[0]['count'] ?? 0;
        
        // Newsletter subscribers
        $result = Database::query("SELECT COUNT(*) as count FROM newsletter_subscribers WHERE status = 'active'");
        $stats['subscribers'] = $result[0]['count'] ?? 0;
        
        // Page views this month
        $result = Database::query("SELECT COUNT(*) as count FROM analytics WHERE created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)");
        $stats['page_views'] = $result[0]['count'] ?? 0;
        
        // Unique visitors this month
        $result = Database::query("SELECT COUNT(DISTINCT visitor_id) as count FROM analytics WHERE visitor_id IS NOT NULL AND created_at >= DATE_SUB(NOW(), INTERVAL 1 MONTH)");
        $stats['unique_visitors'] = $result[0]['count'] ?? 0;
        
        // Total invoices value
        $result = Database::query("SELECT SUM(total) as total FROM invoices WHERE deleted_at IS NULL AND status = 'paid'");
        $stats['revenue'] = $result[0]['total'] ?? 0;
        
        // Published content
        $result = Database::query("SELECT COUNT(*) as count FROM pages WHERE deleted_at IS NULL AND status = 'published'");
        $stats['pages'] = $result[0]['count'] ?? 0;
        
        $result = Database::query("SELECT COUNT(*) as count FROM services WHERE deleted_at IS NULL AND status = 'active'");
        $stats['services'] = $result[0]['count'] ?? 0;
        
        $result = Database::query("SELECT COUNT(*) as count FROM products WHERE deleted_at IS NULL AND status = 'active'");
        $stats['products'] = $result[0]['count'] ?? 0;
        
        return $stats;
    }
    
    /**
     * Get recent contact messages
     */
    private function getRecentContacts(): array
    {
        return Database::query(
            "SELECT * FROM contacts WHERE deleted_at IS NULL ORDER BY created_at DESC LIMIT 5"
        );
    }
    
    /**
     * Get recent job applications
     */
    private function getRecentApplications(): array
    {
        return Database::query(
            "SELECT ja.*, jo.title as job_title 
             FROM job_applications ja 
             LEFT JOIN job_offers jo ON ja.job_offer_id = jo.id 
             ORDER BY ja.created_at DESC LIMIT 5"
        );
    }
    
    /**
     * Get pending reviews
     */
    private function getPendingReviews(): array
    {
        return Database::query(
            "SELECT r.*, CONCAT(u.first_name, ' ', u.last_name) as user_name 
             FROM reviews r 
             LEFT JOIN users u ON r.user_id = u.id 
             WHERE r.deleted_at IS NULL AND r.status = 'pending' 
             ORDER BY r.created_at DESC LIMIT 5"
        );
    }
    
    /**
     * Get open tickets
     */
    private function getOpenTickets(): array
    {
        return Database::query(
            "SELECT * FROM tickets 
             WHERE deleted_at IS NULL AND status IN ('open', 'in_progress') 
             ORDER BY FIELD(priority, 'urgent', 'high', 'medium', 'low'), created_at DESC 
             LIMIT 5"
        );
    }
    
    /**
     * Get traffic data for chart
     */
    private function getTrafficData(): array
    {
        $data = Database::query(
            "SELECT DATE(created_at) as date, COUNT(*) as views 
             FROM analytics 
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
             GROUP BY DATE(created_at) 
             ORDER BY date ASC"
        );
        
        // Return as array of objects for Chart.js
        return array_map(function($row) {
            return ['date' => $row['date'], 'views' => (int)$row['views']];
        }, $data);
    }
    
    /**
     * Get conversion data
     */
    private function getConversionData(): array
    {
        // Contact form submissions
        $contacts = Database::query(
            "SELECT DATE(created_at) as date, COUNT(*) as count 
             FROM contacts 
             WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
             GROUP BY DATE(created_at)"
        );
        
        // Newsletter signups
        $newsletter = Database::query(
            "SELECT DATE(subscribed_at) as date, COUNT(*) as count 
             FROM newsletter_subscribers 
             WHERE subscribed_at >= DATE_SUB(NOW(), INTERVAL 30 DAY) 
             GROUP BY DATE(subscribed_at)"
        );
        
        return [
            'contacts' => array_sum(array_column($contacts, 'count')),
            'newsletter' => array_sum(array_column($newsletter, 'count'))
        ];
    }
}
