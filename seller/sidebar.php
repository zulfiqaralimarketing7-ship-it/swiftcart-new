<aside>
  <div class="sidebar">
    <div style="text-align:center;padding:16px 0 12px;">
      <img src="<?= $_SESSION['user_avatar'] ?? 'https://ui-avatars.com/api/?name='.urlencode($_SESSION['user_name'] ?? 'S').'&background=1565c0&color=fff&size=60' ?>" style="width:60px;height:60px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);" alt="">
      <div style="font-weight:700;font-size:14px;margin-top:8px;color:#1a1a2e;"><?= e($_SESSION['user_name'] ?? '') ?></div>
      <div style="font-size:11px;color:#888;"><?= ucfirst($_SESSION['role_name'] ?? 'Seller') ?></div>
    </div>
    <div class="sidebar-title">Seller Panel</div>
    <a href="<?= SITE_URL ?>/seller/dashboard.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="<?= SITE_URL ?>/seller/products.php" class="sidebar-link <?= in_array(basename($_SERVER['PHP_SELF']),['products.php','product-create.php','product-edit.php'])?'active':'' ?>"><i class="bi bi-box-seam"></i> My Products</a>
    <a href="<?= SITE_URL ?>/seller/orders.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF'])=='orders.php'?'active':'' ?>"><i class="bi bi-bag-check"></i> Orders</a>
    <a href="<?= SITE_URL ?>/profile.php" class="sidebar-link"><i class="bi bi-person"></i> Profile</a>
    <?php if (isAdmin()): ?>
    <div class="sidebar-title">Admin</div>
    <a href="<?= SITE_URL ?>/admin/dashboard.php" class="sidebar-link"><i class="bi bi-shield-check"></i> Admin Panel</a>
    <?php endif; ?>
    <div style="padding:8px 12px;"><a href="<?= SITE_URL ?>/logout.php" class="sidebar-link" style="color:#c62828;"><i class="bi bi-box-arrow-right"></i> Logout</a></div>
  </div>
</aside>
