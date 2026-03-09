<?php
/**
 * TSILIZY LLC — Front Controller
 * All requests are routed through this file
 */

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Start session
session_start();

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load configuration
require_once BASE_PATH . '/config/app.php';
require_once BASE_PATH . '/config/database.php';

// Load core classes
require_once BASE_PATH . '/core/App.php';
require_once BASE_PATH . '/core/Router.php';
require_once BASE_PATH . '/core/Controller.php';
require_once BASE_PATH . '/core/Model.php';
require_once BASE_PATH . '/core/View.php';
require_once BASE_PATH . '/core/Session.php';
require_once BASE_PATH . '/core/CSRF.php';
require_once BASE_PATH . '/core/Security.php';
require_once BASE_PATH . '/core/Auth.php';
require_once BASE_PATH . '/core/Database.php';

// Load routes
require_once BASE_PATH . '/config/routes.php';

// Initialize application
$app = new Core\App();
$app->run();
