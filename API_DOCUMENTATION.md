# Settings API Documentation

## Base URL
```
/Backend/api/settings.php
```

## Authentication
All endpoints require user to be logged in via `requireLogin()` check.

---

## GET Endpoints

### Get All Settings
**Endpoint**: `GET /Backend/api/settings.php?action=all`

**Response**:
```json
{
  "general": {
    "clubName": "NEEDSPORT Pro",
    "slogan": "La performance au quotidien",
    "language": "fr",
    "timezone": "(GMT+01:00) Casablanca"
  },
  "theme": {
    "themeColor": "indigo",
    "logo": null
  },
  "profile": {
    "initials": "AC",
    "name": "Admin Coach",
    "role": "Super Administrateur",
    "id": "ADMIN-01",
    "email": "super-admin@needsport.ma",
    "city": "Casablanca, Maroc"
  },
  "payments": {
    "currency": "DH",
    "taxRate": 20
  }
}
```

### Get General Settings
**Endpoint**: `GET /Backend/api/settings.php?action=general`

**Response**:
```json
{
  "clubName": "NEEDSPORT Pro",
  "slogan": "La performance au quotidien",
  "language": "fr",
  "timezone": "(GMT+01:00) Casablanca"
}
```

### Get Theme Settings
**Endpoint**: `GET /Backend/api/settings.php?action=theme`

**Response**:
```json
{
  "themeColor": "indigo",
  "logo": null
}
```

### Get Profile Info
**Endpoint**: `GET /Backend/api/settings.php?action=profile`

**Response**:
```json
{
  "initials": "AC",
  "name": "Admin Coach",
  "role": "Super Administrateur",
  "id": "ADMIN-01",
  "email": "super-admin@needsport.ma",
  "city": "Casablanca, Maroc"
}
```

---

## POST Endpoints

### Save General Settings
**Endpoint**: `POST /Backend/api/settings.php`

**Body**:
```json
{
  "action": "save_general",
  "clubName": "My Club",
  "slogan": "New slogan",
  "language": "en",
  "timezone": "(GMT+00:00) London"
}
```

**Response** (Success):
```json
{
  "success": true,
  "message": "Paramètres généraux enregistrés",
  "data": {
    "clubName": "My Club",
    "slogan": "New slogan",
    "language": "en",
    "timezone": "(GMT+00:00) London"
  }
}
```

---

### Save Branding Settings
**Endpoint**: `POST /Backend/api/settings.php`

**Body**:
```json
{
  "action": "save_branding",
  "themeColor": "rose",
  "logo": null
}
```

**Response** (Success):
```json
{
  "success": true,
  "message": "Paramètres de branding enregistrés",
  "data": {
    "themeColor": "rose",
    "logo": null
  }
}
```

**Available Colors**:
- `indigo` (default)
- `rose`
- `emerald`
- `amber`
- `slate`

---

### Save Profile Settings
**Endpoint**: `POST /Backend/api/settings.php`

**Body**:
```json
{
  "action": "save_profile",
  "name": "John Coach",
  "email": "john@needsport.ma",
  "city": "Rabat, Maroc"
}
```

**Response** (Success):
```json
{
  "success": true,
  "message": "Profil enregistré",
  "data": {
    "initials": "JC",
    "name": "John Coach",
    "role": "Super Administrateur",
    "id": "ADMIN-01",
    "email": "john@needsport.ma",
    "city": "Rabat, Maroc"
  }
}
```

---

### Save Payment Settings
**Endpoint**: `POST /Backend/api/settings.php`

**Body**:
```json
{
  "action": "save_payments",
  "currency": "EUR",
  "taxRate": 20
}
```

**Response** (Success):
```json
{
  "success": true,
  "message": "Paramètres de paiement enregistrés",
  "data": {
    "currency": "EUR",
    "taxRate": 20
  }
}
```

**Available Currencies**:
- `DH` (Dirham Marocain) - Default
- `EUR` (Euro)
- `USD` (US Dollar)

---

## Error Responses

### Invalid Action
**Status**: 400
```json
{
  "error": "Invalid action"
}
```

### Method Not Allowed
**Status**: 405
```json
{
  "error": "Method not allowed"
}
```

### Server Error
**Status**: 500
```json
{
  "error": "Failed to save setting: Database error message"
}
```

---

## Usage Examples

### JavaScript/React Example

```javascript
// Load all settings
async function loadSettings() {
  const response = await fetch('../Backend/api/settings.php?action=all');
  const data = await response.json();
  console.log(data);
}

// Save general settings
async function saveGeneralSettings(clubName, slogan) {
  const response = await fetch('../Backend/api/settings.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      action: 'save_general',
      clubName,
      slogan,
      language: 'fr',
      timezone: '(GMT+01:00) Casablanca'
    })
  });
  const result = await response.json();
  if (result.success) {
    console.log('Saved!');
  } else {
    console.error(result.error);
  }
}

// Change theme color
async function changeThemeColor(color) {
  const response = await fetch('../Backend/api/settings.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      action: 'save_branding',
      themeColor: color
    })
  });
  return await response.json();
}
```

### cURL Example

```bash
# Get all settings
curl http://localhost/lA/Backend/api/settings.php?action=all

# Get theme settings
curl http://localhost/lA/Backend/api/settings.php?action=theme

# Save general settings
curl -X POST http://localhost/lA/Backend/api/settings.php \
  -H "Content-Type: application/json" \
  -d '{
    "action": "save_general",
    "clubName": "My Club",
    "slogan": "New slogan",
    "language": "en",
    "timezone": "(GMT+00:00) London"
  }'

# Save branding (change theme color)
curl -X POST http://localhost/lA/Backend/api/settings.php \
  -H "Content-Type: application/json" \
  -d '{
    "action": "save_branding",
    "themeColor": "rose"
  }'
```

---

## Implementation Details

### Database Storage

Settings are stored as JSON in the `settings` table:

```sql
-- Get current theme color
SELECT value FROM settings WHERE name = 'theme_settings';
-- Returns: {"themeColor":"indigo","logo":null}

-- Get club name
SELECT value FROM settings WHERE name = 'general_settings';
-- Returns: {"clubName":"NEEDSPORT Pro","slogan":"..."}
```

### Session Requirements

The API endpoints check for active login:
```php
requireLogin();  // Redirects if not logged in
```

Add your user ID context as needed in the SettingsController.

### Error Handling

All endpoints wrap operations in try/catch:
```php
try {
    // Operation
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
```

---

## Testing the API

### Test Profile Save
```bash
curl -X POST http://localhost/lA/Backend/api/settings.php \
  -H "Content-Type: application/json" \
  -d '{
    "action": "save_profile",
    "name": "Test User",
    "email": "test@example.com",
    "city": "Test City"
  }'
```

### Verify Saved Data
```bash
curl http://localhost/lA/Backend/api/settings.php?action=profile
```

The response should show your updated profile information.

---

## Integration Points

### Frontend Integration (React)
- Location: `uui/components/SettingsView.tsx`
- Uses this API to load and save all settings
- Automatically applies theme changes via `ThemeContext`

### Backend Processing
- Location: `Backend/controllers/SettingsController.php`
- Handles database operations
- Validates data before saving

### Theme Application
- Location: `uui/context/ThemeContext.tsx`
- Provides global theme state
- Applies CSS variables for dynamic theming

---

## Notes

- All settings are stored as JSON strings in the database
- Theme changes are applied immediately to the UI
- All operations are database-persisted
- Settings are user-agnostic (global app settings)
- Future versions could add role-based access control
