<?php
$pageTitle = 'Products';
require_once 'includes/config.php';
require_once 'includes/header.php';

$where = ["p.is_approved=1"];
$q = trim($conn->real_escape_string($_GET['q'] ?? ''));
$cat_id = (int)($_GET['category_id'] ?? 0);
$sort = $conn->real_escape_string($_GET['sort'] ?? 'newest');
$deal = isset($_GET['deal']) ? 1 : 0;
$min = (float)($_GET['min'] ?? 0);
$max = (float)($_GET['max'] ?? 0);

if ($q) $where[] = "(p.name LIKE '%$q%' OR p.description LIKE '%$q%')";
if ($cat_id) $where[] = "p.category_id=$cat_id";
if ($deal) $where[] = "p.sale_price IS NOT NULL";
if ($min > 0) $where[] = "COALESCE(p.sale_price, p.price) >= $min";
if ($max > 0) $where[] = "COALESCE(p.sale_price, p.price) <= $max";

$orderBy = match($sort) {
    'price_asc' => 'COALESCE(p.sale_price, p.price) ASC',
    'price_desc' => 'COALESCE(p.sale_price, p.price) DESC',
    'rating' => 'p.rating DESC',
    default => 'p.created_at DESC'
};

$whereSQL = implode(' AND ', $where);
$sql = "SELECT p.*, c.name as cat_name FROM products p JOIN categories c ON p.category_id=c.id WHERE $whereSQL ORDER BY $orderBy";
$result = $conn->query($sql);
$currentCat = $cat_id ? $conn->query("SELECT * FROM categories WHERE id=$cat_id")->fetch_assoc() : null;
?>
<div class="container" style="padding-top:28px;padding-bottom:40px;">
  <!-- Breadcrumb -->
  <div style="font-size:13px;color:#888;margin-bottom:20px;">
    <a href="<?= SITE_URL ?>/" style="color:var(--primary);">Home</a> &rsaquo;
    <?php if ($currentCat): ?><a href="<?= SITE_URL ?>/products.php?category_id=<?= $currentCat['id'] ?>" style="color:var(--primary);"><?= e($currentCat['name']) ?></a> &rsaquo;<?php endif; ?>
    <?= $q ? 'Search: "'.e($q).'"' : ($currentCat ? e($currentCat['name']) : 'All Products') ?>
  </div>
  <div style="display:grid;grid-template-columns:240px 1fr;gap:24px;">
    <!-- SIDEBAR -->
    <aside>
      <div class="sidebar">
        <div style="font-weight:700;font-size:16px;padding:0 12px 12px;border-bottom:1px solid #f0f0f0;margin-bottom:12px;">Filters</div>
        <!-- Categories -->
        <div class="sidebar-title">Categories</div>
        <a href="<?= SITE_URL ?>/products.php" class="sidebar-link <?= !$cat_id ? 'active' : '' ?>"><i class="bi bi-grid"></i> All Categories</a>
        <?php
        $cats = $conn->query("SELECT c.*, COUNT(p.id) as cnt FROM categories c LEFT JOIN products p ON p.category_id=c.id AND p.is_approved=1 GROUP BY c.id ORDER BY c.name");
        while ($c = $cats->fetch_assoc()):
        ?>
        <a href="<?= SITE_URL ?>/products.php?category_id=<?= $c['id'] ?>" class="sidebar-link <?= ($cat_id == $c['id']) ? 'active' : '' ?>">
          <i class="bi <?= e($c['icon']) ?>"></i> <?= e($c['name']) ?> <span style="margin-left:auto;font-size:11px;background:#f0f0f0;border-radius:10px;padding:1px 6px;"><?= $c['cnt'] ?></span>
        </a>
        <?php endwhile; ?>
        <!-- Price -->
        <div style="padding:16px 12px 8px;margin-top:8px;border-top:1px solid #f0f0f0;">
          <div class="sidebar-title" style="padding:0 0 10px;">Price Range</div>
          <form method="GET">
            <?php if ($cat_id): ?><input type="hidden" name="category_id" value="<?= $cat_id ?>"><?php endif; ?>
            <?php if ($q): ?><input type="hidden" name="q" value="<?= e($q) ?>"><?php endif; ?>
            <div style="display:flex;gap:8px;align-items:center;margin-bottom:10px;">
              <input type="number" name="min" placeholder="Min ₹" value="<?= $min ?: '' ?>" class="form-control" style="padding:6px 10px;font-size:12px;">
              <span style="color:#888;">-</span>
              <input type="number" name="max" placeholder="Max ₹" value="<?= $max ?: '' ?>" class="form-control" style="padding:6px 10px;font-size:12px;">
            </div>
            <button type="submit" class="btn-primary" style="width:100%;padding:8px;font-size:13px;">Apply Filter</button>
          </form>
        </div>
      </div>
    </aside>
    <!-- PRODUCTS -->
    <div>
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
        <div>
          <span style="font-weight:700;font-size:17px;"><?= $q ? 'Results for "'.e($q).'"' : ($currentCat ? e($currentCat['name']) : 'All Products') ?></span>
          <span style="color:#888;font-size:13px;margin-left:8px;">(<?= $result->num_rows ?> products)</span>
        </div>
        <form method="GET" style="display:flex;align-items:center;gap:8px;">
          <?php if ($cat_id): ?><input type="hidden" name="category_id" value="<?= $cat_id ?>"><?php endif; ?>
          <?php if ($q): ?><input type="hidden" name="q" value="<?= e($q) ?>"><?php endif; ?>
          <label style="font-size:13px;color:#666;">Sort:</label>
          <select name="sort" class="form-select" style="width:auto;padding:7px 12px;" onchange="this.form.submit()">
            <option value="newest" <?= $sort=='newest'?'selected':'' ?>>Newest</option>
            <option value="price_asc" <?= $sort=='price_asc'?'selected':'' ?>>Price: Low to High</option>
            <option value="price_desc" <?= $sort=='price_desc'?'selected':'' ?>>Price: High to Low</option>
            <option value="rating" <?= $sort=='rating'?'selected':'' ?>>Top Rated</option>
          </select>
        </form>
      </div>
      <?php if ($result->num_rows === 0): ?>
      <div style="text-align:center;padding:60px;background:#fff;border-radius:12px;">
        <i class="bi bi-search" style="font-size:48px;color:#ddd;"></i>
        <h3 style="margin-top:16px;color:#888;">No products found</h3>
        <p style="color:#aaa;">Try different keywords or filters.</p>
        <a href="<?= SITE_URL ?>/products.php" class="btn-primary" style="display:inline-flex;margin-top:16px;">View All Products</a>
      </div>
      <?php else: ?>
      <div class="products-grid" style="grid-template-columns:repeat(3,1fr);">
        <?php while ($p = $result->fetch_assoc()):
          $discount = ($p['sale_price'] && $p['price'] > 0) ? round((1 - $p['sale_price']/$p['price'])*100) : 0;
          $displayPrice = $p['sale_price'] ?? $p['price'];
          $img = $p['thumbnail'] ?: 'https://picsum.photos/seed/'.$p['id'].'/400/400';
        ?>
        <div class="product-card">
          <div class="product-img-wrap">
            <a href="<?= SITE_URL ?>/product.php?id=<?= $p['id'] ?>">
              <img src="<?= e($img) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
            </a>
            <?php if ($discount > 0): ?><div class="product-badge"><span class="badge-sale">-<?= $discount ?>%</span></div><?php endif; ?>
            <button class="product-wishlist"><i class="bi bi-heart"></i></button>
          </div>
          <div class="product-body">
            <div class="product-category"><?= e($p['cat_name']) ?></div>
            <a href="<?= SITE_URL ?>/product.php?id=<?= $p['id'] ?>"><div class="product-name"><?= e($p['name']) ?></div></a>
            <div class="product-stars"><?= renderStars($p['rating']) ?><span><?= number_format($p['rating'],1) ?>(<?= number_format($p['review_count']) ?>)</span></div>
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
  </div>
</div>
<?php require_once 'includes/footer.php'; ?>
