<?php
$pageTitle = 'Login';
require_once 'includes/config.php';
if (isLoggedIn()) redirect(SITE_URL . '/');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($conn->real_escape_string($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    if ($email && $password) {
        $r = $conn->query("SELECT u.*, ro.name as role_name FROM users u JOIN roles ro ON u.role_id=ro.id WHERE u.email='$email' LIMIT 1");
        if ($r && $r->num_rows > 0) {
            $user = $r->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                $_SESSION['user_email'] = $user['email'];
                $_SESSION['role_id'] = $user['role_id'];
                $_SESSION['role_name'] = $user['role_name'];
                $dest = $_GET['redirect'] ?? SITE_URL . '/';
                redirect($dest);
            }
        }
        $error = 'Invalid email or password. Please try again.';
    } else { $error = 'Please fill in all fields.'; }
}
require_once 'includes/header.php';
?>
<div style="min-height:70vh;display:flex;align-items:center;justify-content:center;padding:40px 16px;background:var(--bg-light);">
  <div class="auth-card">
    <div style="text-align:center;margin-bottom:28px;">
      <a href="<?= SITE_URL ?>/" style="font-size:26px;font-weight:900;color:var(--primary);display:inline-flex;align-items:center;gap:8px;"><i class="bi bi-cart3"></i> SwiftCart</a>
      <h2 style="font-size:20px;font-weight:700;margin-top:16px;color:#1a1a2e;">Welcome Back!</h2>
      <p style="color:#888;font-size:14px;">Sign in to your account</p>
    </div>
    <?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
    <form method="POST">
      <div class="form-group">
        <label class="form-label">Email Address</label>
        <input type="email" name="email" class="form-control" placeholder="Enter your email" value="<?= e($_POST['email'] ?? '') ?>" required>
      </div>
      <div class="form-group">
        <label class="form-label">Password</label>
        <div style="position:relative;">
          <input type="password" name="password" id="passInput" class="form-control" placeholder="Enter your password" required>
          <button type="button" onclick="var i=document.getElementById('passInput');i.type=i.type==='password'?'text':'password'" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:none;border:none;color:#888;cursor:pointer;"><i class="bi bi-eye"></i></button>
        </div>
      </div>
      <button type="submit" class="btn-block" style="margin-top:8px;"><i class="bi bi-box-arrow-in-right"></i> Sign In</button>
    </form>
    <div style="text-align:center;margin-top:20px;font-size:13px;color:#666;">
      Don't have an account? <a href="<?= SITE_URL ?>/register.php" style="color:var(--primary);font-weight:600;">Register here</a>
    </div>
    <div style="margin-top:20px;padding:14px;background:#f8f9fa;border-radius:8px;font-size:12px;color:#666;">
      <strong>Demo accounts:</strong><br>
      Admin: admin@swiftcart.com | Seller: hamza@swiftcart.com | Customer: ali@swiftcart.com<br>
      <strong>Password:</strong> password
    </div>
  </div>
</div>
<?php require_once 'includes/footer.php'; ?>
