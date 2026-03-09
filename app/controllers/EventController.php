<?php
/**
 * TSILIZY LLC — Events Controller
 */

namespace App\Controllers;

use Core\Controller;
use Core\View;
use Core\Database;

class EventController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    
    /**
     * List all events
     */
    public function index(): void
    {
        $upcoming = Database::query(
            "SELECT * FROM events WHERE status = 'published' AND start_date >= NOW() AND deleted_at IS NULL ORDER BY start_date ASC"
        );
        
        $past = Database::query(
            "SELECT * FROM events WHERE status = 'published' AND start_date < NOW() AND deleted_at IS NULL ORDER BY start_date DESC LIMIT 8"
        );
        
        View::layout('public', ['page_title' => 'Événements']);
        $this->view('public/events/index', [
            'upcoming' => $upcoming,
            'past' => $past
        ]);
    }
    
    /**
     * Show single event
     */
    public function show(string $slug): void
    {
        $event = Database::query(
            "SELECT * FROM events WHERE slug = ? AND status = 'published' AND deleted_at IS NULL",
            [$slug]
        )[0] ?? null;
        
        if (!$event) {
            \Core\Router::notFound();
            return;
        }
        
        // Get related events
        $related = Database::query(
            "SELECT * FROM events WHERE id != ? AND status = 'published' AND start_date >= NOW() AND deleted_at IS NULL ORDER BY start_date ASC LIMIT 3",
            [$event['id']]
        );
        
        View::layout('public', ['page_title' => $event['title']]);
        $this->view('public/events/show', [
            'event' => $event,
            'related' => $related
        ]);
    }
}
