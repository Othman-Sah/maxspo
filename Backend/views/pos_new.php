<?php
/**
 * POS / Point of Sale View
 * Products and sales management - matching React POSView
 */

require_once ROOT_PATH . '/controllers/DashboardController.php';
require_once ROOT_PATH . '/components/Layout.php';
require_once ROOT_PATH . '/helpers/Icons.php';

$controller = new DashboardController($db);
$currentPage = 'pos';

// Mock POS data
$products = [
    ['id' => 1, 'name' => 'Protéine Whey', 'price' => 350, 'stock' => 45, 'category' => 'Complément'],
    ['id' => 2, 'name' => 'Créatine Monohydrate', 'price' => 180, 'stock' => 28, 'category' => 'Complément'],
    ['id' => 3, 'name' => 'BCAA', 'price' => 220, 'stock' => 12, 'category' => 'Complément'],
    ['id' => 4, 'name' => 'Eau Minérale (500ml)', 'price' => 25, 'stock' => 150, 'category' => 'Boisson'],
    ['id' => 5, 'name' => 'Jus d\'Orange', 'price' => 40, 'stock' => 80, 'category' => 'Boisson'],
    ['id' => 6, 'name' => 'Barre Énergétique', 'price' => 35, 'stock' => 95, 'category' => 'Snack'],
    ['id' => 7, 'name' => 'Chewing-gum', 'price' => 15, 'stock' => 200, 'category' => 'Snack'],
    ['id' => 8, 'name' => 'Banane Fraîche', 'price' => 5, 'stock' => 120, 'category' => 'Snack'],
];

$cartItems = [
    ['product' => 'Protéine Whey', 'price' => 350, 'quantity' => 2],
    ['product' => 'Eau Minérale', 'price' => 25, 'quantity' => 4],
    ['product' => 'Barre Énergétique', 'price' => 35, 'quantity' => 1],
];

$subtotal = 350 * 2 + 25 * 4 + 35 * 1;
?>

<?php renderHeader(); ?>

<div class="flex h-screen bg-slate-50">
    <?php renderSidebar($currentPage); ?>

    <main class="flex-1 overflow-auto">
        <div class="p-8 flex flex-col lg:flex-row gap-8 h-[calc(100vh-80px)]">
            <!-- Products Section -->
            <div class="flex-1 flex flex-col gap-6 overflow-hidden">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 shrink-0">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                            <?php echo icon('shopping-cart', 32, 'text-amber-500'); ?>
                            Caisse & Snacks
                        </h1>
                        <p class="text-slate-500 font-medium mt-1">Ventes directes de compléments et boissons</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="relative">
                            <?php echo icon('search', 20, 'absolute left-4 top-1/2 -translate-y-1/2 text-slate-400'); ?>
                            <input type="text" placeholder="Rechercher produit..." class="pl-12 pr-4 py-3 bg-white border border-slate-100 rounded-2xl outline-none focus:border-indigo-500 font-bold text-sm w-full md:w-64 shadow-sm" />
                        </div>
                        <button class="p-3 bg-indigo-600 text-white rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 group">
                            <?php echo icon('plus', 24, 'group-hover:rotate-90 transition-transform duration-300'); ?>
                        </button>
                    </div>
                </div>

                <!-- Product Grid -->
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-auto">
                    <?php foreach ($products as $product): ?>
                        <div class="bg-white rounded-[24px] border border-slate-100 shadow-sm overflow-hidden hover:shadow-lg transition-all group">
                            <div class="h-24 bg-gradient-to-br from-indigo-100 to-blue-100 flex items-center justify-center text-3xl">
                                💊
                            </div>
                            <div class="p-4 space-y-3">
                                <div>
                                    <h3 class="text-sm font-black text-slate-900 line-clamp-2"><?php echo $product['name']; ?></h3>
                                    <p class="text-[10px] text-slate-500 font-bold mt-1"><?php echo $product['category']; ?></p>
                                </div>
                                <div class="flex items-center justify-between">
                                    <p class="text-lg font-black text-emerald-600"><?php echo $product['price']; ?> DH</p>
                                    <span class="text-[10px] font-bold px-2 py-1 rounded-lg bg-slate-100 text-slate-600"><?php echo $product['stock']; ?> pcs</span>
                                </div>
                                <button class="w-full py-2 bg-indigo-600 text-white font-bold rounded-xl text-sm hover:bg-indigo-700 transition-all">
                                    <?php echo icon('plus', 14); ?> Ajouter
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Shopping Cart Sidebar -->
            <div class="lg:w-96 flex flex-col bg-white rounded-[32px] border border-slate-100 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-slate-100">
                    <h3 class="text-lg font-black text-slate-900 flex items-center gap-2">
                        <?php echo icon('shopping-cart', 20, 'text-amber-500'); ?>
                        Panier
                    </h3>
                </div>

                <!-- Cart Items -->
                <div class="flex-1 overflow-auto p-6 space-y-4">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl group">
                            <div class="flex-1">
                                <p class="font-bold text-slate-900 text-sm"><?php echo $item['product']; ?></p>
                                <p class="text-[10px] text-slate-500 font-bold">Qty: <?php echo $item['quantity']; ?></p>
                            </div>
                            <div class="text-right">
                                <p class="font-black text-slate-900"><?php echo number_format($item['price'] * $item['quantity'], 0); ?> DH</p>
                                <button class="text-rose-600 hover:text-rose-700 mt-1">
                                    <?php echo icon('trash', 14); ?>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Cart Summary -->
                <div class="border-t border-slate-100 p-6 space-y-4">
                    <div class="space-y-2">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-600 font-bold">Sous-total</span>
                            <span class="font-black text-slate-900"><?php echo number_format($subtotal, 0); ?> DH</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-slate-600 font-bold">Remise (10%)</span>
                            <span class="font-black text-emerald-600">-<?php echo number_format($subtotal * 0.1, 0); ?> DH</span>
                        </div>
                        <div class="border-t border-slate-100 pt-2 flex items-center justify-between">
                            <span class="text-slate-900 font-black">Total</span>
                            <span class="text-2xl font-black text-indigo-600"><?php echo number_format($subtotal * 0.9, 0); ?> DH</span>
                        </div>
                    </div>

                    <div>
                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest mb-2 block">Méthode de Paiement</label>
                        <select class="w-full px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl font-bold text-sm focus:border-indigo-500 outline-none">
                            <option>Carte Bancaire</option>
                            <option>Espèces</option>
                            <option>Chèque</option>
                            <option>Virement</option>
                        </select>
                    </div>

                    <button class="w-full py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-[0.98] flex items-center justify-center gap-2">
                        <?php echo icon('check', 20); ?>
                        Valider le Paiement (<?php echo number_format($subtotal * 0.9, 0); ?> DH)
                    </button>
                </div>
            </div>
        </div>
    </main>
</div>
