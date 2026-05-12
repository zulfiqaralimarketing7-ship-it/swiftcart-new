<?php
$pageTitle = 'SwiftCart';
require_once 'includes/config.php';
require_once 'includes/header.php';

// Featured products
$featured = $conn->query("SELECT p.*, c.name as cat_name, u.name as seller_name FROM products p JOIN categories c ON p.category_id=c.id JOIN users u ON p.seller_id=u.id WHERE p.is_approved=1 AND p.is_featured=1 ORDER BY p.created_at DESC LIMIT 6");

// All categories
$categories = $conn->query("SELECT * FROM categories ORDER BY name");

// Latest products
$latest = $conn->query("SELECT p.*, c.name as cat_name FROM products p JOIN categories c ON p.category_id=c.id WHERE p.is_approved=1 ORDER BY p.created_at DESC LIMIT 6");

// Promo products
$electronics = $conn->query("SELECT thumbnail FROM products p JOIN categories c ON p.category_id=c.id WHERE c.slug='electronics' AND p.is_approved=1 LIMIT 1")->fetch_assoc();
$fashion = $conn->query("SELECT thumbnail FROM products p JOIN categories c ON p.category_id=c.id WHERE c.slug='fashion' AND p.is_approved=1 LIMIT 1")->fetch_assoc();
$sports = $conn->query("SELECT thumbnail FROM products p JOIN categories c ON p.category_id=c.id WHERE c.slug='sports-outdoors' AND p.is_approved=1 LIMIT 1")->fetch_assoc();
?> 

<!-- HERO SECTION -->
<section class="hero-section">
  <div class="container">
    <div class="hero-inner">
      <div class="hero-content">
        <span class="hero-label"><i class="bi bi-lightning-charge-fill"></i> Fast Shopping, Fast Delivery</span>
        <h1 class="hero-title">Shop More.<br><span>Deliver Fast.</span></h1>
        <p class="hero-desc">Find everything you need from top brands and get it delivered to your doorstep in no time.</p>
        <div class="hero-btns">
          <a href="<?= SITE_URL ?>/products.php" class="btn-primary"><i class="bi bi-bag-check-fill"></i> Shop Now <i class="bi bi-arrow-right"></i></a>
          <a href="<?= SITE_URL ?>/products.php?deal=1" class="btn-outline"><i class="bi bi-tags"></i> Explore Deals</a>
        </div>
        <div class="hero-features">
          <div class="hero-feat"><i class="bi bi-truck"></i> Free Delivery on orders over PKR=499</div>
          <div class="hero-feat"><i class="bi bi-arrow-repeat"></i> 14 days return policy</div>
          <div class="hero-feat"><i class="bi bi-shield-check"></i> 100% safe & secure</div>
        </div>
      </div>
      <!-- Phone Mockup -->
      <div class="hero-image">
        <div style="position:relative;">
          <div class="phone-mockup">
            <div class="phone-screen">
              <div class="phone-nav">
                <i class="bi bi-cart3" style="color:#fff;font-size:10px;"></i>
                <span>SwiftCart</span>
              </div>
              <div class="phone-search"><i class="bi bi-search" style="font-size:8px;"></i> Search products...</div>
              <div class="phone-banner">
                <h6>Big Deals</h6>
                <h5>On Top Brands</h5>
                <button>Shop Now</button>
              </div>
              <div class="phone-cats">
                <div class="phone-cat"><i class="bi bi-headphones"></i><span>Electronics</span></div>
                <div class="phone-cat"><i class="bi bi-bag"></i><span>Fashion</span></div>
                <div class="phone-cat"><i class="bi bi-house"></i><span>Home</span></div>
                <div class="phone-cat"><i class="bi bi-heart"></i><span>Beauty</span></div>
              </div>
              <div class="phone-products">Best Selling</div>
              <div class="phone-prod">
                <img src="https://picsum.photos/seed/hp1/60/60" alt="">
                <div class="phone-prod-info"><h6>Wireless Headphones</h6><span>PKR=1,499</span></div>
                <span class="disc">-40%</span>
              </div>
              <div class="phone-prod">
                <img src="https://picsum.photos/seed/sw1/60/60" alt="">
                <div class="phone-prod-info"><h6>Smart Watch</h6><span>PKR=2,999</span></div>
                <span class="disc">-40%</span>
              </div>
            </div>
          </div>
          <!-- Floating boxes -->
          <div style="position:absolute;top:-20px;right:-70px;display:flex;flex-direction:column;gap:10px;">
            <div class="hero-box"><i class="bi bi-truck"></i> Free Delivery</div>
            <div class="hero-box"><i class="bi bi-star-fill" style="color:#f9a825;"></i> Top Rated</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- CATEGORIES -->
<section class="section" style="background:#fff;padding-bottom:30px;">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Shop By Category</h2>
      <a href="<?= SITE_URL ?>/products.php" class="view-all">View All Categories <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="categories-grid">
      <?php
      $catIcons = [
        'electronics'=>'bi-headphones','fashion'=>'bi-bag','home-kitchen'=>'bi-house',
        'beauty-health'=>'bi-heart','sports-outdoors'=>'bi-trophy','books-stationery'=>'bi-book',
        'toys-games'=>'bi-dice-5','automotive'=>'bi-car-front'
      ];
      $catImgs = [
        'electronics'=>'https://picsum.photos/seed/elec/120/120',
        'fashion'=>'https://picsum.photos/seed/fash/120/120',
        'home-kitchen'=>'https://picsum.photos/seed/home/120/120',
        'beauty-health'=>'https://picsum.photos/seed/beau/120/120',
        'sports-outdoors'=>'https://picsum.photos/seed/sprt/120/120',
        'books-stationery'=>'https://picsum.photos/seed/book/120/120',
        'toys-games'=>'https://picsum.photos/seed/toys/120/120',
        'automotive'=>'https://picsum.photos/seed/auto/120/120',
      ];
      $cats3 = $conn->query("SELECT * FROM categories ORDER BY name");
      while ($cat = $cats3->fetch_assoc()):
        $icon = $catIcons[$cat['slug']] ?? 'bi-grid';
        $img = $catImgs[$cat['slug']] ?? 'https://picsum.photos/seed/'.$cat['slug'].'/120/120';
      ?>
      <a href="<?= SITE_URL ?>/products.php?category_id=<?= $cat['id'] ?>" class="cat-card" style="text-decoration:none;">
        <div class="cat-icon"><i class="bi <?= $icon ?>"></i></div>
        <div class="cat-name"><?= e($cat['name']) ?></div>
      </a>
      <?php endwhile; ?>
    </div>
  </div>
</section>

<!-- PROMO BANNERS -->
<section class="section" style="padding-top:20px;padding-bottom:30px;">
  <div class="container"> 
    <div class="promo-grid">
      <div class="promo-card promo-blue">
        <div class="promo-content">
          <div class="promo-label">Deal of the Day</div>
          <div class="promo-title">Up to 60% Off</div>
          <div class="promo-sub">On Electronics</div>
          <a href="<?= SITE_URL ?>/products.php?category_id=1" class="promo-btn">Shop Now</a>
        </div>
        <div class="promo-img"><img src="<?= $electronics['thumbnail'] ?? 'https://picsum.photos/seed/elec2/200/120' ?>" alt="Electronics"></div>
      </div>
      <div class="promo-card promo-teal">
        <div class="promo-content">
          <div class="promo-label">New Arrivals</div>
          <div class="promo-title">Latest Products</div>
          <div class="promo-sub">Just For You</div>
          <a href="<?= SITE_URL ?>/products.php?sort=newest" class="promo-btn">Explore Now</a>
        </div>
        <div class="promo-img"><img src="<?= $fashion['thumbnail'] ?? 'https://picsum.photos/seed/fash2/200/120' ?>" alt="New Arrivals"></div>
      </div>
      <div class="promo-card promo-purple">
        <div class="promo-content">
          <div class="promo-label">Big Savings</div>
          <div class="promo-title">Up to 50% Off</div>
          <div class="promo-sub">On Top Brands</div>
          <a href="<?= SITE_URL ?>/products.php?deal=1" class="promo-btn">Shop Now</a>
        </div>
        <div class="promo-img"><img src="<?= $sports['thumbnail'] ?? 'https://picsum.photos/seed/sprt2/200/120' ?>" alt="Brands"></div>
      </div>
    </div>
  </div>
</section>

<!-- BEST SELLING PRODUCTS -->
<section class="section" style="background:#fff;padding-top:30px;">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">Best Selling Products</h2>
      <a href="<?= SITE_URL ?>/products.php" class="view-all">View All Products <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="products-grid">
      <?php while ($p = $featured->fetch_assoc()):
        $discount = ($p['sale_price'] && $p['price'] > 0) ? round((1 - $p['sale_price']/$p['price'])*100) : 0;
        $displayPrice = $p['sale_price'] ?? $p['price'];
        $img = $p['thumbnail'] ?: 'https://picsum.photos/seed/'.$p['id'].'/400/400';
        $inWishlist = isLoggedIn() ? $conn->query("SELECT id FROM wishlist WHERE user_id={$_SESSION['user_id']} AND product_id={$p['id']}")->num_rows > 0 : false;
      ?>
      <div class="product-card">
        <div class="product-img-wrap">
          <a href="<?= SITE_URL ?>/product.php?id=<?= $p['id'] ?>">
            <img src="<?= e($img) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
          </a>
          <?php if ($discount > 0): ?>
          <div class="product-badge"><span class="badge-sale">-<?= $discount ?>%</span></div>
          <?php endif; ?>
          <form method="POST" action="<?= SITE_URL ?>/wishlist.php" style="display:inline;">
            <input type="hidden" name="product_id" value="<?= $p['id'] ?>">
            <input type="hidden" name="action" value="toggle">
            <button type="submit" class="product-wishlist <?= $inWishlist ? 'active' : '' ?>">
              <i class="bi <?= $inWishlist ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
            </button>
          </form>
        </div>
        <div class="product-body">
          <div class="product-category"><?= e($p['cat_name']) ?></div>
          <a href="<?= SITE_URL ?>/product.php?id=<?= $p['id'] ?>"><div class="product-name"><?= e($p['name']) ?></div></a>
          <div class="product-stars">
            <?= renderStars($p['rating']) ?>
            <span><?= number_format($p['rating'],1) ?> (<?= number_format($p['review_count']) ?>)</span>
          </div>
          <div class="product-price">
            <span class="price-sale">PKR=<?= number_format($displayPrice) ?></span>
            <?php if ($p['sale_price']): ?>
            <span class="price-original">PKR=<?= number_format($p['price']) ?></span>
            <?php if ($discount > 0): ?><span class="price-discount">-<?= $discount ?>%</span><?php endif; ?>
            <?php endif; ?>
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
  </div>
</section>

<!-- LATEST PRODUCTS -->
<section class="section" style="padding-top:30px;">
  <div class="container">
    <div class="section-header">
      <h2 class="section-title">New Arrivals</h2>
      <a href="<?= SITE_URL ?>/products.php?sort=newest" class="view-all">View All <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="products-grid">
      <?php while ($p = $latest->fetch_assoc()):
        $discount = ($p['sale_price'] && $p['price'] > 0) ? round((1 - $p['sale_price']/$p['price'])*100) : 0;
        $displayPrice = $p['sale_price'] ?? $p['price'];
        $img = $p['thumbnail'] ?: 'https://picsum.photos/seed/new'.$p['id'].'/400/400';
      ?>
      <div class="product-card">
        <div class="product-img-wrap">
          <a href="<?= SITE_URL ?>/product.php?id=<?= $p['id'] ?>">
            <img src="<?= e($img) ?>" alt="<?= e($p['name']) ?>" loading="lazy">
          </a>
          <div class="product-badge"><span class="badge-new">NEW</span></div>
          <button class="product-wishlist"><i class="bi bi-heart"></i></button>
        </div>
        <div class="product-body">
          <div class="product-category"><?= e($p['cat_name']) ?></div>
          <a href="<?= SITE_URL ?>/product.php?id=<?= $p['id'] ?>"><div class="product-name"><?= e($p['name']) ?></div></a>
          <div class="product-stars">
            <?= renderStars($p['rating']) ?>
            <span><?= number_format($p['rating'],1) ?></span>
          </div>
          <div class="product-price">
            <span class="price-sale">PKR=<?= number_format($displayPrice) ?></span>
            <?php if ($p['sale_price']): ?><span class="price-original">PKR=<?= number_format($p['price']) ?></span><?php endif; ?>
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
  </div>
</section>

<?php require_once 'includes/footer.php'; ?>
