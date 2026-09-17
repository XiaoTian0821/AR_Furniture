# Quick Fix: Images/3D Models Not Showing

## Most Likely Problem: Database Not Imported

### Fix in 2 Minutes:

**Step 1: Open phpMyAdmin**
```
http://localhost/phpmyadmin
```

**Step 2: Import Database**
1. Click **Import** tab (top menu)
2. Click **Choose File** button
3. Navigate to:
   ```
   C:\xampp\htdocs\AR_Furniture\sql\import.sql
   ```
4. Click **Go** button
5. Wait for: "Import has been successfully finished"

**Step 3: Verify**
1. Visit: http://localhost/AR_Furniture/check_setup.php
2. Should show:
   - ✅ Products in database: 24
   - ✅ All tables exist
   - ✅ GLB files found

**Step 4: Test**
1. Visit: http://localhost/AR_Furniture/
2. Should see 24 furniture products
3. Click any category filter
4. Click any product
5. Should see 3D model viewer

---

## If Still Not Working:

### Check 1: Database Empty?
```sql
-- Run this in phpMyAdmin SQL tab:
USE ar_furniture;
SELECT COUNT(*) FROM products;
-- Should return: 24
```

### Check 2: Files Missing?
```
-- Check if files exist:
dir C:\xampp\htdocs\AR_Furniture\models\chairs\
-- Should show 6 .glb files
```

### Check 3: Wrong Paths?
```sql
-- Check database paths:
SELECT slug, glb_path FROM products LIMIT 5;
-- Should show: models/chairs/modern-armchair.glb
```

### Check 4: Apache Config?
```
-- Check if .htaccess is allowed:
-- Edit C:\xampp\apache\conf\httpd.conf
-- Find: AllowOverride None
-- Change to: AllowOverride All
-- Restart Apache
```

---

## Quick Diagnostic

Run this page to see what's wrong:
```
http://localhost/AR_Furniture/debug_models.php
```

This will show:
- ✅ Database status
- ✅ File existence
- ✅ HTTP accessibility
- ✅ 3D viewer test
- ✅ .htaccess content

---

## Common Solutions

### Problem: "No products found"
**Solution:** Import the database (Steps 1-3 above)

### Problem: 3D model shows error
**Solution:** Check .htaccess file exists in /models/

### Problem: Files not loading
**Solution:** 
1. Check file paths in database match actual files
2. Use forward slashes: `models/chairs/file.glb`
3. Not backslashes: `models\chairs\file.glb`

### Problem: 403 Forbidden
**Solution:** 
1. Check Apache config allows .htaccess
2. Restart Apache after any changes

---

## Still Stuck?

1. **Run debug page:** http://localhost/AR_Furniture/debug_models.php
2. **Check console:** Press F12 in browser → Console tab
3. **Check Apache logs:** C:\xampp\apache\logs\error.log
4. **Post error messages** for help

---

## Success Checklist

After fixing, you should see:
- ✅ Homepage shows 24 products
- ✅ Category filters work (Chairs, Sofas, Tables, etc.)
- ✅ Product cards show (with placeholder images)
- ✅ Clicking product opens detail page
- ✅ 3D model viewer loads and rotates
- ✅ AR button appears on mobile
- ✅ QR code works on homepage

---

**Remember:** The most common issue is forgetting to import the database! 🎯
