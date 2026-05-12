<?php
$pageTitle = 'Edit Product';
require_once '../includes/config.php';
requireSeller();
$uid = $_SESSION['user_id'];
$pid = (int)($_GET['id'] ?? 0);
$product = $conn->query("SELECT * FROM products WHERE id=$pid AND seller_id=$uid")->fetch_assoc();
if (!$product) redirect(SITE_URL . '/seller/products.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($conn->real_escape_string($_POST['name'] ?? ''));
    $cat = (int)($_POST['category_id'] ?? 0);
    $desc = $conn->real_escape_string($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $sale = !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : null;
    $stock = (int)($_POST['stock'] ?? 0);
    $sku = $conn->real_escape_string($_POST['sku'] ?? '');
    $featured = isset($_POST['is_featured']) ? 1 : 0;
    $thumb = $product['thumbnail'];
    if (!empty($_FILES['thumbnail']['name'])) {
        $up = uploadImage($_FILES['thumbnail'], 'products');
        if ($up) $thumb = $up;
        else $error = 'Invalid image file.';
    }
    if (!$error && $name && $cat && $price > 0) {
        $thumb_esc = $conn->real_escape_string($thumb ?? '');
        $sale_sql = $sale !== null ? $sale : 'NULL';
        $conn->query("UPDATE products SET name='$name',category_id=$cat,description='$desc',price=$price,sale_price=$sale_sql,stock=$stock,sku='$sku',thumbnail='$thumb_esc',is_featured=$featured WHERE id=$pid AND seller_id=$uid");
        redirect(SITE_URL . '/seller/products.php?msg=updated');
    } elseif (!$error) { $error = 'Please fill required fields.'; }
    $product = $conn->query("SELECT * FROM products WHERE id=$pid")->fetch_assoc();
}
$cats = $conn->query("SELECT * FROM categories ORDER BY name");
require_once '../includes/header.php';
?>
<div class="container" style="padding:28px 16px 50px;">
  <div style="display:grid;grid-template-columns:220px 1fr;gap:24px;">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="card" style="padding:28px;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <h2 style="font-size:18px;font-weight:800;">Edit Product</h2>
        <a href="<?= SITE_URL ?>/seller/products.php" class="btn-sm btn-outline-sm"><i class="bi bi-arrow-left"></i> Back</a>
      </div>
      <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
      <form method="POST" enctype="multipart/form-data">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div class="form-group"><label class="form-label">Product Name *</label><input type="text" name="name" class="form-control" value="<?= e($product['name']) ?>" required></div>
          <div class="form-group"><label class="form-label">Category *</label><select name="category_id" class="form-select" required><option value="">Select Category</option><?php while ($c=$cats->fetch_assoc()): ?><option value="<?= $c['id'] ?>" <?= ($product['category_id']==$c['id'])?'selected':'' ?>><?= e($c['name']) ?></option><?php endwhile; ?></select></div>
          <div class="form-group"><label class="form-label">Original Price (₹) *</label><input type="number" name="price" class="form-control" step="0.01" value="<?= $product['price'] ?>" required></div>
          <div class="form-group"><label class="form-label">Sale Price (₹)</label><input type="number" name="sale_price" class="form-control" step="0.01" value="<?= $product['sale_price'] ?? '' ?>"></div>
          <div class="form-group"><label class="form-label">Stock</label><input type="number" name="stock" class="form-control" min="0" value="<?= $product['stock'] ?>"></div>
          <div class="form-group"><label class="form-label">SKU</label><input type="text" name="sku" class="form-control" value="<?= e($product['sku'] ?? '') ?>"></div>
        </div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4"><?= e($product['description'] ?? '') ?></textarea></div>
        <div class="form-group">
          <label class="form-label">Product Image</label>
          <?php if ($product['thumbnail']): ?>
          <div style="margin-bottom:10px;"><img id="thumbPreview" src="<?= e($product['thumbnail']) ?>" style="max-width:200px;border-radius:8px;"></div>
          <?php else: ?><img id="thumbPreview" src="" style="max-width:200px;border-radius:8px;display:none;" onload="this.style.display='block'"><?php endif; ?>
          <div class="upload-area"><i class="bi bi-cloud-upload"></i><div>Click to change image</div></div>
          <input type="file" name="thumbnail" accept="image/*" data-preview="thumbPreview" style="display:none;">
        </div>
        <div class="form-group"><label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><input type="checkbox" name="is_featured" <?= $product['is_featured']?'checked':'' ?> style="width:16px;height:16px;accent-color:var(--primary);"> <span class="form-label" style="margin:0;">Mark as Featured</span></label></div>
        <div style="display:flex;gap:12px;margin-top:8px;">
          <button type="submit" class="btn-primary"><i class="bi bi-check2-circle"></i> Save Changes</button>
          <a href="<?= SITE_URL ?>/seller/products.php" class="btn-outline">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
