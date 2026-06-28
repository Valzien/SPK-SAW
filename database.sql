CREATE DATABASE IF NOT EXISTS spk_saw CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE spk_saw;

DROP TABLE IF EXISTS hasil;
DROP TABLE IF EXISTS nilai;
DROP TABLE IF EXISTS crisp;
DROP TABLE IF EXISTS alternatif;
DROP TABLE IF EXISTS kriteria;
DROP TABLE IF EXISTS users;

-- Users (login)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Kriteria
CREATE TABLE kriteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(100) NOT NULL,
    tipe ENUM('benefit','cost') NOT NULL,
    bobot DECIMAL(5,4) NOT NULL,
    satuan VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Crisp (himpunan nilai)
CREATE TABLE crisp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kriteria_id INT NOT NULL,
    nama_range VARCHAR(200) NOT NULL,
    nilai_min DECIMAL(15,4) NOT NULL,
    nilai_max DECIMAL(15,4) NOT NULL,
    skor INT NOT NULL,
    FOREIGN KEY (kriteria_id) REFERENCES kriteria(id) ON DELETE CASCADE
);

-- Alternatif
CREATE TABLE alternatif (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kode VARCHAR(10) NOT NULL UNIQUE,
    nama VARCHAR(150) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Nilai (skor crisp per alternatif per kriteria)
CREATE TABLE nilai (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alternatif_id INT NOT NULL,
    kriteria_id INT NOT NULL,
    nilai DECIMAL(15,4) NOT NULL,
    UNIQUE KEY uk_nilai (alternatif_id, kriteria_id),
    FOREIGN KEY (alternatif_id) REFERENCES alternatif(id) ON DELETE CASCADE,
    FOREIGN KEY (kriteria_id) REFERENCES kriteria(id) ON DELETE CASCADE
);

-- Hasil perhitungan
CREATE TABLE hasil (
    id INT AUTO_INCREMENT PRIMARY KEY,
    alternatif_id INT NOT NULL,
    nilai_vi DECIMAL(15,10) NOT NULL,
    peringkat INT NOT NULL,
    dihitung_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (alternatif_id) REFERENCES alternatif(id) ON DELETE CASCADE
);

-- ============================================================
-- DATA AWAL
-- ============================================================

-- Admin default (password: admin123)
INSERT INTO users (username, password, nama_lengkap) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator');

-- Kriteria (bobot masing-masing 0.25, total = 1.00)
INSERT INTO kriteria (kode, nama, tipe, bobot, satuan) VALUES
('C1', 'Kamera',  'benefit', 0.25, 'MP'),
('C2', 'Layar',   'benefit', 0.25, 'inci'),
('C3', 'Baterai', 'benefit', 0.25, 'mAh'),
('C4', 'Harga',   'cost',    0.25, 'Rp');

-- Crisp C1: Kamera (benefit)
INSERT INTO crisp (kriteria_id, nama_range, nilai_min, nilai_max, skor) VALUES
(1, '<= 5 mp',     0,    5,    1),
(1, '5 - 10 mp',   5,    10,   2),
(1, '10 - 12 mp',  10,   12,   3),
(1, '12 - 13 mp',  12,   13,   4),
(1, '>= 13 mp',    13,   9999, 5);

-- Crisp C2: Layar (benefit)
INSERT INTO crisp (kriteria_id, nama_range, nilai_min, nilai_max, skor) VALUES
(2, '<= 6.0 inch',     0,   6.0,  1),
(2, '6.0 - 6.2 inch',  6.0, 6.2,  2),
(2, '6.2 - 6.3 inch',  6.2, 6.3,  3),
(2, '6.3 - 6.5 inch',  6.3, 6.5,  4),
(2, '>= 6.5 inch',     6.5, 9999, 5);

-- Crisp C3: Baterai (benefit)
INSERT INTO crisp (kriteria_id, nama_range, nilai_min, nilai_max, skor) VALUES
(3, '<= 3.000 mAh',      0,    3000, 1),
(3, '3.000 - 3.500 mAh', 3000, 3500, 2),
(3, '3.500 - 4.000 mAh', 3500, 4000, 3),
(3, '4.000 - 4.500 mAh', 4000, 4500, 4),
(3, '>= 4.500 mAh',      4500, 9999, 5);

-- Crisp C4: Harga (cost)
INSERT INTO crisp (kriteria_id, nama_range, nilai_min, nilai_max, skor) VALUES
(4, '>= 2.500.000',           2500000, 9999999, 1),
(4, '2.000.000 - 2.500.000',  2000000, 2500000, 2),
(4, '1.500.000 - 2.000.000',  1500000, 2000000, 3),
(4, '1.000.000 - 1.500.000',  1000000, 1500000, 4),
(4, '<= 1.000.000',           0,       1000000, 5);

-- Alternatif
INSERT INTO alternatif (kode, nama) VALUES
('A1', 'Realme C2'),
('A2', 'Realme 5'),
('A3', 'Samsung Galaxy A20s'),
('A4', 'Infinix Smart 5'),
('A5', 'Xiaomi Mi A2');

-- Nilai skor crisp (sesuai Excel baru)
-- A1 Realme C2:         Kamera=5, Layar=2, Baterai=4, Harga=5
-- A2 Realme 5:          Kamera=4, Layar=4, Baterai=5, Harga=3
-- A3 Samsung Galaxy A20s: Kamera=4, Layar=4, Baterai=3, Harga=2
-- A4 Infinix Smart 5:   Kamera=4, Layar=5, Baterai=5, Harga=4
-- A5 Xiaomi Mi A2:      Kamera=3, Layar=3, Baterai=3, Harga=3
INSERT INTO nilai (alternatif_id, kriteria_id, nilai) VALUES
(1,1,5),(1,2,2),(1,3,4),(1,4,5),
(2,1,4),(2,2,4),(2,3,5),(2,4,3),
(3,1,4),(3,2,4),(3,3,3),(3,4,2),
(4,1,4),(4,2,5),(4,3,5),(4,4,4),
(5,1,3),(5,2,3),(5,3,3),(5,4,3);
