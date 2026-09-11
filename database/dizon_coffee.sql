-- Dizon Coffee Roasters — database schema
-- Import this file in phpMyAdmin, or run: mysql -u root -p < database/dizon_coffee.sql

CREATE DATABASE IF NOT EXISTS dizon_coffee CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE dizon_coffee;

-- ---------------------------------------------------------------
-- users
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('customer', 'admin') NOT NULL DEFAULT 'customer',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------------
-- menu_items
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description VARCHAR(255) DEFAULT NULL,
    price DECIMAL(10,2) NOT NULL,
    category VARCHAR(50) NOT NULL,
    image VARCHAR(255) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- ---------------------------------------------------------------
-- orders
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    status ENUM('pending', 'preparing', 'out for delivery', 'completed', 'cancelled') NOT NULL DEFAULT 'pending',
    total DECIMAL(10,2) NOT NULL,
    payment_method ENUM('cod', 'gcash') NOT NULL DEFAULT 'cod',
    reference_code VARCHAR(50) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- ---------------------------------------------------------------
-- order_items
-- ---------------------------------------------------------------
CREATE TABLE IF NOT EXISTS order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    menu_item_id INT NOT NULL,
    quantity INT NOT NULL,
    price_at_order DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE,
    FOREIGN KEY (menu_item_id) REFERENCES menu_items(id) ON DELETE RESTRICT
);

-- ---------------------------------------------------------------
-- Seed data: menu items (from the "What We Pour" section of the mockup)
-- ---------------------------------------------------------------
INSERT INTO menu_items (name, description, price, category, image) VALUES
('House Blend', 'Our signature everyday roast. Balanced, smooth, easy to love.', 180.00, 'Coffee', 'assets/img/Menu/House_Blend.jpeg'),
('Single Origin', 'Rotating regional beans, roasted for origin character.', 220.00, 'Coffee', 'assets/img/Menu/Single_orgin.jpeg'),
('Cold Brew', 'Slow-steeped for 18 hours. Smooth, naturally sweet.', 150.00, 'Cold Brew', 'assets/img/Menu/Cold_Brew.jpeg'),
('Coffee Beans', 'Whole beans, freshly roasted. Great for grinding at home.', 260.00, 'Beans', 'assets/img/Menu/Coffee_bean.jpeg');

-- ---------------------------------------------------------------
-- Seed data: admin account (email must match ADMIN_EMAIL in config/config.php)
-- Password is "admin123" — change it after first login.
-- Hash generated with password_hash('admin123', PASSWORD_DEFAULT)
-- ---------------------------------------------------------------
INSERT INTO users (name, email, password, role) VALUES
('Dizon Admin', 'admin@dizoncoffee.com', '$2y$10$z7ZQgycfeabvm6SDSZob1e9N9/GQ1UWsWiCCXvUqnCjAdJaywc86u', 'admin');
