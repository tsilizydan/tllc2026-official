<?php
/**
 * TSILIZY LLC — View Renderer
 */

namespace Core;

class View
{
    private static array $sections = [];
    private static ?string $currentSection = null;
    private static ?string $layout = null;
    private static array $layoutData = [];

    /**
     * Render a view
     */
    public static function render(string $template, array $data = []): void
    {
        $viewFile = BASE_PATH . '/resources/views/' . str_replace('.', '/', $template) . '.php';

        if (!file_exists($viewFile)) {
            echo "Vue non trouvée: {$template}";
            return;
        }

        // Extract data to variables
        extract($data);

        // Start output buffering
        ob_start();
        include $viewFile;
        $content = ob_get_clean();

        // If a layout is set, render it with the content
        if (self::$layout !== null) {
            $layoutFile = BASE_PATH . '/resources/views/layouts/' . self::$layout . '.php';
            
            if (file_exists($layoutFile)) {
                $layoutData = array_merge($data, self::$layoutData, ['content' => $content]);
                extract($layoutData);
                include $layoutFile;
            } else {
                echo $content;
            }
            
            // Reset layout for next render
            self::$layout = null;
            self::$layoutData = [];
        } else {
            echo $content;
        }

        // Reset sections
        self::$sections = [];
    }

    /**
     * Set the layout to use
     */
    public static function layout(string $layout, array $data = []): void
    {
        self::$layout = $layout;
        self::$layoutData = $data;
    }

    /**
     * Start a section
     */
    public static function section(string $name): void
    {
        self::$currentSection = $name;
        ob_start();
    }

    /**
     * End a section
     */
    public static function endSection(): void
    {
        if (self::$currentSection !== null) {
            self::$sections[self::$currentSection] = ob_get_clean();
            self::$currentSection = null;
        }
    }

    /**
     * Get a section's content
     */
    public static function yield(string $name, string $default = ''): string
    {
        return self::$sections[$name] ?? $default;
    }

    /**
     * Include a partial view
     */
    public static function partial(string $partial, array $data = []): void
    {
        $partialFile = BASE_PATH . '/resources/views/partials/' . str_replace('.', '/', $partial) . '.php';

        if (file_exists($partialFile)) {
            extract($data);
            include $partialFile;
        }
    }

    /**
     * Escape HTML entities
     */
    public static function e($value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8', true);
    }

    /**
     * Format date in French
     */
    public static function date(?string $date, string $format = 'd/m/Y'): string
    {
        if (!$date) {
            return '';
        }
        return date($format, strtotime($date));
    }

    /**
     * Format datetime in French
     */
    public static function datetime(?string $datetime, string $format = 'd/m/Y H:i'): string
    {
        if (!$datetime) {
            return '';
        }
        return date($format, strtotime($datetime));
    }

    /**
     * Format number
     */
    public static function number($value, int $decimals = 0): string
    {
        return number_format((float)$value, $decimals, ',', ' ');
    }

    /**
     * Format currency
     */
    public static function currency($value, string $symbol = '€'): string
    {
        return self::number($value, 2) . ' ' . $symbol;
    }

    /**
     * Truncate text
     */
    public static function truncate(string $text, int $length = 100, string $suffix = '...'): string
    {
        if (mb_strlen($text) <= $length) {
            return $text;
        }
        return mb_substr($text, 0, $length) . $suffix;
    }

    /**
     * Convert text to slug
     */
    public static function slug(string $text): string
    {
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        $text = preg_replace('~[^-\w]+~', '', $text);
        $text = trim($text, '-');
        $text = preg_replace('~-+~', '-', $text);
        $text = strtolower($text);
        return $text ?: 'n-a';
    }

    /**
     * Get asset URL
     */
    public static function asset(string $path): string
    {
        return SITE_URL . '/assets/' . ltrim($path, '/');
    }

    /**
     * Get upload URL
     */
    public static function upload(string $path): string
    {
        return SITE_URL . '/uploads/' . ltrim($path, '/');
    }

    /**
     * Get URL
     */
    public static function url(string $path = ''): string
    {
        return SITE_URL . '/' . ltrim($path, '/');
    }

    /**
     * Get current URL
     */
    public static function currentUrl(): string
    {
        return (isset($_SERVER['HTTPS']) ? 'https' : 'http') . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
    }

    /**
     * Check if current URL matches
     */
    public static function isActive(string $path): bool
    {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $basePath = parse_url(SITE_URL, PHP_URL_PATH) ?? '';
        $currentPath = str_replace($basePath, '', $currentPath);
        
        return $currentPath === '/' . ltrim($path, '/');
    }

    /**
     * Add active class if URL matches
     */
    public static function activeClass(string $path, string $class = 'active'): string
    {
        return self::isActive($path) ? $class : '';
    }
    
    /**
     * Format time ago in French
     */
    public static function timeAgo(?string $datetime): string
    {
        if (!$datetime) return '';
        
        $timestamp = strtotime($datetime);
        $diff = time() - $timestamp;
        
        if ($diff < 60) {
            return 'À l\'instant';
        } elseif ($diff < 3600) {
            $mins = floor($diff / 60);
            return 'Il y a ' . $mins . ' minute' . ($mins > 1 ? 's' : '');
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return 'Il y a ' . $hours . ' heure' . ($hours > 1 ? 's' : '');
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return 'Il y a ' . $days . ' jour' . ($days > 1 ? 's' : '');
        } elseif ($diff < 2592000) {
            $weeks = floor($diff / 604800);
            return 'Il y a ' . $weeks . ' semaine' . ($weeks > 1 ? 's' : '');
        } else {
            return self::date($datetime);
        }
    }
}
