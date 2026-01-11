<?php
require_once 'config/config.php';
require_once 'config/Models.php';
require_once 'controllers/DashboardController.php';
require_once 'controllers/FinancialsController.php';
require_once 'components/Components.php';
require_once 'components/Layout.php';
require_once 'components/Notifications.php';

requireLogin();

global $db;
$dashboardCtrl = new DashboardController($db);
$financialsCtrl = new FinancialsController($db);

$activities = $dashboardCtrl->getActivities();
$summary = $financialsCtrl->getSummary();
$revenueData = $financialsCtrl->getRevenueData();

$totalMembers = 0;
$expiringSoon = 0;
$loyalMembers = 0;
$monthlyRevenue = $summary['totalEarnings'] ?? 0;

try {
    $conn = $db->getConnection();
    $stmt = $conn->query("SELECT firstName, lastName, email, phone, sport, expiryDate, isLoyal FROM members WHERE expiryDate <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) AND expiryDate >= CURDATE() ORDER BY expiryDate ASC LIMIT 5");
    $expiringMembers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    $expiringMembers = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Tableau de bord</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>
<body class="transition-colors">
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

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <?php
                    renderStatCard('Total Membres', $totalMembers, 0, 'Users', 'indigo', '');
                    renderStatCard('Revenus du Mois', number_format((float)$monthlyRevenue, 0), 0, 'CardIcon', 'emerald', 'DH ');
                    renderStatCard('Expirent Bientôt', $expiringSoon, 0, 'Timer', 'rose', '');
                    renderStatCard('Membres Fidèles', $loyalMembers, 0, 'Award', 'amber', '');
                    ?>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
                    <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                        <div class="flex items-center justify-between mb-8">
                            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                                📈 Évolution des Revenus
                            </h3>
                            <button class="text-xs font-bold text-indigo-600 hover:underline">Détails</button>
                        </div>
                        <canvas id="revenueChart" height="80"></canvas>
                    </div>

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
                                if (empty($expiringMembers)) {
                                    echo '<tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">Aucune expiration proche</td></tr>';
                                }
                                foreach ($expiringMembers as $m) {
                                    renderMemberRow($m);
                                }
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
