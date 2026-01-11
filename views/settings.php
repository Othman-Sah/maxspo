<?php
require_once 'config/config.php';
require_once 'controllers/SettingsController.php';
require_once 'components/Layout.php';
require_once 'helpers/Icons.php';

requireLogin();

$controller = new SettingsController($db);
$currentPage = 'settings';
$activeSection = getParam('section', 'profile');

$profile = $controller->getProfileInfo();
$general = $controller->getGeneralSettings();

$success = '';
$error = '';

// Handle Form Submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conn = $db->getConnection();
        
        // Ensure settings table exists
        $conn->exec("CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) UNIQUE NOT NULL,
            value TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )");

        if (isset($_POST['update_profile'])) {
            $name = $_POST['name'] ?? '';
            $email = $_POST['email'] ?? '';
            $city = $_POST['city'] ?? '';
            
            // Update settings table for profile_info
            $data = json_encode([
                'name' => $name, 
                'email' => $email, 
                'city' => $city, 
                'role' => $profile['role'], 
                'id' => $profile['id'], 
                'initials' => strtoupper(substr($name, 0, 2))
            ]);
            $stmt = $conn->prepare("INSERT INTO settings (name, value) VALUES ('profile_info', ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->execute([$data, $data]);
            
            // Update session for immediate header update
            $nameParts = explode(' ', $name);
            $_SESSION['user']['firstName'] = $nameParts[0];
            $_SESSION['user']['lastName'] = $nameParts[1] ?? '';
            $_SESSION['user']['email'] = $email;
            
            $success = "Profil mis à jour !";
            $profile = $controller->getProfileInfo(); // Refresh
        }
        
        if (isset($_POST['update_general'])) {
            $clubName = $_POST['clubName'] ?? '';
            $slogan = $_POST['slogan'] ?? '';
            $language = $_POST['language'] ?? 'fr';
            $timezone = $_POST['timezone'] ?? '';
            
            $data = json_encode(['clubName' => $clubName, 'slogan' => $slogan, 'language' => $language, 'timezone' => $timezone]);
            $stmt = $conn->prepare("INSERT INTO settings (name, value) VALUES ('general_settings', ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->execute([$data, $data]);
            $success = "Paramètres généraux enregistrés !";
            $general = $controller->getGeneralSettings(); // Refresh
        }

        if (isset($_POST['update_branding'])) {
            $themeColor = $_POST['themeColor'] ?? 'indigo';
            $currentTheme = method_exists($controller, 'getThemeSettings') ? $controller->getThemeSettings() : [];
            $logoPath = $currentTheme['logo'] ?? null;

            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = 'uploads/branding/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
                $logoPath = $uploadDir . 'logo_' . time() . '_' . $_FILES['logo']['name'];
                move_uploaded_file($_FILES['logo']['tmp_name'], $logoPath);
            }

            $data = json_encode(['themeColor' => $themeColor, 'logo' => $logoPath]);
            $stmt = $conn->prepare("INSERT INTO settings (name, value) VALUES ('theme_settings', ?) ON DUPLICATE KEY UPDATE value = ?");
            $stmt->execute([$data, $data]);
            $success = "Branding mis à jour !";
        }
    } catch (Exception $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}

$themeSettings = method_exists($controller, 'getThemeSettings') ? $controller->getThemeSettings() : [];
$themeColor = $themeSettings['themeColor'] ?? 'indigo';

$navItems = [
    ['id' => 'profile', 'icon' => 'user', 'label' => 'Mon Profil'],
    ['id' => 'general', 'icon' => 'globe', 'label' => 'Général'],
    ['id' => 'branding', 'icon' => 'palette', 'label' => 'Branding & Design'],
    ['id' => 'payments', 'icon' => 'creditcard', 'label' => 'Paiements & Taxes'],
    ['id' => 'notifications', 'icon' => 'bell', 'label' => 'Notifications'],
    ['id' => 'security', 'icon' => 'shield', 'label' => 'Accès & Sécurité'],
];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Paramètres</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style> body { font-family: 'Inter', sans-serif; } </style>
</head>
<body class="bg-slate-50">
    <div class="flex min-h-screen">
        <?php renderSidebar($currentPage); ?>

        <main class="flex-1 min-w-0 overflow-auto">
            <?php renderHeader(); ?>
            <div class="p-8 max-w-6xl mx-auto pb-24">
                <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                            <?php echo icon('settings', 32, 'text-slate-400'); ?> Paramètres Système
                        </h1>
                        <p class="text-slate-500 font-medium mt-1">Configurez votre environnement de gestion NEEDSPORT</p>
                    </div>
                </div>

                <?php if ($success): ?>
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-emerald-700 text-sm font-bold animate-in">
                        ✅ <?php echo $success; ?>
                    </div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="mb-6 p-4 bg-rose-50 border border-rose-100 rounded-2xl text-rose-700 text-sm font-bold animate-in">
                        ❌ <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <div class="flex flex-col lg:flex-row gap-8">
                    <div class="lg:w-64 space-y-1">
                        <?php foreach ($navItems as $item): ?>
                            <a href="?page=settings&section=<?php echo $item['id']; ?>" class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl transition-all font-bold text-sm <?php echo $activeSection === $item['id'] ? 'bg-white text-indigo-600 shadow-sm border border-slate-100' : 'text-slate-500 hover:bg-white/50 hover:text-slate-900'; ?>">
                                <?php echo icon($item['icon'], 20); ?>
                                <span><?php echo $item['label']; ?></span>
                            </a>
                        <?php endforeach; ?>
                    </div>

                    <div class="flex-1 space-y-8">
                        <?php if ($activeSection === 'profile'): ?>
                            <div class="space-y-6">
                                <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                                    <h3 class="text-lg font-black text-slate-900 mb-8 flex items-center gap-2"><?php echo icon('user', 20, 'text-indigo-600'); ?> Informations du Profil</h3>
                                    <div class="flex flex-col md:flex-row items-start gap-8">
                                        <div class="relative group">
                                            <div class="h-32 w-32 rounded-3xl bg-indigo-600 flex items-center justify-center text-white text-4xl font-black shadow-xl shadow-indigo-100 relative overflow-hidden">
                                                <?php echo $profile['initials']; ?>
                                                <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer">
                                                    <?php echo icon('camera', 24); ?>
                                                </div>
                                            </div>
                                            <p class="text-center mt-3 text-[10px] font-black text-slate-400 uppercase tracking-widest">ID: #<?php echo $profile['id']; ?></p>
                                        </div>
                                        <div class="flex-1 w-full space-y-6">
                                            <form method="POST" class="space-y-6">
                                                <input type="hidden" name="update_profile" value="1">
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <div class="space-y-2">
                                                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Prénom & Nom</label>
                                                        <div class="relative"><span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"><?php echo icon('user', 16); ?></span><input type="text" name="name" value="<?php echo $profile['name']; ?>" class="w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"></div>
                                                    </div>
                                                    <div class="space-y-2">
                                                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Poste / Rôle</label>
                                                        <input type="text" value="<?php echo $profile['role']; ?>" class="w-full px-5 py-3.5 bg-slate-100 border border-slate-200 rounded-2xl outline-none font-bold text-slate-400 cursor-not-allowed" disabled>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                                    <div class="space-y-2">
                                                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Email</label>
                                                         <div class="relative"><span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"><?php echo icon('mail', 16); ?></span><input type="email" name="email" value="<?php echo $profile['email']; ?>" class="w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"></div>
                                                    </div>
                                                    <div class="space-y-2">
                                                        <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Ville</label>
                                                        <div class="relative"><span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300"><?php echo icon('map-pin', 16); ?></span><input type="text" name="city" value="<?php echo $profile['city']; ?>" class="w-full pl-12 pr-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"></div>
                                                    </div>
                                                </div>
                                                <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">Enregistrer les modifications</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php elseif ($activeSection === 'general'): ?>
                            <div class="space-y-6">
                                <form method="POST" class="space-y-6">
                                    <input type="hidden" name="update_general" value="1">
                                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                                        <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2"><?php echo icon('globe', 20, 'text-indigo-500'); ?> Informations du Club</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="space-y-2"><label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Nom du Club</label><input type="text" name="clubName" value="<?php echo $general['clubName']; ?>" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"></div>
                                            <div class="space-y-2"><label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Slogan / Sous-titre</label><input type="text" name="slogan" value="<?php echo $general['slogan']; ?>" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"></div>
                                        </div>
                                    </div>
                                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                                        <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2"><?php echo icon('languages', 20, 'text-emerald-500'); ?> Langue & Localisation</h3>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <div class="space-y-2"><label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Langue de l'interface</label><select name="language" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"><option value="fr" <?php echo $general['language'] === 'fr' ? 'selected' : ''; ?>>🇫🇷 Français (Maroc)</option><option value="en" <?php echo $general['language'] === 'en' ? 'selected' : ''; ?>>🇺🇸 English</option><option value="ar" <?php echo $general['language'] === 'ar' ? 'selected' : ''; ?>>🇲🇦 العربية</option></select></div>
                                            <div class="space-y-2"><label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Fuseau horaire</label><select name="timezone" class="w-full px-5 py-3.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-2xl outline-none transition-all font-bold text-slate-700"><option selected><?php echo $general['timezone']; ?></option></select></div>
                                        </div>
                                    </div>
                                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">Enregistrer les paramètres du club</button>
                                </form>
                            </div>
                        <?php elseif ($activeSection === 'branding'): ?>
                             <div class="space-y-6">
                                <form method="POST" enctype="multipart/form-data" class="space-y-6">
                                    <input type="hidden" name="update_branding" value="1">
                                    <input type="hidden" name="themeColor" id="themeColorInput" value="<?php echo $themeColor; ?>">
                                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                                        <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2"><?php echo icon('image', 20, 'text-rose-500'); ?> Logo du Club</h3>
                                        <div class="flex items-center gap-8">
                                            <div onclick="document.getElementById('logoInput').click()" class="h-32 w-32 rounded-3xl bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 hover:border-indigo-400 hover:bg-indigo-50 transition-all cursor-pointer group overflow-hidden">
                                                <?php if (isset($themeSettings['logo']) && file_exists($themeSettings['logo'])): ?>
                                                    <img src="<?php echo $themeSettings['logo']; ?>" class="w-full h-full object-contain">
                                                <?php else: ?>
                                                    <?php echo icon('trophy', 40, 'group-hover:scale-110 transition-transform'); ?><span class="text-[10px] font-black mt-2">LOGO</span>
                                                <?php endif; ?>
                                            </div>
                                            <div class="space-y-3">
                                                <p class="text-sm font-bold text-slate-700">Téléchargez votre logo</p>
                                                <p class="text-xs text-slate-400 leading-relaxed max-w-xs">Recommandé : PNG transparent, 512x512px. Apparaîtra sur les factures.</p>
                                                <input type="file" name="logo" id="logoInput" class="hidden" accept="image/*">
                                                <button type="button" onclick="document.getElementById('logoInput').click()" class="px-4 py-2 bg-slate-900 text-white text-xs font-black rounded-xl hover:bg-slate-800 transition-colors">Choisir un fichier</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm">
                                        <h3 class="text-lg font-black text-slate-900 mb-6 flex items-center gap-2"><?php echo icon('palette', 20, 'text-indigo-500'); ?> Couleur Thème</h3>
                                        <div class="flex gap-4" id="colorPicker">
                                            <button type="button" onclick="selectColor('indigo')" class="h-10 w-10 rounded-full border-4 transition-all <?php echo $themeColor === 'indigo' ? 'border-slate-900 scale-110' : 'border-transparent'; ?> bg-indigo-600"></button>
                                            <button type="button" onclick="selectColor('rose')" class="h-10 w-10 rounded-full border-4 transition-all <?php echo $themeColor === 'rose' ? 'border-slate-900 scale-110' : 'border-transparent'; ?> bg-rose-500"></button>
                                            <button type="button" onclick="selectColor('emerald')" class="h-10 w-10 rounded-full border-4 transition-all <?php echo $themeColor === 'emerald' ? 'border-slate-900 scale-110' : 'border-transparent'; ?> bg-emerald-500"></button>
                                            <button type="button" onclick="selectColor('amber')" class="h-10 w-10 rounded-full border-4 transition-all <?php echo $themeColor === 'amber' ? 'border-slate-900 scale-110' : 'border-transparent'; ?> bg-amber-500"></button>
                                        </div>
                                    </div>
                                    <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-100">Enregistrer le branding</button>
                                </form>
                            </div>
                        <?php else: ?>
                            <div class="bg-white p-20 rounded-3xl border border-slate-100 shadow-sm text-center">
                                <h3 class="text-lg font-black text-slate-400">Section en cours de construction</h3>
                                <p class="text-slate-400 text-sm">Cette section des paramètres est en cours de développement.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <script>
        function selectColor(color) {
            document.getElementById('themeColorInput').value = color;
            const buttons = document.querySelectorAll('#colorPicker button');
            buttons.forEach(btn => {
                btn.classList.remove('border-slate-900', 'scale-110');
                btn.classList.add('border-transparent');
            });
            event.currentTarget.classList.add('border-slate-900', 'scale-110');
            event.currentTarget.classList.remove('border-transparent');
        }
    </script>
    <?php renderDropdownScript(); ?>
</body>
</html>