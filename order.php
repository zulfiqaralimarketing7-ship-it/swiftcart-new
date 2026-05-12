<?php
$pageTitle = 'Order Details';
require_once 'includes/config.php';
requireLogin();
$uid = $_SESSION['user_id'];
$oid = (int)($_GET['id'] ?? 0);
$order = $conn->query("SELECT * FROM orders WHERE id=$oid AND user_id=$uid")->fetch_assoc();
if (!$order) redirect(SITE_URL . '/orders.php');
$items = $conn->query("SELECT oi.*,p.name,p.thumbnail FROM order_items oi JOIN products p ON oi.product_id=p.id WHERE oi.order_id=$oid");
$statusColors = ['pending'=>'badge-warning','processing'=>'badge-info','shipped'=>'badge-info','delivered'=>'badge-success','cancelled'=>'badge-danger'];
require_once 'includes/header.php';
?>
<div class="page-header"><div class="container"><h1>Order #<?= $oid ?> <span class="badge <?= $statusColors[$order['status']] ?? 'badge-secondary' ?>" style="font-size:14px;"><?= ucfirst($order['status']) ?></span></h1></div></div>
<div class="container" style="padding-bottom:50px;">
  <div style="display:grid;grid-template-columns:1fr 320px;gap:24px;align-items:start;">
    <div class="card">
      <div class="card-header"><span class="card-title">Order Items</span></div>
      <table class="table">
        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
        <tbody>
        <?php while ($item = $items->fetch_assoc()): $img = $item['thumbnail'] ?: 'https://picsum.photos/seed/'.$item['product_id'].'/80/80'; ?>
        <tr>
          <td><div style="display:flex;align-items:center;gap:10px;"><img src="<?= e($img) ?>" style="width:50px;height:50px;object-fit:cover;border-radius:8px;"><span style="font-weight:600;"><?= e($item['name']) ?></span></div></td>
          <td>PKR=<?= number_format($item['price']) ?></td>
          <td><?= $item['quantity'] ?></td>
          <td><strong>PKR=<?= number_format($item['price']*$item['quantity']) ?></strong></td>
        </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    </div>
    <div class="order-summary">
      <h3 style="font-size:15px;font-weight:700;margin-bottom:16px;">Order Summary</h3>
      <div class="summary-row"><span>Order Date</span><span><?= date('d M Y', strtotime($order['created_at'])) ?></span></div>
      <div class="summary-row"><span>Payment</span><span><?= strtoupper($order['payment_method']) ?></span></div>
      <div class="summary-row"><span>Status</span><span><span class="badge <?= $statusColors[$order['status']] ?>"><?= ucfirst($order['status']) ?></span></span></div>
      <hr style="margin:12px 0;">
      <div class="summary-row summary-total"><span>Total</span><span>PKR=<?= number_format($order['total']) ?></span></div>
      <div style="margin-top:16px;padding:12px;background:#f8f9fa;border-radius:8px;font-size:13px;"><strong>Shipping to:</strong><br><?= e($order['shipping_address']) ?></div>
      <a href="<?= SITE_URL ?>/orders.php" class="btn-outline" style="display:flex;justify-content:center;margin-top:14px;">← Back to Orders</a>
    </div>
  </div>
</div>
<?php require_once 'includes/footer.php'; ?>
