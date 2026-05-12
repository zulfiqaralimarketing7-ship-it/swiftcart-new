<?php
$pageTitle = 'About Us';
require_once '../includes/config.php';
require_once '../includes/header.php';
?>
<div class="container" style="padding:50px 16px;">
  <div style="max-width:800px;margin:0 auto;">
    <h1 style="font-size:32px;font-weight:900;color:#1a1a2e;margin-bottom:12px;">About SwiftCart</h1>
    <p style="font-size:16px;color:#555;line-height:1.8;margin-bottom:20px;">SwiftCart is your one-stop online shopping destination. We connect buyers with thousands of trusted sellers offering everything from electronics and fashion to home essentials and beauty products.</p>
    <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;margin:40px 0;">
      <div style="background:#fff;border-radius:12px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,.07);text-align:center;"><i class="bi bi-truck" style="font-size:36px;color:var(--primary);"></i><h3 style="margin-top:12px;font-size:16px;">Fast Delivery</h3><p style="font-size:13px;color:#888;">Quick and reliable shipping to your doorstep.</p></div>
      <div style="background:#fff;border-radius:12px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,.07);text-align:center;"><i class="bi bi-shield-check" style="font-size:36px;color:var(--primary);"></i><h3 style="margin-top:12px;font-size:16px;">Secure Payments</h3><p style="font-size:13px;color:#888;">100% secure transactions with multiple payment options.</p></div>
      <div style="background:#fff;border-radius:12px;padding:24px;box-shadow:0 2px 10px rgba(0,0,0,.07);text-align:center;"><i class="bi bi-headset" style="font-size:36px;color:var(--primary);"></i><h3 style="margin-top:12px;font-size:16px;">24/7 Support</h3><p style="font-size:13px;color:#888;">Always available to help you with any issues.</p></div>
    </div>
    <div style="background:#fff;border-radius:12px;padding:28px;box-shadow:0 2px 10px rgba(0,0,0,.07);">
      <h2 style="font-size:20px;font-weight:700;margin-bottom:16px;">Contact Us</h2>
      <p><i class="bi bi-telephone" style="color:var(--primary);"></i> +92-300-1234567</p>
      <p style="margin-top:8px;"><i class="bi bi-envelope" style="color:var(--primary);"></i> help@swiftcart.com</p>
      <p style="margin-top:8px;"><i class="bi bi-geo-alt" style="color:var(--primary);"></i> Karachi, Pakistan</p>
    </div>
  </div>
</div>
<?php require_once '../includes/footer.php'; ?>
