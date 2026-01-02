# 🚀 Settings System - Quick Start Guide

## What You Can Do Now

### ✨ Try the Theme Color Changer (Most Fun!)
1. Open your app in browser
2. Click **Settings** in left sidebar
3. Go to **Branding & Design**
4. Click on a color circle:
   - 🔵 **Indigo** (current)
   - 🌹 **Rose** (pink)
   - 💚 **Emerald** (green)
   - 🟠 **Amber** (orange)
   - ⚫ **Slate** (dark)
5. Click **Enregistrer les modifications**
6. **Watch the entire app UI change color!** ✨

### 👤 Edit Your Profile
1. Settings → **Mon Profil**
2. Change name, email, or city
3. Click save
4. Reload the page - changes persist! ✓

### 🏢 Configure Your Club
1. Settings → **Général**
2. Edit club name, slogan, language, timezone
3. Save and reload - all data persists! ✓

### 💰 Set Financial Details
1. Settings → **Paiements & Taxes**
2. Choose currency (DH, EUR, USD)
3. Set tax rate (VAT %)
4. Save and it's stored in database ✓

---

## How It Works (Simple Version)

```
You fill form in Settings page
        ↓
Click "Save Changes" button
        ↓
Data sent to Backend/api/settings.php
        ↓
Saved to MySQL database
        ↓
If it's a color change → App UI changes immediately! 🎨
        ↓
Reload page → Everything still there! ✓
```

---

## Files That Make It Work

| File | Purpose |
|------|---------|
| `Backend/api/settings.php` | Receives save requests, talks to database |
| `Backend/controllers/SettingsController.php` | Handles all database operations |
| `uui/components/SettingsView.tsx` | The settings form you see |
| `uui/context/ThemeContext.tsx` | Makes colors change app-wide |
| `Backend/setup.sql` | Database table (`settings`) |

---

## Testing Checklist

Complete each test to verify everything works:

### Test 1: Save Profile Name
- [ ] Go to Settings → Mon Profil
- [ ] Change name to "Test Name"
- [ ] Click Save
- [ ] See ✓ success message
- [ ] Reload page (F5)
- [ ] Name is still "Test Name" ✓

### Test 2: Change Theme Color
- [ ] Go to Settings → Branding & Design
- [ ] Click Rose color
- [ ] Click Save
- [ ] Entire app turns pink! 🌹
- [ ] Reload page
- [ ] App is still pink ✓

### Test 3: Save Club Name
- [ ] Go to Settings → Général
- [ ] Change "Nom du Club" to "My Club"
- [ ] Click Save
- [ ] Reload page
- [ ] Club name is "My Club" ✓

### Test 4: Set Currency
- [ ] Go to Settings → Paiements & Taxes
- [ ] Change currency to EUR
- [ ] Click Save
- [ ] Reload page
- [ ] Currency is still EUR ✓

---

## Troubleshooting

### "Error saving settings"
**Possible causes**:
- Not logged in (check login page)
- Database not running (check MAMP)
- API file path incorrect

**Solution**: Check browser console (F12) for error details

### Settings don't persist after reload
**Possible causes**:
- Browser cache issue
- Database not actually saving
- Wrong API endpoint path

**Solution**:
1. Hard refresh: Ctrl+Shift+R (or Cmd+Shift+R on Mac)
2. Check MySQL is running
3. Verify settings table exists: `SELECT * FROM settings;`

### Color doesn't change app-wide
**Possible causes**:
- Theme change not saved
- Browser cache
- CSS not updated

**Solution**:
1. Click Save again
2. Hard refresh browser
3. Check browser console for errors

---

## What Gets Saved to Database

The `settings` table stores everything:

```
┌─────────────────────────────────────────────────┐
│ Settings Table                                  │
├──────┬──────────────────────────────────────────┤
│ name │ value                                    │
├──────┼──────────────────────────────────────────┤
│ gen  │ {"clubName":"...","slogan":"..."}       │
│ theme│ {"themeColor":"indigo","logo":null}     │
│ prof │ {"name":"...","email":"..."}            │
│ pay  │ {"currency":"DH","taxRate":20}          │
└──────┴──────────────────────────────────────────┘
```

Each setting is stored as **JSON**, making it flexible and extensible.

---

## Common Tasks

### I want to add a new setting
Add to SettingsView, create a save handler, and database saves it automatically!

### I want to change the color options
Edit `ThemeContext.tsx` - add more colors to the `colorMap`

### I want to make settings per-user (not global)
Add `user_id` column to settings table and filter by current user

### I want to export settings
Add a new API action to download settings as JSON file

---

## API Quick Reference

```javascript
// Load all settings
fetch('../Backend/api/settings.php?action=all')

// Save profile
fetch('../Backend/api/settings.php', {
  method: 'POST',
  body: JSON.stringify({
    action: 'save_profile',
    name: 'New Name',
    email: 'new@email.com',
    city: 'New City'
  })
})

// Save color theme
fetch('../Backend/api/settings.php', {
  method: 'POST',
  body: JSON.stringify({
    action: 'save_branding',
    themeColor: 'rose'  // indigo, rose, emerald, amber, slate
  })
})
```

---

## Success Indicators

You'll know everything is working when you see:

✅ **Green success message** after clicking save
✅ **"Enregistrer les modifications" button disappears** when no changes
✅ **Colors change app-wide** when you change theme
✅ **Settings persist after reload** (F5)
✅ **No errors in browser console** (F12 → Console tab)

---

## Need Help?

Check these files for details:
- **How to add new settings**: `SETTINGS_SYSTEM_GUIDE.md`
- **API endpoints & examples**: `API_DOCUMENTATION.md`
- **Technical architecture**: `SETTINGS_IMPLEMENTATION_SUMMARY.md`

---

## 🎉 You're All Set!

Your settings system is ready to use. Go ahead and:
1. Change colors 🎨
2. Edit profile 👤
3. Configure club 🏢
4. Set finances 💰

Everything saves to the database and persists! Enjoy! 🚀
