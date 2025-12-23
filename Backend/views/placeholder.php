<?php
/**
 * Placeholder views - Create actual view files as needed
 */

require_once 'config/config.php';
require_once 'components/Components.php';
require_once 'components/Layout.php';

requireLogin();

// Determine which placeholder to show
$page = getParam('page', '');

// Generic placeholder for all stub views
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Placeholder</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>
<body>
    <div class="flex min-h-screen bg-slate-50">
        <?php renderSidebar($page); ?>

        <main class="flex-1 min-w-0 overflow-auto">
            <?php renderHeader(); ?>
            
            <div class="p-8">
                <div class="bg-white p-12 rounded-2xl border border-slate-100 shadow-sm text-center">
                    <h1 class="text-3xl font-black text-slate-900 mb-4">🚧 Page en Construction</h1>
                    <p class="text-slate-500 font-medium">Cette page sera bientôt disponible</p>
                    <a href="index.php?page=dashboard" class="inline-block mt-6 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700">
                        ← Retour au Tableau de Bord
                    </a>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
<?php
?>
