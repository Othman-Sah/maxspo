<?php
/**
 * Add Activity / Nouvelle Activité View
 * Form to create new sports activities
 */

require_once ROOT_PATH . '/controllers/DashboardController.php';
require_once ROOT_PATH . '/components/Layout.php';
require_once ROOT_PATH . '/helpers/Icons.php';

$controller = new DashboardController($db);
$currentPage = 'sports';

$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'] ?? '',
        'description' => $_POST['description'] ?? '',
        'monthlyPrice' => $_POST['monthlyPrice'] ?? 0,
        'maxCapacity' => $_POST['maxCapacity'] ?? 0,
        'duration' => $_POST['duration'] ?? '',
        'trainer' => $_POST['trainer'] ?? '',
    ];

    // Validation
    if (empty($data['name']) || empty($data['trainer'])) {
        $error = "Veuillez remplir tous les champs obligatoires";
    } else {
        $success = "Activité '{$data['name']}' a été créée avec succès!";
    }
}
?>

<?php renderHeader(); ?>

<div class="flex h-screen bg-slate-50">
    <?php renderSidebar($currentPage); ?>

    <main class="flex-1 overflow-auto">
        <div class="p-8 max-w-4xl">
            <!-- Header -->
            <div class="mb-8">
                <h1 class="text-3xl font-black text-slate-900">Ajouter une Activité</h1>
                <p class="text-slate-500 mt-1">Créer un nouveau cours ou activité de sport</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl border border-slate-100 p-8">
                        <?php if ($error): ?>
                            <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-xl text-rose-700 text-sm font-medium flex items-center gap-2">
                                <?php echo icon('alert', 16); ?> <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($success): ?>
                            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-sm font-medium flex items-center gap-2">
                                <?php echo checkIcon(16); ?> <?php echo htmlspecialchars($success); ?>
                            </div>
                        <?php endif; ?>

                        <form method="POST" class="space-y-6">
                            <!-- Activity Name -->
                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Nom de l'Activité *</label>
                                <input 
                                    type="text" 
                                    name="name"
                                    placeholder="Ex: Zumba, Pilates, CrossFit..."
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold"
                                    required
                                />
                            </div>

                            <!-- Description -->
                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Description</label>
                                <textarea 
                                    name="description"
                                    placeholder="Décrivez l'activité, les bénéfices, le public cible..."
                                    rows="4"
                                    class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold"
                                ></textarea>
                            </div>

                            <!-- Price and Capacity -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Prix Mensuel (DH) *</label>
                                    <input 
                                        type="number" 
                                        name="monthlyPrice"
                                        placeholder="500"
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold"
                                        required
                                    />
                                </div>
                                <div>
                                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Capacité Maximale *</label>
                                    <input 
                                        type="number" 
                                        name="maxCapacity"
                                        placeholder="20"
                                        class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold"
                                        required
                                    />
                                </div>
                            </div>

                            <!-- Trainer and Duration -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Entraîneur *</label>
                                    <select name="trainer" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold" required>
                                        <option value="">Sélectionner un entraîneur</option>
                                        <option value="Ahmed Hassan">Ahmed Hassan</option>
                                        <option value="Mohamed El Kouri">Mohamed El Kouri</option>
                                        <option value="Fatima Bennani">Fatima Bennani</option>
                                        <option value="Sara Alami">Sara Alami</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Durée de Session</label>
                                    <select name="duration" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold">
                                        <option value="30">30 minutes</option>
                                        <option value="45">45 minutes</option>
                                        <option value="60">60 minutes (1h)</option>
                                        <option value="90">90 minutes (1h30)</option>
                                        <option value="120">120 minutes (2h)</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Buttons -->
                            <div class="flex gap-3 pt-6 border-t border-slate-100">
                                <button 
                                    type="submit"
                                    class="flex-1 py-3 bg-indigo-600 text-white font-black rounded-xl hover:bg-indigo-700 transition-all flex items-center justify-center gap-2"
                                >
                                    <?php echo checkIcon(18); ?>
                                    Créer l'Activité
                                </button>
                                <a href="index.php?page=sports" class="flex-1 py-3 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 transition-all text-center">
                                    Annuler
                                </a>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Preview Card -->
                <div>
                    <div class="sticky top-8">
                        <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                            <div class="h-32 bg-gradient-to-r from-indigo-500 to-blue-600"></div>
                            <div class="p-6">
                                <div class="h-16 w-16 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-600 text-2xl -mt-12 mb-4 border-4 border-white shadow-lg">
                                    <?php echo dumbbellIcon(32); ?>
                                </div>
                                <h3 class="text-lg font-black text-slate-900">Nouvelle Activité</h3>
                                <p class="text-4xl font-black text-indigo-600 mt-2">500 DH</p>
                                <p class="text-xs text-slate-500 font-bold mt-4">Par mois • Capacité 20</p>
                                
                                <div class="mt-6 pt-6 border-t border-slate-100 space-y-2">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500">Entraîneur</span>
                                        <span class="font-semibold text-slate-900">À sélectionner</span>
                                    </div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-slate-500">Durée</span>
                                        <span class="font-semibold text-slate-900">60 minutes</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
                            <span class="text-2xl flex-shrink-0">💡</span>
                            <div>
                                <p class="font-bold text-amber-900 text-sm">Conseil</p>
                                <p class="text-xs text-amber-700 mt-1">Commencez avec une activité populaire et ajustez le prix en fonction de la demande.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
