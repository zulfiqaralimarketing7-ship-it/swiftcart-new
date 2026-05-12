<?php
$pageTitle = 'My Profile';
require_once 'includes/config.php';
requireLogin();
$uid = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $conn->real_escape_string(trim($_POST['name'] ?? ''));
    $phone = $conn->real_escape_string(trim($_POST['phone'] ?? ''));
    $address = $conn->real_escape_string(trim($_POST['address'] ?? ''));
    $avatar = $user['avatar'];
    if (!empty($_FILES['avatar']['name'])) {
        $up = uploadImage($_FILES['avatar'], 'avatars');
        if ($up) $avatar = $up;
    }
    $avatar_esc = $conn->real_escape_string($avatar ?? '');
    if (isset($_POST['change_password']) && !empty($_POST['current_password'])) {
        $cur = $_POST['current_password'];
        $newp = $_POST['new_password'];
        $conf = $_POST['confirm_new'];
        if (!password_verify($cur, $user['password'])) { $msg = 'error:Wrong current password.'; }
        elseif ($newp !== $conf) { $msg = 'error:New passwords do not match.'; }
        elseif (strlen($newp) < 6) { $msg = 'error:Password must be at least 6 characters.'; }
        else {
            $hash = password_hash($newp, PASSWORD_DEFAULT);
            $conn->query("UPDATE users SET name='$name',phone='$phone',address='$address',avatar='$avatar_esc',password='$hash' WHERE id=$uid");
            $msg = 'success:Profile and password updated!';
        }
    } else {
        $conn->query("UPDATE users SET name='$name',phone='$phone',address='$address',avatar='$avatar_esc' WHERE id=$uid");
        $msg = 'success:Profile updated successfully!';
    }
    if (str_starts_with($msg, 'success')) { $_SESSION['user_name'] = $name; $user = $conn->query("SELECT * FROM users WHERE id=$uid")->fetch_assoc(); }
}
require_once 'includes/header.php';
[$msgType, $msgText] = $msg ? explode(':', $msg, 2) : ['',''];
?>
<div class="page-header"><div class="container"><h1><i class="bi bi-person-circle" style="color:var(--primary);"></i> My Profile</h1></div></div>
<div class="container" style="padding-bottom:50px;">
  <?php if ($msgText): ?><div class="alert alert-<?= $msgType === 'success' ? 'success' : 'danger' ?>" data-auto-close><?= e($msgText) ?></div><?php endif; ?>
  <div style="display:grid;grid-template-columns:1fr 1fr;gap:24px;">
    <div class="card" style="padding:28px;">
      <h3 style="font-size:16px;font-weight:700;margin-bottom:20px;">Account Information</h3>
      <form method="POST" enctype="multipart/form-data">
        <!-- Avatar -->
        <div style="text-align:center;margin-bottom:24px;">
          <img id="avatarPreview" src="<?= $user['avatar'] ? e($user['avatar']) : 'https://ui-avatars.com/api/?name='.urlencode($user['name']).'&size=100&background=1565c0&color=fff' ?>" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid var(--primary);" alt="Avatar">
          <div style="margin-top:10px;">
            <label class="upload-area" style="padding:10px 16px;cursor:pointer;display:inline-flex;align-items:center;gap:6px;font-size:13px;">
              <i class="bi bi-camera"></i> Change Photo
              <input type="file" name="avatar" accept="image/*" data-preview="avatarPreview" style="display:none;">
            </label>
          </div>
        </div>
        <div class="form-group"><label class="form-label">Full Name</label><input type="text" name="name" class="form-control" value="<?= e($user['name']) ?>" required></div>
        <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-control" value="<?= e($user['email']) ?>" disabled style="background:#f8f9fa;"></div>
        <div class="form-group"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="<?= e($user['phone'] ?? '') ?>"></div>
        <div class="form-group"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2"><?= e($user['address'] ?? '') ?></textarea></div>
        <button type="submit" class="btn-block"><i class="bi bi-check2"></i> Save Changes</button>
      </form>
    </div>
    <div class="card" style="padding:28px;">
      <h3 style="font-size:16px;font-weight:700;margin-bottom:20px;">Change Password</h3>
      <form method="POST">
        <div class="form-group"><label class="form-label">Current Password</label><input type="password" name="current_password" class="form-control" placeholder="Enter current password"></div>
        <div class="form-group"><label class="form-label">New Password</label><input type="password" name="new_password" class="form-control" placeholder="Min 6 characters"></div>
        <div class="form-group"><label class="form-label">Confirm New Password</label><input type="password" name="confirm_new" class="form-control" placeholder="Repeat new password"></div>
        <input type="hidden" name="change_password" value="1">
        <input type="hidden" name="name" value="<?= e($user['name']) ?>">
        <input type="hidden" name="phone" value="<?= e($user['phone'] ?? '') ?>">
        <input type="hidden" name="address" value="<?= e($user['address'] ?? '') ?>">
        <button type="submit" class="btn-block"><i class="bi bi-lock"></i> Update Password</button>
      </form>
    </div>
  </div>
</div>
<?php require_once 'includes/footer.php'; ?>
