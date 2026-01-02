<?php
require_once '../config/config.php';

echo "<h1>Database Seeder</h1>";

try {
    $conn = $db->getConnection();
    
    // 1. Create Tables
    // Drop existing tables to ensure schema is fresh
    $conn->exec("DROP TABLE IF EXISTS notifications");
    $conn->exec("DROP TABLE IF EXISTS expenses");
    $conn->exec("DROP TABLE IF EXISTS payments");
    $conn->exec("DROP TABLE IF EXISTS staff");
    $conn->exec("DROP TABLE IF EXISTS activities");
    $conn->exec("DROP TABLE IF EXISTS members");

    $queries = [
        "CREATE TABLE IF NOT EXISTS members (
            id INT AUTO_INCREMENT PRIMARY KEY,
            firstName VARCHAR(100),
            lastName VARCHAR(100),
            email VARCHAR(100),
            phone VARCHAR(20),
            age INT,
            sport VARCHAR(50),
            status VARCHAR(20),
            expiryDate DATE,
            joinDate DATE,
            isLoyal TINYINT(1) DEFAULT 0
        )",
        "CREATE TABLE IF NOT EXISTS activities (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50),
            description TEXT,
            monthlyPrice DECIMAL(10,2),
            color VARCHAR(50),
            icon VARCHAR(50)
        )",
        "CREATE TABLE IF NOT EXISTS staff (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100),
            role VARCHAR(50),
            status VARCHAR(20),
            phone VARCHAR(20),
            email VARCHAR(100),
            salary DECIMAL(10,2),
            joinDate DATE
        )",
        "CREATE TABLE IF NOT EXISTS payments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            member_id INT,
            sport VARCHAR(50),
            amount DECIMAL(10,2),
            date DATE,
            method VARCHAR(20),
            status VARCHAR(20)
        )",
        "CREATE TABLE IF NOT EXISTS expenses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category VARCHAR(50),
            description TEXT,
            amount DECIMAL(10,2),
            date DATE,
            status VARCHAR(20)
        )",
        "CREATE TABLE IF NOT EXISTS notifications (
            id INT AUTO_INCREMENT PRIMARY KEY,
            type VARCHAR(20),
            title VARCHAR(100),
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            is_read TINYINT(1) DEFAULT 0,
            priority VARCHAR(10),
            meta TEXT
        )"
    ];

    foreach ($queries as $sql) {
        $conn->exec($sql);
    }
    echo "<p>Tables created successfully.</p>";

    // 2. Insert Data
    
    // Activities
    $conn->exec("TRUNCATE TABLE activities");
    $sql = "INSERT INTO activities (name, description, monthlyPrice, color, icon) VALUES 
    ('Fitness / Cardio', 'Accès illimité au plateau de musculation.', 250, 'from-indigo-500 to-blue-600', 'Dumbbell'),
    ('Boxe Anglaise', 'Entraînement technique et cardio.', 350, 'from-rose-500 to-red-600', 'Target'),
    ('Yoga & Pilates', 'Souplesse et bien-être.', 300, 'from-emerald-500 to-teal-600', 'Flower2'),
    ('CrossFit', 'WOD quotidiens intenses.', 400, 'from-amber-500 to-orange-600', 'Flame')";
    $conn->exec($sql);
    echo "<p>Activities seeded.</p>";

    // Members
    $conn->exec("TRUNCATE TABLE members");
    $sql = "INSERT INTO members (firstName, lastName, email, phone, age, sport, status, expiryDate, joinDate, isLoyal) VALUES 
    ('Yassine', 'Benali', 'yassine@example.com', '06 12 34 56 78', 28, 'Fitness / Cardio', 'actif', '2024-06-25', '2023-01-10', 1),
    ('Sarah', 'Mansouri', 'sarah@example.com', '06 23 45 67 89', 24, 'Boxe Anglaise', 'expirant', '2024-06-12', '2024-03-05', 0),
    ('Mehdi', 'Amrani', 'mehdi@example.com', '06 34 56 78 90', 32, 'Yoga & Pilates', 'actif', '2024-06-18', '2022-11-20', 1),
    ('Amine', 'Kabbaj', 'amine@example.com', '06 45 67 89 01', 30, 'CrossFit', 'expire', '2024-05-30', '2023-05-15', 1),
    ('Fatima', 'Zahra', 'fatima@example.com', '06 56 78 90 12', 26, 'Fitness / Cardio', 'actif', '2024-09-10', '2024-02-12', 0)";
    $conn->exec($sql);
    echo "<p>Members seeded.</p>";

    // Staff
    $conn->exec("TRUNCATE TABLE staff");
    $sql = "INSERT INTO staff (name, role, status, phone, email, salary, joinDate) VALUES 
    ('Karim Idrissi', 'Coach Senior', 'present', '06 11 22 33 44', 'karim@needsport.ma', 6500, '2022-03-01'),
    ('Laila Benani', 'Coach Yoga', 'present', '06 22 33 44 55', 'laila@needsport.ma', 5500, '2023-01-15'),
    ('Omar Tazi', 'Réceptionniste', 'en_pause', '06 33 44 55 66', 'omar@needsport.ma', 4000, '2023-05-10')";
    $conn->exec($sql);
    echo "<p>Staff seeded.</p>";

    // Payments
    $conn->exec("TRUNCATE TABLE payments");
    $sql = "INSERT INTO payments (member_id, sport, amount, date, method, status) VALUES 
    (1, 'Fitness / Cardio', 250, '2024-06-05', 'especes', 'valide'),
    (2, 'Boxe Anglaise', 350, '2024-06-04', 'carte', 'valide'),
    (3, 'Yoga & Pilates', 300, '2024-06-03', 'virement', 'en_attente')";
    $conn->exec($sql);
    echo "<p>Payments seeded.</p>";

    // Expenses
    $conn->exec("TRUNCATE TABLE expenses");
    $sql = "INSERT INTO expenses (category, description, amount, date, status) VALUES 
    ('Loyer', 'Loyer du local Juin', 25000, '2024-06-01', 'paye'),
    ('Électricité', 'Facture REDAL', 4500, '2024-06-10', 'paye'),
    ('Maintenance', 'Entretien Sauna', 1200, '2024-06-12', 'paye')";
    $conn->exec($sql);
    echo "<p>Expenses seeded.</p>";

    // Notifications
    $conn->exec("TRUNCATE TABLE notifications");
    $sql = "INSERT INTO notifications (type, title, description, created_at, is_read, priority) VALUES 
    ('payment', 'Paiement en retard', 'Amine Kabbaj n\'a pas réglé son abonnement.', NOW(), 0, 'high'),
    ('system', 'Maintenance Sauna', 'Vérification nécessaire.', DATE_SUB(NOW(), INTERVAL 2 HOUR), 1, 'low')";
    $conn->exec($sql);
    echo "<p>Notifications seeded.</p>";

    echo "<h3>Database setup complete! You can now delete this file.</h3>";

} catch (PDOException $e) {
    die("Seeding failed: " . $e->getMessage());
}
?>