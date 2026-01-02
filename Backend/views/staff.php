<?php
/**
 * Staff Management View
 * Team members management with add, edit, delete functionality
 */

require_once 'config/config.php';
require_once 'components/Layout.php';
require_once 'components/Notifications.php';
require_once 'helpers/Icons.php';

requireLogin();

global $db;
$currentPage = 'staff';

// Handle adding staff
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_staff'])) {
    $name = $_POST['name'] ?? '';
    $job = $_POST['job'] ?? '';
    $salary = $_POST['salary'] ?? 0;
    $picture = '';
    
    // Handle file upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/staff/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $filename = uniqid() . '_' . basename($_FILES['picture']['name']);
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['picture']['tmp_name'], $filepath)) {
            $picture = $filepath;
        }
    }
    
    if (empty($name) || empty($job)) {
        $error = 'Name and job are required';
    } else {
        try {
            $conn = $db->getConnection();
            // Create staff table if it doesn't exist
            $conn->exec("CREATE TABLE IF NOT EXISTS staff (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                job VARCHAR(100) NOT NULL,
                salary DECIMAL(10, 2) DEFAULT 0,
                picture VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            
            $stmt = $conn->prepare("INSERT INTO staff (name, job, salary, picture) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $job, $salary, $picture]);
            $success = 'Staff member added successfully!';
            header("Refresh: 1; url=index.php?page=staff");
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Handle updating staff
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_staff'])) {
    $staffId = $_POST['staff_id'] ?? 0;
    $name = $_POST['name'] ?? '';
    $job = $_POST['job'] ?? '';
    $salary = $_POST['salary'] ?? 0;
    
    // Get current picture
    try {
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT picture FROM staff WHERE id = ?");
        $stmt->execute([$staffId]);
        $currentStaff = $stmt->fetch(PDO::FETCH_ASSOC);
        $picture = $currentStaff['picture'] ?? '';
    } catch (Exception $e) {
        $picture = '';
    }
    
    // Handle file upload
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/staff/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $filename = uniqid() . '_' . basename($_FILES['picture']['name']);
        $filepath = $uploadDir . $filename;
        
        if (move_uploaded_file($_FILES['picture']['tmp_name'], $filepath)) {
            // Delete old picture if exists
            if (!empty($picture) && file_exists($picture)) {
                unlink($picture);
            }
            $picture = $filepath;
        }
    }
    
    if (empty($name) || empty($job)) {
        $error = 'Name and job are required';
    } else {
        try {
            $conn = $db->getConnection();
            $stmt = $conn->prepare("UPDATE staff SET name=?, job=?, salary=?, picture=? WHERE id=?");
            $stmt->execute([$name, $job, $salary, $picture, $staffId]);
            $success = 'Staff member updated successfully!';
            header("Refresh: 1; url=index.php?page=staff");
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Handle deleting staff
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_staff'])) {
    $staffId = $_POST['staff_id'] ?? 0;
    try {
        $conn = $db->getConnection();
        $stmt = $conn->prepare("DELETE FROM staff WHERE id = ?");
        $stmt->execute([$staffId]);
        header("Refresh: 0.5; url=index.php?page=staff");
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
    }
}

// Get all staff
$staff = [];
try {
    $conn = $db->getConnection();
    
    // Migrate: Add job, salary, and picture columns if they don't exist
    try {
        $conn->exec("ALTER TABLE staff ADD COLUMN job VARCHAR(100) DEFAULT 'Staff'");
    } catch (Exception $e) {}
    try {
        $conn->exec("ALTER TABLE staff ADD COLUMN salary DECIMAL(10, 2) DEFAULT 0");
    } catch (Exception $e) {}
    try {
        $conn->exec("ALTER TABLE staff ADD COLUMN picture VARCHAR(255)");
    } catch (Exception $e) {}
    
    $stmt = $conn->query("SELECT * FROM staff ORDER BY id DESC");
    $staff = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Table might not exist yet
}

// Get initials for avatars
function getInitials($name) {
    $parts = explode(' ', trim($name));
    return strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
}

// Avatar colors
$avatarColors = ['bg-indigo-600', 'bg-blue-600', 'bg-purple-600', 'bg-pink-600', 'bg-rose-600', 'bg-red-600', 'bg-orange-600', 'bg-green-600'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Staff Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .animate-in { animation: animateIn 0.5s ease-out forwards; }
        @keyframes animateIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body class="bg-slate-50">
    <div class="flex min-h-screen">
        <?php renderSidebar($currentPage); ?>

        <main class="flex-1 min-w-0 overflow-auto">
            <?php renderHeader(); ?>

            <div class="p-8 space-y-8 animate-in pb-12">
                <!-- Header Section -->
                <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                            <?php echo icon('users', 32, 'text-indigo-600'); ?>
                            Team Management
                        </h1>
                        <p class="text-slate-500 font-medium mt-1">Manage your coaches and staff members.</p>
                    </div>
                    <button onclick="openAddModal()" class="flex items-center gap-2 px-6 py-3 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-700 transition-all shadow-lg">
                        <?php echo icon('plus', 18); ?>
                        Add Staff Member
                    </button>
                </div>

                <?php if (isset($error)): ?>
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-700"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-700"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <!-- Staff Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php if (empty($staff)): ?>
                        <div class="col-span-full text-center py-12">
                            <p class="text-slate-500 font-medium">No staff members yet. Click "Add Staff Member" to get started.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($staff as $index => $member): 
                            $initials = getInitials($member['name']);
                            $avatarColor = $avatarColors[$index % count($avatarColors)];
                        ?>
                            <div class="bg-white rounded-3xl border border-slate-100 shadow-lg hover:shadow-xl transition-all overflow-hidden group">
                                <!-- Avatar Section -->
                                <div class="h-24 bg-gradient-to-br from-slate-50 to-slate-100"></div>
                                
                                <div class="px-6 pb-6 -mt-12 relative z-10">
                                    <!-- Avatar Circle -->
                                    <div class="flex justify-center mb-4">
                                        <?php if (!empty($member['picture']) && file_exists($member['picture'])): ?>
                                            <img src="<?php echo htmlspecialchars($member['picture']); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" class="h-24 w-24 rounded-2xl object-cover shadow-lg border-4 border-white">
                                        <?php else: ?>
                                            <div class="<?php echo $avatarColor; ?> h-24 w-24 rounded-2xl flex items-center justify-center text-white text-2xl font-black shadow-lg border-4 border-white">
                                                <?php echo htmlspecialchars($initials); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Info -->
                                    <div class="text-center space-y-1 mb-4">
                                        <h3 class="text-lg font-black text-slate-900"><?php echo htmlspecialchars($member['name']); ?></h3>
                                        <p class="text-sm font-bold text-indigo-600"><?php echo htmlspecialchars($member['job'] ?? 'Staff'); ?></p>
                                    </div>
                                    
                                    <!-- Salary -->
                                    <div class="bg-slate-50 rounded-xl p-3 mb-4 text-center">
                                        <p class="text-xs font-bold uppercase text-slate-500">Salary</p>
                                        <p class="text-lg font-black text-slate-900">$<?php echo number_format($member['salary'] ?? 0, 2); ?></p>
                                    </div>
                                    
                                    <!-- Actions -->
                                    <div class="flex gap-2">
                                        <button type="button" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($member)); ?>)" class="flex-1 px-3 py-2 bg-blue-50 text-blue-600 hover:bg-blue-100 rounded-lg font-bold text-sm transition-colors">
                                            Edit
                                        </button>
                                        <button type="button" onclick="deleteStaff(<?php echo $member['id']; ?>)" class="flex-1 px-3 py-2 bg-red-50 text-red-600 hover:bg-red-100 rounded-lg font-bold text-sm transition-colors">
                                            Delete
                                        </button>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-in overflow-y-auto max-h-[90vh]">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Add Staff Member</h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="add_staff" value="1">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Picture</label>
                    <input type="file" name="picture" accept="image/*" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-slate-500 mt-1">JPG, PNG, GIF (max 5MB)</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Name</label>
                    <input type="text" name="name" required placeholder="e.g., John Smith" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Job</label>
                    <input type="text" name="job" required placeholder="e.g., Fitness Trainer" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Salary</label>
                    <input type="number" name="salary" placeholder="0.00" step="0.01" min="0" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="flex gap-2 pt-4">
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700">Add</button>
                    <button type="button" onclick="closeAddModal()" class="flex-1 px-4 py-2 bg-slate-200 text-slate-900 font-bold rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-in overflow-y-auto max-h-[90vh]">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Edit Staff Member</h2>
            <form method="POST" enctype="multipart/form-data" class="space-y-4">
                <input type="hidden" name="update_staff" value="1">
                <input type="hidden" name="staff_id" id="editStaffId">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Picture</label>
                    <input type="file" name="picture" accept="image/*" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <p class="text-xs text-slate-500 mt-1">Leave empty to keep current picture</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Name</label>
                    <input type="text" name="name" id="editName" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Job</label>
                    <input type="text" name="job" id="editJob" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Salary</label>
                    <input type="number" name="salary" id="editSalary" step="0.01" min="0" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div class="flex gap-2 pt-4">
                    <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700">Update</button>
                    <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 bg-slate-200 text-slate-900 font-bold rounded-lg">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Form -->
    <form id="deleteForm" method="POST" style="display: none;">
        <input type="hidden" name="delete_staff" value="1">
        <input type="hidden" name="staff_id" id="deleteStaffId">
    </form>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }
        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }
        function openEditModal(staff) {
            document.getElementById('editStaffId').value = staff.id;
            document.getElementById('editName').value = staff.name;
            document.getElementById('editJob').value = staff.job;
            document.getElementById('editSalary').value = staff.salary;
            document.getElementById('editModal').classList.remove('hidden');
        }
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        function deleteStaff(id) {
            if (confirm('Are you sure you want to delete this staff member?')) {
                document.getElementById('deleteStaffId').value = id;
                document.getElementById('deleteForm').submit();
            }
        }

        // Close modals when clicking outside
        document.getElementById('addModal').addEventListener('click', function(e) {
            if (e.target === this) closeAddModal();
        });
        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) closeEditModal();
        });
    </script>
    <?php renderDropdownScript(); ?>
</body>
</html>
