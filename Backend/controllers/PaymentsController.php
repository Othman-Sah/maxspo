<?php
/**
 * Payments Controller
 * Handles payment data
 */

class PaymentsController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    /**
     * Get all payments with filtering
     */
    public function getPayments($filters = []) {
        try {
            $conn = $this->db->getConnection();
            $sql = "SELECT p.id, p.member_id as memberId, CONCAT(m.firstName, ' ', m.lastName) as memberName, p.sport, p.amount, p.date, p.method, p.status FROM payments p LEFT JOIN members m ON p.member_id = m.id WHERE 1=1";
            
            if (!empty($filters['method']) && $filters['method'] !== 'all') {
                $sql .= " AND p.method = ?";
            }
            if (!empty($filters['status']) && $filters['status'] !== 'all') {
                $sql .= " AND p.status = ?";
            }
            if (!empty($filters['month'])) {
                $sql .= " AND DATE_FORMAT(p.date, '%Y-%m') = ?";
            }
            
            $sql .= " ORDER BY p.date DESC";
            $stmt = $conn->prepare($sql);
            
            $params = [];
            if (!empty($filters['method']) && $filters['method'] !== 'all') $params[] = $filters['method'];
            if (!empty($filters['status']) && $filters['status'] !== 'all') $params[] = $filters['status'];
            if (!empty($filters['month'])) $params[] = $filters['month'];
            
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get payment method statistics
     */
    public function getPaymentMethodStats() {
        // This would be calculated from a DB in a real app
        return [
            ['method' => 'Espèces', 'count' => 145, 'total' => 36250, 'percentage' => 42, 'color' => 'bg-emerald-500'],
            ['method' => 'Carte', 'count' => 98, 'total' => 24500, 'percentage' => 28, 'color' => 'bg-blue-500'],
            ['method' => 'Virement', 'count' => 42, 'total' => 10500, 'percentage' => 12, 'color' => 'bg-indigo-500'],
            ['method' => 'Chèque', 'count' => 62, 'total' => 15500, 'percentage' => 18, 'color' => 'bg-amber-500'],
        ];
    }
}
?>