<?php
require_once 'config/config.php';

$db = new Database();
$conn = $db->getConnection();

try {
    $stmt = $conn->query("SHOW TABLES");

    echo "Tables in database:<br>";
    while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
        echo $row[0] . "<br>";
    }
} catch(PDOException $e) {
    die("Error verifying database: " . $e->getMessage());
}

$conn = null;
?>