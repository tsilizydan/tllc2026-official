<?php
/**
 * TSILIZY LLC — Database Configuration
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'tsilscpx_tsilizy_llc');
define('DB_USER', 'tsilscpx_chibi_admin');
define('DB_PASS', '9@UPN~I@O]Dw');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATION', 'utf8mb4_unicode_ci');

// PDO Options
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
]);
