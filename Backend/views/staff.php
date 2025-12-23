<?php
/**
 * Staff / Équipe View
 * Team members, roles, and management
 */

require_once ROOT_PATH . '/controllers/DashboardController.php';
require_once ROOT_PATH . '/components/Layout.php';
require_once ROOT_PATH . '/helpers/Icons.php';

$controller = new DashboardController($db);
$currentPage = 'staff';
?>

<?php renderHeader(); ?>

<div class="flex h-screen bg-slate-50">
    <?php renderSidebar($currentPage); ?>

    <main class="flex-1 overflow-auto">
        <div class="p-8 space-y-8">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-black text-slate-900">Équipe Staff</h1>
                    <p class="text-slate-500 mt-1"><?php echo 6; ?> membres de l'équipe</p>
                </div>
                <button class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all flex items-center gap-2">
                    <?php echo plusIcon(18); ?>
                    Ajouter Membre
                </button>
            </div>

            <!-- Staff Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php
                $staff = [
                    ['name' => 'Ahmed Hassan', 'role' => 'Directeur Général', 'specialty' => 'Gestion', 'status' => 'Actif', 'experience' => '12 ans'],
                    ['name' => 'Mohamed El Kouri', 'role' => 'Entraîneur Football', 'specialty' => 'Football', 'status' => 'Actif', 'experience' => '8 ans'],
                    ['name' => 'Fatima Bennani', 'role' => 'Entraîneuse Musculation', 'specialty' => 'Fitness', 'status' => 'Actif', 'experience' => '6 ans'],
                    ['name' => 'Sara Alami', 'role' => 'Instructrice Yoga', 'specialty' => 'Yoga', 'status' => 'Actif', 'experience' => '5 ans'],
                    ['name' => 'Karim Tijani', 'role' => 'Réceptionniste', 'specialty' => 'Accueil', 'status' => 'Actif', 'experience' => '3 ans'],
                    ['name' => 'Laila Bensaid', 'role' => 'Responsable Comptabilité', 'specialty' => 'Finances', 'status' => 'Actif', 'experience' => '7 ans'],
                ];
                
                foreach ($staff as $member):
                    $initials = substr($member['name'], 0, 1) . substr(explode(' ', $member['name'])[1], 0, 1);
                ?>
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden hover:shadow-lg transition-all group">
                    <!-- Header with color -->
                    <div class="h-2 bg-gradient-to-r from-indigo-500 to-blue-600"></div>
                    
                    <div class="p-6">
                        <!-- Avatar -->
                        <div class="h-16 w-16 rounded-2xl bg-indigo-100 flex items-center justify-center text-indigo-600 font-black text-lg mb-4 border-2 border-white shadow-sm">
                            <?php echo $initials; ?>
                        </div>

                        <!-- Info -->
                        <h3 class="text-lg font-bold text-slate-900"><?php echo $member['name']; ?></h3>
                        <p class="text-sm font-semibold text-indigo-600 mt-1"><?php echo $member['role']; ?></p>
                        
                        <div class="mt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span class="text-slate-500">Spécialité:</span>
                                <span class="font-semibold text-slate-900"><?php echo $member['specialty']; ?></span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-slate-500">Expérience:</span>
                                <span class="font-semibold text-slate-900"><?php echo $member['experience']; ?></span>
                            </div>
                        </div>

                        <!-- Status Badge -->
                        <div class="mt-4 flex items-center gap-2">
                            <?php echo checkIcon(14); ?>
                            <span class="text-xs font-bold text-emerald-600"><?php echo $member['status']; ?></span>
                        </div>

                        <!-- Actions -->
                        <div class="mt-6 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="flex-1 py-2 bg-indigo-600 text-white font-bold text-sm rounded-lg hover:bg-indigo-700 transition-all flex items-center justify-center gap-1">
                                <?php echo editIcon(14); ?> Modifier
                            </button>
                            <button class="py-2 px-3 bg-rose-50 text-rose-600 font-bold text-sm rounded-lg hover:bg-rose-100 transition-all">
                                <?php echo trashIcon(14); ?>
                            </button>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Staff Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-6 rounded-2xl border border-slate-100">
                    <p class="text-slate-500 text-sm font-medium">Total Staff</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-2"><?php echo count($staff); ?></h3>
                    <p class="text-xs text-emerald-600 font-bold mt-2">100% Actif</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100">
                    <p class="text-slate-500 text-sm font-medium">Entraîneurs</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-2">4</h3>
                    <p class="text-xs text-slate-500 font-bold mt-2">Spécialistes</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100">
                    <p class="text-slate-500 text-sm font-medium">Exp. Moyenne</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-2">6.8</h3>
                    <p class="text-xs text-slate-500 font-bold mt-2">ans</p>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100">
                    <p class="text-slate-500 text-sm font-medium">Masse Salariale</p>
                    <h3 class="text-3xl font-black text-slate-900 mt-2">4,500</h3>
                    <p class="text-xs text-slate-500 font-bold mt-2">DH/mois</p>
                </div>
            </div>

            <!-- Add Staff Form -->
            <div class="bg-white rounded-2xl border border-slate-100 p-8">
                <h2 class="text-xl font-bold text-slate-900 mb-6">Ajouter Membre Staff</h2>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Nom Complet</label>
                        <input type="text" placeholder="Ex: Ahmed Hassan" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold" />
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Email</label>
                        <input type="email" placeholder="email@example.com" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold" />
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Rôle</label>
                        <select class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold">
                            <option>Directeur</option>
                            <option>Entraîneur</option>
                            <option>Réceptionniste</option>
                            <option>Comptable</option>
                            <option>Autre</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Spécialité</label>
                        <select class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold">
                            <option>Fitness</option>
                            <option>Football</option>
                            <option>Yoga</option>
                            <option>Musculation</option>
                            <option>Gestion</option>
                            <option>Accueil</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Téléphone</label>
                        <input type="tel" placeholder="06 12 34 56 78" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold" />
                    </div>
                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Salaire Mensuel</label>
                        <input type="number" placeholder="3000" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold" />
                    </div>
                    <div class="md:col-span-2 flex gap-3">
                        <button type="submit" class="flex-1 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all flex items-center justify-center gap-2">
                            <?php echo checkIcon(18); ?>
                            Ajouter Membre
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
