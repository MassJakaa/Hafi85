<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/layout.php';
requireAuth('desainer');

$orderId = trim($_GET['order_id'] ?? '');
$order   = dbSelectOne("
    SELECT o.*, c.name AS customer_name, c.phone
    FROM orders o JOIN customers c ON o.customer_id=c.id
    WHERE o.order_id=?
", [$orderId]);
if (!$order) { redirect('/desainer/dashboard'); }

// Info file design (preview JPG/PNG untuk admin)
$imageExts = ['jpg','jpeg','png','gif','webp','bmp'];
$ext       = $order->design_file ? strtolower(pathinfo($order->design_file, PATHINFO_EXTENSION)) : '';
$isImage   = in_array($ext, $imageExts);

// Info print file (CDR untuk operator)
$printFileExt = $order->print_file ? strtolower(pathinfo($order->print_file, PATHINFO_EXTENSION)) : '';

// Status
$isApproved = ($order->design_status === 'approved');

layoutStart('Order Detail','dashboard');
?>
<h1 class="page-title">ORDER DETAIL — <?= h($order->order_id) ?></h1>

<div class="detail-grid">
  <div class="detail-panel">
    <div class="detail-panel-title">Customer</div>
    <div class="detail-row"><span class="detail-key">Nama</span><span class="detail-val"><?= h($order->customer_name) ?></span></div>
    <div class="detail-row"><span class="detail-key">Phone</span><span class="detail-val"><?= h($order->phone) ?></span></div>
  </div>
  <div class="detail-panel">
    <div class="detail-panel-title">Order</div>
    <div class="detail-row"><span class="detail-key">Produk</span><span class="detail-val"><?= h($order->product_type) ?></span></div>
    <div class="detail-row"><span class="detail-key">Ukuran</span><span class="detail-val"><?= h($order->panjang) ?>×<?= h($order->lebar) ?> M</span></div>
    <div class="detail-row"><span class="detail-key">Bahan</span><span class="detail-val"><?= h($order->bahan) ?></span></div>
    <div class="detail-row"><span class="detail-key">Qty</span><span class="detail-val"><?= h($order->quantity) ?></span></div>
    <div class="detail-row"><span class="detail-key">Finishing</span><span class="detail-val"><?= h($order->finishing_type) ?></span></div>
    <div class="detail-row"><span class="detail-key">Deadline</span><span class="detail-val"><?= h($order->deadline) ?></span></div>
    <?php if ($order->description): ?>
    <div class="detail-row"><span class="detail-key">Desc</span><span class="detail-val"><?= h($order->description) ?></span></div>
    <?php endif; ?>
  </div>
  <div class="detail-panel">
    <div class="detail-panel-title">Status Design</div>
    <div class="detail-row"><?= statusBadge($order->design_status) ?></div>
    <?php if ($isApproved): ?>
    <div style="margin-top:8px;background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:8px 10px;font-size:12px;color:#065f46;font-weight:600;">
      ✅ Design telah di-approve oleh admin
    </div>
    <?php endif; ?>
    <?php if ($order->revision_note): ?>
    <div style="margin-top:12px;background:#fff3cd;border:1px solid #ffc107;border-radius:8px;padding:10px;font-size:13px;">
      <strong>Revision Note:</strong><br><?= h($order->revision_note) ?>
    </div>
    <?php endif; ?>
  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════════════════════
     SATU CARD KONDISIONAL:
     • Belum approved → upload JPG/PNG + tombol Request Approval
     • Sudah approved  → upload CDR + tombol Kirim ke Operator
═══════════════════════════════════════════════════════════════════════════ -->
<div class="card" style="max-width:520px;">

  <?php if (!$isApproved): ?>
  <!-- ─── FASE 1: Belum Approved — Upload Design (JPG/PNG) untuk Admin ────── -->
  <h3 class="card-title">📤 Upload Design untuk Approval Admin</h3>

  <!-- Preview file design yang sudah diupload -->
  <?php if ($order->design_file): ?>
    <?php if ($isImage): ?>
    <img src="/uploads/<?= h($order->design_file) ?>" alt="Design"
         style="max-width:100%;border-radius:8px;margin-bottom:12px;display:block;">
    <?php else: ?>
    <div style="background:#f3f4f6;border-radius:8px;padding:14px;margin-bottom:12px;display:flex;align-items:center;gap:12px;">
      <div style="font-size:28px;">🖼</div>
      <div>
        <div style="font-weight:700;font-size:13px;"><?= h($order->design_file) ?></div>
        <div style="font-size:12px;color:#6B7280;">File <?= strtoupper(h($ext)) ?></div>
      </div>
    </div>
    <?php endif; ?>
    <a href="/uploads/<?= h($order->design_file) ?>" download
       class="btn btn-blue btn-full" style="margin-bottom:16px;">
      ⬇ Download File Design
    </a>
  <?php else: ?>
    <div style="height:100px;background:#f3f4f6;border-radius:8px;display:flex;align-items:center;
                justify-content:center;color:#9ca3af;margin-bottom:16px;font-size:13px;">
      Belum ada file design — upload terlebih dahulu
    </div>
  <?php endif; ?>

  <!-- Form upload JPG/PNG -->
  <form method="POST" action="/desainer/upload" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="<?= csrfToken() ?>">
    <input type="hidden" name="order_id" value="<?= h($order->order_id) ?>">
    <div class="form-group">
      <label class="form-label"><?= $order->design_file ? 'Ganti File Design' : 'Upload File Design' ?></label>
      <input type="file" name="design_file" class="form-control" accept=".jpg,.jpeg,.png" required>
      <div style="font-size:11px;color:#6B7280;margin-top:4px;">
        Hanya <strong>JPG, JPEG, PNG</strong> — file preview ini dikirim ke admin untuk di-approve
      </div>
    </div>
    <button type="submit" class="btn btn-purple btn-full" style="margin-bottom:12px;">
      ⬆ <?= $order->design_file ? 'Ganti & Upload Design' : 'Upload Design' ?>
    </button>
  </form>

  <!-- Tombol Request Approval (hanya muncul jika sudah ada file design) -->
  <?php if ($order->design_file): ?>
  <form method="POST" action="/desainer/request-approval">
    <input type="hidden" name="_token" value="<?= csrfToken() ?>">
    <input type="hidden" name="order_id" value="<?= h($order->order_id) ?>">
    <button type="submit" class="btn btn-orange btn-full btn-lg">
      📋 Request Approval ke Admin
    </button>
  </form>
  <?php else: ?>
  <div style="text-align:center;font-size:12px;color:#9ca3af;padding:8px 0;">
    Upload file design terlebih dahulu sebelum request approval
  </div>
  <?php endif; ?>


  <?php else: ?>
  <!-- ─── FASE 2: Sudah Approved — Upload CDR & Kirim ke Operator ─────────── -->
  <h3 class="card-title">🚀 Kirim ke Operator</h3>

  <div style="background:#d1fae5;border:1px solid #6ee7b7;border-radius:8px;padding:12px 14px;margin-bottom:16px;font-size:13px;color:#065f46;">
    ✅ Design sudah di-approve admin. Upload file CDR untuk dicetak oleh operator.
  </div>

  <!-- Preview file CDR yang sudah diupload sebelumnya (jika ada) -->
  <?php if ($order->print_file): ?>
  <div style="background:#eff6ff;border:1px solid #bfdbfe;border-radius:8px;padding:14px;margin-bottom:16px;display:flex;align-items:center;gap:12px;">
    <div style="font-size:28px;">📄</div>
    <div style="flex:1;">
      <div style="font-weight:700;font-size:13px;color:#1d4ed8;"><?= strtoupper(h($printFileExt)) ?> — File Cetak</div>
      <div style="font-size:11px;color:#6B7280;margin-top:2px;">File siap dikirim ke operator</div>
    </div>
    <a href="/uploads/<?= h($order->print_file) ?>" download
       style="font-size:11px;color:#6B4EFF;font-weight:700;text-decoration:none;white-space:nowrap;">
      ⬇ Download
    </a>
  </div>
  <?php endif; ?>

  <!-- Form upload CDR + Kirim ke Operator dalam SATU tombol -->
  <form method="POST" action="/desainer/goto-print" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="<?= csrfToken() ?>">
    <input type="hidden" name="order_id" value="<?= h($order->order_id) ?>">
    <div class="form-group">
      <label class="form-label">
        <?= $order->print_file ? 'Ganti / Upload Ulang File Cetak (CDR)' : 'Upload File Cetak (CDR)' ?>
      </label>
      <input type="file" name="print_file" class="form-control"
             accept=".cdr,.ai,.psd,.pdf,.eps"
             <?= $order->print_file ? '' : 'required' ?>>
      <div style="font-size:11px;color:#6B7280;margin-top:4px;">
        Format: <strong>CDR, AI, PSD, PDF, EPS</strong> — file ini diteruskan ke operator untuk dicetak
        <?php if ($order->print_file): ?>
        <br><span style="color:#059669;">✓ File sebelumnya sudah ada, kosongkan jika tidak ingin mengganti</span>
        <?php endif; ?>
      </div>
    </div>
    <button type="submit" class="btn btn-blue btn-full btn-lg"
            onclick="return confirm('Kirim order <?= h($order->order_id) ?> ke operator untuk dicetak?')">
      🖨 <?= $order->print_file ? 'Update CDR &amp; Kirim ke Operator' : 'Upload CDR &amp; Kirim ke Operator' ?>
    </button>
  </form>

  <?php endif; ?>
</div>

<!-- Tombol Kembali -->
<div style="margin-top:12px;">
  <a href="/desainer/dashboard" class="btn btn-outline btn-lg">← Kembali ke Dashboard</a>
</div>

<?php layoutEnd(); ?>
