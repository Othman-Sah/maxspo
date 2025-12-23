<?php
/**
 * Settings / Paramètres View
 * Application settings and configuration
 */

require_once ROOT_PATH . '/controllers/DashboardController.php';
require_once ROOT_PATH . '/components/Layout.php';
require_once ROOT_PATH . '/helpers/Icons.php';

$controller = new DashboardController($db);
$currentPage = 'settings';
?>

<?php renderHeader(); ?>

<div class="flex h-screen bg-slate-50">
    <?php renderSidebar($currentPage); ?>

    <main class="flex-1 overflow-auto">
        <div class="p-8 max-w-4xl">
            <!-- Header -->
            <div>
                <h1 class="text-3xl font-black text-slate-900">Paramètres</h1>
                <p class="text-slate-500 mt-1">Configuration du club et préférences</p>
            </div>

            <!-- Settings Tabs -->
            <div class="mt-8 space-y-6">
                <!-- Club Settings -->
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <?php echo icon('building', 20); ?> Information du Club
                        </h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Nom du Club</label>
                                <input type="text" value="NEEDSPORT Pro" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold" />
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Email</label>
                                <input type="email" value="admin@needsport.ma" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold" />
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Téléphone</label>
                                <input type="tel" value="05 23 45 67 89" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold" />
                            </div>
                            <div>
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Adresse</label>
                                <input type="text" value="Avenue Mohammed V, Casablanca" class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold" />
                            </div>
                        </div>
                        <div>
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Description</label>
                            <textarea class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:border-indigo-500 outline-none transition font-semibold h-24">Club de sport premium offrant fitness, musculation, yoga et football avec équipements modernes.</textarea>
                        </div>
                        <button class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition-all">
                            <?php echo checkIcon(16); ?> Enregistrer
                        </button>
                    </div>
                </div>

                <!-- Subscription Settings -->
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <?php echo creditcardIcon(20); ?> Forfaits d'Adhésion
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <?php
                        $plans = [
                            ['name' => 'Fitness Mensuel', 'price' => '500 DH/mois', 'active' => true],
                            ['name' => 'Football Annuel', 'price' => '2,500 DH/an', 'active' => true],
                            ['name' => 'Yoga Trimestriel', 'price' => '1,200 DH/3 mois', 'active' => true],
                            ['name' => 'Musculation VIP', 'price' => '1,500 DH/mois', 'active' => false],
                        ];
                        
                        foreach ($plans as $plan):
                        ?>
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                            <div>
                                <p class="font-semibold text-slate-900"><?php echo $plan['name']; ?></p>
                                <p class="text-sm text-slate-500"><?php echo $plan['price']; ?></p>
                            </div>
                            <div class="flex gap-2">
                                <button class="px-3 py-1.5 text-xs bg-white text-slate-600 font-bold border border-slate-200 rounded-lg hover:bg-slate-50 transition-all">
                                    <?php echo editIcon(14); ?>
                                </button>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" <?php echo $plan['active'] ? 'checked' : ''; ?> class="w-4 h-4 rounded" />
                                </label>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Notification Settings -->
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <?php echo bellIcon(20); ?> Notifications
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                            <div>
                                <p class="font-semibold text-slate-900">Adhésions Expirantes</p>
                                <p class="text-sm text-slate-500">Alertes pour les membres expirant</p>
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" checked class="w-5 h-5 rounded" />
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                            <div>
                                <p class="font-semibold text-slate-900">Nouveaux Paiements</p>
                                <p class="text-sm text-slate-500">Notification pour chaque paiement reçu</p>
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" checked class="w-5 h-5 rounded" />
                            </label>
                        </div>

                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                            <div>
                                <p class="font-semibold text-slate-900">Rapports Quotidiens</p>
                                <p class="text-sm text-slate-500">Résumé des activités du jour</p>
                            </div>
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" class="w-5 h-5 rounded" />
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                    <div class="p-6 border-b border-slate-100 bg-slate-50">
                        <h2 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                            <?php echo icon('lock', 20); ?> Sécurité
                        </h2>
                    </div>
                    <div class="p-6 space-y-4">
                        <button class="w-full p-4 text-left bg-slate-50 rounded-xl hover:bg-slate-100 transition-all flex items-center justify-between group">
                            <div>
                                <p class="font-semibold text-slate-900">Changer le Mot de Passe</p>
                                <p class="text-sm text-slate-500">Mettre à jour votre mot de passe</p>
                            </div>
                            <span class="text-slate-400 group-hover:text-slate-600">→</span>
                        </button>

                        <button class="w-full p-4 text-left bg-slate-50 rounded-xl hover:bg-slate-100 transition-all flex items-center justify-between group">
                            <div>
                                <p class="font-semibold text-slate-900">Authentification à Deux Facteurs</p>
                                <p class="text-sm text-slate-500">Ajouter une couche de sécurité supplémentaire</p>
                            </div>
                            <span class="text-slate-400 group-hover:text-slate-600">→</span>
                        </button>

                        <button class="w-full p-4 text-left bg-rose-50 rounded-xl hover:bg-rose-100 transition-all flex items-center justify-between group text-rose-600">
                            <div>
                                <p class="font-semibold">Sessions Actives</p>
                                <p class="text-sm text-rose-500">Déconnecter toutes les autres sessions</p>
                            </div>
                            <span class="group-hover:translate-x-1">→</span>
                        </button>
                    </div>
                </div>

                <!-- Danger Zone -->
                <div class="bg-white rounded-2xl border border-rose-100 overflow-hidden">
                    <div class="p-6 border-b border-rose-100 bg-rose-50">
                        <h2 class="text-lg font-bold text-rose-900 flex items-center gap-2">
                            <?php echo icon('alert-triangle', 20); ?> Zone de Danger
                        </h2>
                    </div>
                    <div class="p-6">
                        <button class="px-6 py-3 bg-rose-600 text-white font-bold rounded-xl hover:bg-rose-700 transition-all flex items-center gap-2">
                            <?php echo trashIcon(18); ?>
                            Réinitialiser les Données
                        </button>
                        <p class="text-xs text-rose-600 mt-3">Cette action ne peut pas être annulée. Tous les membres et transactions seront supprimés.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
