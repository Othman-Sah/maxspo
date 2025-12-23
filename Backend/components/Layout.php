<?php
/**
 * Header Component
 */
require_once ROOT_PATH . '/helpers/Icons.php';

function renderHeader() {
    $currentPage = getParam('page', 'dashboard');
    $user = getCurrentUser();
    ?>
    <header class="h-20 bg-white border-b border-slate-200 px-8 flex items-center justify-between sticky top-0 z-20">
        <div class="flex-1 max-w-xl relative group">
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"><?php echo icon('mail', 16); ?></span>
            <input 
                type="text" 
                placeholder="Rechercher un membre, un paiement..." 
                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border-transparent focus:bg-white focus:border-indigo-500 rounded-xl outline-none transition-all text-sm font-medium"
            />
        </div>
        
        <div class="flex items-center gap-4 ml-8">
            <!-- Notifications -->
            <div class="relative">
                <button class="relative text-slate-500 hover:text-slate-900 p-2.5 rounded-xl transition-all hover:bg-slate-50">
                    <?php echo bellIcon(20); ?>
                    <span class="absolute top-1 right-1 w-4 h-4 bg-rose-500 text-white text-[10px] font-black flex items-center justify-center rounded-full border-2 border-white">3</span>
                </button>
            </div>
            
            <div class="h-10 w-px bg-slate-200 mx-2"></div>
            
            <!-- Profile -->
            <div class="relative">
                <button class="flex items-center gap-3 p-1.5 pr-3 rounded-2xl transition-all border border-transparent hover:bg-slate-50">
                    <div class="h-10 w-10 rounded-xl bg-indigo-600 flex items-center justify-center text-white font-black shadow-md shadow-indigo-100">
                        <?php echo strtoupper(substr($user['firstName'] ?? 'A', 0, 1) . substr($user['lastName'] ?? 'C', 0, 1)); ?>
                    </div>
                    <div class="text-left hidden sm:block">
                        <p class="text-sm font-black text-slate-900 leading-none"><?php echo htmlspecialchars($user['firstName'] ?? 'Admin') . ' ' . htmlspecialchars($user['lastName'] ?? 'Coach'); ?></p>
                        <p class="text-[10px] font-bold text-slate-400 mt-1 uppercase tracking-tight">Super Admin</p>
                    </div>
                </button>
            </div>
        </div>
    </header>
    <?php
}

/**
 * Sidebar Component
 */
function renderSidebar($activePage) {
    ?>
    <aside class="w-64 bg-white border-r border-slate-200 flex flex-col h-screen sticky top-0 shrink-0">
        <div class="p-6 flex items-center gap-3">
            <div class="bg-indigo-600 p-2 rounded-xl text-white shadow-lg shadow-indigo-200">
                <?php echo trophyIcon(24); ?>
            </div>
            <span class="text-xl font-bold text-slate-900 tracking-tight">NEEDSPORT</span>
        </div>

        <nav class="flex-1 px-4 py-2 space-y-1 overflow-y-auto">
            <!-- Dashboard -->
            <a href="index.php?page=dashboard" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 font-medium <?php echo $activePage === 'dashboard' ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900'; ?>">
                <?php echo dashboardIcon(20); ?> Tableau de bord
            </a>

            <!-- Quick Actions -->
            <div class="py-4 border-y border-slate-50 my-2">
                <p class="px-4 text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Actions rapides</p>
                <div class="space-y-2 px-1">
                    <a href="index.php?page=add-member" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold text-xs bg-slate-900 text-white hover:bg-slate-800 shadow-md shadow-slate-100">
                        <?php echo plusIcon(16); ?> Nouveau membre
                    </a>
                    <a href="index.php?page=add-activity" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-bold text-xs bg-white border-2 border-slate-100 text-slate-600 hover:border-emerald-200 hover:text-emerald-600">
                        <?php echo zapIcon(16); ?> Nouvelle Activité
                    </a>
                </div>
            </div>

            <!-- Navigation Items -->
            <?php
            $navItems = [
                'members' => ['icon' => usersIcon(20), 'label' => 'Membres'],
                'sports' => ['icon' => dumbbellIcon(20), 'label' => 'Activités'],
                'schedule' => ['icon' => calendarIcon(20), 'label' => 'Planning'],
                'financials' => ['icon' => chartIcon(20), 'label' => 'Finances'],
                'staff' => ['icon' => userIcon(20), 'label' => 'Équipe Staff'],
                'pos' => ['icon' => icon('shopping-cart', 20), 'label' => 'Caisse POS'],
                'payments' => ['icon' => creditcardIcon(20), 'label' => 'Journal Paiements'],
            ];
            foreach ($navItems as $id => $item):
                $isActive = $activePage === $id ? 'bg-indigo-50 text-indigo-600' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900';
                ?>
                <a href="index.php?page=<?php echo $id; ?>" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 font-medium <?php echo $isActive; ?>">
                    <?php echo $item['icon']; ?> <?php echo $item['label']; ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <div class="p-4 border-t border-slate-100 space-y-1">
            <a href="index.php?page=settings" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all font-medium text-slate-500 hover:bg-slate-50 hover:text-slate-900">
                ⚙️ Paramètres
            </a>
            <a href="index.php?action=logout" class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-500 hover:bg-red-50 transition-all font-medium">
                🚪 Déconnexion
            </a>
        </div>
    </aside>
    <?php
}

/**
 * Page Layout Wrapper
 */
function renderPageLayout($title, $description = '', $content) {
    ?>
    <main class="flex-1 min-w-0 overflow-auto">
        <?php renderHeader(); ?>
        
        <div class="p-8">
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-2">
                        <?php echo htmlspecialchars($title); ?>
                    </h1>
                    <?php if ($description): ?>
                        <p class="text-slate-500 font-medium mt-1"><?php echo htmlspecialchars($description); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <?php echo $content; ?>
        </div>
    </main>
    <?php
}
?>
