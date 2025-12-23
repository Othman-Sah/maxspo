<?php
/**
 * Global Helper Functions
 */

/**
 * Format currency value
 */
function formatCurrency($amount, $currency = APP_CURRENCY) {
    return number_format($amount, 0, ',', ' ') . ' ' . $currency;
}

/**
 * Format date to French locale
 */
function formatDate($date, $format = 'd/m/Y') {
    if (is_string($date)) {
        $date = strtotime($date);
    }
    return date($format, $date);
}

/**
 * Sanitize input
 */
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

/**
 * Redirect to page
 */
function redirect($page, $params = []) {
    $url = '/lA/Backend/index.php?page=' . $page;
    if (!empty($params)) {
        $url .= '&' . http_build_query($params);
    }
    header('Location: ' . $url);
    exit();
}

/**
 * Get current user session
 */
function getCurrentUser() {
    return $_SESSION['user'] ?? null;
}

/**
 * Check if user has permission
 */
function hasPermission($permission) {
    $user = getCurrentUser();
    return $user && ($user['role'] === 'admin' || in_array($permission, $user['permissions'] ?? []));
}

/**
 * Generate CSRF token
 */
function generateCSRFToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verifyCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Return JSON response
 */
function jsonResponse($data, $statusCode = 200) {
    header('Content-Type: application/json');
    http_response_code($statusCode);
    echo json_encode($data);
    exit();
}

/**
 * Get query parameter safely
 */
function getParam($key, $default = null) {
    return isset($_GET[$key]) ? sanitizeInput($_GET[$key]) : $default;
}

/**
 * Get POST parameter safely
 */
function postParam($key, $default = null) {
    return isset($_POST[$key]) ? sanitizeInput($_POST[$key]) : $default;
}

/**
 * Calculate days until expiry
 */
function daysUntilExpiry($expiryDate) {
    $expiry = new DateTime($expiryDate);
    $today = new DateTime();
    $diff = $expiry->diff($today);
    return $diff->days;
}

/**
 * Check if membership is expiring soon (within 7 days)
 */
function isExpiringsSoon($expiryDate) {
    return daysUntilExpiry($expiryDate) <= 7 && daysUntilExpiry($expiryDate) >= 0;
}

/**
 * Check if membership is expired
 */
function isExpired($expiryDate) {
    return daysUntilExpiry($expiryDate) < 0;
}

/**
 * Get status badge for member
 */
function getMemberStatus($expiryDate) {
    if (isExpired($expiryDate)) {
        return 'expire';
    } elseif (isExpiringsSoon($expiryDate)) {
        return 'expirant';
    }
    return 'actif';
}

/**
 * Format status in French
 */
function formatStatus($status) {
    $statuses = [
        'actif' => 'Actif',
        'expirant' => 'Expire bientôt',
        'expire' => 'Expiré',
        'present' => 'Présent',
        'absent' => 'Absent',
        'en_pause' => 'En Pause',
        'valide' => 'Validé',
        'en_attente' => 'En attente',
        'annule' => 'Annulé',
        'paye' => 'Réglé',
        'prevu' => 'Prévu'
    ];
    return $statuses[$status] ?? $status;
}

/**
 * Log activity
 */
function logActivity($action, $details = '', $userId = null) {
    global $db;
    if (!$userId) {
        $userId = $_SESSION['user_id'] ?? null;
    }
    
    $stmt = $db->prepare("INSERT INTO activity_logs (user_id, action, details, created_at) VALUES (?, ?, ?, NOW())");
    if ($stmt) {
        $stmt->bind_param('iss', $userId, $action, $details);
        $stmt->execute();
        $stmt->close();
    }
}

/**
 * Generate random token
 */
function generateToken() {
    return bin2hex(random_bytes(16));
}

/**
 * Get file extension
 */
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Validate file upload
 */
function validateFileUpload($file, $allowedTypes = ['jpg', 'jpeg', 'png', 'pdf']) {
    if (empty($file['name'])) {
        return ['valid' => false, 'error' => 'No file selected'];
    }

    $ext = getFileExtension($file['name']);
    if (!in_array($ext, $allowedTypes)) {
        return ['valid' => false, 'error' => 'File type not allowed'];
    }

    if ($file['size'] > 5 * 1024 * 1024) { // 5MB
        return ['valid' => false, 'error' => 'File too large'];
    }

    return ['valid' => true];
}
?>
