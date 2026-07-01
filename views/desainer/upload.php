<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAuth('desainer');
verifyCsrf();

$orderId = trim($_POST['order_id'] ?? '');

if (empty($_FILES['design_file']['name']) || $_FILES['design_file']['error'] !== 0) {
    setFlash('error', 'Gagal upload — tidak ada file dipilih.');
    redirect('/desainer/order?order_id=' . $orderId);
}

$file    = $_FILES['design_file'];
$oriName = $file['name'];
$ext     = strtolower(pathinfo($oriName, PATHINFO_EXTENSION));

// Hanya terima JPG/JPEG/PNG untuk design yang dikirim ke admin untuk approval
$allowedExts = ['jpg', 'jpeg', 'png'];
if (!in_array($ext, $allowedExts)) {
    setFlash('error', 'File design untuk approval harus berformat JPG, JPEG, atau PNG. File CDR/AI/PSD diupload terpisah sebagai file cetak.');
    redirect('/desainer/order?order_id=' . $orderId);
}

$filename  = uniqid('design_') . '.' . $ext;
$uploadDir = __DIR__ . '/../../public/uploads/';
if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

if (move_uploaded_file($file['tmp_name'], $uploadDir . $filename)) {
    // Simpan sebagai design_file (gambar preview untuk admin)
    dbRun("UPDATE orders SET design_file=?, updated_at=NOW() WHERE order_id=?",
        [$filename, $orderId]);
    setFlash('success', 'Design berhasil diupload: ' . h($oriName));
} else {
    setFlash('error', 'Gagal menyimpan file. Coba lagi.');
}

redirect('/desainer/order?order_id=' . $orderId);
