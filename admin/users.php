<?php
$pageTitle = 'Manage Users';
require_once '../includes/config.php';
requireAdmin();
if (isset($_GET['delete']) && $_GET['delete'] != 1) { $conn->query("DELETE FROM users WHERE id=".(int)$_GET['delete']); redirect(SITE_URL.'/admin/users.php?msg=deleted'); }
$users = $conn->query("SELECT u.*,r.name as role_name FROM users u JOIN roles r ON u.role_id=r.id ORDER BY u.created_at DESC");
require_once '../includes/header.php';
$roleColors = ['admin'=>'badge-danger','seller'=>'badge-warning','customer'=>'badge-info'];
?>
<div class="container" style="padding:28px 16px 50px;">
  <div style="display:grid;grid-template-columns:220px 1fr;gap:24px;">
    <?php include __DIR__ . '/sidebar.php'; ?>
    <div>
      <?php if (isset($_GET['msg'])): ?><div class="alert alert-success" data-auto-close>User <?= e($_GET['msg']) ?>.</div><?php endif; ?>
      <div class="card">
        <div class="card-header"><span class="card-title">Users (<?= $users->num_rows ?>)</span></div>
        <table class="table">
          <thead><tr><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Joined</th><th>Actions</th></tr></thead>
          <tbody>
          <?php while ($u = $users->fetch_assoc()): ?>
          <tr>
            <td><div style="display:flex;align-items:center;gap:8px;"><img src="<?= $u['avatar'] ? e($u['avatar']) : 'https://ui-avatars.com/api/?name='.urlencode($u['name']).'&size=36&background=1565c0&color=fff' ?>" style="width:36px;height:36px;border-radius:50%;object-fit:cover;"><strong><?= e($u['name']) ?></strong></div></td>
            <td><?= e($u['email']) ?></td>
            <td><?= e($u['phone'] ?? '-') ?></td>
            <td><span class="badge <?= $roleColors[$u['role_name']] ?? 'badge-secondary' ?>"><?= ucfirst($u['role_name']) ?></span></td>
            <td><?= date('d M Y', strtotime($u['created_at'])) ?></td>
            <td><?php if ($u['id'] != 1): ?><a href="?delete=<?= $u['id'] ?>" class="btn-sm btn-danger-sm" data-confirm="Delete user <?= e($u['name']) ?>?"><i class="bi bi-trash"></i> Delete</a><?php else: ?><span style="font-size:12px;color:#888;">Protected</span><?php endif; ?></td>
          </tr>
          <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
