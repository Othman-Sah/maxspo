<?php
/**
 * Dashboard Controller
 * Handles dashboard data and statistics
 */

class DashboardController {
    private $mockData;

    public function __construct() {
        $this->mockData = require CONFIG_PATH . '/MockData.php';
    }

    /**
     * Get dashboard statistics
     */
    public function getStats() {
        return new DashboardStats($this->mockData['stats']);
    }

    /**
     * Get revenue data
     */
    public function getRevenueData() {
        $data = [];
        $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'];
        $baseAmount = 98000;
        
        foreach ($months as $month) {
            $data[] = [
                'month' => $month,
                'amount' => $baseAmount + rand(-5000, 20000),
                'expenses' => $baseAmount / 2 + rand(-2000, 5000)
            ];
        }
        return $data;
    }

    /**
     * Get sport statistics
     */
    public function getSportStats() {
        return array_map(fn($a) => [
            'name' => $a['name'],
            'count' => $a['memberCount'],
            'color' => substr($a['color'], 5, 7) // Extract color
        ], $this->mockData['activities']);
    }

    /**
     * Get all activities
     */
    public function getActivities() {
        return $this->mockData['activities'];
    }

    /**
     * Get payment methods stats
     */
    public function getPaymentMethodStats() {
        return [
            ['method' => 'Espèces', 'count' => 145, 'total' => 36250, 'percentage' => 42],
            ['method' => 'Carte', 'count' => 98, 'total' => 24500, 'percentage' => 28],
            ['method' => 'Virement', 'count' => 42, 'total' => 10500, 'percentage' => 12],
            ['method' => 'Chèque', 'count' => 62, 'total' => 15500, 'percentage' => 18],
        ];
    }

    /**
     * Get notifications
     */
    public function getNotifications($limit = 5) {
        return array_slice($this->mockData['notifications'], 0, $limit);
    }

    /**
     * Get unread notification count
     */
    public function getUnreadNotificationCount() {
        return count(array_filter($this->mockData['notifications'], fn($n) => !$n['isRead']));
    }
}
?>
