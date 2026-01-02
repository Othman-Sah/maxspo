# Settings System Implementation Guide

## Overview

The settings system is now fully functional and saves all changes to the database. Users can modify:
- **Profile Settings**: Name, email, city
- **General Settings**: Club name, slogan, language, timezone
- **Branding Settings**: Theme color (affects the entire app UI)
- **Payment Settings**: Currency and tax rate
- **Notifications & Security**: Placeholder sections (expandable)

## How It Works

### 1. Frontend (React)

**Location**: `uui/components/SettingsView.tsx`

- Displays form inputs for different settings sections
- Tracks changes in state (`hasChanges` flag)
- Shows "Save Changes" button when modifications are detected
- Displays success/error messages after saving
- Loads initial settings from API on component mount

**Key Features**:
```tsx
- useEffect hook loads settings on mount via API
- Each input has onChange handler that sets hasChanges=true
- handleSave() bundles data and sends to backend API
- Applies theme color immediately via global ThemeContext
```

### 2. Backend (PHP)

**API Endpoint**: `Backend/api/settings.php`

Handles all settings operations:
- `GET ?action=all` - Load all settings
- `GET ?action=general` - Load general settings
- `GET ?action=theme` - Load theme settings
- `POST action=save_general` - Save general settings
- `POST action=save_branding` - Save branding settings
- `POST action=save_profile` - Save profile settings
- `POST action=save_payments` - Save payment settings

**Controller**: `Backend/controllers/SettingsController.php`

Implements database operations:
- `getSetting($name)` - Fetch setting from DB
- `setSetting($name, $value)` - Save setting to DB (JSON stored)
- Specific getter/setter methods for each settings category

### 3. Database

**Table**: `settings` (already created in `setup.sql`)

```sql
CREATE TABLE `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**Stored Settings**:
- `general_settings` - Club name, slogan, language, timezone
- `theme_settings` - Theme color, logo
- `profile_info` - User profile data
- `payment_settings` - Currency, tax rate

### 4. Theme System (Global Color Changes)

**Location**: `uui/context/ThemeContext.tsx`

- Provides global theme context accessible anywhere in the app
- Loads theme on app startup
- Applies CSS variables for dynamic color changes
- When branding settings are saved, immediately updates the entire UI

**Usage in Components**:
```tsx
import { useTheme } from '../context/ThemeContext';

const MyComponent = () => {
  const { themeColor, setThemeColor } = useTheme();
  // Use themeColor or call setThemeColor(newColor)
};
```

## How to Test

### 1. Test Profile Settings
1. Go to Settings → Mon Profil
2. Change name, email, or city
3. Click "Enregistrer les modifications"
4. See success message and verify data persists on page reload

### 2. Test General Settings
1. Go to Settings → Général
2. Change club name, slogan, language, or timezone
3. Save and verify persistence

### 3. Test Branding Settings (Theme Color)
1. Go to Settings → Branding & Design
2. Click on a color (Rose, Emerald, Amber, Slate, or Indigo)
3. Click "Enregistrer les modifications"
4. Watch the entire app UI change colors in real-time!
5. Reload the page - the new color persists

### 4. Test Payment Settings
1. Go to Settings → Paiements & Taxes
2. Change currency or tax rate
3. Save and verify

## Key Features

✅ **Persistent Storage**: All settings saved to MySQL database
✅ **Real-time Theme Application**: Change colors and see immediate UI updates
✅ **Form Validation**: Frontend tracks changes before saving
✅ **Error Handling**: Try/catch blocks and user-friendly error messages
✅ **API-Driven**: All data flows through RESTful API
✅ **Modular Architecture**: Easy to add new settings sections
✅ **Responsive UI**: Settings form works on all screen sizes

## API Response Format

### Success Response
```json
{
  "success": true,
  "message": "Paramètres enregistrés avec succès !",
  "data": {
    "clubName": "NEEDSPORT Pro",
    "slogan": "La performance au quotidien",
    ...
  }
}
```

### Error Response
```json
{
  "error": "Failed to save setting: Database error message"
}
```

## File Locations

```
Backend/
  ├── api/
  │   └── settings.php (API endpoint)
  ├── controllers/
  │   └── SettingsController.php (Business logic)
  └── config/
      └── config.php (Database connection)

uui/
  ├── components/
  │   └── SettingsView.tsx (Settings UI)
  ├── context/
  │   └── ThemeContext.tsx (Global theme provider)
  └── App.tsx (Wrapped with ThemeProvider)
```

## Future Enhancements

- [ ] Logo upload functionality
- [ ] More theme customization options
- [ ] Export/import settings
- [ ] Audit trail of setting changes
- [ ] Role-based permission checking
- [ ] Multilingual support beyond French/English/Arabic
- [ ] Settings for email notifications
- [ ] 2FA security options
