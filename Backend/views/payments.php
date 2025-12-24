<?php
require_once 'config/config.php';
require_once 'controllers/PaymentsController.php';
require_once 'components/Layout.php';
require_once 'helpers/Icons.php';

requireLogin();

$controller = new PaymentsController($db);
$currentPage = 'payments';

$monthFilter = getParam('month', date('Y-m'));
$methodFilter = getParam('method', 'all');
$statusFilter = getParam('status', 'all');

$filters = ['month' => $monthFilter, 'method' => $methodFilter, 'status' => $statusFilter];
$payments = $controller->getPayments($filters);
$methodStats = $controller->getPaymentMethodStats();

$totalAmount = array_reduce($payments, fn($sum, $p) => $sum + $p['amount'], 0);
$totalPayments = count($payments);
$averageBasket = $totalPayments > 0 ? round($totalAmount / $totalPayments) : 0;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Gestion des Revenus</title>
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
            <div class="p-8 space-y-8 animate-in">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                            <?php echo icon('creditcard', 32, 'text-emerald-600'); ?>
                            Gestion des Revenus
                        </h1>
                        <p class="text-slate-500 font-medium mt-1">Suivi détaillé des transactions et performances financières</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <button class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all text-sm shadow-sm">
                            <?php echo icon('printer', 18); ?> Imprimer
                        </button>
                        <button class="flex items-center gap-2 px-6 py-2.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all text-sm shadow-lg shadow-emerald-100">
                            <?php echo icon('download', 18); ?> Exporter CSV
                        </button>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Paiements ce mois</p>
                        <div class="flex items-center justify-between">
                            <h3 class="text-3xl font-black text-slate-900"><?php echo $totalPayments; ?></h3>
                            <div class="h-10 w-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center"><?php echo icon('calendar', 20); ?></div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm relative overflow-hidden group">
                        <div class="absolute top-0 right-0 p-4 opacity-5 group-hover:opacity-10 transition-opacity"><?php echo icon('trending-up', 80); ?></div>
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Total Revenus</p>
                        <div class="flex items-center justify-between">
                            <h3 class="text-3xl font-black text-emerald-600"><?php echo number_format($totalAmount); ?> DH</h3>
                            <div class="h-10 w-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center"><?php echo icon('trending-up', 20); ?></div>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                        <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest mb-1">Panier Moyen</p>
                        <div class="flex items-center justify-between">
                            <h3 class="text-3xl font-black text-indigo-600"><?php echo number_format($averageBasket); ?> DH</h3>
                            <div class="h-10 w-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center"><?php echo icon('arrow-up-right', 20); ?></div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                        <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2">
                            <?php echo icon('banknote', 20, 'text-indigo-500'); ?> Méthodes de Paiement
                        </h3>
                        <div class="space-y-5">
                            <?php foreach ($methodStats as $stat): ?>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm font-bold text-slate-700"><?php echo $stat['method']; ?></span>
                                        <span class="text-xs font-black text-slate-400"><?php echo number_format($stat['total']); ?> DH</span>
                                    </div>
                                    <div class="h-2.5 w-full bg-slate-50 rounded-full overflow-hidden">
                                        <div class="h-full <?php echo $stat['color']; ?> transition-all duration-1000" style="width: <?php echo $stat['percentage']; ?>%"></div>
                                    </div>
                                    <div class="flex justify-between text-[10px] font-bold text-slate-400 uppercase">
                                        <span><?php echo $stat['count']; ?> Transactions</span>
                                        <span><?php echo $stat['percentage']; ?>% du volume</span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="bg-slate-900 p-8 rounded-3xl shadow-xl shadow-slate-200 text-white space-y-6">
                        <form id="filter-form" method="get">
                            <input type="hidden" name="page" value="payments">
                            <div class="flex items-center justify-between">
                                <h3 class="text-lg font-black flex items-center gap-2">
                                    <?php echo icon('filter', 20, 'text-indigo-400'); ?> Filtres de Recherche
                                </h3>
                                <a href="?page=payments" class="text-xs font-bold text-slate-400 hover:text-white transition-colors">Réinitialiser</a>
                            </div>
                            <div class="space-y-4 mt-6">
                                <div>
                                    <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-widest">Période Mensuelle</label>
                                    <input type="month" name="month" value="<?php echo $monthFilter; ?>" class="w-full bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none text-white">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-widest">Méthode</label>
                                        <select name="method" class="w-full bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none text-white">
                                            <option value="all">Toutes</option>
                                            <option value="especes" <?php if($methodFilter == 'especes') echo 'selected'; ?>>Espèces</option>
                                            <option value="carte" <?php if($methodFilter == 'carte') echo 'selected'; ?>>Carte</option>
                                            <option value="virement" <?php if($methodFilter == 'virement') echo 'selected'; ?>>Virement</option>
                                            <option value="cheque" <?php if($methodFilter == 'cheque') echo 'selected'; ?>>Chèque</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="text-[10px] font-black uppercase text-slate-400 mb-2 block tracking-widest">Statut</label>
                                        <select name="status" class="w-full bg-slate-800 border-none rounded-xl px-4 py-3 text-sm font-bold focus:ring-2 focus:ring-indigo-500 outline-none text-white">
                                            <option value="all">Tous</option>
                                            <option value="valide" <?php if($statusFilter == 'valide') echo 'selected'; ?>>Validés</option>
                                            <option value="en_attente" <?php if($statusFilter == 'en_attente') echo 'selected'; ?>>En attente</option>
                                            <option value="annule" <?php if($statusFilter == 'annule') echo 'selected'; ?>>Annulés</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="pt-4">
                                    <button type="submit" class="w-full py-3 bg-indigo-600 hover:bg-indigo-700 rounded-xl font-black transition-all">Appliquer les filtres</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 border-b border-slate-100 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                                <tr>
                                    <th class="px-8 py-6">Date</th>
                                    <th class="px-6 py-6">Membre</th>
                                    <th class="px-6 py-6">Activité</th>
                                    <th class="px-6 py-6">Méthode</th>
                                    <th class="px-6 py-6">Montant</th>
                                    <th class="px-6 py-6">Statut</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                <?php if(empty($payments)): ?>
                                    <tr><td colspan="6" class="text-center py-24"><div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-200"><?php echo icon('creditcard', 40); ?></div><h3 class="text-xl font-black text-slate-900">Aucune transaction trouvée</h3><p class="text-slate-400 font-medium mt-2">Essayez de modifier vos filtres.</p></td></tr>
                                <?php else: ?>
                                    <?php foreach ($payments as $payment): ?>
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-8 py-5 whitespace-nowrap"><div class="flex flex-col"><span class="text-sm font-black text-slate-900"><?php echo date('d/m/Y', strtotime($payment['date'])); ?></span><span class="text-[10px] font-bold text-slate-400">Transaction #<?php echo htmlspecialchars($payment['id']); ?></span></div></td>
                                        <td class="px-6 py-5 whitespace-nowrap"><div class="flex items-center gap-3"><div class="h-9 w-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-black text-sm"><?php echo strtoupper(substr($payment['memberName'], 0, 1)); ?></div><span class="text-sm font-bold text-slate-700"><?php echo htmlspecialchars($payment['memberName']); ?></span></div></td>
                                        <td class="px-6 py-5 whitespace-nowrap"><span class="px-2.5 py-1 text-[10px] font-black rounded-lg bg-slate-100 text-slate-500 border border-slate-200"><?php echo htmlspecialchars($payment['sport']); ?></span></td>
                                        <td class="px-6 py-5 whitespace-nowrap"><div class="flex items-center gap-2 text-sm font-bold text-slate-600"><?php echo icon($payment['method'] === 'especes' ? 'banknote' : ($payment['method'] === 'carte' ? 'creditcard' : ($payment['method'] === 'virement' ? 'landmark' : 'pen-tool')), 16); ?><span class="capitalize"><?php echo $payment['method']; ?></span></div></td>
                                        <td class="px-6 py-5 whitespace-nowrap"><span class="text-lg font-black text-emerald-600"><?php echo number_format($payment['amount']); ?> DH</span></td>
                                        <td class="px-6 py-5 whitespace-nowrap">
                                            <?php
                                            $statusClasses = ['valide' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'en_attente' => 'bg-amber-50 text-amber-600 border-amber-100', 'annule' => 'bg-rose-50 text-rose-600 border-rose-100'];
                                            $statusIcons = ['valide' => 'check-circle', 'en_attente' => 'clock', 'annule' => 'x-circle'];
                                            ?>
                                            <span class="flex items-center gap-1.5 px-2.5 py-1 text-[10px] font-black uppercase rounded-lg <?php echo $statusClasses[$payment['status']]; ?>"><?php echo icon($statusIcons[$payment['status']], 12); ?> <?php echo formatStatus($payment['status']); ?></span>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php renderDropdownScript(); ?>
</body>
</html>