<?php
// /admin/orders.php
require_once __DIR__ . '/layout/header.php'; // includes admin_guard()

// fetch orders newest first
$sql = "SELECT o.id, o.created_at, o.status, o.total,
               u.name AS user_name, u.email AS user_email
        FROM orders o
        LEFT JOIN users u ON u.id = o.user_id
        ORDER BY o.id DESC";
$orders = db()->query($sql)->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
  .admin-card { background:#111827; border:1px solid #1f2937; border-radius:10px; overflow:hidden; }
  .admin-card .card-header{ background:#0b1220; color:#e5e7eb; font-weight:600; }
  .table thead th { border-bottom-color:#1f2937; }
  .btn-primary { background:#2563eb; border:none; }
  .badge-soft { background:#1f2937; border:1px solid #374151; color:#e5e7eb; }
</style>

<h1 class="h4 mb-3">📦 Orders</h1>

<?php if (!$orders): ?>
  <div class="alert alert-info">No orders yet.</div>
<?php else: ?>
<div class="admin-card">
  <div class="card-header p-3">All Orders</div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-dark align-middle mb-0">
        <thead>
          <tr>
            <th>ID</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Status</th>
            <th class="text-end">Total</th>
            <th style="width:150px;"></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
            <tr>
              <td>#<?= (int)$o['id'] ?></td>
              <td><?= htmlspecialchars($o['created_at']) ?></td>
              <td>
                <?= htmlspecialchars($o['user_name'] ?: 'Guest') ?><br>
                <small class="text-secondary"><?= htmlspecialchars($o['user_email'] ?: '-') ?></small>
              </td>
              <td><span class="badge badge-soft"><?= htmlspecialchars($o['status']) ?></span></td>
              <td class="text-end">$<?= number_format($o['total'], 2) ?></td>
              <td>
                <a href="<?= admin_url('order_view.php?id=' . $o['id']) ?>" 
                  class="btn btn-sm btn-primary" 
                  style="padding:6px 12px; font-size:0.9rem; border-radius:5px;">
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