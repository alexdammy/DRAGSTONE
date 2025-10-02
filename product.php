<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/header.php';

$slug = $_GET['slug'] ?? '';
if ($slug === '') { header('Location: ' . url('catalog.php')); exit; }

$stmt = db()->prepare("
  SELECT p.*, c.name AS category_name, c.slug AS category_slug
  FROM products p
  JOIN categories c ON c.id = p.category_id
  WHERE p.slug = ?
  LIMIT 1
");
$stmt->execute([$slug]);
$p = $stmt->fetch();
if (!$p) { header('Location: ' . url('catalog.php')); exit; }
?>
<a href="<?= url('products.php?c=' . urlencode($p['category_slug'])) ?>" class="btn btn-link mb-3">
  &larr; <?= esc($p['category_name']) ?>
</a>

<div class="row g-4">
  <div class="col-md-5">
    <?php if (!empty($p['image_url'])): ?>
      <img src="<?= esc($p['image_url']) ?>" class="img-fluid rounded border" alt="">
    <?php else: ?>
      <div class="border rounded p-5 text-center text-muted">No image</div>
    <?php endif; ?>
  </div>
  <div class="col-md-7">
    <h1 class="h3 mb-2"><?= esc($p['name']) ?></h1>
    <?php if (!is_null($p['carbon_score'])): ?>
      <div class="text-muted mb-2">Carbon score: <?= (int)$p['carbon_score'] ?></div>
    <?php endif; ?>
    <div class="lead mb-3">$<?= price($p['price']) ?></div>
    <?php if (!empty($p['description'])): ?>
      <p><?= nl2br(esc($p['description'])) ?></p>
    <?php endif; ?>
    <button class="btn btn-success" disabled>Add to Cart (coming soon)</button>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
