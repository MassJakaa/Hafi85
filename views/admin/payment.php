<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/layout.php';
requireAuth('admin');

$orderId   = trim($_GET['order_id'] ?? '');
$searchQ   = trim($_GET['q'] ?? '');   // pencarian nama pelanggan
$order     = null;
$searchResults = [];

// ── Cari berdasarkan Order ID langsung ──────────────────────────────────────
if ($orderId) {
    $order = dbSelectOne("
        SELECT o.*, c.name AS customer_name, c.phone, c.is_member
        FROM orders o JOIN customers c ON o.customer_id = c.id
        WHERE o.order_id = ?
    ", [$orderId]);
}

// ── Cari berdasarkan nama pelanggan atau order_id melalui search box ─────────
if ($searchQ && !$order) {
    // Cek apakah input cocok persis dengan order_id dulu
    $tryOrder = dbSelectOne("
        SELECT o.*, c.name AS customer_name, c.phone, c.is_member
        FROM orders o JOIN customers c ON o.customer_id = c.id
        WHERE o.order_id = ?
    ", [$searchQ]);

    if ($tryOrder) {
        // Jika cocok langsung ke form payment
        $order = $tryOrder;
    } else {
        // Cari berdasarkan nama pelanggan (LIKE)
        $searchResults = dbSelect("
            SELECT o.order_id, c.name AS customer_name, c.is_member,
                   o.payment_status, o.total_price, o.remaining_balance,
                   o.product_type, o.status
            FROM orders o JOIN customers c ON o.customer_id = c.id
            WHERE c.name LIKE ?
              AND o.status NOT IN ('cancelled','picked_up')
            ORDER BY o.updated_at DESC
            LIMIT 20
        ", ['%' . $searchQ . '%']);
    }
}

// ── Daftar order belum lunas (sidebar) ────────────────────────────────────────
$unpaidOrders = dbSelect("
    SELECT o.order_id, c.name AS customer_name, c.is_member,
           o.payment_status, o.total_price, o.remaining_balance
    FROM orders o JOIN customers c ON o.customer_id = c.id
    WHERE o.payment_status IN ('unpaid','partial')
    AND o.status NOT IN ('cancelled')
    ORDER BY o.updated_at DESC
");

layoutStart('Payment', 'payment');
?>
<h1 class="page-title">PAYMENT</h1>

<div style="display:grid;grid-template-columns:<?= $order ? '1fr 320px' : '1fr' ?>;gap:20px;align-items:start;">

  <!-- Form Payment / Pencarian -->
  <div>
    <?php if (!$order): ?>
    <div class="card">
      <p style="color:#9ca3af;text-align:center;padding:8px 0 16px;">
        Cari berdasarkan <strong>Order ID</strong> atau <strong>Nama Pelanggan</strong>:
      </p>
      <form method="GET" action="/admin/payment">
        <div style="display:flex;gap:8px;">
          <input type="text" name="q" class="form-control"
                 placeholder="Order ID (HF10001) atau Nama Pelanggan..."
                 value="<?= h($searchQ) ?>" autofocus>
          <button type="submit" class="btn btn-purple" style="white-space:nowrap;">🔍 Cari</button>
        </div>
      </form>

      <?php if ($searchQ && empty($searchResults) && !$order): ?>
      <!-- Tidak ditemukan -->
      <div style="margin-top:16px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:14px;text-align:center;color:#dc2626;font-size:13px;">
        ❌ Tidak ditemukan order atau pelanggan dengan kata kunci "<strong><?= h($searchQ) ?></strong>"
      </div>

      <?php elseif ($searchResults): ?>
      <!-- Hasil pencarian nama pelanggan -->
      <div style="margin-top:20px;">
        <div style="font-size:13px;font-weight:700;color:#6B7280;margin-bottom:10px;">
          Hasil pencarian "<?= h($searchQ) ?>" — <?= count($searchResults) ?> order ditemukan:
        </div>
        <div style="display:flex;flex-direction:column;gap:8px;">
          <?php foreach ($searchResults as $r):
            $isPaid = ($r->payment_status === 'paid');
          ?>
          <a href="/admin/payment?order_id=<?= h($r->order_id) ?>"
             style="display:block;padding:12px 14px;border-radius:10px;border:2px solid #e5e7eb;
                    text-decoration:none;color:inherit;transition:border-color .15s,box-shadow .15s;"
             onmouseover="this.style.borderColor='#6B4EFF';this.style.boxShadow='0 2px 8px rgba(107,78,255,.15)'"
             onmouseout="this.style.borderColor='#e5e7eb';this.style.boxShadow='none'">
            <div style="display:flex;justify-content:space-between;align-items:center;">
              <div>
                <span style="font-weight:800;font-size:14px;"><?= h($r->order_id) ?></span>
                <?php if ($r->is_member): ?>
                <span style="background:#6B4EFF;color:#fff;font-size:10px;padding:1px 6px;border-radius:8px;margin-left:4px;">⭐ Member</span>
                <?php endif; ?>
              </div>
              <?= payBadge($r->payment_status) ?>
            </div>
            <div style="font-size:13px;color:#374151;margin-top:3px;font-weight:600;"><?= h($r->customer_name) ?></div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin-top:6px;font-size:12px;color:#6B7280;">
              <span><?= h($r->product_type) ?></span>
              <?php if ($r->remaining_balance > 0): ?>
              <span style="color:#ef4444;font-weight:700;">Sisa <?= formatRp($r->remaining_balance) ?></span>
              <?php elseif ($isPaid): ?>
              <span style="color:#059669;font-weight:700;">✓ Lunas <?= formatRp($r->total_price) ?></span>
              <?php else: ?>
              <span style="color:#f59e0b;font-weight:700;">Total <?= formatRp($r->total_price) ?></span>
              <?php endif; ?>
            </div>
          </a>
          <?php endforeach; ?>
        </div>
      </div>
      <?php endif; ?>
    </div>

    <?php else: ?>
    <!-- ── Form Payment ────────────────────────────────────────────────────── -->
    <div class="card" style="max-width:580px;">
      <!-- Info Order -->
      <div style="background:#f9fafb;border-radius:10px;padding:14px 16px;margin-bottom:20px;">
        <div style="font-size:17px;font-weight:800;margin-bottom:4px;">
          <?= h($order->order_id) ?> — <?= h($order->customer_name) ?>
          <?php if ($order->is_member): ?>
          <span style="background:#6B4EFF;color:#fff;font-size:11px;padding:2px 8px;border-radius:10px;margin-left:6px;">⭐ Member</span>
          <?php else: ?>
          <span style="background:#e5e7eb;color:#6B7280;font-size:11px;padding:2px 8px;border-radius:10px;margin-left:6px;">Non-Member</span>
          <?php endif; ?>
        </div>
        <div style="font-size:13px;color:#6B7280;">
          <?= h($order->phone) ?> &nbsp;|&nbsp; <?= h($order->product_type) ?>
          <?php if ($order->panjang): ?>&nbsp;|&nbsp; <?= h($order->panjang) ?>×<?= h($order->lebar) ?> M<?php endif; ?>
        </div>
        <div style="margin-top:8px;">
          Status bayar: <?= payBadge($order->payment_status) ?>
          <?php if ($order->total_price > 0): ?>
          &nbsp;| Total: <strong><?= formatRp($order->total_price) ?></strong>
          &nbsp;| Sisa: <strong style="color:#ef4444;"><?= formatRp($order->remaining_balance) ?></strong>
          <?php endif; ?>
        </div>
      </div>

      <?php if (!$order->is_member): ?>
      <!-- Info peringatan non-member -->
      <div style="background:#fff3cd;border:1px solid #ffc107;border-radius:8px;padding:12px 14px;margin-bottom:16px;font-size:13px;">
        <strong>⚠ Non-Member:</strong> Pembayaran harus <strong>LUNAS</strong> — tidak dapat berhutang.
      </div>
      <?php endif; ?>

      <h3 class="card-title">Input Pembayaran</h3>

      <form method="POST" action="/admin/payment/store">
        <input type="hidden" name="_token" value="<?= csrfToken() ?>">
        <input type="hidden" name="order_id" value="<?= h($order->order_id) ?>">
        <input type="hidden" name="is_member" value="<?= $order->is_member ? '1' : '0' ?>">

        <div class="form-group">
          <label class="form-label">Harga Cetak (Rp)</label>
          <input name="total_price" id="totalInput" type="number" step="1" min="0"
                 class="form-control" placeholder="contoh: 18500"
                 value="<?= h(($order->total_price ?? 0) - ($order->design_fee ?? 0)) ?>"
                 oninput="calcTotal()" required>
          <div style="font-size:11px;color:#9ca3af;margin-top:3px;">Harga cetak tanpa biaya desain</div>
        </div>

        <!-- Biaya Desain (Opsional) -->
        <div class="form-group">
          <label class="form-label" style="display:flex;align-items:center;gap:8px;">
            Biaya Desain (Rp)
            <span style="background:#10b981;color:#fff;font-size:10px;padding:2px 6px;border-radius:10px;">Opsional</span>
          </label>
          <input name="design_fee" id="designFeeInput" type="number" step="1" min="0"
                 class="form-control" placeholder="Kosongkan jika tidak ada biaya desain"
                 value="<?= h($order->design_fee ?? '') ?>"
                 oninput="calcTotal()">
          <div style="font-size:11px;color:#9ca3af;margin-top:3px;">Isi jika ada biaya tambahan untuk jasa desain</div>
        </div>

        <!-- Total tagihan -->
        <div class="form-group">
          <label class="form-label">Total Tagihan (Rp)</label>
          <input id="grandTotalDisplay" type="text" class="form-control" readonly
                 style="background:#f3f4f6;font-weight:700;color:#111;"
                 value="<?= number_format(($order->total_price ?? 0), 0, ',', '.') ?>">
        </div>

        <?php if ($order->is_member): ?>
        <div class="form-group" id="dpGroup">
          <label class="form-label">Jumlah Dibayar / DP (Rp) <span style="background:#6B4EFF;color:#fff;font-size:10px;padding:2px 6px;border-radius:10px;">⭐ Member</span></label>
          <input name="down_payment" id="dpInput" type="number" step="1" min="0"
                 class="form-control" placeholder="contoh: 10000"
                 value="<?= h($order->down_payment ?: '') ?>"
                 oninput="calcRem()">
          <div style="font-size:11px;color:#9ca3af;margin-top:3px;">Kosongkan jika belum bayar (DP). Member boleh bayar bertahap.</div>
        </div>
        <?php else: ?>
        <!-- Non-member: down_payment otomatis = total (harus lunas) -->
        <input type="hidden" name="down_payment" id="dpHidden" value="<?= h($order->total_price ?? 0) ?>">
        <?php endif; ?>

        <div class="form-group">
          <label class="form-label">Sisa Pembayaran (Rp)</label>
          <input id="remDisplay" type="text" class="form-control" readonly
                 style="background:#f3f4f6;font-weight:700;color:#ef4444;"
                 value="<?= $order->is_member && $order->remaining_balance > 0 ? number_format($order->remaining_balance, 0, ',', '.') : '0' ?>">
          <?php if (!$order->is_member): ?>
          <div style="font-size:11px;color:#6B7280;margin-top:3px;">Non-member: sisa selalu 0 (harus bayar lunas)</div>
          <?php endif; ?>
        </div>

        <div class="form-group">
          <label class="form-label">Metode Pembayaran</label>
          <div style="display:flex;gap:20px;margin-top:8px;">
            <?php foreach (['cash'=>'💵 Cash','transfer'=>'🏦 Transfer'] as $val=>$lbl): ?>
            <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
              <input type="radio" name="payment_method" value="<?= $val ?>"
                     <?= ($order->payment_method ?: 'cash') === $val ? 'checked' : '' ?>>
              <?= $lbl ?>
            </label>
            <?php endforeach; ?>
            <?php if ($order->is_member): ?>
            <label style="display:flex;align-items:center;gap:6px;font-size:13px;cursor:pointer;">
              <input type="radio" name="payment_method" value="term"
                     <?= ($order->payment_method) === 'term' ? 'checked' : '' ?>>
              📋 Term
            </label>
            <?php endif; ?>
          </div>
          <?php if (!$order->is_member): ?>
          <div style="font-size:11px;color:#6B7280;margin-top:4px;">Non-member tidak tersedia metode Term (kredit).</div>
          <?php endif; ?>
        </div>

        <div style="display:flex;gap:12px;">
          <a href="/admin/payment" class="btn btn-outline btn-lg" style="flex:1;text-align:center;">← Kembali</a>
          <button type="submit" class="btn btn-purple btn-lg" style="flex:2;">💾 Simpan Payment</button>
        </div>
      </form>
    </div>
    <?php endif; ?>
  </div>

  <!-- Daftar Order Belum Lunas (Sidebar) -->
  <div class="card">
    <h3 class="card-title" style="font-size:14px;">Order Belum Lunas</h3>
    <?php if ($unpaidOrders): ?>
    <div style="display:flex;flex-direction:column;gap:6px;">
      <?php foreach ($unpaidOrders as $u): ?>
      <a href="/admin/payment?order_id=<?= h($u->order_id) ?>"
         style="display:block;padding:10px 12px;border-radius:8px;border:2px solid <?= ($order && $order->order_id === $u->order_id) ? '#6B4EFF' : '#e5e7eb' ?>;text-decoration:none;color:inherit;transition:border-color .15s;"
         onmouseover="this.style.borderColor='#6B4EFF'" onmouseout="this.style.borderColor='<?= ($order && $order->order_id === $u->order_id) ? '#6B4EFF' : '#e5e7eb' ?>'">
        <div style="font-weight:700;font-size:13px;">
          <?= h($u->order_id) ?>
          <?php if ($u->is_member): ?><span style="background:#6B4EFF;color:#fff;font-size:9px;padding:1px 4px;border-radius:6px;margin-left:3px;">⭐</span><?php endif; ?>
        </div>
        <div style="font-size:12px;color:#6B7280;"><?= h($u->customer_name) ?></div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin-top:4px;">
          <?= payBadge($u->payment_status) ?>
          <?php if ($u->remaining_balance > 0): ?>
          <span style="font-size:11px;color:#ef4444;font-weight:700;">Sisa <?= formatRp($u->remaining_balance) ?></span>
          <?php endif; ?>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
    <?php else: ?>
    <div style="text-align:center;color:#9ca3af;font-size:12px;padding:24px 0;">
      Semua order sudah lunas 🎉
    </div>
    <?php endif; ?>
  </div>

</div>

<script>
const isMember = <?= $order ? ($order->is_member ? 'true' : 'false') : 'false' ?>;

function calcTotal() {
  const cetak     = parseFloat(document.getElementById('totalInput')?.value) || 0;
  const designFee = parseFloat(document.getElementById('designFeeInput')?.value) || 0;
  const grand     = cetak + designFee;

  const gtEl = document.getElementById('grandTotalDisplay');
  if (gtEl) gtEl.value = grand.toLocaleString('id-ID');

  if (!isMember) {
    // Non-member: dp hidden selalu = total, sisa selalu 0
    const dpHidden = document.getElementById('dpHidden');
    if (dpHidden) dpHidden.value = grand;
    const rem = document.getElementById('remDisplay');
    if (rem) rem.value = '0';
  } else {
    calcRem();
  }
}

function calcRem() {
  const cetak     = parseFloat(document.getElementById('totalInput')?.value) || 0;
  const designFee = parseFloat(document.getElementById('designFeeInput')?.value) || 0;
  const grand     = cetak + designFee;
  const dp        = parseFloat(document.getElementById('dpInput')?.value) || 0;
  const rem       = Math.max(0, grand - dp);
  const el        = document.getElementById('remDisplay');
  if (el) el.value = rem.toLocaleString('id-ID');
}
</script>
<?php layoutEnd(); ?>
