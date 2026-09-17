-- ============================================
-- AR Furniture Database Setup
-- Copy and paste this into phpMyAdmin SQL tab
-- ============================================

-- Create database
CREATE DATABASE IF NOT EXISTS ar_furniture CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ar_furniture;

-- Create tables
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category_id INT NOT NULL,
    slug VARCHAR(150) NOT NULL UNIQUE,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    currency VARCHAR(10) NOT NULL DEFAULT 'RM',
    glb_path VARCHAR(255) DEFAULT NULL,
    usdz_path VARCHAR(255) DEFAULT NULL,
    thumb_path VARCHAR(255) DEFAULT NULL,
    width_cm DECIMAL(10, 2) DEFAULT NULL,
    height_cm DECIMAL(10, 2) DEFAULT NULL,
    depth_cm DECIMAL(10, 2) DEFAULT NULL,
    glb_x_m DECIMAL(10, 4) DEFAULT NULL,
    glb_y_m DECIMAL(10, 4) DEFAULT NULL,
    glb_z_m DECIMAL(10, 4) DEFAULT NULL,
    is_active TINYINT(1) DEFAULT 1,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT,
    INDEX idx_slug (slug),
    INDEX idx_category (category_id),
    INDEX idx_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert data
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

INSERT INTO categories (slug, name, sort_order, is_active) VALUES
('chairs', 'Chairs', 1, 1),
('sofas', 'Sofas', 2, 1),
('tables', 'Tables', 3, 1),
('beds', 'Beds', 4, 1),
('storage', 'Storage', 5, 1),
('lighting', 'Lighting', 6, 1);

INSERT INTO products (category_id, slug, name, description, price, currency, glb_path, width_cm, height_cm, depth_cm, is_active, sort_order) VALUES
(1, 'modern-armchair', 'Modern Armchair', 'Stylish contemporary armchair with ergonomic design', 899.00, 'RM', 'models/chairs/modern-armchair.glb', 80.00, 85.00, 80.00, 1, 1),
(1, 'dining-chair', 'Dining Chair', 'Elegant dining chair with solid wood frame', 349.00, 'RM', 'models/chairs/dining-chair.glb', 45.00, 95.00, 50.00, 1, 2),
(1, 'office-chair', 'Office Chair', 'Ergonomic office chair with adjustable height', 699.00, 'RM', 'models/chairs/office-chair.glb', 65.00, 110.00, 65.00, 1, 3),
(1, 'bar-stool', 'Bar Stool', 'Modern bar stool with footrest', 299.00, 'RM', 'models/chairs/bar-stool.glb', 40.00, 75.00, 40.00, 1, 4),
(1, 'accent-chair', 'Accent Chair', 'Bold accent chair with distinctive design', 749.00, 'RM', 'models/chairs/accent-chair.glb', 75.00, 85.00, 75.00, 1, 5),
(1, 'dining-chair-2', 'Classic Dining Chair', 'Traditional dining chair with curved backrest', 399.00, 'RM', 'models/chairs/dining-chair-2.glb', 45.00, 90.00, 50.00, 1, 6),
(2, 'three-seater-sofa', 'Three-Seater Sofa', 'Spacious three-seater with deep seating', 2499.00, 'RM', 'models/sofas/three-seater-sofa.glb', 200.00, 90.00, 95.00, 1, 1),
(2, 'loveseat', 'Loveseat', 'Compact loveseat perfect for smaller spaces', 1299.00, 'RM', 'models/sofas/loveseat.glb', 150.00, 85.00, 90.00, 1, 2),
(2, 'sectional', 'L-Sectional Sofa', 'Modern L-shaped sectional with chaise', 3299.00, 'RM', 'models/sofas/sectional.glb', 280.00, 90.00, 180.00, 1, 3),
(3, 'coffee-table', 'Coffee Table', 'Minimalist coffee table with lower shelf', 699.00, 'RM', 'models/tables/coffee-table.glb', 120.00, 45.00, 60.00, 1, 1),
(3, 'dining-table', 'Dining Table', 'Solid wood dining table seats 6-8', 1899.00, 'RM', 'models/tables/dining-table.glb', 180.00, 75.00, 90.00, 1, 2),
(3, 'side-table', 'Side Table', 'Compact side table with geometric base', 349.00, 'RM', 'models/tables/side-table.glb', 50.00, 60.00, 50.00, 1, 3),
(3, 'desk', 'Writing Desk', 'Modern writing desk with cable management', 899.00, 'RM', 'models/tables/desk.glb', 120.00, 75.00, 60.00, 1, 4),
(3, 'coffee-table-2', 'Nesting Coffee Tables', 'Set of 2 nesting tables with marble-look tops', 599.00, 'RM', 'models/tables/coffee-table-2.glb', 100.00, 45.00, 50.00, 1, 5),
(4, 'platform-bed', 'Platform Bed Queen', 'Modern platform bed with integrated storage', 1899.00, 'RM', 'models/beds/platform-bed.glb', 160.00, 35.00, 200.00, 1, 1),
(4, 'king-bed', 'King Size Bed Frame', 'Luxurious king size bed with upholstered headboard', 2299.00, 'RM', 'models/beds/king-bed.glb', 190.00, 40.00, 210.00, 1, 2),
(4, 'twin-bed', 'Twin Bed Frame', 'Space-saving twin bed with clean lines', 799.00, 'RM', 'models/beds/twin-bed.glb', 95.00, 35.00, 200.00, 1, 3),
(4, 'daybed', 'Daybed with Trundle', 'Versatile daybed with pull-out trundle', 1299.00, 'RM', 'models/beds/daybed.glb', 150.00, 80.00, 85.00, 1, 4),
(5, 'bookshelf', 'Bookshelf', 'Open bookshelf with 5 adjustable shelves', 599.00, 'RM', 'models/storage/bookshelf.glb', 80.00, 180.00, 30.00, 1, 1),
(5, 'wardrobe', 'Wardrobe Closet', 'Spacious wardrobe with hanging space and drawers', 1599.00, 'RM', 'models/storage/wardrobe.glb', 120.00, 200.00, 60.00, 1, 2),
(5, 'shelf-unit', 'Floating Shelf Unit', 'Set of 3 floating shelves', 299.00, 'RM', 'models/storage/shelf-unit.glb', 60.00, 30.00, 15.00, 1, 3),
(6, 'floor-lamp', 'Arc Floor Lamp', 'Adjustable arc floor lamp with LED', 449.00, 'RM', 'models/lighting/floor-lamp.glb', 40.00, 180.00, 40.00, 1, 1),
(6, 'table-lamp', 'Table Lamp', 'Elegant table lamp with fabric shade', 199.00, 'RM', 'models/lighting/table-lamp.glb', 30.00, 50.00, 30.00, 1, 2),
(6, 'floor-lamp-2', 'Arc Floor Lamp Modern', 'Contemporary arc floor lamp with marble base', 599.00, 'RM', 'models/lighting/floor-lamp-2.glb', 45.00, 190.00, 45.00, 1, 3);

-- Verify
SELECT 'Setup Complete!' AS status;
SELECT COUNT(*) AS total_products FROM products;
SELECT COUNT(*) AS active_products FROM products WHERE is_active = 1;
