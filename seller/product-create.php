<?php
$pageTitle = 'Add Product';
require_once '../includes/config.php';
requireSeller();
$uid = $_SESSION['user_id'];
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($conn->real_escape_string($_POST['name'] ?? ''));
  $cat = (int)($_POST['category_id'] ?? 0);
  $desc = $conn->real_escape_string($_POST['description'] ?? '');
  $price = (float)($_POST['price'] ?? 0);
  $sale = !empty($_POST['sale_price']) ? (float)$_POST['sale_price'] : 'NULL';
  $stock = (int)($_POST['stock'] ?? 0);
  $sku = $conn->real_escape_string($_POST['sku'] ?? '');
  $featured = isset($_POST['is_featured']) ? 1 : 0;
  if (!$name || !$cat || $price <= 0) {
    $error = 'Please fill required fields.';
  } else {
    $slug = strtolower(preg_replace('/[^a-z0-9]+/', '-', $name)) . '-' . time();
    $thumbnail = 'NULL';
    if (!empty($_FILES['thumbnail']['name'])) {
      $up = uploadImage($_FILES['thumbnail'], 'products');
      if ($up) $thumbnail = "'" . $conn->real_escape_string($up) . "'";
      else $error = 'Invalid image file.';
    }
    if (!$error) {
      $sale_sql = ($sale === 'NULL') ? 'NULL' : $sale;
      $conn->query("INSERT INTO products (seller_id,category_id,name,slug,description,price,sale_price,stock,sku,thumbnail,is_featured) VALUES ($uid,$cat,'$name','$slug','$desc',$price,$sale_sql,$stock,'$sku',$thumbnail,$featured)");
      redirect(SITE_URL . '/seller/products.php?msg=created');
    }
  }
}
$cats = $conn->query("SELECT * FROM categories ORDER BY name");
require_once '../includes/header.php';
?>
<div class="container" style="padding:28px 16px 50px;">
  <div style="display:grid;grid-template-columns:220px 1fr;gap:24px;">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div class="card" style="padding:28px;">
      <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:20px;">
        <h2 style="font-size:18px;font-weight:800;">Add New Product</h2>
        <a href="<?= SITE_URL ?>/seller/products.php" class="btn-sm btn-outline-sm"><i class="bi bi-arrow-left"></i> Back</a>
      </div>
      <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
      <form method="POST" enctype="multipart/form-data">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div class="form-group"><label class="form-label">Product Name *</label><input type="text" name="name" class="form-control" value="<?= e($_POST['name'] ?? '') ?>" required></div>
          <div class="form-group"><label class="form-label">Category *</label><select name="category_id" class="form-select" required>
              <option value="">Select Category</option>
              <?php
              global $conn;
              $cats = $conn->query("SELECT id, name FROM categories ORDER BY name");
              while ($c = $cats->fetch_assoc()):
              ?>
                <option value="<?= $c['id'] ?>" <?= (($_GET['category_id'] ?? '') == $c['id']) ? 'selected' : '' ?>><?= e($c['name']) ?></option>
              <?php endwhile; ?>
              <?php while ($c = $cats->fetch_assoc()): ?><option value="<?= $c['id'] ?>" <?= (($_POST['category_id'] ?? '') == $c['id']) ? 'selected' : '' ?>><?= e($c['name']) ?></option><?php endwhile; ?>
            </select></div>
          <div class="form-group"><label class="form-label">Original Price (₹) *</label><input type="number" name="price" class="form-control" step="0.01" min="0" value="<?= $_POST['price'] ?? '' ?>" required></div>
          <div class="form-group"><label class="form-label">Sale Price (₹) <small style="color:#888;">(optional)</small></label><input type="number" name="sale_price" class="form-control" step="0.01" min="0" value="<?= $_POST['sale_price'] ?? '' ?>"></div>
          <div class="form-group"><label class="form-label">Stock Quantity</label><input type="number" name="stock" class="form-control" min="0" value="<?= $_POST['stock'] ?? 0 ?>"></div>
          <div class="form-group"><label class="form-label">SKU <small style="color:#888;">(optional)</small></label><input type="text" name="sku" class="form-control" value="<?= e($_POST['sku'] ?? '') ?>"></div>
        </div>
        <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="4"><?= e($_POST['description'] ?? '') ?></textarea></div>
        <div class="form-group">
          <label class="form-label">Product Image</label>
          <div class="upload-area"><i class="bi bi-cloud-upload"></i>
            <div>Click or drag image here</div>
            <div style="font-size:12px;color:#999;margin-top:4px;">JPG, PNG, WEBP up to 5MB</div>
          </div>
          <input type="file" name="thumbnail" accept="image/*" data-preview="thumbPreview" style="display:none;">
          <img id="thumbPreview" src="" style="max-width:200px;border-radius:8px;margin-top:10px;display:none;" onload="this.style.display='block'">
        </div>
        <div class="form-group"><label style="display:flex;align-items:center;gap:8px;cursor:pointer;"><input type="checkbox" name="is_featured" <?= isset($_POST['is_featured']) ? 'checked' : '' ?> style="width:16px;height:16px;accent-color:var(--primary);"> <span class="form-label" style="margin:0;">Mark as Featured</span></label></div>
        <div style="display:flex;gap:12px;margin-top:8px;">
          <button type="submit" class="btn-primary"><i class="bi bi-check2-circle"></i> Add Product</button>
          <a href="<?= SITE_URL ?>/seller/products.php" class="btn-outline">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>