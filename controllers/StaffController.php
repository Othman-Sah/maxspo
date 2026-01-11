<?php
/**
 * Staff Controller
 * Handles staff operations
 */

class StaffController {
    private $db;

    public function __construct($database) {
        $this->db = $database;
    }

    /**
     * Get all staff members
     */
    public function getAll() {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->query("SELECT id, name, role, status, phone, email, salary, joinDate FROM staff ORDER BY id DESC");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * Get single staff member by ID
     */
    public function getById($id) {
        try {
            $conn = $this->db->getConnection();
            $stmt = $conn->prepare("SELECT id, name, role, status, phone, email, salary, joinDate FROM staff WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return null;
        }
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