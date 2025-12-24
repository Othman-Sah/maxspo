<?php
/**
 * Staff / Team View
 * Team members management with roles and status - matching React StaffView
 */

require_once 'config/config.php';
require_once 'controllers/StaffController.php';
require_once 'components/Layout.php';
require_once 'helpers/Icons.php';
require_once 'components/Notifications.php';

requireLogin();

$controller = new StaffController($db);
$staffList = $controller->getAll();
$currentPage = 'staff';

function getStatusBadgeInfo($status) {
    $badges = [
        'present' => ['class' => 'bg-emerald-50 text-emerald-600 border border-emerald-100', 'icon' => 'badge-check', 'label' => 'Présent'],
        'absent' => ['class' => 'bg-rose-50 text-rose-600 border border-rose-100', 'icon' => 'user-x', 'label' => 'Absent'],
        'en_pause' => ['class' => 'bg-amber-50 text-amber-600 border border-amber-100', 'icon' => 'clock', 'label' => 'En Pause'],
    ];
    return $badges[$status] ?? $badges['present'];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Gestion de l'Équipe</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                            <?php echo icon('user-check', 32, 'text-indigo-600'); ?>
                            Gestion de l'Équipe
                        </h1>
                        <p class="text-slate-500 font-medium mt-1">Gérez vos coachs, réceptionnistes et personnel technique.</p>
                    </div>
                    <button class="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100 group active:scale-95">
                        <?php echo icon('plus', 20, 'group-hover:rotate-90 transition-transform duration-300'); ?>
                        Ajouter un Staff
                    </button>
                </div>

                <!-- Staff Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php foreach ($staffList as $staff):
                        $initials = implode('', array_map(fn($n) => substr($n, 0, 1), explode(' ', $staff['name'])));
                        $badgeInfo = getStatusBadgeInfo($staff['status']);
                    ?>
                        <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 p-6 flex flex-col group overflow-hidden relative">
                            <div class="absolute top-0 right-0 p-4">
                               <button class="p-2 text-slate-300 hover:text-slate-900 transition-colors">
                                 <?php echo icon('more-vertical', 20); ?>
                               </button>
                            </div>
                            
                            <div class="flex items-center gap-4 mb-6">
                                <div class="h-16 w-16 rounded-2xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 font-black text-2xl group-hover:scale-105 transition-transform">
                                    <?php echo htmlspecialchars($initials); ?>
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-lg font-black text-slate-900 truncate"><?php echo htmlspecialchars($staff['name']); ?></h3>
                                    <p class="text-xs font-bold text-slate-400 uppercase tracking-tighter"><?php echo htmlspecialchars($staff['role']); ?></p>
                                </div>
                            </div>

                            <div class="space-y-3 mb-6 flex-1">
                                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-2xl">
                                    <span class="text-[10px] font-black uppercase text-slate-400">Statut Actuel</span>
                                    <span class="flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-black uppercase rounded-lg <?php echo $badgeInfo['class']; ?>">
                                        <?php echo icon($badgeInfo['icon'], 12); ?> <?php echo $badgeInfo['label']; ?>
                                    </span>
                                </div>
                              
                                <div class="flex items-center gap-3 text-slate-500">
                                    <?php echo icon('phone', 14); ?>
                                    <span class="text-sm font-bold"><?php echo htmlspecialchars($staff['phone']); ?></span>
                                </div>
                                <div class="flex items-center gap-3 text-slate-500">
                                    <?php echo icon('mail', 14); ?>
                                    <span class="text-sm font-bold truncate"><?php echo htmlspecialchars($staff['email']); ?></span>
                                </div>
                            </div>

                            <div class="pt-4 border-t border-slate-50 flex items-center justify-between">
                                <div>
                                    <p class="text-[10px] font-black uppercase text-slate-400">Salaire</p>
                                    <p class="text-sm font-black text-slate-900"><?php echo number_format($staff['salary']); ?> DH</p>
                                </div>
                                <div class="flex gap-2">
                                    <button class="p-2 text-indigo-600 bg-indigo-50 hover:bg-indigo-100 rounded-xl transition-colors">
                                        <?php echo icon('edit', 16); ?>
                                    </button>
                                    <button class="p-2 text-rose-500 bg-rose-50 hover:bg-rose-100 rounded-xl transition-colors">
                                        <?php echo icon('user-minus', 16); ?>
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    
                    <!-- Add Staff Placeholder -->
                    <div class="border-2 border-dashed border-slate-100 rounded-[32px] p-6 flex flex-col items-center justify-center text-center space-y-4 hover:border-indigo-300 hover:bg-indigo-50/30 transition-all cursor-pointer group py-12">
                        <div class="h-16 w-16 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center group-hover:bg-white group-hover:text-indigo-500 transition-all">
                            <?php echo icon('plus', 32); ?>
                        </div>
                        <div>
                            <h4 class="text-sm font-black text-slate-900">Nouveau membre</h4>
                            <p class="text-xs text-slate-400 font-medium">Recruter un nouveau coach</p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php renderDropdownScript(); ?>
</body>
</html>