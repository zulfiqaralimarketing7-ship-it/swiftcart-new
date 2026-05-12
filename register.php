<?php
$pageTitle = 'Register';
require_once 'includes/config.php';
if (isLoggedIn()) redirect(SITE_URL . '/');
$error = ''; $success = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($conn->real_escape_string($_POST['name'] ?? ''));
    $email = trim($conn->real_escape_string($_POST['email'] ?? ''));
    $phone = trim($conn->real_escape_string($_POST['phone'] ?? ''));
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $role_id = (int)($_POST['role_id'] ?? 3);
    if (!$name || !$email || !$password) { $error = 'Please fill in all required fields.'; }
    elseif ($password !== $confirm) { $error = 'Passwords do not match.'; }
    elseif (strlen($password) < 6) { $error = 'Password must be at least 6 characters.'; }
    else {
        $chk = $conn->query("SELECT id FROM users WHERE email='$email'");
        if ($chk->num_rows > 0) { $error = 'This email is already registered.'; }
        else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $ri = in_array($role_id, [2, 3]) ? $role_id : 3;
            $conn->query("INSERT INTO users (name, email, phone, password, role_id) VALUES ('$name','$email','$phone','$hash',$ri)");
            $success = 'Account created! <a href="' . SITE_URL . '/login.php">Click here to login</a>.';
        }
    }
}
require_once 'includes/header.php';
?>
<div style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:40px 16px;background:var(--bg-light);">
  <div class="auth-card" style="max-width:520px;">
    <div style="text-align:center;margin-bottom:24px;">
      <a href="<?= SITE_URL ?>/" style="font-size:26px;font-weight:900;color:var(--primary);display:inline-flex;align-items:center;gap:8px;"><i class="bi bi-cart3"></i> SwiftCart</a>
      <h2 style="font-size:20px;font-weight:700;margin-top:16px;color:#1a1a2e;">Create Account</h2>
      <p style="color:#888;font-size:14px;">Join SwiftCart today</p>
    </div>
    <?php if ($error): ?><div class="alert alert-danger"><?= $error ?></div><?php endif; ?>
    <?php if ($success): ?><div class="alert alert-success"><?= $success ?></div><?php else: ?>
    <form method="POST">
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
        <div class="form-group" style="margin:0;">
          <label class="form-label">Full Name *</label>
          <input type="text" name="name" class="form-control" placeholder="Your name" required value="<?= e($_POST['name'] ?? '') ?>">
        </div>
        <div class="form-group" style="margin:0;">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" class="form-control" placeholder="+92-300-xxx" value="<?= e($_POST['phone'] ?? '') ?>">
        </div>
      </div>
      <div class="form-group" style="margin-top:14px;">
        <label class="form-label">Email Address *</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email" required value="<?= e($_POST['email'] ?? '') ?>">
      </div>
      <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:14px;">
        <div class="form-group" style="margin:0;">
          <label class="form-label">Password *</label>
          <input type="password" name="password" class="form-control" placeholder="Min 6 chars" required>
        </div>
        <div class="form-group" style="margin:0;">
          <label class="form-label">Confirm Password *</label>
          <input type="password" name="confirm_password" class="form-control" placeholder="Repeat password" required>
        </div>
      </div>
      <div class="form-group" style="margin-top:14px;">
        <label class="form-label">Register as</label>
        <select name="role_id" class="form-select">
          <option value="3">Customer</option>
          <option value="2">Seller</option>
        </select>
      </div>
      <button type="submit" class="btn-block" style="margin-top:8px;"><i class="bi bi-person-plus"></i> Create Account</button>
    </form>
    <?php endif; ?>
    <div style="text-align:center;margin-top:20px;font-size:13px;color:#666;">
      Already have an account? <a href="<?= SITE_URL ?>/login.php" style="color:var(--primary);font-weight:600;">Sign In</a>
    </div>
  </div>
</div>
<?php require_once 'includes/footer.php'; ?>
