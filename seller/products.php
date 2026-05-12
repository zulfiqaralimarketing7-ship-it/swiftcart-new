<?php
$pageTitle = 'My Products';
require_once '../includes/config.php';
requireSeller();
$uid = $_SESSION['user_id'];
if (isset($_GET['delete'])) {
    $pid = (int)$_GET['delete'];
    $conn->query("DELETE FROM products WHERE id=$pid AND seller_id=$uid");
    redirect(SITE_URL . '/seller/products.php?msg=deleted');
}
$products = $conn->query("SELECT p.*,c.name as cat_name FROM products p JOIN categories c ON p.category_id=c.id WHERE p.seller_id=$uid ORDER BY p.created_at DESC");
require_once '../includes/header.php';
$appMap=[0=>'badge-warning',1=>'badge-success',2=>'badge-danger']; $appText=[0=>'Pending',1=>'Approved',2=>'Rejected'];
?>
<div class="container" style="padding:28px 16px 50px;">
  <div style="display:grid;grid-template-columns:220px 1fr;gap:24px;">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div>
      <?php if (isset($_GET['msg'])): ?><div class="alert alert-success" data-auto-close>Product <?= e($_GET['msg']) ?> successfully.</div><?php endif; ?>
      <div class="card">
        <div class="card-header"><span class="card-title">My Products (<?= $products->num_rows ?>)</span><a href="<?= SITE_URL ?>/seller/product-create.php" class="btn-sm btn-primary-sm"><i class="bi bi-plus-circle"></i> Add Product</a></div>
        <table class="table">
          <thead><tr><th>Image</th><th>Product</th><th>Category</th><th>Price</th><th>Stock</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
          <?php if ($products->num_rows === 0): ?>
          <tr><td colspan="7" style="text-align:center;padding:40px;color:#888;">No products yet. <a href="<?= SITE_URL ?>/seller/product-create.php" style="color:var(--primary);">Add your first product</a></td></tr>
          <?php else: while ($p = $products->fetch_assoc()): $img = $p['thumbnail'] ?: 'https://picsum.photos/seed/'.$p['id'].'/80/80'; ?>
          <tr>
            <td><img src="<?= e($img) ?>" style="width:50px;height:50px;object-fit:cover;border-radius:8px;"></td>
            <td><strong><?= e($p['name']) ?></strong><?php if ($p['sku']): ?><br><span style="font-size:11px;color:#888;">SKU: <?= e($p['sku']) ?></span><?php endif; ?></td>
            <td><?= e($p['cat_name']) ?></td>
            <td><?php if ($p['sale_price']): ?><strong>PKR=<?= number_format($p['sale_price']) ?></strong><br><span style="text-decoration:line-through;color:#aaa;font-size:12px;">PKR=<?= number_format($p['price']) ?></span><?php else: ?><strong>PKR=<?= number_format($p['price']) ?></strong><?php endif; ?></td>
            <td><?= $p['stock'] ?></td>
            <td><span class="badge <?= $appMap[$p['is_approved']] ?>"><?= $appText[$p['is_approved']] ?></span></td>
            <td>
              <a href="<?= SITE_URL ?>/seller/product-edit.php?id=<?= $p['id'] ?>" class="btn-sm btn-outline-sm"><i class="bi bi-pencil"></i></a>
              <a href="<?= SITE_URL ?>/seller/products.php?delete=<?= $p['id'] ?>" class="btn-sm btn-danger-sm" data-confirm="Delete this product?"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
          <?php endwhile; endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
