<?php
/**
 * TSILIZY LLC — Admin Agenda Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Database;
use Core\Session;

class AgendaController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->requireAdmin();
    }
    
    public function index(): void
    {
        $month = (int)($this->query('month') ?? date('m'));
        $year = (int)($this->query('year') ?? date('Y'));
        
        $startDate = "$year-$month-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        
        $appointments = Database::query(
            "SELECT * FROM appointments WHERE start_date >= ? AND start_date <= ? ORDER BY start_date ASC",
            [$startDate, $endDate . ' 23:59:59']
        );
        
        $upcomingToday = Database::query(
            "SELECT * FROM appointments WHERE DATE(start_date) = CURDATE() ORDER BY start_date ASC"
        );
        
        View::layout('admin', ['page_title' => 'Agenda']);
        $this->view('admin/agenda/index', [
            'appointments' => $appointments,
            'today' => $upcomingToday,
            'month' => $month,
            'year' => $year
        ]);
    }
    
    public function create(): void
    {
        $users = Database::query("SELECT id, CONCAT(first_name, ' ', last_name) as name FROM users WHERE deleted_at IS NULL ORDER BY first_name");
        View::layout('admin', ['page_title' => 'Nouveau rendez-vous']);
        $this->view('admin/agenda/form', ['appointment' => null, 'users' => $users, 'mode' => 'create']);
    }
    
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        Database::insert(
            "INSERT INTO appointments (user_id, title, description, start_date, end_date, location, status, created_at, updated_at) 
             VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $this->input('user_id') ?: null, $this->input('title'), $this->input('description'),
                $this->input('start_date'), $this->input('end_date') ?: null,
                $this->input('location'), $this->input('status') ?? 'scheduled'
            ]
        );
        
        Session::flash('success', 'Rendez-vous créé.');
        $this->redirect(SITE_URL . '/admin/agenda');
    }
    
    public function edit(string $id): void
    {
        $appointment = Database::query("SELECT * FROM appointments WHERE id = ?", [(int)$id])[0] ?? null;
        if (!$appointment) { \Core\Router::notFound(); return; }
        
        $users = Database::query("SELECT id, CONCAT(first_name, ' ', last_name) as name FROM users WHERE deleted_at IS NULL ORDER BY first_name");
        View::layout('admin', ['page_title' => 'Modifier le rendez-vous']);
        $this->view('admin/agenda/form', ['appointment' => $appointment, 'users' => $users, 'mode' => 'edit']);
    }
    
    public function update(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        Database::execute(
            "UPDATE appointments SET user_id = ?, title = ?, description = ?, start_date = ?, end_date = ?, location = ?, status = ?, updated_at = NOW() WHERE id = ?",
            [
                $this->input('user_id') ?: null, $this->input('title'), $this->input('description'),
                $this->input('start_date'), $this->input('end_date') ?: null,
                $this->input('location'), $this->input('status'), (int)$id
            ]
        );
        
        Session::flash('success', 'Rendez-vous modifié.');
        $this->redirect(SITE_URL . '/admin/agenda');
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("DELETE FROM appointments WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Rendez-vous supprimé.');
        $this->redirect(SITE_URL . '/admin/agenda');
    }
}
