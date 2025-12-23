<?php
/**
 * POS / Caisse View
 * Point of Sale system for retail products
 */

require_once ROOT_PATH . '/controllers/DashboardController.php';
require_once ROOT_PATH . '/components/Layout.php';
require_once ROOT_PATH . '/helpers/Icons.php';

$controller = new DashboardController($db);
$currentPage = 'pos';
?>

<?php renderHeader(); ?>

<div class="flex h-screen bg-slate-50">
    <?php renderSidebar($currentPage); ?>

    <main class="flex-1 overflow-auto flex">
        <!-- Products Section -->
        <div class="flex-1 p-8 overflow-auto">
            <div class="space-y-8">
                <!-- Header -->
                <div>
                    <h1 class="text-3xl font-black text-slate-900">Caisse POS</h1>
                    <p class="text-slate-500 mt-1">Système de vente et gestion des produits</p>
                </div>

                <!-- Product Grid -->
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                    <?php
                    $products = [
                        ['name' => 'Bouteille Eau', 'price' => 15, 'stock' => 45, 'img' => '💧'],
                        ['name' => 'Serviette', 'price' => 50, 'stock' => 30, 'img' => '🏋️'],
                        ['name' => 'Gant Musculation', 'price' => 120, 'stock' => 20, 'img' => '🧤'],
                        ['name' => 'Barre Protéine', 'price' => 45, 'stock' => 25, 'img' => '🍫'],
                        ['name' => 'Tapis Yoga', 'price' => 350, 'stock' => 12, 'img' => '🧘'],
                        ['name' => 'Corde à Sauter', 'price' => 80, 'stock' => 18, 'img' => '🔗'],
                        ['name' => 'Bouteille Protéine', 'price' => 200, 'stock' => 35, 'img' => '🥤'],
                        ['name' => 'Bandeau Sueur', 'price' => 40, 'stock' => 28, 'img' => '👟'],
                    ];
                    
                    foreach ($products as $product):
                    ?>
                    <div class="bg-white rounded-2xl border border-slate-100 p-4 hover:shadow-lg transition-all cursor-pointer group">
                        <div class="h-24 bg-slate-100 rounded-xl flex items-center justify-center text-3xl mb-3 group-hover:bg-indigo-100 transition">
                            <?php echo $product['img']; ?>
                        </div>
                        <h3 class="font-bold text-slate-900 text-sm"><?php echo $product['name']; ?></h3>
                        <p class="text-indigo-600 font-black text-lg mt-2"><?php echo $product['price']; ?> DH</p>
                        <p class="text-xs text-slate-500 font-bold mt-1">Stock: <?php echo $product['stock']; ?></p>
                        <button class="w-full mt-3 py-2 bg-indigo-600 text-white font-bold text-xs rounded-lg hover:bg-indigo-700 transition-all">
                            <?php echo plusIcon(14); ?> Ajouter
                        </button>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="w-96 bg-white border-l border-slate-200 p-6 flex flex-col">
            <h2 class="text-lg font-black text-slate-900 mb-6">Panier</h2>

            <!-- Cart Items -->
            <div class="flex-1 overflow-auto space-y-3 mb-6 pb-4 border-b border-slate-200">
                <?php
                $cartItems = [
                    ['name' => 'Bouteille Eau', 'qty' => 2, 'price' => 15, 'total' => 30],
                    ['name' => 'Gant Musculation', 'qty' => 1, 'price' => 120, 'total' => 120],
                    ['name' => 'Barre Protéine', 'qty' => 3, 'price' => 45, 'total' => 135],
                ];

                foreach ($cartItems as $item):
                ?>
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-lg">
                    <div>
                        <p class="font-semibold text-slate-900 text-sm"><?php echo $item['name']; ?></p>
                        <p class="text-xs text-slate-500">x<?php echo $item['qty']; ?></p>
                    </div>
                    <div class="text-right">
                        <p class="font-black text-slate-900"><?php echo $item['total']; ?> DH</p>
                        <button class="text-rose-600 hover:text-rose-700 text-xs font-bold">
                            <?php echo trashIcon(12); ?>
                        </button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <!-- Totals -->
            <div class="space-y-3 mb-6">
                <div class="flex justify-between text-slate-600">
                    <span>Sous-total</span>
                    <span class="font-semibold">285 DH</span>
                </div>
                <div class="flex justify-between text-slate-600">
                    <span>Remise (10%)</span>
                    <span class="font-semibold text-emerald-600">-28.50 DH</span>
                </div>
                <div class="flex justify-between text-lg font-black pt-3 border-t border-slate-200">
                    <span>Total</span>
                    <span class="text-indigo-600">256.50 DH</span>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="mb-6">
                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1 block mb-2">Mode Paiement</label>
                <select class="w-full px-3 py-2 border border-slate-200 rounded-lg focus:border-indigo-500 outline-none transition font-semibold text-sm">
                    <option>Carte Bancaire</option>
                    <option>Espèces</option>
                    <option>Chèque</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="space-y-2">
                <button class="w-full py-3 bg-indigo-600 text-white font-black rounded-xl hover:bg-indigo-700 transition-all flex items-center justify-center gap-2">
                    <?php echo checkIcon(18); ?>
                    Payer (256.50 DH)
                </button>
                <button class="w-full py-2 bg-slate-100 text-slate-600 font-bold rounded-lg hover:bg-slate-200 transition-all">
                    Annuler
                </button>
            </div>
        </div>
    </main>
</div>
