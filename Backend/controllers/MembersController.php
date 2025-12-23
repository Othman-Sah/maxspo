<?php
/**
 * Members Controller
 * Handles member operations (CRUD)
 */

class MembersController {
    private $db;
    private $mockData;

    public function __construct($database) {
        $this->db = $database;
        $this->mockData = require CONFIG_PATH . '/MockData.php';
    }

    /**
     * Get all members
     */
    public function getAll($filters = []) {
        // Using mock data for now - can be replaced with DB query
        $members = $this->mockData['members'];

        // Apply filters
        if (!empty($filters['sport'])) {
            $members = array_filter($members, fn($m) => $m['sport'] === $filters['sport']);
        }

        if (!empty($filters['status'])) {
            $members = array_filter($members, fn($m) => getMemberStatus($m['expiryDate']) === $filters['status']);
        }

        if (!empty($filters['search'])) {
            $search = strtolower($filters['search']);
            $members = array_filter($members, fn($m) => 
                stripos($m['firstName'] . ' ' . $m['lastName'], $search) !== false ||
                stripos($m['email'], $search) !== false ||
                stripos($m['phone'], $search) !== false
            );
        }

        return $members;
    }

    /**
     * Get single member by ID
     */
    public function getById($id) {
        $members = $this->mockData['members'];
        foreach ($members as $member) {
            if ($member['id'] === $id) {
                return $member;
            }
        }
        return null;
    }

    /**
     * Get expiring members
     */
    public function getExpiringMembers() {
        $members = $this->mockData['members'];
        return array_filter($members, fn($m) => 
            getMemberStatus($m['expiryDate']) === 'expirant' || 
            getMemberStatus($m['expiryDate']) === 'expire'
        );
    }

    /**
     * Create new member
     */
    public function create($data) {
        $validator = new Validator();
        
        if (!$validator->validateRequired('firstName', $data['firstName'])) return ['success' => false, 'errors' => $validator->getErrors()];
        if (!$validator->validateRequired('lastName', $data['lastName'])) return ['success' => false, 'errors' => $validator->getErrors()];
        if (!$validator->validateEmail($data['email'])) return ['success' => false, 'errors' => $validator->getErrors()];
        if (!$validator->validatePhone($data['phone'])) return ['success' => false, 'errors' => $validator->getErrors()];
        if (!$validator->validateAge($data['age'])) return ['success' => false, 'errors' => $validator->getErrors()];

        // Here you would insert into database
        // For now, we're returning success with mock ID
        
        logActivity('MEMBER_CREATED', $data['firstName'] . ' ' . $data['lastName']);
        
        return [
            'success' => true,
            'message' => 'Membre ajouté avec succès',
            'id' => uniqid('M')
        ];
    }

    /**
     * Update member
     */
    public function update($id, $data) {
        // Validation
        if (!empty($data['email']) && !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            return ['success' => false, 'error' => 'Email invalide'];
        }

        // Update in database
        logActivity('MEMBER_UPDATED', 'ID: ' . $id);
        
        return ['success' => true, 'message' => 'Membre mis à jour'];
    }

    /**
     * Delete member
     */
    public function delete($id) {
        logActivity('MEMBER_DELETED', 'ID: ' . $id);
        return ['success' => true, 'message' => 'Membre supprimé'];
    }

    /**
     * Renew membership
     */
    public function renew($id, $duration) {
        $member = $this->getById($id);
        if (!$member) {
            return ['success' => false, 'error' => 'Membre non trouvé'];
        }

        $activity = array_values(array_filter($this->mockData['activities'], 
            fn($a) => $a['name'] === $member['sport']
        ))[0] ?? null;

        if (!$activity) {
            return ['success' => false, 'error' => 'Activité non trouvée'];
        }

        $totalPrice = $activity['monthlyPrice'] * $duration;
        
        logActivity('MEMBERSHIP_RENEWED', $member['firstName'] . ' ' . $member['lastName'] . ' - ' . $duration . ' mois');
        
        return [
            'success' => true,
            'message' => 'Abonnement renouvelé',
            'totalPrice' => $totalPrice,
            'newExpiryDate' => date('Y-m-d', strtotime('+' . $duration . ' months'))
        ];
    }
}
?>
