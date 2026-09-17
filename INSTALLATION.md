# AR Furniture - Quick Installation Guide

## Problem: "No products found in this category"

This error means the database is not set up yet. Follow these steps:

## Step 1: Import the Database

### Option A: Using phpMyAdmin (Recommended)
1. Open your browser and go to: http://localhost/phpmyadmin
2. Click on the **Import** tab at the top
3. Click **Choose File** button
4. Navigate to: `C:\xampp\htdocs\AR_Furniture\sql\quick_setup.sql`
5. Click **Go** button
6. Wait for success message

### Option B: Using MySQL Command Line
```bash
mysql -u root -p ar_furniture < C:\xampp\htdocs\AR_Furniture\sql\quick_setup.sql
```

## Step 2: Verify Installation

After importing, check if everything works:

1. Open: http://localhost/AR_Furniture/check_setup.php
2. You should see:
   - ✅ Database connection successful
   - ✅ Tables exist
   - ✅ 24 products loaded
   - ✅ 6 categories created

## Step 3: Access the Application

- **Homepage**: http://localhost/AR_Furniture/
- **Admin Login**: http://localhost/AR_Furniture/admin/login.php
  - Username: `admin`
  - Password: `admin123`

## Step 4: Test QR Code

1. Open homepage: http://localhost/AR_Furniture/
2. You should see a QR code under "View on Your Phone"
3. Scan it with your phone camera
4. The catalog should open on your mobile device

## Troubleshooting

### If you still see "No products found":
1. Check if database exists in phpMyAdmin
2. Re-import the SQL file
3. Refresh the page (Ctrl+F5)

### If you see database connection errors:
1. Make sure XAMPP Apache and MySQL are running
2. Check config.php credentials:
   ```php
   'db' => [
       'host' => 'localhost',
       'name' => 'ar_furniture',
       'user' => 'root',
       'pass' => '',
   ]
   ```

### If GLB files not loading:
1. Check that files exist in: `C:\xampp\htdocs\AR_Furniture\models\`
2. Verify folder structure matches database paths
3. Check browser console for errors

## Product Categories

The database now contains:
- **Chairs**: 6 products
- **Sofas**: 3 products  
- **Tables**: 5 products
- **Beds**: 4 products
- **Storage**: 3 products
- **Lighting**: 3 products

Total: **24 products** with GLB models!
