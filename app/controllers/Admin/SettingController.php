<?php
/**
 * TSILIZY LLC — Admin Settings Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;
use App\Models\Setting;

class SettingController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
        $this->requirePermission('settings.manage');
    }
    
    /**
     * Display settings page
     */
    public function index(): void
    {
        require_once BASE_PATH . '/app/models/Setting.php';
        
        $settings = [
            'general' => Setting::getGroup('general'),
            'seo' => Setting::getGroup('seo'),
            'email' => Setting::getGroup('email'),
            'social' => Setting::getGroup('social')
        ];
        
        View::layout('admin', ['page_title' => 'Paramètres']);
        $this->view('admin/settings/index', [
            'page_title' => 'Paramètres',
            'settings' => $settings
        ]);
    }
    
    /**
     * Update settings
     */
    public function update(): void
    {
        if (!$this->validateCsrf()) return;
        
        require_once BASE_PATH . '/app/models/Setting.php';
        
        $group = $this->input('group') ?? 'general';
        
        // Define settings structure
        $settingsSchema = [
            'general' => [
                'site_name' => 'string',
                'site_tagline' => 'string',
                'site_email' => 'string',
                'site_phone' => 'string',
                'site_address' => 'string',
                'timezone' => 'string',
                'maintenance_mode' => 'boolean'
            ],
            'seo' => [
                'meta_title' => 'string',
                'meta_description' => 'string',
                'meta_keywords' => 'string',
                'google_analytics' => 'string',
                'robots_txt' => 'string'
            ],
            'email' => [
                'smtp_host' => 'string',
                'smtp_port' => 'integer',
                'smtp_username' => 'string',
                'smtp_password' => 'string',
                'smtp_encryption' => 'string',
                'mail_from_name' => 'string',
                'mail_from_address' => 'string'
            ],
            'social' => [
                'facebook_url' => 'string',
                'twitter_url' => 'string',
                'linkedin_url' => 'string',
                'instagram_url' => 'string',
                'youtube_url' => 'string'
            ]
        ];
        
        if (!isset($settingsSchema[$group])) {
            Session::flash('error', 'Groupe de paramètres invalide.');
            $this->back();
            return;
        }
        
        foreach ($settingsSchema[$group] as $key => $type) {
            $value = $_POST[$key] ?? null;
            
            if ($type === 'boolean') {
                $value = isset($_POST[$key]) ? '1' : '0';
            }
            
            if ($value !== null) {
                Setting::set($key, $value, $type, $group);
            }
        }
        
        Session::flash('success', 'Paramètres mis à jour avec succès.');
        $this->redirect(SITE_URL . '/admin/parametres#' . $group);
    }
}
