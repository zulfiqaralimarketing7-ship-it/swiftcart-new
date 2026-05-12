<?php if (!defined('SITE_URL')) { require_once __DIR__ . '/config.php'; } ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= e($pageTitle ?? 'SwiftCart') ?> - Fast Shopping, Fast Delivery</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">

</head>
<body>
<!-- TOP BAR -->
<div class="top-bar">
  <div class="container">
    <div style="display:flex;justify-content:space-between;align-items:center;">
      <span><i class="bi bi-telephone-fill"></i> Support: +92-300-1234567 &nbsp;|&nbsp; <i class="bi bi-envelope-fill"></i> help@swiftcart.com</span>
      <span>Free Delivery on orders over PKR=499 &nbsp;|&nbsp; <a href="<?= SITE_URL ?>/pages/about.php">About Us</a></span>
    </div>
  </div>
</div>
<!-- MAIN HEADER -->
<header class="main-header">
  <div class="container">
    <div class="header-inner">
      <!-- Logo -->
      <a href="<?= SITE_URL ?>/" class="logo">
        <i class="bi bi-cart3"></i><span>Swift<span style="color:var(--primary)">Cart</span></span>
      </a>
      <!-- Search -->
      <form class="search-box" action="<?= SITE_URL ?>/products.php" method="GET" style="flex:1;max-width:580px;">
        <select name="category_id">
          <option value="">All Categories</option>
          <?php
          global $conn;
          $cats = $conn->query("SELECT id, name FROM categories ORDER BY name");
          while ($c = $cats->fetch_assoc()):
          ?>
          <option value="<?= $c['id'] ?>" <?= (($_GET['category_id'] ?? '') == $c['id']) ? 'selected' : '' ?>><?= e($c['name']) ?></option>
          <?php endwhile; ?>
        </select>
        <input type="text" name="q" placeholder="Search for products, brands and more..." value="<?= e($_GET['q'] ?? '') ?>">
        <button type="submit"><i class="bi bi-search"></i></button>
      </form>
      <!-- Actions -->
      <div class="header-actions">
        <a href="<?= SITE_URL ?>/orders.php" class="header-action-btn">
          <i class="bi bi-box-seam"></i><span>Track Order</span>
        </a>
        <a href="<?= SITE_URL ?>/wishlist.php" class="header-action-btn">
          <i class="bi bi-heart"></i><span>Wishlist</span>
          <?php $wc = getWishlistCount($conn); if ($wc > 0): ?><span class="action-badge"><?= $wc ?></span><?php endif; ?>
        </a>
        <a href="<?= SITE_URL ?>/cart.php" class="header-action-btn">
          <i class="bi bi-cart3"></i><span>Cart</span>
          <?php $cc = getCartCount($conn); if ($cc > 0): ?><span class="action-badge"><?= $cc ?></span><?php endif; ?>
        </a>
      </div>
    </div>
  </div>
  <!-- NAVBAR -->
  <nav class="main-nav">
    <div class="container">
      <div class="nav-inner">
        <button class="nav-categories-btn" onclick="toggleCategoryMenu()">
          <i class="bi bi-grid-fill"></i> All Categories <i class="bi bi-chevron-down" style="font-size:10px;"></i>
        </button>
        <ul class="nav-links">
          <li><a href="<?= SITE_URL ?>/" class="<?= (basename($_SERVER['PHP_SELF']) == 'index.php' || basename($_SERVER['PHP_SELF']) == '') ? 'active' : '' ?>">Home</a></li>
          <li><a href="<?= SITE_URL ?>/products.php">Shop</a></li>
          <li><a href="<?= SITE_URL ?>/products.php?deal=1">Deals</a></li>
          <li><a href="<?= SITE_URL ?>/products.php?sort=newest">New Arrivals</a></li>
          <li><a href="<?= SITE_URL ?>/pages/about.php">Contact</a></li>
        </ul>
        <div class="nav-right">
          <?php if (isLoggedIn()): ?>
          <div class="nav-account" style="position:relative;" onclick="toggleAccountMenu()">
            <i class="bi bi-person-circle" style="font-size:18px;"></i>
            <div>
              <div style="font-size:10px;opacity:.7;">Hello,</div>
              <div style="font-size:13px;font-weight:600;"><?= e($_SESSION['user_name'] ?? 'User') ?></div>
            </div>
            <i class="bi bi-chevron-down" style="font-size:10px;"></i>
            <div id="accountMenu" style="display:none;position:absolute;top:100%;right:0;background:#fff;border-radius:10px;box-shadow:0 8px 30px rgba(0,0,0,.15);min-width:180px;z-index:1000;overflow:hidden;">
              <a href="<?= SITE_URL ?>/profile.php" style="display:flex;align-items:center;gap:8px;padding:11px 16px;color:#333;font-size:13px;border-bottom:1px solid #f0f0f0;"><i class="bi bi-person" style="color:var(--primary);"></i> My Profile</a>
              <a href="<?= SITE_URL ?>/orders.php" style="display:flex;align-items:center;gap:8px;padding:11px 16px;color:#333;font-size:13px;border-bottom:1px solid #f0f0f0;"><i class="bi bi-box-seam" style="color:var(--primary);"></i> My Orders</a>
              <?php if (isSeller()): ?>
              <a href="<?= SITE_URL ?>/seller/dashboard.php" style="display:flex;align-items:center;gap:8px;padding:11px 16px;color:#333;font-size:13px;border-bottom:1px solid #f0f0f0;"><i class="bi bi-shop" style="color:#f57f17;"></i> Seller Panel</a>
              <?php endif; ?>
              <?php if (isAdmin()): ?>
              <a href="<?= SITE_URL ?>/admin/dashboard.php" style="display:flex;align-items:center;gap:8px;padding:11px 16px;color:#333;font-size:13px;border-bottom:1px solid #f0f0f0;"><i class="bi bi-shield-check" style="color:#2e7d32;"></i> Admin Panel</a>
              <?php endif; ?>
              <a href="<?= SITE_URL ?>/logout.php" style="display:flex;align-items:center;gap:8px;padding:11px 16px;color:#c62828;font-size:13px;"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
          </div>
          <?php else: ?>
          <div class="nav-account">
            <i class="bi bi-person-circle" style="font-size:18px;"></i>
            <div>
              <div style="font-size:10px;opacity:.7;">Hello, Guest</div>
              <div style="display:flex;gap:8px;align-items:center;">
                <a href="<?= SITE_URL ?>/login.php" style="color:#fff;font-size:12px;font-weight:600;">Login</a>
                <span style="opacity:.4;">|</span>
                <a href="<?= SITE_URL ?>/register.php" style="color:#fff;font-size:12px;font-weight:600;">Register</a>
              </div>
            </div>
          </div>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </nav>
  <!-- Category Dropdown -->
  <div id="categoryMenu" style="display:none;position:absolute;top:100%;left:0;right:0;background:#fff;box-shadow:0 8px 30px rgba(0,0,0,.12);z-index:998;border-top:1px solid #eee;">
    <div class="container" style="padding:20px 16px;">
      <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:8px;">
        <?php
        $cats2 = $conn->query("SELECT id, name, icon FROM categories ORDER BY name");
        while ($c2 = $cats2->fetch_assoc()):
        ?>
        <a href="<?= SITE_URL ?>/products.php?category_id=<?= $c2['id'] ?>" style="display:flex;align-items:center;gap:10px;padding:10px 14px;border-radius:8px;color:#333;font-size:14px;transition:all .2s;" onmouseover="this.style.background='#e3f2fd'" onmouseout="this.style.background='none'">
          <i class="bi <?= e($c2['icon']) ?>" style="color:var(--primary);font-size:18px;"></i> <?= e($c2['name']) ?>
        </a>
        <?php endwhile; ?>
      </div>
    </div>
  </div>
</header>
<script>
function toggleCategoryMenu(){var m=document.getElementById('categoryMenu');m.style.display=m.style.display==='none'?'block':'none';}
function toggleAccountMenu(){var m=document.getElementById('accountMenu');if(m)m.style.display=m.style.display==='none'?'block':'none';}
document.addEventListener('click',function(e){
  var cm=document.getElementById('categoryMenu');
  if(cm&&!e.target.closest('.nav-categories-btn')&&!e.target.closest('#categoryMenu'))cm.style.display='none';
  var am=document.getElementById('accountMenu');
  if(am&&!e.target.closest('.nav-account'))am.style.display='none';
});
</script>
