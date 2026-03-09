<?php
/**
 * TSILIZY LLC — Admin Analytics Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;

class AnalyticsController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    public function index(): void
    {
        // Get filter parameters
        $period = $this->query('period') ?? '30days';
        $startDate = $this->query('start_date');
        $endDate = $this->query('end_date');
        
        // Calculate date range based on period or custom dates
        if ($startDate && $endDate) {
            $dateFrom = $startDate;
            $dateTo = $endDate;
            $period = 'custom';
        } else {
            $dateTo = date('Y-m-d');
            switch ($period) {
                case '7days':
                    $dateFrom = date('Y-m-d', strtotime('-7 days'));
                    break;
                case '30days':
                    $dateFrom = date('Y-m-d', strtotime('-30 days'));
                    break;
                case '90days':
                    $dateFrom = date('Y-m-d', strtotime('-90 days'));
                    break;
                case 'year':
                    $dateFrom = date('Y-m-d', strtotime('-1 year'));
                    break;
                default:
                    $dateFrom = date('Y-m-d', strtotime('-30 days'));
                    $period = '30days';
            }
        }
        
        // Page views by day
        $pageViews = Database::query(
            "SELECT DATE(created_at) as date, COUNT(*) as views 
             FROM analytics WHERE DATE(created_at) BETWEEN ? AND ? 
             GROUP BY DATE(created_at) ORDER BY date ASC",
            [$dateFrom, $dateTo]
        );
        
        // Top pages
        $topPages = Database::query(
            "SELECT page_url, page_title, COUNT(*) as views 
             FROM analytics WHERE DATE(created_at) BETWEEN ? AND ? 
             GROUP BY page_url, page_title ORDER BY views DESC LIMIT 10",
            [$dateFrom, $dateTo]
        );
        
        // Traffic sources
        $sources = Database::query(
            "SELECT 
                CASE 
                    WHEN referrer IS NULL OR referrer = '' THEN 'Direct'
                    WHEN referrer LIKE '%google%' THEN 'Google'
                    WHEN referrer LIKE '%facebook%' THEN 'Facebook'
                    WHEN referrer LIKE '%twitter%' OR referrer LIKE '%x.com%' THEN 'Twitter/X'
                    WHEN referrer LIKE '%linkedin%' THEN 'LinkedIn'
                    ELSE 'Autre'
                END as source,
                COUNT(*) as visits
             FROM analytics WHERE DATE(created_at) BETWEEN ? AND ? 
             GROUP BY source ORDER BY visits DESC",
            [$dateFrom, $dateTo]
        );
        
        // Visitors by device
        $devices = Database::query(
            "SELECT 
                SUM(CASE WHEN user_agent LIKE '%Mobile%' OR user_agent LIKE '%Android%' OR user_agent LIKE '%iPhone%' THEN 1 ELSE 0 END) as mobile,
                SUM(CASE WHEN user_agent LIKE '%Tablet%' OR user_agent LIKE '%iPad%' THEN 1 ELSE 0 END) as tablet,
                SUM(CASE WHEN user_agent NOT LIKE '%Mobile%' AND user_agent NOT LIKE '%Android%' AND user_agent NOT LIKE '%iPhone%' AND user_agent NOT LIKE '%Tablet%' AND user_agent NOT LIKE '%iPad%' THEN 1 ELSE 0 END) as desktop
             FROM analytics WHERE DATE(created_at) BETWEEN ? AND ?",
            [$dateFrom, $dateTo]
        )[0] ?? ['mobile' => 0, 'tablet' => 0, 'desktop' => 0];
        
        // Summary stats
        $totalViews = Database::query("SELECT COUNT(*) as count FROM analytics WHERE DATE(created_at) BETWEEN ? AND ?", [$dateFrom, $dateTo])[0]['count'];
        $uniqueVisitors = Database::query("SELECT COUNT(DISTINCT ip_address) as count FROM analytics WHERE DATE(created_at) BETWEEN ? AND ?", [$dateFrom, $dateTo])[0]['count'];
        $todayViews = Database::query("SELECT COUNT(*) as count FROM analytics WHERE DATE(created_at) = CURDATE()")[0]['count'];
        
        // Bounce rate approximation (single page sessions)
        $bounceRate = 0;
        if ($uniqueVisitors > 0) {
            $singlePageSessions = Database::query(
                "SELECT COUNT(*) as c FROM (SELECT session_id FROM analytics WHERE DATE(created_at) BETWEEN ? AND ? GROUP BY session_id HAVING COUNT(*) = 1) as single_sessions",
                [$dateFrom, $dateTo]
            )[0]['c'] ?? 0;
            $totalSessions = Database::query(
                "SELECT COUNT(DISTINCT session_id) as c FROM analytics WHERE DATE(created_at) BETWEEN ? AND ?",
                [$dateFrom, $dateTo]
            )[0]['c'] ?? 1;
            $bounceRate = round(($singlePageSessions / max($totalSessions, 1)) * 100, 1);
        }
        
        View::layout('admin', ['page_title' => 'Analytiques']);
        $this->view('admin/analytics/index', [
            'page_views' => $pageViews,
            'top_pages' => $topPages,
            'sources' => $sources,
            'devices' => $devices,
            'total_views' => $totalViews,
            'unique_visitors' => $uniqueVisitors,
            'today_views' => $todayViews,
            'bounce_rate' => $bounceRate,
            'period' => $period,
            'date_from' => $dateFrom,
            'date_to' => $dateTo
        ]);
    }
    
    public function export(): void
    {
        $startDate = $this->query('start_date') ?? date('Y-m-d', strtotime('-30 days'));
        $endDate = $this->query('end_date') ?? date('Y-m-d');
        
        $data = Database::query(
            "SELECT DATE(created_at) as date, page_url, page_title, ip_address, user_agent, referrer, created_at 
             FROM analytics WHERE DATE(created_at) BETWEEN ? AND ? ORDER BY created_at DESC",
            [$startDate, $endDate]
        );
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="analytics_' . date('Y-m-d') . '.csv"');
        
        $output = fopen('php://output', 'w');
        fputcsv($output, ['Date', 'URL', 'Titre', 'IP', 'User Agent', 'Referrer', 'Horodatage']);
        
        foreach ($data as $row) {
            fputcsv($output, [
                $row['date'],
                $row['page_url'],
                $row['page_title'],
                $row['ip_address'],
                $row['user_agent'],
                $row['referrer'],
                $row['created_at']
            ]);
        }
        
        fclose($output);
        exit;
    }
}
