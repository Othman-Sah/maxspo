<?php
/**
 * Schedule / Planning View with Drag-and-Drop
 * Weekly calendar with activities and time slots
 */

require_once 'config/config.php';
require_once 'components/Layout.php';
require_once 'components/Notifications.php';
require_once 'helpers/Icons.php';

requireLogin();

global $db;
$currentPage = 'schedule';

// Handle getting schedule data via AJAX
if (isset($_GET['get_schedule'])) {
    header('Content-Type: application/json');
    $scheduleId = $_GET['get_schedule'];
    try {
        $conn = $db->getConnection();
        $stmt = $conn->prepare("SELECT * FROM schedule WHERE id = ?");
        $stmt->execute([$scheduleId]);
        $schedule = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($schedule);
    } catch (Exception $e) {
        echo json_encode(['error' => $e->getMessage()]);
    }
    exit;
}

// Handle adding schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_schedule'])) {
    $day = $_POST['day'] ?? '';
    $time = $_POST['time'] ?? '';
    $activity = $_POST['activity'] ?? '';
    $room = $_POST['room'] ?? '';
    $instructor = $_POST['instructor'] ?? '';
    
    if (empty($day) || empty($time) || empty($activity)) {
        $error = 'Day, time, and activity are required';
    } else {
        try {
            $conn = $db->getConnection();
            // Create schedule table if it doesn't exist
            $conn->exec("CREATE TABLE IF NOT EXISTS schedule (
                id INT AUTO_INCREMENT PRIMARY KEY,
                day VARCHAR(20),
                time VARCHAR(20),
                activity VARCHAR(50),
                room VARCHAR(50),
                instructor VARCHAR(100),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            
            $stmt = $conn->prepare("INSERT INTO schedule (day, time, activity, room, instructor) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$day, $time, $activity, $room, $instructor]);
            $success = 'Class added successfully!';
            header("Refresh: 1; url=index.php?page=schedule");
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Handle updating schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_schedule'])) {
    $scheduleId = $_POST['schedule_id'] ?? 0;
    $day = $_POST['day'] ?? '';
    $time = $_POST['time'] ?? '';
    $activity = $_POST['activity'] ?? '';
    $room = $_POST['room'] ?? '';
    $instructor = $_POST['instructor'] ?? '';
    
    if (empty($day) || empty($time) || empty($activity)) {
        $error = 'Day, time, and activity are required';
    } else {
        try {
            $conn = $db->getConnection();
            $stmt = $conn->prepare("UPDATE schedule SET day=?, time=?, activity=?, room=?, instructor=? WHERE id=?");
            $stmt->execute([$day, $time, $activity, $room, $instructor, $scheduleId]);
            $success = 'Class updated successfully!';
            header("Refresh: 1; url=index.php?page=schedule");
        } catch (Exception $e) {
            $error = "Error: " . $e->getMessage();
        }
    }
}

// Handle deleting schedule
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_schedule'])) {
    $scheduleId = $_POST['schedule_id'] ?? 0;
    try {
        $conn = $db->getConnection();
        $stmt = $conn->prepare("DELETE FROM schedule WHERE id = ?");
        $stmt->execute([$scheduleId]);
        header("Refresh: 0.5; url=index.php?page=schedule");
    } catch (Exception $e) {
        error_log("Error: " . $e->getMessage());
    }
}

// Get all schedules
$schedules = [];
try {
    $conn = $db->getConnection();
    $stmt = $conn->query("SELECT * FROM schedule ORDER BY FIELD(day, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'), time");
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Table might not exist yet
}

// Get activities for dropdown
$activities = [];
try {
    $conn = $db->getConnection();
    $stmt = $conn->query("SELECT DISTINCT name FROM activities");
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Handle error
}

$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
$times = ['09:00', '11:00', '14:00', '16:00', '18:00'];
$timeBlocks = array_map(fn($t) => ['time' => $t, 'label' => $t], $times);

// Vibrant gradient activity colors
$activityColors = [
    'Boxing' => 'bg-gradient-to-br from-red-500 to-rose-700',
    'Lifting' => 'bg-gradient-to-br from-green-500 to-emerald-700',
    'Yoga' => 'bg-gradient-to-br from-purple-500 to-indigo-700',
    'Fitness' => 'bg-gradient-to-br from-blue-500 to-cyan-700',
    'CrossFit' => 'bg-gradient-to-br from-orange-500 to-red-700',
    'Swimming' => 'bg-gradient-to-br from-cyan-500 to-blue-700',
    'Dance' => 'bg-gradient-to-br from-pink-500 to-rose-700',
    'Pilates' => 'bg-gradient-to-br from-indigo-500 to-purple-700',
    'Zumba' => 'bg-gradient-to-br from-rose-500 to-pink-700',
    'Martial Arts' => 'bg-gradient-to-br from-amber-500 to-orange-700',
];

// Try to get from database and override defaults
try {
    $conn = $db->getConnection();
    $stmt = $conn->query("SELECT name FROM activities");
    $activities_list = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $gradientOptions = [
        'bg-gradient-to-br from-red-500 to-rose-700',
        'bg-gradient-to-br from-green-500 to-emerald-700',
        'bg-gradient-to-br from-blue-500 to-cyan-700',
        'bg-gradient-to-br from-purple-500 to-indigo-700',
        'bg-gradient-to-br from-orange-500 to-red-700',
        'bg-gradient-to-br from-pink-500 to-rose-700',
        'bg-gradient-to-br from-indigo-500 to-purple-700',
        'bg-gradient-to-br from-cyan-500 to-blue-700',
        'bg-gradient-to-br from-amber-500 to-orange-700',
        'bg-gradient-to-br from-teal-500 to-cyan-700',
    ];
    $i = 0;
    foreach ($activities_list as $activity) {
        if (!isset($activityColors[$activity['name']])) {
            $activityColors[$activity['name']] = $gradientOptions[$i % count($gradientOptions)];
            $i++;
        }
    }
} catch (Exception $e) {
    // Use defaults
}
?>
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NEEDSPORT Pro - Schedule</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .animate-in { animation: animateIn 0.5s ease-out forwards; }
        @keyframes animateIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        .draggable { cursor: grab; }
        .draggable.dragging { opacity: 0.6; cursor: grabbing; transform: scale(0.95); }
        .drop-slot { transition: all 0.2s; }
        .drop-slot.drag-over { background-color: rgba(99, 102, 241, 0.15); border-color: rgb(99, 102, 241); box-shadow: inset 0 0 10px rgba(99, 102, 241, 0.2); }
        .schedule-card { box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15); }
        .schedule-card:hover { transform: translateY(-2px); box-shadow: 0 12px 24px rgba(0, 0, 0, 0.2); }
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
                            <?php echo icon('calendar-days', 32, 'text-indigo-600'); ?>
                            Weekly Schedule
                        </h1>
                        <p class="text-slate-500 font-medium mt-1">Manage class schedules with drag-and-drop functionality.</p>
                    </div>
                    <button onclick="openAddModal()" class="flex items-center gap-2 px-6 py-3 bg-slate-900 text-white font-bold rounded-xl hover:bg-slate-800 transition-all shadow-lg">
                        <?php echo icon('plus', 18); ?>
                        Add Class
                    </button>
                </div>

                <?php if (isset($error)): ?>
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg text-red-700"><?php echo htmlspecialchars($error); ?></div>
                <?php endif; ?>
                <?php if (isset($success)): ?>
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg text-green-700"><?php echo htmlspecialchars($success); ?></div>
                <?php endif; ?>

                <!-- Schedule Table -->
                <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="p-4 w-32 text-left"></th>
                                    <?php foreach ($times as $time): ?>
                                        <th class="p-4 text-center min-w-[140px]">
                                            <span class="text-sm font-bold text-slate-900"><?php echo $time; ?></span>
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($days as $day): ?>
                                    <tr class="border-b border-slate-50 last:border-0">
                                        <td class="p-4 bg-slate-50/30 font-bold text-slate-900 text-sm min-w-[120px]"><?php echo $day; ?></td>
                                        <?php foreach ($times as $time):
                                            $dayTimeSlots = array_filter($schedules, fn($s) => $s['day'] === $day && $s['time'] === $time);
                                            $slot = reset($dayTimeSlots);
                                            $bgColor = $slot ? ($activityColors[$slot['activity']] ?? 'bg-indigo-600') : 'bg-slate-50';
                                        ?>
                                            <td class="p-1 min-w-[140px] drop-slot border-l border-slate-100 h-28 align-top" data-day="<?php echo $day; ?>" data-time="<?php echo $time; ?>">
                                                <?php if ($slot): ?>
                                                    <div class="schedule-card <?php echo $bgColor; ?> p-3 rounded-xl text-white shadow-lg text-sm draggable hover:shadow-2xl transition-all h-full flex flex-col justify-between" draggable="true" data-schedule-id="<?php echo $slot['id']; ?>">
                                                        <div>
                                                            <div class="font-bold text-sm drop-shadow-md"><?php echo htmlspecialchars($slot['activity']); ?></div>
                                                            <?php if (!empty($slot['room'])): ?>
                                                                <div class="text-xs opacity-95 drop-shadow-sm"><?php echo htmlspecialchars($slot['room']); ?></div>
                                                            <?php endif; ?>
                                                            <?php if (!empty($slot['instructor'])): ?>
                                                                <div class="text-xs opacity-95 drop-shadow-sm"><?php echo htmlspecialchars($slot['instructor']); ?></div>
                                                            <?php endif; ?>
                                                        </div>
                                                        <div class="flex gap-1 mt-1">
                                                            <button type="button" onclick="openEditModal(<?php echo htmlspecialchars(json_encode($slot)); ?>)" class="flex-1 bg-white/20 hover:bg-white/30 px-2 py-1 rounded text-xs font-bold backdrop-blur-sm transition-all">Edit</button>
                                                            <button type="button" onclick="deleteSchedule(<?php echo $slot['id']; ?>)" class="flex-1 bg-white/20 hover:bg-white/30 px-2 py-1 rounded text-xs font-bold backdrop-blur-sm transition-all">Delete</button>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="h-full border-2 border-dashed border-slate-200 rounded-lg flex items-center justify-center group hover:border-indigo-300 hover:bg-indigo-50/20 transition-all cursor-pointer">
                                                        <span class="text-slate-300 text-xs">+</span>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-in">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Add New Class</h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="add_schedule" value="1">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Day</label>
                    <select name="day" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select a day</option>
                        <?php foreach ($days as $day): ?>
                            <option value="<?php echo $day; ?>"><?php echo $day; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Time</label>
                    <select name="time" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select a time</option>
                        <?php foreach ($times as $time): ?>
                            <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Activity</label>
                    <select name="activity" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select an activity</option>
                        <?php foreach ($activities as $activity): ?>
                            <option value="<?php echo $activity['name']; ?>"><?php echo htmlspecialchars($activity['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Room (Optional)</label>
                    <input type="text" name="room" placeholder="e.g., Room A" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Instructor (Optional)</label>
                    <input type="text" name="instructor" placeholder="e.g., John Doe" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
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
        <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 animate-in">
            <h2 class="text-2xl font-bold text-slate-900 mb-4">Edit Class</h2>
            <form method="POST" class="space-y-4">
                <input type="hidden" name="update_schedule" value="1">
                <input type="hidden" name="schedule_id" id="editScheduleId">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Day</label>
                    <select name="day" id="editDay" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select a day</option>
                        <?php foreach ($days as $day): ?>
                            <option value="<?php echo $day; ?>"><?php echo $day; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Time</label>
                    <select name="time" id="editTime" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select a time</option>
                        <?php foreach ($times as $time): ?>
                            <option value="<?php echo $time; ?>"><?php echo $time; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Activity</label>
                    <select name="activity" id="editActivity" required class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">Select an activity</option>
                        <?php foreach ($activities as $activity): ?>
                            <option value="<?php echo $activity['name']; ?>"><?php echo htmlspecialchars($activity['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Room (Optional)</label>
                    <input type="text" name="room" id="editRoom" placeholder="e.g., Room A" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Instructor (Optional)</label>
                    <input type="text" name="instructor" id="editInstructor" placeholder="e.g., John Doe" class="w-full px-4 py-2 border border-slate-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
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
        <input type="hidden" name="delete_schedule" value="1">
        <input type="hidden" name="schedule_id" id="deleteScheduleId">
    </form>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
        }
        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
        }
        function openEditModal(schedule) {
            document.getElementById('editScheduleId').value = schedule.id;
            document.getElementById('editDay').value = schedule.day;
            document.getElementById('editTime').value = schedule.time;
            document.getElementById('editActivity').value = schedule.activity;
            document.getElementById('editRoom').value = schedule.room || '';
            document.getElementById('editInstructor').value = schedule.instructor || '';
            document.getElementById('editModal').classList.remove('hidden');
        }
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
        function deleteSchedule(id) {
            if (confirm('Delete this class?')) {
                document.getElementById('deleteScheduleId').value = id;
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

        // Drag and drop
        let draggedElement = null;
        document.querySelectorAll('.draggable').forEach(item => {
            item.addEventListener('dragstart', (e) => {
                draggedElement = item;
                item.classList.add('dragging');
            });
            item.addEventListener('dragend', () => {
                item.classList.remove('dragging');
                document.querySelectorAll('.drop-slot').forEach(s => s.classList.remove('drag-over'));
            });
        });

        document.querySelectorAll('.drop-slot').forEach(slot => {
            slot.addEventListener('dragover', (e) => {
                e.preventDefault();
                if (draggedElement) slot.classList.add('drag-over');
            });
            slot.addEventListener('dragleave', () => {
                slot.classList.remove('drag-over');
            });
            slot.addEventListener('drop', (e) => {
                e.preventDefault();
                slot.classList.remove('drag-over');
                if (draggedElement && !slot.querySelector('.draggable')) {
                    const day = slot.dataset.day;
                    const time = slot.dataset.time;
                    const scheduleId = draggedElement.dataset.scheduleId;
                    updateScheduleSlot(scheduleId, day, time);
                }
            });
        });

        function updateScheduleSlot(scheduleId, day, time) {
            // Get the actual schedule data
            fetch(`index.php?page=schedule&get_schedule=${scheduleId}`)
                .then(r => r.json())
                .then(schedule => {
                    const formData = new FormData();
                    formData.append('update_schedule', '1');
                    formData.append('schedule_id', scheduleId);
                    formData.append('day', day);
                    formData.append('time', time);
                    formData.append('activity', schedule.activity);
                    formData.append('room', schedule.room || '');
                    formData.append('instructor', schedule.instructor || '');
                    
                    fetch('', { method: 'POST', body: formData }).then(() => {
                        window.location.reload();
                    });
                });
        }
    </script>
    <?php renderDropdownScript(); ?>
</body>
</html>