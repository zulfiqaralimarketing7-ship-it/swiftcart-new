<?php
$pageTitle = 'Categories';
require_once '../includes/config.php';
requireAdmin();
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    if ($action === 'add') {
        $name = $conn->real_escape_string(trim($_POST['name'] ?? ''));
        $icon = $conn->real_escape_string(trim($_POST['icon'] ?? 'bi-grid'));
        $desc = $conn->real_escape_string($_POST['description'] ?? '');
        if ($name) {
            $slug = strtolower(preg_replace('/[^a-z0-9]+/','-',$name)).'-'.time();
            $conn->query("INSERT INTO categories (name,slug,icon,description) VALUES ('$name','$slug','$icon','$desc')");
            $msg = 'success:Category added!';
        }
    } elseif ($action === 'delete') {
        $cid = (int)($_POST['category_id'] ?? 0);
        $conn->query("DELETE FROM categories WHERE id=$cid");
        $msg = 'success:Category deleted.';
    }
}
$cats = $conn->query("SELECT c.*,COUNT(p.id) as product_count FROM categories c LEFT JOIN products p ON p.category_id=c.id GROUP BY c.id ORDER BY c.name");
require_once '../includes/header.php';
[$mtype,$mtext] = $msg ? explode(':',$msg,2) : ['',''];
?>
<div class="container" style="padding:28px 16px 50px;">
  <div style="display:grid;grid-template-columns:220px 1fr;gap:24px;">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div>
      <?php if ($mtext): ?><div class="alert alert-<?= $mtype ?>" data-auto-close><?= e($mtext) ?></div><?php endif; ?>
      <div style="display:grid;grid-template-columns:1fr 360px;gap:20px;align-items:start;">
        <div class="card">
          <div class="card-header"><span class="card-title">Categories (<?= $cats->num_rows ?>)</span></div>
          <table class="table">
            <thead><tr><th>Icon</th><th>Name</th><th>Slug</th><th>Products</th><th>Actions</th></tr></thead>
            <tbody>
            <?php while ($c = $cats->fetch_assoc()): ?>
            <tr>
              <td><i class="bi <?= e($c['icon']) ?>" style="font-size:20px;color:var(--primary);"></i></td>
              <td><strong><?= e($c['name']) ?></strong></td>
              <td><code style="font-size:12px;"><?= e($c['slug']) ?></code></td>
              <td><?= $c['product_count'] ?></td>
              <td>
                <form method="POST" style="display:inline;">
                  <input type="hidden" name="action" value="delete">
                  <input type="hidden" name="category_id" value="<?= $c['id'] ?>">
                  <button type="submit" class="btn-sm btn-danger-sm" data-confirm="Delete '<?= e($c['name']) ?>'?"><i class="bi bi-trash"></i> Delete</button>
                </form>
              </td>
            </tr>
            <?php endwhile; ?>
            </tbody>
          </table>
        </div>
        <div class="card" style="padding:24px;">
          <h3 style="font-size:16px;font-weight:700;margin-bottom:18px;">Add Category</h3>
          <form method="POST">
            <input type="hidden" name="action" value="add">
            <div class="form-group"><label class="form-label">Name *</label><input type="text" name="name" class="form-control" placeholder="Category name" required></div>
            <div class="form-group"><label class="form-label">Bootstrap Icon Class</label><input type="text" name="icon" class="form-control" placeholder="bi-grid" value="bi-grid"><small style="color:#888;font-size:12px;">e.g. bi-headphones, bi-bag, bi-house</small></div>
            <div class="form-group"><label class="form-label">Description</label><textarea name="description" class="form-control" rows="2"></textarea></div>
            <button type="submit" class="btn-primary"><i class="bi bi-plus-circle"></i> Add Category</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
