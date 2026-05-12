<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'swiftcart_4');
define('SITE_URL', 'http://localhost/swiftcart-new');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', SITE_URL . '/uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024);

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);
$conn->set_charset("utf8mb4");
session_start();

function isLoggedIn() { return isset($_SESSION['user_id']); }
function isAdmin() { return isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1; }
function isSeller() { return isset($_SESSION['role_id']) && in_array($_SESSION['role_id'], [1, 2]); }
function requireLogin() { if (!isLoggedIn()) { header('Location: ' . SITE_URL . '/login.php'); exit; } }
function requireAdmin() { if (!isAdmin()) { header('Location: ' . SITE_URL . '/'); exit; } }
function requireSeller() { if (!isSeller()) { header('Location: ' . SITE_URL . '/'); exit; } }
function e(?string $s): string { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function redirect(string $url) { header('Location: ' . $url); exit; }

function uploadImage(array $file, string $subfolder = '') {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return null;
    if ($file['size'] > MAX_FILE_SIZE) return null;
    $allowed = ['jpg','jpeg','png','gif','webp'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, $allowed)) return null;
    if (function_exists('finfo_open')) {
        $fi = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($fi, $file['tmp_name']);
        finfo_close($fi);
        if (!in_array($mime, ['image/jpeg','image/jpg','image/png','image/gif','image/webp'])) return null;
    }
    $dir = UPLOAD_DIR . ($subfolder ? $subfolder . DIRECTORY_SEPARATOR : '');
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    $fname = uniqid('img_', true) . '.' . $ext;
    if (move_uploaded_file($file['tmp_name'], $dir . $fname))
        return UPLOAD_URL . ($subfolder ? $subfolder . '/' : '') . $fname;
    return null;
}

function getCartCount(mysqli $conn): int {
    if (!isLoggedIn()) return 0;
    $uid = $_SESSION['user_id'];
    $r = $conn->query("SELECT SUM(quantity) as c FROM cart WHERE user_id=$uid");
    return (int)($r->fetch_assoc()['c'] ?? 0);
}

function getWishlistCount(mysqli $conn): int {
    if (!isLoggedIn()) return 0;
    $uid = $_SESSION['user_id'];
    $r = $conn->query("SELECT COUNT(*) as c FROM wishlist WHERE user_id=$uid");
    return (int)($r->fetch_assoc()['c'] ?? 0);
}

function renderStars(float $rating): string {
    $full = floor($rating); $half = ($rating - $full) >= 0.5 ? 1 : 0; $empty = 5 - $full - $half;
    $html = '';
    for ($i = 0; $i < $full; $i++) $html .= '<i class="bi bi-star-fill"></i>';
    if ($half) $html .= '<i class="bi bi-star-half"></i>';
    for ($i = 0; $i < $empty; $i++) $html .= '<i class="bi bi-star"></i>';
    return $html;
}
?>
