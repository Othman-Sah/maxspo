<?php
/**
 * Financials / Analysis View
 * Revenue and expense tracking with analytics - matching React FinancialsView
 */

require_once 'config/config.php';
require_once 'controllers/FinancialsController.php';
require_once 'components/Layout.php';
require_once 'helpers/Icons.php';
require_once 'components/Notifications.php';

requireLogin();

$controller = new FinancialsController($db);
$summary = $controller->getSummary();
$revenueData = $controller->getRevenueData();
$expenses = $controller->getExpenses();
$currentPage = 'financials';

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
            // Redirect to refresh page
            header("Refresh: 1; url=index.php?page=financials");
        } catch (Exception $e) {
            $expenseError = "Error: " . $e->getMessage();
            $showModal = true;
        }
    }
}

// Handle expense actions (approve, reject, delete)
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
        header("Refresh: 0.5; url=index.php?page=financials");
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
    }
}

// Refresh data after adding expense
$controller = new FinancialsController($db);
$summary = $controller->getSummary();
$revenueData = $controller->getRevenueData();
$expenses = $controller->getExpenses();

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Financial Analysis</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .animate-in { animation: animateIn 0.5s ease-out forwards; }
        @keyframes animateIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
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
                            Financial Analysis
                        </h1>
                        <p class="text-slate-500 font-medium mt-1">Suivez la rentabilité de NEEDSPORT avec précision.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all text-sm shadow-sm">
                            <?php echo icon('download', 18); ?> Export Report
                        </button>
                        <button onclick="document.getElementById('expenseModal').classList.remove('hidden')" class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all text-sm shadow-lg shadow-indigo-100">
                            <?php echo icon('plus', 18); ?> Nouvelle Dépense
                        </button>
                    </div>
                </div>

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
                    <div class="bg-slate-900 p-8 rounded-[32px] text-white shadow-xl shadow-slate-200 relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 text-white/5 opacity-20 group-hover:scale-110 transition-transform"><?php echo icon('pie-chart', 80); ?></div>
                        <p class="text-white/40 text-[10px] font-black uppercase tracking-widest mb-1">Bénéfice Net</p>
                        <h3 class="text-4xl font-black text-white"><?php echo number_format($summary['netProfit'] / 1000, 1, ',', ''); ?>k DH</h3>
                        <div class="mt-4 flex items-center gap-2 text-indigo-400 font-bold text-sm bg-white/10 w-fit px-3 py-1 rounded-full">
                            Marge: <?php echo number_format(($summary['netProfit'] / $summary['totalEarnings']) * 100, 1); ?>%
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
                            <!-- NOTE: The React version uses a sophisticated AreaChart from recharts. 
                                 This is a simplified representation using Chart.js. 
                                 A full server-side generation of a similar SVG chart would be overly complex. -->
                        </div>
                    </div>

                    <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm flex flex-col">
                        <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                            <?php echo icon('filter', 20, 'text-rose-500'); ?>
                            Recent Expenses
                        </h3>
                        <div class="flex-1 space-y-4 overflow-y-auto pr-2">
                            <?php foreach ($expenses as $expense): ?>
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
        </main>
    </div>

    <script>
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
                <h2 class="text-2xl font-black text-slate-900">Nouvelle Dépense</h2>
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