<?php
/**
 * TSILIZY LLC — Database Configuration
 */

define('DB_HOST', 'sql105.byethost7.com');
define('DB_NAME', 'b7_40611962_tsilizy_llc');
define('DB_USER', 'b7_40611962');
define('DB_PASS', 'RamTsida@2898');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATION', 'utf8mb4_unicode_ci');

// PDO Options
define('DB_OPTIONS', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . DB_CHARSET
]);
