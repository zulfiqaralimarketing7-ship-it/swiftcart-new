<aside>
  <div class="sidebar">
    <div style="padding:14px 12px;background:var(--primary);border-radius:8px;margin-bottom:12px;text-align:center;">
      <i class="bi bi-shield-check" style="font-size:24px;color:#fff;"></i>
      <div style="color:#fff;font-weight:700;font-size:14px;margin-top:4px;">Admin Panel</div>
    </div>
    <a href="<?= SITE_URL ?>/admin/dashboard.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF'])=='dashboard.php'?'active':'' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a>
    <a href="<?= SITE_URL ?>/admin/users.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF'])=='users.php'?'active':'' ?>"><i class="bi bi-people"></i> Users</a>
    <a href="<?= SITE_URL ?>/admin/products.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF'])=='products.php'?'active':'' ?>"><i class="bi bi-box-seam"></i> Products</a>
    <a href="<?= SITE_URL ?>/admin/categories.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF'])=='categories.php'?'active':'' ?>"><i class="bi bi-grid"></i> Categories</a>
    <a href="<?= SITE_URL ?>/admin/orders.php" class="sidebar-link <?= basename($_SERVER['PHP_SELF'])=='orders.php'?'active':'' ?>"><i class="bi bi-bag-check"></i> Orders</a>
    <div style="padding:8px 12px;"><a href="<?= SITE_URL ?>/logout.php" class="sidebar-link" style="color:#c62828;"><i class="bi bi-box-arrow-right"></i> Logout</a></div>
  </div>
</aside>
