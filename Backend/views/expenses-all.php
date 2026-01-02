<?php
/**
 * All Expenses View
 */

require_once 'config/config.php';
require_once 'config/Models.php';
require_once 'components/Components.php';
require_once 'components/Layout.php';
require_once 'components/Notifications.php';

requireLogin();

global $db;

// Get filters
$filters = [
    'category' => $_GET['category'] ?? 'all',
    'status' => $_GET['status'] ?? 'all',
    'search' => $_GET['search'] ?? ''
];

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
            header("Refresh: 1; url=index.php?page=expenses-all");
        } catch (Exception $e) {
            $expenseError = "Error: " . $e->getMessage();
            $showModal = true;
        }
    }
}

// Get all expenses with filters
try {
    $conn = $db->getConnection();
    $sql = "SELECT id, category, description, amount, date, status FROM expenses WHERE 1=1";
    $params = [];
    
    if (!empty($filters['category']) && $filters['category'] !== 'all') {
        $sql .= " AND category = ?";
        $params[] = $filters['category'];
    }
    
    if (!empty($filters['status']) && $filters['status'] !== 'all') {
        $sql .= " AND status = ?";
        $params[] = $filters['status'];
    }
    
    if (!empty($filters['search'])) {
        $sql .= " AND (category LIKE ? OR description LIKE ?)";
        $search = '%' . $filters['search'] . '%';
        $params[] = $search;
        $params[] = $search;
    }
    
    $sql .= " ORDER BY date DESC";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $expenses = [];
}

// Get unique categories
try {
    $conn = $db->getConnection();
    $stmt = $conn->query("SELECT DISTINCT category FROM expenses ORDER BY category");
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $categories = [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - All Expenses</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
    <div class="flex min-h-screen bg-slate-50">
        <?php renderSidebar('financials'); ?>

        <main class="flex-1 min-w-0 overflow-auto">
            <?php renderHeader(); ?>
            
            <div class="p-8">
                <!-- Header -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                            💸 All Expenses
                        </h1>
                        <p class="text-slate-500 font-medium mt-1">View and manage all expenses in your gym</p>
                    </div>
                    <button onclick="document.getElementById('expenseModal').classList.remove('hidden')" class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all text-sm shadow-lg shadow-indigo-100">
                        ➕ Add Expense
                    </button>
                </div>

                <!-- Filters -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6 mb-8">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            🔍 Advanced Filters
                        </h3>
                        <a href="index.php?page=expenses-all" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                            Reset
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                            <input 
                                type="text"
                                placeholder="Search..."
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none transition-all text-sm font-medium"
                                value="<?php echo htmlspecialchars($filters['search']); ?>"
                                onchange="document.location='index.php?page=expenses-all&search=' + this.value"
                            />
                        </div>

                        <select class="px-4 py-2.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none transition-all text-sm font-medium text-slate-600"
                                onchange="document.location='index.php?page=expenses-all&category=' + this.value">
                            <option value="all">All Categories</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?php echo htmlspecialchars($cat['category']); ?>" <?php echo $filters['category'] === $cat['category'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($cat['category']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select class="px-4 py-2.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none transition-all text-sm font-medium text-slate-600"
                                onchange="document.location='index.php?page=expenses-all&status=' + this.value">
                            <option value="all">All Statuses</option>
                            <option value="valide" <?php echo $filters['status'] === 'valide' ? 'selected' : ''; ?>>Valid</option>
                            <option value="en_attente" <?php echo $filters['status'] === 'en_attente' ? 'selected' : ''; ?>>Pending</option>
                        </select>
                    </div>
                </div>

                <!-- Expenses Table -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Category</th>
                                    <th class="px-6 py-4">Description</th>
                                    <th class="px-6 py-4">Amount</th>
                                    <th class="px-6 py-4">Date</th>
                                    <th class="px-6 py-4">Status</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php
                                if (empty($expenses)) {
                                    echo '<tr><td colspan="6" class="px-6 py-8 text-center text-slate-500">No expenses found</td></tr>';
                                } else {
                                    $totalAmount = 0;
                                    foreach ($expenses as $expense):
                                        $totalAmount += $expense['amount'];
                                ?>
                                    <tr class="hover:bg-slate-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-slate-100 text-slate-700">
                                                <?php echo htmlspecialchars($expense['category']); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-slate-700">
                                            <?php echo htmlspecialchars($expense['description']); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-bold text-slate-900"><?php echo number_format($expense['amount'], 2); ?> DH</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                            <?php echo date('M d, Y', strtotime($expense['date'])); ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <?php if ($expense['status'] === 'valide'): ?>
                                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-emerald-100 text-emerald-700">Valid</span>
                                            <?php else: ?>
                                                <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-rose-100 text-rose-700">Pending</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                            <button class="text-slate-400 hover:text-slate-600 font-bold text-xs">Edit</button>
                                        </td>
                                    </tr>
                                <?php
                                    endforeach;
                                    if (!empty($expenses)):
                                ?>
                                    <tr class="bg-slate-50">
                                        <td colspan="2" class="px-6 py-4 font-bold text-slate-900">Total</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="font-black text-lg text-slate-900"><?php echo number_format($totalAmount, 2); ?> DH</span>
                                        </td>
                                        <td colspan="3"></td>
                                    </tr>
                                <?php endif; ?>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

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

    <?php renderDropdownScript(); ?>
</body>
</html>
