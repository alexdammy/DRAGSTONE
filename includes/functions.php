<?php
/* =========================
   General helpers
   ========================= */
function esc(string $s): string { return htmlspecialchars($s, ENT_QUOTES, 'UTF-8'); }

function price($n): string { return number_format((float)$n, 2); }

/** Build an absolute URL from a project-relative path. Requires BASE_URL in config.php */
function url(string $path = ''): string {
    $base = rtrim(BASE_URL, '/');
    $path = '/' . ltrim($path, '/');
    return $base . $path;
}


/* =========================
   Session + user helpers
   (flash helpers live in includes/session.php)
   ========================= */
function ensure_session(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        session_start();
    }
}

function current_user(): ?array {
    ensure_session();
    return $_SESSION['user'] ?? null;
}

function is_logged_in(): bool {
    return current_user() !== null;
}

function logout_user(): void {
    ensure_session();
    $_SESSION = [];
    session_destroy();
}


/* =========================
   Cart helpers
   ========================= */
/** Return a reference to the cart array in session. Shape: id => ['id','name','price','qty','image_url'] */
function &cart_ref(): array {
    ensure_session();
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }
    return $_SESSION['cart'];
}

function cart_count(): int {
    $c = 0;
    foreach (cart_ref() as $it) {
        $c += (int)$it['qty'];
    }
    return $c;
}

function cart_total(): float {
    $t = 0.0;
    foreach (cart_ref() as $it) {
        $t += (float)$it['price'] * (int)$it['qty'];
    }
    return $t;
}


/* =========================
   Image upload helpers
   Requires in config.php:
     define('UPLOAD_DIR', __DIR__ . '/../uploads');
     define('UPLOAD_URL', BASE_URL . 'uploads/');
   ========================= */

/**
 * Save an uploaded image and return a relative path like "uploads/abc123.jpg".
 * Returns null if no file was provided. Throws Exception on validation/save errors.
 */
function save_uploaded_image(?array $file): ?string {
    if (!$file || !isset($file['error']) || $file['error'] === UPLOAD_ERR_NO_FILE) {
        return null; // nothing uploaded
    }
    if ($file['error'] !== UPLOAD_ERR_OK) {
        throw new Exception('Upload failed (error code ' . $file['error'] . ').');
    }

    // Limit ~3MB
    if ($file['size'] > 3 * 1024 * 1024) {
        throw new Exception('Image too large (max 3MB).');
    }

    // Validate real mime
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($file['tmp_name']);
    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
    ];
    if (!isset($allowed[$mime])) {
        throw new Exception('Invalid image type. Allowed: JPG, PNG, WEBP, GIF.');
    }

    // Ensure uploads dir exists
    if (!defined('UPLOAD_DIR')) {
        throw new Exception('UPLOAD_DIR is not defined. Add it to includes/config.php.');
    }
    if (!is_dir(UPLOAD_DIR)) {
        if (!mkdir(UPLOAD_DIR, 0775, true)) {
            throw new Exception('Upload directory not writable.');
        }
    }

    $ext  = $allowed[$mime];
    $name = bin2hex(random_bytes(8)) . '.' . $ext;
    $dest = rtrim(UPLOAD_DIR, '/\\') . '/' . $name;

    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        throw new Exception('Failed to move uploaded file.');
    }

    // Store relative path in DB (portable)
    return 'uploads/' . $name;
}

/** Is this path pointing to a local upload we manage? */
function is_local_upload_path(string $path): bool {
    return (bool)preg_match('~^/?uploads/~', $path);
}

/** Convert "uploads/xxx.jpg" to filesystem path like ".../DRAGSTONE/uploads/xxx.jpg" */
function local_upload_fs_path(string $relative): string {
    $relative = ltrim($relative, '/');
    return __DIR__ . '/../' . $relative;
}