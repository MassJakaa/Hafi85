<?php
require_once __DIR__ . '/../../includes/db.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/layout.php';
requireAuth('admin');

// Ambil data harga untuk dropdown
$jenisCetakList = dbSelect("SELECT DISTINCT jenis_cetak FROM price_cetak ORDER BY jenis_cetak");
$finishingList  = dbSelect("SELECT * FROM price_finishing ORDER BY jenis_finishing, nama_finishing");

layoutStart('Order Form', 'order');
?>
<h1 class="page-title">Order Form</h1>

<div id="step1">
  <div class="card">
    <h3 class="card-title">Customer Data</h3>
    <div class="form-row">
      <div class="form-group">
        <label class="form-label">Name</label>
        <input type="text" id="custName" class="form-control" placeholder="Name">
      </div>
      <div class="form-group">
        <label class="form-label">Phone</label>
        <input type="text" id="custPhone" class="form-control" placeholder="Phone Number" oninput="checkMember(this.value)">
        <div id="memberHint" style="font-size:11px;margin-top:4px;display:none;"></div>
      </div>
    </div>
    <div class="form-group">
      <label style="display:flex;align-items:center;gap:8px;font-weight:600;font-size:13px;cursor:pointer;">
        <input type="checkbox" id="isMember" style="width:16px;height:16px;" onchange="recalcPrice()"> Member
        <span style="font-size:11px;color:#9ca3af;font-weight:400;">(centang jika pelanggan member — harga lebih murah)</span>
      </label>
    </div>
  </div>
  <div class="card">
    <h3 class="card-title">Pilih Jenis Cetak</h3>
    <div class="product-types">
      <?php foreach ($jenisCetakList as $jc): ?>
      <button type="button" class="product-btn" onclick="selectJenis(this,'<?= h($jc->jenis_cetak) ?>')"><?= h($jc->jenis_cetak) ?></button>
      <?php endforeach; ?>
    </div>
  </div>
  <button class="btn btn-purple btn-full btn-lg" onclick="goStep2()">NEXT</button>
</div>

<div id="step2" class="hidden">
  <form method="POST" action="/admin/order/store" enctype="multipart/form-data">
    <input type="hidden" name="_token" value="<?= csrfToken() ?>">
    <input type="hidden" name="product_type" id="hiddenProduct">
    <input type="hidden" name="name"          id="hiddenName">
    <input type="hidden" name="phone"         id="hiddenPhone">
    <input type="hidden" name="is_member"     id="hiddenMember" value="">
    <input type="hidden" name="price_cetak_id" id="hiddenPriceCetakId" value="">
    <input type="hidden" name="price_finishing_id" id="hiddenPriceFinishingId" value="">
    <h1 class="page-title" id="formTitle">Order Form</h1>
    <div class="card">
      <h3 class="card-title">Pilih Produk</h3>
      <div class="form-group">
        <label class="form-label">Nama Produk / Bahan</label>
        <select id="selProduk" class="form-control" onchange="onProdukChange()">
          <option value="">-- Pilih produk --</option>
        </select>
      </div>
      <div id="priceInfoBox" style="display:none;background:#f0f4ff;border:2px solid #6B4EFF;border-radius:10px;padding:14px 16px;margin-top:10px;">
        <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:8px;">
          <div>
            <div style="font-size:11px;color:#6B7280;font-weight:600;">HARGA SATUAN</div>
            <div style="font-size:20px;font-weight:800;color:#6B4EFF;" id="hargaSatuanDisplay">Rp 0</div>
            <div style="font-size:11px;color:#9ca3af;" id="satuanLabel">per pcs</div>
          </div>
          <div style="text-align:right;">
            <div style="font-size:11px;color:#6B7280;font-weight:600;">ESTIMASI TOTAL CETAK</div>
            <div style="font-size:20px;font-weight:800;color:#22c55e;" id="totalCetakDisplay">Rp 0</div>
          </div>
        </div>
      </div>
    </div>
    <div class="card">
      <h3 class="card-title">Detail Produk</h3>
      <div class="form-row">
        <div class="form-group" id="grpPanjang"><label class="form-label">Panjang (cm)</label><input name="panjang" id="inpPanjang" type="number" step="0.01" class="form-control" placeholder="Panjang" oninput="recalcPrice()"></div>
        <div class="form-group" id="grpLebar"><label class="form-label">Lebar (cm)</label><input name="lebar" id="inpLebar" type="number" step="0.01" class="form-control" placeholder="Lebar" oninput="recalcPrice()"></div>
        <div class="form-group"><label class="form-label">Bahan</label><input name="bahan" id="inpBahan" type="text" class="form-control" placeholder="Material" readonly></div>
        <div class="form-group"><label class="form-label">Quantity</label><input name="quantity" id="inpQty" type="number" min="1" class="form-control" value="1" oninput="recalcPrice()"></div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label class="form-label">Finishing (opsional)</label>
          <select name="finishing_type" id="selFinishing" class="form-control" onchange="onFinishingChange()">
            <option value="">-- Tanpa Finishing --</option>
            <?php foreach ($finishingList as $f): ?>
            <option value="<?= h($f->nama_finishing) ?>"
              data-id="<?= $f->id ?>"
              data-hm="<?= $f->harga_member ?>"
              data-hu="<?= $f->harga_umum ?>">
              <?= h($f->jenis_finishing) ?> — <?= h($f->nama_finishing) ?>
              (Member: Rp <?= number_format($f->harga_member,0,',','.') ?> | Umum: Rp <?= number_format($f->harga_umum,0,',','.') ?>)
            </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="form-group"><label class="form-label">Deadline</label><input name="deadline" type="date" class="form-control" min="<?= date('Y-m-d') ?>"></div>
      </div>
      <div class="form-group"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="3" placeholder="Desc"></textarea></div>
      <div class="form-group"><label class="form-label">Upload File Design</label><input name="design_file" type="file" class="form-control" accept=".jpg,.jpeg,.png,.pdf,.ai,.psd"></div>
    </div>

    <!-- Ringkasan Harga -->
    <div class="card" style="background:linear-gradient(135deg,#f0f4ff 0%,#e8f5e9 100%);border:2px solid #6B4EFF;">
      <h3 class="card-title" style="color:#6B4EFF;">💰 Ringkasan Harga</h3>
      <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:12px;text-align:center;">
        <div>
          <div style="font-size:11px;color:#6B7280;font-weight:600;">Cetak</div>
          <div style="font-size:18px;font-weight:800;color:#3b82f6;" id="sumCetak">Rp 0</div>
        </div>
        <div>
          <div style="font-size:11px;color:#6B7280;font-weight:600;">Finishing</div>
          <div style="font-size:18px;font-weight:800;color:#f97316;" id="sumFinishing">Rp 0</div>
        </div>
        <div>
          <div style="font-size:11px;color:#6B7280;font-weight:600;">TOTAL</div>
          <div style="font-size:22px;font-weight:900;color:#22c55e;" id="sumTotal">Rp 0</div>
        </div>
      </div>
      <input type="hidden" name="calculated_total" id="hiddenCalcTotal" value="0">
    </div>

    <div style="display:flex;gap:12px;">
      <button type="button" class="btn btn-outline btn-lg" style="flex:1;" onclick="goBack()">KEMBALI</button>
      <button type="submit" class="btn btn-purple btn-lg" style="flex:2;">DONE</button>
    </div>
  </form>
</div>

<script>
let selJenis = '';
let produkData = []; // cached cetak products for selected jenis
let selectedProduk = null; // currently selected product object

let memberCheckTimeout = null;
function checkMember(phone) {
  clearTimeout(memberCheckTimeout);
  if (phone.length < 6) {
    document.getElementById('memberHint').style.display = 'none';
    return;
  }
  memberCheckTimeout = setTimeout(() => {
    fetch('/api/prices?type=check_member&phone=' + encodeURIComponent(phone))
      .then(r => r.json())
      .then(data => {
        const hint = document.getElementById('memberHint');
        const cb   = document.getElementById('isMember');
        if (data.found) {
          if (data.is_member) {
            cb.checked = true;
            hint.innerHTML = '⭐ <strong>Pelanggan Member</strong> — harga member otomatis diterapkan';
            hint.style.color = '#6B4EFF';
          } else {
            cb.checked = false;
            hint.innerHTML = '✓ Pelanggan lama (bukan member)';
            hint.style.color = '#6B7280';
          }
          // Auto-isi nama jika kosong
          const nameEl = document.getElementById('custName');
          if (!nameEl.value.trim() && data.name) nameEl.value = data.name;
        } else {
          hint.innerHTML = '✦ Pelanggan baru';
          hint.style.color = '#22c55e';
        }
        hint.style.display = 'block';
        recalcPrice();
      });
  }, 500);
}

function selectJenis(btn, jenis) {
  document.querySelectorAll('.product-btn').forEach(b => b.classList.remove('selected'));
  btn.classList.add('selected');
  selJenis = jenis;
}

function goStep2() {
  const name = document.getElementById('custName').value.trim();
  const phone = document.getElementById('custPhone').value.trim();
  if (!name || !phone) { alert('Lengkapi nama dan nomor telepon!'); return; }
  if (!selJenis) { alert('Pilih jenis cetak terlebih dahulu!'); return; }
  document.getElementById('hiddenProduct').value = selJenis;
  document.getElementById('hiddenName').value = name;
  document.getElementById('hiddenPhone').value = phone;
  document.getElementById('hiddenMember').value = document.getElementById('isMember').checked ? '1' : '';
  document.getElementById('formTitle').textContent = 'Order Form — ' + selJenis;
  document.getElementById('step1').classList.add('hidden');
  document.getElementById('step2').classList.remove('hidden');

  // Load products for this jenis via API
  fetch('/api/prices?type=cetak&jenis=' + encodeURIComponent(selJenis))
    .then(r => r.json())
    .then(data => {
      produkData = data;
      const sel = document.getElementById('selProduk');
      sel.innerHTML = '<option value="">-- Pilih produk --</option>';
      data.forEach(p => {
        const isMember = document.getElementById('isMember').checked;
        const harga = isMember ? p.harga_member : p.harga_umum;
        const opt = document.createElement('option');
        opt.value = p.id;
        opt.textContent = p.nama_cetak + ' — Rp ' + Number(harga).toLocaleString('id-ID') + '/' + (p.satuan === 'cm' ? 'cm²' : 'pcs');
        sel.appendChild(opt);
      });

      // Untuk jenis A3 (satuan pcs), sembunyikan panjang & lebar
      const isAreaBased = data.length > 0 && data[0].satuan === 'cm';
      document.getElementById('grpPanjang').style.display = isAreaBased ? '' : 'none';
      document.getElementById('grpLebar').style.display   = isAreaBased ? '' : 'none';
      // Reset input agar tidak ikut kalkulasi
      if (!isAreaBased) {
        document.getElementById('inpPanjang').value = '';
        document.getElementById('inpLebar').value = '';
      }
    });
}

function goBack() {
  document.getElementById('step2').classList.add('hidden');
  document.getElementById('step1').classList.remove('hidden');
}

function onProdukChange() {
  const sel = document.getElementById('selProduk');
  const id = parseInt(sel.value);
  selectedProduk = produkData.find(p => p.id === id) || null;

  if (selectedProduk) {
    document.getElementById('hiddenPriceCetakId').value = selectedProduk.id;
    document.getElementById('inpBahan').value = selectedProduk.nama_cetak;
    document.getElementById('priceInfoBox').style.display = 'block';
    document.getElementById('satuanLabel').textContent = selectedProduk.satuan === 'cm' ? 'per cm² (Rp/cm²)' : 'per pcs';
    recalcPrice();
  } else {
    document.getElementById('hiddenPriceCetakId').value = '';
    document.getElementById('inpBahan').value = '';
    document.getElementById('priceInfoBox').style.display = 'none';
    resetPriceDisplay();
  }
}

function onFinishingChange() {
  const sel = document.getElementById('selFinishing');
  const opt = sel.selectedOptions[0];
  document.getElementById('hiddenPriceFinishingId').value = opt?.dataset?.id || '';
  recalcPrice();
}

function recalcPrice() {
  if (!selectedProduk) { resetPriceDisplay(); return; }

  const isMember = document.getElementById('isMember').checked;
  const hargaSatuan = isMember ? selectedProduk.harga_member : selectedProduk.harga_umum;
  const panjang = parseFloat(document.getElementById('inpPanjang').value) || 0;
  const lebar   = parseFloat(document.getElementById('inpLebar').value) || 0;
  const qty     = parseInt(document.getElementById('inpQty').value) || 1;

  document.getElementById('hargaSatuanDisplay').textContent = 'Rp ' + Number(hargaSatuan).toLocaleString('id-ID');

  let totalCetak = 0;
  if (selectedProduk.satuan === 'cm') {
    totalCetak = hargaSatuan * panjang * lebar * qty;
    document.getElementById('satuanLabel').textContent = 'per cm² — ' + panjang + '×' + lebar + ' cm × ' + qty;
  } else {
    totalCetak = hargaSatuan * qty;
    document.getElementById('satuanLabel').textContent = 'per pcs × ' + qty;
  }

  document.getElementById('totalCetakDisplay').textContent = 'Rp ' + Math.round(totalCetak).toLocaleString('id-ID');
  document.getElementById('sumCetak').textContent = 'Rp ' + Math.round(totalCetak).toLocaleString('id-ID');

  // Finishing
  let totalFinishing = 0;
  const finSel = document.getElementById('selFinishing');
  const finOpt = finSel.selectedOptions[0];
  if (finOpt && finOpt.value) {
    const finHarga = isMember ? parseFloat(finOpt.dataset.hm) : parseFloat(finOpt.dataset.hu);
    totalFinishing = finHarga * qty;
  }
  document.getElementById('sumFinishing').textContent = 'Rp ' + Math.round(totalFinishing).toLocaleString('id-ID');

  const grandTotal = totalCetak + totalFinishing;
  document.getElementById('sumTotal').textContent = 'Rp ' + Math.round(grandTotal).toLocaleString('id-ID');
  document.getElementById('hiddenCalcTotal').value = Math.round(grandTotal);
}

function resetPriceDisplay() {
  document.getElementById('sumCetak').textContent = 'Rp 0';
  document.getElementById('sumFinishing').textContent = 'Rp 0';
  document.getElementById('sumTotal').textContent = 'Rp 0';
  document.getElementById('hiddenCalcTotal').value = '0';
  if (document.getElementById('totalCetakDisplay'))
    document.getElementById('totalCetakDisplay').textContent = 'Rp 0';
  if (document.getElementById('hargaSatuanDisplay'))
    document.getElementById('hargaSatuanDisplay').textContent = 'Rp 0';
}
</script>
<?php layoutEnd(); ?>
