CREATE DATABASE IF NOT EXISTS swiftcart_4 CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE swiftcart_4;

CREATE TABLE IF NOT EXISTS roles (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(50) NOT NULL
);
INSERT IGNORE INTO roles (id, name) VALUES (1,'admin'),(2,'seller'),(3,'customer');

CREATE TABLE IF NOT EXISTS users (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  role_id INT DEFAULT 3,
  avatar VARCHAR(500) DEFAULT NULL,
  phone VARCHAR(30) DEFAULT NULL,
  address TEXT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (role_id) REFERENCES roles(id)
);

CREATE TABLE IF NOT EXISTS categories (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(100) NOT NULL,
  slug VARCHAR(100) UNIQUE NOT NULL,
  icon VARCHAR(50) DEFAULT 'bi-grid',
  description TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS products (
  id INT PRIMARY KEY AUTO_INCREMENT,
  seller_id INT NOT NULL,
  category_id INT NOT NULL,
  name VARCHAR(255) NOT NULL,
  slug VARCHAR(255) UNIQUE NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  sale_price DECIMAL(10,2) DEFAULT NULL,
  stock INT DEFAULT 0,
  sku VARCHAR(100) DEFAULT NULL,
  thumbnail VARCHAR(500) DEFAULT NULL,
  rating DECIMAL(2,1) DEFAULT 4.0,
  review_count INT DEFAULT 0,
  is_approved TINYINT DEFAULT 0,
  is_featured TINYINT DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (seller_id) REFERENCES users(id),
  FOREIGN KEY (category_id) REFERENCES categories(id)
);

CREATE TABLE IF NOT EXISTS wishlist (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  UNIQUE KEY unique_wish (user_id, product_id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE IF NOT EXISTS orders (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  total DECIMAL(10,2) NOT NULL,
  status ENUM('pending','processing','shipped','delivered','cancelled') DEFAULT 'pending',
  payment_method ENUM('cod','card') DEFAULT 'cod',
  shipping_address TEXT NOT NULL,
  notes TEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS order_items (
  id INT PRIMARY KEY AUTO_INCREMENT,
  order_id INT NOT NULL,
  product_id INT NOT NULL,
  seller_id INT NOT NULL,
  quantity INT NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (order_id) REFERENCES orders(id),
  FOREIGN KEY (product_id) REFERENCES products(id),
  FOREIGN KEY (seller_id) REFERENCES users(id)
);

CREATE TABLE IF NOT EXISTS cart (
  id INT PRIMARY KEY AUTO_INCREMENT,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT DEFAULT 1,
  UNIQUE KEY unique_cart (user_id, product_id),
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

INSERT IGNORE INTO users (id, name, email, password, role_id) VALUES
(1,'Admin User','admin@swiftcart.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',1),
(2,'Hamza Seller','hamza@swiftcart.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',2),
(3,'Sara Seller','sara@swiftcart.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',2),
(4,'Ali Customer','ali@swiftcart.com','$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',3);

INSERT IGNORE INTO categories (id, name, slug, icon) VALUES
(1,'Electronics','electronics','bi-headphones'),
(2,'Fashion','fashion','bi-bag'),
(3,'Home & Kitchen','home-kitchen','bi-house'),
(4,'Beauty & Health','beauty-health','bi-heart'),
(5,'Sports & Outdoors','sports-outdoors','bi-trophy'),
(6,'Books & Stationery','books-stationery','bi-book'),
(7,'Toys & Games','toys-games','bi-dice-5'),
(8,'Automotive','automotive','bi-car-front');

INSERT IGNORE INTO products (id, seller_id, category_id, name, slug, description, price, sale_price, stock, rating, review_count, is_approved, is_featured, thumbnail) VALUES
(1,2,1,'Sony WH-CH520 Wireless Headphones','sony-wh-ch520','Premium wireless headphones with 50hr battery life and clear sound.',2499.00,1499.00,50,4.5,1200,1,1,'https://picsum.photos/seed/headphones1/400/400'),
(2,2,1,'boAt Watch Storm Smart Watch','boat-watch-storm','Smart watch with health tracking, GPS and notifications.',4999.00,2999.00,30,4.6,890,1,1,'https://picsum.photos/seed/smartwatch1/400/400'),
(3,3,2,'Nike Air Max Running Shoes','nike-air-max','Comfortable air cushion running shoes for everyday wear.',6999.00,4499.00,40,4.7,1760,1,1,'https://picsum.photos/seed/shoes1/400/400'),
(4,2,1,'iPhone 15 (128 GB)','iphone-15-128gb','Latest iPhone with A16 chip, ProCamera system.',79999.00,69999.00,15,4.8,3100,1,1,'https://picsum.photos/seed/phone1/400/400'),
(5,3,3,'Philips Air Fryer HD9252','philips-air-fryer','XL capacity air fryer for healthy oil-free cooking.',10999.00,7999.00,25,4.6,900,1,0,'https://picsum.photos/seed/airfryer/400/400'),
(6,2,2,'American Tourister Backpack','american-tourister-backpack','Durable laptop backpack with water-resistant finish.',1999.00,1299.00,60,4.5,1100,1,0,'https://picsum.photos/seed/backpack/400/400'),
(7,3,4,'Vitamin C Face Serum','vitamin-c-serum','Brightening serum with 20% Vitamin C for glowing skin.',1200.00,899.00,80,4.3,540,1,1,'https://picsum.photos/seed/serum/400/400'),
(8,2,5,'Yoga Mat Premium Non-Slip','yoga-mat-premium','6mm thick eco-friendly non-slip yoga mat.',1499.00,799.00,70,4.4,320,1,0,'https://picsum.photos/seed/yogamat/400/400');
