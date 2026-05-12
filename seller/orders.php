<?php
$pageTitle = 'Seller Orders';
require_once '../includes/config.php';
requireSeller();
$uid = $_SESSION['user_id'];
$orders = $conn->query("SELECT o.*,u.name as customer,u.email as cemail,COUNT(oi2.id) as item_count FROM order_items oi JOIN orders o ON o.id=oi.order_id JOIN users u ON o.user_id=u.id LEFT JOIN order_items oi2 ON oi2.order_id=o.id WHERE oi.seller_id=$uid GROUP BY o.id ORDER BY o.created_at DESC");
require_once '../includes/header.php';
$sc=['pending'=>'badge-warning','processing'=>'badge-info','shipped'=>'badge-info','delivered'=>'badge-success','cancelled'=>'badge-danger'];
?>
<div class="container" style="padding:28px 16px 50px;">
  <div style="display:grid;grid-template-columns:220px 1fr;gap:24px;">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div>
      <div class="card">
        <div class="card-header"><span class="card-title">Orders (<?= $orders->num_rows ?>)</span></div>
        <table class="table">
          <thead><tr><th>Order #</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Date</th><th>Status</th></tr></thead>
          <tbody>
          <?php if ($orders->num_rows === 0): ?><tr><td colspan="7" style="text-align:center;padding:40px;color:#888;">No orders yet</td></tr>
          <?php else: while ($o = $orders->fetch_assoc()): ?>
          <tr>
            <td><strong>#<?= $o['id'] ?></strong></td>
            <td><?= e($o['customer']) ?><br><span style="font-size:11px;color:#888;"><?= e($o['cemail']) ?></span></td>
            <td><?= $o['item_count'] ?></td>
            <td><strong>PKR=<?= number_format($o['total']) ?></strong></td>
            <td><span class="badge badge-secondary"><?= strtoupper($o['payment_method']) ?></span></td>
            <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
            <td><span class="badge <?= $sc[$o['status']] ?>"><?= ucfirst($o['status']) ?></span></td>
          </tr>
          <?php endwhile; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
