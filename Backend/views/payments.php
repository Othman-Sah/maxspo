<?php
/**
 * Payments / Journal Paiements View
 * Payment tracking and management
 */

require_once ROOT_PATH . '/controllers/DashboardController.php';
require_once ROOT_PATH . '/components/Layout.php';
require_once ROOT_PATH . '/helpers/Icons.php';

$controller = new DashboardController($db);
$currentPage = 'payments';
?>

<?php renderHeader(); ?>

<div class="flex h-screen bg-slate-50">
    <?php renderSidebar($currentPage); ?>

    <main class="flex-1 overflow-auto">
        <div class="p-8 space-y-8">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-black text-slate-900">Journal des Paiements</h1>
                    <p class="text-slate-500 mt-1">Suivi des transactions et reçus</p>
                </div>
                <button class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all flex items-center gap-2">
                    <?php echo icon('download', 18); ?>
                    Exporter
                </button>
            </div>

            <!-- Payment Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-6 rounded-2xl border border-slate-100">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                            <?php echo icon('check-circle', 20); ?>
                        </div>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">12</span>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Paiements Régulés</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-2">98,500 DH</h3>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-xl bg-amber-50 text-amber-600">
                            <?php echo icon('clock', 20); ?>
                        </div>
                        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">3</span>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">En Attente</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-2">12,300 DH</h3>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-xl bg-rose-50 text-rose-600">
                            <?php echo icon('x-circle', 20); ?>
                        </div>
                        <span class="text-xs font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-full">2</span>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Échecs</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-2">3,200 DH</h3>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white rounded-2xl border border-slate-100 p-4 flex items-center gap-4">
                <input type="text" placeholder="Rechercher membre..." class="flex-1 px-4 py-2 border border-slate-200 rounded-lg focus:border-indigo-500 outline-none transition" />
                <select class="px-4 py-2 border border-slate-200 rounded-lg focus:border-indigo-500 outline-none transition font-semibold">
                    <option>Tous les statuts</option>
                    <option>Réglé</option>
                    <option>En attente</option>
                    <option>Échec</option>
                </select>
                <select class="px-4 py-2 border border-slate-200 rounded-lg focus:border-indigo-500 outline-none transition font-semibold">
                    <option>Tous les mois</option>
                    <option>Décembre</option>
                    <option>Novembre</option>
                    <option>Octobre</option>
                </select>
            </div>

            <!-- Payment Table -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <table class="w-full">
                    <thead class="bg-slate-50 border-b border-slate-100">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-wider">Membre</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-wider">Montant</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-wider">Méthode</th>
                            <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php
                        $payments = [
                            ['date' => '2024-12-23', 'member' => 'Ahmed Ben Salem', 'type' => 'Adhésion', 'amount' => '1,500 DH', 'status' => 'Réglé', 'method' => 'Carte'],
                            ['date' => '2024-12-22', 'member' => 'Leila Mahmoud', 'type' => 'Cours', 'amount' => '500 DH', 'status' => 'Réglé', 'method' => 'Espèces'],
                            ['date' => '2024-12-21', 'member' => 'Hassan Zahra', 'type' => 'Adhésion', 'amount' => '1,500 DH', 'status' => 'En attente', 'method' => 'Chèque'],
                            ['date' => '2024-12-20', 'member' => 'Fatima Aziz', 'type' => 'Renouvellement', 'amount' => '1,200 DH', 'status' => 'Réglé', 'method' => 'Virement'],
                            ['date' => '2024-12-19', 'member' => 'Mohamed Karim', 'type' => 'Cours', 'amount' => '450 DH', 'status' => 'Échec', 'method' => 'Carte'],
                            ['date' => '2024-12-18', 'member' => 'Nadia Bennani', 'type' => 'Adhésion', 'amount' => '1,500 DH', 'status' => 'Réglé', 'method' => 'Espèces'],
                        ];

                        foreach ($payments as $payment):
                            $statusClass = $payment['status'] === 'Réglé' ? 'bg-emerald-50 text-emerald-600' : ($payment['status'] === 'En attente' ? 'bg-amber-50 text-amber-600' : 'bg-rose-50 text-rose-600');
                            $statusIcon = $payment['status'] === 'Réglé' ? 'check' : ($payment['status'] === 'En attente' ? 'alert' : 'x');
                        ?>
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-sm font-semibold text-slate-600"><?php echo $payment['date']; ?></td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-900"><?php echo $payment['member']; ?></td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-600"><?php echo $payment['type']; ?></td>
                            <td class="px-6 py-4 text-sm font-black text-indigo-600"><?php echo $payment['amount']; ?></td>
                            <td class="px-6 py-4">
                                <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo $statusClass; ?>">
                                    <?php echo icon($statusIcon, 12); ?> <?php echo $payment['status']; ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm font-semibold text-slate-600"><?php echo $payment['method']; ?></td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <button class="text-indigo-600 hover:text-indigo-900 font-semibold mr-3">
                                    <?php echo icon('eye', 16); ?>
                                </button>
                                <button class="text-rose-600 hover:text-rose-900 font-semibold">
                                    <?php echo trashIcon(16); ?>
                                </button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
