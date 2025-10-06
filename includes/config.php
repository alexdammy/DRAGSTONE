<?php
// Base URL of your app (note trailing slash)
define('BASE_URL', 'http://localhost:8888/DRAGSTONE/');
// Where images are stored on disk and how they’re served
define('UPLOAD_DIR', __DIR__ . '/../uploads');        // filesystem path
define('UPLOAD_URL', BASE_URL . 'uploads/');          // public URL

// DB config (your values may differ)
define('DB_HOST', 'localhost');
define('DB_PORT', 8889);
define('DB_NAME', 'dragonstone');
define('DB_USER', 'root');
define('DB_PASS', 'root');