<?php
/**
 * Journal Controller
 * Handles combined financial data (payments and expenses)
 */

class JournalController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    /**
     * Get all transactions (payments and expenses) with filtering
     */
    public function getTransactions($filters = []) {
        $conn = $this->db->getConnection();
        
        // Base query for payments (revenue)
        $sqlPayments = "SELECT 
            p.id, 
            'payment' as type, 
            CONCAT(m.firstName, ' ', m.lastName) as description,
            p.sport as category,
            p.amount, 
            p.date, 
            p.status,
            p.method
        FROM payments p 
        LEFT JOIN members m ON p.member_id = m.id
        WHERE 1=1";

        // Base query for expenses
        $sqlExpenses = "SELECT 
            id, 
            'expense' as type, 
            description,
            category,
            amount, 
            date, 
            status,
            'n/a' as method
        FROM expenses
        WHERE 1=1";

        $params = [];

        // Apply filters
        $typeFilter = $filters['type'] ?? 'all';
        $statusFilter = $filters['status'] ?? 'all';
        $monthFilter = $filters['month'] ?? '';
        $searchFilter = $filters['search'] ?? '';

        $paymentConditions = "";
        $expenseConditions = "";

        if ($statusFilter !== 'all') {
            $paymentConditions .= " AND p.status = ?";
            $expenseConditions .= " AND status = ?";
            $params[] = $statusFilter;
        }
        if (!empty($monthFilter)) {
            $paymentConditions .= " AND DATE_FORMAT(p.date, '%Y-%m') = ?";
            $expenseConditions .= " AND DATE_FORMAT(date, '%Y-%m') = ?";
            $params[] = $monthFilter;
        }
        if(!empty($searchFilter)) {
            $search = '%' . $searchFilter . '%';
            $paymentConditions .= " AND (CONCAT(m.firstName, ' ', m.lastName) LIKE ? OR p.sport LIKE ?)";
            $expenseConditions .= " AND (description LIKE ? OR category LIKE ?)";
            $params[] = $search;
            $params[] = $search;
        }


        $sql = "";
        if ($typeFilter === 'all') {
            // we need to duplicate params for union all
            $allParams = array_merge($params, $params);
            $sql = "($sqlPayments $paymentConditions) UNION ALL ($sqlExpenses $expenseConditions) ORDER BY date DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute($allParams);

        } elseif ($typeFilter === 'payment') {
            $sql = $sqlPayments . $paymentConditions . " ORDER BY p.date DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);

        } elseif ($typeFilter === 'expense') {
            $sql = $sqlExpenses . $expenseConditions . " ORDER BY date DESC";
            $stmt = $conn->prepare($sql);
            $stmt->execute($params);
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Add a new expense
     */
    public function addExpense($data) {
        if (empty($data['category']) || empty($data['amount'])) {
            throw new Exception('Category and amount are required.');
        }

        try {
            $conn = $this->db->getConnection();
            $date = date('Y-m-d');
            $stmt = $conn->prepare("INSERT INTO expenses (category, description, amount, date, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['category'], 
                $data['description'] ?? '', 
                $data['amount'], 
                $date, 
                'valide'
            ]);
            return true;
        } catch (PDOException $e) {
            throw new Exception("Database error while adding expense: " . $e->getMessage());
        }
    }

    /**
     * Get unique expense categories
     */
    public function getExpenseCategories() {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->query("SELECT DISTINCT category FROM expenses ORDER BY category");
            return $stmt->fetchAll(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>