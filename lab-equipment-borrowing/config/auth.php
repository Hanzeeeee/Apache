<?php
declare(strict_types=1);
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Local practice login only. Do not use these credentials on a public site.
const ADMIN_USERNAME = 'localadmin';
const ADMIN_PASSWORD = 'Admin123!';

function is_logged_in(): bool
{
    return ($_SESSION['admin'] ?? false) === true;
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: ' . app_url('login.php'));
        exit;
    }
}
