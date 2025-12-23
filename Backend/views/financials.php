<?php
/**
 * Financials / Finances View
 * Revenue tracking, expenses, and financial reports
 */

require_once ROOT_PATH . '/controllers/DashboardController.php';
require_once ROOT_PATH . '/components/Layout.php';
require_once ROOT_PATH . '/helpers/Icons.php';

$controller = new DashboardController($db);
$currentPage = 'financials';
?>

<?php renderHeader(); ?>

<div class="flex h-screen bg-slate-50">
    <?php renderSidebar($currentPage); ?>

    <main class="flex-1 overflow-auto">
        <div class="p-8 space-y-8">
            <!-- Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-black text-slate-900">Finances</h1>
                    <p class="text-slate-500 mt-1">Analyse financière et rapports</p>
                </div>
                <div class="flex gap-3">
                    <button class="px-4 py-2 border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all">
                        <?php echo icon('download', 18); ?> Télécharger
                    </button>
                </div>
            </div>

            <!-- Financial Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-xl bg-indigo-50 text-indigo-600">
                            <?php echo chartIcon(20); ?>
                        </div>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">+12%</span>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Revenu Mensuel</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-2">24,500 DH</h3>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-xl bg-rose-50 text-rose-600">
                            <?php echo icon('trending-down', 20); ?>
                        </div>
                        <span class="text-xs font-bold text-rose-600 bg-rose-50 px-2 py-1 rounded-full">+8%</span>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Dépenses</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-2">8,200 DH</h3>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600">
                            <?php echo icon('trending-up', 20); ?>
                        </div>
                        <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded-full">+18%</span>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Bénéfice Net</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-2">16,300 DH</h3>
                </div>

                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <div class="p-3 rounded-xl bg-amber-50 text-amber-600">
                            <?php echo creditcardIcon(20); ?>
                        </div>
                        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-1 rounded-full">3 DH</span>
                    </div>
                    <p class="text-slate-500 text-sm font-medium">Moyenne par Membre</p>
                    <h3 class="text-2xl font-bold text-slate-900 mt-2">950 DH</h3>
                </div>
            </div>

            <!-- Revenue vs Expenses Chart -->
            <div class="bg-white rounded-2xl border border-slate-100 p-8">
                <h2 class="text-lg font-bold text-slate-900 mb-6">Revenu vs Dépenses</h2>
                <div class="h-80 bg-gradient-to-b from-slate-50 to-white rounded-xl border border-slate-100 p-4 flex items-end justify-around">
                    <?php
                    $months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Aoû', 'Sep', 'Oct', 'Nov', 'Déc'];
                    $revenues = [18000, 19500, 21000, 20500, 22000, 23500, 24000, 23000, 22500, 23000, 24000, 24500];
                    $expenses = [6500, 6800, 7000, 7200, 7500, 7800, 8000, 8200, 8000, 8100, 8200, 8200];
                    
                    $maxRevenue = max($revenues);
                    
                    foreach ($months as $index => $month):
                        $revenueHeight = ($revenues[$index] / $maxRevenue) * 100;
                        $expenseHeight = ($expenses[$index] / $maxRevenue) * 100;
                    ?>
                    <div class="flex flex-col items-center gap-2">
                        <div class="flex gap-1 items-end h-64">
                            <div class="w-3 bg-indigo-600 rounded-t" style="height: <?php echo $revenueHeight; ?>%;" title="Revenu: <?php echo number_format($revenues[$index], 0, ',', ' '); ?> DH"></div>
                            <div class="w-3 bg-rose-600 rounded-t" style="height: <?php echo $expenseHeight; ?>%;" title="Dépense: <?php echo number_format($expenses[$index], 0, ',', ' '); ?> DH"></div>
                        </div>
                        <span class="text-xs font-bold text-slate-500"><?php echo $month; ?></span>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="mt-6 flex items-center justify-center gap-6">
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-indigo-600 rounded"></div>
                        <span class="text-sm font-semibold text-slate-600">Revenu</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="w-4 h-4 bg-rose-600 rounded"></div>
                        <span class="text-sm font-semibold text-slate-600">Dépenses</span>
                    </div>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h2 class="text-lg font-bold text-slate-900">Transactions Récentes</h2>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 border-b border-slate-100">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-wider">Description</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-wider">Catégorie</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-wider">Montant</th>
                                <th class="px-6 py-4 text-left text-xs font-black text-slate-400 uppercase tracking-wider">Type</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <?php
                            $transactions = [
                                ['date' => '2024-12-23', 'desc' => 'Paiement Adhésion', 'cat' => 'Revenu', 'amount' => '+2,500 DH', 'type' => 'Entrée'],
                                ['date' => '2024-12-23', 'desc' => 'Achat Équipement', 'cat' => 'Dépense', 'amount' => '-850 DH', 'type' => 'Sortie'],
                                ['date' => '2024-12-22', 'desc' => 'Paiement Cours', 'cat' => 'Revenu', 'amount' => '+1,200 DH', 'type' => 'Entrée'],
                                ['date' => '2024-12-22', 'desc' => 'Salaires Staff', 'cat' => 'Dépense', 'amount' => '-3,500 DH', 'type' => 'Sortie'],
                                ['date' => '2024-12-21', 'desc' => 'POS Vente', 'cat' => 'Revenu', 'amount' => '+450 DH', 'type' => 'Entrée'],
                            ];
                            
                            foreach ($transactions as $trans):
                                $isIncome = strpos($trans['amount'], '+') !== false;
                                $amountClass = $isIncome ? 'text-emerald-600' : 'text-rose-600';
                                $typeClass = $isIncome ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600';
                            ?>
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 text-sm font-semibold text-slate-600"><?php echo $trans['date']; ?></td>
                                <td class="px-6 py-4 text-sm font-semibold text-slate-900"><?php echo $trans['desc']; ?></td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-600 uppercase"><?php echo $trans['cat']; ?></td>
                                <td class="px-6 py-4 text-sm font-black <?php echo $amountClass; ?>"><?php echo $trans['amount']; ?></td>
                                <td class="px-6 py-4"><span class="px-3 py-1 rounded-full text-xs font-bold <?php echo $typeClass; ?>"><?php echo $trans['type']; ?></span></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Expense Breakdown -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-white rounded-2xl border border-slate-100 p-8">
                    <h2 class="text-lg font-bold text-slate-900 mb-6">Répartition des Dépenses</h2>
                    <div class="space-y-4">
                        <?php
                        $expenses = [
                            ['name' => 'Salaires', 'amount' => 4500, 'color' => 'indigo'],
                            ['name' => 'Maintenance', 'amount' => 1500, 'color' => 'emerald'],
                            ['name' => 'Électricité', 'amount' => 1200, 'color' => 'amber'],
                            ['name' => 'Équipement', 'amount' => 800, 'color' => 'rose'],
                            ['name' => 'Autres', 'amount' => 200, 'color' => 'slate'],
                        ];
                        
                        $total = array_sum(array_column($expenses, 'amount'));
                        
                        foreach ($expenses as $exp):
                            $percentage = ($exp['amount'] / $total) * 100;
                        ?>
                        <div>
                            <div class="flex justify-between mb-2">
                                <span class="text-sm font-semibold text-slate-700"><?php echo $exp['name']; ?></span>
                                <span class="text-sm font-bold text-slate-900"><?php echo number_format($exp['amount'], 0, ',', ' '); ?> DH</span>
                            </div>
                            <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-<?php echo $exp['color']; ?>-600" style="width: <?php echo $percentage; ?>%;"></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-slate-100 p-8">
                    <h2 class="text-lg font-bold text-slate-900 mb-6">Méthodes de Paiement</h2>
                    <div class="space-y-4">
                        <?php
                        $methods = [
                            ['name' => 'Carte Bancaire', 'percent' => 45, 'amount' => '11,025 DH'],
                            ['name' => 'Espèces', 'percent' => 35, 'amount' => '8,575 DH'],
                            ['name' => 'Virement', 'percent' => 15, 'amount' => '3,675 DH'],
                            ['name' => 'Chèque', 'percent' => 5, 'amount' => '1,225 DH'],
                        ];
                        
                        foreach ($methods as $method):
                        ?>
                        <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                            <div>
                                <p class="font-semibold text-slate-900"><?php echo $method['name']; ?></p>
                                <p class="text-xs text-slate-500"><?php echo $method['amount']; ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-bold text-slate-900"><?php echo $method['percent']; ?>%</p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>
