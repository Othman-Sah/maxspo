<?php
/**
 * Activities View
 */

require_once 'config/config.php';
require_once 'config/Models.php';
require_once 'controllers/DashboardController.php';
require_once 'components/Components.php';
require_once 'components/Layout.php';

requireLogin();

$dashboardCtrl = new DashboardController();
$activities = $dashboardCtrl->getActivities();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Gestion des Sports</title>
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
        <?php renderSidebar('sports'); ?>

        <main class="flex-1 min-w-0 overflow-auto">
            <?php renderHeader(); ?>
            
            <div class="p-8">
                <!-- Header -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                            🏋️ Gestion des Sports
                        </h1>
                        <p class="text-slate-500 font-medium mt-1">Configurez vos offres et suivez la rentabilité par activité</p>
                    </div>
                    <a href="index.php?page=add-activity" class="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">
                        ➕ Ajouter une Activité
                    </a>
                </div>

                <!-- Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <?php
                    $totalMembers = array_sum(array_column($activities, 'memberCount'));
                    $totalRevenue = array_sum(array_column($activities, 'monthlyRevenue'));
                    ?>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
                        <div class="h-14 w-14 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center text-2xl">👥</div>
                        <div>
                            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Membres Inscrits</p>
                            <h3 class="text-2xl font-black text-slate-900"><?php echo $totalMembers; ?></h3>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex items-center gap-5">
                        <div class="h-14 w-14 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-2xl">💳</div>
                        <div>
                            <p class="text-slate-500 text-xs font-bold uppercase tracking-wider">Revenu Mensuel</p>
                            <h3 class="text-2xl font-black text-slate-900"><?php echo number_format($totalRevenue, 0); ?> DH</h3>
                        </div>
                    </div>
                    <div class="bg-indigo-600 p-6 rounded-2xl shadow-lg shadow-indigo-100 flex items-center gap-5 text-white">
                        <div class="h-14 w-14 rounded-xl bg-white/20 flex items-center justify-center text-2xl">🧖</div>
                        <div>
                            <p class="text-indigo-100 text-xs font-bold uppercase tracking-wider italic">Accès Premium</p>
                            <h3 class="text-xl font-black">Sauna Illimité</h3>
                        </div>
                    </div>
                </div>

                <!-- Activities Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <?php
                    foreach ($activities as $activity):
                        renderActivityCard($activity);
                    endforeach;
                    ?>
                </div>
            </div>
        </main>
    </div>
    <?php renderDropdownScript(); ?>
</body>
</html>
<?php
?>
