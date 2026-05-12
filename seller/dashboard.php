<?php
$pageTitle = 'Seller Dashboard';
require_once '../includes/config.php';
requireSeller();
$uid = $_SESSION['user_id'];
$stats = [
  'products' => $conn->query("SELECT COUNT(*) as c FROM products WHERE seller_id=$uid")->fetch_assoc()['c'],
  'orders' => $conn->query("SELECT COUNT(DISTINCT order_id) as c FROM order_items WHERE seller_id=$uid")->fetch_assoc()['c'],
  'revenue' => $conn->query("SELECT COALESCE(SUM(oi.price*oi.quantity),0) as c FROM order_items oi JOIN orders o ON o.id=oi.order_id WHERE oi.seller_id=$uid AND o.status!='cancelled'")->fetch_assoc()['c'],
  'pending' => $conn->query("SELECT COUNT(*) as c FROM products WHERE seller_id=$uid AND is_approved=0")->fetch_assoc()['c'],
];
$recentOrders = $conn->query("SELECT o.id,o.total,o.status,o.created_at,u.name as customer FROM order_items oi JOIN orders o ON o.id=oi.order_id JOIN users u ON o.user_id=u.id WHERE oi.seller_id=$uid GROUP BY o.id ORDER BY o.created_at DESC LIMIT 5");
$myProducts = $conn->query("SELECT p.*,c.name as cat_name FROM products p JOIN categories c ON p.category_id=c.id WHERE p.seller_id=$uid ORDER BY p.created_at DESC LIMIT 5");
require_once '../includes/header.php';
?>
<div class="container" style="padding:28px 16px 50px;">
  <div style="display:grid;grid-template-columns:220px 1fr;gap:24px;">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div>
      <h2 style="font-size:20px;font-weight:800;margin-bottom:20px;">Seller Dashboard</h2>
      <div class="stats-grid">
        <div class="stat-card"><div class="stat-icon" style="background:#e3f2fd;"><i class="bi bi-box-seam" style="color:var(--primary);"></i></div><div><div class="stat-value"><?= $stats['products'] ?></div><div class="stat-label">Total Products</div></div></div>
        <div class="stat-card" style="border-color:#2e7d32;"><div class="stat-icon" style="background:#e8f5e9;"><i class="bi bi-bag-check" style="color:#2e7d32;"></i></div><div><div class="stat-value"><?= $stats['orders'] ?></div><div class="stat-label">Total Orders</div></div></div>
        <div class="stat-card" style="border-color:#f57f17;"><div class="stat-icon" style="background:#fff8e1;"><i class="bi bi-currency-rupee" style="color:#f57f17;"></i></div><div><div class="stat-value">PKR=<?= number_format($stats['revenue']) ?></div><div class="stat-label">Total Revenue</div></div></div>
        <div class="stat-card" style="border-color:#c62828;"><div class="stat-icon" style="background:#ffebee;"><i class="bi bi-clock" style="color:#c62828;"></i></div><div><div class="stat-value"><?= $stats['pending'] ?></div><div class="stat-label">Pending Approval</div></div></div>
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">
        <div class="card">
          <div class="card-header"><span class="card-title">Recent Orders</span></div>
          <table class="table">
            <thead><tr><th>Order</th><th>Customer</th><th>Total</th><th>Status</th></tr></thead>
            <tbody>
            <?php $sc2 = ['pending'=>'badge-warning','processing'=>'badge-info','shipped'=>'badge-info','delivered'=>'badge-success','cancelled'=>'badge-danger'];
            while ($o = $recentOrders->fetch_assoc()): ?>
            <tr><td>#<?= $o['id'] ?></td><td><?= e($o['customer']) ?></td><td>PKR=<?= number_format($o['total']) ?></td><td><span class="badge <?= $sc2[$o['status']] ?>"><?= ucfirst($o['status']) ?></span></td></tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <div class="card">
          <div class="card-header"><span class="card-title">Recent Products</span><a href="<?= SITE_URL ?>/seller/product-create.php" class="btn-sm btn-primary-sm"><i class="bi bi-plus"></i> Add</a></div>
          <table class="table">
            <thead><tr><th>Product</th><th>Price</th><th>Status</th></tr></thead>
            <tbody>
            <?php while ($p = $myProducts->fetch_assoc()): $appMap=[0=>'badge-warning',1=>'badge-success',2=>'badge-danger']; $appText=[0=>'Pending',1=>'Approved',2=>'Rejected']; ?>
            <tr><td><?= e(mb_strimwidth($p['name'],0,22,'...')) ?></td><td>PKR=<?= number_format($p['sale_price'] ?? $p['price']) ?></td><td><span class="badge <?= $appMap[$p['is_approved']] ?>"><?= $appText[$p['is_approved']] ?></span></td></tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
