# NEEDSPORT Pro PHP Backend - Quick Start

## 🚀 Quick Setup (5 minutes)

### 1. Verify MAMP is Running
```bash
# MAMP should be running on http://localhost:8888
# PHP should be accessible from command line
php -v  # Should show PHP 7.4+
```

### 2. Create Database
```bash
# Option A: Using PHPMyAdmin (easier)
# 1. Open http://localhost:8888/phpMyAdmin
# 2. Click "New" 
# 3. Create database: needsport_pro
# 4. Charset: utf8mb4

# Option B: Using MySQL CLI
mysql -u root
CREATE DATABASE needsport_pro CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
EXIT;
```

### 3. Test Application
```
Open: http://localhost:8888/lA/Backend/index.php

Login:
- Email: admin@needsport.ma
- Password: password
```

## 📁 File Structure Overview

```
Backend/
├── index.php                    ← START HERE (Router)
├── config/
│   ├── config.php              ← Database & app settings
│   ├── Database.php            ← MySQLi connection
│   ├── Models.php              ← Data classes
│   └── MockData.php            ← Sample data
├── controllers/                 ← Business logic
│   ├── DashboardController.php
│   └── MembersController.php
├── views/                       ← Page templates
│   ├── login.php
│   ├── dashboard.php
│   ├── members.php
│   ├── sports.php
│   └── add-member.php
├── components/                  ← Reusable UI parts
│   ├── Components.php
│   └── Layout.php
├── helpers/                     ← Utility functions
│   ├── functions.php
│   └── Validator.php
└── api/                         ← REST endpoints
    ├── members.php
    └── dashboard.php
```

## 🔑 Key Entry Points

1. **Login Page:** `index.php?page=login`
2. **Dashboard:** `index.php?page=dashboard`
3. **Members:** `index.php?page=members`
4. **Add Member:** `index.php?page=add-member`
5. **Activities:** `index.php?page=sports`

## 💻 Common Tasks

### View All Members
```php
<?php
require_once 'config/config.php';
require_once 'controllers/MembersController.php';

$controller = new MembersController($db);
$members = $controller->getAll();
print_r($members);
?>
```

### Add New Member
```php
<?php
$data = [
    'firstName' => 'John',
    'lastName' => 'Doe',
    'email' => 'john@example.com',
    'phone' => '06 12 34 56 78',
    'age' => 28,
    'sport' => 'Fitness / Cardio'
];

$result = $controller->create($data);
echo json_encode($result);
?>
```

### Make API Request
```javascript
// Get all members
fetch('Backend/api/members.php?action=list')
    .then(r => r.json())
    .then(data => console.log(data));

// Create member
const formData = new FormData();
formData.append('firstName', 'John');
formData.append('lastName', 'Doe');

fetch('Backend/api/members.php?action=create', {
    method: 'POST',
    body: formData
})
.then(r => r.json())
.then(data => console.log(data));
```

## 🐛 Troubleshooting

### "Class 'Database' not found"
- Make sure `config/config.php` is included at the top of the file
- Check that `Database.php` path is correct in `config.php`

### "Cannot find database"
- Create the database in MySQL
- Update `DB_NAME` in `config/config.php`
- Make sure MySQL is running

### Login not working
- Default credentials: admin@needsport.ma / password
- Check that sessions are enabled in PHP
- Clear browser cookies/cache

### CSS not loading
- Tailwind CSS is loaded via CDN
- Make sure you have internet connection
- Check browser console for CDN errors

## 📚 Learning Resources

- **PHP Manual:** https://www.php.net/manual/
- **MySQLi Guide:** https://www.php.net/manual/en/book.mysqli.php
- **Tailwind CSS:** https://tailwindcss.com/docs
- **HTML/CSS:** https://developer.mozilla.org/

## 🎯 Development Tips

### Enable Debug Mode
Edit `config/config.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);  // Set to 0 in production
```

### Test Database Connection
```php
<?php
require_once 'config/config.php';
try {
    $db->connect();
    echo "✓ Database connected";
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage();
}
?>
```

### View SQL Errors
```php
global $db;
$result = $db->query("SELECT * FROM members");
if (!$result) {
    echo "SQL Error: " . $db->getConnection()->error;
}
```

## ✅ Checklist

- [ ] MAMP is running
- [ ] Database created (needsport_pro)
- [ ] Can access http://localhost:8888/lA/Backend/
- [ ] Can login with admin credentials
- [ ] Can view dashboard
- [ ] Can view members list
- [ ] Can add new member

---

**Need help?** Check README.md or MIGRATION.md for detailed documentation.
