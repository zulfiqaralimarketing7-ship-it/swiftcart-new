<?php
$pageTitle = 'Manage Products';
require_once '../includes/config.php';
requireAdmin();
if (isset($_GET['approve'])) { $conn->query("UPDATE products SET is_approved=1 WHERE id=".(int)$_GET['approve']); redirect(SITE_URL.'/admin/products.php?msg=approved'); }
if (isset($_GET['reject']))  { $conn->query("UPDATE products SET is_approved=2 WHERE id=".(int)$_GET['reject']);  redirect(SITE_URL.'/admin/products.php?msg=rejected'); }
if (isset($_GET['delete']))  { $conn->query("DELETE FROM products WHERE id=".(int)$_GET['delete']); redirect(SITE_URL.'/admin/products.php?msg=deleted'); }
$filter = $conn->real_escape_string($_GET['filter'] ?? 'all');
$where = $filter === 'pending' ? "WHERE p.is_approved=0" : ($filter==='approved' ? "WHERE p.is_approved=1" : ($filter==='rejected' ? "WHERE p.is_approved=2" : ""));
$products = $conn->query("SELECT p.*,c.name as cat_name,u.name as seller_name FROM products p JOIN categories c ON p.category_id=c.id JOIN users u ON p.seller_id=u.id $where ORDER BY p.created_at DESC");
$appMap=[0=>'badge-warning',1=>'badge-success',2=>'badge-danger']; $appText=[0=>'Pending',1=>'Approved',2=>'Rejected'];
require_once '../includes/header.php';
?>
<div class="container" style="padding:28px 16px 50px;">
  <div style="display:grid;grid-template-columns:220px 1fr;gap:24px;">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div>
      <?php if (isset($_GET['msg'])): ?><div class="alert alert-success" data-auto-close>Product <?= e($_GET['msg']) ?>.</div><?php endif; ?>
      <div class="card">
        <div class="card-header">
          <span class="card-title">Products (<?= $products->num_rows ?>)</span>
          <div style="display:flex;gap:6px;">
            <?php foreach (['all'=>'All','pending'=>'Pending','approved'=>'Approved','rejected'=>'Rejected'] as $k=>$v): ?>
            <a href="?filter=<?= $k ?>" class="btn-sm <?= $filter===$k?'btn-primary-sm':'btn-outline-sm' ?>"><?= $v ?></a>
            <?php endforeach; ?>
          </div>
        </div>
        <table class="table">
          <thead><tr><th>Image</th><th>Product</th><th>Seller</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>
          <tbody>
          <?php while ($p = $products->fetch_assoc()): $img = $p['thumbnail'] ?: 'https://picsum.photos/seed/'.$p['id'].'/80/80'; ?>
          <tr>
            <td><img src="<?= e($img) ?>" style="width:48px;height:48px;object-fit:cover;border-radius:8px;"></td>
            <td><strong><?= e($p['name']) ?></strong><br><span style="font-size:11px;color:#888;"><?= e($p['cat_name']) ?></span></td>
            <td><?= e($p['seller_name']) ?></td>
            <td>PKR=<?= number_format($p['sale_price'] ?? $p['price']) ?></td>
            <td><span class="badge <?= $appMap[$p['is_approved']] ?>"><?= $appText[$p['is_approved']] ?></span></td>
            <td>
              <?php if ($p['is_approved'] != 1): ?><a href="?approve=<?= $p['id'] ?>" class="btn-sm btn-success-sm"><i class="bi bi-check-circle"></i> Approve</a><?php endif; ?>
              <?php if ($p['is_approved'] != 2): ?><a href="?reject=<?= $p['id'] ?>"  class="btn-sm btn-danger-sm"><i class="bi bi-x-circle"></i> Reject</a><?php endif; ?>
              <a href="?delete=<?= $p['id'] ?>" class="btn-sm btn-danger-sm" data-confirm="Delete permanently?"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
