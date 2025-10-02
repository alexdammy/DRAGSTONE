<?php
function esc(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }
function price($n): string { return number_format((float)$n, 2); }
function url(string $path = ''): string {
  $base = rtrim(BASE_URL, '/');
  $path = '/' . ltrim($path, '/');
  return $base . $path;
}
