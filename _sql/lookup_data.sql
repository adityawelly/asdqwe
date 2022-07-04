-- Reason Of Hiring PTK
DELETE FROM `lookups` WHERE `category` = 'PTKROH';
INSERT INTO `lookups` (
  `category`,
  `lookup_id`,
  `lookup_value`,
  `lookup_desc`
)
VALUES
  ('PTKROH', 'PTKROH_1', 'NewPosition', 'Posisi Baru'),
  ('PTKROH', 'PTKROH_2', 'ContractExt', 'Perpanjangan Kontrak'),
  ('PTKROH', 'PTKROH_3', 'ReplcMut', 'Menggantikan Karyawan Mutasi'),
  ('PTKROH', 'PTKROH_4', 'ReplcRsgn', 'Menggantikan Karyawan Resign'),
  ('PTKROH', 'PTKROH_5', 'ReplcTemp', 'Penggantian Karyawan Sementara'),
  ('PTKROH', 'PTKROH_6', 'ReplcDead', 'Menggantikan Karyawan Meninggal Dunia');

-- Employee Status PTK
DELETE FROM `lookups` WHERE `category` = 'PTKES';
INSERT INTO `lookups` (
  `category`,
  `lookup_id`,
  `lookup_value`,
  `lookup_desc`
)
VALUES
  ('PTKES', 'PTKES_1', 'Casual', 'Harian Lepas (max 3 bulan)'),
  ('PTKES', 'PTKES_2', 'Contract', 'Kontrak'),
  ('PTKES', 'PTKES_3', 'Outsource', 'Outsource'),
  ('PTKES', 'PTKES_4', 'Probation', 'Probation'),
  ('PTKES', 'PTKES_5', 'Borongan', 'Borongan (max 3 bulan)');

-- Working Time PTK
INSERT INTO `lookups` (
  `category`,
  `lookup_id`,
  `lookup_value`,
  `lookup_desc`
)
VALUES
  ('PTWT', 'PTWT_1', 'SHIFT', 'Shift'),
  ('PTWT', 'PTWT_2', 'NONSHIFT', 'Non Shift');

-- Facilities PTK
INSERT INTO `lookups` (
  `category`,
  `lookup_id`,
  `lookup_value`,
  `lookup_desc`
)
VALUES
  ('PTFAC', null, 'Mobil', 'Mobil'),
  ('PTFAC', null, 'Meja', 'Meja'),
  ('PTFAC', null, 'Kursi', 'Kursi'),
  ('PTFAC', null, 'ID Card', 'ID Card'),
  ('PTFAC', null, 'Laptop', 'Laptop'),
  ('PTFAC', null, 'PC', 'PC'),
  ('PTFAC', null, 'Tunjangan Komunikasi', 'Tunjangan Komunikasi'),
  ('PTFAC', null, 'Email', 'Email'),
  ('PTFAC', null, 'Seragam', 'Seragam'),
  ('PTFAC', null, 'Masker (Kain Putih)', 'Masker (Kain Putih)'),
  ('PTFAC', null, 'Hairnet (Kain Biru)', 'Hairnet (Kain Biru)'),
  ('PTFAC', null, 'Sepatu Boot', 'Sepatu Boot'),
  ('PTFAC', null, 'Apron', 'Apron'),
  ('PTFAC', null, 'Masker (Hijau)', 'Masker (Hijau)'),
  ('PTFAC', null, 'Hairnet (Hijau)', 'Hairnet (Hijau)'),
  ('PTFAC', null, 'Baju Astronot', 'Baju Astronot');