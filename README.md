# AR Furniture Catalog System

A professional web-based AR furniture catalog that allows users to browse furniture, view 3D models, and place products in their real environment using augmented reality.

## Features

- **3D Model Viewer**: Interactive 3D furniture viewing using `<model-viewer>`
- **AR Experience**: Place furniture in your space using WebXR, Scene Viewer, or Quick Look
- **Product Management**: Full CRUD operations for products and categories
- **Responsive Design**: Works on desktop, tablet, and mobile devices
- **Secure Admin Panel**: Password-protected administration with file upload security
- **QR Code Integration**: Desktop-to-mobile handoff for AR viewing
- **Category Filtering**: Dynamic category-based product browsing

## Technology Stack

- **Backend**: PHP 8.3+, MySQL/MariaDB
- **Frontend**: HTML5, CSS3, Vanilla JavaScript
- **3D/AR**: `<model-viewer>` Web Component, WebXR, GLB, USDZ
- **Libraries**: Bootstrap 5, Font Awesome 6
- **Hosting**: XAMPP, Apache, cPanel

## Project Structure

```
ar-furniture/
│
├── index.php                 # Homepage
├── product.php              # Product detail page
├── .htaccess                # Apache configuration
├── .user.ini                # PHP settings
│
├── admin/
│   ├── index.php            # Admin dashboard
│   ├── login.php            # Admin login
│   ├── logout.php           # Admin logout
│   ├── product_edit.php     # Add/Edit products
│   ├── product_delete.php   # Delete products
│   ├── categories.php       # Category management
│   └── category_edit.php    # Edit categories
│
├── api/
│   └── products.php         # Product API endpoint
│
├── config/
│   ├── config.php           # Application configuration
│   └── .htaccess            # Block access to config
│
├── includes/
│   ├── db.php               # Database connection
│   ├── auth.php             # Authentication functions
│   ├── products.php         # Product helper functions
│   ├── categories.php       # Category helper functions
│   ├── admin_layout.php     # Admin page layout
│   └── public_layout.php    # Public page layout
│
├── css/
│   └── styles.css           # Main stylesheet
│
├── js/
│   ├── app.js               # Main JavaScript
│   └── qr.js                # QR code functionality
│
├── models/                  # 3D model storage
│   └── .htaccess            # Security
│
├── uploads/                 # Upload storage
│   ├── .htaccess            # Security
│   └── thumbs/              # Product thumbnails
│
├── sql/
│   └── database.sql         # Database schema and sample data
│
└── README.md                # This file
```

## Installation

### XAMPP Installation

1. **Download and Install XAMPP**
   - Download from https://www.apachefriends.org/
   - Install to `C:\xampp`

2. **Place the Project**
   - Copy the `AR_Furniture` folder to `C:\xampp\htdocs\`
   - The full path should be: `C:\xampp\htdocs\AR_Furniture\`

3. **Start Services**
   - Open XAMPP Control Panel
   - Start Apache and MySQL

4. **Create Database**
   - Open phpMyAdmin: http://localhost/phpmyadmin
   - Click "Import" tab
   - Choose file: `sql/database.sql`
   - Click "Go"

5. **Configure Application**
   - Open `config/config.php`
   - Update database credentials if needed (default: root with no password)
   - Set the correct base URL if needed

6. **Access the Application**
   - Homepage: http://localhost/AR_Furniture/
   - Admin Panel: http://localhost/AR_Furniture/admin/login.php

### Default Admin Credentials

- **Username**: `admin`
- **Password**: `admin123`

**Important**: Change these credentials immediately after first login!

## cPanel Deployment

1. **Create MySQL Database**
   - Log in to cPanel
   - Go to "MySQL Databases"
   - Create a new database (e.g., `username_arfurniture`)
   - Create a new user and assign to database
   - Note down database name, username, and password

2. **Import Database**
   - Go to "phpMyAdmin"
   - Select your database
   - Click "Import" tab
   - Upload `sql/database.sql`
   - Click "Go"

3. **Upload Files**
   - Go to "File Manager"
   - Navigate to `public_html` (or your desired directory)
   - Upload all project files
   - Ensure correct permissions:
     - `models/` - 755
     - `uploads/` - 755
     - `uploads/thumbs/` - 755

4. **Configure Application**
   - Edit `config/config.php`
   - Update database credentials:
     ```php
     'db' => [
         'host' => 'localhost',
         'name' => 'username_arfurniture',
         'user' => 'username_dbuser',
         'pass' => 'your_password',
     ]
     ```
   - Set the correct base URL if needed

5. **Enable HTTPS**
   - Go to "SSL/TLS" in cPanel
   - Enable HTTPS for your domain
   - Update `config/config.php` if needed

6. **Test the Application**
   - Visit your domain
   - Test product browsing
   - Test admin login
   - Test AR on mobile device
   - Test QR code functionality

## File Permissions

After upload, ensure correct permissions:

```bash
# Directories
chmod 755 models/
chmod 755 uploads/
chmod 755 uploads/thumbs/

# Files
chmod 644 *.php
chmod 644 config/*.php
chmod 644 includes/*.php
```

## Testing Checklist

### Database
- [ ] Database connection works
- [ ] Products can be queried
- [ ] Categories work correctly
- [ ] Foreign key relationships function

### Authentication
- [ ] Admin login works
- [ ] Invalid credentials rejected
- [ ] Logout works
- [ ] Protected pages redirect properly

### Products
- [ ] Add new product
- [ ] Edit existing product
- [ ] Delete product with confirmation
- [ ] Toggle active/inactive
- [ ] Category filtering works

### Uploads
- [ ] Thumbnail upload works
- [ ] GLB model upload works
- [ ] Invalid file types rejected
- [ ] Large files handled properly

### Frontend
- [ ] Homepage loads
- [ ] Category filtering works
- [ ] Product cards display correctly
- [ ] Product detail page works
- [ ] Responsive design on all devices
- [ ] QR code generates correctly

### AR
- [ ] 3D model loads on product page
- [ ] Model viewer controls work
- [ ] AR button appears on mobile
- [ ] AR launches successfully
- [ ] Furniture placement works
- [ ] Dimensions appear realistic

### Hosting
- [ ] XAMPP compatibility verified
- [ ] cPanel compatibility verified
- [ ] HTTPS works correctly
- [ ] Relative URLs function properly
- [ ] File permissions correct

## AR Functionality

### How AR Works

The AR feature uses the `<model-viewer>` Web Component from Google, which provides:

1. **WebXR**: For devices with native WebXR support
2. **Scene Viewer**: For Android devices with Google Play Services
3. **Quick Look**: For Apple devices (iOS 12+)

### AR Requirements

- **Android**: Chrome browser, ARCore supported device
- **iOS**: Safari browser, iOS 12+ with AR Quick Look support
- **Desktop**: 3D viewing only, no AR

### Testing AR

1. Deploy the application to a web server (HTTPS required for WebXR)
2. Open a product page on a mobile device
3. Tap "View in Your Space" button
4. Point camera at a flat surface
5. Tap to place the furniture

## Security Features

- **Password Hashing**: Uses PHP's `password_hash()` and `password_verify()`
- **SQL Injection Prevention**: PDO prepared statements throughout
- **Session Security**: Session regeneration after login
- **File Upload Validation**: Extension, MIME type, and size checks
- **Directory Protection**: `.htaccess` files prevent directory listing and script execution
- **Output Escaping**: All user-generated content is escaped
- **Access Control**: Admin pages require authentication

## Configuration

### config/config.php

```php
return [
    'app' => [
        'name' => 'AR Furniture Catalog',
        'url' => '', // Leave empty for auto-detection
    ],
    'db' => [
        'host' => 'localhost',
        'name' => 'ar_furniture',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'upload' => [
        'max_size' => 50 * 1024 * 1024, // 50MB
        'allowed_models' => ['.glb', '.gltf', '.usdz'],
        'allowed_images' => ['.jpg', '.jpeg', '.png', '.webp'],
    ],
    'debug' => false,
];
```

## API Endpoints

### GET /api/products.php
Returns all active products as JSON.

**Parameters:**
- `category` (optional): Filter by category ID
- `limit` (optional): Limit number of results

**Response:**
```json
{
    "success": true,
    "count": 6,
    "products": [
        {
            "id": 1,
            "slug": "modern-armchair",
            "name": "Modern Armchair",
            "price": 899.00,
            "currency": "RM",
            ...
        }
    ]
}
```

## Browser Support

- **Desktop**: Chrome, Firefox, Safari, Edge (latest versions)
- **Mobile**: Chrome Android, Safari iOS (AR features)
- **AR Supported**: Android Chrome (WebXR/Scene Viewer), iOS Safari (Quick Look)

## Troubleshooting

### Database Connection Failed
- Check database credentials in `config/config.php`
- Ensure MySQL service is running
- Verify database exists in phpMyAdmin

### 3D Models Not Loading
- Check file paths in database
- Ensure GLB files are in `models/` directory
- Verify `.htaccess` allows GLB access
- Check browser console for errors

### AR Not Working
- Ensure HTTPS is enabled (required for WebXR)
- Test on a supported mobile device
- Check browser compatibility
- Verify GLB file has correct scale

### File Upload Issues
- Check PHP upload limits in `.user.ini`
- Verify directory permissions
- Check file size restrictions
- Review upload error messages

## Support

For issues or questions:
1. Check the troubleshooting section
2. Review error messages in browser console
3. Verify database connection
4. Test on different devices/browsers

## License

This project is created for educational purposes.

## Credits

- **model-viewer**: Google Web Component
- **Bootstrap 5**: Frontend framework
- **Font Awesome**: Icon library

---

**Note**: This system is designed to work with real GLB 3D models. Sample data includes placeholder paths. Replace with actual model files for full functionality.
