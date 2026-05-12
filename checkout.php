<?php
$pageTitle = 'Checkout';
require_once 'includes/config.php';
requireLogin();
$uid = $_SESSION['user_id'];
$msg = ''; $orderId = null;

// Cart items
$items = $conn->query("SELECT c.*,p.name,p.price,p.sale_price,p.thumbnail,p.stock,p.seller_id FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_id=$uid");
$rows = []; $subtotal = 0;
while ($row = $items->fetch_assoc()) { $row['display_price'] = $row['sale_price'] ?? $row['price']; $row['sub'] = $row['display_price']*$row['quantity']; $subtotal += $row['sub']; $rows[] = $row; }
if (empty($rows)) redirect(SITE_URL . '/cart.php');

$delivery = $subtotal >= 499 ? 0 : 49;
$total = $subtotal + $delivery;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string($_POST['name'] ?? '');
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $address = $conn->real_escape_string($_POST['address'] ?? '');
    $city = $conn->real_escape_string($_POST['city'] ?? '');
    $payment = in_array($_POST['payment'] ?? '', ['cod','card']) ? $_POST['payment'] : 'cod';
    if ($name && $phone && $address) {
        $shipping = "$name, $phone, $address, $city";
        $conn->query("INSERT INTO orders (user_id, total, payment_method, shipping_address) VALUES ($uid,$total,'$payment','$shipping')");
        $oid = $conn->insert_id;
        foreach ($rows as $item) {
            $sid = $item['seller_id'];
            $pid = $item['product_id'];
            $qty = $item['quantity'];
            $price = $item['display_price'];
            $conn->query("INSERT INTO order_items (order_id,product_id,seller_id,quantity,price) VALUES ($oid,$pid,$sid,$qty,$price)");
            $conn->query("UPDATE products SET stock=GREATEST(0,stock-$qty) WHERE id=$pid");
        }
        $conn->query("DELETE FROM cart WHERE user_id=$uid");
        $orderId = $oid;
    }
}

$user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
require_once 'includes/header.php';
?>
<div class="page-header"><div class="container"><h1><i class="bi bi-bag-check" style="color:var(--primary);"></i> Checkout</h1></div></div>
<div class="container" style="padding-bottom:50px;">
<?php if ($orderId): ?>
  <div style="text-align:center;padding:60px;background:#fff;border-radius:16px;max-width:600px;margin:0 auto;">
    <div style="width:80px;height:80px;background:#e8f5e9;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;"><i class="bi bi-check-lg" style="font-size:40px;color:#2e7d32;"></i></div>
    <h2 style="color:#2e7d32;font-weight:800;">Order Placed Successfully!</h2>
    <p style="color:#666;margin-top:10px;">Order #<?= $orderId ?> has been placed. You will receive updates soon.</p>
    <div style="display:flex;gap:12px;justify-content:center;margin-top:28px;">
      <a href="<?= SITE_URL ?>/orders.php" class="btn-primary"><i class="bi bi-box-seam"></i> Track Order</a>
      <a href="<?= SITE_URL ?>/" class="btn-outline">Continue Shopping</a>
    </div>
  </div>
<?php else: ?>
  <div style="display:grid;grid-template-columns:1fr 360px;gap:24px;align-items:start;">
    <div>
      <div class="card" style="padding:28px;">
        <h3 style="font-size:16px;font-weight:700;margin-bottom:20px;"><i class="bi bi-geo-alt" style="color:var(--primary);"></i> Delivery Details</h3>
        <form method="POST" id="checkoutForm">
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group"><label class="form-label">Full Name *</label><input type="text" name="name" class="form-control" value="<?= e($user['name']) ?>" required></div>
            <div class="form-group"><label class="form-label">Phone *</label><input type="text" name="phone" class="form-control" value="<?= e($user['phone'] ?? '') ?>" required></div>
          </div>
          <div class="form-group"><label class="form-label">Address *</label><input type="text" name="address" class="form-control" placeholder="Street, House No." value="<?= e($user['address'] ?? '') ?>" required></div>
          <div class="form-group"><label class="form-label">City</label><input type="text" name="city" class="form-control" placeholder="City, State, PIN"></div>
          <h3 style="font-size:16px;font-weight:700;margin:24px 0 16px;"><i class="bi bi-credit-card" style="color:var(--primary);"></i> Payment Method</h3>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <label style="border:2px solid #ddd;border-radius:10px;padding:16px;cursor:pointer;display:flex;align-items:center;gap:10px;" onclick="this.style.borderColor='var(--primary)'">
              <input type="radio" name="payment" value="cod" checked style="accent-color:var(--primary);">
              <div><div style="font-weight:600;font-size:14px;">Cash on Delivery</div><div style="font-size:12px;color:#888;">Pay when you receive</div></div>
            </label>
            <label style="border:2px solid #ddd;border-radius:10px;padding:16px;cursor:pointer;display:flex;align-items:center;gap:10px;" onclick="this.style.borderColor='var(--primary)'">
              <input type="radio" name="payment" value="card" style="accent-color:var(--primary);">
              <div><div style="font-weight:600;font-size:14px;">Card / JAZZCASH / EASYPAISA</div><div style="font-size:12px;color:#888;">Secure online payment</div></div>
            </label>
          </div>
        </form>
      </div>
    </div>
    <div class="order-summary">
      <h3 style="font-size:16px;font-weight:700;margin-bottom:18px;">Order Summary</h3>
      <?php foreach ($rows as $item): $img = $item['thumbnail'] ?: 'https://picsum.photos/seed/'.$item['product_id'].'/80/80'; ?>
      <div style="display:flex;gap:10px;margin-bottom:12px;padding-bottom:12px;border-bottom:1px solid #f0f0f0;">
        <img src="<?= e($img) ?>" style="width:50px;height:50px;border-radius:8px;object-fit:cover;">
        <div style="flex:1;"><div style="font-size:12px;font-weight:600;line-height:1.4;"><?= e($item['name']) ?></div><div style="font-size:12px;color:#888;">Qty: <?= $item['quantity'] ?></div></div>
        <div style="font-weight:700;font-size:13px;">PKR=<?= number_format($item['sub']) ?></div>
      </div>
      <?php endforeach; ?>
      <div class="summary-row"><span>Subtotal</span><span>PKR=<?= number_format($subtotal) ?></span></div>
      <div class="summary-row"><span>Delivery</span><span style="color:#2e7d32;"><?= $delivery > 0 ? 'PKR='.$delivery : 'FREE' ?></span></div>
      <hr style="margin:12px 0;border-color:#f0f0f0;">
      <div class="summary-row summary-total"><span>Total</span><span>PKR=<?= number_format($total) ?></span></div>
      <button type="submit" form="checkoutForm" class="btn-block" style="margin-top:16px;"><i class="bi bi-lock"></i> Place Order (PKR=<?= number_format($total) ?>)</button>
    </div>
  </div>
<?php endif; ?>
</div>
<?php require_once 'includes/footer.php'; ?>
