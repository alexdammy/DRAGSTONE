<?php
// Start the session once
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* Flash helpers only — no user/cart helpers here */
if (!function_exists('set_flash')) {
    function set_flash(string $msg, string $type = 'success'): void {
        $_SESSION['flash'] = ['msg' => $msg, 'type' => $type];
    }
}

if (!function_exists('get_flash')) {
    function get_flash(): ?array {
        if (!empty($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
}