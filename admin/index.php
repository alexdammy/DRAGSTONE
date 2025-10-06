<?php
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/header.php';

// Only admins can view this page
$user = current_user();
if (!$user || $user['role'] !== 'admin') {
    set_flash('Access denied — Admins only.', 'info');
    header('Location: ' . url('auth_login.php'));
    exit;
}

// Fetch all products
$stmt = db()->query("SELECT p.*, c.name AS category_name 
                     FROM products p
                     LEFT JOIN categories c ON c.id = p.category_id
                     ORDER BY p.id DESC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<h1 class="h4 mb-3">🛠 Admin Dashboard</h1>

<a href="<?= url('admin/product_add.php') ?>" class="btn btn-success mb-3">+ Add New Product</a>

<table class="table table-bordered align-middle">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Category</th>
      <th>Price</th>
      <th>Stock</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($products as $p): ?>
      <tr>
        <td><?= $p['id'] ?></td>
        <td><?= esc($p['name']) ?></td>
        <td><?= esc($p['category_name']) ?></td>
        <td>$<?= price($p['price']) ?></td>
        <td><?= $p['stock'] ?></td>
        <td>
          <a href="<?= url('admin/product_edit.php?id=' . $p['id']) ?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="<?= url('admin/product_delete.php?id=' . $p['id']) ?>" class="btn btn-sm btn-danger"
             onclick="return confirm('Delete this product?')">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>