<?php
require_once '../config/config.php';
require_once '../controllers/JournalController.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

$journalController = new JournalController($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    try {
        $journalController->addExpense($data);
        echo json_encode(['success' => true, 'message' => 'Expense added successfully.']);
    } catch (Exception $e) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
    }
    exit;
}

// Default filters
$filters = [
    'type' => $_GET['type'] ?? 'all',
    'status' => $_GET['status'] ?? 'all',
    'month' => $_GET['month'] ?? '',
    'search' => $_GET['search'] ?? ''
];

try {
    $transactions = $journalController->getTransactions($filters);
    $expenseCategories = $journalController->getExpenseCategories();
    
    // Calculate totals
    $totalRevenue = 0;
    $totalExpenses = 0;
    foreach ($transactions as $t) {
        if ($t['type'] === 'payment') {
            $totalRevenue += $t['amount'];
        } else {
            $totalExpenses += $t['amount'];
        }
    }

    echo json_encode([
        'transactions' => $transactions,
        'summary' => [
            'totalRevenue' => $totalRevenue,
            'totalExpenses' => $totalExpenses,
            'netIncome' => $totalRevenue - $totalExpenses
        ],
        'meta' => [
            'expenseCategories' => $expenseCategories,
            'filters' => $filters
        ]
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

?>