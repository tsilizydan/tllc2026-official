<?php
/**
 * TSILIZY LLC — Career Controller
 */

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;
use Core\Security;

class CareerController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * List all job offers
     */
    public function index(): void
    {
        $jobs = Database::query(
            "SELECT * FROM job_offers WHERE status = 'open' AND deleted_at IS NULL ORDER BY is_featured DESC, created_at DESC"
        );
        
        View::layout('public', ['page_title' => 'Carrières']);
        $this->view('public/careers/index', ['jobs' => $jobs]);
    }
    
    /**
     * Show single job
     */
    public function show(string $slug): void
    {
        $job = Database::query(
            "SELECT * FROM job_offers WHERE slug = ? AND status = 'open' AND deleted_at IS NULL",
            [$slug]
        )[0] ?? null;
        
        if (!$job) {
            \Core\Router::notFound();
            return;
        }
        
        View::layout('public', ['page_title' => $job['title'] . ' - Carrières']);
        $this->view('public/careers/show', ['job' => $job]);
    }
    
    /**
     * Apply for a job
     */
    public function apply(string $slug): void
    {
        if (!$this->validateCsrf()) return;
        
        $job = Database::query(
            "SELECT * FROM job_offers WHERE slug = ? AND status = 'open' AND deleted_at IS NULL",
            [$slug]
        )[0] ?? null;
        
        if (!$job) {
            \Core\Router::notFound();
            return;
        }
        
        // Validate
        $errors = $this->validate([
            'first_name' => 'required|min:2|max:100',
            'last_name' => 'required|min:2|max:100',
            'email' => 'required|email',
            'phone' => 'required|min:8|max:20',
            'message' => 'required|min:50'
        ]);
        
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs.');
            Session::set('validation_errors', $errors);
            Session::flashInput();
            $this->back();
            return;
        }
        
        // Handle CV upload
        $cvPath = null;
        if ($file = $this->file('cv')) {
            $cvPath = $this->uploadFile($file, 'applications');
            if (!$cvPath) {
                Session::flash('error', 'Erreur lors du téléchargement du CV.');
                $this->back();
                return;
            }
        }
        
        // Save application
        Database::insert(
            "INSERT INTO job_applications (job_offer_id, first_name, last_name, email, phone, message, cv_path, status, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', NOW())",
            [
                $job['id'],
                trim($_POST['first_name']),
                trim($_POST['last_name']),
                trim($_POST['email']),
                trim($_POST['phone']),
                Security::cleanHtml($_POST['message']),
                $cvPath
            ]
        );
        
        Session::flash('success', 'Votre candidature a été envoyée avec succès ! Nous vous contacterons prochainement.');
        $this->redirect(SITE_URL . '/carrieres/' . $slug);
    }
}
