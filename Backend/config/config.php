<?php
/**
 * NEEDSPORT Pro - Configuration File
 * Main application settings and database configuration
 */

session_start();

// Define application paths
define('ROOT_PATH', dirname(dirname(__FILE__)));
define('CONFIG_PATH', ROOT_PATH . '/config');
define('CONTROLLERS_PATH', ROOT_PATH . '/controllers');
define('COMPONENTS_PATH', ROOT_PATH . '/components');
define('HELPERS_PATH', ROOT_PATH . '/helpers');
define('API_PATH', ROOT_PATH . '/api');
define('VIEWS_PATH', ROOT_PATH . '/views');

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'root');
define('DB_NAME', 'needsport_pro');
define('DB_PORT', 3306);

// Application Settings
define('APP_NAME', 'NEEDSPORT Pro');
define('APP_VERSION', '2.4.0');
define('APP_TIMEZONE', 'Africa/Casablanca');
define('APP_CURRENCY', 'DH');

// Language
define('DEFAULT_LANGUAGE', 'fr');

// Security
define('SESSION_TIMEOUT', 3600); // 1 hour
define('ADMIN_ROLE', 'admin');
define('STAFF_ROLE', 'staff');

// API Settings
define('API_URL', 'http://localhost/lA/Backend/api');
define('UPLOAD_DIR', ROOT_PATH . '/public/uploads');

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', ROOT_PATH . '/logs/error.log');

// Set timezone
date_default_timezone_set(APP_TIMEZONE);

// Include essential files
require_once CONFIG_PATH . '/Database.php';
require_once HELPERS_PATH . '/functions.php';
require_once HELPERS_PATH . '/Validator.php';

// Initialize Database
try {
    $db = new Database();
    $db->connect();
} catch (Exception $e) {
    die('Database connection failed: ' . $e->getMessage());
}

// Check authentication
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['admin_role']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /lA/Backend/index.php?page=login');
        exit();
    }
}

function logout() {
    session_destroy();
    header('Location: /lA/Backend/index.php?page=login');
    exit();
}
?>
