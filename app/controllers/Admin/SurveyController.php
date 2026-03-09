<?php
/**
 * TSILIZY LLC — Admin Survey Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;

class SurveyController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    public function index(): void
    {
        $surveys = Database::query(
            "SELECT s.*, (SELECT COUNT(*) FROM survey_responses WHERE survey_id = s.id) as responses_count 
             FROM surveys s WHERE s.deleted_at IS NULL ORDER BY s.created_at DESC"
        );
        
        View::layout('admin', ['page_title' => 'Sondages']);
        $this->view('admin/surveys/index', ['surveys' => $surveys]);
    }
    
    public function create(): void
    {
        View::layout('admin', ['page_title' => 'Nouveau sondage']);
        $this->view('admin/surveys/form', ['survey' => null, 'mode' => 'create']);
    }
    
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        $id = Database::insert(
            "INSERT INTO surveys (title, description, questions, status, starts_at, ends_at, created_at, updated_at) 
             VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $this->input('title'), $this->input('description'),
                json_encode($_POST['questions'] ?? []),
                $this->input('status') ?? 'draft',
                $this->input('starts_at') ?: null, $this->input('ends_at') ?: null
            ]
        );
        
        Session::flash('success', 'Sondage créé.');
        $this->redirect(SITE_URL . '/admin/sondages');
    }
    
    public function edit(string $id): void
    {
        $survey = Database::query("SELECT * FROM surveys WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$survey) { \Core\Router::notFound(); return; }
        
        $survey['questions'] = json_decode($survey['questions'] ?? '[]', true);
        
        View::layout('admin', ['page_title' => 'Modifier le sondage']);
        $this->view('admin/surveys/form', ['survey' => $survey, 'mode' => 'edit']);
    }
    
    public function update(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        Database::execute(
            "UPDATE surveys SET title = ?, description = ?, questions = ?, status = ?, starts_at = ?, ends_at = ?, updated_at = NOW() WHERE id = ?",
            [
                $this->input('title'), $this->input('description'),
                json_encode($_POST['questions'] ?? []),
                $this->input('status'),
                $this->input('starts_at') ?: null, $this->input('ends_at') ?: null,
                (int)$id
            ]
        );
        
        Session::flash('success', 'Sondage modifié.');
        $this->redirect(SITE_URL . '/admin/sondages');
    }
    
    public function results(string $id): void
    {
        $survey = Database::query("SELECT * FROM surveys WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$survey) { \Core\Router::notFound(); return; }
        
        $responses = Database::query(
            "SELECT * FROM survey_responses WHERE survey_id = ? ORDER BY created_at DESC",
            [(int)$id]
        );
        
        $survey['questions'] = json_decode($survey['questions'] ?? '[]', true);
        
        View::layout('admin', ['page_title' => 'Résultats du sondage']);
        $this->view('admin/surveys/results', ['survey' => $survey, 'responses' => $responses]);
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE surveys SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Sondage supprimé.');
        $this->redirect(SITE_URL . '/admin/sondages');
    }
}
