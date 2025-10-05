<?php
// GENERAL
function esc(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function price($n): string { return number_format((float)$n, 2); }
function url(string $path = ''): string {
  $base = rtrim(BASE_URL, '/');
  $path = '/' . ltrim($path, '/');
  return $base . $path;
}

// SESSION (ensure started)
function ensure_session(): void {
  if (session_status() !== PHP_SESSION_ACTIVE) session_start();
}

// USER
function current_user(): ?array {
  ensure_session();
  return $_SESSION['user'] ?? null;
}
function is_logged_in(): bool {
  return current_user() !== null;
}

// CART
function &cart_ref(): array {
  ensure_session();
  if (!isset($_SESSION['cart'])) $_SESSION['cart'] = []; // id => ['id'=>, 'name'=>, 'price'=>, 'qty'=>, 'image_url'=>]
  return $_SESSION['cart'];
}
function cart_count(): int {
  $c = 0; foreach (cart_ref() as $item) $c += (int)$item['qty']; return $c;
}
function cart_total(): float {
  $t = 0.0; foreach (cart_ref() as $it) $t += (float)$it['price'] * (int)$it['qty']; return $t;
}