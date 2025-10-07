<?php
// /admin/user_update.php — Handles role updates
require_once __DIR__ . '/layout/header.php'; // includes admin_guard() + db()

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ' . admin_url('users.php'));
  exit;
}

$user_id = (int)($_POST['user_id'] ?? 0);
$new_role = trim($_POST['role'] ?? '');

if ($user_id <= 0 || !in_array($new_role, ['admin', 'customer'])) {
  set_flash('danger', 'Invalid input.');
  header('Location: ' . admin_url('users.php'));
  exit;
}

// Prevent admin from removing their own privileges
$me = current_user();
if ($me && $me['id'] == $user_id) {
  set_flash('info', 'You cannot change your own role.');
  header('Location: ' . admin_url('users.php'));
  exit;
}

$stmt = db()->prepare("UPDATE users SET role=? WHERE id=?");
$stmt->execute([$new_role, $user_id]);

set_flash('success', 'User role updated.');
header('Location: ' . admin_url('users.php'));
exit;