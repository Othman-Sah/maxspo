<?php
/**
 * Add Member View
 */

require_once 'config/config.php';
require_once 'config/Models.php';
require_once 'controllers/DashboardController.php';
require_once 'components/Components.php';
require_once 'components/Layout.php';

requireLogin();

global $db;
$dashboardCtrl = new DashboardController($db);
$activities = $dashboardCtrl->getActivities();
$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['firstName'] ?? '';
    $lastName = $_POST['lastName'] ?? '';
    $email = $_POST['email'] ?? '';
    $phone = $_POST['phone'] ?? '';
    $age = $_POST['age'] ?? 0;
    $sport = $_POST['sport'] ?? '';
    
    // Validation
    if (empty($firstName)) {
        $error = 'Prénom requis';
    } elseif (empty($lastName)) {
        $error = 'Nom requis';
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email invalide';
    } elseif (empty($phone)) {
        $error = 'Téléphone requis';
    } elseif (empty($sport)) {
        $error = 'Activité requise';
    } else {
        // Save to database
        try {
            $conn = $db->getConnection();
            $joinDate = date('Y-m-d');
            $expiryDate = date('Y-m-d', strtotime('+1 year'));
            
            $stmt = $conn->prepare("INSERT INTO members (firstName, lastName, email, phone, age, sport, joinDate, expiryDate, status, isLoyal) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$firstName, $lastName, $email, $phone, $age, $sport, $joinDate, $expiryDate, 'actif', 0]);
            
            $success = 'Membre ajouté avec succès!';
            // Reset form
            $_POST = [];
        } catch (Exception $e) {
            $error = "Erreur lors de l'ajout du membre: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Ajouter un Membre</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
    <div class="flex min-h-screen bg-slate-50">
        <?php renderSidebar('add-member'); ?>

        <main class="flex-1 min-w-0 overflow-auto">
            <?php renderHeader(); ?>
            
            <div class="p-8 max-w-5xl mx-auto">
                <!-- Back Button -->
                <div class="flex items-center justify-between mb-8">
                    <a href="index.php?page=members" class="flex items-center gap-2 text-slate-500 hover:text-indigo-600 font-bold transition-colors">
                        <div class="p-2 bg-white border border-slate-200 rounded-xl hover:border-indigo-100 hover:bg-indigo-50 transition-all">
                            ←
                        </div>
                        Retour à la liste
                    </a>
                    <div class="text-right">
                        <span class="text-xs font-black uppercase text-slate-400 tracking-widest">Enregistrement</span>
                        <p class="text-sm font-bold text-slate-900">Nouveau Membre NEEDSPORT Pro</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Main Form -->
                    <div class="lg:col-span-2 space-y-8">
                        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                            <h2 class="text-2xl font-black text-slate-900 mb-8 flex items-center gap-3">
                                <div class="h-10 w-10 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-lg">
                                    👤
                                </div>
                                Informations Personnelles
                            </h2>
                            
                            <?php if ($error): ?>
                                <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-xl text-rose-700 text-sm font-medium">
                                    <?php require_once ROOT_PATH . '/helpers/Icons.php'; echo icon('alert', 16); ?> <?php echo htmlspecialchars($error); ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($success): ?>
                                <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-sm font-medium">
                                    <?php echo checkIcon(16); ?> <?php echo htmlspecialchars($success); ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" class="space-y-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Prénom</label>
                                        <input 
                                            type="text" 
                                            name="firstName"
                                            required
                                            placeholder="Ex: Yassine"
                                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 rounded-2xl outline-none transition-all font-bold text-slate-700"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Nom de famille</label>
                                        <input 
                                            type="text" 
                                            name="lastName"
                                            required
                                            placeholder="Ex: Benali"
                                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 rounded-2xl outline-none transition-all font-bold text-slate-700"
                                        />
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">
                                            ✉️ Email
                                        </label>
                                        <input 
                                            type="email" 
                                            name="email"
                                            required
                                            placeholder="nom@exemple.com"
                                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 rounded-2xl outline-none transition-all font-bold text-slate-700"
                                        />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">
                                            ☎️ Téléphone
                                        </label>
                                        <input 
                                            type="tel" 
                                            name="phone"
                                            required
                                            placeholder="06 -- -- -- --"
                                            class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 rounded-2xl outline-none transition-all font-bold text-slate-700"
                                        />
                                    </div>
                                </div>

                                <div class="w-1/2 space-y-2">
                                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Âge</label>
                                    <input 
                                        type="number" 
                                        name="age"
                                        required
                                        min="12"
                                        max="100"
                                        placeholder="25"
                                        class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 rounded-2xl outline-none transition-all font-bold text-slate-700"
                                    />
                                </div>

                                <div class="space-y-2">
                                    <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">
                                        🏋️ Activité / Sport
                                    </label>
                                    <select name="sport" required class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 rounded-2xl outline-none transition-all font-bold text-slate-700">
                                        <option value="">Sélectionner une activité</option>
                                        <?php foreach ($activities as $activity): ?>
                                            <option value="<?php echo htmlspecialchars($activity['name']); ?>">
                                                <?php echo htmlspecialchars($activity['name']); ?> (<?php echo $activity['monthlyPrice']; ?> DH)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="pt-4 border-t border-slate-100 flex gap-3">
                                    <a href="index.php?page=members" class="flex-1 py-3.5 bg-slate-50 text-slate-500 font-bold rounded-2xl hover:bg-slate-100 transition-colors text-center">
                                        Annuler
                                    </a>
                                    <button 
                                        type="submit"
                                        class="flex-1 py-3.5 bg-slate-900 text-white font-black rounded-2xl hover:bg-slate-800 transition-all active:scale-95 flex items-center justify-center gap-2"
                                    >
                                        <?php echo checkIcon(16); ?> Ajouter le Membre
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Summary Card -->
                    <div class="space-y-6">
                        <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                            <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6">Résumé</h3>
                            
                            <div class="space-y-6">
                                <div class="p-4 bg-indigo-50 rounded-2xl border border-indigo-100">
                                    <p class="text-[10px] font-black uppercase text-indigo-600 mb-2">Accès inclus</p>
                                    <p class="text-sm font-black text-indigo-700">🧖 Sauna Illimité</p>
                                </div>

                                <div class="space-y-3">
                                    <div class="flex justify-between items-center py-2 px-3 bg-slate-50 rounded-xl">
                                        <span class="text-xs font-bold text-slate-500">Abonnement:</span>
                                        <span class="font-black text-indigo-600">À déterminer</span>
                                    </div>
                                    <div class="flex justify-between items-center py-2 px-3 bg-slate-50 rounded-xl">
                                        <span class="text-xs font-bold text-slate-500">Durée:</span>
                                        <span class="font-black text-slate-900">1 mois</span>
                                    </div>
                                </div>

                                <div class="pt-4 border-t border-slate-100 flex justify-between items-center">
                                    <span class="text-xs font-bold text-slate-400 uppercase">Total</span>
                                    <span class="text-xl font-black text-emerald-600">À déterminer</span>
                                </div>
                            </div>
                        </div>

                        <div class="p-4 bg-amber-50 rounded-2xl border border-amber-100 flex items-center gap-3">
                            <span class="text-lg">ℹ️</span>
                            <p class="text-[11px] font-bold text-amber-700 leading-relaxed">
                                Le paiement initial sera demandé après validation du profil.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <?php renderDropdownScript(); ?>
</body>
</html>
<?php
?>
