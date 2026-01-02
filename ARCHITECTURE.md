# Settings System Architecture Diagram

## System Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                      NEEDSPORT Pro Application                   │
├─────────────────────────────────────────────────────────────────┤
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │             React Frontend (uui/)                        │   │
│  ├──────────────────────────────────────────────────────────┤   │
│  │                                                          │   │
│  │  ┌──────────────────────────────────────────────────┐  │   │
│  │  │ App.tsx (Root Component)                         │  │   │
│  │  │ ├─ Wrapped with <ThemeProvider>                 │  │   │
│  │  │ └─ Provides global theme context                │  │   │
│  │  └──────────────────────────────────────────────────┘  │   │
│  │                      ↓                                  │   │
│  │  ┌──────────────────────────────────────────────────┐  │   │
│  │  │ SettingsView.tsx (Settings Page)                │  │   │
│  │  │ ├─ Displays form inputs                         │  │   │
│  │  │ ├─ Tracks changes (hasChanges flag)            │  │   │
│  │  │ ├─ Loads settings on mount                      │  │   │
│  │  │ └─ Saves via API calls                          │  │   │
│  │  └──────────────────────────────────────────────────┘  │   │
│  │                      ↓                                  │   │
│  │  ┌──────────────────────────────────────────────────┐  │   │
│  │  │ ThemeContext.tsx (Global Theme Manager)         │  │   │
│  │  │ ├─ Manages theme color state                    │  │   │
│  │  │ ├─ Applies CSS variables                        │  │   │
│  │  │ └─ Available to all components via useTheme()  │  │   │
│  │  └──────────────────────────────────────────────────┘  │   │
│  │                                                          │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                   │
│                      ↓ API Calls ↓                              │
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │          Backend API (Backend/api/)                      │   │
│  ├──────────────────────────────────────────────────────────┤   │
│  │                                                          │   │
│  │  settings.php                                           │   │
│  │  ├─ GET ?action=all          (Load all settings)       │   │
│  │  ├─ GET ?action=general      (Load general)           │   │
│  │  ├─ GET ?action=theme        (Load theme)             │   │
│  │  ├─ GET ?action=profile      (Load profile)           │   │
│  │  ├─ POST action=save_general (Save general)          │   │
│  │  ├─ POST action=save_branding (Save theme)           │   │
│  │  ├─ POST action=save_profile (Save profile)          │   │
│  │  └─ POST action=save_payments (Save payments)        │   │
│  │                                                          │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                   │
│                      ↓ Database Calls ↓                         │
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │       SettingsController.php (Business Logic)           │   │
│  ├──────────────────────────────────────────────────────────┤   │
│  │                                                          │   │
│  │  Private Methods:                                        │   │
│  │  ├─ getSetting($name)          → Fetch from DB         │   │
│  │  └─ setSetting($name, $value)  → Save to DB            │   │
│  │                                                          │   │
│  │  Public Methods:                                         │   │
│  │  ├─ getProfileInfo()           → Get profile data      │   │
│  │  ├─ getGeneralSettings()       → Get club info         │   │
│  │  ├─ getThemeSettings()         → Get theme color      │   │
│  │  ├─ getPaymentSettings()       → Get payments         │   │
│  │  ├─ getAllSettings()           → Get everything        │   │
│  │  ├─ saveProfileInfo()          → Save profile         │   │
│  │  ├─ saveGeneralSettings()      → Save club info       │   │
│  │  ├─ saveBrandingSettings()     → Save theme color    │   │
│  │  └─ savePaymentSettings()      → Save payments       │   │
│  │                                                          │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                   │
│                      ↓ SQL Queries ↓                            │
│                                                                   │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │          MySQL Database (needsport_pro)                 │   │
│  ├──────────────────────────────────────────────────────────┤   │
│  │                                                          │   │
│  │  settings table                                          │   │
│  │  ┌────┬──────────────────────┬──────────────────────┐  │   │
│  │  │ id │ name                 │ value                │  │   │
│  │  ├────┼──────────────────────┼──────────────────────┤  │   │
│  │  │ 1  │ general_settings     │ {...JSON...}         │  │   │
│  │  │ 2  │ theme_settings       │ {...JSON...}         │  │   │
│  │  │ 3  │ profile_info         │ {...JSON...}         │  │   │
│  │  │ 4  │ payment_settings     │ {...JSON...}         │  │   │
│  │  └────┴──────────────────────┴──────────────────────┘  │   │
│  │                                                          │   │
│  └──────────────────────────────────────────────────────────┘   │
│                                                                   │
└─────────────────────────────────────────────────────────────────┘
```

## Data Flow: Saving Profile Settings

```
User Types Name in Input Field
          ↓
onChange Handler Fires
          ↓
setProfileName(newValue)
setHasChanges(true)
          ↓
"Save Changes" Button Appears
          ↓
User Clicks "Save Changes"
          ↓
handleSave() Function Executes
          ↓
Detects activeSection === 'profile'
          ↓
Sends POST Request to settings.php with:
{
  action: 'save_profile',
  name: 'New Name',
  email: 'new@email.com',
  city: 'New City'
}
          ↓
Backend receives request
          ↓
Calls $controller->saveProfileInfo()
          ↓
Calls $this->setSetting('profile_info', {...})
          ↓
Executes SQL:
INSERT INTO settings (name, value) 
VALUES ('profile_info', '{"name":"..."}')
ON DUPLICATE KEY UPDATE value = '{"name":"..."}' 
          ↓
Database Updated
          ↓
Response sent back to frontend:
{
  "success": true,
  "message": "Profil enregistré",
  "data": {...}
}
          ↓
setSaveMessage() shows success
setHasChanges(false) hides button
          ↓
Settings Persisted! ✓
Reload page - data still there
```

## Data Flow: Changing Theme Color

```
User Clicks Rose Color Button
          ↓
setThemeColor('rose')
setHasChanges(true)
          ↓
Color Picker Highlights Rose
          ↓
User Clicks "Save Changes"
          ↓
handleSave() Detects branding section
          ↓
POST to settings.php:
{
  action: 'save_branding',
  themeColor: 'rose'
}
          ↓
Backend Saves to Database
          ↓
Frontend Receives Success Response
          ↓
updateGlobalTheme('rose') Called
          ↓
ThemeContext Updates State
          ↓
applyThemeColor('rose') Executes
          ↓
CSS Variables Set:
--color-primary: rgb(244, 63, 94)
--color-primary-dark: rgb(244, 63, 94)
--color-primary-light: rgb(254, 205, 211)
          ↓
All Components Using These Variables Update
          ↓
UI Turns Pink! 🌹
          ↓
Success Message Shown
          ↓
Color Persists (Stored in DB)
```

## State Management Flow

```
┌─────────────────────────────────────────────────────────┐
│              SettingsView Component State                │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  Profile State          General State       Payment     │
│  ├─ profileName         ├─ clubName         ├─currency  │
│  ├─ profileEmail        ├─ slogan           └─taxRate   │
│  └─ profileCity         ├─ language                     │
│                         ├─ timezone                     │
│  Branding State         └─ themeColor                   │
│  └─ themeColor                                          │
│                                                         │
│  UI State                                               │
│  ├─ activeSection (which tab)                          │
│  ├─ hasChanges (any edits?)                            │
│  ├─ isSaving (loading state)                           │
│  ├─ saveMessage (success text)                         │
│  └─ saveError (error text)                             │
│                                                         │
└─────────────────────────────────────────────────────────┘
            ↓
┌─────────────────────────────────────────────────────────┐
│              ThemeContext Global State                   │
├─────────────────────────────────────────────────────────┤
│                                                         │
│  themeColor: 'indigo' | 'rose' | 'emerald' | ...      │
│  ↓                                                      │
│  Applied to Document Root CSS Variables                │
│  ↓                                                      │
│  All Components Using CSS Classes Get Updated!         │
│                                                         │
└─────────────────────────────────────────────────────────┘
```

## File Dependencies

```
App.tsx
├─ Imports ThemeProvider from ThemeContext.tsx
├─ Wraps entire app with <ThemeProvider>
└─ Imports SettingsView component

SettingsView.tsx
├─ Imports useTheme hook from ThemeContext.tsx
├─ Uses updateGlobalTheme() when saving colors
├─ Makes API calls to Backend/api/settings.php
└─ Manages local state for form inputs

ThemeContext.tsx
├─ Provides useTheme() hook
├─ Manages global theme state
├─ Loads theme on mount from API
├─ Applies CSS variables on change
└─ Available to all components

Backend/api/settings.php
├─ Requires SettingsController.php
├─ Routes requests to controller methods
├─ Returns JSON responses
└─ Handles both GET and POST

Backend/controllers/SettingsController.php
├─ Requires Database connection
├─ Implements CRUD operations
├─ Uses JSON storage in DB
└─ Provides data to API

Backend/setup.sql
└─ Creates settings table with JSON storage
```

## Color Application Flow

```
ThemeProvider on App Mount
          ↓
loadTheme() from API
          ↓
Get themeColor from Database
          ↓
applyThemeColor() Function
          ↓
Map color name to RGB values:
├─ indigo: rgb(79, 70, 229)
├─ rose: rgb(244, 63, 94)
├─ emerald: rgb(16, 185, 129)
├─ amber: rgb(245, 158, 11)
└─ slate: rgb(30, 41, 59)
          ↓
Set CSS Variables:
document.documentElement.style.setProperty(
  '--color-primary', rgbValue
)
          ↓
All Components See:
className="bg-indigo-600"
          ↓
Tailwind Applies Color
(Based on CSS variables or hardcoded classes)
          ↓
UI Displays in Selected Color
```

## Database Storage Format

```
INSERT Query:
INSERT INTO settings (name, value) 
VALUES (
  'theme_settings',
  '{"themeColor":"indigo","logo":null}'
)
ON DUPLICATE KEY UPDATE value = VALUES(value)

Stored JSON Example:
{
  "themeColor": "rose",
  "logo": null
}

Retrieval:
SELECT value FROM settings WHERE name='theme_settings'
Returns: {"themeColor":"rose","logo":null}

PHP Conversion:
json_decode($result['value'], true)
Returns: Array ( 'themeColor' => 'rose', 'logo' => null )

Usage in React:
const data = await response.json()
setThemeColor(data.themeColor) // 'rose'
```

---

This architecture ensures:
✅ Clean separation of concerns (Frontend/Backend)
✅ Database persistence for all settings
✅ Real-time UI updates when colors change
✅ Easy to extend with new settings
✅ Type-safe frontend with React/TypeScript
✅ Secure backend with PHP validation
