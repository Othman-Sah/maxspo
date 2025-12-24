<?php
require_once ROOT_PATH . '/helpers/Icons.php';

function renderNotificationsDropdown() {
    $mockData = include ROOT_PATH . '/config/MockData.php';
    $notifications = $mockData['notifications'] ?? [];
    $unreadCount = count(array_filter($notifications, fn($n) => !$n['isRead']));

    $getIcon = function($type) {
        switch ($type) {
            case 'payment': return icon('credit-card', 16, 'text-rose-500');
            case 'session': return icon('calendar', 16, 'text-indigo-500');
            case 'system': return icon('settings', 16, 'text-slate-500');
            case 'member': return icon('user-plus', 16, 'text-emerald-500');
            default: return '';
        }
    };
    ?>
    <div id="notifications-dropdown" class="absolute top-full right-0 mt-4 w-96 bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden z-50 hidden animate-in fade-in slide-in-from-top-2 duration-300">
        <div class="p-5 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
            <div class="flex items-center gap-2">
                <h3 class="font-black text-slate-900">Notifications</h3>
                <?php if ($unreadCount > 0): ?>
                    <span class="bg-rose-500 text-white text-[10px] font-black px-2 py-0.5 rounded-full">
                        <?php echo $unreadCount; ?> NOUVEAUX
                    </span>
                <?php endif; ?>
            </div>
            <button class="text-xs font-bold text-indigo-600 hover:text-indigo-700">Tout marquer comme lu</button>
        </div>

        <div class="max-h-[400px] overflow-y-auto divide-y divide-slate-50">
            <?php foreach (array_slice($notifications, 0, 5) as $notif): ?>
                <div class="p-4 flex gap-4 hover:bg-slate-50 transition-colors cursor-pointer group <?php echo !$notif['isRead'] ? 'bg-indigo-50/30' : ''; ?>">
                    <div class="mt-1 h-10 w-10 shrink-0 rounded-2xl flex items-center justify-center <?php 
                        echo $notif['type'] === 'payment' ? 'bg-rose-50' : 
                            ($notif['type'] === 'session' ? 'bg-indigo-50' : 
                            ($notif['type'] === 'member' ? 'bg-emerald-50' : 'bg-slate-100'));
                    ?>">
                        <?php echo $getIcon($notif['type']); ?>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between mb-0.5">
                            <p class="text-sm font-black text-slate-900 truncate"><?php echo htmlspecialchars($notif['title']); ?></p>
                            <?php if ($notif['priority'] === 'high'): ?>
                                <span class="flex h-2 w-2 rounded-full bg-rose-500 ring-4 ring-rose-100"></span>
                            <?php endif; ?>
                        </div>
                        <p class="text-xs text-slate-500 font-medium line-clamp-2 leading-relaxed">
                            <?php echo htmlspecialchars($notif['description']); ?>
                        </p>
                        <p class="text-[10px] font-bold text-slate-400 mt-2 uppercase flex items-center gap-1">
                            <?php echo icon('clock', 10); ?>
                            <?php echo htmlspecialchars($notif['time']); ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <a href="index.php?page=notifications" class="w-full p-4 bg-slate-900 text-white text-sm font-black flex items-center justify-center gap-2 hover:bg-slate-800 transition-all group">
            Voir tout l'historique
            <?php echo icon('arrow-right', 16, 'group-hover:translate-x-1 transition-transform'); ?>
        </a>
    </div>
    <?php
}

function renderProfileDropdown() {
    $user = getCurrentUser();
    $menuItems = [
        ['id' => 'settings-profile', 'label' => 'Mon Profil', 'icon' => 'user', 'desc' => 'Infos personnelles', 'section' => 'profile'],
        ['id' => 'settings', 'label' => 'Paramètres', 'icon' => 'settings', 'desc' => 'Configuration du club', 'section' => 'general'],
        ['id' => 'settings-security', 'label' => 'Sécurité', 'icon' => 'shield', 'desc' => 'Mot de passe & 2FA', 'section' => 'security'],
    ];
    ?>
    <div id="profile-dropdown" class="absolute top-full right-0 mt-4 w-72 bg-white rounded-3xl shadow-2xl border border-slate-100 overflow-hidden z-50 hidden animate-in fade-in slide-in-from-top-2 duration-300">
        <div class="p-6 bg-slate-50 border-b border-slate-100">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-2xl bg-indigo-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-100">
                    <?php echo strtoupper(substr($user['firstName'] ?? 'A', 0, 1) . substr($user['lastName'] ?? 'C', 0, 1)); ?>
                </div>
                <div class="min-w-0">
                    <p class="text-sm font-black text-slate-900 truncate"><?php echo htmlspecialchars($user['firstName'] ?? 'Admin') . ' ' . htmlspecialchars($user['lastName'] ?? 'Coach'); ?></p>
                    <p class="text-xs font-bold text-slate-400"><?php echo htmlspecialchars($user['email'] ?? 'super-admin@needsport.ma'); ?></p>
                </div>
            </div>
        </div>

        <div class="p-2">
            <?php foreach ($menuItems as $item): ?>
                <a href="index.php?page=settings&section=<?php echo $item['section']; ?>" class="w-full flex items-center gap-3 p-3 rounded-2xl hover:bg-slate-50 transition-all group text-left">
                    <div class="h-10 w-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 group-hover:text-indigo-600 group-hover:border-indigo-100 transition-all">
                        <?php echo icon($item['icon'], 20); ?>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-black text-slate-900 leading-tight"><?php echo $item['label']; ?></p>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter"><?php echo $item['desc']; ?></p>
                    </div>
                    <?php echo icon('chevron-right', 14, 'text-slate-200 group-hover:text-slate-400 transition-colors'); ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="p-2 border-t border-slate-50">
            <a href="index.php?action=logout" class="w-full flex items-center gap-3 p-3 rounded-2xl hover:bg-rose-50 text-rose-500 transition-all group text-left">
                <div class="h-10 w-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-rose-300 group-hover:text-rose-500 group-hover:border-rose-100 transition-all">
                    <?php echo icon('log-out', 20); ?>
                </div>
                <div>
                    <p class="text-sm font-black leading-tight">Déconnexion</p>
                    <p class="text-[10px] font-bold uppercase opacity-60">Quitter la session</p>
                </div>
            </a>
        </div>
        
        <div class="p-4 bg-indigo-600 flex items-center justify-between text-white">
            <span class="text-[10px] font-black uppercase tracking-widest">Version Pro 2.4</span>
            <?php echo icon('external-link', 12, 'opacity-50'); ?>
        </div>
    </div>
    <?php
}

function renderDropdownScript() {
    ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationButton = document.getElementById('notifications-button');
            const notificationDropdown = document.getElementById('notifications-dropdown');
            const profileButton = document.getElementById('profile-button');
            const profileDropdown = document.getElementById('profile-dropdown');

            function toggleDropdown(dropdown) {
                if (dropdown) {
                    dropdown.classList.toggle('hidden');
                }
            }

            notificationButton?.addEventListener('click', function(event) {
                event.stopPropagation();
                toggleDropdown(notificationDropdown);
                profileDropdown?.classList.add('hidden');
            });

            profileButton?.addEventListener('click', function(event) {
                event.stopPropagation();
                toggleDropdown(profileDropdown);
                notificationDropdown?.classList.add('hidden');
            });

            document.addEventListener('click', function(event) {
                if (notificationDropdown && !notificationDropdown.contains(event.target)) {
                    notificationDropdown.classList.add('hidden');
                }
                if (profileDropdown && !profileDropdown.contains(event.target)) {
                    profileDropdown.classList.add('hidden');
                }
            });
        });
    </script>
    <?php
}
?>