-- AR Furniture Catalog Database
-- Database: ar_furniture
-- Updated with actual GLB model paths

CREATE DATABASE IF NOT EXISTS ar_furniture CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE ar_furniture;

-- Admins Table
CREATE TABLE admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Categories Table
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(100) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Products Table
CREATE TABLE products (
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

-- Sample Admin User (username: admin, password: admin123)
INSERT INTO admins (username, password_hash) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Sample Categories
INSERT INTO categories (slug, name, sort_order, is_active) VALUES
('chairs', 'Chairs', 1, 1),
('sofas', 'Sofas', 2, 1),
('tables', 'Tables', 3, 1),
('beds', 'Beds', 4, 1),
('storage', 'Storage', 5, 1),
('lighting', 'Lighting', 6, 1);

-- Sample Products with actual GLB paths
INSERT INTO products (category_id, slug, name, description, price, currency, glb_path, usdz_path, thumb_path, width_cm, height_cm, depth_cm, glb_x_m, glb_y_m, glb_z_m, is_active, sort_order) VALUES
-- Chairs
(1, 'modern-armchair', 'Modern Armchair', 'A stylish contemporary armchair with ergonomic design and premium upholstery. Perfect for living rooms and reading corners.', 899.00, 'RM', 'models/chairs/modern-armchair.glb', NULL, NULL, 80.00, 85.00, 80.00, 0.80, 0.85, 0.80, 1, 1),
(1, 'dining-chair', 'Dining Chair', 'Elegant dining chair with solid wood frame and padded seat. Stackable design for easy storage.', 349.00, 'RM', 'models/chairs/dining-chair.glb', NULL, NULL, 45.00, 95.00, 50.00, 0.45, 0.95, 0.50, 1, 2),
(1, 'office-chair', 'Office Chair', 'Ergonomic office chair with adjustable height, lumbar support, and smooth-rolling casters.', 699.00, 'RM', 'models/chairs/office-chair.glb', NULL, NULL, 65.00, 110.00, 65.00, 0.65, 1.10, 0.65, 1, 3),
(1, 'bar-stool', 'Bar Stool', 'Modern bar stool with footrest and comfortable rounded seat. Available in multiple finishes.', 299.00, 'RM', 'models/chairs/bar-stool.glb', NULL, NULL, 40.00, 75.00, 40.00, 0.40, 0.75, 0.40, 1, 4),
(1, 'accent-chair', 'Accent Chair', 'Bold accent chair with distinctive design. Perfect statement piece for any room.', 749.00, 'RM', 'models/chairs/accent-chair.glb', NULL, NULL, 75.00, 85.00, 75.00, 0.75, 0.85, 0.75, 1, 5),
(1, 'dining-chair-2', 'Classic Dining Chair', 'Traditional dining chair with curved backrest and upholstered seat. Set of 2 available.', 399.00, 'RM', 'models/chairs/dining-chair-2.glb', NULL, NULL, 45.00, 90.00, 50.00, 0.45, 0.90, 0.50, 1, 6),
-- Sofas
(2, 'three-seater-sofa', 'Three-Seater Sofa', 'Spacious three-seater sofa with deep seating and high-density foam cushions. Available in multiple fabric options.', 2499.00, 'RM', 'models/sofas/three-seater-sofa.glb', NULL, NULL, 200.00, 90.00, 95.00, 2.00, 0.90, 0.95, 1, 1),
(2, 'loveseat', 'Loveseat', 'Compact loveseat perfect for smaller spaces. Features plush cushions and sturdy hardwood frame.', 1299.00, 'RM', 'models/sofas/loveseat.glb', NULL, NULL, 150.00, 85.00, 90.00, 1.50, 0.85, 0.90, 1, 2),
(2, 'sectional', 'L-Sectional Sofa', 'Modern L-shaped sectional with chaise lounge. Modular design allows flexible configurations.', 3299.00, 'RM', 'models/sofas/sectional.glb', NULL, NULL, 280.00, 90.00, 180.00, 2.80, 0.90, 1.80, 1, 3),
-- Tables
(3, 'coffee-table', 'Coffee Table', 'Minimalist coffee table with clean lines and durable surface. Features lower shelf for storage.', 699.00, 'RM', 'models/tables/coffee-table.glb', NULL, NULL, 120.00, 45.00, 60.00, 1.20, 0.45, 0.60, 1, 1),
(3, 'dining-table', 'Dining Table', 'Solid wood dining table seats 6-8 people. Features rich grain pattern and sturdy pedestal base.', 1899.00, 'RM', 'models/tables/dining-table.glb', NULL, NULL, 180.00, 75.00, 90.00, 1.80, 0.75, 0.90, 1, 2),
(3, 'side-table', 'Side Table', 'Compact side table with geometric base. Perfect for lamps, drinks, or decorative accessories.', 349.00, 'RM', 'models/tables/side-table.glb', NULL, NULL, 50.00, 60.00, 50.00, 0.50, 0.60, 0.50, 1, 3),
(3, 'desk', 'Writing Desk', 'Modern writing desk with cable management and drawer storage. Ideal for home office.', 899.00, 'RM', 'models/tables/desk.glb', NULL, NULL, 120.00, 75.00, 60.00, 1.20, 0.75, 0.60, 1, 4),
(3, 'coffee-table-2', 'Nesting Coffee Tables', 'Set of 2 nesting tables with marble-look tops and gold-finished legs. Versatile styling.', 599.00, 'RM', 'models/tables/coffee-table-2.glb', NULL, NULL, 100.00, 45.00, 50.00, 1.00, 0.45, 0.50, 1, 5),
-- Beds
(4, 'platform-bed', 'Platform Bed Queen', 'Modern platform bed with integrated storage. No box spring required. Made from sustainable materials.', 1899.00, 'RM', 'models/beds/platform-bed.glb', NULL, NULL, 160.00, 35.00, 200.00, 1.60, 0.35, 2.00, 1, 1),
(4, 'king-bed', 'King Size Bed Frame', 'Luxurious king size bed frame with upholstered headboard and low profile design.', 2299.00, 'RM', 'models/beds/king-bed.glb', NULL, NULL, 190.00, 40.00, 210.00, 1.90, 0.40, 2.10, 1, 2),
(4, 'twin-bed', 'Twin Bed Frame', 'Space-saving twin bed with clean lines and sturdy construction. Perfect for bedrooms and guest rooms.', 799.00, 'RM', 'models/beds/twin-bed.glb', NULL, NULL, 95.00, 35.00, 200.00, 0.95, 0.35, 2.00, 1, 3),
(4, 'daybed', 'Daybed with Trundle', 'Versatile daybed with pull-out trundle. Functions as sofa during day, bed at night.', 1299.00, 'RM', 'models/beds/daybed.glb', NULL, NULL, 150.00, 80.00, 85.00, 1.50, 0.80, 0.85, 1, 4),
-- Storage
(5, 'bookshelf', 'Bookshelf', 'Open bookshelf with 5 shelves. Adjustable shelf heights and modern minimalist design.', 599.00, 'RM', 'models/storage/bookshelf.glb', NULL, NULL, 80.00, 180.00, 30.00, 0.80, 1.80, 0.30, 1, 1),
(5, 'wardrobe', 'Wardrobe Closet', 'Spacious wardrobe with hanging space, shelves, and drawers. Soft-close doors included.', 1599.00, 'RM', 'models/storage/wardrobe.glb', NULL, NULL, 120.00, 200.00, 60.00, 1.20, 2.00, 0.60, 1, 2),
(5, 'shelf-unit', 'Floating Shelf Unit', 'Set of 3 floating shelves with invisible mounting. Perfect for displaying books and decor.', 299.00, 'RM', 'models/storage/shelf-unit.glb', NULL, NULL, 60.00, 30.00, 15.00, 0.60, 0.30, 0.15, 1, 3),
-- Lighting
(6, 'floor-lamp', 'Arc Floor Lamp', 'Adjustable arc floor lamp with LED light source. Perfect for reading corners and accent lighting.', 449.00, 'RM', 'models/lighting/floor-lamp.glb', NULL, NULL, 40.00, 180.00, 40.00, 0.40, 1.80, 0.40, 1, 1),
(6, 'table-lamp', 'Table Lamp', 'Elegant table lamp with fabric shade and ceramic base. Warm ambient lighting for any room.', 199.00, 'RM', 'models/lighting/table-lamp.glb', NULL, NULL, 30.00, 50.00, 30.00, 0.30, 0.50, 0.30, 1, 2),
(6, 'floor-lamp-2', 'Arc Floor Lamp Modern', 'Contemporary arc floor lamp with marble base and adjustable brightness. Statement piece for modern homes.', 599.00, 'RM', 'models/lighting/floor-lamp-2.glb', NULL, NULL, 45.00, 190.00, 45.00, 0.45, 1.90, 0.45, 1, 3);
