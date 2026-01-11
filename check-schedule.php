<?php
require_once 'config/config.php';

global $db;
$conn = $db->getConnection();

// Check if schedule table exists
try {
    $stmt = $conn->query("DESCRIBE schedule");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Schedule table structure:\n";
    foreach ($columns as $col) {
        echo $col['Field'] . " (" . $col['Type'] . ")\n";
    }
    
    // Get existing schedule data
    $stmt = $conn->query("SELECT * FROM schedule LIMIT 10");
    $schedules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "\n\nExisting schedules:\n";
    echo json_encode($schedules, JSON_PRETTY_PRINT);
} catch (Exception $e) {
    echo "Schedule table does not exist\n";
    echo "Error: " . $e->getMessage();
}
?>
