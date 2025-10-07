<?php
// /admin/users.php  (Admin → Manage Users)
require_once __DIR__ . '/layout/header.php'; // includes admin_guard(), db(), esc(), admin_url()

/* ---------- Search ---------- */
$q = trim($_GET['q'] ?? '');
$params = [];
$sql = "SELECT id, name, email, role, created_at FROM users";
if ($q !== '') {
  $sql .= " WHERE name LIKE ? OR email LIKE ?";
  $like = '%' . $q . '%';
  $params = [$like, $like];
}
$sql .= " ORDER BY id DESC";
$stmt = db()->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ---------- Admin safety helpers ---------- */
$me         = current_user();
$adminCount = (int) db()->query("SELECT COUNT(*) FROM users WHERE role='admin'")->fetchColumn();
?>
<style>
  .admin-card { background:#111827; border:1px solid #1f2937; border-radius:10px; overflow:hidden; }
  .admin-card .card-header{ background:#0b1220; color:#e5e7eb; font-weight:600; }
  .table thead th { border-bottom-color:#1f2937; }
  .btn-primary { background:#2563eb; border:none; }
  .btn-danger { background:#dc2626; border:none; }
  .btn-secondary { background:#374151; border:none; }
  .badge-soft { background:#1f2937; border:1px solid #374151; color:#e5e7eb; }
  .role-pill { padding:.25rem .5rem; border-radius:999px; background:#1f2937; border:1px solid #374151; color:#e5e7eb; font-size:.8rem; }
</style>

<h1 class="h4 mb-3">👥 Users</h1>

<div class="admin-card mb-3" style="max-width:720px;">
  <div class="card-header p-3">Search</div>
  <div class="card-body p-3">
    <form class="row g-2" method="get" action="<?= admin_url('users.php') ?>">
      <div class="col-sm">
        <input class="form-control" name="q" placeholder="Search name or email…" value="<?= esc($q) ?>">
      </div>
      <div class="col-auto">
        <button class="btn btn-primary">Search</button>
        <a class="btn btn-secondary" href="<?= admin_url('users.php') ?>">Reset</a>
      </div>
    </form>
  </div>
</div>

<div class="admin-card">
  <div class="card-header p-3">All Users</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-dark align-middle mb-0">
        <thead>
          <tr>
            <th style="width:80px">ID</th>
            <th style="min-width:220px">Name</th>
            <th style="min-width:280px">Email</th>
            <th style="width:220px">Role</th>
            <th style="width:180px">Joined</th>
            <th style="width:300px" class="text-end">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $u): ?>
            <?php
              $isSelf    = ((int)$u['id'] === (int)$me['id']);
              $isAdmin   = ($u['role'] === 'admin');
              // Don’t allow deleting yourself, or the last remaining admin
              $canDelete = !$isSelf && (!$isAdmin || $adminCount > 1);
            ?>
            <tr>
              <td>#<?= (int)$u['id'] ?></td>
              <td><?= esc($u['name']) ?></td>
              <td><?= esc($u['email']) ?></td>

              <td>
                <form class="d-flex gap-2 align-items-center" method="post" action="<?= admin_url('user_update.php') ?>">
                  <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                  <select name="role" class="form-select form-select-sm" <?= $isSelf ? 'disabled' : '' ?>>
                    <option value="customer" <?= $u['role']==='customer' ? 'selected' : '' ?>>customer</option>
                    <option value="admin"    <?= $u['role']==='admin'    ? 'selected' : '' ?>>admin</option>
                  </select>
                  <button class="btn btn-sm btn-primary" <?= $isSelf ? 'disabled' : '' ?>>Save</button>
                </form>
              </td>

              <td><?= esc($u['created_at']) ?></td>

              <td class="text-end">
                <a class="btn btn-sm btn-secondary" 
                   href="<?= admin_url('user_orders.php?uid='.(int)$u['id']) ?>">
                   📦 View Orders
                </a>

                <a class="btn btn-sm btn-outline-secondary"
                   href="<?= admin_url('user_view.php?id='.(int)$u['id']) ?>">
                   👁 View Profile
                </a>

                <form method="post" action="<?= admin_url('user_delete.php') ?>"
                      onsubmit="return confirm('Delete this user? This cannot be undone.');"
                      class="d-inline">
                  <input type="hidden" name="user_id" value="<?= (int)$u['id'] ?>">
                  <button class="btn btn-sm btn-danger" <?= !$canDelete ? 'disabled' : '' ?>>Delete</button>
                </form>
              </td>
            </tr>
          <?php endforeach; ?>
          <?php if (!$users): ?>
            <tr>
              <td colspan="6" class="text-center text-secondary py-4">No users found.</td>
            </tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>