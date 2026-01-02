<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../config/config.php';
require_once '../controllers/StaffController.php';

$db = new Database();
$staffController = new StaffController($db);

$staff = $staffController->getAll();

echo json_encode($staff);
