# NEEDSPORT Pro - Migration Guide

## Converting from React to PHP

This document explains how the React TypeScript application was converted to PHP.

### React Components → PHP Functions

**React (TypeScript):**
```typescript
interface Props {
  title: string;
  value: number;
  trend: number;
}

const StatCard: React.FC<Props> = ({ title, value, trend }) => {
  return <div className="...">...</div>;
};
```

**PHP Equivalent:**
```php
function renderStatCard($title, $value, $trend, $icon, $color, $prefix = '') {
    // Component logic in PHP
    // Returns HTML echo statements
}
```

### State Management

**React (hooks):**
```typescript
const [members, setMembers] = useState([]);
const [filter, setFilter] = useState('all');
```

**PHP (sessions & parameters):**
```php
$_SESSION['members'] = $members;
$filter = getParam('filter', 'all');
```

### API Calls

**React (fetch):**
```typescript
const response = await fetch('/api/members');
const data = await response.json();
```

**PHP (REST endpoints):**
```php
// Client-side
fetch('api/members.php?action=list')
    .then(r => r.json())
    .then(data => console.log(data));

// Server-side
header('Content-Type: application/json');
jsonResponse(['data' => $members]);
```

### Routing

**React Router:**
```typescript
<Route path="/members" element={<MembersView />} />
```

**PHP Router:**
```php
$page = getParam('page', 'dashboard');
include VIEWS_PATH . '/' . $page . '.php';
```

## Key Differences

| React | PHP |
|-------|-----|
| TypeScript types | PHP class models |
| Hooks state | $_SESSION & $_GET/$_POST |
| React Router | URL parameters |
| Props drilling | Function parameters |
| Promises/async-await | Synchronous execution |
| JSX | PHP template files |
| Component library | PHP functions |

## Database Integration

### Current State
- Using **MockData.php** for demo data
- Ready for MySQLi database queries
- Structure supports easy migration

### To Implement Database:

1. **Update Controllers** - Replace mock data with DB queries
```php
// Current (mock):
$members = $this->mockData['members'];

// Updated (database):
$stmt = $this->db->prepare("SELECT * FROM members WHERE sport = ?");
$stmt->bind_param("s", $sport);
$stmt->execute();
$result = $stmt->get_result();
$members = $result->fetch_all(MYSQLI_ASSOC);
```

2. **Update API Endpoints** - Return database results instead of mock data

3. **Add Database Operations** - Create, update, delete records

## Advantages of PHP Version

✅ **Server-side rendering** - No JavaScript required for core functionality
✅ **Session management** - Built-in authentication
✅ **Database integration** - Direct MySQLi connections
✅ **No build step** - Edit and refresh
✅ **Traditional architecture** - Familiar to PHP developers
✅ **Security** - Server-side validation & protection
✅ **SEO friendly** - HTML generated server-side

## Next Steps

1. Implement database tables (see `Backend/README.md`)
2. Update controllers with real database queries
3. Expand remaining views (schedule, payments, etc.)
4. Add form submission handlers
5. Integrate file upload system
6. Implement user roles & permissions
7. Add email notifications
8. Create admin settings panel

---

**Conversion completed:** December 2024
