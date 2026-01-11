<?php
require_once 'config/config.php';

$sql = file_get_contents('setup.sql');

$db = new Database();
$conn = $db->connect();

$queries = explode(';', $sql);

foreach ($queries as $query) {
    if (trim($query) != '') {
        if ($conn->query($query)) {
            echo "Query executed successfully: " . $query . "<br>";
        } else {
            echo "Error executing query: " . $conn->error . "<br>";
        }
    }
}

$conn->close();
?>