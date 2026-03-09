<?php
/**
 * TSILIZY LLC — Base Controller
 */

namespace Core;

abstract class Controller
{
    protected array $data = [];

    /**
     * Constructor
     */
    public function __construct()
    {
        // Set default view data
        $this->data['site_name'] = SITE_NAME;
        $this->data['site_url'] = SITE_URL;
        $this->data['csrf_token'] = CSRF::generate();
        $this->data['current_user'] = Auth::user();
    }

    /**
     * Render a view
     */
    protected function view(string $template, array $data = []): void
    {
        View::render($template, array_merge($this->data, $data));
    }

    /**
     * Redirect to URL
     */
    protected function redirect(string $url): void
    {
        Router::redirect($url);
    }

    /**
     * Redirect back
     */
    protected function back(): void
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? SITE_URL;
        $this->redirect($referer);
    }

    /**
     * Return JSON response
     */
    protected function json(array $data, int $code = 200): void
    {
        http_response_code($code);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Get POST data
     */
    protected function input(string $key, $default = null)
    {
        return Security::clean($_POST[$key] ?? $default);
    }

    /**
     * Get GET parameter
     */
    protected function query(string $key, $default = null)
    {
        return Security::clean($_GET[$key] ?? $default);
    }

    /**
     * Validate CSRF token
     */
    protected function validateCsrf(): bool
    {
        $token = $_POST[CSRF_TOKEN_NAME] ?? '';
        
        if (!CSRF::validate($token)) {
            Session::flash('error', 'Token de sécurité invalide. Veuillez réessayer.');
            $this->back();
            return false;
        }

        return true;
    }

    /**
     * Validate required fields
     */
    protected function validate(array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $rule) {
            $value = $_POST[$field] ?? null;
            $ruleList = explode('|', $rule);

            foreach ($ruleList as $r) {
                $parts = explode(':', $r);
                $ruleName = $parts[0];
                $ruleParam = $parts[1] ?? null;

                switch ($ruleName) {
                    case 'required':
                        if (empty($value)) {
                            $errors[$field] = 'Ce champ est requis.';
                        }
                        break;
                    case 'email':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field] = 'Adresse email invalide.';
                        }
                        break;
                    case 'min':
                        if (!empty($value) && strlen($value) < (int)$ruleParam) {
                            $errors[$field] = "Minimum {$ruleParam} caractères requis.";
                        }
                        break;
                    case 'max':
                        if (!empty($value) && strlen($value) > (int)$ruleParam) {
                            $errors[$field] = "Maximum {$ruleParam} caractères autorisés.";
                        }
                        break;
                    case 'numeric':
                        if (!empty($value) && !is_numeric($value)) {
                            $errors[$field] = 'Valeur numérique requise.';
                        }
                        break;
                    case 'url':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_URL)) {
                            $errors[$field] = 'URL invalide.';
                        }
                        break;
                }

                // Stop checking rules if field already has error
                if (isset($errors[$field])) {
                    break;
                }
            }
        }

        return $errors;
    }

    /**
     * Check if user is authenticated
     */
    protected function requireAuth(): void
    {
        if (!Auth::check()) {
            Session::flash('error', 'Veuillez vous connecter pour accéder à cette page.');
            $this->redirect(SITE_URL . '/connexion');
        }
    }

    /**
     * Check if user is admin
     */
    protected function requireAdmin(): void
    {
        // First check if authenticated at all
        if (!Auth::check()) {
            Session::flash('error', 'Votre session a expiré. Veuillez vous reconnecter.');
            $this->redirect(SITE_URL . '/connexion?redirect=' . urlencode($_SERVER['REQUEST_URI']));
            exit;
        }
        
        // Then check if admin
        if (!Auth::isAdmin()) {
            Router::forbidden();
        }
    }

    /**
     * Check if user has permission
     */
    protected function requirePermission(string $permission): void
    {
        if (!Auth::can($permission)) {
            Router::forbidden();
        }
    }

    /**
     * Set flash message
     */
    protected function flash(string $type, string $message): void
    {
        Session::flash($type, $message);
    }

    /**
     * Get uploaded file
     */
    protected function file(string $key): ?array
    {
        if (!isset($_FILES[$key]) || $_FILES[$key]['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        return $_FILES[$key];
    }

    /**
     * Upload a file
     */
    protected function uploadFile(array $file, string $directory = ''): ?string
    {
        // Validate file
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Check file size
        if ($file['size'] > MAX_UPLOAD_SIZE) {
            return null;
        }

        // Get file extension
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Check extension
        if (!in_array($ext, ALLOWED_EXTENSIONS)) {
            return null;
        }

        // Generate unique filename
        $filename = uniqid() . '_' . time() . '.' . $ext;

        // Create directory if needed
        $uploadDir = UPLOAD_PATH . $directory;
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Move file
        $destination = $uploadDir . '/' . $filename;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $directory . '/' . $filename;
        }

        return null;
    }
}
