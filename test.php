<?php
die('Script diagnosis sudah selesai dan dikunci.');
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h2>🔍 Diagnosis Server InfinityFree</h2>";
echo "<pre>";

// ── Test 1: PHP berjalan? ─────────────────────────────────────────────────
echo "✅ PHP Version: " . PHP_VERSION . "\n\n";

// ── Test 2: Baca config.php ───────────────────────────────────────────────
$configPath = __DIR__ . '/config.php';
if (!file_exists($configPath)) {
    echo "❌ config.php TIDAK DITEMUKAN di: $configPath\n\n";
} else {
    echo "✅ config.php ditemukan\n";
    $cfg = require $configPath;
    echo "   host    : " . $cfg['host']     . "\n";
    echo "   dbname  : " . $cfg['dbname']   . "\n";
    echo "   username: " . $cfg['username'] . "\n";
    echo "   password: " . (empty($cfg['password']) ? "⚠️  KOSONG!" : "✅ (diisi)") . "\n\n";

    // ── Test 3: Koneksi database ─────────────────────────────────────────
    try {
        $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['dbname']};charset={$cfg['charset']}";
        $db  = new PDO($dsn, $cfg['username'], $cfg['password']);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $count = $db->query("SELECT COUNT(*) FROM price_cetak")->fetchColumn();
        echo "✅ DATABASE TERHUBUNG!\n";
        echo "   Tabel price_cetak: $count baris\n\n";
    } catch (PDOException $e) {
        echo "❌ KONEKSI DATABASE GAGAL:\n";
        echo "   " . $e->getMessage() . "\n\n";
    }
}

// ── Test 4: Cek folder & file penting ────────────────────────────────────
$checks = [
    'public/index.php'  => 'Router utama',
    'includes/db.php'   => 'Database helper',
    'includes/auth.php' => 'Auth helper',
    'views/auth/login.php' => 'Halaman login',
    'public/uploads'    => 'Folder uploads',
    'public/.htaccess'  => '.htaccess public',
];

echo "── Cek File & Folder ─────────────────────\n";
foreach ($checks as $path => $label) {
    $full   = __DIR__ . '/' . $path;
    $exists = file_exists($full);
    echo ($exists ? "✅" : "❌") . " $label ($path)\n";
}

echo "\n── REQUEST_URI & SERVER INFO ─────────────\n";
echo "REQUEST_URI  : " . $_SERVER['REQUEST_URI']  . "\n";
echo "DOCUMENT_ROOT: " . $_SERVER['DOCUMENT_ROOT'] . "\n";
echo "SCRIPT_NAME  : " . $_SERVER['SCRIPT_NAME']   . "\n";

echo "</pre>";
echo "<hr><p style='color:gray;font-size:12px'>Hapus file test.php ini setelah diagnosis selesai!</p>";
