<?php
require_once 'config/config.php';

$sql = file_get_contents('setup.sql');

$db = new Database();
$conn = $db->getConnection();

$queries = explode(';', $sql);

foreach ($queries as $query) {
    if (trim($query) != '') {
        try {
            $conn->exec($query);
            echo "Query executed successfully: " . $query . "<br>";
        } catch(PDOException $e) {
            echo "Error executing query: " . $e->getMessage() . "<br>";
        }
    }
}

$conn = null;
?>