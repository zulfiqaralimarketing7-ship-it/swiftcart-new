<?php
$pageTitle = 'Cart';
require_once 'includes/config.php';
requireLogin();
$uid = $_SESSION['user_id'];
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $pid = (int)($_POST['product_id'] ?? 0);
    if ($action === 'add' && $pid) {
        $qty = max(1,(int)($_POST['qty'] ?? 1));
        $ex = $conn->query("SELECT id FROM cart WHERE user_id=$uid AND product_id=$pid")->fetch_assoc();
        if ($ex) $conn->query("UPDATE cart SET quantity=quantity+$qty WHERE id={$ex['id']}");
        else $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($uid,$pid,$qty)");
        if (isset($_SERVER['HTTP_REFERER'])) { header('Location: '.$_SERVER['HTTP_REFERER']); exit; }
    } elseif ($action === 'update' && $pid) {
        $qty = max(1,(int)($_POST['qty'] ?? 1));
        $conn->query("UPDATE cart SET quantity=$qty WHERE user_id=$uid AND product_id=$pid");
    } elseif ($action === 'remove' && $pid) {
        $conn->query("DELETE FROM cart WHERE user_id=$uid AND product_id=$pid");
    } elseif ($action === 'clear') {
        $conn->query("DELETE FROM cart WHERE user_id=$uid");
    }
}

$items = $conn->query("SELECT c.*, p.name, p.price, p.sale_price, p.thumbnail, p.stock, p.slug FROM cart c JOIN products p ON c.product_id=p.id WHERE c.user_id=$uid");
$total = 0; $rows = [];
while ($row = $items->fetch_assoc()) { $row['display_price'] = $row['sale_price'] ?? $row['price']; $row['subtotal'] = $row['display_price'] * $row['quantity']; $total += $row['subtotal']; $rows[] = $row; }
require_once 'includes/header.php';
?>
<div class="page-header"><div class="container"><h1><i class="bi bi-cart3" style="color:var(--primary);"></i> My Cart (<?= count($rows) ?> items)</h1></div></div>
<div class="container" style="padding-bottom:50px;">
<?php if (empty($rows)): ?>
  <div style="text-align:center;padding:80px;background:#fff;border-radius:16px;">
    <i class="bi bi-cart-x" style="font-size:64px;color:#ddd;"></i>
    <h3 style="margin-top:20px;color:#888;">Your cart is empty</h3>
    <a href="<?= SITE_URL ?>/products.php" class="btn-primary" style="display:inline-flex;margin-top:20px;">Start Shopping</a>
  </div>
<?php else: ?>
  <div style="display:grid;grid-template-columns:1fr 340px;gap:24px;align-items:start;">
    <div class="card">
      <div class="card-header">
        <span class="card-title">Cart Items</span>
        <form method="POST"><input type="hidden" name="action" value="clear">
          <button type="submit" class="btn-sm btn-danger-sm" data-confirm="Remove all items?"><i class="bi bi-trash"></i> Clear Cart</button>
        </form>
      </div>
      <table class="cart-table">
        <thead><tr><th>Product</th><th>Price</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
        <tbody>
        <?php foreach ($rows as $item): $img = $item['thumbnail'] ?: 'https://picsum.photos/seed/'.$item['product_id'].'/100/100'; ?>
        <tr>
          <td><div class="cart-product"><img src="<?= e($img) ?>" alt=""><div><a href="<?= SITE_URL ?>/product.php?id=<?= $item['product_id'] ?>" style="font-weight:600;font-size:14px;color:#333;"><?= e($item['name']) ?></a></div></div></td>
          <td><span style="font-weight:700;">PKR=<?= number_format($item['display_price']) ?></span><?php if ($item['sale_price']): ?><br><span style="font-size:12px;color:#aaa;text-decoration:line-through;">PKR=<?= number_format($item['price']) ?></span><?php endif; ?></td>
          <td>
            <form method="POST" style="display:flex;align-items:center;gap:6px;">
              <input type="hidden" name="action" value="update"><input type="hidden" name="product_id" value="<?= $item['product_id'] ?>">
              <div class="qty-control"><button type="button" class="qty-btn" data-dir="down">-</button><input type="number" name="qty" class="qty-input" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>"><button type="button" class="qty-btn" data-dir="up">+</button></div>
              <button type="submit" class="btn-sm btn-outline-sm"><i class="bi bi-check2"></i></button>
            </form>
          </td>
          <td><strong>PKR=<?= number_format($item['subtotal']) ?></strong></td>
          <td><form method="POST"><input type="hidden" name="action" value="remove"><input type="hidden" name="product_id" value="<?= $item['product_id'] ?>"><button type="submit" style="background:none;border:none;color:#c62828;cursor:pointer;font-size:16px;"><i class="bi bi-trash"></i></button></form></td>
        </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <div class="order-summary">
      <h3 style="font-size:16px;font-weight:700;margin-bottom:18px;">Order Summary</h3>
      <div class="summary-row"><span>Subtotal (<?= count($rows) ?> items)</span><span>PKR=<?= number_format($total) ?></span></div>
      <div class="summary-row"><span>Delivery</span><span style="color:#2e7d32;"><?= $total >= 499 ? 'FREE' : 'PKR=49' ?></span></div>
      <hr style="margin:14px 0;border-color:#f0f0f0;">
      <div class="summary-row summary-total"><span>Total</span><span>PKR=<?= number_format($total >= 499 ? $total : $total + 49) ?></span></div>
      <?php if ($total < 499): ?><div style="font-size:12px;color:#888;margin-top:6px;">Add PKR=<?= number_format(499 - $total) ?> more for free delivery</div><?php endif; ?>
      <a href="<?= SITE_URL ?>/checkout.php" class="btn-block" style="margin-top:20px;display:flex;justify-content:center;gap:8px;text-decoration:none;"><i class="bi bi-lock"></i> Proceed to Checkout</a>
      <div style="display:flex;justify-content:center;gap:14px;margin-top:16px;">
        <div style="font-size:11px;color:#888;display:flex;align-items:center;gap:4px;"><i class="bi bi-shield-check" style="color:var(--primary);"></i> Secure</div>
        <div style="font-size:11px;color:#888;display:flex;align-items:center;gap:4px;"><i class="bi bi-arrow-repeat" style="color:var(--primary);"></i> Easy Return</div>
      </div>
    </div>
  </div>
<?php endif; ?>
</div>
<?php require_once 'includes/footer.php'; ?>
