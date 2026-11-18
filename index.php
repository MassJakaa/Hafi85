<?php
// ============================================================
// ROOT WRAPPER — untuk hosting InfinityFree & Shared Hosting
// Meneruskan semua request ke public/index.php serta melayani file statis
// ============================================================
ini_set('display_errors', 0);

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$scriptDir = dirname($_SERVER['SCRIPT_NAME']);
if ($scriptDir !== '/' && strpos($uri, $scriptDir) === 0) {
    $uri = substr($uri, strlen($scriptDir)) ?: '/';
    $_SERVER['REQUEST_URI'] = $uri;
}

// Fallback untuk file statis (/css/app.css, /uploads/..., dll.)
$publicFile = __DIR__ . '/public' . $uri;
if ($uri !== '/' && file_exists($publicFile) && !is_dir($publicFile)) {
    $ext = strtolower(pathinfo($publicFile, PATHINFO_EXTENSION));
    $mimes = [
        'css'  => 'text/css',
        'js'   => 'application/javascript',
        'png'  => 'image/png',
        'jpg'  => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'gif'  => 'image/gif',
        'svg'  => 'image/svg+xml',
        'pdf'  => 'application/pdf',
        'ico'  => 'image/x-icon',
        'woff' => 'font/woff',
        'woff2'=> 'font/woff2',
        'ttf'  => 'font/ttf',
        'cdr'  => 'application/octet-stream',
    ];
    header("Content-Type: " . ($mimes[$ext] ?? 'application/octet-stream'));
    readfile($publicFile);
    exit;
}

require __DIR__ . '/public/index.php';

