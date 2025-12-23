<?php
/**
 * Payments Controller
 * Handles payment data
 */

class PaymentsController {
    private $db;
    private $mockData;

    public function __construct($database) {
        $this->db = $database;
        $this->mockData = require CONFIG_PATH . '/MockData.php';
    }

    /**
     * Get all payments with filtering
     */
    public function getPayments($filters = []) {
        $payments = $this->mockData['payments'] ?? [];

        if (!empty($filters['method']) && $filters['method'] !== 'all') {
            $payments = array_filter($payments, fn($p) => $p['method'] === $filters['method']);
        }
        if (!empty($filters['status']) && $filters['status'] !== 'all') {
            $payments = array_filter($payments, fn($p) => $p['status'] === $filters['status']);
        }
        if (!empty($filters['month'])) {
            $payments = array_filter($payments, fn($p) => strpos($p['date'], $filters['month']) === 0);
        }

        return $payments;
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