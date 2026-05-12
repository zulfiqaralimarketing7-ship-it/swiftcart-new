<?php
$pageTitle = 'Wishlist';
require_once 'includes/config.php';
requireLogin();
$uid = $_SESSION['user_id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && isset($_POST['product_id'])) {
    $pid = (int)$_POST['product_id'];
    $ex = $conn->query("SELECT id FROM wishlist WHERE user_id=$uid AND product_id=$pid")->num_rows;
    if ($ex) $conn->query("DELETE FROM wishlist WHERE user_id=$uid AND product_id=$pid");
    else $conn->query("INSERT IGNORE INTO wishlist (user_id,product_id) VALUES ($uid,$pid)");
    if (isset($_SERVER['HTTP_REFERER'])) { header('Location: '.$_SERVER['HTTP_REFERER']); exit; }
}
$items = $conn->query("SELECT p.*,c.name as cat_name FROM wishlist w JOIN products p ON w.product_id=p.id JOIN categories c ON p.category_id=c.id WHERE w.user_id=$uid");
require_once 'includes/header.php';
?>
<div class="page-header"><div class="container"><h1><i class="bi bi-heart" style="color:var(--danger);"></i> My Wishlist</h1></div></div>
<div class="container" style="padding-bottom:50px;">
<?php if ($items->num_rows === 0): ?>
  <div style="text-align:center;padding:80px;background:#fff;border-radius:16px;">
    <i class="bi bi-heart" style="font-size:64px;color:#ddd;"></i>
    <h3 style="margin-top:20px;color:#888;">Your wishlist is empty</h3>
    <a href="<?= SITE_URL ?>/products.php" class="btn-primary" style="display:inline-flex;margin-top:20px;">Browse Products</a>
  </div>
<?php else: ?>
  <div class="products-grid">
    <?php while ($p = $items->fetch_assoc()):
      $discount = ($p['sale_price'] && $p['price'] > 0) ? round((1 - $p['sale_price']/$p['price'])*100) : 0;
      $displayPrice = $p['sale_price'] ?? $p['price'];
      $img = $p['thumbnail'] ?: 'https://picsum.photos/seed/'.$p['id'].'/400/400';
    ?>
    <div class="product-card">
      <div class="product-img-wrap">
        <a href="<?= SITE_URL ?>/product.php?id=<?= $p['id'] ?>"><img src="<?= e($img) ?>" alt="<?= e($p['name']) ?>" loading="lazy"></a>
        <?php if ($discount > 0): ?><div class="product-badge"><span class="badge-sale">-<?= $discount ?>%</span></div><?php endif; ?>
        <form method="POST" style="display:inline;">
          <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
          <input type="hidden" name="action" value="toggle">
          <button type="submit" class="product-wishlist active"><i class="bi bi-heart-fill"></i></button>
        </form>
      </div>
      <div class="product-body">
        <div class="product-category"><?= e($p['cat_name']) ?></div>
        <a href="<?= SITE_URL ?>/product.php?id=<?= $p['id'] ?>"><div class="product-name"><?= e($p['name']) ?></div></a>
        <div class="product-stars"><?= renderStars($p['rating']) ?><span><?= number_format($p['rating'],1) ?></span></div>
        <div class="product-price">
          <span class="price-sale">₹<?= number_format($displayPrice) ?></span>
          <?php if ($p['sale_price']): ?><span class="price-original">₹<?= number_format($p['price']) ?></span><?php endif; ?>
          <?php if ($discount > 0): ?><span class="price-discount">-<?= $discount ?>%</span><?php endif; ?>
        </div>
        <form method="POST" action="<?= SITE_URL ?>/cart.php">
          <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
          <input type="hidden" name="action" value="add">
          <button type="submit" class="product-add-btn"><i class="bi bi-cart-plus"></i> Add to Cart</button>
        </form>
      </div>
    </div>
    <?php endwhile; ?>
  </div>
<?php endif; ?>
</div>
<?php require_once 'includes/footer.php'; ?>
