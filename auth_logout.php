<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/functions.php'; // <-- needed for url() + sessions

ensure_session();
$_SESSION = [];
session_destroy();

// redirect anywhere you like:
header('Location: ' . url('catalog.php')); // or url('index.php') if you made a homepage
exit;