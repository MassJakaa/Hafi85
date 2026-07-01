<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAuth('admin');
verifyCsrf();

$orderId   = trim($_POST['order_id'] ?? '');
$isMember  = (int)($_POST['is_member'] ?? 0);
$cetak     = (float)($_POST['total_price']  ?? 0);
$designFee = (float)($_POST['design_fee']   ?? 0);
$total     = $cetak + $designFee;   // Grand total = harga cetak + biaya desain
$method    = $_POST['payment_method'] ?? 'cash';

if (!$orderId) {
    setFlash('error', 'Order tidak valid.');
    redirect('/admin/dashboard');
}

// Ambil data customer dari order untuk validasi member
$order = dbSelectOne("
    SELECT o.*, c.is_member FROM orders o
    JOIN customers c ON o.customer_id = c.id
    WHERE o.order_id = ?
", [$orderId]);

if (!$order) {
    setFlash('error', 'Order tidak ditemukan.');
    redirect('/admin/dashboard');
}

// Gunakan is_member dari DB (lebih aman daripada POST)
$isMember = (int)$order->is_member;

if ($isMember) {
    // Member: bisa DP / bayar sebagian — boleh berhutang
    $dp        = (float)($_POST['down_payment'] ?? 0);
    $remaining = max(0, $total - $dp);
    $payStatus = ($remaining <= 0) ? 'paid' : ($dp > 0 ? 'partial' : 'unpaid');
} else {
    // Non-member: harus lunas — down_payment = total, remaining = 0
    // Paksa metode hanya cash atau transfer (tidak boleh term)
    if ($method === 'term') {
        $method = 'cash';
    }
    $dp        = $total;   // lunas penuh
    $remaining = 0;
    $payStatus = 'paid';
}

dbRun("UPDATE orders SET
    total_price=?, design_fee=?, down_payment=?, remaining_balance=?,
    payment_method=?, payment_status=?, updated_at=NOW()
    WHERE order_id=?",
    [$total, $designFee, $dp, $remaining, $method, $payStatus, $orderId]
);

if ($payStatus === 'paid') {
    $msg = "Payment order {$orderId} LUNAS ✓";
} elseif ($payStatus === 'partial') {
    $msg = "Payment order {$orderId} — DP tersimpan, sisa " . number_format($remaining, 0, ',', '.');
} else {
    $msg = "Payment order {$orderId} tersimpan (belum bayar, hutang " . number_format($remaining, 0, ',', '.') . ")";
}

setFlash('success', $msg);
redirect('/admin/dashboard');
