<?php
/**
 * Notifications Controller
 * Handles notification data
 */

class NotificationsController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    /**
     * Get all notifications with optional filtering
     */
    public function getNotifications($filter = 'all') {
        try {
            $conn = $this->db->getConnection();
            $sql = "SELECT id, type, title, description, created_at as time, is_read as isRead, priority FROM notifications WHERE 1=1";
            
            if ($filter !== 'all') {
                $sql .= " AND type = ?";
            }
            
            $sql .= " ORDER BY created_at DESC";
            $stmt = $conn->prepare($sql);
            
            if ($filter !== 'all') {
                $stmt->execute([$filter]);
            } else {
                $stmt->execute();
            }
            
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($notifications as &$n) {
                $n['isRead'] = (bool)$n['isRead'];
            }
            
            return $notifications;
        } catch (PDOException $e) {
            return [];
        }
    }
}
?>