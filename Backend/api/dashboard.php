<?php
/**
 * API Endpoint: Dashboard
 * Handles dashboard data requests
 */

header('Content-Type: application/json');
require_once '../config/config.php';
require_once '../controllers/DashboardController.php';

$action = getParam('action');

try {
    $controller = new DashboardController();

    switch ($action) {
        case 'stats':
            $stats = $controller->getStats();
            jsonResponse(['success' => true, 'data' => $stats->toArray()]);
            break;

        case 'revenue':
            $data = $controller->getRevenueData();
            jsonResponse(['success' => true, 'data' => $data]);
            break;

        case 'sports':
            $data = $controller->getSportStats();
            jsonResponse(['success' => true, 'data' => $data]);
            break;

        case 'activities':
            $data = $controller->getActivities();
            jsonResponse(['success' => true, 'data' => $data]);
            break;

        case 'payment-methods':
            $data = $controller->getPaymentMethodStats();
            jsonResponse(['success' => true, 'data' => $data]);
            break;

        case 'notifications':
            $limit = intval(getParam('limit', 5));
            $data = $controller->getNotifications($limit);
            $unread = $controller->getUnreadNotificationCount();
            jsonResponse(['success' => true, 'data' => $data, 'unreadCount' => $unread]);
            break;

        default:
            jsonResponse(['success' => false, 'error' => 'Unknown action'], 400);
    }

} catch (Exception $e) {
    jsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
}
?>
