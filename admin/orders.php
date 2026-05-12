<?php
$pageTitle = 'All Orders';
require_once '../includes/config.php';
requireAdmin();
if (isset($_GET['status']) && isset($_GET['id'])) {
    $s = $conn->real_escape_string($_GET['status']);
    $oid = (int)$_GET['id'];
    $allowed = ['pending','processing','shipped','delivered','cancelled'];
    if (in_array($s, $allowed)) $conn->query("UPDATE orders SET status='$s' WHERE id=$oid");
    redirect(SITE_URL.'/admin/orders.php');
}
$orders = $conn->query("SELECT o.*,u.name as customer,u.email as cemail,COUNT(oi.id) as item_count FROM orders o JOIN users u ON o.user_id=u.id LEFT JOIN order_items oi ON oi.order_id=o.id GROUP BY o.id ORDER BY o.created_at DESC");
require_once '../includes/header.php';
$sc=['pending'=>'badge-warning','processing'=>'badge-info','shipped'=>'badge-info','delivered'=>'badge-success','cancelled'=>'badge-danger'];
$statuses=['pending','processing','shipped','delivered','cancelled'];
?>
<div class="container" style="padding:28px 16px 50px;">
  <div style="display:grid;grid-template-columns:220px 1fr;gap:24px;">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div>
      <div class="card">
        <div class="card-header"><span class="card-title">All Orders (<?= $orders->num_rows ?>)</span></div>
        <table class="table">
          <thead><tr><th>Order #</th><th>Customer</th><th>Items</th><th>Total</th><th>Payment</th><th>Date</th><th>Status</th><th>Update</th></tr></thead>
          <tbody>
          <?php while ($o = $orders->fetch_assoc()): ?>
          <tr>
            <td><strong>#<?= $o['id'] ?></strong></td>
            <td><?= e($o['customer']) ?><br><small style="color:#888;"><?= e($o['cemail']) ?></small></td>
            <td><?= $o['item_count'] ?></td>
            <td><strong>PKR=<?= number_format($o['total']) ?></strong></td>
            <td><?= strtoupper($o['payment_method']) ?></td>
            <td><?= date('d M Y', strtotime($o['created_at'])) ?></td>
            <td><span class="badge <?= $sc[$o['status']] ?>"><?= ucfirst($o['status']) ?></span></td>
            <td>
              <select class="form-select" style="padding:4px 8px;font-size:12px;width:auto;" onchange="location='?id=<?= $o['id'] ?>&status='+this.value">
                <?php foreach ($statuses as $st): ?><option value="<?= $st ?>" <?= $o['status']===$st?'selected':'' ?>><?= ucfirst($st) ?></option><?php endforeach; ?>
              </select>
            </td>
          </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
