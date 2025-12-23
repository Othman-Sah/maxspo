<?php
/**
 * Schedule / Planning View
 * Weekly calendar with activities and time slots
 */

require_once ROOT_PATH . '/controllers/DashboardController.php';
require_once ROOT_PATH . '/components/Layout.php';
require_once ROOT_PATH . '/helpers/Icons.php';

$controller = new DashboardController($db);
$currentPage = 'schedule';
?>

<?php renderHeader(); ?>

<div class="flex h-screen bg-slate-50">
    <?php renderSidebar($currentPage); ?>

    <main class="flex-1 overflow-auto">
        <div class="p-8 space-y-8">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-black text-slate-900">Planning</h1>
                    <p class="text-slate-500 mt-1">Semaine du 20 au 26 Décembre 2024</p>
                </div>
                <button class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all flex items-center gap-2">
                    <?php echo plusIcon(18); ?>
                    Ajouter Session
                </button>
            </div>

            <!-- Week Navigation -->
            <div class="bg-white rounded-2xl border border-slate-100 p-4 flex items-center justify-between">
                <button class="p-2 hover:bg-slate-100 rounded-lg transition">
                    <?php echo icon('chevron-left', 20); ?>
                </button>
                <div class="text-center">
                    <p class="text-sm font-bold text-slate-500 uppercase">Semaine actuelle</p>
                    <p class="text-lg font-black text-slate-900">20 - 26 Dec 2024</p>
                </div>
                <button class="p-2 hover:bg-slate-100 rounded-lg transition">
                    <?php echo icon('chevron-right', 20); ?>
                </button>
            </div>

            <!-- Weekly Calendar -->
            <div class="grid grid-cols-7 gap-4">
                <?php
                $days = [
                    ['name' => 'Lun', 'date' => '20', 'sessions' => 3],
                    ['name' => 'Mar', 'date' => '21', 'sessions' => 2],
                    ['name' => 'Mer', 'date' => '22', 'sessions' => 4],
                    ['name' => 'Jeu', 'date' => '23', 'sessions' => 3],
                    ['name' => 'Ven', 'date' => '24', 'sessions' => 2],
                    ['name' => 'Sam', 'date' => '25', 'sessions' => 5],
                    ['name' => 'Dim', 'date' => '26', 'sessions' => 1],
                ];

                foreach ($days as $day):
                    $isToday = $day['date'] === '23';
                    $dayClass = $isToday ? 'bg-indigo-50 border-indigo-200' : 'bg-white';
                ?>
                <div class="<?php echo $dayClass; ?> rounded-2xl border border-slate-100 p-4 text-center">
                    <p class="text-sm font-bold text-slate-500 uppercase"><?php echo $day['name']; ?></p>
                    <p class="text-3xl font-black text-slate-900 my-2"><?php echo $day['date']; ?></p>
                    <div class="space-y-2">
                        <?php for ($i = 0; $i < min($day['sessions'], 3); $i++): ?>
                            <div class="text-xs px-2 py-1 rounded-lg bg-emerald-100 text-emerald-700 font-semibold truncate">
                                Session <?php echo $i + 1; ?>
                            </div>
                        <?php endfor; ?>
                        <?php if ($day['sessions'] > 3): ?>
                            <p class="text-[10px] text-slate-500 font-bold">+<?php echo $day['sessions'] - 3; ?> de plus</p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Detailed Schedule -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Mercredi 22 Décembre</h2>
                </div>
                
                <div class="divide-y divide-slate-100">
                    <?php
                    $sessions = [
                        ['time' => '06:00 - 07:30', 'activity' => 'Fitness / Cardio', 'trainer' => 'Ahmed Hassan', 'capacity' => '15/20', 'status' => 'Confirmé'],
                        ['time' => '07:30 - 09:00', 'activity' => 'Football', 'trainer' => 'Mohamed El Kouri', 'capacity' => '22/22', 'status' => 'Complet'],
                        ['time' => '09:00 - 10:30', 'activity' => 'Musculation', 'trainer' => 'Fatima Bennani', 'capacity' => '8/15', 'status' => 'Confirmé'],
                        ['time' => '17:00 - 18:30', 'activity' => 'Yoga', 'trainer' => 'Sara Alami', 'capacity' => '12/20', 'status' => 'Confirmé'],
                    ];

                    foreach ($sessions as $session):
                    ?>
                    <div class="p-6 hover:bg-slate-50 transition-colors flex items-center justify-between group">
                        <div class="flex-1">
                            <div class="flex items-center gap-4">
                                <div class="text-left">
                                    <p class="text-sm font-black text-slate-600"><?php echo $session['time']; ?></p>
                                    <p class="text-lg font-bold text-slate-900 mt-1"><?php echo $session['activity']; ?></p>
                                </div>
                                <div class="text-left ml-8">
                                    <p class="text-xs text-slate-500 font-bold">Entraîneur</p>
                                    <p class="text-sm font-semibold text-slate-900"><?php echo $session['trainer']; ?></p>
                                </div>
                                <div class="text-left ml-8">
                                    <p class="text-xs text-slate-500 font-bold">Capacité</p>
                                    <p class="text-sm font-semibold text-slate-900"><?php echo $session['capacity']; ?></p>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 text-xs font-bold rounded-full bg-emerald-50 text-emerald-600">
                                <?php echo checkIcon(14); ?> <?php echo $session['status']; ?>
                            </span>
                            <button class="p-2 hover:bg-slate-200 rounded-lg transition opacity-0 group-hover:opacity-100">
                                <?php echo editIcon(18); ?>
                            </button>
                            <button class="p-2 hover:bg-rose-100 rounded-lg transition opacity-0 group-hover:opacity-100 text-rose-600">
                                <?php echo trashIcon(18); ?>
                            </button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Add Session Form -->
            <div class="bg-white rounded-2xl border border-slate-100 p-8">
                <h2 class="text-xl font-bold text-slate-900 mb-6">Ajouter une Nouvelle Session</h2>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Activité</label>
                        <select class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold">
                            <option>Fitness / Cardio</option>
                            <option>Football</option>
                            <option>Musculation</option>
                            <option>Yoga</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Entraîneur</label>
                        <select class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold">
                            <option>Ahmed Hassan</option>
                            <option>Mohamed El Kouri</option>
                            <option>Fatima Bennani</option>
                            <option>Sara Alami</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Date</label>
                        <input type="date" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold" />
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Heure de Début</label>
                        <input type="time" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold" />
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Heure de Fin</label>
                        <input type="time" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold" />
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Capacité Maximale</label>
                        <input type="number" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold" />
                    </div>
                    <div class="md:col-span-2 flex gap-3">
                        <button type="submit" class="flex-1 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all flex items-center justify-center gap-2">
                            <?php echo checkIcon(18); ?>
                            Créer Session
                        </button>
                        <button type="reset" class="flex-1 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>
