<?php
/**
 * Financial Journal View
 */

require_once 'config/config.php';
require_once 'config/Models.php';
require_once 'components/Components.php';
require_once 'components/Layout.php';
require_once 'controllers/JournalController.php';

requireLogin();

global $db;
$journalController = new JournalController($db);

// Get filters
$filters = [
    'type' => $_GET['type'] ?? 'all',
    'status' => $_GET['status'] ?? 'all',
    'month' => $_GET['month'] ?? '',
    'search' => $_GET['search'] ?? ''
];

// Handle adding new expense
$expenseSuccess = '';
$expenseError = '';
$showModal = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_expense'])) {
    try {
        $journalController->addExpense($_POST);
        $expenseSuccess = 'Expense added successfully!';
        // Refresh to show the new expense
        header("Location: index.php?page=journal&type=expense");
        exit();
    } catch (Exception $e) {
        $expenseError = $e->getMessage();
        $showModal = true;
    }
}


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
$netIncome = $totalRevenue - $totalExpenses;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Financial Journal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        .animate-in {
            animation: fadeIn 0.3s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.95); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="flex min-h-screen bg-slate-50">
        <?php renderSidebar('journal'); ?>

        <main class="flex-1 min-w-0 overflow-auto">
            <?php renderHeader(); ?>
            
            <div class="p-8">
                <!-- Header -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                            <svg class="w-8 h-8 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v.01"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18.5A6.5 6.5 0 1012 5.5a6.5 6.5 0 000 13z"></path></svg>
                            Financial Journal
                        </h1>
                        <p class="text-slate-500 font-medium mt-1">A unified view of all payments and expenses.</p>
                    </div>
                    <button onclick="document.getElementById('expenseModal').classList.remove('hidden')" class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all text-sm shadow-lg shadow-indigo-100">
                        ➕ Add Expense
                    </button>
                </div>
                
                <!-- Tabs for filtering -->
                <div class="mb-8 flex border-b border-slate-200">
                    <a href="?page=journal&type=all" class="px-6 py-3 font-bold text-sm <?php echo $filters['type'] === 'all' ? 'text-indigo-600 border-b-2 border-indigo-600' : 'text-slate-500'; ?>">All Transactions</a>
                    <a href="?page=journal&type=payment" class="px-6 py-3 font-bold text-sm <?php echo $filters['type'] === 'payment' ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-slate-500'; ?>">Revenue</a>
                    <a href="?page=journal&type=expense" class="px-6 py-3 font-bold text-sm <?php echo $filters['type'] === 'expense' ? 'text-rose-600 border-b-2 border-rose-600' : 'text-slate-500'; ?>">Expenses</a>
                </div>

                <!-- Filters -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6 mb-8">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            🔍 Advanced Filters
                        </h3>
                        <a href="index.php?page=journal&type=<?php echo $filters['type']; ?>" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                            Reset
                        </a>
                    </div>
                    
                    <form id="filterForm" method="GET">
                        <input type="hidden" name="page" value="journal">
                        <input type="hidden" name="type" value="<?php echo $filters['type']; ?>">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                                <input 
                                    type="text"
                                    name="search"
                                    placeholder="Search..."
                                    class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none transition-all text-sm font-medium"
                                    value="<?php echo htmlspecialchars($filters['search']); ?>"
                                />
                            </div>

                            <select name="status" class="px-4 py-2.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none transition-all text-sm font-medium text-slate-600">
                                <option value="all">All Statuses</option>
                                <option value="valide" <?php echo $filters['status'] === 'valide' ? 'selected' : ''; ?>>Valid</option>
                                <option value="en_attente" <?php echo $filters['status'] === 'en_attente' ? 'selected' : ''; ?>>Pending</option>
                            </select>

                            <input type="month" name="month" class="px-4 py-2.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none transition-all text-sm font-medium text-slate-600" value="<?php echo $filters['month']; ?>">

                            <button type="submit" class="px-6 py-2.5 bg-slate-800 text-white font-bold rounded-xl hover:bg-slate-900 transition-all text-sm">Filter</button>
                        </div>
                    </form>
                </div>


                <!-- Transactions Table -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Type</th>
                                    <th class="px-6 py-4">Date</th>
                                    <th class="px-6 py-4">Description</th>
                                    <th class="px-6 py-4">Category</th>
                                    <th class="px-6 py-4 text-right">Amount</th>
                                    <th class="px-6 py-4">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php if (empty($transactions)): ?>
                                    <tr><td colspan="6" class="px-6 py-8 text-center text-slate-500">No transactions found for the selected filters.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($transactions as $t): ?>
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-6 py-4">
                                                <?php if ($t['type'] === 'payment'): ?>
                                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">Revenue</span>
                                                <?php else: ?>
                                                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-rose-100 text-rose-700">Expense</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                                <?php echo date('M d, Y', strtotime($t['date'])); ?>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-slate-700 max-w-xs truncate">
                                                <?php echo htmlspecialchars($t['description']); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-700">
                                                    <?php echo htmlspecialchars($t['category']); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                                <span class="font-bold text-slate-900 <?php echo $t['type'] === 'payment' ? 'text-emerald-600' : 'text-rose-600'; ?>">
                                                    <?php echo ($t['type'] === 'payment' ? '+' : '-') . number_format($t['amount'], 2); ?> DH
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php if ($t['status'] === 'valide'): ?>
                                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">Valid</span>
                                                <?php else: ?>
                                                    <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-yellow-100 text-yellow-800">Pending</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                             <tfoot class="bg-slate-100 font-bold">
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-right text-emerald-600">Total Revenue</td>
                                    <td class="px-6 py-4 text-right text-emerald-600">+<?php echo number_format($totalRevenue, 2); ?> DH</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-right text-rose-600">Total Expenses</td>
                                    <td class="px-6 py-4 text-right text-rose-600">-<?php echo number_format($totalExpenses, 2); ?> DH</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-right text-slate-800 text-lg">Net Income</td>
                                    <td class="px-6 py-4 text-right text-slate-800 text-lg">
                                        <?php echo number_format($netIncome, 2); ?> DH
                                    </td>
                                    <td></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Expense Modal -->
    <div id="expenseModal" class="<?php echo $showModal ? '' : 'hidden'; ?> fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4 p-8 animate-in">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-slate-900">Add Expense</h2>
                <button onclick="document.getElementById('expenseModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-2xl">✕</button>
            </div>

            <?php if ($expenseError): ?>
                <div class="mb-4 p-3 bg-rose-50 border border-rose-100 text-rose-700 text-sm font-bold rounded-xl">
                    ❌ <?php echo htmlspecialchars($expenseError); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php?page=journal" class="space-y-4">
                <input type="hidden" name="add_expense" value="1">
                
                <div>
                    <label class="text-xs font-black uppercase text-slate-400 tracking-wider mb-2 block">Category</label>
                    <select name="category" required class="w-full px-4 py-3 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none font-bold text-slate-700">
                        <option value="">Select a category</option>
                        <?php foreach($expenseCategories as $cat): ?>
                            <option value="<?php echo htmlspecialchars($cat); ?>"><?php echo htmlspecialchars($cat); ?></option>
                        <?php endforeach; ?>
                         <option value="Rent">Rent</option>
                        <option value="Electricity">Electricity</option>
                        <option value="Water">Water</option>
                        <option value="Salaries">Salaries</option>
                        <option value="Maintenance">Maintenance</option>
                        <option value="Equipment">Equipment</option>
                        <option value="Marketing">Marketing</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div>
                    <label class="text-xs font-black uppercase text-slate-400 tracking-wider mb-2 block">Description</label>
                    <input type="text" name="description" placeholder="Ex: AC Maintenance" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none font-bold text-slate-700" />
                </div>

                <div>
                    <label class="text-xs font-black uppercase text-slate-400 tracking-wider mb-2 block">Amount (DH)</label>
                    <input type="number" name="amount" required min="0" step="0.01" placeholder="0.00" class="w-full px-4 py-3 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none font-bold text-slate-700" />
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="document.getElementById('expenseModal').classList.add('hidden')" class="flex-1 px-4 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all">
                        Cancel
                    </button>
                    <button type="submit" class="flex-1 px-4 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all">
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>

    <?php renderDropdownScript(); ?>
</body>
</html>
