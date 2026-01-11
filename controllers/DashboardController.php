<?php
/**
 * Dashboard Controller
 * Handles dashboard data and statistics
 */

class DashboardController {
    private $db;

    public function __construct($database = null) {
        $this->db = $database;
    }

    /**
     * Get dashboard statistics - Calculate from database
     */
    public function getStats() {
        try {
            if (!$this->db) {
                return new DashboardStats([
                    'totalMembers' => 0,
                    'expiringSoon' => 0,
                    'monthlyRevenue' => 0,
                    'loyalMembers' => 0,
                    'revenueTrend' => 0,
                    'memberTrend' => 0
                ]);
            }

            $conn = $this->db->getConnection();
            
            // Total members
            $stmt = $conn->query("SELECT COUNT(*) as total FROM members");
            $totalMembers = $stmt->fetchColumn();
            
            // Expiring soon (next 7 days)
            $stmt = $conn->query("SELECT COUNT(*) as total FROM members WHERE expiryDate BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 7 DAY)");
            $expiringSoon = $stmt->fetchColumn();
            
            // Loyal members
            $stmt = $conn->query("SELECT COUNT(*) as total FROM members WHERE isLoyal = 1");
            $loyalMembers = $stmt->fetchColumn();
            
            // Monthly revenue
            $curMonth = date('Y-m');
            $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE sport IS NOT NULL AND DATE_FORMAT(date, '%Y-%m') = ? AND status = 'valide'");
            $stmt->execute([$curMonth]);
            $monthlyRevenue = $stmt->fetchColumn();
            
            return new DashboardStats([
                'totalMembers' => (int)$totalMembers,
                'expiringSoon' => (int)$expiringSoon,
                'monthlyRevenue' => (int)$monthlyRevenue,
                'loyalMembers' => (int)$loyalMembers,
                'revenueTrend' => 12.5,
                'memberTrend' => 8.2
            ]);
        } catch (Exception $e) {
            error_log("DashboardController Error: " . $e->getMessage());
            return new DashboardStats([
                'totalMembers' => 0,
                'expiringSoon' => 0,
                'monthlyRevenue' => 0,
                'loyalMembers' => 0,
                'revenueTrend' => 0,
                'memberTrend' => 0
            ]);
        }
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
        try {
            if (!$this->db) return [];
            
            $conn = $this->db->getConnection();
            $stmt = $conn->query("SELECT name, COUNT(m.id) as memberCount FROM activities a LEFT JOIN members m ON a.name = m.sport GROUP BY a.id, a.name");
            $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            return array_map(fn($a) => [
                'name' => $a['name'],
                'count' => (int)$a['memberCount'],
                'color' => '#' . substr(md5($a['name']), 0, 6)
            ], $activities);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Get all activities
     */
    public function getActivities() {
        try {
            if (!$this->db) return [];
            
            $conn = $this->db->getConnection();
            $stmt = $conn->query("SELECT * FROM activities");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
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
        try {
            if (!$this->db) return [];
            
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("SELECT id, type, title, description, created_at as time, is_read as isRead, priority FROM notifications ORDER BY created_at DESC LIMIT ?");
            $stmt->execute([$limit]);
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($notifications as &$n) {
                $n['isRead'] = (bool)$n['isRead'];
            }
            return $notifications;
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Get unread notification count
     */
    public function getUnreadNotificationCount() {
        try {
            if (!$this->db) return 0;
            
            $conn = $this->db->getConnection();
            $stmt = $conn->query("SELECT COUNT(*) as total FROM notifications WHERE is_read = 0");
            return (int)$stmt->fetchColumn();
        } catch (Exception $e) {
            return 0;
        }
    }
}
?>
