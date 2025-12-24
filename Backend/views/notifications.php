<?php
require_once 'config/config.php';
require_once 'controllers/NotificationsController.php';
require_once 'components/Layout.php';
require_once 'helpers/Icons.php';

requireLogin();

$filter = getParam('filter', 'all');
$controller = new NotificationsController($db);
$notifications = $controller->getNotifications($filter);
$currentPage = 'notifications';

function getNotificationIconInfo($type) {
    $map = [
        'payment' => ['icon' => 'creditcard', 'color' => 'text-rose-600', 'bg' => 'bg-rose-50'],
        'session' => ['icon' => 'calendar', 'color' => 'text-indigo-600', 'bg' => 'bg-indigo-50'],
        'system'  => ['icon' => 'settings', 'color' => 'text-slate-600', 'bg' => 'bg-slate-100'],
        'member'  => ['icon' => 'user-plus', 'color' => 'text-emerald-600', 'bg' => 'bg-emerald-50'],
    ];
    return $map[$type] ?? $map['system'];
}

function getPriorityColor($priority) {
    $map = ['high' => 'bg-rose-500', 'medium' => 'bg-amber-500', 'low' => 'bg-emerald-500'];
    return $map[$priority] ?? 'bg-slate-500';
}

$navItems = [
    ['id' => 'all', 'label' => 'Toutes les alertes', 'icon' => 'bell'],
    ['id' => 'payment', 'label' => 'Paiements', 'icon' => 'creditcard'],
    ['id' => 'session', 'label' => 'Séances', 'icon' => 'calendar'],
    ['id' => 'system', 'label' => 'Système', 'icon' => 'settings'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Centre de Notifications</title>
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
            <div class="p-8 max-w-6xl mx-auto space-y-8 animate-in pb-20">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                            <?php echo icon('bell', 32, 'text-indigo-600'); ?>
                            Centre de Notifications
                        </h1>
                        <p class="text-slate-500 font-medium mt-1">Restez informé de l'activité de votre club en temps réel.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="px-4 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl text-xs hover:bg-slate-50">Historique complet</button>
                        <button class="px-6 py-2.5 bg-slate-900 text-white font-bold rounded-xl text-xs shadow-lg shadow-slate-200">Paramètres d'alertes</button>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                    <div class="space-y-6">
                        <div class="bg-white p-4 rounded-3xl border border-slate-100 shadow-sm space-y-1">
                            <?php foreach ($navItems as $item): ?>
                                <a href="?page=notifications&filter=<?php echo $item['id']; ?>" class="w-full flex items-center justify-between px-4 py-3 rounded-2xl transition-all font-bold text-sm <?php echo $filter === $item['id'] ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-100' : 'text-slate-500 hover:bg-slate-50'; ?>">
                                    <div class="flex items-center gap-3"><?php echo icon($item['icon'], 18); ?> <?php echo $item['label']; ?></div>
                                </a>
                            <?php endforeach; ?>
                        </div>
                        <div class="bg-gradient-to-br from-indigo-600 to-indigo-700 p-6 rounded-3xl text-white shadow-xl shadow-indigo-100 relative overflow-hidden">
                             <?php echo icon('waves', 100, 'absolute -bottom-4 -right-4 opacity-10 rotate-12'); ?>
                            <div class="relative z-10 space-y-4">
                                <h4 class="font-black text-lg">Focus Aujourd'hui</h4>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-2"><span class="text-indigo-200"><?php echo icon('clock', 14); ?></span><span class="text-xs font-bold">12 séances prévues</span></div>
                                    <div class="flex items-center gap-2"><span class="text-amber-300"><?php echo icon('alert-triangle', 14); ?></span><span class="text-xs font-bold">3 impayés critiques</span></div>
                                    <div class="flex items-center gap-2"><span class="text-emerald-300"><?php echo icon('trending-up', 14); ?></span><span class="text-xs font-bold">+8 nouveaux membres</span></div>
                                </div>
                                <button class="w-full py-3 bg-white/20 hover:bg-white/30 rounded-xl text-xs font-black transition-all">Générer rapport du jour</button>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-3 space-y-4">
                        <?php if(empty($notifications)): ?>
                            <div class="bg-white py-24 rounded-3xl border-2 border-dashed border-slate-100 text-center space-y-4">
                                <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto text-slate-200"><?php echo icon('bell', 40); ?></div>
                                <div>
                                    <h3 class="text-xl font-black text-slate-900">Tout est calme !</h3>
                                    <p class="text-slate-400 font-medium">Aucune notification ne correspond à ce filtre.</p>
                                </div>
                            </div>
                        <?php else: ?>
                            <?php foreach ($notifications as $notif): 
                                $iconInfo = getNotificationIconInfo($notif['type']);
                            ?>
                            <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden group hover:border-indigo-200 transition-all <?php if(!$notif['isRead']) echo 'border-l-4 border-l-indigo-600'; ?>">
                                <div class="p-6 flex flex-col md:flex-row gap-6">
                                    <div class="h-16 w-16 shrink-0 rounded-2xl flex items-center justify-center relative <?php echo $iconInfo['bg']; ?>">
                                        <?php echo icon($iconInfo['icon'], 20, $iconInfo['color']); ?>
                                        <span class="absolute -top-1 -right-1 w-3 h-3 rounded-full border-2 border-white <?php echo getPriorityColor($notif['priority']); ?>"></span>
                                    </div>
                                    <div class="flex-1 min-w-0 space-y-1">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <span class="text-[10px] font-black uppercase text-slate-400 tracking-widest"><?php echo htmlspecialchars($notif['type']); ?></span>
                                                <?php if (!$notif['isRead']): ?><span class="px-1.5 py-0.5 bg-indigo-600 text-white text-[8px] font-black rounded-full uppercase">Nouveau</span><?php endif; ?>
                                            </div>
                                            <span class="text-[10px] font-bold text-slate-400 flex items-center gap-1"><?php echo icon('clock', 10); ?> <?php echo htmlspecialchars($notif['time']); ?></span>
                                        </div>
                                        <h3 class="text-lg font-black text-slate-900 leading-tight"><?php echo htmlspecialchars($notif['title']); ?></h3>
                                        <p class="text-sm text-slate-500 font-medium leading-relaxed"><?php echo htmlspecialchars($notif['description']); ?></p>
                                        
                                        <?php if ($notif['type'] === 'payment'): ?>
                                        <div class="pt-4 flex items-center gap-3">
                                            <button class="flex items-center gap-2 px-4 py-2 bg-rose-600 text-white text-xs font-black rounded-xl shadow-lg shadow-rose-100 hover:bg-rose-700 transition-all active:scale-95">
                                                <?php echo icon('message-square', 14); ?> Relancer par WhatsApp
                                            </button>
                                            <button class="text-xs font-bold text-slate-400 hover:text-slate-600">Détails facture</button>
                                        </div>
                                        <?php elseif ($notif['type'] === 'session'): ?>
                                        <div class="pt-4 flex items-center gap-3">
                                            <button class="flex items-center gap-2 px-4 py-2 bg-indigo-600 text-white text-xs font-black rounded-xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95">
                                                <?php echo icon('external-link', 14); ?> Voir la liste d'appel
                                            </button>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="flex md:flex-col justify-end gap-2">
                                        <button class="p-3 bg-slate-50 rounded-2xl text-slate-400 hover:bg-indigo-50 hover:text-indigo-600 transition-all">
                                            <?php echo icon('check-circle', 20); ?>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php renderDropdownScript(); ?>
</body>
</html>