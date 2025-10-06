<?php
// Always load BASE_URL first
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/session.php';    // starts session + flash helpers
require_once __DIR__ . '/functions.php';  // url(), esc(), current_user(), cart_count(), etc.
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dragonstone Store</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
    body { padding-bottom: 50px; }
    nav.navbar {
      background-color: #111827;
      padding: 0.8rem 1rem;
    }
    nav.navbar a.navbar-brand {
      color: #10b981;
      font-weight: bold;
      font-size: 1.25rem;
      text-decoration: none;
    }
    nav.navbar a {
      color: #d1d5db;
      text-decoration: none;
      margin-right: 1rem;
    }
    nav.navbar a:hover {
      color: #10b981;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= url('index.php') ?>">🐉 Dragonstone</a>
    <div>
      <a href="<?= url('catalog.php') ?>">Catalog</a>

      <?php if (is_logged_in()): ?>
        <a href="<?= url('cart.php') ?>">Cart 🛒 (<?= cart_count() ?>)</a>
        <span style="color:#9ca3af;">|</span>
        <span style="color:#10b981;">👋 <?= esc(current_user()['name']) ?></span>
        <?php if (current_user()['role'] === 'admin'): ?>
  <a href="<?= url('admin.php') ?>">Dashboard</a>
<?php endif; ?>
        <a href="<?= url('auth_logout.php') ?>">Logout</a>
      <?php else: ?>
        <a href="<?= url('cart.php') ?>">Cart 🛒 (<?= cart_count() ?>)</a>
        <a href="<?= url('auth_login.php') ?>">Login</a>
        <a href="<?= url('auth_register.php') ?>">Register</a>
      <?php endif; ?>
    </div>
  </div>
</nav>

<?php
// Flash banner (success/info)
$flash = get_flash();
if ($flash):
?>
  <div style="
    background: <?= $flash['type'] === 'info' ? '#dbeafe' : '#dcfce7' ?>;
    color: #111827;
    padding: 10px;
    border-radius: 6px;
    margin: 10px auto;
    max-width: 960px;
    text-align: center;
  ">
    <?= esc($flash['msg']) ?>
  </div>
<?php endif; ?>

<div class="container mt-4">