<?php
// /admin/index.php — Dragonstone Admin Dashboard
require_once __DIR__ . '/layout/header.php'; // includes admin_guard() and db()

/* ------------ KPIs ------------- */
$totalUsers  = (int) db()->query("SELECT COUNT(*) FROM users")->fetchColumn();
$totalOrders = (int) db()->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$totalRevenue = (float) db()->query("SELECT COALESCE(SUM(total),0) FROM orders")->fetchColumn();

/* ------------ Recent Orders (10) ------------- */
$recent = db()->query("
  SELECT o.id, o.created_at, o.status, o.total, u.name AS user_name, u.email AS user_email
  FROM orders o
  LEFT JOIN users u ON u.id = o.user_id
  ORDER BY o.id DESC
  LIMIT 10
")->fetchAll(PDO::FETCH_ASSOC);

/* ------------ Helpers ------------- */
function kpi_card(string $label, string $value, string $sub = ''): string {
  return <<<HTML
  <div class="col-md-4">
    <div class="card kpi-card">
      <div class="card-body">
        <div class="kpi-label">{$label}</div>
        <div class="kpi-value">{$value}</div>
        <div class="kpi-sub">{$sub}</div>
      </div>
    </div>
  </div>
HTML;
}
?>
<style>
  /* Subtle admin styles on top of your dark layout */
  .kpi-card {
    background:#0b1220;
    border:1px solid #1f2937;
    border-radius: 12px;
    box-shadow: 0 0 0 1px rgba(255,255,255,0.02) inset;
  }
  .kpi-label { color:#94a3b8; font-size:.95rem; margin-bottom:.35rem; }
  .kpi-value { color:#e5e7eb; font-weight:700; font-size:1.8rem; }
  .kpi-sub   { color:#64748b; font-size:.85rem; margin-top:.35rem; }
  .quick-card {
    background:#0b1220; border:1px solid #1f2937; border-radius:12px;
  }
  .quick-card a.btn { min-width:180px; }
  .table thead th { border-bottom-color:#1f2937; }
  .badge-soft { background:#1f2937; border:1px solid #374151; color:#e5e7eb; }
</style>

<h1 class="h4 mb-3">📊 Admin Dashboard</h1>

<!-- KPIs -->
<div class="row g-3 mb-4">
  <?= kpi_card('Total Users', number_format($totalUsers), 'All registered accounts'); ?>
  <?= kpi_card('Total Orders', number_format($totalOrders), 'All time'); ?>
  <?= kpi_card('Total Revenue', '$' . number_format($totalRevenue, 2), 'All time (stored total)'); ?>
</div>

<!-- Quick Links -->
<div class="quick-card card mb-4">
  <div class="card-body d-flex flex-wrap gap-2">
    <a href="<?= admin_url('products.php') ?>" class="btn btn-primary">🛒 Manage Products</a>
    <a href="<?= admin_url('orders.php') ?>" class="btn btn-primary">📦 View Orders</a>
    <a href="<?= admin_url('users.php') ?>" class="btn btn-primary">👥 Manage Users</a>
    <a href="<?= url('index.php') ?>" class="btn btn-outline-light" target="_blank">👁 View Storefront</a>
  </div>
</div>

<!-- Recent Orders -->
<div class="card quick-card">
  <div class="card-header" style="background:#0b1220; color:#e5e7eb; border-bottom:1px solid #1f2937;">
    Recent Orders
  </div>
  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table table-dark align-middle mb-0">
        <thead>
          <tr>
            <th style="width:80px;">ID</th>
            <th>Date</th>
            <th>Customer</th>
            <th>Status</th>
            <th class="text-end" style="width:140px;">Total</th>
            <th style="width:120px;"></th>
          </tr>
        </thead>
        <tbody>
          <?php if (!$recent): ?>
            <tr><td colspan="6" class="text-center text-secondary py-4">No orders yet.</td></tr>
          <?php else: foreach ($recent as $o): ?>
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
                <a href="<?= admin_url('order_view.php?id='.(int)$o['id']) ?>" class="btn btn-sm btn-primary">View</a>
              </td>
            </tr>
          <?php endforeach; endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>