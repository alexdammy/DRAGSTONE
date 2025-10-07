<?php
// /admin/user_orders.php — Admin: view a user's orders
require_once __DIR__ . '/layout/header.php'; // includes admin guard + db(), esc(), admin_url()

/* --------- Inputs --------- */
$uid = isset($_GET['uid']) ? (int)$_GET['uid'] : 0;
if ($uid <= 0) {
  set_flash('info', 'Invalid user id.');
  header('Location: ' . admin_url('users.php'));
  exit;
}

/* --------- Fetch user --------- */
$usrStmt = db()->prepare("SELECT id, name, email, role, created_at FROM users WHERE id=? LIMIT 1");
$usrStmt->execute([$uid]);
$user = $usrStmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
  set_flash('info', 'User not found.');
  header('Location: ' . admin_url('users.php'));
  exit;
}

/* --------- Fetch orders for the user --------- */
$ordStmt = db()->prepare("
  SELECT id, created_at, status, total
  FROM orders
  WHERE user_id = ?
  ORDER BY id DESC
");
$ordStmt->execute([$uid]);
$orders = $ordStmt->fetchAll(PDO::FETCH_ASSOC);

/* --------- UI helpers --------- */
function _status_badge_class(string $s): string {
  return [
    'pending'   => 'secondary',
    'paid'      => 'primary',
    'shipped'   => 'warning',
    'completed' => 'success',
    'cancelled' => 'danger',
  ][$s] ?? 'secondary';
}
?>
<style>
  .admin-card { background:#111827; border:1px solid #1f2937; border-radius:10px; overflow:hidden; }
  .admin-card .card-header{ background:#0b1220; color:#e5e7eb; font-weight:600; }
  .table thead th { border-bottom-color:#1f2937; }
  .badge-soft { background:#1f2937; border:1px solid #374151; color:#e5e7eb; }
  .btn-primary { background:#2563eb; border:none; }
  .btn-secondary { background:#374151; border:none; }
</style>

<div class="d-flex align-items-center justify-content-between mb-3">
  <h1 class="h4 mb-0">👤 User Orders</h1>
  <a href="<?= admin_url('users.php') ?>" class="btn btn-secondary">← Back to Users</a>
</div>

<div class="admin-card mb-4">
  <div class="card-header p-3">User</div>
  <div class="card-body p-3">
    <div class="row g-3 align-items-center">
      <div class="col-md-6">
        <div><strong>Name:</strong> <?= esc($user['name']) ?></div>
        <div><strong>Email:</strong> <?= esc($user['email']) ?></div>
      </div>
      <div class="col-md-6">
        <div><strong>Role:</strong> <span class="badge-soft" style="padding:.25rem .5rem; border-radius:999px;"><?= esc($user['role']) ?></span></div>
        <div><strong>Joined:</strong> <?= esc($user['created_at']) ?></div>
      </div>
    </div>
  </div>
</div>

<?php if (!$orders): ?>
  <div class="alert alert-info">This user has no orders yet.</div>
<?php else: ?>
  <div class="admin-card">
    <div class="card-header p-3">Orders (<?= count($orders) ?>)</div>
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-dark align-middle mb-0">
          <thead>
            <tr>
              <th style="width:90px;">ID</th>
              <th>Date</th>
              <th style="width:160px;">Status</th>
              <th class="text-end" style="width:140px;">Total</th>
              <th style="width:150px;"></th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($orders as $o): ?>
              <tr>
                <td>#<?= (int)$o['id'] ?></td>
                <td><?= esc($o['created_at']) ?></td>
                <td>
                  <span class="badge bg-<?= _status_badge_class($o['status']) ?>">
                    <?= esc($o['status']) ?>
                  </span>
                </td>
                <td class="text-end">$<?= number_format((float)$o['total'], 2) ?></td>
                <td>
                  <a class="btn btn-sm btn-primary"
                     href="<?= admin_url('order_view.php?id='.(int)$o['id']) ?>">
                    👁 View
                  </a>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
<?php endif; ?>

<?php require_once __DIR__ . '/layout/footer.php'; ?>