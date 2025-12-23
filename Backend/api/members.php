<?php
/**
 * API Endpoint: Members
 * Handles AJAX requests for member operations
 */

header('Content-Type: application/json');
require_once '../config/config.php';
require_once '../controllers/MembersController.php';

$method = $_SERVER['REQUEST_METHOD'];
$action = getParam('action');

try {
    $controller = new MembersController($db);

    switch ($action) {
        case 'list':
            $filters = [
                'sport' => postParam('sport'),
                'status' => postParam('status'),
                'search' => postParam('search')
            ];
            $members = $controller->getAll($filters);
            jsonResponse(['success' => true, 'data' => $members]);
            break;

        case 'get':
            $id = getParam('id');
            $member = $controller->getById($id);
            if ($member) {
                jsonResponse(['success' => true, 'data' => $member]);
            } else {
                jsonResponse(['success' => false, 'error' => 'Membre non trouvé'], 404);
            }
            break;

        case 'create':
            if ($method !== 'POST') {
                jsonResponse(['success' => false, 'error' => 'Invalid method'], 405);
            }
            $result = $controller->create($_POST);
            jsonResponse($result, $result['success'] ? 201 : 400);
            break;

        case 'update':
            if ($method !== 'POST') {
                jsonResponse(['success' => false, 'error' => 'Invalid method'], 405);
            }
            $id = getParam('id');
            $result = $controller->update($id, $_POST);
            jsonResponse($result, $result['success'] ? 200 : 400);
            break;

        case 'delete':
            if ($method !== 'POST') {
                jsonResponse(['success' => false, 'error' => 'Invalid method'], 405);
            }
            $id = getParam('id');
            $result = $controller->delete($id);
            jsonResponse($result);
            break;

        case 'renew':
            if ($method !== 'POST') {
                jsonResponse(['success' => false, 'error' => 'Invalid method'], 405);
            }
            $id = getParam('id');
            $duration = intval(postParam('duration', 1));
            $result = $controller->renew($id, $duration);
            jsonResponse($result, $result['success'] ? 200 : 400);
            break;

        case 'expiring':
            $members = $controller->getExpiringMembers();
            jsonResponse(['success' => true, 'data' => $members]);
            break;

        default:
            jsonResponse(['success' => false, 'error' => 'Unknown action'], 400);
    }

} catch (Exception $e) {
    jsonResponse(['success' => false, 'error' => $e->getMessage()], 500);
}
?>
