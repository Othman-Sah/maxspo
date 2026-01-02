<?php
require_once 'config/config.php';

$db = new Database();
$conn = $db->connect();

$result = $conn->query("SHOW TABLES");

while ($row = $result->fetch_array()) {
    echo $row[0] . "<br>";
}

$conn->close();
?>