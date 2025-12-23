<?php
/**
 * Financials Controller
 * Handles financial data like revenue and expenses
 */

class FinancialsController {
    private $db;
    private $mockData;

    public function __construct($database) {
        $this->db = $database;
        $this->mockData = require CONFIG_PATH . '/MockData.php';
    }

    /**
     * Get revenue data for the chart
     */
    public function getRevenueData() {
        return $this->mockData['revenue'] ?? [];
    }
    
    /**
     * Get recent expenses
     */
    public function getExpenses() {
        return $this->mockData['expenses'] ?? [];
    }

    /**
     * Get financial summary stats
     */
    public function getSummary() {
        $revenue = $this->getRevenueData();
        $totalEarnings = array_reduce($revenue, fn($sum, $item) => $sum + $item['amount'], 0);
        $totalExpenses = array_reduce($revenue, fn($sum, $item) => $sum + $item['expenses'], 0);
        $netProfit = $totalEarnings - $totalExpenses;

        return [
            'totalEarnings' => $totalEarnings,
            'totalExpenses' => $totalExpenses,
            'netProfit' => $netProfit,
        ];
    }
}
?>