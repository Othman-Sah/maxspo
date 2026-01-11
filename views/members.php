<?php
/**
 * Members View
 */

require_once 'config/config.php';
require_once 'config/Models.php';
require_once 'components/Components.php';
require_once 'components/Layout.php';
require_once 'components/Notifications.php';

requireLogin();

// Ensure we have database access
global $db;

// Handle Delete Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_member_id'])) {
    $memberId = (int)$_POST['delete_member_id'];
    try {
        $conn = $db->getConnection();
        $stmt = $conn->prepare("DELETE FROM members WHERE id = ?");
        $stmt->execute([$memberId]);
        header("Location: index.php?page=members&success=deleted");
        exit;
    } catch (Exception $e) { error_log("Delete error: " . $e->getMessage()); }
}

// Handle Edit Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_member'])) {
    $memberId = (int)$_POST['member_id'];
    $firstName = $_POST['firstName'];
    $lastName = $_POST['lastName'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $sport = $_POST['sport'];
    try {
        $conn = $db->getConnection();
        $stmt = $conn->prepare("UPDATE members SET firstName = ?, lastName = ?, email = ?, phone = ?, sport = ? WHERE id = ?");
        $stmt->execute([$firstName, $lastName, $email, $phone, $sport, $memberId]);
        header("Location: index.php?page=members&success=updated");
        exit;
    } catch (Exception $e) { error_log("Update error: " . $e->getMessage()); }
}

// Handle Renewal Logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['renew_member_id'])) {
    $memberId = (int)$_POST['renew_member_id'];
    try {
        $conn = $db->getConnection();
        // Ensure payments table exists
        $conn->exec("CREATE TABLE IF NOT EXISTS payments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            member_id INT,
            amount DECIMAL(10,2),
            date DATE,
            method VARCHAR(50),
            status VARCHAR(20),
            sport VARCHAR(100),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");

        // Get member info to calculate new expiry and record payment
        $stmt = $conn->prepare("SELECT sport, expiryDate FROM members WHERE id = ?");
        $stmt->execute([$memberId]);
        $member = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($member) {
            $currentExpiry = strtotime($member['expiryDate']);
            $baseDate = ($currentExpiry > time()) ? $currentExpiry : time();
            $newExpiry = date('Y-m-d', strtotime('+1 year', $baseDate));

            // Update member expiry
            $updateStmt = $conn->prepare("UPDATE members SET expiryDate = ?, status = 'actif' WHERE id = ?");
            $updateStmt->execute([$newExpiry, $memberId]);

            // Get activity price for payment record
            $priceStmt = $conn->prepare("SELECT monthlyPrice FROM activities WHERE name = ?");
            $priceStmt->execute([$member['sport']]);
            $amount = ($priceStmt->fetchColumn() ?: 0) * 12; // Calculate for 12 months

            // Record the yearly payment
            $payStmt = $conn->prepare("INSERT INTO payments (member_id, amount, date, method, status, sport) VALUES (?, ?, ?, ?, ?, ?)");
            $payStmt->execute([$memberId, $amount, date('Y-m-d'), 'especes', 'valide', $member['sport']]);
            
            header("Location: index.php?page=members&success=renewed");
            exit;
        }
    } catch (Exception $e) { error_log("Renewal error: " . $e->getMessage()); }
}

// Get filters
$filters = [
    'sport' => getParam('sport', 'all'),
    'status' => getParam('status', 'all'),
    'search' => getParam('search', '')
];

// Inline MembersController logic - Get all members with filters
try {
    $conn = $db->getConnection();
    $sql = "SELECT id, firstName, lastName, email, phone, age, sport, status, expiryDate, joinDate, isLoyal FROM members ORDER BY id DESC";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $members = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($members as &$m) {
        $m['isLoyal'] = (bool)$m['isLoyal'];
        $m['age'] = (int)$m['age'];
    }
    
    // Apply filters
    if (!empty($filters['sport']) && $filters['sport'] !== 'all') {
        $members = array_filter($members, fn($m) => $m['sport'] === $filters['sport']);
    }

    if (!empty($filters['status']) && $filters['status'] !== 'all') {
        $members = array_filter($members, fn($m) => getMemberStatus($m['expiryDate']) === $filters['status']);
    }

    if (!empty($filters['search'])) {
        $search = strtolower($filters['search']);
        $members = array_filter($members, fn($m) => 
            stripos($m['firstName'] . ' ' . $m['lastName'], $search) !== false ||
            stripos($m['email'], $search) !== false ||
            stripos($m['phone'], $search) !== false
        );
    }
    
} catch (Exception $e) {
    error_log("Members fetch error: " . $e->getMessage());
    $members = [];
}

// Load activities from database
try {
    $activitiesStmt = $db->getConnection()->query("SELECT id, name FROM activities");
    $activities = $activitiesStmt ? $activitiesStmt->fetchAll(PDO::FETCH_ASSOC) : [];
} catch (Exception $e) {
    $activities = [];
}
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
<body class="transition-colors">
    <div class="flex min-h-screen bg-slate-50">
        <?php renderSidebar('members'); ?>

        <main class="flex-1 min-w-0 overflow-auto">
            <?php renderHeader(); ?>
            
            <div class="p-8">
                <!-- Header -->
                <?php if (isset($_GET['success'])): ?>
                    <div class="mb-6 p-4 bg-emerald-50 border border-emerald-100 rounded-xl text-emerald-700 text-sm font-medium animate-in">
                        ✅ Action effectuée avec succès !
                    </div>
                <?php endif; ?>

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
                            <?php foreach ($activities as $activity): ?>
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
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="renew_member_id" value="<?php echo $member['id']; ?>">
                                                    <button type="submit" onclick="return confirm('Renouveler l\'abonnement pour 1 an ?')" class="text-indigo-600 hover:text-indigo-900 font-bold text-xs py-1.5 px-3 bg-indigo-50 rounded-lg transition-all">
                                                        Renew
                                                    </button>
                                                </form>
                                                <button onclick='openEditModal(<?php echo json_encode($member); ?>)' class="text-amber-600 hover:text-amber-900 font-bold text-xs py-1.5 px-3 bg-amber-50 rounded-lg transition-all">
                                                    Edit
                                                </button>
                                                <form method="POST" class="inline" onsubmit="return confirm('Supprimer ce membre définitivement ?')">
                                                    <input type="hidden" name="delete_member_id" value="<?php echo $member['id']; ?>">
                                                    <button type="submit" class="text-rose-600 hover:text-rose-900 font-bold text-xs py-1.5 px-3 bg-rose-50 rounded-lg transition-all">
                                                        Delete
                                                    </button>
                                                </form>
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

    <!-- Edit Member Modal -->
    <div id="editMemberModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-3xl shadow-2xl max-w-lg w-full p-8 animate-in">
            <h2 class="text-2xl font-black text-slate-900 mb-6">Modifier le Membre</h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="update_member" value="1">
                <input type="hidden" name="member_id" id="edit_id">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Prénom</label>
                        <input type="text" name="firstName" id="edit_firstName" required class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl font-bold">
                    </div>
                    <div>
                        <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Nom</label>
                        <input type="text" name="lastName" id="edit_lastName" required class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl font-bold">
                    </div>
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Email</label>
                    <input type="email" name="email" id="edit_email" required class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Téléphone</label>
                    <input type="text" name="phone" id="edit_phone" required class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl font-bold">
                </div>
                <div>
                    <label class="block text-[10px] font-black uppercase text-slate-400 mb-1">Sport</label>
                    <select name="sport" id="edit_sport" required class="w-full px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl font-bold">
                        <?php foreach ($activities as $activity): ?>
                            <option value="<?php echo htmlspecialchars($activity['name']); ?>"><?php echo htmlspecialchars($activity['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="flex-1 py-3 bg-indigo-600 text-white font-black rounded-xl hover:bg-indigo-700 transition-all">Enregistrer</button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 py-3 bg-slate-100 text-slate-500 font-black rounded-xl hover:bg-slate-200 transition-all">Annuler</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(member) {
            document.getElementById('edit_id').value = member.id;
            document.getElementById('edit_firstName').value = member.firstName;
            document.getElementById('edit_lastName').value = member.lastName;
            document.getElementById('edit_email').value = member.email;
            document.getElementById('edit_phone').value = member.phone;
            document.getElementById('edit_sport').value = member.sport;
            document.getElementById('editMemberModal').classList.remove('hidden');
        }
        function closeEditModal() {
            document.getElementById('editMemberModal').classList.add('hidden');
        }
    </script>
    <?php renderDropdownScript(); ?>
</body>
</html>
<?php
?>
