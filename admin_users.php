<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/header.php';

if (!is_logged_in() || (current_user()['role'] ?? '') !== 'admin') {
  set_flash('Access denied — Admins only.', 'info');
  header('Location: ' . url('auth_login.php')); exit;
}

$pdo = db();
$users = $pdo->query("SELECT id, name, email, role, created_at FROM users ORDER BY id DESC")
             ->fetchAll(PDO::FETCH_ASSOC);
?>
<h1 class="h4 mb-3">👥 Manage Users</h1>

<div class="card">
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-striped table-hover align-middle mb-0">
        <thead class="table-dark">
          <tr>
            <th style="width:70px;">ID</th>
            <th>Name</th>
            <th>Email</th>
            <th style="width:120px;">Role</th>
            <th style="width:180px;">Joined</th>
          </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
          <tr>
            <td><?= (int)$u['id'] ?></td>
            <td><?= esc($u['name']) ?></td>
            <td><?= esc($u['email']) ?></td>
            <td><?= esc($u['role']) ?></td>
            <td><?= esc($u['created_at']) ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (!$users): ?>
          <tr><td colspan="5" class="text-center py-4 text-muted">No users yet.</td></tr>
        <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/includes/footer.php'; ?>