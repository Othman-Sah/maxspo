<?php
/**
 * POS Controller
 * Handles POS products and sales
 */

class POSController {
    private $db;
    private $mockData;

    public function __construct($database) {
        $this->db = $database;
        $this->mockData = require CONFIG_PATH . '/MockData.php';
    }

    /**
     * Get all POS items
     */
    public function getItems() {
        // In a real app, this would be a DB query.
        // The mock data is in a different format in the react app, so we'll use that one.
        return [
            ['id' => 'P1', 'name' => 'Whey Protein (Single)', 'category' => 'complement', 'price' => 25, 'stock' => 45],
            ['id' => 'P2', 'name' => 'Barre Énergétique', 'category' => 'snack', 'price' => 15, 'stock' => 120],
            ['id' => 'P3', 'name' => 'Eau Minérale 50cl', 'category' => 'boisson', 'price' => 5, 'stock' => 200],
            ['id' => 'P4', 'name' => 'BCAA Drink', 'category' => 'complement', 'price' => 20, 'stock' => 35],
            ['id' => 'P5', 'name' => 'Pre-Workout Shot', 'category' => 'complement', 'price' => 30, 'stock' => 25],
            ['id' => 'P6', 'name' => 'Isotonic Orange', 'category' => 'boisson', 'price' => 15, 'stock' => 60],
        ];
    }
}
?>