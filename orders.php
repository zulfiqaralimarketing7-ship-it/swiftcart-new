<?php
$pageTitle = 'My Orders';
require_once 'includes/config.php';
requireLogin();
$uid = $_SESSION['user_id'];
$orders = $conn->query("SELECT o.*, COUNT(oi.id) as item_count FROM orders o LEFT JOIN order_items oi ON o.id=oi.order_id WHERE o.user_id=$uid GROUP BY o.id ORDER BY o.created_at DESC");
require_once 'includes/header.php';
$statusColors = ['pending'=>'badge-warning','processing'=>'badge-info','shipped'=>'badge-info','delivered'=>'badge-success','cancelled'=>'badge-danger'];
?>
<div class="page-header"><div class="container"><h1><i class="bi bi-box-seam" style="color:var(--primary);"></i> My Orders</h1></div></div>
<div class="container" style="padding-bottom:50px;">
<?php if ($orders->num_rows === 0): ?>
  <div style="text-align:center;padding:80px;background:#fff;border-radius:16px;">
    <i class="bi bi-bag-x" style="font-size:64px;color:#ddd;"></i>
    <h3 style="margin-top:20px;color:#888;">No orders yet</h3>
    <a href="<?= SITE_URL ?>/products.php" class="btn-primary" style="display:inline-flex;margin-top:20px;">Start Shopping</a>
  </div>
<?php else: ?>
  <div class="card">
    <div class="card-header"><span class="card-title">Order History</span></div>
    <table class="table">
      <thead><tr><th>Order #</th><th>Date</th><th>Items</th><th>Total</th><th>Payment</th><th>Status</th><th>Action</th></tr></thead>
      <tbody>
      <?php while ($o = $orders->fetch_assoc()): ?>
      <tr>
        <td><strong>#<?= $o['id'] ?></strong></td>
        <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
        <td><?= $o['item_count'] ?> item(s)</td>
        <td><strong>PKR=<?= number_format($o['total']) ?></strong></td>
        <td><span class="badge badge-secondary"><?= strtoupper($o['payment_method']) ?></span></td>
        <td><span class="badge <?= $statusColors[$o['status']] ?? 'badge-secondary' ?>"><?= ucfirst($o['status']) ?></span></td>
        <td><a href="<?= SITE_URL ?>/order.php?id=<?= $o['id'] ?>" class="btn-sm btn-primary-sm"><i class="bi bi-eye"></i> View</a></td>
      </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
</div>
<?php require_once 'includes/footer.php'; ?>
