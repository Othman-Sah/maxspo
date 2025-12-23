<?php
/**
 * Staff Controller
 * Handles staff operations
 */

class StaffController {
    private $db;
    private $mockData;

    public function __construct($database) {
        $this->db = $database;
        $this->mockData = require CONFIG_PATH . '/MockData.php';
    }

    /**
     * Get all staff members
     */
    public function getAll() {
        // Using mock data for now
        return $this->mockData['staff'];
    }

    /**
     * Get single staff member by ID
     */
    public function getById($id) {
        $staff = $this->mockData['staff'];
        foreach ($staff as $member) {
            if ($member['id'] === $id) {
                return $member;
            }
        }
        return null;
    }

    /**
     * Create new staff member
     */
    public function create($data) {
        // Validation would go here
        logActivity('STAFF_CREATED', $data['name']);
        return ['success' => true, 'message' => 'Employé ajouté avec succès'];
    }

    /**
     * Update staff member
     */
    public function update($id, $data) {
        logActivity('STAFF_UPDATED', 'ID: ' . $id);
        return ['success' => true, 'message' => 'Employé mis à jour'];
    }

    /**
     * Delete staff member
     */
    public function delete($id) {
        logActivity('STAFF_DELETED', 'ID: ' . $id);
        return ['success' => true, 'message' => 'Employé supprimé'];
    }
}
?>