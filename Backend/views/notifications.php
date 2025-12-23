<?php
/**
 * Notifications View
 * Notification center and alerts
 */

require_once ROOT_PATH . '/controllers/DashboardController.php';
require_once ROOT_PATH . '/components/Layout.php';
require_once ROOT_PATH . '/helpers/Icons.php';

$controller = new DashboardController($db);
$currentPage = 'notifications';
?>

<?php renderHeader(); ?>

<div class="flex h-screen bg-slate-50">
    <?php renderSidebar($currentPage); ?>

    <main class="flex-1 overflow-auto max-w-4xl mx-auto">
        <div class="p-8 space-y-6">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-black text-slate-900">Notifications</h1>
                    <p class="text-slate-500 mt-1">Vous avez <span class="font-bold text-indigo-600">7</span> notifications non lues</p>
                </div>
                <button class="px-4 py-2 text-slate-600 text-sm font-bold border border-slate-200 rounded-lg hover:bg-slate-50 transition-all">
                    Marquer tout comme lu
                </button>
            </div>

            <!-- Notification Categories -->
            <div class="flex gap-2 border-b border-slate-200">
                <button class="px-4 py-3 font-bold text-slate-900 border-b-2 border-indigo-600">Toutes</button>
                <button class="px-4 py-3 font-semibold text-slate-500 border-b-2 border-transparent hover:text-slate-900">Alertes</button>
                <button class="px-4 py-3 font-semibold text-slate-500 border-b-2 border-transparent hover:text-slate-900">Messages</button>
                <button class="px-4 py-3 font-semibold text-slate-500 border-b-2 border-transparent hover:text-slate-900">Anniversaires</button>
            </div>

            <!-- Notifications List -->
            <div class="space-y-4">
                <?php
                $notifications = [
                    [
                        'type' => 'alert',
                        'icon' => 'alert',
                        'color' => 'rose',
                        'title' => '⚠️ 5 Membres Expirant Bientôt',
                        'message' => 'Ahmed Ben Salem, Leila Mahmoud et 3 autres membres expirent dans les 7 jours.',
                        'time' => 'À l\'instant',
                        'unread' => true
                    ],
                    [
                        'type' => 'success',
                        'icon' => 'check',
                        'color' => 'emerald',
                        'title' => '✅ Paiement Confirmé',
                        'message' => 'Le paiement de Mohamed Karim (1,500 DH) a été traité avec succès.',
                        'time' => 'Il y a 2h',
                        'unread' => true
                    ],
                    [
                        'type' => 'info',
                        'icon' => 'bell',
                        'color' => 'indigo',
                        'title' => 'ℹ️ Nouvelle Inscription',
                        'message' => 'Fatima Aziz a rejoint le club et s\'est inscrite au cours de Yoga.',
                        'time' => 'Il y a 4h',
                        'unread' => true
                    ],
                    [
                        'type' => 'warning',
                        'icon' => 'alert',
                        'color' => 'amber',
                        'title' => '⚠️ Accès Peu Fréquent',
                        'message' => 'Nadia Bennani n\'a pas visité le club depuis 30 jours.',
                        'time' => 'Il y a 1j',
                        'unread' => false
                    ],
                    [
                        'type' => 'info',
                        'icon' => 'calendar',
                        'color' => 'indigo',
                        'title' => '🎂 Anniversaire Demain',
                        'message' => 'Karim Tijani aura 30 ans demain. Envoyez-lui un message!',
                        'time' => 'Il y a 2j',
                        'unread' => false
                    ],
                    [
                        'type' => 'success',
                        'icon' => 'check',
                        'color' => 'emerald',
                        'title' => '✅ Rapport Généré',
                        'message' => 'Le rapport financier mensuel a été généré avec succès.',
                        'time' => 'Il y a 3j',
                        'unread' => false
                    ],
                    [
                        'type' => 'info',
                        'icon' => 'users',
                        'color' => 'indigo',
                        'title' => 'Nouvel Entraîneur Ajouté',
                        'message' => 'Dr. Sarah Alami a rejoint l\'équipe en tant qu\'instructrice Yoga.',
                        'time' => 'Il y a 5j',
                        'unread' => false
                    ],
                ];

                foreach ($notifications as $notif):
                    $bgClass = $notif['unread'] ? "bg-{$notif['color']}-50" : 'bg-white';
                    $borderClass = $notif['unread'] ? "border-{$notif['color']}-200" : 'border-slate-100';
                ?>
                <div class="<?php echo $bgClass; ?> border <?php echo $borderClass; ?> rounded-2xl p-4 hover:shadow-md transition-all cursor-pointer">
                    <div class="flex items-start gap-4">
                        <div class="p-3 rounded-xl bg-white bg-opacity-50 flex-shrink-0">
                            <?php echo icon($notif['icon'], 20); ?>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-slate-900"><?php echo $notif['title']; ?></h3>
                            <p class="text-sm text-slate-600 mt-1"><?php echo $notif['message']; ?></p>
                            <p class="text-xs text-slate-500 font-semibold mt-2"><?php echo $notif['time']; ?></p>
                        </div>
                        <?php if ($notif['unread']): ?>
                            <div class="w-3 h-3 rounded-full bg-indigo-600 flex-shrink-0 mt-1.5"></div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Empty State for older notifications -->
            <div class="text-center py-12">
                <p class="text-slate-500 text-sm font-medium">Vous avez tout lu!</p>
                <p class="text-slate-400 text-xs mt-1">Les anciennes notifications apparaîtront ici</p>
            </div>
        </div>
    </main>
</div>
