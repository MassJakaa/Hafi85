<?php
die('Database setup sudah selesai dan dikunci untuk keamanan.');
/**
 * HaFI85 Digital Printing — Setup Database (MySQL)
 * Jalankan sekali: php setup.php
 *
 * PASTIKAN:
 * 1. MySQL server sudah berjalan
 * 2. Database sudah dibuat:
 *    CREATE DATABASE hafi85_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
 * 3. Edit config.php sesuai kredensial MySQL Anda
 */

$cfg = require __DIR__ . '/config.php';
$dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['dbname']};charset={$cfg['charset']}";
$db  = new PDO($dsn, $cfg['username'], $cfg['password']);
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// ── Drop tabel lama (urutan: child dulu) ────────────────────────────────────
$db->exec("DROP TABLE IF EXISTS print_session_orders");
$db->exec("DROP TABLE IF EXISTS print_sessions");
$db->exec("DROP TABLE IF EXISTS printers");
$db->exec("DROP TABLE IF EXISTS orders");
$db->exec("DROP TABLE IF EXISTS customers");
$db->exec("DROP TABLE IF EXISTS expenses");
$db->exec("DROP TABLE IF EXISTS users");
$db->exec("DROP TABLE IF EXISTS price_cetak");
$db->exec("DROP TABLE IF EXISTS price_barang");
$db->exec("DROP TABLE IF EXISTS price_finishing");

echo "✓ Tabel lama dihapus\n";

// ── Buat Tabel ─────────────────────────────────────────────────────────────

$db->exec("CREATE TABLE users (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    username   VARCHAR(100) UNIQUE NOT NULL,
    password   VARCHAR(255) NOT NULL,
    role       VARCHAR(50) NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->exec("CREATE TABLE customers (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    customer_id VARCHAR(20) UNIQUE NOT NULL,
    name        VARCHAR(255) NOT NULL,
    phone       VARCHAR(50) NOT NULL,
    is_member   TINYINT DEFAULT 0,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->exec("CREATE TABLE orders (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    order_id          VARCHAR(20) UNIQUE NOT NULL,
    customer_id       INT NOT NULL,
    product_type      VARCHAR(100) NOT NULL,
    panjang           DOUBLE DEFAULT 0,
    lebar             DOUBLE DEFAULT 0,
    bahan             VARCHAR(255),
    quantity          INT DEFAULT 1,
    finishing_type    VARCHAR(255),
    deadline          VARCHAR(20),
    description       TEXT,
    design_file       VARCHAR(255),
    print_file        VARCHAR(255),
    status            VARCHAR(50) DEFAULT 'new_order',
    design_status     VARCHAR(50) DEFAULT 'pending',
    total_price       DOUBLE DEFAULT 0,
    design_fee        DOUBLE DEFAULT 0,
    down_payment      DOUBLE DEFAULT 0,
    remaining_balance DOUBLE DEFAULT 0,
    payment_method    VARCHAR(50) DEFAULT 'cash',
    payment_status    VARCHAR(50) DEFAULT 'unpaid',
    revision_note     TEXT,
    operator_note     TEXT,
    is_urgent         TINYINT DEFAULT 0,
    price_cetak_id    INT DEFAULT NULL,
    price_finishing_id INT DEFAULT NULL,
    harga_cetak_satuan DOUBLE DEFAULT 0,
    harga_finishing_satuan DOUBLE DEFAULT 0,
    created_at        DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at        DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->exec("CREATE TABLE expenses (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    amount      DOUBLE NOT NULL,
    description TEXT,
    created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->exec("CREATE TABLE print_sessions (
    id            INT AUTO_INCREMENT PRIMARY KEY,
    user_id       INT NOT NULL,
    session_date  VARCHAR(20),
    status        VARCHAR(50) DEFAULT 'idle',
    progress      INT DEFAULT 0,
    estimate_time VARCHAR(10) DEFAULT '00:00',
    ink_c         INT DEFAULT 75,
    ink_m         INT DEFAULT 60,
    ink_y         INT DEFAULT 80,
    ink_k         INT DEFAULT 30,
    printer_id    INT DEFAULT 0,
    updated_at    DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->exec("CREATE TABLE print_session_orders (
    id         INT AUTO_INCREMENT PRIMARY KEY,
    session_id INT NOT NULL,
    order_id   INT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->exec("CREATE TABLE printers (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    name            VARCHAR(100) NOT NULL,
    machine         VARCHAR(100) NOT NULL,
    ip_address      VARCHAR(50),
    connection_type VARCHAR(20) DEFAULT 'lan',
    status          VARCHAR(50) DEFAULT 'offline',
    ink_c           INT DEFAULT 0,
    ink_m           INT DEFAULT 0,
    ink_y           INT DEFAULT 0,
    ink_k           INT DEFAULT 0,
    progress        INT DEFAULT 0,
    current_job     VARCHAR(255),
    error_msg       TEXT,
    last_seen       VARCHAR(30),
    created_at      DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at      DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// ── Tabel Harga ─────────────────────────────────────────────────────────────

$db->exec("CREATE TABLE price_cetak (
    id                INT AUTO_INCREMENT PRIMARY KEY,
    jenis_cetak       VARCHAR(50) NOT NULL,
    nama_cetak        VARCHAR(255) NOT NULL,
    satuan            VARCHAR(10) DEFAULT 'pcs',
    harga_member      DOUBLE DEFAULT 0,
    harga_umum        DOUBLE DEFAULT 0,
    nota_harga_member DOUBLE DEFAULT 0,
    nota_harga_umum   DOUBLE DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->exec("CREATE TABLE price_barang (
    id           INT AUTO_INCREMENT PRIMARY KEY,
    nama_barang  VARCHAR(255) NOT NULL,
    harga_member DOUBLE DEFAULT 0,
    harga_umum   DOUBLE DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$db->exec("CREATE TABLE price_finishing (
    id              INT AUTO_INCREMENT PRIMARY KEY,
    jenis_finishing VARCHAR(50) NOT NULL,
    nama_finishing  VARCHAR(255) NOT NULL,
    harga_member    DOUBLE DEFAULT 0,
    harga_umum      DOUBLE DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo "✓ Semua tabel berhasil dibuat\n";

// ── Seed Users ──────────────────────────────────────────────────────────────
$users = [
    ['admin',     'admin123',     'admin'],
    ['desainer',  'desainer123',  'desainer'],
    ['operator_uv',      'operator123',  'operator'],
    ['operator_indoor',  'operator123',  'operator'],
    ['operator_bendera', 'operator123',  'operator'],
    ['operator_dtf',     'operator123',  'operator'],
    ['operator_banner',  'operator123',  'operator'],
    ['operator_a3',      'operator123',  'operator'],
    ['operator_laser1',  'operator123',  'operator'],
    ['operator_laser2',  'operator123',  'operator'],
    ['finishing', 'finishing123', 'finishing'],
    ['owner',     'owner123',     'owner'],
];

$stmt = $db->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
foreach ($users as [$u, $p, $r]) {
    $stmt->execute([$u, password_hash($p, PASSWORD_DEFAULT), $r]);
}
echo "✓ Users berhasil di-seed\n";

// Seed printers
$printerStmt = $db->prepare("INSERT INTO printers (name, machine, ip_address, connection_type) VALUES (?, ?, ?, ?)");
$printers = [
    ['PC Hafi 1 - UV',  'Nocai UV',            null,              'lan'],
    ['PC Indoor',       'Epson S40679',         '192.168.1.164',   'lan'],
    ['PC Bendera',      'Century Star',         '192.168.1.10',    'lan'],
    ['PC DTF',          'Kingjet DTF',          null,              'usb'],
    ['PC Banner',       'Allwin K3204B-512i',   null,              'usb'],
    ['PC Server - A3',  'Ricoh Pro C5100s',     '192.168.1.56',    'lan'],
    ['Laser Cutting 1', 'Laser Cutter',         '192.168.1.135',   'lan'],
    ['Laser Cutting 2', 'Laser Cutter',         null,              'usb'],
];
foreach ($printers as [$name, $machine, $ip, $conn]) {
    $printerStmt->execute([$name, $machine, $ip, $conn]);
}
echo "✓ Printers berhasil di-seed\n";

// ── Seed Price Cetak (267 items dari HargaCetak.xlsx) ───────────────────────
$stmtCetak = $db->prepare("INSERT INTO price_cetak (jenis_cetak, nama_cetak, satuan, harga_member, harga_umum, nota_harga_member, nota_harga_umum) VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmtCetak->execute(['A3', 'AP 120 GR', 'pcs', 2300, 2500, 2300, 2500]);
$stmtCetak->execute(['A3', 'AP 120 GR BB', 'pcs', 4500, 4700, 4500, 4700]);
$stmtCetak->execute(['A3', 'AP 150 GR', 'pcs', 2400, 2600, 2400, 2600]);
$stmtCetak->execute(['A3', 'AP 150 GR BB', 'pcs', 4700, 4900, 4700, 4900]);
$stmtCetak->execute(['A3', 'FLYER A4 AP 150 GR 1 MK 1 RIM', 'pcs', 525000, 550000, 525000, 550000]);
$stmtCetak->execute(['A3', 'FLYER A4 AP 150 GR 2 MK 1 RIM', 'pcs', 975000, 1000000, 975000, 1000000]);
$stmtCetak->execute(['A3', 'FLYER A5 AP 150 GR 1 MK 1 RIM', 'pcs', 300000, 325000, 300000, 325000]);
$stmtCetak->execute(['A3', 'FLYER A5 AP 150 GR 2 MK 1 RIM', 'pcs', 500000, 525000, 500000, 525000]);
$stmtCetak->execute(['A3', 'HVS 80 GR', 'pcs', 2000, 2300, 2000, 2300]);
$stmtCetak->execute(['A3', 'HVS 80 GR BB', 'pcs', 3900, 4300, 3900, 4300]);
$stmtCetak->execute(['A3', 'IVORY 210 GR', 'pcs', 3300, 3500, 3300, 3500]);
$stmtCetak->execute(['A3', 'IVORY 210 GR BB', 'pcs', 6500, 6700, 6500, 6700]);
$stmtCetak->execute(['A3', 'IVORY 230 GR', 'pcs', 3300, 3500, 3300, 3500]);
$stmtCetak->execute(['A3', 'IVORY 230 GR BB', 'pcs', 6500, 6700, 6500, 6700]);
$stmtCetak->execute(['A3', 'IVORY 260 GR', 'pcs', 3500, 3700, 3500, 3700]);
$stmtCetak->execute(['A3', 'IVORY 260 GR BB', 'pcs', 6700, 6900, 6700, 6900]);
$stmtCetak->execute(['A3', 'IVORY 310 GR', 'pcs', 3700, 3900, 3700, 3900]);
$stmtCetak->execute(['A3', 'IVORY 310 GR BB', 'pcs', 6700, 6900, 6700, 6900]);
$stmtCetak->execute(['A3', 'KERTAS FOTO A3', 'pcs', 8000, 10000, 8000, 10000]);
$stmtCetak->execute(['A3', 'LINEN 1 MUKA', 'pcs', 3800, 4000, 3800, 4000]);
$stmtCetak->execute(['A3', 'LINEN 2 MUKA', 'pcs', 7500, 7700, 7500, 7700]);
$stmtCetak->execute(['A3', 'STIKER A3 TRANSPARAN', 'pcs', 6900, 7100, 6700, 7100]);
$stmtCetak->execute(['A3', 'STIKER CROMO', 'pcs', 3800, 4000, 3800, 4000]);
$stmtCetak->execute(['A3', 'STIKER HVS', 'pcs', 3200, 3400, 3200, 3400]);
$stmtCetak->execute(['A3', 'STIKER VINYL', 'pcs', 7300, 7500, 7300, 7500]);
$stmtCetak->execute(['A3', 'STIKER VYNIL A3 POLOSAN', 'pcs', 5000, 5000, 5000, 5000]);
$stmtCetak->execute(['A3', 'VINYL POLOSAN A3', 'pcs', 4000, 4000, 4000, 4000]);
$stmtCetak->execute(['Century', 'SATIN 120 CM (1-5 M)', 'cm', 41250, 45833, 49500, 55000]);
$stmtCetak->execute(['Century', 'SATIN 120 CM (>5 M)', 'cm', 37500, 41666, 45000, 50000]);
$stmtCetak->execute(['Century', 'SATIN 90 CM (1-5 M)', 'cm', 50000, 55555, 45000, 50000]);
$stmtCetak->execute(['Century', 'SATIN 90 CM (>5 M)', 'cm', 45000, 50000, 40500, 45000]);
$stmtCetak->execute(['Century', 'TC IMPORT 120 CM (1-5 M)', 'cm', 37500, 41666, 45000, 50000]);
$stmtCetak->execute(['Century', 'TC IMPORT 120 CM (>5 M)', 'cm', 33750, 37500, 40500, 45000]);
$stmtCetak->execute(['Century', 'TC IMPORT 90 CM (1-5 M)', 'cm', 40000, 44444, 36000, 40000]);
$stmtCetak->execute(['Century', 'TC IMPORT 90 CM (>5 M)', 'cm', 35000, 38888, 31500, 35000]);
$stmtCetak->execute(['DTF', 'DTF 1-3 METER', 'cm', 103448, 120689, 60000, 70000]);
$stmtCetak->execute(['DTF', 'DTF 3-10 METER', 'cm', 86206, 103448, 50000, 60000]);
$stmtCetak->execute(['DTF', 'DTF >10 METER', 'cm', 77586, 86206, 45000, 50000]);
$stmtCetak->execute(['Indoor', 'ALBATROS DOFF', 'cm', 60000, 65000, 60000, 65000]);
$stmtCetak->execute(['Indoor', 'ALBATROS GLOSY', 'cm', 60000, 65000, 60000, 65000]);
$stmtCetak->execute(['Indoor', 'ALBATROS POLOSAN', 'cm', 45000, 45000, 45000, 45000]);
$stmtCetak->execute(['Indoor', 'CLOTH BANNER (1 METER)', 'cm', 35000, 40000, 35000, 40000]);
$stmtCetak->execute(['Indoor', 'DURATRANS', 'cm', 60000, 65000, 60000, 65000]);
$stmtCetak->execute(['Indoor', 'FLEXI GARMAN', 'cm', 40000, 45000, 40000, 45000]);
$stmtCetak->execute(['Indoor', 'KANVAS', 'cm', 135000, 150000, 135000, 150000]);
$stmtCetak->execute(['Indoor', 'LUSTER', 'cm', 60000, 65000, 60000, 65000]);
$stmtCetak->execute(['Indoor', 'PAKET STIKER IMPRA 50X75', 'cm', 110000, 110000, 110000, 110000]);
$stmtCetak->execute(['Indoor', 'SATIN POSTER PAPPER', 'cm', 50000, 55000, 50000, 55000]);
$stmtCetak->execute(['Indoor', 'STIKER AVERY BLACKOUT', 'cm', 85000, 90000, 85000, 90000]);
$stmtCetak->execute(['Indoor', 'STIKER AVERY BLACKOUT (1 METER)', 'cm', 85000, 90000, 85000, 90000]);
$stmtCetak->execute(['Indoor', 'STIKER INDOOR (1.2 METER)', 'cm', 80000, 90000, 80000, 90000]);
$stmtCetak->execute(['Indoor', 'STIKER INDOOR 1.5M', 'cm', 80000, 90000, 80000, 90000]);
$stmtCetak->execute(['Indoor', 'STIKER ONEWAY (1 METER)', 'cm', 110000, 120000, 110000, 120000]);
$stmtCetak->execute(['Indoor', 'STIKER TRANSPARAN (1 METER)', 'cm', 80000, 90000, 80000, 90000]);
$stmtCetak->execute(['Indoor', 'STIKER VYNIL (1 METER)', 'cm', 80000, 90000, 80000, 90000]);
$stmtCetak->execute(['Indoor', 'VINYL POLOSAN', 'cm', 60000, 60000, 60000, 60000]);
$stmtCetak->execute(['Offset', 'JASA POTONG A', 'pcs', 10000, 10000, 10000, 10000]);
$stmtCetak->execute(['Offset', 'NOMORATOR STD', 'pcs', 15000, 20000, 15000, 20000]);
$stmtCetak->execute(['Offset', 'ALKAUSAR BULETIN BB', 'pcs', 134000, 134000, 134000, 134000]);
$stmtCetak->execute(['Offset', 'AMPLOP FALLAH', 'pcs', 25000, 25000, 25000, 25000]);
$stmtCetak->execute(['Offset', 'AMPLOP FULL COLOR 9 X 17 CM', 'pcs', 2400, 2400, 2400, 2400]);
$stmtCetak->execute(['Offset', 'AMPLOP KABINET', 'pcs', 55000, 69000, 55000, 69000]);
$stmtCetak->execute(['Offset', 'AMPLOP KABINET FC', 'pcs', 70000, 75000, 70000, 75000]);
$stmtCetak->execute(['Offset', 'AMPLOP KACA', 'pcs', 92000, 115000, 92000, 115000]);
$stmtCetak->execute(['Offset', 'AMPLOP SAMSON', 'pcs', 50000, 55000, 50000, 55000]);
$stmtCetak->execute(['Offset', 'BAF BROSUR BB 1/2', 'pcs', 55000, 55000, 55000, 55000]);
$stmtCetak->execute(['Offset', 'BC (100 LEMBAR) 1 MUKA', 'pcs', 81000, 94500, 81000, 94500]);
$stmtCetak->execute(['Offset', 'BC (100 LEMBAR) 2 MUKA', 'pcs', 99000, 115500, 99000, 115500]);
$stmtCetak->execute(['Offset', 'BC (200 LEMBAR) 1 MUKA', 'pcs', 121200, 140400, 121200, 140400]);
$stmtCetak->execute(['Offset', 'BC (200 LEMBAR) 2 MUKA', 'pcs', 156000, 182000, 156000, 182000]);
$stmtCetak->execute(['Offset', 'BC (300 LEMBAR) 1 MUKA', 'pcs', 155400, 181300, 155400, 181300]);
$stmtCetak->execute(['Offset', 'BC (300 LEMBAR) 2 MUKA', 'pcs', 196200, 228900, 196200, 228900]);
$stmtCetak->execute(['Offset', 'BC (400 LEMBAR) 1 MUKA', 'pcs', 207000, 242000, 207000, 242000]);
$stmtCetak->execute(['Offset', 'BC (400 LEMBAR) 2 MUKA', 'pcs', 242000, 282000, 242000, 282000]);
$stmtCetak->execute(['Offset', 'BC (500 LEMBAR) 1 MUKA', 'pcs', 247000, 289100, 247000, 289100]);
$stmtCetak->execute(['Offset', 'BC (500 LEMBAR) 2 MUKA', 'pcs', 282000, 329700, 282000, 329700]);
$stmtCetak->execute(['Offset', 'BC 1 WARNA / DAHLIA ISI 100', 'pcs', 20000, 20000, 20000, 20000]);
$stmtCetak->execute(['Offset', 'BROSUR F4 70 GR (1 WARNA) 1 RIM', 'pcs', 105600, 123200, 105600, 123200]);
$stmtCetak->execute(['Offset', 'BROSUR F4 70 GR (2 WARNA) 1 RIM', 'pcs', 134400, 156800, 134400, 156800]);
$stmtCetak->execute(['Offset', 'BROSUR F4 70 GR (3 WARNA) 1 RIM', 'pcs', 163200, 190400, 163200, 190400]);
$stmtCetak->execute(['Offset', 'BROSUR F4 80 GR (1 WARNA) 1 RIM', 'pcs', 122400, 142800, 122400, 142800]);
$stmtCetak->execute(['Offset', 'BROSUR F4 80 GR (2 WARNA) 1 RIM', 'pcs', 151800, 177100, 151800, 177100]);
$stmtCetak->execute(['Offset', 'BROSUR F4 80 GR (3 WARNA) 1 RIM', 'pcs', 180600, 210700, 180600, 210700]);
$stmtCetak->execute(['Offset', 'Buku kenangan SD IT', 'pcs', 60000, 60000, 60000, 60000]);
$stmtCetak->execute(['Offset', 'BUKU POLOS A7 ISI 40 LBR', 'pcs', 3000, 3000, 3000, 3000]);
$stmtCetak->execute(['Offset', 'BURAM 1 WARNA', 'pcs', 88800, 103600, 88800, 103600]);
$stmtCetak->execute(['Offset', 'BURAM 2 WARNA', 'pcs', 117600, 137200, 117600, 137200]);
$stmtCetak->execute(['Offset', 'BURAM 3 WARNA', 'pcs', 146400, 170800, 146400, 170800]);
$stmtCetak->execute(['Offset', 'CETAK AMPLOP', 'pcs', 5000, 7000, 5000, 7000]);
$stmtCetak->execute(['Offset', 'FILM BARU', 'pcs', 17000, 0, 17000, 0]);
$stmtCetak->execute(['Offset', 'FILM LAMA', 'pcs', 5000, 0, 5000, 0]);
$stmtCetak->execute(['Offset', 'FINISING NOTA', 'pcs', 13000, 13000, 13000, 13000]);
$stmtCetak->execute(['Offset', 'FLASH 2 WARNA', 'pcs', 100000, 100000, 100000, 100000]);
$stmtCetak->execute(['Offset', 'FULLC AP A4 BB', 'pcs', 850000, 850000, 850000, 850000]);
$stmtCetak->execute(['Offset', 'FULLC AP A4 BB KILAT', 'pcs', 950000, 950000, 950000, 950000]);
$stmtCetak->execute(['Offset', 'FULLC AP A5 BB', 'pcs', 500000, 500000, 500000, 500000]);
$stmtCetak->execute(['Offset', 'FULLC AP A5 BB KILAT', 'pcs', 650000, 650000, 650000, 650000]);
$stmtCetak->execute(['Offset', 'FULLC CROMO A3 (1 RIM)', 'pcs', 1680000, 1680000, 1680000, 1680000]);
$stmtCetak->execute(['Offset', 'GIGA BC 100 LBR 1 MUKA', 'pcs', 67500, 0, 67500, 0]);
$stmtCetak->execute(['Offset', 'GIGA BC 100 LBR 2 MUKA', 'pcs', 92500, 0, 92500, 0]);
$stmtCetak->execute(['Offset', 'GIGA BC 200 LBR 1 MUKA', 'pcs', 102000, 0, 102000, 0]);
$stmtCetak->execute(['Offset', 'GIGA BC 200 LBR 2 MUKA', 'pcs', 130000, 0, 130000, 0]);
$stmtCetak->execute(['Offset', 'GIGA BURAM 1PLAY 1 WARNA', 'pcs', 75000, 0, 75000, 0]);
$stmtCetak->execute(['Offset', 'GIGA BURAM 1PLAY 2 WARNA', 'pcs', 98000, 0, 98000, 0]);
$stmtCetak->execute(['Offset', 'GIGA BURAM 1PLAY 3 WARNA', 'pcs', 122000, 0, 122000, 0]);
$stmtCetak->execute(['Offset', 'GIGA HVS 100GR 1 WARNA', 'pcs', 125000, 125000, 125000, 125000]);
$stmtCetak->execute(['Offset', 'GIGA HVS 75GR 1 PLAY 1 WARNA', 'pcs', 95000, 0, 95000, 0]);
$stmtCetak->execute(['Offset', 'GIGA HVS 75GR 1 PLAY 2 WARNA', 'pcs', 125000, 0, 125000, 0]);
$stmtCetak->execute(['Offset', 'GIGA HVS 75GR 1 PLAY 3 WARNA', 'pcs', 136000, 0, 136000, 0]);
$stmtCetak->execute(['Offset', 'GIGA HVS 80GR 1 PLAY 1 WARNA', 'pcs', 108000, 0, 108000, 0]);
$stmtCetak->execute(['Offset', 'GIGA HVS 80GR 1 PLAY 2 WARNA', 'pcs', 132000, 0, 132000, 0]);
$stmtCetak->execute(['Offset', 'GIGA HVS 80GR 1 PLAY 3 WARNA', 'pcs', 156000, 0, 156000, 0]);
$stmtCetak->execute(['Offset', 'GIGA HVS WARNA 60GR 1 WARNA', 'pcs', 105000, 0, 105000, 0]);
$stmtCetak->execute(['Offset', 'GIGA HVS WARNA 60GR 2 WARNA', 'pcs', 128000, 0, 128000, 0]);
$stmtCetak->execute(['Offset', 'GIGA HVS WARNA 60GR 3 WARNA', 'pcs', 155000, 0, 155000, 0]);
$stmtCetak->execute(['Offset', 'GIGA HVS WARNA 70GR 1 WARNA', 'pcs', 120000, 0, 120000, 0]);
$stmtCetak->execute(['Offset', 'GIGA HVS WARNA 70GR 2 WARNA', 'pcs', 145000, 0, 145000, 0]);
$stmtCetak->execute(['Offset', 'GIGA HVS WARNA 70GR 3 WARNA', 'pcs', 163000, 0, 163000, 0]);
$stmtCetak->execute(['Offset', 'GIGA NCR 1 PLAY 1 WARNA', 'pcs', 110000, 0, 110000, 0]);
$stmtCetak->execute(['Offset', 'GIGA NCR 1 PLAY 2 WARNA', 'pcs', 125000, 0, 125000, 0]);
$stmtCetak->execute(['Offset', 'GIGA NCR 1 PLAY 3 WARNA', 'pcs', 155000, 0, 155000, 0]);
$stmtCetak->execute(['Offset', 'GIGA NCR 2 PLAY 1 WARNA', 'pcs', 200000, 0, 200000, 0]);
$stmtCetak->execute(['Offset', 'GIGA NCR 2 PLAY 2 WARNA', 'pcs', 230000, 0, 230000, 0]);
$stmtCetak->execute(['Offset', 'GIGA NCR 2 PLAY 3 WARNA', 'pcs', 260000, 0, 260000, 0]);
$stmtCetak->execute(['Offset', 'GIGA NCR 3 PLAY 1 WARNA', 'pcs', 282500, 0, 282500, 0]);
$stmtCetak->execute(['Offset', 'GIGA NCR 3 PLAY 2 WARNA', 'pcs', 327500, 0, 327500, 0]);
$stmtCetak->execute(['Offset', 'GIGA NCR 3 PLAY 3 WARNA', 'pcs', 372500, 0, 372500, 0]);
$stmtCetak->execute(['Offset', 'GIGA NCR 4 PLAY 1 WARNA', 'pcs', 371000, 0, 371000, 0]);
$stmtCetak->execute(['Offset', 'GIGA NCR 4 PLAY 2 WARNA', 'pcs', 430000, 0, 430000, 0]);
$stmtCetak->execute(['Offset', 'GIGA NCR 4 PLAY 3 WARNA', 'pcs', 471000, 0, 471000, 0]);
$stmtCetak->execute(['Offset', 'GIGA NCR 5 PLAY 1 WARNA', 'pcs', 464000, 0, 464000, 0]);
$stmtCetak->execute(['Offset', 'GIGA NCR 5 PLAY 2 WARNA', 'pcs', 528000, 0, 528000, 0]);
$stmtCetak->execute(['Offset', 'GIGA NCR 5 PLAY 3 WARNA', 'pcs', 592000, 0, 592000, 0]);
$stmtCetak->execute(['Offset', 'GIGA NOTA HVS 75GR & HVS WARNA 60GR', 'pcs', 180000, 0, 180000, 0]);
$stmtCetak->execute(['Offset', 'HVS 1 WARNA 1 RIM', 'pcs', 105000, 123000, 105000, 123000]);
$stmtCetak->execute(['Offset', 'JASA CETAK', 'pcs', 12000, 0, 12000, 0]);
$stmtCetak->execute(['Offset', 'JASA JILID NOTA', 'pcs', 12000, 0, 12000, 0]);
$stmtCetak->execute(['Offset', 'JASA ONGKOS POTONG B', 'pcs', 20000, 20000, 20000, 20000]);
$stmtCetak->execute(['Offset', 'JASA SUSUN NOTA', 'pcs', 6000, 0, 6000, 0]);
$stmtCetak->execute(['Offset', 'KARTU NAMA IVORY (1 MUKA) 1 BOX', 'pcs', 30000, 35000, 30000, 35000]);
$stmtCetak->execute(['Offset', 'KARTU NAMA IVORY (2 MUKA) 1 BOX', 'pcs', 55000, 55000, 55000, 55000]);
$stmtCetak->execute(['Offset', 'KERTAS NCR MIDLE', 'pcs', 67500, 0, 67500, 0]);
$stmtCetak->execute(['Offset', 'KERTAS NCR TOP', 'pcs', 67000, 0, 67000, 0]);
$stmtCetak->execute(['Offset', 'KERTAS BC ISI 100', 'pcs', 35000, 0, 35000, 0]);
$stmtCetak->execute(['Offset', 'KERTAS CD / BURAM', 'pcs', 35000, 0, 35000, 0]);
$stmtCetak->execute(['Offset', 'KERTAS F4 SIDU 80 GR', 'pcs', 75000, 0, 75000, 0]);
$stmtCetak->execute(['Offset', 'KERTAS HVS F4 75 GR', 'pcs', 53000, 0, 53000, 0]);
$stmtCetak->execute(['Offset', 'KERTAS NCR BOTT', 'pcs', 62000, 0, 62000, 0]);
$stmtCetak->execute(['Offset', 'KERTAS WARNA 60 GR', 'pcs', 69000, 0, 69000, 0]);
$stmtCetak->execute(['Offset', 'KERTAS WARNA 70 GR', 'pcs', 79000, 0, 79000, 0]);
$stmtCetak->execute(['Offset', 'KOP SURAT HVS 70 GR (1 WARNA)', 'pcs', 105600, 123000, 105600, 123000]);
$stmtCetak->execute(['Offset', 'KOP SURAT HVS 70 GR (2 WARNA)', 'pcs', 134400, 156800, 134400, 156800]);
$stmtCetak->execute(['Offset', 'KOP SURAT HVS 70 GR (FULL COLLOR)', 'pcs', 190000, 190000, 190000, 190000]);
$stmtCetak->execute(['Offset', 'KUPON ARTPAPER 10X5 <500', 'pcs', 250000, 250000, 250000, 250000]);
$stmtCetak->execute(['Offset', 'NCR 1 PLY 1 WARNA', 'pcs', 125000, 147000, 125000, 147000]);
$stmtCetak->execute(['Offset', 'NCR 1 PLY 2 WARNA', 'pcs', 144000, 167000, 144000, 167000]);
$stmtCetak->execute(['Offset', 'NCR 1 PLY 3 WARNA', 'pcs', 185000, 215000, 185000, 215000]);
$stmtCetak->execute(['Offset', 'NCR 2 PLY 1 WARNA', 'pcs', 225000, 260000, 225000, 260000]);
$stmtCetak->execute(['Offset', 'NCR 2 PLY 2 WARNA', 'pcs', 267800, 313600, 267800, 313600]);
$stmtCetak->execute(['Offset', 'NCR 2 PLY 3 WARNA', 'pcs', 308600, 356800, 308600, 356800]);
$stmtCetak->execute(['Offset', 'NCR 3 PLY 1 WARNA', 'pcs', 332600, 387200, 332600, 387200]);
$stmtCetak->execute(['Offset', 'NCR 3 PLY 2 WARNA', 'pcs', 385400, 448800, 385400, 448800]);
$stmtCetak->execute(['Offset', 'NCR 3 PLY 3 WARNA', 'pcs', 438200, 510400, 438200, 510400]);
$stmtCetak->execute(['Offset', 'NOMER MEJA MAGNET', 'pcs', 12000, 12000, 12000, 12000]);
$stmtCetak->execute(['Offset', 'NOMORATOR EXTRA', 'pcs', 20000, 25000, 20000, 25000]);
$stmtCetak->execute(['Offset', 'NOTA FULLCOLOUR ISI 100/BENDEL FULLCOLOUR', 'pcs', 10000, 10000, 10000, 10000]);
$stmtCetak->execute(['Offset', 'NOTA FULLCOLOUR ISI 100PC/BENDEL FULLCOLOUR 1/2F', 'pcs', 20000, 20000, 20000, 20000]);
$stmtCetak->execute(['Offset', 'NOTA JK SAYUR', 'pcs', 110000, 110000, 110000, 110000]);
$stmtCetak->execute(['Offset', 'NOTA/BUKU UKURAN 1/4 : 1 WARNA', 'pcs', 6000, 8000, 6000, 8000]);
$stmtCetak->execute(['Offset', 'NOTA/BUKU UKURAN 1/4 : FULLCOLLOUR', 'pcs', 12000, 16000, 12000, 16000]);
$stmtCetak->execute(['Offset', 'PLATE BR', 'pcs', 18000, 18000, 18000, 18000]);
$stmtCetak->execute(['Offset', 'PLATE RO', 'pcs', 6000, 10000, 6000, 10000]);
$stmtCetak->execute(['Offset', 'RAPORT 70 GRAM', 'pcs', 95000, 95000, 95000, 95000]);
$stmtCetak->execute(['Offset', 'RAPORT 80 GRAM', 'pcs', 105000, 105000, 105000, 105000]);
$stmtCetak->execute(['Offset', 'SABLON PLASTIK OPP', 'pcs', 110000, 110000, 110000, 110000]);
$stmtCetak->execute(['Offset', 'SAMSON', 'pcs', 50000, 55000, 50000, 55000]);
$stmtCetak->execute(['Offset', 'SAMSON ALAS KAKI A3 (PER 1000)', 'pcs', 408000, 476000, 408000, 476000]);
$stmtCetak->execute(['Offset', 'SAMSON ALAS KAKI A3 (PER 500)', 'pcs', 250000, 350000, 250000, 350000]);
$stmtCetak->execute(['Offset', 'SKOTLET 1 WARNA', 'pcs', 60000, 60000, 60000, 60000]);
$stmtCetak->execute(['Offset', 'STAMPEL FLASH 3 WARNA', 'pcs', 125000, 125000, 125000, 125000]);
$stmtCetak->execute(['Offset', 'STEMPEL CRISTAL KECIL', 'pcs', 35000, 35000, 35000, 35000]);
$stmtCetak->execute(['Offset', 'STEMPEL FLASH 1 WARNA', 'pcs', 75000, 75000, 75000, 75000]);
$stmtCetak->execute(['Offset', 'STEMPEL SEDANG', 'pcs', 50000, 50000, 50000, 50000]);
$stmtCetak->execute(['Offset', 'STOPMAP BC F4 (1 WARNA) 100 LEMBAR', 'pcs', 1750, 1750, 1750, 1750]);
$stmtCetak->execute(['Offset', 'STOPMAP IVORY A4 (FULL COLOR) 100 PCS', 'pcs', 6500, 6500, 6500, 6500]);
$stmtCetak->execute(['Offset', 'STOPMAP IVORY A4 (FULL COLOR) 200 PCS', 'pcs', 6000, 6000, 6000, 6000]);
$stmtCetak->execute(['Offset', 'STOPMAP IVORY A4 (FULL COLOR) 500 PCS', 'pcs', 5000, 5000, 5000, 5000]);
$stmtCetak->execute(['Offset', 'STOPMAP IVORY F4 (FULL COLOR) 100 PCS', 'pcs', 8500, 8500, 8500, 8500]);
$stmtCetak->execute(['Offset', 'STOPMAP IVORY SABLON (1 WARNA) 100 PCS', 'pcs', 2500, 2500, 2500, 2500]);
$stmtCetak->execute(['Offset', 'STOPMAP IVORY SABLON (1 WARNA) 50 PCS', 'pcs', 3000, 3000, 3000, 3000]);
$stmtCetak->execute(['Offset', 'Undangan blangko salma', 'pcs', 1000, 1000, 1000, 1000]);
$stmtCetak->execute(['Outdoor', 'BACKLITE', 'cm', 55000, 60000, 55000, 60000]);
$stmtCetak->execute(['Outdoor', 'CLOTH BANNER', 'cm', 30000, 35000, 30000, 35000]);
$stmtCetak->execute(['Outdoor', 'FLEXY 280', 'cm', 18000, 22000, 18000, 22000]);
$stmtCetak->execute(['Outdoor', 'FLEXY 280 GSM (HARGA KARYAWAN)', 'cm', 17000, 17000, 17000, 17000]);
$stmtCetak->execute(['Outdoor', 'FLEXY 280 KILAT', 'cm', 20000, 24000, 20000, 24000]);
$stmtCetak->execute(['Outdoor', 'FLEXY 280 KOSONGAN', 'cm', 12000, 12000, 12000, 12000]);
$stmtCetak->execute(['Outdoor', 'FLEXY 440', 'cm', 32000, 35000, 32000, 35000]);
$stmtCetak->execute(['Outdoor', 'FLEXY 440 KOSONGAN', 'cm', 24000, 24000, 24000, 24000]);
$stmtCetak->execute(['Outdoor', 'STIKER ONEWAY', 'cm', 80000, 90000, 80000, 90000]);
$stmtCetak->execute(['Outdoor', 'STIKER TRANSPARAN', 'cm', 70000, 80000, 70000, 80000]);
$stmtCetak->execute(['Outdoor', 'STIKER VYNIL', 'cm', 70000, 80000, 70000, 80000]);
$stmtCetak->execute(['Sublim cm', 'PRINT SUBLIM (METER)', 'cm', 100000, 125000, 20000, 25000]);
$stmtCetak->execute(['Sublim pcs', 'KARDUS MUG SUBLIM FULLCOLOUR', 'pcs', 2000, 2000, 2000, 2000]);
$stmtCetak->execute(['Sublim pcs', 'LANYARD + ID CARD + HOLDER', 'pcs', 45000, 45000, 45000, 45000]);
$stmtCetak->execute(['Sublim pcs', 'LANYARD KECIL', 'pcs', 8000, 8000, 8000, 8000]);
$stmtCetak->execute(['Sublim pcs', 'LANYARD TALI COSTUME', 'pcs', 35000, 35000, 35000, 35000]);
$stmtCetak->execute(['Sublim pcs', 'MUG SUBLIM FULLCOLOUR', 'pcs', 18000, 20000, 18000, 20000]);
$stmtCetak->execute(['Sublim pcs', 'PRINT SUBLIM A4', 'pcs', 5000, 6000, 5000, 6000]);
$stmtCetak->execute(['UV cm', '2MM AKRILIK AJA', 'cm', 35000, 35000, 35000, 35000]);
$stmtCetak->execute(['UV cm', 'AKRILIK 2 MM', 'cm', 135000, 155000, 135000, 155000]);
$stmtCetak->execute(['UV cm', 'AKRILIK 2 MM PERNIS', 'cm', 155000, 175000, 155000, 175000]);
$stmtCetak->execute(['UV cm', 'AKRILIK 3 MM', 'cm', 145000, 165000, 145000, 165000]);
$stmtCetak->execute(['UV cm', 'AKRILIK 3 MM (HARGA KARYAWAN)', 'cm', 125000, 125000, 125000, 125000]);
$stmtCetak->execute(['UV cm', 'AKRILIK 3 MM PERNIS', 'cm', 165000, 185000, 165000, 185000]);
$stmtCetak->execute(['UV cm', 'AKRILIK 5 MM', 'cm', 185000, 205000, 185000, 205000]);
$stmtCetak->execute(['UV cm', 'AKRILIK 5 MM PERNIS', 'cm', 205000, 225000, 205000, 225000]);
$stmtCetak->execute(['UV cm', 'PRINT UV', 'cm', 100000, 120000, 100000, 120000]);
$stmtCetak->execute(['UV cm', 'PRINT UV PERNIS', 'cm', 120000, 140000, 120000, 140000]);
$stmtCetak->execute(['UV cm', 'UV (HARGA KARYAWAN)', 'cm', 80000, 80000, 80000, 80000]);
$stmtCetak->execute(['UV pcs', 'DTF UV A3', 'pcs', 70000, 80000, 70000, 80000]);
$stmtCetak->execute(['UV pcs', 'DTF UV A4', 'pcs', 35000, 40000, 35000, 40000]);
$stmtCetak->execute(['UV pcs', 'ID CARD BIASA (MIN 8 PCS)', 'pcs', 8000, 10000, 8000, 10000]);
$stmtCetak->execute(['UV pcs', 'ID CARD UV', 'pcs', 13500, 15000, 13500, 15000]);
$stmtCetak->execute(['UV pcs', 'KOREK (1 SISI)', 'pcs', 4000, 5000, 4000, 5000]);
$stmtCetak->execute(['UV pcs', 'KOREK (2 SISI)', 'pcs', 4500, 6000, 4500, 6000]);
$stmtCetak->execute(['UV pcs', 'KOREK CRICKET 2 SISI', 'pcs', 9000, 10000, 9000, 10000]);
$stmtCetak->execute(['Paket', 'IMPRA BOARD', 'pcs', 50000, 50000, 50000, 50000]);
$stmtCetak->execute(['Paket', 'MINI X BANNER', 'pcs', 20000, 25000, 20000, 25000]);
$stmtCetak->execute(['Paket', 'PAKET IMPRA (40X60) STIKER', 'pcs', 90000, 90000, 90000, 90000]);
$stmtCetak->execute(['Paket', 'PAKET IMPRA (50X70) STIKER', 'pcs', 110000, 90000, 110000, 90000]);
$stmtCetak->execute(['Paket', 'PAKET IMPRA (60X80) STIKER', 'pcs', 100000, 125000, 100000, 125000]);
$stmtCetak->execute(['Paket', 'PAKET X BANNER (160X60)', 'pcs', 65000, 75000, 65000, 75000]);
$stmtCetak->execute(['Paket', 'PAKET Y BANNER', 'pcs', 65000, 75000, 65000, 75000]);
$stmtCetak->execute(['Paket', 'ROLL BANNER (160X60) GERMAN', 'pcs', 200000, 220000, 200000, 220000]);
$stmtCetak->execute(['Paket', 'ROLL BANNER (60X160) FLEXY', 'pcs', 175000, 190000, 175000, 190000]);
$stmtCetak->execute(['Paket', 'ROLL BANNER (80X200) FLEXY', 'pcs', 185000, 210000, 185000, 210000]);
$stmtCetak->execute(['Paket', 'SPANRAM (150X60)', 'pcs', 70000, 70000, 70000, 70000]);
$stmtCetak->execute(['Paket', 'SPANRAM (30X40)', 'pcs', 25000, 25000, 25000, 25000]);
$stmtCetak->execute(['Paket', 'SPANRAM (30X60)', 'pcs', 30000, 30000, 30000, 30000]);
$stmtCetak->execute(['Paket', 'SPANRAM (40X60)', 'pcs', 35000, 35000, 35000, 35000]);
$stmtCetak->execute(['Paket', 'SPANRAM (50X70)', 'pcs', 40000, 40000, 40000, 40000]);
$stmtCetak->execute(['Paket', 'SPANRAM (60X80)', 'pcs', 45000, 45000, 45000, 45000]);
$stmtCetak->execute(['Paket', 'SPANRAM (60X90)', 'pcs', 50000, 50000, 50000, 50000]);
$stmtCetak->execute(['Paket', 'SPANRAM (80X120)', 'pcs', 55000, 55000, 55000, 55000]);
$stmtCetak->execute(['Paket', 'SPANRAM SEGITIGA (50X100)', 'pcs', 130000, 130000, 130000, 130000]);
$stmtCetak->execute(['Paket', 'SPANRAM SEGITIGA (60X90)', 'pcs', 125000, 125000, 125000, 125000]);
$stmtCetak->execute(['Paket', 'SPANRAM SEGITIGA 100X60', 'pcs', 135000, 135000, 135000, 135000]);
$stmtCetak->execute(['Paket', 'TRIPOD BANNER', 'pcs', 140000, 150000, 140000, 150000]);
$stmtCetak->execute(['Paket', 'TRIPOD STIKER (50X70)', 'pcs', 210000, 230000, 210000, 230000]);
$stmtCetak->execute(['Paket', 'TRIPOT STIKER (60X80)', 'pcs', 220000, 240000, 220000, 240000]);
$stmtCetak->execute(['Lainnya', 'FIGURA KACA A4', 'pcs', 35000, 35000, 35000, 35000]);
$stmtCetak->execute(['Lainnya', 'FIGURA KACA A4 + IVORY', 'pcs', 45000, 45000, 45000, 45000]);
$stmtCetak->execute(['Lainnya', 'MEMBER BARU', 'pcs', 50000, 50000, 50000, 50000]);
$stmtCetak->execute(['Lainnya', 'NAMETAG AKRILIK MAGNET', 'pcs', 20000, 20000, 20000, 20000]);
$stmtCetak->execute(['Lainnya', 'PIN BROSS 4.4 CM 1-20', 'pcs', 4650, 4900, 4650, 4900]);
$stmtCetak->execute(['Lainnya', 'PIN BROSS 4.4 CM 100++', 'pcs', 4250, 4500, 4250, 4500]);
$stmtCetak->execute(['Lainnya', 'PIN BROSS 4.4 CM 21-50', 'pcs', 4550, 4800, 4550, 4800]);
$stmtCetak->execute(['Lainnya', 'PIN BROSS 4.4 CM 51-100', 'pcs', 4450, 4700, 4450, 4700]);
$stmtCetak->execute(['Lainnya', 'PIN BROSS 5.8 CM 1-20', 'pcs', 5150, 5400, 5150, 5400]);
$stmtCetak->execute(['Lainnya', 'PIN BROSS 5.8 CM 100++', 'pcs', 4850, 5100, 4850, 5100]);
$stmtCetak->execute(['Lainnya', 'PIN BROSS 5.8 CM 21-50', 'pcs', 5050, 5300, 5050, 5300]);
$stmtCetak->execute(['Lainnya', 'PIN BROSS 5.8 CM 51-100', 'pcs', 4950, 5200, 4950, 5200]);
$stmtCetak->execute(['Lainnya', 'PRINT QC', 'pcs', 350000, 350000, 350000, 350000]);
$stmtCetak->execute(['Lainnya', 'SABLON BENDERA 2 WARNA', 'pcs', 4500, 4500, 4500, 4500]);
$stmtCetak->execute(['Lainnya', 'SAMIR WISUDA AKRILIK', 'pcs', 18000, 18000, 18000, 18000]);
$stmtCetak->execute(['Lainnya', 'SPIRAL A4', 'pcs', 10000, 10000, 10000, 10000]);
echo "✓ Price Cetak: 267 items\n";

// ── Seed Price Barang (22 items dari HargaBarang.xlsx) ──────────────────────
$stmtBarang = $db->prepare("INSERT INTO price_barang (nama_barang, harga_member, harga_umum) VALUES (?, ?, ?)");
$stmtBarang->execute(['BARANG BEKAS', 7000, 8000]);
$stmtBarang->execute(['DUS MUG', 2000, 2000]);
$stmtBarang->execute(['FIGURA A4 + CETAK', 40000, 40000]);
$stmtBarang->execute(['GANCI NAMA AKRILIK 8X2.5', 5000, 5000]);
$stmtBarang->execute(['KELING', 500000, 500000]);
$stmtBarang->execute(['LEM', 5000, 5000]);
$stmtBarang->execute(['MINI X BANNER', 15000, 20000]);
$stmtBarang->execute(['PAKET FIGURA A4', 40000, 40000]);
$stmtBarang->execute(['PAKET SETIKER IMPRA 50X70', 100000, 110000]);
$stmtBarang->execute(['PAPAN A5 AKRILIK SEGITIGA', 22500, 25000]);
$stmtBarang->execute(['PENITI', 1000, 1000]);
$stmtBarang->execute(['RING GANCI', 1000, 1000]);
$stmtBarang->execute(['ROLL BANNER 60X160', 175000, 190000]);
$stmtBarang->execute(['ROLL BANNER 80X200', 180000, 200000]);
$stmtBarang->execute(['SPANRAM (100X100)', 60000, 60000]);
$stmtBarang->execute(['SPANRAM (30X60)', 15000, 15000]);
$stmtBarang->execute(['SPANRAM (40X60)', 18000, 18000]);
$stmtBarang->execute(['SPANRAM (50X70)', 20000, 20000]);
$stmtBarang->execute(['SPANRAM (60X80)', 23000, 23000]);
$stmtBarang->execute(['SPANRAM (60X90)', 28000, 28000]);
$stmtBarang->execute(['SPANRAM (80X120)', 30000, 30000]);
$stmtBarang->execute(['STAND BANNER', 40000, 60000]);
echo "✓ Price Barang: 22 items\n";

// ── Seed Price Finishing (27 items dari HargaFinishing.xlsx) ─────────────────
$stmtFinishing = $db->prepare("INSERT INTO price_finishing (jenis_finishing, nama_finishing, harga_member, harga_umum) VALUES (?, ?, ?, ?)");
$stmtFinishing->execute(['Jahit', 'TALI JAHIT BAHAN 120 CM (1 M)', 10000, 10000]);
$stmtFinishing->execute(['Jahit', 'TALI JAHIT BAHAN 120 CM (1-3 M)', 12000, 12000]);
$stmtFinishing->execute(['Jahit', 'TALI JAHIT BAHAN 120 CM (3-5 M)', 15000, 15000]);
$stmtFinishing->execute(['Jahit', 'TALI JAHIT BAHAN 120 CM (>5 M)', 18000, 18000]);
$stmtFinishing->execute(['Jahit', 'TALI JAHIT BAHAN 90 CM (1 M)', 8000, 8000]);
$stmtFinishing->execute(['Jahit', 'TALI JAHIT BAHAN 90 CM (1-3 M)', 10000, 10000]);
$stmtFinishing->execute(['Jahit', 'TALI JAHIT BAHAN 90 CM (3-5 M)', 12000, 12000]);
$stmtFinishing->execute(['Jahit', 'TALI JAHIT BAHAN 90 CM (>5 M)', 14000, 14000]);
$stmtFinishing->execute(['Laminasi cm', 'GLOSSY METERAN', 15000, 15000]);
$stmtFinishing->execute(['Laminasi cm', 'LAMINASI DOFF', 20000, 20000]);
$stmtFinishing->execute(['Laminasi pcs', 'GLOSSY / DOFF DINGIN', 4000, 5000]);
$stmtFinishing->execute(['Laminasi pcs', 'GLOSSY / DOFF PANAS', 2500, 3000]);
$stmtFinishing->execute(['Laminasi pcs', 'LAMINATING A4', 5000, 5000]);
$stmtFinishing->execute(['Laminasi pcs', 'LAMINATING F4', 6000, 6000]);
$stmtFinishing->execute(['Laser cutting', 'GRAFIR', 0, 0]);
$stmtFinishing->execute(['Laser cutting', 'POTONG AKRILIK', 0, 0]);
$stmtFinishing->execute(['Lainnya', 'PASANG ROLL BANNER', 10, 10]);
$stmtFinishing->execute(['Lainnya', 'SPIRAL A4', 10, 10]);
$stmtFinishing->execute(['Lainnya', 'SPIRAL A5', 6, 6]);
echo "✓ Price Finishing: 27 items\n";

echo "\n✓ Database baru berhasil dibuat!\n\n";
echo "✓ Akun login:\n";
echo "  admin            / admin123\n";
echo "  desainer         / desainer123\n";
echo "  operator_uv      / operator123     (PC Hafi 1 - UV)\n";
echo "  operator_indoor  / operator123     (PC Indoor)\n";
echo "  operator_bendera / operator123     (PC Bendera)\n";
echo "  operator_dtf     / operator123     (PC DTF)\n";
echo "  operator_banner  / operator123     (PC Banner)\n";
echo "  operator_a3      / operator123     (PC Server - A3)\n";
echo "  operator_laser1  / operator123     (Laser Cutting 1)\n";
echo "  operator_laser2  / operator123     (Laser Cutting 2)\n";
echo "  finishing        / finishing123\n";
echo "  owner            / owner123\n\n";
echo "Sekarang jalankan: php -S localhost:8000 -t public\n\n";

echo "=== ALUR KERJA ===\n";
echo "1. Admin input order (harga otomatis terhitung dari daftar harga)\n";
echo "   - Ada file design dari pelanggan → langsung ke Operator\n";
echo "   - Tidak ada file design → ke Desainer dulu\n";
echo "2. Desainer upload design → Request Approval ke Admin\n";
echo "3. Admin approve → order masuk ke Operator\n";
echo "4. Operator pilih order → Mulai Print → Finish\n";
echo "5. Finishing kerjakan → Mark as Done\n";
echo "6. Admin dashboard: order muncul di 'Siap di Ambil'\n";
echo "7. Pelanggan ambil → Admin klik PAYMENT → input harga\n";
