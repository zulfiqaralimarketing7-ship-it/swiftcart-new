<!-- FEATURES BAR -->
<div class="features-bar">
  <div class="container">
    <div class="features-grid">
      <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-truck"></i></div>
        <div class="feature-text"><h6>Super Fast Delivery</h6><p>Get your order delivered to your doorstep quickly.</p></div>
      </div>
      <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-shield-lock"></i></div>
        <div class="feature-text"><h6>Secure & Safe Payment</h6><p>Multiple secure payment options available.</p></div>
      </div>
      <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-arrow-repeat"></i></div>
        <div class="feature-text"><h6>Easy Returns & Refunds</h6><p>Not satisfied? Get easy returns and refunds.</p></div>
      </div>
      <div class="feature-item">
        <div class="feature-icon"><i class="bi bi-headset"></i></div>
        <div class="feature-text"><h6>24/7 Customer Support</h6><p>We're here to help you anytime, anywhere.</p></div>
      </div>
    </div>
  </div>
</div>
<!-- FOOTER -->
<footer class="main-footer">
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <h3><i class="bi bi-cart3"></i> SwiftCart</h3>
        <p>Your one-stop shop for everything you need. Fast delivery, best prices, and amazing deals.</p>
        <div class="social-links">
          <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
          <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
          <a href="#" class="social-link"><i class="bi bi-twitter-x"></i></a>
          <a href="#" class="social-link"><i class="bi bi-youtube"></i></a>
        </div>
      </div>
      <div class="footer-col">
        <h5>Quick Links</h5>
        <ul class="footer-links">
          <li><a href="<?= SITE_URL ?>/pages/about.php">About Us</a></li>
          <li><a href="<?= SITE_URL ?>/pages/about.php">Contact Us</a></li>
          <li><a href="<?= SITE_URL ?>/orders.php">Track Order</a></li>
          <li><a href="#">FAQs</a></li>
          <li><a href="#">Shipping & Delivery</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h5>Customer Service</h5>
        <ul class="footer-links">
          <li><a href="#">Returns & Refunds</a></li>
          <li><a href="#">Terms & Conditions</a></li>
          <li><a href="#">Privacy Policy</a></li>
          <li><a href="#">Help Center</a></li>
        </ul>
      </div>
      <div class="footer-col">
        <h5>Newsletter</h5>
        <div class="newsletter-box">
          <p>Subscribe to get exclusive offers and latest updates.</p>
          <form class="newsletter-form" onsubmit="this.innerHTML='<p style=color:#6ee7b7;padding:10px 0;font-size:13px;>✓ Subscribed successfully!</p>';return false;">
            <input type="email" placeholder="Enter your email" required>
            <button type="submit">Subscribe</button>
          </form>
        </div>
      </div>
    </div>
    <div class="footer-bottom">
      <p>&copy; <?= date('Y') ?> SwiftCart. All rights reserved.</p>
      <div class="payment-icons">
        <span class="payment-icon" style="color:#1565c0;">VISA</span>
        <span class="payment-icon" style="color:#eb001b;">MC</span>
        <span class="payment-icon" style="color:#f57f17;">UPI</span>
        <span class="payment-icon" style="background:#00baf2;color:#fff;">Paytm</span>
      </div>
    </div>
  </div>
</footer>
<script src="<?= SITE_URL ?>/assets/js/bootstrap.bundle.min.js"></script>
<script src="<?= SITE_URL ?>/assets/js/main.js"></script>
</body></html>
