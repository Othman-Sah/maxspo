# NEEDSPORT Pro - PHP Backend

A professional sports club management system built with **PHP, HTML, Tailwind CSS, and JavaScript**.

## 📋 Project Structure

```
Backend/
├── config/
│   ├── config.php          # Main configuration & database setup
│   ├── Database.php        # MySQLi database connection class
│   ├── Models.php          # Data model classes
│   └── MockData.php        # Sample/mock data
├── controllers/
│   ├── MembersController.php    # Member management logic
│   ├── DashboardController.php  # Dashboard data & statistics
│   └── (more controllers...)
├── api/
│   ├── members.php         # REST API endpoints for members
│   ├── dashboard.php       # REST API endpoints for dashboard
│   └── (more API routes...)
├── components/
│   ├── Components.php      # Reusable UI component functions
│   └── Layout.php          # Layout & header/sidebar components
├── helpers/
│   ├── functions.php       # Global helper functions
│   └── Validator.php       # Input validation class
├── views/
│   ├── login.php           # Login page
│   ├── dashboard.php       # Main dashboard
│   ├── members.php         # Members management
│   ├── sports.php          # Activities/sports management
│   ├── add-member.php      # Add member form
│   └── (more views...)
├── public/
│   └── uploads/            # User file uploads
└── index.php               # Main router/entry point
```

## 🚀 Getting Started

### Prerequisites
- PHP 7.4+ (with MySQLi)
- MySQL 5.7+
- Apache/Nginx with `.htaccess` support

### Installation

1. **Copy to MAMP Directory**
```bash
cp -r Backend/ /Applications/MAMP/htdocs/lA/Backend
```

2. **Create Database**
```sql
CREATE DATABASE needsport_pro;
USE needsport_pro;

-- Members table
CREATE TABLE members (
    id INT PRIMARY KEY AUTO_INCREMENT,
    firstName VARCHAR(100),
    lastName VARCHAR(100),
    email VARCHAR(100) UNIQUE,
    phone VARCHAR(20),
    age INT,
    sport VARCHAR(100),
    status ENUM('actif', 'expirant', 'expire'),
    expiryDate DATE,
    joinDate DATE,
    isLoyal BOOLEAN DEFAULT FALSE,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Activities table
CREATE TABLE activities (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    description TEXT,
    monthlyPrice INT,
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Payments table
CREATE TABLE payments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    memberId INT,
    amount INT,
    date DATE,
    method ENUM('especes', 'carte', 'virement', 'cheque'),
    status ENUM('valide', 'en_attente', 'annule'),
    createdAt TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (memberId) REFERENCES members(id)
);

-- Add more tables as needed
```

3. **Update Configuration**
Edit `config/config.php` with your database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', 'password');
define('DB_NAME', 'needsport_pro');
```

4. **Access Application**
```
http://localhost/lA/Backend/index.php
```

### Default Credentials
- **Email:** `admin@needsport.ma`
- **Password:** `password`

## 📚 Features

### ✅ Implemented
- ✓ Login/Authentication system
- ✓ Dashboard with statistics
- ✓ Member management (view, filter)
- ✓ Activities/Sports management
- ✓ Mock data system
- ✓ Component-based UI system
- ✓ Form validation
- ✓ CSRF protection
- ✓ API endpoints structure

### 🔄 In Progress / To Do
- [ ] Full database integration for all tables
- [ ] Member CRUD operations
- [ ] Payment tracking
- [ ] Financial reports
- [ ] Staff management
- [ ] POS system
- [ ] Schedule planning
- [ ] Notifications system
- [ ] Settings/configuration
- [ ] Export to PDF/CSV
- [ ] User role management
- [ ] Activity logging

## 🔧 API Endpoints

### Members API
- `GET /api/members.php?action=list` - Get all members
- `GET /api/members.php?action=get&id=1` - Get single member
- `POST /api/members.php?action=create` - Create member
- `POST /api/members.php?action=update&id=1` - Update member
- `POST /api/members.php?action=delete&id=1` - Delete member
- `POST /api/members.php?action=renew&id=1&duration=3` - Renew membership

### Dashboard API
- `GET /api/dashboard.php?action=stats` - Dashboard statistics
- `GET /api/dashboard.php?action=revenue` - Revenue data
- `GET /api/dashboard.php?action=sports` - Sport statistics
- `GET /api/dashboard.php?action=notifications` - Get notifications

## 📝 Code Examples

### Using Helper Functions
```php
<?php
require_once 'config/config.php';

// Get and sanitize input
$email = getParam('email'); // From $_GET
$name = postParam('name');  // From $_POST

// Format values
echo formatCurrency(1500);  // 1 500 DH
echo formatDate('2024-06-15'); // 15/06/2024

// Validation
$validator = new Validator();
if (!$validator->validateEmail($email)) {
    echo $validator->getErrors()['email'];
}

// Redirect
redirect('members', ['status' => 'actif']);
?>
```

### Creating a Controller
```php
<?php
class PaymentsController {
    private $db;
    
    public function __construct($database) {
        $this->db = $database;
    }
    
    public function getPayments($filters = []) {
        // Database query logic
    }
}
?>
```

### Using Components
```php
<?php
require_once 'components/Components.php';

// Render stat card
renderStatCard('Total Revenue', 125400, 12.5, 'dollar', 'emerald', 'DH ');

// Render status badge
renderStatusBadge('actif');

// Render button
renderButton('Save', 'primary', 'submitForm()', '💾');
?>
```

## 🎨 UI Components

### Available Components
- `renderStatCard()` - Statistics card
- `renderMemberRow()` - Table row for member
- `renderActivityCard()` - Activity card
- `renderStatusBadge()` - Status indicator
- `renderAlert()` - Alert message
- `renderButton()` - Button
- `renderHeader()` - Top navigation
- `renderSidebar()` - Left sidebar

## 🔐 Security Features

- ✓ SQL injection prevention (MySQLi prepared statements)
- ✓ XSS protection (htmlspecialchars)
- ✓ CSRF tokens
- ✓ Session-based authentication
- ✓ Input validation & sanitization
- ✓ Error logging

## 📱 Responsive Design

All views are built with Tailwind CSS and are fully responsive:
- Mobile (< 768px)
- Tablet (768px - 1024px)
- Desktop (> 1024px)

## 🌐 Browser Support

- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+

## 📧 Contact & Support

For issues or questions about the PHP backend, contact the development team.

---

**Version:** 2.4.0  
**Last Updated:** December 2024  
**Language:** PHP 7.4+
