<?php
/**
 * TSILIZY LLC — Admin Careers Controller (Job Offers)
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Security;
use Core\Session;

class CareerController extends Controller
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
        
        $jobs = Database::query(
            "SELECT jo.*, (SELECT COUNT(*) FROM job_applications WHERE job_offer_id = jo.id) as applications_count
             FROM job_offers jo WHERE jo.deleted_at IS NULL ORDER BY jo.created_at DESC LIMIT ? OFFSET ?",
            [ADMIN_ITEMS_PER_PAGE, $offset]
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM job_offers WHERE deleted_at IS NULL")[0]['count'];
        
        View::layout('admin', ['page_title' => 'Offres d\'emploi']);
        $this->view('admin/careers/index', [
            'page_title' => 'Offres d\'emploi',
            'jobs' => $jobs,
            'total' => $total,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE)
        ]);
    }
    
    public function create(): void
    {
        View::layout('admin', ['page_title' => 'Nouvelle offre']);
        $this->view('admin/careers/form', ['job' => null, 'mode' => 'create']);
    }
    
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        $errors = $this->validate(['title' => 'required|min:3|max:255', 'description' => 'required|min:50']);
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs.');
            Session::set('validation_errors', $errors);
            Session::flashInput();
            $this->back();
            return;
        }
        
        $slug = Security::slug($this->input('title'));
        $existing = Database::query("SELECT id FROM job_offers WHERE slug = ? AND deleted_at IS NULL", [$slug]);
        if (!empty($existing)) $slug .= '-' . time();
        
        Database::insert(
            "INSERT INTO job_offers (title, slug, description, requirements, benefits, location, department, employment_type, experience_level, salary_min, salary_max, salary_currency, is_remote, application_deadline, status, is_featured, seo_title, seo_description, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $this->input('title'), $slug, Security::cleanHtml($_POST['description']),
                Security::cleanHtml($_POST['requirements'] ?? ''), Security::cleanHtml($_POST['benefits'] ?? ''),
                $this->input('location'), $this->input('department'), $this->input('employment_type'),
                $this->input('experience_level'), $this->input('salary_min') ?: null, $this->input('salary_max') ?: null,
                $this->input('salary_currency') ?? 'EUR', isset($_POST['is_remote']) ? 1 : 0,
                $this->input('application_deadline') ?: null, $this->input('status') ?? 'open',
                isset($_POST['is_featured']) ? 1 : 0, $this->input('seo_title'), $this->input('seo_description')
            ]
        );
        
        Session::flash('success', 'Offre créée avec succès.');
        $this->redirect(SITE_URL . '/admin/carrieres');
    }
    
    public function edit(string $id): void
    {
        $job = Database::query("SELECT * FROM job_offers WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$job) { \Core\Router::notFound(); return; }
        
        View::layout('admin', ['page_title' => 'Modifier l\'offre']);
        $this->view('admin/careers/form', ['job' => $job, 'mode' => 'edit']);
    }
    
    public function update(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        $job = Database::query("SELECT * FROM job_offers WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$job) { \Core\Router::notFound(); return; }
        
        Database::execute(
            "UPDATE job_offers SET title = ?, description = ?, requirements = ?, benefits = ?, location = ?, department = ?, employment_type = ?, experience_level = ?, salary_min = ?, salary_max = ?, salary_currency = ?, is_remote = ?, application_deadline = ?, status = ?, is_featured = ?, seo_title = ?, seo_description = ?, updated_at = NOW() WHERE id = ?",
            [
                $this->input('title'), Security::cleanHtml($_POST['description']),
                Security::cleanHtml($_POST['requirements'] ?? ''), Security::cleanHtml($_POST['benefits'] ?? ''),
                $this->input('location'), $this->input('department'), $this->input('employment_type'),
                $this->input('experience_level'), $this->input('salary_min') ?: null, $this->input('salary_max') ?: null,
                $this->input('salary_currency') ?? 'EUR', isset($_POST['is_remote']) ? 1 : 0,
                $this->input('application_deadline') ?: null, $this->input('status') ?? 'open',
                isset($_POST['is_featured']) ? 1 : 0, $this->input('seo_title'), $this->input('seo_description'), (int)$id
            ]
        );
        
        Session::flash('success', 'Offre modifiée avec succès.');
        $this->redirect(SITE_URL . '/admin/carrieres');
    }
    
    public function applications(?string $id = null): void
    {
        // If no ID, show all applications
        if (!$id) {
            $applications = Database::query(
                "SELECT ja.*, jo.title as job_title FROM job_applications ja 
                 LEFT JOIN job_offers jo ON ja.job_offer_id = jo.id 
                 ORDER BY ja.created_at DESC"
            );
            
            View::layout('admin', ['page_title' => 'Toutes les candidatures']);
            $this->view('admin/careers/applications', [
                'job' => null,
                'applications' => $applications,
                'page_title' => 'Toutes les candidatures'
            ]);
            return;
        }
        
        $job = Database::query("SELECT * FROM job_offers WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$job) { \Core\Router::notFound(); return; }
        
        $applications = Database::query(
            "SELECT * FROM job_applications WHERE job_offer_id = ? ORDER BY created_at DESC",
            [(int)$id]
        );
        
        View::layout('admin', ['page_title' => 'Candidatures - ' . $job['title']]);
        $this->view('admin/careers/applications', ['job' => $job, 'applications' => $applications]);
    }
    
    public function showApplication(string $id): void
    {
        $application = Database::query(
            "SELECT ja.*, jo.title as job_title FROM job_applications ja 
             LEFT JOIN job_offers jo ON ja.job_offer_id = jo.id 
             WHERE ja.id = ?",
            [(int)$id]
        )[0] ?? null;
        
        if (!$application) { \Core\Router::notFound(); return; }
        
        View::layout('admin', ['page_title' => 'Candidature']);
        $this->view('admin/careers/application-show', ['application' => $application]);
    }
    
    public function updateApplicationStatus(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        $status = $this->input('status');
        Database::execute(
            "UPDATE job_applications SET status = ?, updated_at = NOW() WHERE id = ?",
            [$status, (int)$id]
        );
        
        Session::flash('success', 'Statut mis à jour.');
        $this->back();
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE job_offers SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Offre supprimée.');
        $this->redirect(SITE_URL . '/admin/carrieres');
    }
}
