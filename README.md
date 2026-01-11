# MaxSporti - PHP Backend

A professional sports club management system built with **PHP, HTML, Tailwind CSS, and JavaScript**.

##  Project Structure

The project follows a custom MVC-like pattern.

```
/
├── api/                  # REST API endpoints
│   ├── dashboard.php
│   ├── journal.php
│   ├── members.php
│   └── ...
├── components/           # Reusable UI component functions
│   ├── Components.php
│   └── Layout.php
├── config/               # Application configuration
│   ├── config.php        # Main configuration & database setup
│   ├── Database.php      # MySQLi database connection class
│   └── Models.php        # Data model classes
├── controllers/          # Business logic
│   ├── DashboardController.php
│   ├── MembersController.php
│   └── ...
├── helpers/              # Global helper functions
│   ├── functions.php
│   └── Validator.php
├── logs/                 # Error logs
│   └── error.log
├── public/               # (Should be document root)
├── uploads/              # User file uploads
│   └── staff/
├── views/                # PHP-based templates
│   ├── dashboard.php
│   ├── members.php
│   └── ...
├── run_setup.php         # Automated database setup script
├── setup.sql             # SQL schema for the database
├── index.php             # Main router/entry point
└── README.md             # This file
```

##  Getting Started

### Prerequisites
- A local web server environment (MAMP, WAMP, XAMPP, or equivalent).
- PHP 7.4+
- MySQL 5.7+

### Installation

1.  **Place Files**
    - Clone this repository or copy all the files into a directory within your web server's document root (e.g., `C:\MAMP\htdocs\la`).

2.  **Create Database**
    - Using a MySQL client (like phpMyAdmin), create a new, empty database. For example, `needsport_pro`.

3.  **Update Configuration**
    - Open `config/config.php` and update the database constants with your credentials:
    ```php
    // Database Configuration
    define('DB_HOST', '127.0.0.1'); // Or 'localhost'
    define('DB_USER', 'your_db_user');
    define('DB_PASS', 'your_db_password');
    define('DB_NAME', 'needsport_pro'); // The database you created in step 2
    define('DB_PORT', 3306);
    ```

4.  **Run Setup Script**
    - In your web browser, navigate to the `setup_db.php` script to automatically create all the necessary database tables.
    - **URL:** `http://localhost/maxsporti/setup_db.php` (adjust the path if you named the folder differently).
    - You should see a list of "Query executed successfully" messages.
    - **URL:** `http://localhost/maxsporti/install/seed.php`
    - By entering that URL you are seedign database by mock data to test the site.

5.  **Secure Your Setup**
    - After setup is complete, it is highly recommended to **delete or rename `setup_db.php` and `setup.sql`** to prevent accidental or malicious re-execution.

6.  **Access Application**
    - You can now access the main application.
    - **URL:** `http://localhost/maxsporti`
    - **Default Login:**
        - **Email:** `admin@needsport.ma`
        - **Password:** `password`

##  Features

###  Implemented
- ✓ Login/Authentication system
- ✓ Dashboard with statistics
- ✓ Member management (view, filter)
- ✓ Activities/Sports management
- ✓ Component-based UI system
- ✓ Form validation
- ✓ CSRF protection
- ✓ Automated database installer
- ✓ Full database integration for all tables
- ✓ Financial reports
- ✓ Staff management
- ✓ POS system
- ✓ Schedule planning

###  In Progress / To Do
- [ ] Member CRUD operations
- [ ] Payment tracking
- [ ] Notifications system
- [ ] Settings/configuration
- [ ] Export to PDF/CSV
- [ ] User role management
- [ ] Activity logging

##  API Endpoints

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

##  Security Features

- ✓ SQL injection prevention (MySQLi prepared statements)
- ✓ XSS protection (htmlspecialchars)
- ✓ CSRF tokens
- ✓ Session-based authentication
- ✓ Input validation & sanitization
- ✓ Error logging

---
**Version:** 2.4.0