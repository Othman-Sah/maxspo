<?php
/**
 * StatCard Component - Displays dashboard statistics
 * @param string $title Card title
 * @param mixed $value The stat value
 * @param float $trend Trend percentage
 * @param string $icon Icon name
 * @param string $color Color class
 * @param string $prefix Optional prefix
 */
require_once ROOT_PATH . '/helpers/Icons.php';

function renderStatCard($title, $value, $trend, $icon, $color, $prefix = '') {
    $isPositive = $trend >= 0;
    $trendClass = $isPositive ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600';
    $arrowIcon = $isPositive ? '↗' : '↘';
    ?>
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-all duration-200 group">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 rounded-xl bg-opacity-10 transition-transform group-hover:scale-110 bg-<?php echo $color; ?>">
                <i class="text-lg"><?php echo chartIcon(24); ?></i>
            </div>
            <div class="flex items-center gap-1 text-sm font-semibold px-2 py-1 rounded-full <?php echo $trendClass; ?>">
                <?php echo $arrowIcon; ?> <?php echo abs($trend); ?>%
            </div>
        </div>
        <div>
            <p class="text-slate-500 text-sm font-medium"><?php echo htmlspecialchars($title); ?></p>
            <h3 class="text-2xl font-bold text-slate-900 mt-1">
                <?php echo htmlspecialchars($prefix . $value); ?>
            </h3>
        </div>
    </div>
    <?php
}

/**
 * Member Row Component - Table row for member display
 */
function renderMemberRow($member) {
    $isExpiringSoon = isExpiringsSoon($member['expiryDate']);
    $statusClass = $isExpiringSoon ? 'text-rose-600' : 'text-slate-700';
    $expiryFormatted = formatDate($member['expiryDate']);
    $fullName = $member['firstName'] . ' ' . $member['lastName'];
    $initials = substr($member['firstName'], 0, 1) . substr($member['lastName'], 0, 1);
    ?>
    <tr class="hover:bg-slate-50 transition-colors group">
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold border-2 border-white shadow-sm overflow-hidden shrink-0">
                    <?php echo htmlspecialchars($initials); ?>
                </div>
                <div class="ml-3 min-w-0">
                    <div class="text-sm font-semibold text-slate-900 flex items-center gap-2 truncate">
                        <?php echo htmlspecialchars($fullName); ?>
                        <?php if ($member['isLoyal']): ?>
                            <span class="text-amber-400">★</span>
                        <?php endif; ?>
                    </div>
                    <div class="text-xs text-slate-500 truncate"><?php echo htmlspecialchars($member['email']); ?></div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">
                <?php echo htmlspecialchars($member['sport']); ?>
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex flex-col">
                <span class="text-sm font-medium <?php echo $statusClass; ?>">
                    <?php echo $expiryFormatted; ?>
                </span>
                <?php if ($isExpiringSoon): ?>
                    <span class="text-[10px] font-bold text-rose-500 uppercase">Attention</span>
                <?php endif; ?>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center gap-2">
                <button class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Email">
                    <?php echo mailIcon(16); ?>
                </button>
                <button class="p-1.5 text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors" title="Phone">
                    <?php echo phoneIcon(16); ?>
                </button>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <button class="text-indigo-600 hover:text-indigo-900 font-semibold text-xs py-1.5 px-3 bg-indigo-50 rounded-lg transition-all active:scale-95">
                Renouveler
            </button>
        </td>
    </tr>
    <?php
}

/**
 * Activity Card Component
 */
function renderActivityCard($activity) {
    $color = $activity['color'] ?? 'from-indigo-500 to-blue-600';
    ?>
    <div class="bg-white rounded-[32px] border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden group">
        <div class="h-2 bg-gradient-to-r <?php echo $color; ?>"></div>
        <div class="p-8">
            <div class="flex items-start justify-between mb-6">
                <div class="h-16 w-16 rounded-2xl bg-gradient-to-br <?php echo $color; ?> text-white flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-500 text-2xl">
                    <?php echo dumbbellIcon(32); ?>
                </div>
                <span class="flex items-center gap-1.5 px-3 py-1 bg-amber-50 text-amber-600 text-[10px] font-black uppercase rounded-full border border-amber-100">
                    🧖 Sauna Inclus
                </span>
            </div>
            <h3 class="text-2xl font-black text-slate-900 mb-2"><?php echo htmlspecialchars($activity['name']); ?></h3>
            <div class="bg-slate-50 rounded-2xl p-5 mb-6">
                <div class="flex items-baseline gap-1">
                    <span class="text-4xl font-black text-indigo-600"><?php echo $activity['monthlyPrice']; ?></span>
                    <span class="text-slate-500 font-bold">DH</span>
                </div>
            </div>
            <div class="flex gap-3">
                <button class="flex-1 flex items-center justify-center gap-2 py-3 bg-slate-50 text-slate-600 font-bold rounded-xl hover:bg-slate-100 transition-colors border border-slate-100">
                    <?php echo editIcon(18); ?> Modifier
                </button>
                <button class="p-3 text-rose-500 hover:bg-rose-50 rounded-xl transition-colors border border-transparent hover:border-rose-100">
                    <?php echo trashIcon(18); ?>
                </button>
            </div>
        </div>
    </div>
    <?php
}

/**
 * Status Badge Component
 */
function renderStatusBadge($status) {
    $badges = [
        'actif' => ['class' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'icon' => 'check', 'text' => 'Actif'],
        'expirant' => ['class' => 'bg-amber-50 text-amber-600 border-amber-100 animate-pulse', 'icon' => 'alert', 'text' => 'Expire bientôt'],
        'expire' => ['class' => 'bg-rose-50 text-rose-600 border-rose-100', 'icon' => 'x', 'text' => 'Expiré'],
        'present' => ['class' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'icon' => 'check', 'text' => 'Présent'],
        'absent' => ['class' => 'bg-rose-50 text-rose-600 border-rose-100', 'icon' => 'x', 'text' => 'Absent'],
        'en_pause' => ['class' => 'bg-amber-50 text-amber-600 border-amber-100', 'icon' => 'alert', 'text' => 'En Pause'],
        'valide' => ['class' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'icon' => 'check', 'text' => 'Validé'],
        'en_attente' => ['class' => 'bg-amber-50 text-amber-600 border-amber-100', 'icon' => 'alert', 'text' => 'En attente'],
        'paye' => ['class' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'icon' => 'check', 'text' => 'Réglé'],
    ];
    
    $badge = $badges[$status] ?? ['class' => 'bg-slate-50 text-slate-600 border-slate-100', 'icon' => '•', 'text' => $status];
    ?>
    <span class="px-2.5 py-1 text-xs font-bold rounded-full border <?php echo $badge['class']; ?>">
        <?php echo htmlspecialchars($badge['text']); ?>
    </span>
    <?php
}

/**
 * Alert Box Component
 */
function renderAlert($type, $message, $icon = '!') {
    $colors = [
        'info' => 'bg-indigo-50 border-indigo-100 text-indigo-700',
        'warning' => 'bg-amber-50 border-amber-100 text-amber-700',
        'success' => 'bg-emerald-50 border-emerald-100 text-emerald-700',
        'error' => 'bg-rose-50 border-rose-100 text-rose-700'
    ];
    $color = $colors[$type] ?? $colors['info'];
    ?>
    <div class="p-4 <?php echo $color; ?> rounded-xl border flex items-center gap-3">
        <span class="font-black text-lg"><?php echo $icon; ?></span>
        <span class="text-sm font-medium"><?php echo htmlspecialchars($message); ?></span>
    </div>
    <?php
}

/**
 * Button Component
 */
function renderButton($text, $class = 'primary', $onClick = '', $icon = '') {
    $styles = [
        'primary' => 'bg-indigo-600 text-white hover:bg-indigo-700 shadow-lg shadow-indigo-100',
        'secondary' => 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50',
        'danger' => 'bg-rose-600 text-white hover:bg-rose-700 shadow-lg shadow-rose-100',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-lg shadow-emerald-100',
    ];
    $style = $styles[$class] ?? $styles['primary'];
    ?>
    <button class="px-6 py-2.5 font-bold rounded-xl transition-all active:scale-95 <?php echo $style; ?>" 
            onclick="<?php echo htmlspecialchars($onClick); ?>">
        <?php if ($icon): ?><span class="mr-2"><?php echo $icon; ?></span><?php endif; ?>
        <?php echo htmlspecialchars($text); ?>
    </button>
    <?php
}
?>
