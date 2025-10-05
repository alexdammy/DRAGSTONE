<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/functions.php';
ensure_session();
$user = current_user();
$items = cart_count();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dragonstone Store</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
  <div class="container">
    <a class="navbar-brand" href="<?= url('index.php') ?>">🐉 Dragonstone</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="<?= url('catalog.php') ?>">Catalog</a></li>
      </ul>
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link" href="<?= url('cart.php') ?>">Cart <span class="badge bg-success"><?= (int)$items ?></span></a>
        </li>
        <?php if ($user): ?>
          <li class="nav-item"><span class="nav-link">Hi, <?= esc($user['name']) ?></span></li>
          <li class="nav-item"><a class="nav-link" href="<?= url('auth_logout.php') ?>">Logout</a></li>
        <?php else: ?>
          <li class="nav-item"><a class="nav-link" href="<?= url('auth_login.php') ?>">Login</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= url('auth_register.php') ?>">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container">