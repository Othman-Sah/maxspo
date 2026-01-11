<?php
class FinancialsController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    public function getRevenueData() {
        try {
            $conn = $this->db->getConnection();
            $data = [];
            
            for ($i = 5; $i >= 0; $i--) {
                $month = date('Y-m', strtotime("-$i months"));
                $monthName = date('M', strtotime($month));
                
                $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE DATE_FORMAT(date, '%Y-%m') = ? AND status = 'valide'");
                $stmt->execute([$month]);
                $revenue = (int)$stmt->fetchColumn();
                
                $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) as total FROM expenses WHERE DATE_FORMAT(date, '%Y-%m') = ?");
                $stmt->execute([$month]);
                $expenses = (int)$stmt->fetchColumn();
                
                $data[] = [
                    'month' => $monthName,
                    'amount' => $revenue,
                    'expenses' => $expenses
                ];
            }
            
            return $data;
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function getExpenses() {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->query("SELECT id, category, description, amount, date, status FROM expenses ORDER BY date DESC LIMIT 50");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    public function getSummary() {
        try {
            $conn = $this->db->getConnection();
            
            $stmt = $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE status = 'valide'");
            $totalEarnings = (int)$stmt->fetchColumn();
            
            $stmt = $conn->query("SELECT COALESCE(SUM(amount), 0) as total FROM expenses");
            $totalExpenses = (int)$stmt->fetchColumn();
            
            $netProfit = $totalEarnings - $totalExpenses;

            return [
                'totalEarnings' => $totalEarnings,
                'totalExpenses' => $totalExpenses,
                'netProfit' => $netProfit,
            ];
        } catch (Exception $e) {
            return [
                'totalEarnings' => 0,
                'totalExpenses' => 0,
                'netProfit' => 0,
            ];
        }
    }
}
?>