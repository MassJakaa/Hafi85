<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/layout.php';
requireAuth('operator');
$userId   = authUser()['id'];
$username = authUser()['username'];
$session  = getOrCreateSession($userId);
$sessionId = $session->id;

// Mapping username operator → jenis_cetak di database price_cetak
$operatorProductMap = [
    'operator_uv'      => ['UV cm', 'UV pcs'],
    'operator_indoor'  => ['Indoor', 'Sublim cm', 'Sublim pcs'],
    'operator_bendera' => ['Century'],
    'operator_dtf'     => ['DTF'],
    'operator_banner'  => ['Outdoor'],
    'operator_a3'      => ['A3', 'Offset', 'Paket', 'Lainnya'],
    'operator_laser1'  => ['Laser cutting'],
    'operator_laser2'  => ['Laser cutting'],
];

$today = date('Y-m-d');

if (isset($operatorProductMap[$username])) {
    $allowedJenis  = $operatorProductMap[$username];
    $placeholders  = implode(',', array_fill(0, count($allowedJenis), '?'));
    $orders = dbSelect("
        SELECT o.*, c.name AS customer_name,
               COALESCE(pc.jenis_cetak, o.product_type) AS jenis_label
        FROM orders o
        JOIN customers c ON o.customer_id=c.id
        LEFT JOIN price_cetak pc ON o.price_cetak_id = pc.id
        WHERE o.design_status='approved'
          AND o.status IN ('approved','new_order','ready_print')
          AND (pc.jenis_cetak IN ($placeholders) OR o.product_type IN ($placeholders))
        ORDER BY o.is_urgent DESC, o.deadline ASC
    ", array_merge($allowedJenis, $allowedJenis));
} else {
    // Fallback: tampilkan semua (untuk akun operator lama / generic)
    $orders = dbSelect("
        SELECT o.*, c.name AS customer_name,
               COALESCE(pc.jenis_cetak, o.product_type) AS jenis_label
        FROM orders o
        JOIN customers c ON o.customer_id=c.id
        LEFT JOIN price_cetak pc ON o.price_cetak_id = pc.id
        WHERE o.design_status='approved' AND o.status IN ('approved','new_order','ready_print')
        ORDER BY o.is_urgent DESC, o.deadline ASC
    ");
}

// $session already fetched via getOrCreateSession() above
$selectedOrders = [];
if ($session) {
    $selectedOrders = dbSelect("
        SELECT o.order_id FROM print_session_orders pso
        JOIN orders o ON pso.order_id=o.id WHERE pso.session_id=?
    ", [$sessionId]);
}
$selectedIds = array_column(array_map(fn($r)=>(array)$r, $selectedOrders), 'order_id');
$printers = dbSelect("SELECT * FROM printers ORDER BY id ASC");

layoutStart('Dashboard','dashboard');
?>
<h1 class="page-title">DASHBOARD</h1>
<hr class="page-title-divider">
<div style="display:grid;grid-template-columns:1fr 300px;gap:20px;align-items:start;">
  <div class="card">
    <h3 class="card-title">Order Siap Cetak</h3>
    <div class="table-wrap">
      <table>
        <thead><tr><th>Order ID</th><th>Customer</th><th>Produk</th><th>Design</th><th>Status</th><th>Pilih</th></tr></thead>
        <tbody>
          <?php if ($orders): foreach ($orders as $o): $isSel = in_array($o->order_id, $selectedIds); ?>
          <tr>
            <td>
              <?= h($o->order_id) ?>
              <?= $o->is_urgent ? urgentIcon('Urgent') : '' ?>
              <?= ($o->deadline === $today) ? urgentIcon('Deadline hari ini!') : '' ?>
            </td>
            <td><?= h($o->customer_name) ?></td>
            <td><?= h($o->jenis_label ?? $o->product_type) ?></td>
            <td>
              <?php if ($o->design_file): ?>
              <img src="/uploads/<?= h($o->design_file) ?>" style="width:50px;height:35px;object-fit:cover;border-radius:4px;" title="Preview design">
              <?php else: ?><span style="color:#9ca3af;font-size:11px;">No img</span><?php endif; ?>
              <?php if (!empty($o->print_file)): ?>
              <br><a href="/uploads/<?= h($o->print_file) ?>" download
                 style="font-size:10px;color:#6B4EFF;font-weight:700;text-decoration:none;">⬇ CDR</a>
              <?php else: ?>
              <br><span style="font-size:10px;color:#f59e0b;font-weight:600;">No CDR</span>
              <?php endif; ?>
            </td>
            <td>
              <?php if ($o->status === 'ready_print'): ?>
              <span class="badge" style="background:#6B4EFF;color:#fff;font-size:10px;">SIAP CETAK</span>
              <?php else: ?>
              <span class="badge" style="background:#28a745;color:#fff;font-size:10px;">APPROVED</span>
              <?php endif; ?>
            </td>
            <td>
              <form method="POST" action="/operator/select">
                <input type="hidden" name="_token" value="<?= csrfToken() ?>">
                <input type="hidden" name="order_id" value="<?= h($o->order_id) ?>">
                <input type="hidden" name="action" value="<?= $isSel?'remove':'add' ?>">
                <button type="submit" class="btn <?= $isSel?'btn-red':'btn-purple' ?>" style="padding:5px 12px;font-size:11px;">
                  <?= $isSel?'✕ Remove':'+ Pilih' ?>
                </button>
              </form>
            </td>
          </tr>
          <?php endforeach; else: ?>
          <tr><td colspan="6" class="text-center" style="color:#9ca3af;padding:24px;">Belum ada order siap cetak</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card">
    <h3 class="card-title text-center">Work List</h3>
    <?php if ($selectedOrders): ?>
    <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:16px;">
      <?php foreach ($selectedOrders as $so): ?>
      <div style="display:flex;align-items:center;justify-content:space-between;padding:8px 10px;border:2px solid #e5e7eb;border-radius:8px;font-weight:700;font-size:13px;">
        <?= h($so->order_id) ?>
        <form method="POST" action="/operator/select" style="margin:0;">
          <input type="hidden" name="_token" value="<?= csrfToken() ?>">
          <input type="hidden" name="order_id" value="<?= h($so->order_id) ?>">
          <input type="hidden" name="action" value="remove">
          <button type="submit" style="background:none;border:none;color:#ef4444;font-size:16px;font-weight:900;cursor:pointer;">✕</button>
        </form>
      </div>
      <?php endforeach; ?>
    </div>
    <form method="POST" action="/operator/start">
      <input type="hidden" name="_token" value="<?= csrfToken() ?>">
      <button type="submit" class="btn btn-purple btn-full btn-lg">▶ MULAI PRINT</button>
    </form>
    <?php else: ?>
    <div style="text-align:center;color:#9ca3af;font-size:12px;padding:24px 0;">Pilih order dari daftar untuk memulai</div>
    <?php endif; ?>
  </div>
</div>
<?php layoutEnd(); ?>
