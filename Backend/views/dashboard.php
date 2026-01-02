<?php
/**
 * Dashboard View
 */

require_once 'config/config.php';
require_once 'config/Models.php';
require_once 'controllers/DashboardController.php';
require_once 'components/Components.php';
require_once 'components/Layout.php';
require_once 'components/Notifications.php';

requireLogin();

global $db;
$dashboardCtrl = new DashboardController($db);
$stats = $dashboardCtrl->getStats();
$activities = $dashboardCtrl->getActivities();

// Add member count to each activity
try {
    $conn = $db->getConnection();
    foreach ($activities as &$activity) {
        $stmt = $conn->prepare("SELECT COUNT(*) as count FROM members WHERE sport = ?");
        $stmt->execute([$activity['name']]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $activity['memberCount'] = $result['count'] ?? 0;
    }
} catch (Exception $e) {
    // If query fails, set memberCount to 0
    foreach ($activities as &$activity) {
        $activity['memberCount'] = 0;
    }
}

// Get revenue data for the last 6 months
$revenueData = [];
try {
    $conn = $db->getConnection();
    for ($i = 5; $i >= 0; $i--) {
        $month = date('Y-m', strtotime("-$i months"));
        $stmt = $conn->prepare("SELECT COALESCE(SUM(amount), 0) as total FROM payments WHERE DATE_FORMAT(date, '%Y-%m') = ? AND status = 'valide'");
        $stmt->execute([$month]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $monthName = date('M', strtotime($month));
        $revenueData[] = [
            'month' => $monthName,
            'amount' => (int)$result['total'],
            'expenses' => (int)($result['total'] / 2)  // Estimate expenses as 50% of revenue
        ];
    }
} catch (Exception $e) {
    $revenueData = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Tableau de bord</title>
    <script>tailwind = { config: { darkMode: 'class' } };</script>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        (function() {
            const htmlElement = document.documentElement;
            const isDarkMode = localStorage.getItem('darkMode') === 'true';
            if (isDarkMode) {
                htmlElement.classList.add('dark');
            }
        })();
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
        html.dark { background-color: #0f172a; color: #f1f5f9; }
        body.dark { background-color: #0f172a; color: #f1f5f9; }
    </style>
</head>
<body class="dark:bg-slate-950 transition-colors">
    <div class="flex min-h-screen bg-slate-50">
        <?php renderSidebar('dashboard'); ?>

        <main class="flex-1 min-w-0 overflow-auto">
            <?php renderHeader(); ?>
            
            <div class="p-8">
                <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight">Bonjour, Coach 👋</h1>
                        <p class="text-slate-500 font-medium mt-1">Voici ce qu'il se passe dans votre club aujourd'hui.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex items-center gap-2 text-sm font-semibold px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl border border-indigo-100 cursor-pointer hover:bg-indigo-100 transition-all">
                            🌊 Sauna: Opérationnel
                        </div>
                        <a href="index.php?page=add-member" class="flex items-center gap-2 text-sm font-bold px-6 py-2 bg-slate-900 text-white rounded-xl shadow-lg shadow-slate-200 hover:bg-slate-800 transition-all active:scale-95">
                            ➕ Nouveau Membre
                        </a>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <?php
                    renderStatCard('Total Membres', $stats->totalMembers, $stats->memberTrend, 'Users', 'indigo', '');
                    renderStatCard('Revenus du Mois', number_format($stats->monthlyRevenue, 0), $stats->revenueTrend, 'CardIcon', 'emerald', 'DH ');
                    renderStatCard('Expirent Bientôt', $stats->expiringSoon, -5, 'Timer', 'rose', '');
                    renderStatCard('Membres Fidèles', $stats->loyalMembers, 15, 'Award', 'amber', '');
                    ?>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <!-- Revenue Chart -->
                    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                                📈 Évolution des Revenus
                            </h3>
                            <button class="text-xs font-bold text-indigo-600 hover:underline">Détails</button>
                        </div>
                        <canvas id="revenueChart" height="80"></canvas>
                    </div>

                    <!-- Sports Distribution -->
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                        <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2 mb-8">
                            <?php require_once ROOT_PATH . '/helpers/Icons.php'; echo chartIcon(20); ?> Répartition par Sport
                        </h3>
                        <div class="space-y-4">
                            <?php foreach ($activities as $activity): ?>
                                <div class="space-y-1">
                                    <div class="flex justify-between text-sm font-bold">
                                        <span class="text-slate-700"><?php echo htmlspecialchars($activity['name']); ?></span>
                                        <span class="text-slate-500"><?php echo $activity['memberCount']; ?></span>
                                    </div>
                                    <div class="h-2 w-full bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full bg-indigo-600" style="width: <?php echo ($activity['memberCount'] / 250) * 100; ?>%"></div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <a href="index.php?page=sports" class="w-full mt-4 py-2 bg-slate-50 text-slate-500 text-xs font-black rounded-xl hover:bg-slate-100 transition-colors text-center">
                            Gérer les sports
                        </a>
                    </div>
                </div>

                <!-- Expiring Members Alert -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-900">Alertes Expirations</h3>
                        <a href="index.php?page=members" class="text-sm font-bold text-indigo-600 hover:underline">Voir tout</a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Membre</th>
                                    <th class="px-6 py-4">Sport</th>
                                    <th class="px-6 py-4">Date Expiration</th>
                                    <th class="px-6 py-4">Contact</th>
                                    <th class="px-6 py-4 text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php
                                // Show sample expiring members
                                $members = [
                                    ['firstName' => 'Sarah', 'lastName' => 'Mansouri', 'email' => 'sarah@example.com', 'phone' => '06 23 45 67 89', 'sport' => 'Boxe Anglaise', 'expiryDate' => '2024-06-12', 'isLoyal' => false],
                                ];
                                foreach ($members as $member):
                                    renderMemberRow($member);
                                endforeach;
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php renderDropdownScript(); ?>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Revenue Chart
        const ctx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_column($revenueData, 'month')); ?>,
                datasets: [
                    {
                        label: 'Revenus',
                        data: <?php echo json_encode(array_column($revenueData, 'amount')); ?>,
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 6,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                    },
                    {
                        label: 'Dépenses (Estimées)',
                        data: <?php echo json_encode(array_column($revenueData, 'expenses')); ?>,
                        borderColor: '#64748b',
                        backgroundColor: 'rgba(100, 116, 139, 0.05)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointBackgroundColor: '#64748b',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                weight: 'bold',
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' DH';
                            }
                        },
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    </script>
</body>
</html>
<?php
?>
