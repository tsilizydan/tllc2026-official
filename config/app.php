<?php
/**
 * TSILIZY LLC — Application Configuration
 */

// Detect protocol (HTTP or HTTPS)
$isSecure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') 
         || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
         || (!empty($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == 443);
$protocol = $isSecure ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Site Information
define('SITE_NAME', 'TSILIZY LLC');
define('SITE_TAGLINE', 'Excellence & Innovation');
define('SITE_URL', rtrim($protocol . $host, '/'));
define('SITE_EMAIL', 'contact@tsilizy.com');
define('SITE_PHONE', '+33 1 23 45 67 89');
define('SITE_ADDRESS', 'Paris, France');

// Language
define('SITE_LANG', 'fr');
define('SITE_CHARSET', 'UTF-8');

// Timezone
date_default_timezone_set('Europe/Paris');

// Security
define('HASH_COST', 12);
define('SESSION_LIFETIME', 3600); // 1 hour
define('CSRF_TOKEN_NAME', '_csrf_token');
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_LOCKOUT_TIME', 900); // 15 minutes

// Upload Settings
define('UPLOAD_PATH', BASE_PATH . '/storage/uploads/');
define('MAX_UPLOAD_SIZE', 10 * 1024 * 1024); // 10MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx']);

// Pagination
define('ITEMS_PER_PAGE', 12);
define('ADMIN_ITEMS_PER_PAGE', 20);

// Feature Toggles
define('ENABLE_REGISTRATION', true);
define('ENABLE_REVIEWS', true);
define('ENABLE_NEWSLETTER', true);
define('ENABLE_JOBS', true);
define('ENABLE_SURVEYS', true);
define('MAINTENANCE_MODE', false);
