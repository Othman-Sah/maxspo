<?php
header('Content-Type: application/json');
require_once '../config/config.php';
require_once '../controllers/SettingsController.php';

requireLogin();

$method = $_SERVER['REQUEST_METHOD'];
$controller = new SettingsController($db);

try {
    if ($method === 'GET') {
        $action = getParam('action', '');
        
        if ($action === 'all') {
            echo json_encode($controller->getAllSettings());
        } else if ($action === 'general') {
            echo json_encode($controller->getGeneralSettings());
        } else if ($action === 'profile') {
            echo json_encode($controller->getProfileInfo());
        } else if ($action === 'theme') {
            echo json_encode($controller->getThemeSettings());
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
        }
    } else if ($method === 'POST') {
        $data = json_decode(file_get_contents('php://input'), true);
        $action = $data['action'] ?? '';
        
        if ($action === 'save_general') {
            $result = $controller->saveGeneralSettings(
                $data['clubName'] ?? '',
                $data['slogan'] ?? '',
                $data['language'] ?? 'fr',
                $data['timezone'] ?? ''
            );
            echo json_encode($result);
        } else if ($action === 'save_branding') {
            $result = $controller->saveBrandingSettings(
                $data['themeColor'] ?? 'indigo',
                $data['logo'] ?? null
            );
            echo json_encode($result);
        } else if ($action === 'save_profile') {
            $result = $controller->saveProfileInfo(
                $data['name'] ?? '',
                $data['email'] ?? '',
                $data['city'] ?? ''
            );
            echo json_encode($result);
        } else if ($action === 'save_payments') {
            $result = $controller->savePaymentSettings(
                $data['currency'] ?? 'DH',
                $data['taxRate'] ?? 0
            );
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid action']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>
