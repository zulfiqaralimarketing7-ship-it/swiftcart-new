<?php
$pageTitle = 'Product';
require_once 'includes/config.php';
$id = (int)($_GET['id'] ?? 0);
$p = $conn->query("SELECT p.*,c.name as cat_name,c.id as cat_id,u.name as seller_name FROM products p JOIN categories c ON p.category_id=c.id JOIN users u ON p.seller_id=u.id WHERE p.id=$id AND p.is_approved=1")->fetch_assoc();
if (!$p) { header('Location: '.SITE_URL.'/products.php'); exit; }
$pageTitle = $p['name'];
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    requireLogin();
    $qty = max(1, (int)($_POST['qty'] ?? 1));
    $uid = $_SESSION['user_id'];
    $pid = $id;
    $ex = $conn->query("SELECT id, quantity FROM cart WHERE user_id=$uid AND product_id=$pid")->fetch_assoc();
    if ($ex) $conn->query("UPDATE cart SET quantity=quantity+$qty WHERE id={$ex['id']}");
    else $conn->query("INSERT INTO cart (user_id, product_id, quantity) VALUES ($uid, $pid, $qty)");
    $msg = 'success';
}
$related = $conn->query("SELECT p.*,c.name as cat_name FROM products p JOIN categories c ON p.category_id=c.id WHERE p.category_id={$p['cat_id']} AND p.id!=$id AND p.is_approved=1 LIMIT 4");
$discount = ($p['sale_price'] && $p['price'] > 0) ? round((1 - $p['sale_price']/$p['price'])*100) : 0;
$displayPrice = $p['sale_price'] ?? $p['price'];
$img = $p['thumbnail'] ?: 'https://picsum.photos/seed/'.$p['id'].'/600/600';
require_once 'includes/header.php';
?>
<div class="container" style="padding-top:28px;padding-bottom:50px;">
  <div style="font-size:13px;color:#888;margin-bottom:20px;">
    <a href="<?= SITE_URL ?>/" style="color:var(--primary);">Home</a> &rsaquo;
    <a href="<?= SITE_URL ?>/products.php?category_id=<?= $p['cat_id'] ?>" style="color:var(--primary);"><?= e($p['cat_name']) ?></a> &rsaquo; <?= e($p['name']) ?>
  </div>
  <?php if ($msg === 'success'): ?><div class="alert alert-success" data-auto-close>✓ Added to cart successfully!</div><?php endif; ?>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;background:#fff;border-radius:16px;padding:32px;box-shadow:0 2px 20px rgba(0,0,0,.07);">
    <div>
      <img src="<?= e($img) ?>" alt="<?= e($p['name']) ?>" style="width:100%;border-radius:12px;object-fit:cover;aspect-ratio:1/1;">
    </div>
    <div>
      <div style="font-size:12px;color:var(--primary);font-weight:600;text-transform:uppercase;letter-spacing:.5px;margin-bottom:8px;"><?= e($p['cat_name']) ?></div>
      <h1 style="font-size:24px;font-weight:800;color:#1a1a2e;margin-bottom:12px;line-height:1.3;"><?= e($p['name']) ?></h1>
      <div class="product-stars" style="margin-bottom:14px;font-size:14px;"><?= renderStars($p['rating']) ?> <span><?= number_format($p['rating'],1) ?> (<?= number_format($p['review_count']) ?> reviews)</span></div>
      <div style="display:flex;align-items:baseline;gap:12px;margin-bottom:20px;">
        <span style="font-size:32px;font-weight:900;color:var(--primary);">PKR=<?= number_format($displayPrice) ?></span>
        <?php if ($p['sale_price']): ?>
        <span style="font-size:18px;color:#aaa;text-decoration:line-through;">₹<?= number_format($p['price']) ?></span>
        <?php if ($discount > 0): ?><span style="background:#e8f5e9;color:#2e7d32;padding:4px 10px;border-radius:5px;font-size:13px;font-weight:700;">-<?= $discount ?>% OFF</span><?php endif; ?>
        <?php endif; ?>
      </div>
      <?php if ($p['description']): ?>
      <p style="color:#555;font-size:14px;line-height:1.8;margin-bottom:20px;"><?= nl2br(e($p['description'])) ?></p>
      <?php endif; ?>
      <div style="margin-bottom:20px;padding:14px;background:#f8f9fa;border-radius:10px;font-size:13px;color:#555;">
        <div style="display:flex;gap:20px;flex-wrap:wrap;">
          <span><i class="bi bi-person-check" style="color:var(--primary);"></i> Seller: <strong><?= e($p['seller_name']) ?></strong></span>
          <span><i class="bi bi-box" style="color:var(--primary);"></i> Stock: <strong><?= $p['stock'] ?> units</strong></span>
          <?php if ($p['sku']): ?><span><i class="bi bi-upc" style="color:var(--primary);"></i> SKU: <strong><?= e($p['sku']) ?></strong></span><?php endif; ?>
        </div>
      </div>
      <form method="POST">
        <div style="display:flex;align-items:center;gap:16px;margin-bottom:20px;">
          <div class="qty-control">
            <button type="button" class="qty-btn" data-dir="down">-</button>
            <input type="number" name="qty" class="qty-input" value="1" min="1" max="<?= $p['stock'] ?>">
            <button type="button" class="qty-btn" data-dir="up">+</button>
          </div>
        </div>
        <div style="display:flex;gap:12px;flex-wrap:wrap;">
          <button type="submit" name="add_to_cart" class="btn-primary" style="flex:1;justify-content:center;"><i class="bi bi-cart-plus"></i> Add to Cart</button>
          <a href="<?= SITE_URL ?>/checkout.php?buy=<?= $id ?>" class="btn-outline" style="flex:1;justify-content:center;display:inline-flex;align-items:center;gap:6px;"><i class="bi bi-lightning-charge"></i> Buy Now</a>
        </div>
      </form>
      <div style="display:flex;gap:16px;margin-top:20px;">
        <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#666;"><i class="bi bi-truck" style="color:var(--primary);"></i> Free Delivery</div>
        <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#666;"><i class="bi bi-arrow-repeat" style="color:var(--primary);"></i> 14-day Return</div>
        <div style="display:flex;align-items:center;gap:6px;font-size:12px;color:#666;"><i class="bi bi-shield-check" style="color:var(--primary);"></i> Secure Payment</div>
      </div>
    </div>
  </div>
  <!-- Related -->
  <?php if ($related->num_rows > 0): ?>
  <div style="margin-top:40px;">
    <div class="section-header"><h2 class="section-title">Related Products</h2></div>
    <div class="products-grid" style="grid-template-columns:repeat(4,1fr);">
      <?php while ($rp = $related->fetch_assoc()):
        $rDisc = ($rp['sale_price'] && $rp['price'] > 0) ? round((1 - $rp['sale_price']/$rp['price'])*100) : 0;
        $rPrice = $rp['sale_price'] ?? $rp['price'];
        $rImg = $rp['thumbnail'] ?: 'https://picsum.photos/seed/'.$rp['id'].'/400/400';
      ?>
      <div class="product-card">
        <div class="product-img-wrap">
          <a href="<?= SITE_URL ?>/product.php?id=<?= $rp['id'] ?>"><img src="<?= e($rImg) ?>" alt="<?= e($rp['name']) ?>" loading="lazy"></a>
          <?php if ($rDisc > 0): ?><div class="product-badge"><span class="badge-sale">-<?= $rDisc ?>%</span></div><?php endif; ?>
          <button class="product-wishlist"><i class="bi bi-heart"></i></button>
        </div>
        <div class="product-body">
          <div class="product-category"><?= e($rp['cat_name']) ?></div>
          <a href="<?= SITE_URL ?>/product.php?id=<?= $rp['id'] ?>"><div class="product-name"><?= e($rp['name']) ?></div></a>
          <div class="product-stars"><?= renderStars($rp['rating']) ?></div>
          <div class="product-price">
            <span class="price-sale">₹<?= number_format($rPrice) ?></span>
            <?php if ($rp['sale_price']): ?><span class="price-original">₹<?= number_format($rp['price']) ?></span><?php endif; ?>
          </div>
        </div>
      </div>
      <?php endwhile; ?>
    </div>
  </div>
  <?php endif; ?>
</div>
<?php require_once 'includes/footer.php'; ?>
