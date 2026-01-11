<?php
/**
 * Unified Financial Management View
 * Combines Financials Analysis & Revenue Payments Management
 */

require_once 'config/config.php';
require_once 'controllers/FinancialsController.php';
require_once 'controllers/PaymentsController.php';
require_once 'components/Layout.php';
require_once 'helpers/Icons.php';
require_once 'components/Notifications.php';

requireLogin();

$financialsController = new FinancialsController($db);
$paymentsController = new PaymentsController($db);
$currentPage = 'financials';

// Get financial data
$summary = $financialsController->getSummary();
$revenueData = $financialsController->getRevenueData();
$expenses = $financialsController->getExpenses();

// Get payment data
$monthFilter = getParam('month', date('Y-m'));
$methodFilter = getParam('method', 'all');
$statusFilter = getParam('status', 'all');

$filters = ['month' => $monthFilter, 'method' => $methodFilter, 'status' => $statusFilter];
$payments = $paymentsController->getPayments($filters);
$methodStats = $paymentsController->getPaymentMethodStats();

$totalPaymentAmount = array_reduce($payments, fn($sum, $p) => $sum + $p['amount'], 0);
$totalPayments = count($payments);
$averageBasket = $totalPayments > 0 ? round($totalPaymentAmount / $totalPayments) : 0;

// Handle adding new expense
$expenseSuccess = '';
$expenseError = '';
$showModal = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_expense'])) {
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $amount = $_POST['amount'] ?? 0;
    
    if (empty($category) || empty($amount)) {
        $expenseError = 'Category and amount required';
        $showModal = true;
    } else {
        try {
            $conn = $db->getConnection();
            $date = date('Y-m-d');
            $stmt = $conn->prepare("INSERT INTO expenses (category, description, amount, date, status) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$category, $description, $amount, $date, 'valide']);
            $expenseSuccess = 'Expense added successfully!';
            header("Refresh: 1; url=index.php?page=financials-payments");
        } catch (Exception $e) {
            $expenseError = "Error: " . $e->getMessage();
            $showModal = true;
        }
    }
}

// Handle expense actions
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action_expense'])) {
    $expenseId = $_POST['expense_id'] ?? 0;
    $action = $_POST['action'] ?? '';
    
    try {
        $conn = $db->getConnection();
        if ($action === 'approve') {
            $stmt = $conn->prepare("UPDATE expenses SET status = 'valide' WHERE id = ?");
            $stmt->execute([$expenseId]);
        } elseif ($action === 'reject') {
            $stmt = $conn->prepare("UPDATE expenses SET status = 'en_attente' WHERE id = ?");
            $stmt->execute([$expenseId]);
        } elseif ($action === 'delete') {
            $stmt = $conn->prepare("DELETE FROM expenses WHERE id = ?");
            $stmt->execute([$expenseId]);
        }
        header("Refresh: 0.5; url=index.php?page=financials-payments");
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
    }
}

// Refresh data after adding expense
$summary = $financialsController->getSummary();
$revenueData = $financialsController->getRevenueData();
$expenses = $financialsController->getExpenses();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Financial Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .animate-in { animation: animateIn 0.5s ease-out forwards; }
        @keyframes animateIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .tab-active { border-bottom: 3px solid #4f46e5; color: #4f46e5; font-weight: 700; }
        .tab-inactive { color: #94a3b8; border-bottom: 3px solid transparent; }
    </style>
</head>
<body class="bg-slate-50">
    <div class="flex min-h-screen">
        <?php renderSidebar($currentPage); ?>

        <main class="flex-1 min-w-0 overflow-auto">
            <?php renderHeader(); ?>

            <div class="p-8 space-y-8 animate-in">
                <!-- Header Section -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                            <?php echo icon('dollar-sign', 32, 'text-emerald-600'); ?>
                            Financial Management
                        </h1>
                        <p class="text-slate-500 font-medium mt-1">Manage finances, expenses, and revenue tracking in one place.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all text-sm shadow-sm">
                            <?php echo icon('download', 18); ?> Export Report
                        </button>
                        <button onclick="document.getElementById('expenseModal').classList.remove('hidden')" class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all text-sm shadow-lg shadow-indigo-100">
                            <?php echo icon('plus', 18); ?> Add Expense
                        </button>
                    </div>
                </div>

                <!-- Tab Navigation -->
                <div class="border-b border-slate-200 flex gap-8">
                    <button onclick="switchTab('analysis')" class="tab-active pb-4 px-1 text-sm font-bold transition-all" id="tab-analysis-btn">
                        Financial Analysis
                    </button>
                    <button onclick="switchTab('payments')" class="tab-inactive pb-4 px-1 text-sm font-bold transition-all" id="tab-payments-btn">
                        Revenue & Payments
                    </button>
                </div>

                <!-- TAB 1: FINANCIAL ANALYSIS -->
                <div id="tab-analysis" class="space-y-8">
                    <!-- Summary Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 text-emerald-100 opacity-20 group-hover:scale-110 transition-transform"><?php echo icon('trending-up', 80); ?></div>
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Revenue (Year)</p>
                            <h3 class="text-4xl font-black text-slate-900"><?php echo number_format($summary['totalEarnings'] / 1000, 1, ',', ''); ?>k DH</h3>
                            <div class="mt-4 flex items-center gap-2 text-emerald-600 font-bold text-sm bg-emerald-50 w-fit px-3 py-1 rounded-full">
                                <?php echo icon('arrow-up-right', 14); ?> +12.4%
                            </div>
                        </div>
                        <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 text-rose-100 opacity-20 group-hover:scale-110 transition-transform"><?php echo icon('trending-down', 80); ?></div>
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Expenses (Year)</p>
                            <h3 class="text-4xl font-black text-slate-900"><?php echo number_format($summary['totalExpenses'] / 1000, 1, ',', ''); ?>k DH</h3>
                            <div class="mt-4 flex items-center gap-2 text-rose-600 font-bold text-sm bg-rose-50 w-fit px-3 py-1 rounded-full">
                                <?php echo icon('arrow-up-right', 14); ?> +5.2%
                            </div>
                        </div>
                        <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 text-indigo-100 opacity-20 group-hover:scale-110 transition-transform"><?php echo icon('pie-chart', 80); ?></div>
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Net Profit</p>
                            <h3 class="text-4xl font-black text-slate-900"><?php echo number_format($summary['netProfit'] / 1000, 1, ',', ''); ?>k DH</h3>
                            <div class="mt-4 flex items-center gap-2 text-indigo-600 font-bold text-sm bg-indigo-50 w-fit px-3 py-1 rounded-full">
                                Margin: <?php echo number_format(($summary['netProfit'] / $summary['totalEarnings']) * 100, 1); ?>%
                            </div>
                        </div>
                    </div>

                    <!-- Charts & Expenses Grid -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2 bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm">
                            <div class="flex items-center justify-between mb-8">
                                <h3 class="text-lg font-black text-slate-900">Revenue vs Expenses</h3>
                                <div class="flex gap-2">
                                    <button class="px-3 py-1.5 text-xs font-bold text-indigo-600 bg-indigo-50 rounded-lg">Monthly</button>
                                    <button class="px-3 py-1.5 text-xs font-bold text-slate-400">Yearly</button>
                                </div>
                            </div>
                            <div class="h-[350px] w-full">
                                <canvas id="revenueChart"></canvas>
                            </div>
                        </div>

                        <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm flex flex-col">
                            <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                                <?php echo icon('filter', 20, 'text-rose-500'); ?>
                                Recent Expenses
                            </h3>
                            <div class="flex-1 space-y-4 overflow-y-auto pr-2">
                                <?php foreach (array_slice($expenses, 0, 5) as $expense): ?>
                                    <div onclick="openExpenseModal(<?php echo $expense['id']; ?>, '<?php echo htmlspecialchars($expense['category']); ?>', '<?php echo htmlspecialchars($expense['description']); ?>', <?php echo $expense['amount']; ?>, '<?php echo $expense['status']; ?>')" class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between group hover:border-rose-200 transition-all cursor-pointer">
                                        <div class="flex items-center gap-3">
                                            <div class="h-10 w-10 rounded-xl flex items-center justify-center font-black text-xs <?php echo $expense['status'] === 'valide' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600'; ?>">
                                                <?php echo strtoupper(substr($expense['category'], 0, 1)); ?>
                                            </div>
                                            <div>
                                                <p class="text-sm font-bold text-slate-900"><?php echo htmlspecialchars($expense['category']); ?></p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tight"><?php echo htmlspecialchars($expense['date']); ?></p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-black text-rose-600">-<?php echo number_format($expense['amount']); ?> DH</p>
                                            <p class="text-[10px] font-black uppercase <?php echo $expense['status'] === 'valide' ? 'text-emerald-500' : 'text-rose-400 animate-pulse'; ?>">
                                                <?php echo $expense['status'] === 'valide' ? 'Approved' : 'Pending'; ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <a href="index.php?page=expenses-all" class="w-full mt-6 py-3 bg-slate-50 text-slate-500 text-xs font-black rounded-xl hover:bg-slate-100 transition-colors uppercase tracking-widest block text-center">
                                View All History
                            </a>
                        </div>
                    </div>
                </div>

                <!-- TAB 2: REVENUE & PAYMENTS -->
                <div id="tab-payments" class="space-y-8 hidden">
                    <!-- Payment Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Payments This Month</p>
                            <div class="flex items-center justify-between">
                                <h3 class="text-3xl font-black text-slate-900"><?php echo $totalPayments; ?></h3>
                                <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center"><?php echo icon('calendar', 20); ?></div>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
                            <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity"><?php echo icon('trending-up', 80); ?></div>
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Revenue</p>
                            <div class="flex items-center justify-between">
                                <h3 class="text-3xl font-black text-emerald-600"><?php echo number_format($totalPaymentAmount); ?> DH</h3>
                                <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center"><?php echo icon('trending-up', 20); ?></div>
                            </div>
                        </div>
                        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                            <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Average Basket</p>
                            <div class="flex items-center justify-between">
                                <h3 class="text-3xl font-black text-indigo-600"><?php echo number_format($averageBasket); ?> DH</h3>
                                <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center"><?php echo icon('arrow-up-right', 20); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Methods & Filters -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                            <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                                <?php echo icon('banknote', 20, 'text-indigo-500'); ?> Payment Methods
                            </h3>
                            <div class="space-y-5">
                                <?php foreach ($methodStats as $stat): ?>
                                    <div class="space-y-2">
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm font-bold text-slate-700"><?php echo $stat['method']; ?></span>
                                            <span class="text-xs font-black text-slate-400"><?php echo number_format($stat['total']); ?> DH</span>
                                        </div>
                                        <div class="h-2.5 w-full bg-slate-50 rounded-full overflow-hidden">
                                            <div class="h-full <?php echo $stat['color']; ?> transition-all duration-1000" style="width: <?php echo $stat['percentage']; ?>%"></div>
                                        </div>
                                        <div class="flex justify-between text-[10px] font-bold text-slate-400 uppercase">
                                            <span><?php echo $stat['count']; ?> Transactions</span>
                                            <span><?php echo $stat['percentage']; ?>% of Volume</span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm space-y-6">
                            <form id="filter-form" method="get">
                                <input type="hidden" name="page" value="financials-payments">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                                        <?php echo icon('filter', 20, 'text-indigo-400'); ?> Search Filters
                                    </h3>
                                    <a href="?page=financials-payments" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 transition-colors">Reset</a>
                                </div>
                                <div class="space-y-4 mt-6">
                                    <div>
                                        <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-widest">Période Mensuelle</label>
                                        <input type="month" name="month" value="<?php echo $monthFilter; ?>" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none text-slate-700">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-widest">Method</label>
                                            <select name="method" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none text-slate-700">
                                                <option value="all">All</option>
                                                <option value="especes" <?php if($methodFilter == 'especes') echo 'selected'; ?>>Cash</option>
                                                <option value="carte" <?php if($methodFilter == 'carte') echo 'selected'; ?>>Card</option>
                                                <option value="virement" <?php if($methodFilter == 'virement') echo 'selected'; ?>>Transfer</option>
                                                <option value="cheque" <?php if($methodFilter == 'cheque') echo 'selected'; ?>>Check</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-widest">Status</label>
                                            <select name="status" class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none text-slate-700">
                                                <option value="all">All</option>
                                                <option value="valide" <?php if($statusFilter == 'valide') echo 'selected'; ?>>Validated</option>
                                                <option value="en_attente" <?php if($statusFilter == 'en_attente') echo 'selected'; ?>>Pending</option>
                                                <option value="annule" <?php if($statusFilter == 'annule') echo 'selected'; ?>>Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="pt-4">
                                        <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 rounded-xl font-black transition-all">Apply Filters</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Payments Table -->
                    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                                    <tr>
                                        <th class="px-8 py-6">Date</th>
                                        <th class="px-6 py-6">Member</th>
                                        <th class="px-6 py-6">Activity</th>
                                        <th class="px-6 py-6">Method</th>
                                        <th class="px-6 py-6">Amount</th>
                                        <th class="px-6 py-6">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    <?php if(empty($payments)): ?>
                                        <tr><td colspan="6" class="text-center py-24"><div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200"><?php echo icon('creditcard', 40); ?></div><h3 class="text-xl font-black text-slate-900">No Transactions Found</h3><p class="text-slate-400 font-medium mt-2">Try adjusting your filters.</p></td></tr>
                                    <?php else: ?>
                                        <?php foreach ($payments as $payment): ?>
                                        <tr class="hover:bg-slate-50/50 transition-colors group">
                                            <td class="px-8 py-5 whitespace-nowrap"><div class="flex flex-col"><span class="text-sm font-black text-slate-900"><?php echo date('d/m/Y', strtotime($payment['date'])); ?></span><span class="text-[10px] font-bold text-slate-400">Transaction #<?php echo htmlspecialchars($payment['id']); ?></span></div></td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <?php $mName = $payment['memberName'] ?? ($payment['sport'] === 'Vente POS' ? 'Client POS' : 'Inconnu'); ?>
                                                <div class="flex items-center gap-3">
                                                    <div class="h-9 w-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-black text-sm"><?php echo strtoupper(substr($mName, 0, 1)); ?></div>
                                                    <span class="text-sm font-bold text-slate-700"><?php echo htmlspecialchars($mName); ?></span>
                                                </div>
                                            </td>
                                            <td class="px-6 py-5 whitespace-nowrap"><span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-slate-100 text-slate-500 border border-slate-200"><?php echo htmlspecialchars($payment['sport']); ?></span></td>
                                            <td class="px-6 py-5 whitespace-nowrap"><div class="flex items-center gap-2 text-sm font-bold text-slate-600"><?php echo icon($payment['method'] === 'especes' ? 'banknote' : ($payment['method'] === 'carte' ? 'creditcard' : ($payment['method'] === 'virement' ? 'landmark' : 'pen-tool')), 16); ?><span class="capitalize"><?php echo $payment['method']; ?></span></div></td>
                                            <td class="px-6 py-5 whitespace-nowrap"><span class="text-lg font-black text-emerald-600"><?php echo number_format($payment['amount']); ?> DH</span></td>
                                            <td class="px-6 py-5 whitespace-nowrap">
                                                <?php
                                                $statusClasses = ['valide' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'en_attente' => 'bg-amber-50 text-amber-600 border-amber-100', 'annule' => 'bg-rose-50 text-rose-600 border-rose-100'];
                                                $statusIcons = ['valide' => 'check-circle', 'en_attente' => 'clock', 'annule' => 'x-circle'];
                                                ?>
                                                <span class="flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-black uppercase rounded-lg <?php echo $statusClasses[$payment['status']]; ?>"><?php echo icon($statusIcons[$payment['status']], 12); ?> <?php echo formatStatus($payment['status']); ?></span>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function switchTab(tabName) {
            // Hide all tabs
            document.getElementById('tab-analysis').classList.add('hidden');
            document.getElementById('tab-payments').classList.add('hidden');
            
            // Remove active class from all buttons
            document.getElementById('tab-analysis-btn').classList.remove('tab-active');
            document.getElementById('tab-payments-btn').classList.remove('tab-active');
            document.getElementById('tab-analysis-btn').classList.add('tab-inactive');
            document.getElementById('tab-payments-btn').classList.add('tab-inactive');
            
            // Show selected tab
            document.getElementById('tab-' + tabName).classList.remove('hidden');
            document.getElementById('tab-' + tabName + '-btn').classList.add('tab-active');
            document.getElementById('tab-' + tabName + '-btn').classList.remove('tab-inactive');
        }

        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('revenueChart').getContext('2d');
            const revenueData = <?php echo json_encode($revenueData); ?>;

            const labels = revenueData.map(d => d.month);
            const revenues = revenueData.map(d => d.amount);
            const expenses = revenueData.map(d => d.expenses);
            
            const earningsGradient = ctx.createLinearGradient(0, 0, 0, 350);
            earningsGradient.addColorStop(0, 'rgba(16, 185, 129, 0.1)');
            earningsGradient.addColorStop(1, 'rgba(16, 185, 129, 0)');

            const expensesGradient = ctx.createLinearGradient(0, 0, 0, 350);
            expensesGradient.addColorStop(0, 'rgba(244, 63, 94, 0.1)');
            expensesGradient.addColorStop(1, 'rgba(244, 63, 94, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Revenue',
                        data: revenues,
                        borderColor: '#10b981',
                        backgroundColor: earningsGradient,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointHoverBorderWidth: 2,
                    }, {
                        label: 'Expenses',
                        data: expenses,
                        borderColor: '#f43f5e',
                        backgroundColor: expensesGradient,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 0,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#f43f5e',
                        pointBorderColor: '#fff',
                        pointHoverBorderWidth: 2,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            align: 'center',
                            labels: {
                                usePointStyle: true,
                                boxWidth: 8,
                                padding: 20,
                                font: {
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                            backgroundColor: '#fff',
                            titleColor: '#334155',
                            bodyColor: '#64748b',
                            titleFont: { weight: 'bold' },
                            bodyFont: { weight: 'medium' },
                            padding: 12,
                            cornerRadius: 12,
                            boxPadding: 4,
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: { weight: 500 }
                            }
                        },
                        y: {
                            grid: {
                                color: '#f1f5f9',
                                drawBorder: false,
                            },
                            ticks: {
                                color: '#94a3b8',
                                font: { weight: 500 },
                                callback: function(value) {
                                    return value / 1000 + 'k';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
    <?php renderDropdownScript(); ?>

    <!-- Add Expense Modal -->
    <div id="expenseModal" class="<?php echo $showModal || $expenseError ? '' : 'hidden'; ?> fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4 p-8 animate-in">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-slate-900">Add Expense</h2>
                <button onclick="document.getElementById('expenseModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-2xl">✕</button>
            </div>

            <?php if ($expenseSuccess): ?>
                <div class="mb-4 p-3 bg-emerald-50 border border-emerald-100 text-emerald-700 text-sm font-bold rounded-xl">
                    ✅ <?php echo htmlspecialchars($expenseSuccess); ?>
                </div>
            <?php endif; ?>

            <?php if ($expenseError): ?>
                <div class="mb-4 p-3 bg-rose-50 border border-rose-100 text-rose-700 text-sm font-bold rounded-xl">
                    ❌ <?php echo htmlspecialchars($expenseError); ?>
                </div>
            <?php endif; ?>

            <form method="POST" class="space-y-4">
                <input type="hidden" name="add_expense" value="1">
                
                <div>
                    <label class="text-xs font-black uppercase text-slate-400 tracking-wider mb-2 block">Category</label>
                    <select name="category" required class="w-full px-4 py-3 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none font-bold text-slate-700">
                        <option value="">Select a category</option>
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

    <!-- Expense Action Modal -->
    <div id="expenseActionModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full mx-4 p-8">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-black text-slate-900">Expense Details</h2>
                <button onclick="document.getElementById('expenseActionModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 text-2xl">✕</button>
            </div>

            <div class="space-y-4 mb-6">
                <div>
                    <p class="text-xs font-black text-slate-400 uppercase">Category</p>
                    <p class="text-sm font-bold text-slate-900" id="actionModalCategory">-</p>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-400 uppercase">Description</p>
                    <p class="text-sm font-bold text-slate-900" id="actionModalDescription">-</p>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-400 uppercase">Amount</p>
                    <p class="text-lg font-black text-rose-600" id="actionModalAmount">0 DH</p>
                </div>
                <div>
                    <p class="text-xs font-black text-slate-400 uppercase">Status</p>
                    <p class="text-sm font-bold" id="actionModalStatus">-</p>
                </div>
            </div>

            <form method="POST" class="space-y-3">
                <input type="hidden" name="action_expense" value="1">
                <input type="hidden" name="expense_id" id="actionModalExpenseId" value="">
                
                <button type="submit" name="action" value="approve" class="w-full px-4 py-3 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all">
                    ✓ Approve
                </button>
                <button type="submit" name="action" value="reject" class="w-full px-4 py-3 bg-amber-600 text-white font-bold rounded-xl hover:bg-amber-700 transition-all">
                    ⚠ Reject
                </button>
                <button type="submit" name="action" value="delete" onclick="return confirm('Are you sure?')" class="w-full px-4 py-3 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 transition-all">
                    🗑 Delete
                </button>
                <button type="button" onclick="document.getElementById('expenseActionModal').classList.add('hidden')" class="w-full px-4 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all">
                    Close
                </button>
            </form>
        </div>
    </div>

    <script>
    function openExpenseModal(id, category, description, amount, status) {
        document.getElementById('actionModalExpenseId').value = id;
        document.getElementById('actionModalCategory').textContent = category;
        document.getElementById('actionModalDescription').textContent = description || 'N/A';
        document.getElementById('actionModalAmount').textContent = '-' + amount.toFixed(2) + ' DH';
        document.getElementById('actionModalStatus').textContent = status === 'valide' ? 'Approved' : 'Pending';
        document.getElementById('actionModalStatus').className = 'text-sm font-bold ' + (status === 'valide' ? 'text-emerald-600' : 'text-rose-600');
        document.getElementById('expenseActionModal').classList.remove('hidden');
    }
    </script>

    <?php renderDropdownScript(); ?>
</body>
</html>
