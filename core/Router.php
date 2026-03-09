<?php
/**
 * TSILIZY LLC — Router
 */

namespace Core;

class Router
{
    private static array $routes = [];
    private static array $namedRoutes = [];

    /**
     * Add a GET route
     */
    public static function get(string $uri, string $action, ?string $name = null): void
    {
        self::addRoute('GET', $uri, $action, $name);
    }

    /**
     * Add a POST route
     */
    public static function post(string $uri, string $action, ?string $name = null): void
    {
        self::addRoute('POST', $uri, $action, $name);
    }

    /**
     * Add a route
     */
    private static function addRoute(string $method, string $uri, string $action, ?string $name): void
    {
        $pattern = self::convertToRegex($uri);
        
        self::$routes[] = [
            'method' => $method,
            'uri' => $uri,
            'pattern' => $pattern,
            'action' => $action,
            'name' => $name
        ];

        if ($name) {
            self::$namedRoutes[$name] = $uri;
        }
    }

    /**
     * Convert URI pattern to regex
     */
    private static function convertToRegex(string $uri): string
    {
        // Escape forward slashes
        $pattern = preg_quote($uri, '/');
        
        // Convert {param} to named capture groups
        $pattern = preg_replace('/\\\{([a-zA-Z_]+)\\\}/', '(?P<$1>[^\/]+)', $pattern);
        
        return '/^' . $pattern . '$/';
    }

    /**
     * Dispatch the request
     */
    public static function dispatch(string $uri, string $method): void
    {
        foreach (self::$routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $uri, $matches)) {
                // Extract named parameters
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                // Parse controller and method
                [$controllerName, $methodName] = explode('@', $route['action']);
                
                // Determine if admin controller
                $isAdmin = strpos($controllerName, 'Admin\\') === 0;
                
                if ($isAdmin) {
                    $controllerClass = 'App\\Controllers\\' . $controllerName;
                    $controllerFile = BASE_PATH . '/app/controllers/' . str_replace('\\', '/', $controllerName) . '.php';
                } else {
                    $controllerClass = 'App\\Controllers\\' . $controllerName;
                    $controllerFile = BASE_PATH . '/app/controllers/' . $controllerName . '.php';
                }

                // Check if controller file exists
                if (!file_exists($controllerFile)) {
                    self::notFound();
                    return;
                }

                require_once $controllerFile;

                // Check if controller class exists
                if (!class_exists($controllerClass)) {
                    self::notFound();
                    return;
                }

                // Create controller instance
                $controller = new $controllerClass();

                // Check if method exists
                if (!method_exists($controller, $methodName)) {
                    self::notFound();
                    return;
                }

                // Call the controller method
                call_user_func_array([$controller, $methodName], $params);
                return;
            }
        }

        // No route matched
        self::notFound();
    }

    /**
     * Get URL for named route
     */
    public static function url(string $name, array $params = []): string
    {
        if (!isset(self::$namedRoutes[$name])) {
            return '#';
        }

        $uri = self::$namedRoutes[$name];

        foreach ($params as $key => $value) {
            $uri = str_replace('{' . $key . '}', $value, $uri);
        }

        return SITE_URL . $uri;
    }

    /**
     * Show 404 page
     */
    public static function notFound(): void
    {
        http_response_code(404);
        View::render('errors/404');
        exit;
    }

    /**
     * Show 403 page
     */
    public static function forbidden(): void
    {
        http_response_code(403);
        View::render('errors/403');
        exit;
    }

    /**
     * Show 500 page
     */
    public static function serverError(): void
    {
        http_response_code(500);
        View::render('errors/500');
        exit;
    }

    /**
     * Redirect to URL
     */
    public static function redirect(string $url): void
    {
        header('Location: ' . $url);
        exit;
    }
}
