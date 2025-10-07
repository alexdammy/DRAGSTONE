<?php
// /admin/_bootstrap.php
// Common includes + admin guard for all admin pages
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/session.php';

// small helper to build admin-relative links using your existing BASE_URL
function admin_url(string $path = ''): string {
  $base = rtrim(BASE_URL, '/');
  $path = '/admin/' . ltrim($path, '/');
  return $base . $path;
}

// guard
function admin_guard(): void {
  if (!is_logged_in() || (current_user()['role'] ?? 'customer') !== 'admin') {
    set_flash('Admins only. Please log in as admin.', 'info');
    header('Location: ' . url('auth_login.php'));
    exit;
  }
}