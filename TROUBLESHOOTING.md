# AR Furniture - Troubleshooting Guide

## Problem: Images/3D Models Not Showing

### Common Causes & Solutions

## 1. Database Not Imported ❌

**Symptoms:**
- Homepage shows "No products found in this category"
- No products appear in any category
- Empty catalog

**Solution:**
```
1. Open http://localhost/phpmyadmin
2. Click "Import" tab
3. Choose file: C:\xampp\htdocs\AR_Furniture\sql\import.sql
4. Click "Go"
5. Wait for success message
```

**Verify:**
- Visit: http://localhost/AR_Furniture/check_setup.php
- Should show: "Products in database: 24"

---

## 2. GLB Files Not Accessible ❌

**Symptoms:**
- Database has products
- 3D viewer shows error
- Console shows 404 errors

**Solution:**
```
1. Check .htaccess file exists in /models/
2. Verify Apache allows .htaccess overrides
3. Test file access: http://localhost/AR_Furniture/models/chairs/modern-armchair.glb
```

**Fix .htaccess if needed:**
```apache
# models/.htaccess
Options -Indexes
<FilesMatch "\.(glb|gltf)$">
    Order allow,deny
    Allow from all
</FilesMatch>
```

---

## 3. Wrong File Paths in Database ❌

**Symptoms:**
- Products exist in database
- Files exist on disk
- But paths don't match

**Solution:**
```sql
-- Check what paths are in database
SELECT id, slug, glb_path FROM products LIMIT 5;

-- Check what files actually exist
-- Expected: models/chairs/modern-armchair.glb
-- Actual: models\chairs\modern-armchair.glb (Windows path)
```

**Fix:** Update paths in database to use forward slashes:
```sql
UPDATE products SET glb_path = 'models/chairs/modern-armchair.glb' WHERE slug = 'modern-armchair';
```

---

## 4. Browser Issues ❌

**Symptoms:**
- Model viewer shows error
- Console has JavaScript errors
- Different behavior in different browsers

**Solution:**
```
1. Open browser console (F12)
2. Check for errors
3. Try different browser:
   - Chrome (recommended)
   - Firefox
   - Edge
```

**Common Console Errors:**
- `Failed to load model` → Check file path
- `model-viewer is not defined` → Check script loading
- `CORS error` → Check .htaccess headers

---

## 5. Apache Configuration ❌

**Symptoms:**
- Files not accessible
- 403 Forbidden errors
- .htaccess not working

**Solution:**
```
1. Check XAMPP Apache config
2. Ensure AllowOverride is set to All
3. Restart Apache after changes
```

**Apache config (httpd.conf):**
```apache
<Directory "C:/xampp/htdocs">
    AllowOverride All
    Require all granted
</Directory>
```

---

## Debug Checklist

### Step 1: Check Database
```
□ Open http://localhost/AR_Furniture/check_setup.php
□ Verify: Products in database: 24
□ Verify: All tables exist
```

### Step 2: Check Files
```
□ Open http://localhost/AR_Furniture/debug_models.php
□ Verify: All GLB files exist
□ Verify: Files are accessible via HTTP
```

### Step 3: Check 3D Viewer
```
□ Test model-viewer on debug page
□ Check browser console for errors
□ Verify MIME types are correct
```

### Step 4: Check Main Pages
```
□ Visit http://localhost/AR_Furniture/
□ Should see 24 products
□ Click a category filter
□ Click a product
□ Should see 3D viewer
```

---

## Quick Fixes

### If database is empty:
```bash
# Import via command line
mysql -u root -p ar_furniture < C:\xampp\htdocs\AR_Furniture\sql\import.sql
```

### If GLB files not loading:
```bash
# Check .htaccess exists
dir C:\xampp\htdocs\AR_Furniture\models\.htaccess

# Check Apache allows overrides
# Edit C:\xampp\apache\conf\httpd.conf
# Find: AllowOverride None
# Change to: AllowOverride All
# Restart Apache
```

### If browser shows errors:
```
1. Clear browser cache (Ctrl+Shift+Delete)
2. Hard refresh (Ctrl+F5)
3. Try incognito/private mode
4. Check console (F12)
```

---

## Support

### Check these URLs:
- **Debug Page:** http://localhost/AR_Furniture/debug_models.php
- **Setup Check:** http://localhost/AR_Furniture/check_setup.php
- **Homepage:** http://localhost/AR_Furniture/
- **Product Test:** http://localhost/AR_Furniture/product.php?slug=modern-armchair

### Common Errors:
- **"No products found"** → Import database
- **"Failed to load model"** → Check .htaccess
- **"404 Not Found"** → Check file paths
- **"403 Forbidden"** → Check Apache config
- **"CORS error"** → Check headers in .htaccess

---

## File Locations

```
Database SQL:     C:\xampp\htdocs\AR_Furniture\sql\
GLB Models:       C:\xampp\htdocs\AR_Furniture\models\
Main Config:      C:\xampp\htdocs\AR_Furniture\config\config.php
Apache Config:    C:\xampp\apache\conf\httpd.conf
phpMyAdmin:       http://localhost/phpmyadmin
```

---

## Still Not Working?

1. **Run the debug page:** http://localhost/AR_Furniture/debug_models.php
2. **Check browser console:** Press F12 → Console tab
3. **Check Apache errors:** C:\xampp\apache\logs\error.log
4. **Check MySQL errors:** C:\xampp\mysql\data\*.err

Post any error messages you see for help!
