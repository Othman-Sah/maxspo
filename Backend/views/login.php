<?php
/**
 * Login View
 */

require_once 'config/config.php';

// If already logged in, redirect to dashboard
if (isLoggedIn()) {
    header('Location: index.php?page=dashboard');
    exit;
}

// Handle login form submission
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = sanitizeInput($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // For demo: validate with hardcoded credentials
    if ($email === 'admin@needsport.ma' && $password === 'password') {
        $_SESSION['user_id'] = 1;
        $_SESSION['admin_role'] = 'admin';
        $_SESSION['user'] = [
            'id' => 1,
            'firstName' => 'Admin',
            'lastName' => 'Coach',
            'email' => $email,
            'role' => 'admin'
        ];
        header('Location: index.php?page=dashboard');
        exit;
    } else {
        $error = 'Email ou mot de passe incorrect';
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Connexion</title>
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
    <div class="min-h-screen bg-slate-50 flex items-center justify-center p-4 sm:p-6">
        <div class="w-full max-w-5xl bg-white rounded-[40px] shadow-2xl shadow-slate-200 overflow-hidden flex flex-col md:flex-row min-h-[600px]">
            
            <!-- Left Side: Hero -->
            <div class="md:w-5/12 bg-slate-900 relative p-12 flex flex-col justify-between overflow-hidden">
                <div class="absolute -top-20 -left-20 w-64 h-64 bg-indigo-600/20 rounded-full blur-[100px] animate-pulse"></div>
                <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-emerald-600/10 rounded-full blur-[100px] animate-pulse" style="animation-delay: 0.7s;"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-12">
                        <div class="bg-indigo-600 p-2.5 rounded-2xl text-white shadow-lg shadow-indigo-500/20">
                            <?php require_once ROOT_PATH . '/helpers/Icons.php'; echo trophyIcon(24); ?>
                        </div>
                        <span class="text-2xl font-black text-white tracking-tight">NEEDSPORT</span>
                    </div>
                    
                    <div class="space-y-6">
                        <h1 class="text-4xl lg:text-5xl font-black text-white leading-tight">
                            Gérez votre club comme un <span class="text-indigo-400">champion.</span>
                        </h1>
                        <p class="text-slate-400 text-sm font-medium leading-relaxed max-w-xs">
                            La plateforme de gestion tout-en-un pour les clubs de sport d'élite. Suivi des membres, revenus et planning en temps réel.
                        </p>
                    </div>
                </div>

                <div class="relative z-10">
                    <div class="flex items-center gap-3 p-4 bg-white/5 rounded-3xl border border-white/10 backdrop-blur-sm">
                        <div class="h-10 w-10 bg-indigo-500/20 rounded-xl flex items-center justify-center text-indigo-400">
                            <?php echo icon('lock', 18); ?>
                        </div>
                        <div>
                            <p class="text-xs font-black text-white uppercase tracking-widest">Accès Sécurisé</p>
                            <p class="text-[10px] text-slate-500 font-bold">Chiffrement SSL 256-bit actif</p>
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-600 mt-6 font-bold uppercase tracking-widest">© 2024 NEEDSPORT PRO VERSION 2.4</p>
                </div>
            </div>

            <!-- Right Side: Form -->
            <div class="md:w-7/12 p-8 lg:p-16 flex flex-col justify-center bg-white">
                <div class="max-w-md mx-auto w-full space-y-10">
                    <div>
                        <h2 class="text-3xl font-black text-slate-900 tracking-tight">Content de vous revoir !</h2>
                        <p class="text-slate-500 font-medium mt-2">Connectez-vous pour accéder au tableau de bord.</p>
                    </div>

                    <?php if ($error): ?>
                        <div class="p-4 bg-rose-50 border border-rose-100 rounded-xl text-rose-700 text-sm font-medium">
                            ⚠️ <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest ml-1">Adresse Email</label>
                            <div class="relative group">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">✉️</span>
                                <input 
                                    type="email" 
                                    name="email"
                                    required
                                    class="w-full pl-12 pr-5 py-4 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 rounded-2xl outline-none transition-all font-bold text-slate-700"
                                    placeholder="admin@needsport.ma"
                                    value="admin@needsport.ma"
                                />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <div class="flex justify-between items-center ml-1">
                                <label class="text-[10px] font-black uppercase text-slate-400 tracking-widest">Mot de passe</label>
                                <button type="button" class="text-[10px] font-black uppercase text-indigo-600 hover:underline">Oublié ?</button>
                            </div>
                            <div class="relative group">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-300">🔐</span>
                                <input 
                                    type="password" 
                                    name="password"
                                    required
                                    class="w-full pl-12 pr-12 py-4 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 focus:ring-4 focus:ring-indigo-50 rounded-2xl outline-none transition-all font-bold text-slate-700"
                                    placeholder="••••••••"
                                    value="password"
                                />
                            </div>
                        </div>

                        <div class="flex items-center gap-2 ml-1">
                            <input type="checkbox" id="remember" class="rounded border-slate-300" />
                            <label for="remember" class="text-xs font-bold text-slate-500 cursor-pointer">Se souvenir de moi</label>
                        </div>

                        <button 
                            type="submit"
                            class="w-full py-4 bg-indigo-600 text-white font-black rounded-2xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition-all active:scale-[0.98] flex items-center justify-center gap-3 group"
                        >
                            <span class="uppercase tracking-widest text-xs">Se connecter</span>
                            <span class="group-hover:translate-x-1 transition-transform">→</span>
                        </button>
                    </form>

                    <div class="pt-8 border-t border-slate-50 text-center">
                        <p class="text-xs font-bold text-slate-400">
                            Pas encore de compte ? <button type="button" class="text-indigo-600 hover:underline">Contactez le support</button>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
?>
