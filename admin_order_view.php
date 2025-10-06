<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/header.php';

if (!is_logged_in() || current_user()['role'] !== 'admin') {
  set_flash('Admins only.','info');
  header('Location: ' . url('auth_login.php'));
  exit;
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id <= 0) {
  set_flash('Invalid order id','info');
  header('Location: ' . url('admin_orders.php'));
  exit;
}

// order header
$stmt = db()->prepare("
  SELECT o.*, u.name AS user_name, u.email AS user_email
  FROM orders o
  LEFT JOIN users u ON u.id = o.user_id
  WHERE o.id = ?
  LIMIT 1
");
$stmt->execute([$id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
  set_flash('Order not found','info');
  header('Location: ' . url('admin_orders.php'));
  exit;
}

// line items
$itemsStmt = db()->prepare("
  SELECT oi.qty, oi.price, p.name, p.slug
  FROM order_items oi
  JOIN products p ON p.id = oi.product_id
  WHERE oi.order_id = ?
  ORDER BY oi.id ASC
");
$itemsStmt->execute([$id]);
$items = $itemsStmt->fetchAll(PDO::FETCH_ASSOC);

// compute total from items (for display check)
$computedTotal = 0.00;
foreach ($items as $it) $computedTotal += $it['qty'] * $it['price'];
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4 mb-0">Order #<?= (int)$order['id'] ?></h1>
  <a href="admin_orders.php" 
   class="btn btn-secondary"
   style="background:#374151; border:none; color:white; padding:8px 14px; font-size:0.9rem; border-radius:6px;">
   ← Back to Orders
</a>

</div>

<div class="mb-3">
  <strong>Status:</strong> <?= htmlspecialchars($order['status']) ?><br>
  <strong>Date:</strong> <?= htmlspecialchars($order['created_at']) ?><br>
  <strong>Customer:</strong>
  <?= htmlspecialchars($order['user_name'] ?: 'Guest') ?>
  (<?= htmlspecialchars($order['user_email'] ?: '-') ?>)
</div>

<table class="table table-bordered align-middle">
  <thead class="table-light">
    <tr>
      <th>Product</th>
      <th class="text-end">Qty</th>
      <th class="text-end">Price</th>
      <th class="text-end">Subtotal</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($items as $it): ?>
      <tr>
        <td><?= htmlspecialchars($it['name']) ?></td>
        <td class="text-end"><?= (int)$it['qty'] ?></td>
        <td class="text-end">$<?= number_format($it['price'], 2) ?></td>
        <td class="text-end">$<?= number_format($it['qty'] * $it['price'], 2) ?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="3" class="text-end">Stored Total:</th>
      <th class="text-end">$<?= number_format($order['total'], 2) ?></th>
    </tr>
    <tr>
      <th colspan="3" class="text-end">Computed Total:</th>
      <th class="text-end">$<?= number_format($computedTotal, 2) ?></th>
    </tr>
  </tfoot>
</table>

<?php require_once __DIR__ . '/includes/footer.php'; ?>