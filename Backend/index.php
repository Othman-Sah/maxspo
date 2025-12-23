<?php
/**
 * NEEDSPORT Pro - Main Application Router
 * Entry point for all requests
 */

require_once 'config/config.php';

// Handle logout action
if (getParam('action') === 'logout') {
    logout();
}

// Get current page
$page = getParam('page', 'login');

// Check authentication for protected pages
$publicPages = ['login'];
if (!in_array($page, $publicPages) && !isLoggedIn()) {
    header('Location: index.php?page=login');
    exit;
}

// Route to appropriate view
$viewPath = VIEWS_PATH . '/' . $page . '.php';

if (file_exists($viewPath)) {
    include $viewPath;
} else {
    // If page not found, show dashboard for authenticated users or login for guests
    if (isLoggedIn()) {
        include VIEWS_PATH . '/dashboard.php';
    } else {
        include VIEWS_PATH . '/login.php';
    }
}
?>
