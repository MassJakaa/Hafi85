<?php
/**
 * HaFI85 Digital Printing — Konfigurasi Database MySQL
 * Mendukung Railway, Cloud Hosting (Environment Variables), dan Localhost.
 */

$host     = getenv('MYSQLHOST') ?: getenv('DB_HOST');
$port     = getenv('MYSQLPORT') ?: getenv('DB_PORT');
$dbname   = getenv('MYSQLDATABASE') ?: getenv('DB_NAME');
$username = getenv('MYSQLUSER') ?: getenv('DB_USER');
$password = getenv('MYSQLPASSWORD') !== false ? getenv('MYSQLPASSWORD') : (getenv('DB_PASS') !== false ? getenv('DB_PASS') : null);

// Fallback jika menggunakan format URL (DATABASE_URL / MYSQL_URL) dari Railway
$dbUrl = getenv('MYSQL_URL') ?: getenv('DATABASE_URL');
if ($dbUrl && (!$host || !$dbname)) {
    $parsed = parse_url($dbUrl);
    if ($parsed) {
        $host     = $parsed['host'] ?? $host;
        $port     = $parsed['port'] ?? $port;
        $username = $parsed['user'] ?? $username;
        $password = $parsed['pass'] ?? $password;
        $dbname   = isset($parsed['path']) ? ltrim($parsed['path'], '/') : $dbname;
    }
}

return [
    'host'     => $host ?: 'localhost',
    'port'     => (int)($port ?: 3306),
    'dbname'   => $dbname ?: 'hafi85_db',
    'username' => $username ?: 'root',
    'password' => $password !== null ? $password : '',
    'charset'  => 'utf8mb4',
];
