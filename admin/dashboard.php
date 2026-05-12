<?php
$pageTitle = 'Admin Dashboard';
require_once '../includes/config.php';
requireAdmin();
$stats = [
  'users' => $conn->query("SELECT COUNT(*) as c FROM users")->fetch_assoc()['c'],
  'products' => $conn->query("SELECT COUNT(*) as c FROM products")->fetch_assoc()['c'],
  'orders' => $conn->query("SELECT COUNT(*) as c FROM orders")->fetch_assoc()['c'],
  'revenue' => $conn->query("SELECT COALESCE(SUM(total),0) as c FROM orders WHERE status!='cancelled'")->fetch_assoc()['c'],
  'pending' => $conn->query("SELECT COUNT(*) as c FROM products WHERE is_approved=0")->fetch_assoc()['c'],
];
$recentOrders = $conn->query("SELECT o.*,u.name as customer FROM orders o JOIN users u ON o.user_id=u.id ORDER BY o.created_at DESC LIMIT 6");
require_once '../includes/header.php';
?>
<div class="container" style="padding:28px 16px 50px;">
  <div style="display:grid;grid-template-columns:220px 1fr;gap:24px;">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div>
      <h2 style="font-size:20px;font-weight:800;margin-bottom:20px;">Admin Dashboard</h2>
      <div class="stats-grid" style="grid-template-columns:repeat(5,1fr);">
        <div class="stat-card"><div class="stat-icon" style="background:#e3f2fd;"><i class="bi bi-people" style="color:var(--primary);"></i></div><div><div class="stat-value"><?= $stats['users'] ?></div><div class="stat-label">Total Users</div></div></div>
        <div class="stat-card" style="border-color:#2e7d32;"><div class="stat-icon" style="background:#e8f5e9;"><i class="bi bi-box-seam" style="color:#2e7d32;"></i></div><div><div class="stat-value"><?= $stats['products'] ?></div><div class="stat-label">Products</div></div></div>
        <div class="stat-card" style="border-color:#f57f17;"><div class="stat-icon" style="background:#fff8e1;"><i class="bi bi-bag-check" style="color:#f57f17;"></i></div><div><div class="stat-value"><?= $stats['orders'] ?></div><div class="stat-label">Orders</div></div></div>
        <div class="stat-card" style="border-color:#1565c0;"><div class="stat-icon" style="background:#e3f2fd;"><i class="bi bi-currency-rupee" style="color:#1565c0;"></i></div><div><div class="stat-value" style="font-size:18px;">PKR=<?= number_format($stats['revenue']) ?></div><div class="stat-label">Revenue</div></div></div>
        <div class="stat-card" style="border-color:#c62828;"><div class="stat-icon" style="background:#ffebee;"><i class="bi bi-clock" style="color:#c62828;"></i></div><div><div class="stat-value"><?= $stats['pending'] ?></div><div class="stat-label">Pending</div></div></div>
      </div>
      <div class="card">
        <div class="card-header"><span class="card-title">Recent Orders</span><a href="<?= SITE_URL ?>/admin/orders.php" class="btn-sm btn-outline-sm">View All</a></div>
        <table class="table">
          <thead><tr><th>Order #</th><th>Customer</th><th>Total</th><th>Payment</th><th>Date</th><th>Status</th></tr></thead>
          <tbody>
          <?php $sc=['pending'=>'badge-warning','processing'=>'badge-info','shipped'=>'badge-info','delivered'=>'badge-success','cancelled'=>'badge-danger'];
          while ($o = $recentOrders->fetch_assoc()): ?>
          <tr><td><strong>#<?= $o['id'] ?></strong></td><td><?= e($o['customer']) ?></td><td>PKR=<?= number_format($o['total']) ?></td><td><?= strtoupper($o['payment_method']) ?></td><td><?= date('d M Y',strtotime($o['created_at'])) ?></td><td><span class="badge <?= $sc[$o['status']] ?>"><?= ucfirst($o['status']) ?></span></td></tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
