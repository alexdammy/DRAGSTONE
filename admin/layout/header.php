<?php
// /admin/layout/header.php
require_once __DIR__ . '/../_bootstrap.php';
admin_guard(); // protect every admin page using this header

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dragonstone Admin</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <style>
    body { background:#0f172a; color:#e5e7eb; }
    .admin-topbar { background:#111827; }
    .admin-topbar .brand { color:#34d399; font-weight:700; text-decoration:none; }
    .admin-topbar a { color:#d1d5db; text-decoration:none; margin-right:1rem; }
    .admin-topbar a:hover { color:#34d399; }
    .admin-card { background:#111827; border:1px solid #1f2937; }
    .table-dark th, .table-dark td { vertical-align: middle; }
    .badge-soft { background:#1f2937; border:1px solid #374151; color:#e5e7eb; }
  </style>
</head>
<body>

<nav class="admin-topbar py-3 mb-4">
  <div class="container d-flex align-items-center justify-content-between">
    <div>
      <a class="brand me-4" href="<?= admin_url('index.php') ?>">🐉 Dragonstone Admin</a>
      <a href="<?= admin_url('products.php') ?>">Products</a>
      <a href="<?= admin_url('orders.php') ?>">Orders</a>
      <a href="<?= admin_url('users.php') ?>">Users</a>
    </div>
    <div>
      <span class="me-3">👋 <?= esc(current_user()['name'] ?? 'Admin') ?></span>
      <a class="btn btn-sm btn-outline-light" href="<?= url('auth_logout.php') ?>">Logout</a>
      <a class="btn btn-sm btn-success ms-2" href="<?= url('index.php') ?>">View Store</a>
    </div>
  </div>
</nav>

<div class="container">
<?php
$flash = get_flash();
if ($flash): ?>
  <div class="alert <?= $flash['type']==='info' ? 'alert-info' : 'alert-success' ?>"><?= esc($flash['msg']) ?></div>
<?php endif; ?>