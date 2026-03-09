<?php
/**
 * TSILIZY LLC — Admin Events Controller
 */

namespace App\Controllers\Admin;

use Core\Controller;
use Core\View;
use Core\Auth;
use Core\Database;
use Core\Security;
use Core\Session;

class EventController extends Controller
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
        
        $events = Database::query(
            "SELECT * FROM events WHERE deleted_at IS NULL ORDER BY start_date DESC LIMIT ? OFFSET ?",
            [ADMIN_ITEMS_PER_PAGE, $offset]
        );
        
        $total = Database::query("SELECT COUNT(*) as count FROM events WHERE deleted_at IS NULL")[0]['count'];
        
        View::layout('admin', ['page_title' => 'Événements']);
        $this->view('admin/events/index', [
            'page_title' => 'Événements',
            'events' => $events,
            'total' => $total,
            'current_page' => $page,
            'last_page' => (int)ceil($total / ADMIN_ITEMS_PER_PAGE)
        ]);
    }
    
    public function create(): void
    {
        View::layout('admin', ['page_title' => 'Nouvel événement']);
        $this->view('admin/events/form', ['event' => null, 'mode' => 'create']);
    }
    
    public function store(): void
    {
        if (!$this->validateCsrf()) return;
        
        $errors = $this->validate([
            'title' => 'required|min:3|max:255',
            'description' => 'required|min:20',
            'start_date' => 'required'
        ]);
        
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs.');
            Session::set('validation_errors', $errors);
            Session::flashInput();
            $this->back();
            return;
        }
        
        $slug = Security::slug($this->input('title'));
        $existing = Database::query("SELECT id FROM events WHERE slug = ? AND deleted_at IS NULL", [$slug]);
        if (!empty($existing)) $slug .= '-' . time();
        
        $image = null;
        if ($file = $this->file('image')) $image = $this->uploadFile($file, 'events');
        
        Database::insert(
            "INSERT INTO events (title, slug, description, short_description, location, address, start_date, end_date, image, category, max_attendees, registration_deadline, is_online, meeting_url, price, status, is_featured, seo_title, seo_description, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())",
            [
                $this->input('title'), $slug, Security::cleanHtml($_POST['description']), $this->input('short_description'),
                $this->input('location'), $this->input('address'),
                $this->input('start_date'), $this->input('end_date') ?: null,
                $image, $this->input('category'), $this->input('max_attendees') ?: null,
                $this->input('registration_deadline') ?: null, isset($_POST['is_online']) ? 1 : 0,
                $this->input('meeting_url'), $this->input('price') ?: null,
                $this->input('status') ?? 'draft', isset($_POST['is_featured']) ? 1 : 0,
                $this->input('seo_title'), $this->input('seo_description')
            ]
        );
        
        Session::flash('success', 'Événement créé avec succès.');
        $this->redirect(SITE_URL . '/admin/evenements');
    }
    
    public function edit(string $id): void
    {
        $event = Database::query("SELECT * FROM events WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$event) { \Core\Router::notFound(); return; }
        
        View::layout('admin', ['page_title' => 'Modifier l\'événement']);
        $this->view('admin/events/form', ['event' => $event, 'mode' => 'edit']);
    }
    
    public function update(string $id): void
    {
        if (!$this->validateCsrf()) return;
        
        $event = Database::query("SELECT * FROM events WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$event) { \Core\Router::notFound(); return; }
        
        $image = $event['image'];
        if ($file = $this->file('image')) {
            $newImage = $this->uploadFile($file, 'events');
            if ($newImage) $image = $newImage;
        }
        
        Database::execute(
            "UPDATE events SET title = ?, description = ?, short_description = ?, location = ?, address = ?, start_date = ?, end_date = ?, image = ?, category = ?, max_attendees = ?, registration_deadline = ?, is_online = ?, meeting_url = ?, price = ?, status = ?, is_featured = ?, seo_title = ?, seo_description = ?, updated_at = NOW() WHERE id = ?",
            [
                $this->input('title'), Security::cleanHtml($_POST['description']), $this->input('short_description'),
                $this->input('location'), $this->input('address'),
                $this->input('start_date'), $this->input('end_date') ?: null,
                $image, $this->input('category'), $this->input('max_attendees') ?: null,
                $this->input('registration_deadline') ?: null, isset($_POST['is_online']) ? 1 : 0,
                $this->input('meeting_url'), $this->input('price') ?: null,
                $this->input('status') ?? 'draft', isset($_POST['is_featured']) ? 1 : 0,
                $this->input('seo_title'), $this->input('seo_description'), (int)$id
            ]
        );
        
        Session::flash('success', 'Événement modifié avec succès.');
        $this->redirect(SITE_URL . '/admin/evenements');
    }
    
    public function destroy(string $id): void
    {
        if (!$this->validateCsrf()) return;
        Database::execute("UPDATE events SET deleted_at = NOW() WHERE id = ?", [(int)$id]);
        Session::flash('success', 'Événement supprimé.');
        $this->redirect(SITE_URL . '/admin/evenements');
    }
    
    public function registrations(string $id): void
    {
        $event = Database::query("SELECT * FROM events WHERE id = ? AND deleted_at IS NULL", [(int)$id])[0] ?? null;
        if (!$event) { \Core\Router::notFound(); return; }
        
        $registrations = Database::query(
            "SELECT er.*, CONCAT(u.first_name, ' ', u.last_name) as user_name, u.email
             FROM event_registrations er
             LEFT JOIN users u ON er.user_id = u.id
             WHERE er.event_id = ?
             ORDER BY er.created_at DESC",
            [(int)$id]
        );
        
        View::layout('admin', ['page_title' => 'Inscriptions']);
        $this->view('admin/events/registrations', [
            'event' => $event,
            'registrations' => $registrations
        ]);
    }
}
