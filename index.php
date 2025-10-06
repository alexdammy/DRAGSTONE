<?php
require_once __DIR__ . '/includes/config.php';
require_once __DIR__ . '/includes/session.php';
require_once __DIR__ . '/includes/functions.php'; // defines url()
header('Location: ' . url('catalog.php'));
exit;