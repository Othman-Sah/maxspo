<?php
/**
 * Notifications Controller
 * Handles notification data
 */

class NotificationsController {
    private $db;
    private $mockData;

    public function __construct($database) {
        $this->db = $database;
        $this->mockData = require CONFIG_PATH . '/MockData.php';
    }

    /**
     * Get all notifications with optional filtering
     */
    public function getNotifications($filter = 'all') {
        $notifications = $this->mockData['notifications'] ?? [];

        if ($filter !== 'all') {
            $notifications = array_filter($notifications, fn($n) => $n['type'] === $filter);
        }

        return $notifications;
    }
}
?>