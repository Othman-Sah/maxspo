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

// Mock data for icons and colors, similar to the React component
$icons = [
    ['id' => 'Dumbbell', 'label' => 'Muscu'],
    ['id' => 'Target', 'label' => 'Cible'],
    ['id' => 'flower-2', 'label' => 'Zen'],
    ['id' => 'Flame', 'label' => 'Intense'],
];

$colors = [
    ['id' => 'indigo', 'class' => 'from-indigo-500 to-blue-600'],
    ['id' => 'rose', 'class' => 'from-rose-500 to-red-600'],
    ['id' => 'emerald', 'class' => 'from-emerald-500 to-teal-600'],
    ['id' => 'amber', 'class' => 'from-amber-500 to-orange-600'],
];

$name = $_POST['name'] ?? '';
$selectedIcon = $_POST['selectedIcon'] ?? 'Dumbbell';
$selectedColor = $_POST['selectedColor'] ?? 'indigo';
$price = $_POST['price'] ?? '250';

$success = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simple validation
    if (empty($name) || empty($price)) {
        $error = "Veuillez remplir tous les champs obligatoires.";
    } else {
        // Here you would typically insert into the database
        // For now, just show a success message
        $success = "L'activité '{$name}' a été créée avec succès !";
    }
}

$selectedColorClass = 'from-indigo-500 to-blue-600';
foreach ($colors as $color) {
    if ($color['id'] === $selectedColor) {
        $selectedColorClass = $color['class'];
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Nouvelle Activité</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .animate-in {
            animation: animateIn 0.5s ease-out forwards;
        }
        @keyframes animateIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <div class="flex h-screen bg-slate-50">
        <?php renderSidebar($currentPage); ?>

        <main class="flex-1 overflow-auto">
            <?php renderHeader(); ?>

            <div class="animate-in max-w-4xl mx-auto space-y-8 p-8">
                <div class="flex items-center justify-between">
                    <a href="index.php?page=sports" class="flex items-center gap-2 text-slate-500 hover:text-indigo-600 font-bold transition-colors">
                        <?php echo icon('chevron-left', 20); ?> Retour
                    </a>
                    <h1 class="text-2xl font-black text-slate-900">Nouvelle Activité Sportive</h1>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <div class="lg:col-span-2 space-y-6">
                        <form method="POST" action="index.php?page=add-activity" class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm space-y-6">
                            <input type="hidden" name="selectedIcon" id="selectedIcon" value="<?php echo htmlspecialchars($selectedIcon); ?>">
                            <input type="hidden" name="selectedColor" id="selectedColor" value="<?php echo htmlspecialchars($selectedColor); ?>">

                            <?php if ($error): ?>
                                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-xl text-rose-700 text-sm font-medium flex items-center gap-2">
                                    <?php echo icon('alert-triangle', 16); ?> <?php echo htmlspecialchars($error); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($success): ?>
                                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-sm font-medium flex items-center gap-2">
                                    <?php echo icon('check-circle', 16); ?> <?php echo htmlspecialchars($success); ?>
                                </div>
                            <?php endif; ?>

                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Nom du Sport</label>
                                <input 
                                    type="text" 
                                    name="name"
                                    required
                                    placeholder="Ex: Muay Thaï"
                                    class="w-full px-5 py-4 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-lg"
                                    value="<?php echo htmlspecialchars($name); ?>"
                                />
                            </div>

                            <div class="space-y-4">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Icône Représentative</label>
                                <div class="grid grid-cols-4 gap-4">
                                    <?php foreach ($icons as $item): ?>
                                        <button
                                            type="button"
                                            onclick="document.getElementById('selectedIcon').value='<?php echo $item['id']; ?>'; document.getElementById('previewIcon').innerHTML = this.innerHTML; document.getElementById('previewIcon').className = this.children[0].className; "
                                            class="flex flex-col items-center justify-center p-4 rounded-2xl border-2 transition-all <?php echo $selectedIcon === $item['id'] ? 'border-indigo-500 bg-indigo-50 text-indigo-600' : 'border-slate-50 text-slate-400 hover:border-slate-200'; ?>"
                                        >
                                            <?php echo icon(strtolower($item['id']), 24); ?>
                                            <span class="text-[10px] font-black mt-2 uppercase"><?php echo $item['label']; ?></span>
                                        </button>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Thème Visuel</label>
                                <div class="flex gap-4">
                                    <?php foreach ($colors as $color): ?>
                                        <button
                                            type="button"
                                            onclick="document.getElementById('selectedColor').value='<?php echo $color['id']; ?>'; document.getElementById('previewCard').className = 'bg-gradient-to-br <?php echo $color['class']; ?> p-6 rounded-3xl text-white shadow-lg'"
                                            class="h-12 w-12 rounded-full border-4 transition-all <?php echo $selectedColor === $color['id'] ? 'border-slate-900 scale-110' : 'border-white shadow-sm'; ?> bg-gradient-to-r <?php echo $color['class']; ?>"
                                        ></button>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-6 pt-4">
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Prix Mensuel (DH)</label>
                                    <div class="relative">
                                        <?php echo icon('credit-card', 18, 'absolute left-4 top-1/2 -translate-y-1/2 text-slate-400'); ?>
                                        <input 
                                            type="number"
                                            name="price"
                                            id="priceInput"
                                            class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-black text-xl text-emerald-600"
                                            value="<?php echo htmlspecialchars($price); ?>"
                                            onkeyup="document.getElementById('previewPrice').innerText = this.value + ' DH / Mois';"
                                        />
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Accès Sauna</label>
                                    <div class="h-14 bg-indigo-50 rounded-2xl flex items-center justify-between px-6 border border-indigo-100">
                                        <span class="text-xs font-bold text-indigo-700">Inclus par défaut</span>
                                        <?php echo icon('check-circle', 20, 'text-indigo-600'); ?>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 bg-slate-900 text-white font-black rounded-2xl shadow-xl shadow-slate-200 hover:bg-slate-800 transition-all active:scale-[0.98]">
                                Créer l'activité
                            </button>
                        </form>
                    </div>

                    <div class="space-y-6">
                        <div class="bg-white p-8 rounded-[32px] border border-slate-100 shadow-sm">
                            <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6">Aperçu de la carte</h3>
                            <div id="previewCard" class="bg-gradient-to-br <?php echo $selectedColorClass; ?> p-6 rounded-3xl text-white shadow-lg">
                                <div id="previewIcon" class="h-12 w-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                                    <?php echo icon(strtolower($selectedIcon), 24); ?>
                                </div>
                                <h4 id="previewName" class="text-xl font-black"><?php echo htmlspecialchars($name) ?: 'Nom du Sport'; ?></h4>
                                <p id="previewPrice" class="text-sm font-medium opacity-80 mt-1"><?php echo htmlspecialchars($price); ?> DH / Mois</p>
                            </div>
                            
                            <div class="mt-8 flex items-start gap-4 p-4 bg-amber-50 rounded-2xl border border-amber-100">
                                <?php echo icon('info', 20, 'text-amber-500 shrink-0'); ?>
                                <p class="text-[11px] font-bold text-amber-700 leading-relaxed">
                                    Les activités créées sont immédiatement disponibles dans le menu de sélection lors de l'ajout d'un membre.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        document.getElementById('name').addEventListener('keyup', function() {
            document.getElementById('previewName').innerText = this.value || 'Nom du Sport';
        });
        document.getElementById('priceInput').addEventListener('keyup', function() {
            document.getElementById('previewPrice').innerText = (this.value || '0') + ' DH / Mois';
        });
    </script>
    <?php renderDropdownScript(); ?>
</body>
</html>
