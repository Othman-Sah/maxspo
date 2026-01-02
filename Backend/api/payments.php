<?php
require_once '../config/config.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle Add Payment
    $data = json_decode(file_get_contents("php://input"), true);
    if (!$data) $data = $_POST;

    if (!empty($data)) {
        try {
            $sql = "INSERT INTO payments (member_id, sport, amount, date, method, status) 
                    VALUES (:memberId, :sport, :amount, :date, :method, :status)";
            
            $stmt = $conn->prepare($sql);
            
            $stmt->execute([
                ':memberId' => $data['memberId'] ?? null,
                ':sport' => $data['sport'] ?? '',
                ':amount' => $data['amount'] ?? 0,
                ':date' => $data['date'] ?? date('Y-m-d'),
                ':method' => $data['method'] ?? 'especes',
                ':status' => $data['status'] ?? 'valide'
            ]);

            echo json_encode(['success' => true, 'message' => 'Payment recorded successfully', 'id' => $conn->lastInsertId()]);
        } catch (PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
        }
    } else {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'No data provided']);
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Handle Get Payments
    try {
        $stmt = $conn->query("SELECT p.id, p.member_id as memberId, CONCAT(m.firstName, ' ', m.lastName) as memberName, p.sport, p.amount, p.date, p.method, p.status 
                            FROM payments p 
                            LEFT JOIN members m ON p.member_id = m.id 
                            ORDER BY p.date DESC");
        $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($payments);
    } catch (PDOException $e) {
        echo json_encode([]);
    }
}
?>