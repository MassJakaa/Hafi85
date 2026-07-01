-- ═══════════════════════════════════════════════════════════════
-- HaFI85 Digital Printing — Database Lengkap
-- Generated: 2026-07-01 08:46:03
-- Tabel: customers, expenses, orders, price_barang, price_cetak, price_finishing, print_session_orders, print_sessions, printers, users
-- ═══════════════════════════════════════════════════════════════

SET FOREIGN_KEY_CHECKS=0;

-- ── Tabel: customers ──────────────────────────────────────────────
DROP TABLE IF EXISTS `customers`;
CREATE TABLE `customers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone` varchar(50) NOT NULL,
  `is_member` tinyint(4) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `customer_id` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `customers` (`id`,`customer_id`,`name`,`phone`,`is_member`,`created_at`,`updated_at`) VALUES ('1','HF10001','joko','556595','1','2026-06-29 16:12:09','2026-06-29 16:12:09');
INSERT INTO `customers` (`id`,`customer_id`,`name`,`phone`,`is_member`,`created_at`,`updated_at`) VALUES ('2','HF10002','ata','9326795','0','2026-06-29 16:14:28','2026-06-29 16:14:28');

-- ── Tabel: expenses ──────────────────────────────────────────────
DROP TABLE IF EXISTS `expenses`;
CREATE TABLE `expenses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `amount` double NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `expenses` (`id`,`amount`,`description`,`created_at`,`updated_at`) VALUES ('1','12000','bengsin','2026-06-29 16:21:06','2026-06-29 16:21:06');

-- ── Tabel: orders ──────────────────────────────────────────────
DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` varchar(20) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `product_type` varchar(100) NOT NULL,
  `panjang` double DEFAULT 0,
  `lebar` double DEFAULT 0,
  `bahan` varchar(255) DEFAULT NULL,
  `quantity` int(11) DEFAULT 1,
  `finishing_type` varchar(255) DEFAULT NULL,
  `deadline` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `design_file` varchar(255) DEFAULT NULL,
  `print_file` varchar(255) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'new_order',
  `design_status` varchar(50) DEFAULT 'pending',
  `total_price` double DEFAULT 0,
  `design_fee` double DEFAULT 0,
  `down_payment` double DEFAULT 0,
  `remaining_balance` double DEFAULT 0,
  `payment_method` varchar(50) DEFAULT 'cash',
  `payment_status` varchar(50) DEFAULT 'unpaid',
  `revision_note` text DEFAULT NULL,
  `operator_note` text DEFAULT NULL,
  `is_urgent` tinyint(4) DEFAULT 0,
  `price_cetak_id` int(11) DEFAULT NULL,
  `price_finishing_id` int(11) DEFAULT NULL,
  `harga_cetak_satuan` double DEFAULT 0,
  `harga_finishing_satuan` double DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `orders` (`id`,`order_id`,`customer_id`,`product_type`,`panjang`,`lebar`,`bahan`,`quantity`,`finishing_type`,`deadline`,`description`,`design_file`,`print_file`,`status`,`design_status`,`total_price`,`design_fee`,`down_payment`,`remaining_balance`,`payment_method`,`payment_status`,`revision_note`,`operator_note`,`is_urgent`,`price_cetak_id`,`price_finishing_id`,`harga_cetak_satuan`,`harga_finishing_satuan`,`created_at`,`updated_at`) VALUES ('1','HF10001','1','Sublim pcs','0','0','LANYARD TALI COSTUME','1','','2026-07-02','','design_6a427d4c31093.jpg','print_6a427d743dd8d.cdr','picked_up','approved','35000','0','35000','0','cash','paid',NULL,NULL,'0','207',NULL,'35000','0','2026-06-29 16:12:09','2026-06-29 21:20:47');
INSERT INTO `orders` (`id`,`order_id`,`customer_id`,`product_type`,`panjang`,`lebar`,`bahan`,`quantity`,`finishing_type`,`deadline`,`description`,`design_file`,`print_file`,`status`,`design_status`,`total_price`,`design_fee`,`down_payment`,`remaining_balance`,`payment_method`,`payment_status`,`revision_note`,`operator_note`,`is_urgent`,`price_cetak_id`,`price_finishing_id`,`harga_cetak_satuan`,`harga_finishing_satuan`,`created_at`,`updated_at`) VALUES ('2','HF10002','2','A3','0','0','KERTAS FOTO A3','1','','2026-07-09','','design_6a427ddccb352.png','print_6a427dfdde552.pdf','picked_up','approved','10000','0','10000','0','cash','paid',NULL,NULL,'0','19',NULL,'10000','0','2026-06-29 16:14:28','2026-06-29 21:17:08');

-- ── Tabel: price_barang ──────────────────────────────────────────────
DROP TABLE IF EXISTS `price_barang`;
CREATE TABLE `price_barang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_barang` varchar(255) NOT NULL,
  `harga_member` double DEFAULT 0,
  `harga_umum` double DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('1','BARANG BEKAS','7000','8000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('2','DUS MUG','2000','2000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('3','FIGURA A4 + CETAK','40000','40000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('4','GANCI NAMA AKRILIK 8X2.5','5000','5000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('5','KELING','500000','500000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('6','LEM','5000','5000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('7','MINI X BANNER','15000','20000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('8','PAKET FIGURA A4','40000','40000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('9','PAKET SETIKER IMPRA 50X70','100000','110000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('10','PAPAN A5 AKRILIK SEGITIGA','22500','25000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('11','PENITI','1000','1000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('12','RING GANCI','1000','1000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('13','ROLL BANNER 60X160','175000','190000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('14','ROLL BANNER 80X200','180000','200000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('15','SPANRAM (100X100)','60000','60000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('16','SPANRAM (30X60)','15000','15000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('17','SPANRAM (40X60)','18000','18000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('18','SPANRAM (50X70)','20000','20000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('19','SPANRAM (60X80)','23000','23000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('20','SPANRAM (60X90)','28000','28000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('21','SPANRAM (80X120)','30000','30000');
INSERT INTO `price_barang` (`id`,`nama_barang`,`harga_member`,`harga_umum`) VALUES ('22','STAND BANNER','40000','60000');

-- ── Tabel: price_cetak ──────────────────────────────────────────────
DROP TABLE IF EXISTS `price_cetak`;
CREATE TABLE `price_cetak` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_cetak` varchar(50) NOT NULL,
  `nama_cetak` varchar(255) NOT NULL,
  `satuan` varchar(10) DEFAULT 'pcs',
  `harga_member` double DEFAULT 0,
  `harga_umum` double DEFAULT 0,
  `nota_harga_member` double DEFAULT 0,
  `nota_harga_umum` double DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=268 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('1','A3','AP 120 GR','pcs','2300','2500','2300','2500');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('2','A3','AP 120 GR BB','pcs','4500','4700','4500','4700');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('3','A3','AP 150 GR','pcs','2400','2600','2400','2600');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('4','A3','AP 150 GR BB','pcs','4700','4900','4700','4900');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('5','A3','FLYER A4 AP 150 GR 1 MK 1 RIM','pcs','525000','550000','525000','550000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('6','A3','FLYER A4 AP 150 GR 2 MK 1 RIM','pcs','975000','1000000','975000','1000000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('7','A3','FLYER A5 AP 150 GR 1 MK 1 RIM','pcs','300000','325000','300000','325000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('8','A3','FLYER A5 AP 150 GR 2 MK 1 RIM','pcs','500000','525000','500000','525000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('9','A3','HVS 80 GR','pcs','2000','2300','2000','2300');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('10','A3','HVS 80 GR BB','pcs','3900','4300','3900','4300');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('11','A3','IVORY 210 GR','pcs','3300','3500','3300','3500');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('12','A3','IVORY 210 GR BB','pcs','6500','6700','6500','6700');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('13','A3','IVORY 230 GR','pcs','3300','3500','3300','3500');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('14','A3','IVORY 230 GR BB','pcs','6500','6700','6500','6700');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('15','A3','IVORY 260 GR','pcs','3500','3700','3500','3700');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('16','A3','IVORY 260 GR BB','pcs','6700','6900','6700','6900');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('17','A3','IVORY 310 GR','pcs','3700','3900','3700','3900');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('18','A3','IVORY 310 GR BB','pcs','6700','6900','6700','6900');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('19','A3','KERTAS FOTO A3','pcs','8000','10000','8000','10000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('20','A3','LINEN 1 MUKA','pcs','3800','4000','3800','4000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('21','A3','LINEN 2 MUKA','pcs','7500','7700','7500','7700');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('22','A3','STIKER A3 TRANSPARAN','pcs','6900','7100','6700','7100');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('23','A3','STIKER CROMO','pcs','3800','4000','3800','4000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('24','A3','STIKER HVS','pcs','3200','3400','3200','3400');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('25','A3','STIKER VINYL','pcs','7300','7500','7300','7500');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('26','A3','STIKER VYNIL A3 POLOSAN','pcs','5000','5000','5000','5000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('27','A3','VINYL POLOSAN A3','pcs','4000','4000','4000','4000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('28','Century','SATIN 120 CM (1-5 M)','cm','41250','45833','49500','55000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('29','Century','SATIN 120 CM (>5 M)','cm','37500','41666','45000','50000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('30','Century','SATIN 90 CM (1-5 M)','cm','50000','55555','45000','50000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('31','Century','SATIN 90 CM (>5 M)','cm','45000','50000','40500','45000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('32','Century','TC IMPORT 120 CM (1-5 M)','cm','37500','41666','45000','50000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('33','Century','TC IMPORT 120 CM (>5 M)','cm','33750','37500','40500','45000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('34','Century','TC IMPORT 90 CM (1-5 M)','cm','40000','44444','36000','40000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('35','Century','TC IMPORT 90 CM (>5 M)','cm','35000','38888','31500','35000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('36','DTF','DTF 1-3 METER','cm','103448','120689','60000','70000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('37','DTF','DTF 3-10 METER','cm','86206','103448','50000','60000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('38','DTF','DTF >10 METER','cm','77586','86206','45000','50000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('39','Indoor','ALBATROS DOFF','cm','60000','65000','60000','65000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('40','Indoor','ALBATROS GLOSY','cm','60000','65000','60000','65000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('41','Indoor','ALBATROS POLOSAN','cm','45000','45000','45000','45000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('42','Indoor','CLOTH BANNER (1 METER)','cm','35000','40000','35000','40000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('43','Indoor','DURATRANS','cm','60000','65000','60000','65000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('44','Indoor','FLEXI GARMAN','cm','40000','45000','40000','45000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('45','Indoor','KANVAS','cm','135000','150000','135000','150000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('46','Indoor','LUSTER','cm','60000','65000','60000','65000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('47','Indoor','PAKET STIKER IMPRA 50X75','cm','110000','110000','110000','110000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('48','Indoor','SATIN POSTER PAPPER','cm','50000','55000','50000','55000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('49','Indoor','STIKER AVERY BLACKOUT','cm','85000','90000','85000','90000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('50','Indoor','STIKER AVERY BLACKOUT (1 METER)','cm','85000','90000','85000','90000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('51','Indoor','STIKER INDOOR (1.2 METER)','cm','80000','90000','80000','90000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('52','Indoor','STIKER INDOOR 1.5M','cm','80000','90000','80000','90000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('53','Indoor','STIKER ONEWAY (1 METER)','cm','110000','120000','110000','120000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('54','Indoor','STIKER TRANSPARAN (1 METER)','cm','80000','90000','80000','90000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('55','Indoor','STIKER VYNIL (1 METER)','cm','80000','90000','80000','90000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('56','Indoor','VINYL POLOSAN','cm','60000','60000','60000','60000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('57','Offset','JASA POTONG A','pcs','10000','10000','10000','10000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('58','Offset','NOMORATOR STD','pcs','15000','20000','15000','20000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('59','Offset','ALKAUSAR BULETIN BB','pcs','134000','134000','134000','134000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('60','Offset','AMPLOP FALLAH','pcs','25000','25000','25000','25000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('61','Offset','AMPLOP FULL COLOR 9 X 17 CM','pcs','2400','2400','2400','2400');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('62','Offset','AMPLOP KABINET','pcs','55000','69000','55000','69000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('63','Offset','AMPLOP KABINET FC','pcs','70000','75000','70000','75000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('64','Offset','AMPLOP KACA','pcs','92000','115000','92000','115000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('65','Offset','AMPLOP SAMSON','pcs','50000','55000','50000','55000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('66','Offset','BAF BROSUR BB 1/2','pcs','55000','55000','55000','55000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('67','Offset','BC (100 LEMBAR) 1 MUKA','pcs','81000','94500','81000','94500');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('68','Offset','BC (100 LEMBAR) 2 MUKA','pcs','99000','115500','99000','115500');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('69','Offset','BC (200 LEMBAR) 1 MUKA','pcs','121200','140400','121200','140400');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('70','Offset','BC (200 LEMBAR) 2 MUKA','pcs','156000','182000','156000','182000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('71','Offset','BC (300 LEMBAR) 1 MUKA','pcs','155400','181300','155400','181300');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('72','Offset','BC (300 LEMBAR) 2 MUKA','pcs','196200','228900','196200','228900');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('73','Offset','BC (400 LEMBAR) 1 MUKA','pcs','207000','242000','207000','242000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('74','Offset','BC (400 LEMBAR) 2 MUKA','pcs','242000','282000','242000','282000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('75','Offset','BC (500 LEMBAR) 1 MUKA','pcs','247000','289100','247000','289100');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('76','Offset','BC (500 LEMBAR) 2 MUKA','pcs','282000','329700','282000','329700');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('77','Offset','BC 1 WARNA / DAHLIA ISI 100','pcs','20000','20000','20000','20000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('78','Offset','BROSUR F4 70 GR (1 WARNA) 1 RIM','pcs','105600','123200','105600','123200');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('79','Offset','BROSUR F4 70 GR (2 WARNA) 1 RIM','pcs','134400','156800','134400','156800');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('80','Offset','BROSUR F4 70 GR (3 WARNA) 1 RIM','pcs','163200','190400','163200','190400');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('81','Offset','BROSUR F4 80 GR (1 WARNA) 1 RIM','pcs','122400','142800','122400','142800');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('82','Offset','BROSUR F4 80 GR (2 WARNA) 1 RIM','pcs','151800','177100','151800','177100');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('83','Offset','BROSUR F4 80 GR (3 WARNA) 1 RIM','pcs','180600','210700','180600','210700');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('84','Offset','Buku kenangan SD IT','pcs','60000','60000','60000','60000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('85','Offset','BUKU POLOS A7 ISI 40 LBR','pcs','3000','3000','3000','3000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('86','Offset','BURAM 1 WARNA','pcs','88800','103600','88800','103600');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('87','Offset','BURAM 2 WARNA','pcs','117600','137200','117600','137200');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('88','Offset','BURAM 3 WARNA','pcs','146400','170800','146400','170800');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('89','Offset','CETAK AMPLOP','pcs','5000','7000','5000','7000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('90','Offset','FILM BARU','pcs','17000','0','17000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('91','Offset','FILM LAMA','pcs','5000','0','5000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('92','Offset','FINISING NOTA','pcs','13000','13000','13000','13000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('93','Offset','FLASH 2 WARNA','pcs','100000','100000','100000','100000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('94','Offset','FULLC AP A4 BB','pcs','850000','850000','850000','850000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('95','Offset','FULLC AP A4 BB KILAT','pcs','950000','950000','950000','950000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('96','Offset','FULLC AP A5 BB','pcs','500000','500000','500000','500000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('97','Offset','FULLC AP A5 BB KILAT','pcs','650000','650000','650000','650000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('98','Offset','FULLC CROMO A3 (1 RIM)','pcs','1680000','1680000','1680000','1680000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('99','Offset','GIGA BC 100 LBR 1 MUKA','pcs','67500','0','67500','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('100','Offset','GIGA BC 100 LBR 2 MUKA','pcs','92500','0','92500','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('101','Offset','GIGA BC 200 LBR 1 MUKA','pcs','102000','0','102000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('102','Offset','GIGA BC 200 LBR 2 MUKA','pcs','130000','0','130000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('103','Offset','GIGA BURAM 1PLAY 1 WARNA','pcs','75000','0','75000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('104','Offset','GIGA BURAM 1PLAY 2 WARNA','pcs','98000','0','98000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('105','Offset','GIGA BURAM 1PLAY 3 WARNA','pcs','122000','0','122000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('106','Offset','GIGA HVS 100GR 1 WARNA','pcs','125000','125000','125000','125000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('107','Offset','GIGA HVS 75GR 1 PLAY 1 WARNA','pcs','95000','0','95000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('108','Offset','GIGA HVS 75GR 1 PLAY 2 WARNA','pcs','125000','0','125000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('109','Offset','GIGA HVS 75GR 1 PLAY 3 WARNA','pcs','136000','0','136000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('110','Offset','GIGA HVS 80GR 1 PLAY 1 WARNA','pcs','108000','0','108000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('111','Offset','GIGA HVS 80GR 1 PLAY 2 WARNA','pcs','132000','0','132000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('112','Offset','GIGA HVS 80GR 1 PLAY 3 WARNA','pcs','156000','0','156000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('113','Offset','GIGA HVS WARNA 60GR 1 WARNA','pcs','105000','0','105000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('114','Offset','GIGA HVS WARNA 60GR 2 WARNA','pcs','128000','0','128000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('115','Offset','GIGA HVS WARNA 60GR 3 WARNA','pcs','155000','0','155000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('116','Offset','GIGA HVS WARNA 70GR 1 WARNA','pcs','120000','0','120000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('117','Offset','GIGA HVS WARNA 70GR 2 WARNA','pcs','145000','0','145000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('118','Offset','GIGA HVS WARNA 70GR 3 WARNA','pcs','163000','0','163000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('119','Offset','GIGA NCR 1 PLAY 1 WARNA','pcs','110000','0','110000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('120','Offset','GIGA NCR 1 PLAY 2 WARNA','pcs','125000','0','125000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('121','Offset','GIGA NCR 1 PLAY 3 WARNA','pcs','155000','0','155000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('122','Offset','GIGA NCR 2 PLAY 1 WARNA','pcs','200000','0','200000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('123','Offset','GIGA NCR 2 PLAY 2 WARNA','pcs','230000','0','230000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('124','Offset','GIGA NCR 2 PLAY 3 WARNA','pcs','260000','0','260000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('125','Offset','GIGA NCR 3 PLAY 1 WARNA','pcs','282500','0','282500','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('126','Offset','GIGA NCR 3 PLAY 2 WARNA','pcs','327500','0','327500','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('127','Offset','GIGA NCR 3 PLAY 3 WARNA','pcs','372500','0','372500','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('128','Offset','GIGA NCR 4 PLAY 1 WARNA','pcs','371000','0','371000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('129','Offset','GIGA NCR 4 PLAY 2 WARNA','pcs','430000','0','430000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('130','Offset','GIGA NCR 4 PLAY 3 WARNA','pcs','471000','0','471000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('131','Offset','GIGA NCR 5 PLAY 1 WARNA','pcs','464000','0','464000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('132','Offset','GIGA NCR 5 PLAY 2 WARNA','pcs','528000','0','528000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('133','Offset','GIGA NCR 5 PLAY 3 WARNA','pcs','592000','0','592000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('134','Offset','GIGA NOTA HVS 75GR & HVS WARNA 60GR','pcs','180000','0','180000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('135','Offset','HVS 1 WARNA 1 RIM','pcs','105000','123000','105000','123000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('136','Offset','JASA CETAK','pcs','12000','0','12000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('137','Offset','JASA JILID NOTA','pcs','12000','0','12000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('138','Offset','JASA ONGKOS POTONG B','pcs','20000','20000','20000','20000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('139','Offset','JASA SUSUN NOTA','pcs','6000','0','6000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('140','Offset','KARTU NAMA IVORY (1 MUKA) 1 BOX','pcs','30000','35000','30000','35000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('141','Offset','KARTU NAMA IVORY (2 MUKA) 1 BOX','pcs','55000','55000','55000','55000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('142','Offset','KERTAS NCR MIDLE','pcs','67500','0','67500','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('143','Offset','KERTAS NCR TOP','pcs','67000','0','67000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('144','Offset','KERTAS BC ISI 100','pcs','35000','0','35000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('145','Offset','KERTAS CD / BURAM','pcs','35000','0','35000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('146','Offset','KERTAS F4 SIDU 80 GR','pcs','75000','0','75000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('147','Offset','KERTAS HVS F4 75 GR','pcs','53000','0','53000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('148','Offset','KERTAS NCR BOTT','pcs','62000','0','62000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('149','Offset','KERTAS WARNA 60 GR','pcs','69000','0','69000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('150','Offset','KERTAS WARNA 70 GR','pcs','79000','0','79000','0');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('151','Offset','KOP SURAT HVS 70 GR (1 WARNA)','pcs','105600','123000','105600','123000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('152','Offset','KOP SURAT HVS 70 GR (2 WARNA)','pcs','134400','156800','134400','156800');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('153','Offset','KOP SURAT HVS 70 GR (FULL COLLOR)','pcs','190000','190000','190000','190000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('154','Offset','KUPON ARTPAPER 10X5 <500','pcs','250000','250000','250000','250000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('155','Offset','NCR 1 PLY 1 WARNA','pcs','125000','147000','125000','147000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('156','Offset','NCR 1 PLY 2 WARNA','pcs','144000','167000','144000','167000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('157','Offset','NCR 1 PLY 3 WARNA','pcs','185000','215000','185000','215000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('158','Offset','NCR 2 PLY 1 WARNA','pcs','225000','260000','225000','260000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('159','Offset','NCR 2 PLY 2 WARNA','pcs','267800','313600','267800','313600');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('160','Offset','NCR 2 PLY 3 WARNA','pcs','308600','356800','308600','356800');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('161','Offset','NCR 3 PLY 1 WARNA','pcs','332600','387200','332600','387200');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('162','Offset','NCR 3 PLY 2 WARNA','pcs','385400','448800','385400','448800');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('163','Offset','NCR 3 PLY 3 WARNA','pcs','438200','510400','438200','510400');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('164','Offset','NOMER MEJA MAGNET','pcs','12000','12000','12000','12000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('165','Offset','NOMORATOR EXTRA','pcs','20000','25000','20000','25000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('166','Offset','NOTA FULLCOLOUR ISI 100/BENDEL FULLCOLOUR','pcs','10000','10000','10000','10000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('167','Offset','NOTA FULLCOLOUR ISI 100PC/BENDEL FULLCOLOUR 1/2F','pcs','20000','20000','20000','20000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('168','Offset','NOTA JK SAYUR','pcs','110000','110000','110000','110000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('169','Offset','NOTA/BUKU UKURAN 1/4 : 1 WARNA','pcs','6000','8000','6000','8000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('170','Offset','NOTA/BUKU UKURAN 1/4 : FULLCOLLOUR','pcs','12000','16000','12000','16000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('171','Offset','PLATE BR','pcs','18000','18000','18000','18000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('172','Offset','PLATE RO','pcs','6000','10000','6000','10000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('173','Offset','RAPORT 70 GRAM','pcs','95000','95000','95000','95000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('174','Offset','RAPORT 80 GRAM','pcs','105000','105000','105000','105000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('175','Offset','SABLON PLASTIK OPP','pcs','110000','110000','110000','110000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('176','Offset','SAMSON','pcs','50000','55000','50000','55000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('177','Offset','SAMSON ALAS KAKI A3 (PER 1000)','pcs','408000','476000','408000','476000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('178','Offset','SAMSON ALAS KAKI A3 (PER 500)','pcs','250000','350000','250000','350000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('179','Offset','SKOTLET 1 WARNA','pcs','60000','60000','60000','60000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('180','Offset','STAMPEL FLASH 3 WARNA','pcs','125000','125000','125000','125000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('181','Offset','STEMPEL CRISTAL KECIL','pcs','35000','35000','35000','35000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('182','Offset','STEMPEL FLASH 1 WARNA','pcs','75000','75000','75000','75000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('183','Offset','STEMPEL SEDANG','pcs','50000','50000','50000','50000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('184','Offset','STOPMAP BC F4 (1 WARNA) 100 LEMBAR','pcs','1750','1750','1750','1750');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('185','Offset','STOPMAP IVORY A4 (FULL COLOR) 100 PCS','pcs','6500','6500','6500','6500');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('186','Offset','STOPMAP IVORY A4 (FULL COLOR) 200 PCS','pcs','6000','6000','6000','6000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('187','Offset','STOPMAP IVORY A4 (FULL COLOR) 500 PCS','pcs','5000','5000','5000','5000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('188','Offset','STOPMAP IVORY F4 (FULL COLOR) 100 PCS','pcs','8500','8500','8500','8500');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('189','Offset','STOPMAP IVORY SABLON (1 WARNA) 100 PCS','pcs','2500','2500','2500','2500');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('190','Offset','STOPMAP IVORY SABLON (1 WARNA) 50 PCS','pcs','3000','3000','3000','3000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('191','Offset','Undangan blangko salma','pcs','1000','1000','1000','1000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('192','Outdoor','BACKLITE','cm','55000','60000','55000','60000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('193','Outdoor','CLOTH BANNER','cm','30000','35000','30000','35000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('194','Outdoor','FLEXY 280','cm','18000','22000','18000','22000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('195','Outdoor','FLEXY 280 GSM (HARGA KARYAWAN)','cm','17000','17000','17000','17000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('196','Outdoor','FLEXY 280 KILAT','cm','20000','24000','20000','24000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('197','Outdoor','FLEXY 280 KOSONGAN','cm','12000','12000','12000','12000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('198','Outdoor','FLEXY 440','cm','32000','35000','32000','35000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('199','Outdoor','FLEXY 440 KOSONGAN','cm','24000','24000','24000','24000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('200','Outdoor','STIKER ONEWAY','cm','80000','90000','80000','90000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('201','Outdoor','STIKER TRANSPARAN','cm','70000','80000','70000','80000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('202','Outdoor','STIKER VYNIL','cm','70000','80000','70000','80000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('203','Sublim cm','PRINT SUBLIM (METER)','cm','100000','125000','20000','25000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('204','Sublim pcs','KARDUS MUG SUBLIM FULLCOLOUR','pcs','2000','2000','2000','2000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('205','Sublim pcs','LANYARD + ID CARD + HOLDER','pcs','45000','45000','45000','45000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('206','Sublim pcs','LANYARD KECIL','pcs','8000','8000','8000','8000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('207','Sublim pcs','LANYARD TALI COSTUME','pcs','35000','35000','35000','35000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('208','Sublim pcs','MUG SUBLIM FULLCOLOUR','pcs','18000','20000','18000','20000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('209','Sublim pcs','PRINT SUBLIM A4','pcs','5000','6000','5000','6000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('210','UV cm','2MM AKRILIK AJA','cm','35000','35000','35000','35000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('211','UV cm','AKRILIK 2 MM','cm','135000','155000','135000','155000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('212','UV cm','AKRILIK 2 MM PERNIS','cm','155000','175000','155000','175000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('213','UV cm','AKRILIK 3 MM','cm','145000','165000','145000','165000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('214','UV cm','AKRILIK 3 MM (HARGA KARYAWAN)','cm','125000','125000','125000','125000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('215','UV cm','AKRILIK 3 MM PERNIS','cm','165000','185000','165000','185000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('216','UV cm','AKRILIK 5 MM','cm','185000','205000','185000','205000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('217','UV cm','AKRILIK 5 MM PERNIS','cm','205000','225000','205000','225000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('218','UV cm','PRINT UV','cm','100000','120000','100000','120000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('219','UV cm','PRINT UV PERNIS','cm','120000','140000','120000','140000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('220','UV cm','UV (HARGA KARYAWAN)','cm','80000','80000','80000','80000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('221','UV pcs','DTF UV A3','pcs','70000','80000','70000','80000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('222','UV pcs','DTF UV A4','pcs','35000','40000','35000','40000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('223','UV pcs','ID CARD BIASA (MIN 8 PCS)','pcs','8000','10000','8000','10000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('224','UV pcs','ID CARD UV','pcs','13500','15000','13500','15000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('225','UV pcs','KOREK (1 SISI)','pcs','4000','5000','4000','5000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('226','UV pcs','KOREK (2 SISI)','pcs','4500','6000','4500','6000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('227','UV pcs','KOREK CRICKET 2 SISI','pcs','9000','10000','9000','10000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('228','Paket','IMPRA BOARD','pcs','50000','50000','50000','50000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('229','Paket','MINI X BANNER','pcs','20000','25000','20000','25000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('230','Paket','PAKET IMPRA (40X60) STIKER','pcs','90000','90000','90000','90000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('231','Paket','PAKET IMPRA (50X70) STIKER','pcs','110000','90000','110000','90000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('232','Paket','PAKET IMPRA (60X80) STIKER','pcs','100000','125000','100000','125000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('233','Paket','PAKET X BANNER (160X60)','pcs','65000','75000','65000','75000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('234','Paket','PAKET Y BANNER','pcs','65000','75000','65000','75000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('235','Paket','ROLL BANNER (160X60) GERMAN','pcs','200000','220000','200000','220000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('236','Paket','ROLL BANNER (60X160) FLEXY','pcs','175000','190000','175000','190000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('237','Paket','ROLL BANNER (80X200) FLEXY','pcs','185000','210000','185000','210000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('238','Paket','SPANRAM (150X60)','pcs','70000','70000','70000','70000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('239','Paket','SPANRAM (30X40)','pcs','25000','25000','25000','25000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('240','Paket','SPANRAM (30X60)','pcs','30000','30000','30000','30000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('241','Paket','SPANRAM (40X60)','pcs','35000','35000','35000','35000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('242','Paket','SPANRAM (50X70)','pcs','40000','40000','40000','40000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('243','Paket','SPANRAM (60X80)','pcs','45000','45000','45000','45000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('244','Paket','SPANRAM (60X90)','pcs','50000','50000','50000','50000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('245','Paket','SPANRAM (80X120)','pcs','55000','55000','55000','55000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('246','Paket','SPANRAM SEGITIGA (50X100)','pcs','130000','130000','130000','130000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('247','Paket','SPANRAM SEGITIGA (60X90)','pcs','125000','125000','125000','125000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('248','Paket','SPANRAM SEGITIGA 100X60','pcs','135000','135000','135000','135000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('249','Paket','TRIPOD BANNER','pcs','140000','150000','140000','150000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('250','Paket','TRIPOD STIKER (50X70)','pcs','210000','230000','210000','230000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('251','Paket','TRIPOT STIKER (60X80)','pcs','220000','240000','220000','240000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('252','Lainnya','FIGURA KACA A4','pcs','35000','35000','35000','35000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('253','Lainnya','FIGURA KACA A4 + IVORY','pcs','45000','45000','45000','45000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('254','Lainnya','MEMBER BARU','pcs','50000','50000','50000','50000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('255','Lainnya','NAMETAG AKRILIK MAGNET','pcs','20000','20000','20000','20000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('256','Lainnya','PIN BROSS 4.4 CM 1-20','pcs','4650','4900','4650','4900');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('257','Lainnya','PIN BROSS 4.4 CM 100++','pcs','4250','4500','4250','4500');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('258','Lainnya','PIN BROSS 4.4 CM 21-50','pcs','4550','4800','4550','4800');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('259','Lainnya','PIN BROSS 4.4 CM 51-100','pcs','4450','4700','4450','4700');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('260','Lainnya','PIN BROSS 5.8 CM 1-20','pcs','5150','5400','5150','5400');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('261','Lainnya','PIN BROSS 5.8 CM 100++','pcs','4850','5100','4850','5100');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('262','Lainnya','PIN BROSS 5.8 CM 21-50','pcs','5050','5300','5050','5300');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('263','Lainnya','PIN BROSS 5.8 CM 51-100','pcs','4950','5200','4950','5200');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('264','Lainnya','PRINT QC','pcs','350000','350000','350000','350000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('265','Lainnya','SABLON BENDERA 2 WARNA','pcs','4500','4500','4500','4500');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('266','Lainnya','SAMIR WISUDA AKRILIK','pcs','18000','18000','18000','18000');
INSERT INTO `price_cetak` (`id`,`jenis_cetak`,`nama_cetak`,`satuan`,`harga_member`,`harga_umum`,`nota_harga_member`,`nota_harga_umum`) VALUES ('267','Lainnya','SPIRAL A4','pcs','10000','10000','10000','10000');

-- ── Tabel: price_finishing ──────────────────────────────────────────────
DROP TABLE IF EXISTS `price_finishing`;
CREATE TABLE `price_finishing` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jenis_finishing` varchar(50) NOT NULL,
  `nama_finishing` varchar(255) NOT NULL,
  `harga_member` double DEFAULT 0,
  `harga_umum` double DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('1','Jahit','TALI JAHIT BAHAN 120 CM (1 M)','10000','10000');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('2','Jahit','TALI JAHIT BAHAN 120 CM (1-3 M)','12000','12000');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('3','Jahit','TALI JAHIT BAHAN 120 CM (3-5 M)','15000','15000');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('4','Jahit','TALI JAHIT BAHAN 120 CM (>5 M)','18000','18000');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('5','Jahit','TALI JAHIT BAHAN 90 CM (1 M)','8000','8000');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('6','Jahit','TALI JAHIT BAHAN 90 CM (1-3 M)','10000','10000');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('7','Jahit','TALI JAHIT BAHAN 90 CM (3-5 M)','12000','12000');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('8','Jahit','TALI JAHIT BAHAN 90 CM (>5 M)','14000','14000');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('9','Laminasi cm','GLOSSY METERAN','15000','15000');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('10','Laminasi cm','LAMINASI DOFF','20000','20000');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('11','Laminasi pcs','GLOSSY / DOFF DINGIN','4000','5000');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('12','Laminasi pcs','GLOSSY / DOFF PANAS','2500','3000');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('13','Laminasi pcs','LAMINATING A4','5000','5000');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('14','Laminasi pcs','LAMINATING F4','6000','6000');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('15','Laser cutting','GRAFIR','0','0');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('16','Laser cutting','POTONG AKRILIK','0','0');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('17','Lainnya','PASANG ROLL BANNER','10','10');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('18','Lainnya','SPIRAL A4','10','10');
INSERT INTO `price_finishing` (`id`,`jenis_finishing`,`nama_finishing`,`harga_member`,`harga_umum`) VALUES ('19','Lainnya','SPIRAL A5','6','6');

-- ── Tabel: print_session_orders ──────────────────────────────────────────────
DROP TABLE IF EXISTS `print_session_orders`;
CREATE TABLE `print_session_orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `session_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ── Tabel: print_sessions ──────────────────────────────────────────────
DROP TABLE IF EXISTS `print_sessions`;
CREATE TABLE `print_sessions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `session_date` varchar(20) DEFAULT NULL,
  `status` varchar(50) DEFAULT 'idle',
  `progress` int(11) DEFAULT 0,
  `estimate_time` varchar(10) DEFAULT '00:00',
  `ink_c` int(11) DEFAULT 75,
  `ink_m` int(11) DEFAULT 60,
  `ink_y` int(11) DEFAULT 80,
  `ink_k` int(11) DEFAULT 30,
  `printer_id` int(11) DEFAULT 0,
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `print_sessions` (`id`,`user_id`,`session_date`,`status`,`progress`,`estimate_time`,`ink_c`,`ink_m`,`ink_y`,`ink_k`,`printer_id`,`updated_at`) VALUES ('1','8','2026-06-29','idle','0','05:00','75','60','80','30','0','2026-06-29 21:16:09');
INSERT INTO `print_sessions` (`id`,`user_id`,`session_date`,`status`,`progress`,`estimate_time`,`ink_c`,`ink_m`,`ink_y`,`ink_k`,`printer_id`,`updated_at`) VALUES ('2','4','2026-06-29','idle','0','05:00','75','60','80','30','0','2026-06-29 21:19:55');

-- ── Tabel: printers ──────────────────────────────────────────────
DROP TABLE IF EXISTS `printers`;
CREATE TABLE `printers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `machine` varchar(100) NOT NULL,
  `ip_address` varchar(50) DEFAULT NULL,
  `connection_type` varchar(20) DEFAULT 'lan',
  `status` varchar(50) DEFAULT 'offline',
  `ink_c` int(11) DEFAULT 0,
  `ink_m` int(11) DEFAULT 0,
  `ink_y` int(11) DEFAULT 0,
  `ink_k` int(11) DEFAULT 0,
  `progress` int(11) DEFAULT 0,
  `current_job` varchar(255) DEFAULT NULL,
  `error_msg` text DEFAULT NULL,
  `last_seen` varchar(30) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `printers` (`id`,`name`,`machine`,`ip_address`,`connection_type`,`status`,`ink_c`,`ink_m`,`ink_y`,`ink_k`,`progress`,`current_job`,`error_msg`,`last_seen`,`created_at`,`updated_at`) VALUES ('1','PC Hafi 1 - UV','Nocai UV',NULL,'lan','offline','0','0','0','0','0',NULL,NULL,NULL,'2026-06-29 21:11:06','2026-06-29 21:11:06');
INSERT INTO `printers` (`id`,`name`,`machine`,`ip_address`,`connection_type`,`status`,`ink_c`,`ink_m`,`ink_y`,`ink_k`,`progress`,`current_job`,`error_msg`,`last_seen`,`created_at`,`updated_at`) VALUES ('2','PC Indoor','Epson S40679','192.168.1.164','lan','offline','0','0','0','0','0',NULL,NULL,NULL,'2026-06-29 21:11:06','2026-06-29 21:11:06');
INSERT INTO `printers` (`id`,`name`,`machine`,`ip_address`,`connection_type`,`status`,`ink_c`,`ink_m`,`ink_y`,`ink_k`,`progress`,`current_job`,`error_msg`,`last_seen`,`created_at`,`updated_at`) VALUES ('3','PC Bendera','Century Star','192.168.1.10','lan','offline','0','0','0','0','0',NULL,NULL,NULL,'2026-06-29 21:11:06','2026-06-29 21:11:06');
INSERT INTO `printers` (`id`,`name`,`machine`,`ip_address`,`connection_type`,`status`,`ink_c`,`ink_m`,`ink_y`,`ink_k`,`progress`,`current_job`,`error_msg`,`last_seen`,`created_at`,`updated_at`) VALUES ('4','PC DTF','Kingjet DTF',NULL,'usb','offline','0','0','0','0','0',NULL,NULL,NULL,'2026-06-29 21:11:06','2026-06-29 21:11:06');
INSERT INTO `printers` (`id`,`name`,`machine`,`ip_address`,`connection_type`,`status`,`ink_c`,`ink_m`,`ink_y`,`ink_k`,`progress`,`current_job`,`error_msg`,`last_seen`,`created_at`,`updated_at`) VALUES ('5','PC Banner','Allwin K3204B-512i',NULL,'usb','offline','0','0','0','0','0',NULL,NULL,NULL,'2026-06-29 21:11:06','2026-06-29 21:11:06');
INSERT INTO `printers` (`id`,`name`,`machine`,`ip_address`,`connection_type`,`status`,`ink_c`,`ink_m`,`ink_y`,`ink_k`,`progress`,`current_job`,`error_msg`,`last_seen`,`created_at`,`updated_at`) VALUES ('6','PC Server - A3','Ricoh Pro C5100s','192.168.1.56','lan','offline','0','0','0','0','0',NULL,NULL,NULL,'2026-06-29 21:11:06','2026-06-29 21:11:06');
INSERT INTO `printers` (`id`,`name`,`machine`,`ip_address`,`connection_type`,`status`,`ink_c`,`ink_m`,`ink_y`,`ink_k`,`progress`,`current_job`,`error_msg`,`last_seen`,`created_at`,`updated_at`) VALUES ('7','Laser Cutting 1','Laser Cutter','192.168.1.135','lan','offline','0','0','0','0','0',NULL,NULL,NULL,'2026-06-29 21:11:06','2026-06-29 21:11:06');
INSERT INTO `printers` (`id`,`name`,`machine`,`ip_address`,`connection_type`,`status`,`ink_c`,`ink_m`,`ink_y`,`ink_k`,`progress`,`current_job`,`error_msg`,`last_seen`,`created_at`,`updated_at`) VALUES ('8','Laser Cutting 2','Laser Cutter',NULL,'usb','offline','0','0','0','0','0',NULL,NULL,NULL,'2026-06-29 21:11:06','2026-06-29 21:11:06');

-- ── Tabel: users ──────────────────────────────────────────────
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(50) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`,`username`,`password`,`role`,`created_at`,`updated_at`) VALUES ('1','admin','$2y$10$LHdqXEhbEZHkPTD4wga3zuQgEd/rLhaUMc3o3.oqcjgpaIeI4EOtu','admin','2026-06-29 21:11:05','2026-06-29 21:11:05');
INSERT INTO `users` (`id`,`username`,`password`,`role`,`created_at`,`updated_at`) VALUES ('2','desainer','$2y$10$LWHny7c4uRwdeMoCSEq4IuXZR1yRhwnM0kq0sKFTmEVUyWAuEflga','desainer','2026-06-29 21:11:05','2026-06-29 21:11:05');
INSERT INTO `users` (`id`,`username`,`password`,`role`,`created_at`,`updated_at`) VALUES ('3','operator_uv','$2y$10$1MXkhKctYt9QhVdHT7O0DeC/5Se399YiBOovESY.okSQkzdyWc6R2','operator','2026-06-29 21:11:05','2026-06-29 21:11:05');
INSERT INTO `users` (`id`,`username`,`password`,`role`,`created_at`,`updated_at`) VALUES ('4','operator_indoor','$2y$10$PuxeANsd/wjEgxBpj0BSruuJWFjKDTwioOs6wiykASvCZPMyQpqTC','operator','2026-06-29 21:11:05','2026-06-29 21:11:05');
INSERT INTO `users` (`id`,`username`,`password`,`role`,`created_at`,`updated_at`) VALUES ('5','operator_bendera','$2y$10$OrxCEbMpJwxu.CCHMkbmyOnTzw.l5FXs4DnzKy5h5zcExYDcisq3e','operator','2026-06-29 21:11:05','2026-06-29 21:11:05');
INSERT INTO `users` (`id`,`username`,`password`,`role`,`created_at`,`updated_at`) VALUES ('6','operator_dtf','$2y$10$3uxa5EyN6bmIgkYMGvjCIO9HlRTgJnkCWZUe5JX0zFM8n7AQ7wwfu','operator','2026-06-29 21:11:05','2026-06-29 21:11:05');
INSERT INTO `users` (`id`,`username`,`password`,`role`,`created_at`,`updated_at`) VALUES ('7','operator_banner','$2y$10$aXGI0Hg3kA7gp7GJFPaT6evzy2Sne3KzSau/PlZW8h..bBLQnNOtm','operator','2026-06-29 21:11:05','2026-06-29 21:11:05');
INSERT INTO `users` (`id`,`username`,`password`,`role`,`created_at`,`updated_at`) VALUES ('8','operator_a3','$2y$10$9Z7LujNMtECLb9Yct0ELeOiY8mDWkRU8PJrjFYWqIyZkS/CnkNlee','operator','2026-06-29 21:11:05','2026-06-29 21:11:05');
INSERT INTO `users` (`id`,`username`,`password`,`role`,`created_at`,`updated_at`) VALUES ('9','operator_laser1','$2y$10$zsGA31S4zS7.MlwQbrIDRuE3fIv/k5PK01BH/C0dyra7urdDtZct.','operator','2026-06-29 21:11:05','2026-06-29 21:11:05');
INSERT INTO `users` (`id`,`username`,`password`,`role`,`created_at`,`updated_at`) VALUES ('10','operator_laser2','$2y$10$5lahJp1lXflP9g8HvKHBnOdc1Qm2P7gauhA88sx7SMiyTDAjRNOZW','operator','2026-06-29 21:11:05','2026-06-29 21:11:05');
INSERT INTO `users` (`id`,`username`,`password`,`role`,`created_at`,`updated_at`) VALUES ('11','finishing','$2y$10$eO6gvHZWniOaXSz1LaH5reXLJqWMGEa2nJYgH/cbb/Gha8E3YrjAq','finishing','2026-06-29 21:11:05','2026-06-29 21:11:05');
INSERT INTO `users` (`id`,`username`,`password`,`role`,`created_at`,`updated_at`) VALUES ('12','owner','$2y$10$ecE/sbgrdunTy56a6wVSYOdHQageC6kwrQh4NXwyf3jLcdgLQDbMu','owner','2026-06-29 21:11:06','2026-06-29 21:11:06');

SET FOREIGN_KEY_CHECKS=1;
