<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
requireAuth('admin');
verifyCsrf();

$name        = trim($_POST['name'] ?? '');
$phone       = trim($_POST['phone'] ?? '');
$isMember    = !empty($_POST['is_member']) ? 1 : 0;
$productType = trim($_POST['product_type'] ?? '');
$panjang     = (float)($_POST['panjang'] ?? 0);
$lebar       = (float)($_POST['lebar'] ?? 0);
$bahan       = trim($_POST['bahan'] ?? '');
$qty         = (int)($_POST['quantity'] ?? 1);
$finishing   = trim($_POST['finishing_type'] ?? '');
$deadline    = $_POST['deadline'] ?? null;
$desc        = trim($_POST['description'] ?? '');
$priceCetakId    = (int)($_POST['price_cetak_id'] ?? 0);
$priceFinishingId = (int)($_POST['price_finishing_id'] ?? 0);

if (!$name || !$phone || !$productType) {
    setFlash('error', 'Data tidak lengkap.');
    redirect('/admin/order');
}

// Cari atau buat customer
$cust = dbSelectOne("SELECT * FROM customers WHERE phone = ?", [$phone]);
if (!$cust) {
    $custId   = generateCustomerId();
    $custDbId = dbInsert('customers', [
        'customer_id' => $custId,
        'name'        => $name,
        'phone'       => $phone,
        'is_member'   => $isMember,
    ]);
} else {
    $custDbId = $cust->id;
    // Update member status jika berubah
    if ((int)$cust->is_member !== $isMember) {
        dbUpdate('customers', ['is_member' => $isMember], 'id = ?', [$custDbId]);
    }
}

// Upload file design dari pelanggan (jika ada) — semua tipe file diterima
$designFile = null;
if (!empty($_FILES['design_file']['name']) && $_FILES['design_file']['error'] === 0) {
    $file      = $_FILES['design_file'];
    $oriName   = $file['name'];
    $ext       = strtolower(pathinfo($oriName, PATHINFO_EXTENSION));
    $filename  = uniqid() . ($ext ? '.' . $ext : '');
    $uploadDir = __DIR__ . '/../../public/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
    move_uploaded_file($file['tmp_name'], $uploadDir . $filename);
    $designFile = $filename;
}

// ── Hitung harga otomatis ──────────────────────────────────────────────────
$totalPrice = 0;
$hargaCetakSatuan = 0;
$hargaFinishingSatuan = 0;

// Harga cetak
if ($priceCetakId > 0) {
    $price = dbSelectOne("SELECT * FROM price_cetak WHERE id=?", [$priceCetakId]);
    if ($price) {
        $hargaCetakSatuan = $isMember ? $price->harga_member : $price->harga_umum;
        if ($price->satuan === 'cm') {
            // Harga per cm² × panjang × lebar × qty
            $totalPrice = $hargaCetakSatuan * $panjang * $lebar * $qty;
        } else {
            // Harga per pcs × qty
            $totalPrice = $hargaCetakSatuan * $qty;
        }
    }
}

// Harga finishing (tambahan)
if ($priceFinishingId > 0) {
    $finPrice = dbSelectOne("SELECT * FROM price_finishing WHERE id=?", [$priceFinishingId]);
    if ($finPrice) {
        $hargaFinishingSatuan = $isMember ? $finPrice->harga_member : $finPrice->harga_umum;
        $totalPrice += $hargaFinishingSatuan * $qty;
    }
}

// Fallback: jika ada calculated_total dari frontend tapi tidak ada price lookup
if ($totalPrice <= 0 && !empty($_POST['calculated_total'])) {
    $totalPrice = (float)$_POST['calculated_total'];
}

$totalPrice = round($totalPrice);

// SELALU ke desainer dulu (new_order) — ada atau tidak ada file dari pelanggan
// File dari pelanggan hanya sebagai referensi desainer, bukan skip desainer
$orderId = generateOrderId();
dbInsert('orders', [
    'order_id'             => $orderId,
    'customer_id'          => $custDbId,
    'product_type'         => $productType,
    'panjang'              => $panjang,
    'lebar'                => $lebar,
    'bahan'                => $bahan,
    'quantity'             => $qty,
    'finishing_type'       => $finishing,
    'deadline'             => $deadline,
    'description'          => $desc,
    'design_file'          => $designFile,
    'status'               => 'new_order',
    'design_status'        => 'pending',
    'total_price'          => $totalPrice,
    'price_cetak_id'       => $priceCetakId ?: null,
    'price_finishing_id'   => $priceFinishingId ?: null,
    'harga_cetak_satuan'   => $hargaCetakSatuan,
    'harga_finishing_satuan' => $hargaFinishingSatuan,
]);

$priceInfo = $totalPrice > 0
    ? " Total harga: Rp " . number_format($totalPrice, 0, ',', '.')
    : '';

$msg = $designFile
    ? "Order {$orderId} dibuat!{$priceInfo} File referensi dari pelanggan tersimpan, diteruskan ke Desainer."
    : "Order {$orderId} dibuat{$priceInfo} & diteruskan ke Desainer!";

setFlash('success', $msg);
redirect('/admin/dashboard');
