SwiftCart - PHP E-Commerce Website
====================================
Design: Matches provided screenshot with blue header, hero section,
        category grid, promo banners, product cards with ratings, dark footer.

INSTALLATION:
1. Extract this ZIP into your XAMPP htdocs folder: C:/xampp/htdocs/swiftcart-php/
2. Start XAMPP Apache + MySQL
3. Open phpMyAdmin (http://localhost/phpmyadmin)
4. Create database named: swiftcart
5. Import database.sql into that database
6. Open: http://localhost/swiftcart-php/

DEMO ACCOUNTS (Password for all: password)
-------------------------------------------
Admin    : admin@swiftcart.com
Seller 1 : hamza@swiftcart.com
Seller 2 : sara@swiftcart.com
Customer : ali@swiftcart.com

FEATURES:
- Responsive design matching screenshot (blue header, hero, categories, banners)
- Customer: Browse, search, filter, cart, checkout (COD/Card), order tracking, wishlist, profile
- Seller: Dashboard, product CRUD with image upload, orders view
- Admin: Dashboard stats, approve/reject products, manage categories, users, orders
- Image upload for product thumbnails and profile avatars
- Star ratings display on product cards

FOLDER STRUCTURE:
swiftcart-php/
  index.php           - Homepage
  products.php        - Product listing with filters
  product.php         - Product detail
  cart.php            - Shopping cart
  checkout.php        - Checkout & order placement
  orders.php          - Order history
  order.php           - Order detail
  profile.php         - User profile & avatar upload
  wishlist.php        - Wishlist
  login.php           - Login
  register.php        - Register
  logout.php          - Logout
  seller/             - Seller panel (dashboard, products, orders)
  admin/              - Admin panel (dashboard, users, products, categories, orders)
  includes/           - config.php, header.php, footer.php
  assets/css/         - style.css (complete custom CSS)
  assets/js/          - main.js (interactions)
  uploads/products/   - Uploaded product images
  uploads/avatars/    - Uploaded user avatars
  database.sql        - Database schema + demo data
