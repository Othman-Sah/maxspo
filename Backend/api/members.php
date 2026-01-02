<?php
require_once '../config/config.php';
require_once '../config/Models.php';
require_once '../helpers/functions.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

global $db;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Get all members with filters
    try {
        $conn = $db->getConnection();
        $sql = "SELECT id, firstName, lastName, email, phone, age, sport, status, expiryDate, joinDate, isLoyal FROM members ORDER BY id DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($members as &$m) {
            $m['isLoyal'] = (bool)$m['isLoyal'];
            $m['age'] = (int)$m['age'];
        }
        
        echo json_encode($members);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Add Member
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) $data = $_POST;

    if (!empty($data)) {
        try {
            $firstName = $data['firstName'] ?? $data['first_name'] ?? '';
            $lastName = $data['lastName'] ?? $data['last_name'] ?? '';
            $email = $data['email'] ?? '';
            $phone = $data['phone'] ?? '';
            $age = $data['age'] ?? 0;
            $sport = $data['sport'] ?? '';
            $joinDate = $data['joinDate'] ?? date('Y-m-d');
            $expiryDate = $data['expiryDate'] ?? date('Y-m-d', strtotime('+1 year'));
            $isLoyal = $data['isLoyal'] ?? 0;
            
            $conn = $db->getConnection();
            $stmt = $conn->prepare("INSERT INTO members (firstName, lastName, email, phone, age, sport, joinDate, expiryDate, isLoyal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstName, $lastName, $email, $phone, $age, $sport, $joinDate, $expiryDate, $isLoyal]);
            
            echo json_encode(['success' => true, 'message' => 'Member added successfully']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No data provided']);
    }
}
?>