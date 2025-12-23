<?php
require_once 'config/config.php';
require_once 'controllers/POSController.php';
require_once 'components/Layout.php';
require_once 'helpers/Icons.php';

requireLogin();

$controller = new POSController($db);
$products = $controller->getItems();
$currentPage = 'pos';

function getProductEmoji($category) {
    switch ($category) {
        case 'complement': return '💊';
        case 'snack': return '🍫';
        case 'boisson': return '🥤';
        default: return '📦';
    }
}

function getProductColorClass($category) {
    switch ($category) {
        case 'complement': return 'bg-indigo-50 text-indigo-500';
        case 'snack': return 'bg-amber-50 text-amber-500';
        case 'boisson': return 'bg-emerald-50 text-emerald-500';
        default: return 'bg-slate-50 text-slate-500';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Caisse & Snacks</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .animate-in { animation: animateIn 0.5s ease-out forwards; }
        @keyframes animateIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="bg-slate-50">
    <div class="flex h-screen">
        <?php renderSidebar($currentPage); ?>

        <main class="flex-1 min-w-0">
            <div class="flex flex-col lg:flex-row gap-8 h-full">
                <!-- Main Content -->
                <div class="flex-1 flex flex-col p-8 overflow-hidden">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 shrink-0 mb-6 animate-in">
                        <div>
                            <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                                <?php echo icon('shopping-cart', 32, 'text-amber-500'); ?>
                                Caisse & Snacks
                            </h1>
                            <p class="text-slate-500 font-medium mt-1 italic">Ventes directes de compléments et boissons 🧖</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="relative">
                                <?php echo icon('search', 20, 'absolute left-4 top-1/2 -translate-y-1/2 text-slate-400'); ?>
                                <input type="text" id="search-input" placeholder="Rechercher produit..." class="pl-12 pr-4 py-3 bg-white border border-slate-100 rounded-2xl outline-none focus:border-indigo-500 font-bold text-sm w-full md:w-64 shadow-sm" />
                            </div>
                            <button class="p-3 bg-indigo-600 text-white rounded-2xl shadow-lg shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-95 group" title="Ajouter un nouveau produit">
                                <?php echo icon('plus-circle', 24, 'group-hover:rotate-90 transition-transform duration-300'); ?>
                            </button>
                        </div>
                    </div>

                    <div id="category-filters" class="flex gap-2 overflow-x-auto pb-4 shrink-0 no-scrollbar animate-in" style="animation-delay: 0.1s;">
                        <button data-category="all" class="px-6 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest transition-all whitespace-nowrap border bg-slate-900 text-white border-slate-900 shadow-lg">Tout</button>
                        <button data-category="complement" class="px-6 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest transition-all whitespace-nowrap border bg-white text-slate-400 border-slate-100 hover:border-slate-300">Compléments</button>
                        <button data-category="snack" class="px-6 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest transition-all whitespace-nowrap border bg-white text-slate-400 border-slate-100 hover:border-slate-300">Snacks</button>
                        <button data-category="boisson" class="px-6 py-2.5 rounded-xl font-black text-xs uppercase tracking-widest transition-all whitespace-nowrap border bg-white text-slate-400 border-slate-100 hover:border-slate-300">Boissons</button>
                    </div>

                    <div id="product-grid" class="flex-1 overflow-y-auto pr-4 grid grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-6 animate-in" style="animation-delay: 0.2s;">
                        <?php foreach ($products as $product): ?>
                            <button
                                class="product-card bg-white p-5 rounded-[28px] border border-slate-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all text-left flex flex-col group active:scale-95"
                                data-id="<?php echo $product['id']; ?>"
                                data-name="<?php echo htmlspecialchars($product['name']); ?>"
                                data-price="<?php echo $product['price']; ?>"
                                data-category="<?php echo $product['category']; ?>"
                                data-stock="<?php echo $product['stock']; ?>"
                            >
                                <div class="h-32 w-full rounded-2xl mb-4 flex items-center justify-center text-4xl group-hover:scale-110 transition-transform <?php echo getProductColorClass($product['category']); ?>">
                                    <?php echo getProductEmoji($product['category']); ?>
                                </div>
                                <p class="text-[10px] font-black uppercase text-slate-400 mb-1"><?php echo htmlspecialchars($product['category']); ?></p>
                                <h3 class="text-sm font-black text-slate-900 flex-1 leading-tight"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <div class="mt-4 flex items-center justify-between">
                                    <span class="text-lg font-black text-slate-900"><?php echo $product['price']; ?> DH</span>
                                    <span class="text-[10px] font-bold text-slate-400">Stock: <?php echo $product['stock']; ?></span>
                                </div>
                            </button>
                        <?php endforeach; ?>
                         <button class="p-5 rounded-[28px] border-2 border-dashed border-slate-200 text-slate-300 hover:border-indigo-300 hover:bg-indigo-50/30 transition-all flex flex-col items-center justify-center text-center gap-3 py-12">
                            <?php echo icon('plus', 32); ?>
                            <span class="text-xs font-black uppercase tracking-widest">Nouveau Produit</span>
                        </button>
                    </div>
                </div>

                <!-- Cart Sidebar -->
                <div class="w-full lg:w-96 bg-white rounded-[40px] border border-slate-100 shadow-2xl flex flex-col overflow-hidden animate-in" style="animation-delay: 0.3s;">
                    <div class="p-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/50">
                       <h2 class="text-xl font-black text-slate-900 flex items-center gap-2">
                         <?php echo icon('shopping-cart', 24, 'text-indigo-600'); ?> Panier
                       </h2>
                       <span id="cart-total-items" class="bg-indigo-600 text-white text-[10px] font-black px-2 py-1 rounded-full">0 ARTICLES</span>
                    </div>

                    <div id="cart-items-container" class="flex-1 overflow-y-auto p-6 space-y-4">
                        <div id="empty-cart-message" class="h-full flex flex-col items-center justify-center text-center space-y-4 text-slate-300 py-20">
                            <?php echo icon('package', 64, 'opacity-20'); ?>
                            <p class="text-sm font-bold">Votre panier est vide.</p>
                        </div>
                    </div>

                    <div class="p-8 bg-slate-900 text-white space-y-6">
                        <div class="space-y-3">
                             <div class="flex justify-between text-slate-400 text-xs font-bold uppercase tracking-widest">
                                <span>Sous-total</span>
                                <span id="cart-subtotal">0 DH</span>
                             </div>
                             <div class="flex justify-between text-indigo-400 text-xs font-bold uppercase tracking-widest">
                                <span>Remise</span>
                                <span>0 DH</span>
                             </div>
                             <div class="pt-3 border-t border-white/10 flex justify-between items-baseline">
                                <span class="text-sm font-black">TOTAL À PAYER</span>
                                <span class="text-4xl font-black text-emerald-400"><span id="cart-total">0</span> <span class="text-xl">DH</span></span>
                             </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                             <button class="flex flex-col items-center justify-center p-4 bg-white/10 hover:bg-white/20 rounded-2xl transition-all group">
                                <span class="text-emerald-400 group-hover:scale-110 transition-transform text-2xl mb-1">💵</span>
                                <span class="text-[10px] font-black uppercase">Espèces</span>
                             </button>
                             <button class="flex flex-col items-center justify-center p-4 bg-indigo-600 hover:bg-indigo-700 rounded-2xl transition-all shadow-lg shadow-indigo-500/20 group">
                                <span class="text-white group-hover:scale-110 transition-transform text-2xl mb-1">💳</span>
                                <span class="text-[10px] font-black uppercase">Carte / NFC</span>
                             </button>
                        </div>
                        <div class="flex items-center justify-center gap-2 text-[10px] font-black uppercase text-white/30 tracking-tighter">
                             <?php echo icon('zap', 10); ?> Commande synchronisée avec le stock
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const cart = [];
            const productGrid = document.getElementById('product-grid');
            const cartItemsContainer = document.getElementById('cart-items-container');
            const emptyCartMessage = document.getElementById('empty-cart-message');
            const subtotalEl = document.getElementById('cart-subtotal');
            const totalEl = document.getElementById('cart-total');
            const totalItemsEl = document.getElementById('cart-total-items');
            const searchInput = document.getElementById('search-input');
            const categoryFilters = document.getElementById('category-filters');

            function updateCart() {
                cartItemsContainer.innerHTML = '';
                if (cart.length === 0) {
                    emptyCartMessage.style.display = 'flex';
                } else {
                    emptyCartMessage.style.display = 'none';
                    cart.forEach(item => {
                        const itemHtml = `
                            <div class="flex gap-4 group animate-in" style="animation-name: slideInLeft; animation-duration: 0.3s;">
                                <div class="h-16 w-16 rounded-2xl flex items-center justify-center text-2xl shrink-0 ${item.colorClass}">
                                    ${item.emoji}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <h4 class="text-sm font-black text-slate-900 truncate pr-2">${item.name}</h4>
                                        <button data-remove-id="${item.id}" class="text-slate-300 hover:text-rose-500 transition-colors">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                                        </button>
                                    </div>
                                    <p class="text-xs font-bold text-slate-400">${item.price} DH / un.</p>
                                    <div class="mt-2 flex items-center justify-between">
                                         <div class="flex items-center bg-slate-100 rounded-lg p-1">
                                            <button data-update-id="${item.id}" data-delta="-1" class="p-1 hover:bg-white rounded transition-colors text-slate-500"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line></svg></button>
                                            <span class="px-3 text-xs font-black">${item.quantity}</span>
                                            <button data-update-id="${item.id}" data-delta="1" class="p-1 hover:bg-white rounded transition-colors text-slate-500"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg></button>
                                         </div>
                                         <span class="text-sm font-black text-indigo-600">${item.price * item.quantity} DH</span>
                                    </div>
                                </div>
                            </div>
                        `;
                        cartItemsContainer.innerHTML += itemHtml;
                    });
                }
                
                const subtotal = cart.reduce((acc, curr) => acc + (curr.price * curr.quantity), 0);
                const totalItems = cart.reduce((acc, curr) => acc + curr.quantity, 0);

                subtotalEl.textContent = `${subtotal} DH`;
                totalEl.textContent = subtotal;
                totalItemsEl.textContent = `${totalItems} ARTICLES`;
            }

            productGrid.addEventListener('click', e => {
                const card = e.target.closest('.product-card');
                if (!card) return;
                
                const id = card.dataset.id;
                const existingItem = cart.find(item => item.id === id);

                if (existingItem) {
                    existingItem.quantity++;
                } else {
                    cart.push({
                        id,
                        name: card.dataset.name,
                        price: parseFloat(card.dataset.price),
                        category: card.dataset.category,
                        quantity: 1,
                        emoji: getProductEmoji(card.dataset.category),
                        colorClass: getProductColorClass(card.dataset.category),
                    });
                }
                updateCart();
            });

            cartItemsContainer.addEventListener('click', e => {
                const removeBtn = e.target.closest('[data-remove-id]');
                if(removeBtn) {
                    const id = removeBtn.dataset.removeId;
                    const itemIndex = cart.findIndex(item => item.id === id);
                    if (itemIndex > -1) {
                        cart.splice(itemIndex, 1);
                        updateCart();
                    }
                }
                
                const updateBtn = e.target.closest('[data-update-id]');
                if(updateBtn) {
                    const id = updateBtn.dataset.updateId;
                    const delta = parseInt(updateBtn.dataset.delta);
                    const item = cart.find(item => item.id === id);
                    if(item) {
                        item.quantity += delta;
                        if (item.quantity < 1) item.quantity = 1;
                        updateCart();
                    }
                }
            });

            function filterProducts() {
                const searchTerm = searchInput.value.toLowerCase();
                const activeCategory = categoryFilters.querySelector('.bg-slate-900').dataset.category;

                document.querySelectorAll('.product-card').forEach(card => {
                    const name = card.dataset.name.toLowerCase();
                    const category = card.dataset.category;
                    
                    const matchesSearch = name.includes(searchTerm);
                    const matchesCategory = activeCategory === 'all' || category === activeCategory;

                    if (matchesSearch && matchesCategory) {
                        card.style.display = 'flex';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            searchInput.addEventListener('input', filterProducts);
            
            categoryFilters.addEventListener('click', e => {
                const button = e.target.closest('button');
                if(!button) return;

                categoryFilters.querySelectorAll('button').forEach(btn => {
                    btn.classList.remove('bg-slate-900', 'text-white', 'shadow-lg');
                    btn.classList.add('bg-white', 'text-slate-400', 'border-slate-100');
                });

                button.classList.add('bg-slate-900', 'text-white', 'shadow-lg');
                button.classList.remove('bg-white', 'text-slate-400', 'border-slate-100');
                
                filterProducts();
            });

            const emojiMap = {
                'complement': '💊', 'snack': '🍫', 'boisson': '🥤', 'default': '📦'
            };
            const colorMap = {
                'complement': 'bg-indigo-50 text-indigo-500', 
                'snack': 'bg-amber-50 text-amber-500', 
                'boisson': 'bg-emerald-50 text-emerald-500',
                'default': 'bg-slate-50 text-slate-500'
            };
            function getProductEmoji(category) { return emojiMap[category] || emojiMap.default; }
            function getProductColorClass(category) { return colorMap[category] || colorMap.default; }

            updateCart();
        });
    </script>
</body>
</html>