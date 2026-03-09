<?php
/**
 * TSILIZY LLC — Application Bootstrap
 */

namespace Core;

class App
{
    /**
     * Run the application
     */
    public function run(): void
    {
        // Check maintenance mode
        if (MAINTENANCE_MODE && !$this->isAdmin()) {
            $this->showMaintenance();
            return;
        }

        // Get request URI and method
        $uri = $this->getUri();
        $method = $_SERVER['REQUEST_METHOD'];

        // Dispatch the route
        Router::dispatch($uri, $method);
    }

    /**
     * Get the request URI
     */
    private function getUri(): string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }

        // Remove base path from URI
        $basePath = parse_url(SITE_URL, PHP_URL_PATH) ?? '';
        if ($basePath && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
        }

        // Ensure URI starts with /
        $uri = '/' . ltrim($uri, '/');

        return $uri;
    }

    /**
     * Check if current user is admin
     */
    private function isAdmin(): bool
    {
        return Session::get('user_role') === 'admin' || Session::get('user_role') === 'super_admin';
    }

    /**
     * Show maintenance page
     */
    private function showMaintenance(): void
    {
        http_response_code(503);
        View::render('errors/maintenance');
        exit;
    }
}
