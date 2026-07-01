<?php
/**
 * API: GET /api/prices
 * Query params:
 *   type  = cetak | finishing | barang | jenis_cetak
 *   jenis = (filter by jenis_cetak, opsional)
 */
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';

header('Content-Type: application/json; charset=utf-8');

$type  = $_GET['type']  ?? '';
$jenis = $_GET['jenis'] ?? '';

switch ($type) {
    case 'check_member':
        // Cek apakah nomor HP sudah terdaftar, dan apakah member
        $phone = trim($_GET['phone'] ?? '');
        if (!$phone) { echo json_encode(['found' => false]); break; }
        $cust = dbSelectOne("SELECT id, name, is_member FROM customers WHERE phone = ?", [$phone]);
        if ($cust) {
            echo json_encode([
                'found'     => true,
                'name'      => $cust->name,
                'is_member' => (bool)$cust->is_member,
            ]);
        } else {
            echo json_encode(['found' => false]);
        }
        break;

    case 'jenis_cetak':
        // Daftar unik jenis cetak
        $rows = dbSelect("SELECT DISTINCT jenis_cetak FROM price_cetak ORDER BY jenis_cetak");
        echo json_encode(array_map(fn($r) => $r->jenis_cetak, $rows));
        break;

    case 'cetak':
        if ($jenis) {
            $rows = dbSelect("SELECT * FROM price_cetak WHERE jenis_cetak=? ORDER BY nama_cetak", [$jenis]);
        } else {
            $rows = dbSelect("SELECT * FROM price_cetak ORDER BY jenis_cetak, nama_cetak");
        }
        echo json_encode($rows);
        break;

    case 'finishing':
        $rows = dbSelect("SELECT * FROM price_finishing ORDER BY jenis_finishing, nama_finishing");
        echo json_encode($rows);
        break;

    case 'barang':
        $rows = dbSelect("SELECT * FROM price_barang ORDER BY nama_barang");
        echo json_encode($rows);
        break;

    default:
        http_response_code(400);
        echo json_encode(['error' => 'Parameter type tidak valid']);
}
