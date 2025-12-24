<?php
/**
 * Schedule / Planning View
 * Weekly calendar with activities and time slots - matching React ScheduleView
 */

require_once 'config/config.php';
require_once 'controllers/ScheduleController.php';
require_once 'components/Layout.php';
require_once 'helpers/Icons.php';
require_once 'components/Notifications.php';

requireLogin();

$controller = new ScheduleController($db);
$scheduleData = $controller->getWeeklySchedule();
$days = $controller->getDays();
$timeBlocks = $controller->getTimeBlocks();
$currentPage = 'schedule';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Planning</title>
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

            <div class="p-8 space-y-8 animate-in pb-12">
                <!-- Header Section -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                            <?php echo icon('calendar-days', 32, 'text-indigo-600'); ?>
                            Planning de la Semaine
                        </h1>
                        <p class="text-slate-500 font-medium mt-1">Gérez les créneaux horaires et l'occupation des salles par activité.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex bg-white border border-slate-200 rounded-xl p-1 shadow-sm">
                            <button class="p-2 hover:bg-slate-50 rounded-lg text-slate-400"><?php echo icon('chevron-left', 18); ?></button>
                            <div class="px-4 flex items-center font-bold text-sm text-slate-700">10 Juin - 16 Juin</div>
                            <button class="p-2 hover:bg-slate-50 rounded-lg text-slate-400"><?php echo icon('chevron-right', 18); ?></button>
                        </div>
                        <button class="flex items-center gap-2 px-6 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg shadow-slate-200 group">
                            <?php echo icon('settings-2', 18, 'group-hover:rotate-180 transition-transform duration-500'); ?>
                            Modifier le planning
                        </button>
                    </div>
                </div>

                <!-- Schedule Table -->
                <div class="bg-white rounded-[32px] border border-slate-100 shadow-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="p-6 w-40"></th>
                                    <?php foreach ($days as $index => $day): ?>
                                        <th class="p-6 text-center">
                                            <span class="text-xs font-black uppercase text-slate-400 tracking-widest block mb-1"><?php echo $day; ?></span>
                                            <span class="text-lg font-black text-slate-900"><?php echo 10 + $index; ?></span>
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($timeBlocks as $timeBlock): ?>
                                    <tr class="border-b border-slate-50 last:border-0">
                                        <td class="p-8 bg-slate-50/30">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-black text-slate-900"><?php echo $timeBlock['label']; ?></span>
                                                <span class="text-[10px] font-bold text-slate-400 uppercase flex items-center gap-1 mt-1">
                                                    <?php echo icon('clock', 10); ?>
                                                    <?php echo $timeBlock['time']; ?>
                                                </span>
                                            </div>
                                        </td>
                                        <?php foreach ($days as $day):
                                            $slot = null;
                                            foreach ($scheduleData as $s) {
                                                if ($s['day'] === $day && $s['block'] === $timeBlock['id']) {
                                                    $slot = $s;
                                                    break;
                                                }
                                            }
                                        ?>
                                            <td class="p-2 min-w-[160px]">
                                                <?php if ($slot): ?>
                                                    <div class="<?php echo $slot['color']; ?> p-4 rounded-2xl text-white shadow-lg shadow-slate-200 transition-all hover:scale-[1.02] cursor-pointer group relative overflow-hidden">
                                                        <?php echo icon($slot['icon'], 48, 'absolute -right-2 -bottom-2 opacity-10 group-hover:scale-125 transition-transform'); ?>
                                                        <div class="relative z-10 space-y-3">
                                                            <div class="flex items-center justify-between">
                                                                <?php echo icon($slot['icon'], 16); ?>
                                                                <span class="text-[8px] font-black uppercase px-2 py-0.5 rounded-full <?php echo $slot['capacity'] === 'Complet' ? 'bg-rose-600' : 'bg-black/20'; ?>">
                                                                    <?php echo htmlspecialchars($slot['capacity']); ?>
                                                                </span>
                                                            </div>
                                                            <h4 class="text-xs font-black leading-tight"><?php echo htmlspecialchars($slot['activity']); ?></h4>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="h-24 w-full border-2 border-dashed border-slate-50 rounded-2xl flex items-center justify-center group hover:border-indigo-100 hover:bg-indigo-50/30 transition-all cursor-pointer">
                                                        <?php echo icon('plus', 16, 'text-slate-200 group-hover:text-indigo-400'); ?>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Legend & Info -->
                <div class="flex flex-wrap items-center justify-between gap-6 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                    <div class="flex flex-wrap items-center gap-6">
                        <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-indigo-600"></div><span class="text-xs font-bold text-slate-500">Fitness</span></div>
                        <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-rose-500"></div><span class="text-xs font-bold text-slate-500">Boxe</span></div>
                        <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-emerald-500"></div><span class="text-xs font-bold text-slate-500">Yoga</span></div>
                        <div class="flex items-center gap-2"><div class="w-3 h-3 rounded-full bg-amber-500"></div><span class="text-xs font-bold text-slate-500">CrossFit</span></div>
                    </div>
                    <div class="flex items-center gap-2 text-[10px] font-black uppercase text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-full border border-indigo-100">
                        <?php echo icon('info', 12); ?>
                        Mise à jour en temps réel
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php renderDropdownScript(); ?>
</body>
</html>