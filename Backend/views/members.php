<?php
/**
 * Members View
 */

require_once 'config/config.php';
require_once 'config/Models.php';
require_once 'controllers/MembersController.php';
require_once 'components/Components.php';
require_once 'components/Layout.php';
require_once 'components/Notifications.php';

requireLogin();

$membersCtrl = new MembersController($db);
$filters = [
    'sport' => getParam('sport', 'all'),
    'status' => getParam('status', 'all'),
    'search' => getParam('search', '')
];
$members = $membersCtrl->getAll($filters);
$mockData = require 'config/MockData.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Gestion des Membres</title>
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
        <?php renderSidebar('members'); ?>

        <main class="flex-1 min-w-0 overflow-auto">
            <?php renderHeader(); ?>
            
            <div class="p-8">
                <!-- Header -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 mb-8">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                            👥 Gestion des Membres
                        </h1>
                        <p class="text-slate-500 font-medium mt-1">Gérez vos abonnés et suivez les renouvellements</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="javascript:void(0)" class="flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-slate-600 font-bold rounded-xl hover:bg-slate-50 transition-all text-sm shadow-sm">
                            🖨️ Imprimer
                        </a>
                        <a href="javascript:void(0)" class="flex items-center gap-2 px-4 py-2.5 bg-emerald-600 text-white font-bold rounded-xl hover:bg-emerald-700 transition-all text-sm shadow-lg shadow-emerald-100">
                            ⬇️ Exporter
                        </a>
                        <a href="index.php?page=add-member" class="flex items-center gap-2 px-6 py-2.5 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all text-sm shadow-lg shadow-indigo-100">
                            ➕ Nouveau Membre
                        </a>
                    </div>
                </div>

                <!-- Filters -->
                <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-6 mb-8">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2">
                            🔍 Filtres avancés
                        </h3>
                        <a href="index.php?page=members" class="text-xs font-bold text-indigo-600 hover:text-indigo-700 hover:underline">
                            Réinitialiser
                        </a>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">🔍</span>
                            <input 
                                type="text"
                                placeholder="Nom, email, téléphone..."
                                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none transition-all text-sm font-medium"
                                value="<?php echo htmlspecialchars($filters['search']); ?>"
                                onchange="document.location='index.php?page=members&search=' + this.value"
                            />
                        </div>

                        <select class="px-4 py-2.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none transition-all text-sm font-medium text-slate-600"
                                onchange="document.location='index.php?page=members&sport=' + this.value">
                            <option value="all">Tous les sports</option>
                            <?php foreach ($mockData['activities'] as $activity): ?>
                                <option value="<?php echo htmlspecialchars($activity['name']); ?>" <?php echo $filters['sport'] === $activity['name'] ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($activity['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <select class="px-4 py-2.5 bg-slate-50 border border-slate-100 focus:bg-white focus:border-indigo-500 rounded-xl outline-none transition-all text-sm font-medium text-slate-600"
                                onchange="document.location='index.php?page=members&status=' + this.value">
                            <option value="all">Tous les statuts</option>
                            <option value="actif" <?php echo $filters['status'] === 'actif' ? 'selected' : ''; ?>>Actif</option>
                            <option value="expirant" <?php echo $filters['status'] === 'expirant' ? 'selected' : ''; ?>>Expire bientôt</option>
                            <option value="expire" <?php echo $filters['status'] === 'expire' ? 'selected' : ''; ?>>Expiré</option>
                        </select>
                    </div>
                </div>

                <!-- Members Table -->
                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-bold tracking-wider">
                                <tr>
                                    <th class="px-6 py-4">Membre</th>
                                    <th class="px-6 py-4">Sport</th>
                                    <th class="px-6 py-4">Statut</th>
                                    <th class="px-6 py-4">Date Expiration</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                <?php
                                if (empty($members)) {
                                    echo '<tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">Aucun membre trouvé</td></tr>';
                                } else {
                                    foreach ($members as $member):
                                        $status = getMemberStatus($member['expiryDate']);
                                        $fullName = $member['firstName'] . ' ' . $member['lastName'];
                                        ?>
                                        <tr class="hover:bg-slate-50 transition-colors">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center gap-3">
                                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold text-sm">
                                                        <?php echo substr($member['firstName'], 0, 1) . substr($member['lastName'], 0, 1); ?>
                                                    </div>
                                                    <div>
                                                        <div class="font-semibold text-sm text-slate-900"><?php echo htmlspecialchars($fullName); ?></div>
                                                        <div class="text-xs text-slate-500"><?php echo htmlspecialchars($member['email']); ?></div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">
                                                    <?php echo htmlspecialchars($member['sport']); ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php renderStatusBadge($status); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-700">
                                                <?php echo formatDate($member['expiryDate']); ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                                                <button class="text-indigo-600 hover:text-indigo-900 font-bold text-xs py-1.5 px-3 bg-indigo-50 rounded-lg transition-all">
                                                    Renew
                                                </button>
                                                <button class="text-rose-600 hover:text-rose-900 font-bold text-xs py-1.5 px-3 bg-rose-50 rounded-lg transition-all">
                                                    Edit
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    endforeach;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
<?php
?>
