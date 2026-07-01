<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAuth('desainer');
verifyCsrf();

$orderId = trim($_POST['order_id'] ?? '');
$order   = dbSelectOne("SELECT * FROM orders WHERE order_id=?", [$orderId]);

if (!$order || $order->design_status !== 'approved') {
    setFlash('error', 'Design harus diapprove terlebih dahulu.');
    redirect('/desainer/order?order_id=' . $orderId);
}

// ── Upload file CDR jika ada ─────────────────────────────────────────────────
if (!empty($_FILES['print_file']['name']) && $_FILES['print_file']['error'] === 0) {
    $file    = $_FILES['print_file'];
    $oriName = $file['name'];
    $ext     = strtolower(pathinfo($oriName, PATHINFO_EXTENSION));

    $allowedExts = ['cdr', 'ai', 'psd', 'pdf', 'eps'];
    if (!in_array($ext, $allowedExts)) {
        setFlash('error', 'File cetak harus berformat CDR, AI, PSD, PDF, atau EPS.');
        redirect('/desainer/order?order_id=' . $orderId);
    }

    $filename  = uniqid('print_') . '.' . $ext;
    $uploadDir = __DIR__ . '/../../public/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    if (!move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
        setFlash('error', 'Gagal menyimpan file CDR. Coba lagi.');
        redirect('/desainer/order?order_id=' . $orderId);
    }

    // Simpan print_file dan ubah status ke ready_print sekaligus
    dbRun("UPDATE orders SET print_file=?, status='ready_print', updated_at=NOW() WHERE order_id=?",
        [$filename, $orderId]);
} else {
    // Tidak ada file yang diupload — hanya ubah status (print_file mungkin sudah ada sebelumnya)
    if (empty($order->print_file)) {
        setFlash('error', 'Harap upload file CDR/AI/PSD sebelum mengirim ke operator.');
        redirect('/desainer/order?order_id=' . $orderId);
    }
    dbRun("UPDATE orders SET status='ready_print', updated_at=NOW() WHERE order_id=?", [$orderId]);
}

setFlash('success', "Order {$orderId} berhasil dikirim ke Operator! 🎉");
redirect('/desainer/dashboard');
