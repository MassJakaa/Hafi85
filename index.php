<?php
// ============================================================
// ROOT WRAPPER — untuk hosting InfinityFree
// Meneruskan semua request ke public/index.php
// ============================================================
ini_set('display_errors', 0);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
if ($scriptDir !== '/' && strpos($uri, $scriptDir) === 0) {
    $uri = substr($uri, strlen($scriptDir)) ?: '/';
    $_SERVER['REQUEST_URI'] = $uri;
}

require __DIR__ . '/public/index.php';
