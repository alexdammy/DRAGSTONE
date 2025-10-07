<?php
// /admin/user_view.php — Admin view single user profile
require_once __DIR__ . '/layout/header.php'; // includes admin_guard()

$uid = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($uid <= 0) {
  set_flash('info', 'Invalid user id.');
  header('Location: ' . admin_url('users.php'));
  exit;
}

$stmt = db()->prepare("SELECT * FROM users WHERE id=? LIMIT 1");
$stmt->execute([$uid]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  set_flash('info', 'User not found.');
  header('Location: ' . admin_url('users.php'));
  exit;
}
?>
<style>
  .admin-card { background:#111827; border:1px solid #1f2937; border-radius:10px; overflow:hidden; }
  .admin-card .card-header{ background:#0b1220; color:#e5e7eb; font-weight:600; }
  .badge-soft { background:#1f2937; border:1px solid #374151; color:#e5e7eb; }
</style>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0">👤 User Profile</h1>
  <a href="<?= admin_url('users.php') ?>" class="btn btn-secondary">← Back to Users</a>
</div>

<div class="admin-card mb-3">
  <div class="card-header p-3">User Information</div>
  <div class="card-body p-3">
    <p><strong>ID:</strong> #<?= (int)$user['id'] ?></p>
    <p><strong>Name:</strong> <?= esc($user['name']) ?></p>
    <p><strong>Email:</strong> <?= esc($user['email']) ?></p>
    <p><strong>Role:</strong> <span class="badge-soft"><?= esc($user['role']) ?></span></p>
    <p><strong>Joined:</strong> <?= esc($user['created_at']) ?></p>
  </div>
</div>

<div class="text-end">
  <a href="<?= admin_url('user_orders.php?uid='.(int)$user['id']) ?>" class="btn btn-primary">
    📦 View Orders
  </a>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>