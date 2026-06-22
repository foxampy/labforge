<?php
// Dev-only router for PHP built-in server. Mimics .htaccess:
// serve real static files as-is, route everything else to index.php.
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$full = __DIR__ . $path;
if ($path !== '/' && is_file($full)) {
    return false; // let the built-in server serve the static asset
}
require __DIR__ . '/index.php';
