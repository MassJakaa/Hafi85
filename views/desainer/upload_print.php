<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAuth('desainer');
verifyCsrf();

$orderId = trim($_POST['order_id'] ?? '');

if (empty($_FILES['print_file']['name']) || $_FILES['print_file']['error'] !== 0) {
    setFlash('error', 'Gagal upload — tidak ada file dipilih.');
    redirect('/desainer/order?order_id=' . $orderId);
}

$file    = $_FILES['print_file'];
$oriName = $file['name'];
$ext     = strtolower(pathinfo($oriName, PATHINFO_EXTENSION));

// Hanya terima file vektor/cetak: CDR, AI, PSD, PDF, EPS
$allowedExts = ['cdr', 'ai', 'psd', 'pdf', 'eps'];
if (!in_array($ext, $allowedExts)) {
    setFlash('error', 'File cetak untuk operator harus berformat CDR, AI, PSD, PDF, atau EPS. File gambar (JPG/PNG) diupload sebagai file design untuk approval.');
    redirect('/desainer/order?order_id=' . $orderId);
}

$filename  = uniqid('print_') . '.' . $ext;
$uploadDir = __DIR__ . '/../../public/uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
    // Simpan sebagai print_file (file cetak untuk operator)
    dbRun("UPDATE orders SET print_file=?, updated_at=NOW() WHERE order_id=?",
        [$filename, $orderId]);
    setFlash('success', 'File cetak berhasil diupload: ' . h($oriName));
} else {
    setFlash('error', 'Gagal menyimpan file. Coba lagi.');
}

redirect('/desainer/order?order_id=' . $orderId);
