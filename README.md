# Sistem Pendukung Keputusan Pemilihan Smartphone untuk Pembelajaran Jarak Jauh
### Menggunakan Metode Simple Additive Weighting (SAW)

---

## Daftar Isi

1. [Latar Belakang](#1-latar-belakang)
2. [Tujuan Sistem](#2-tujuan-sistem)
3. [Metode SAW](#3-metode-saw)
4. [Penentuan Kriteria](#4-penentuan-kriteria)
5. [Alasan Pemilihan Tipe Benefit dan Cost](#5-alasan-pemilihan-tipe-benefit-dan-cost)
6. [Penentuan Bobot Kriteria](#6-penentuan-bobot-kriteria)
7. [Data Alternatif Smartphone](#7-data-alternatif-smartphone)
8. [Crisp / Himpunan Nilai](#8-crisp--himpunan-nilai)
9. [Proses Perhitungan SAW](#9-proses-perhitungan-saw)
10. [Hasil dan Peringkat](#10-hasil-dan-peringkat)
11. [Kesimpulan](#11-kesimpulan)
12. [Teknologi yang Digunakan](#12-teknologi-yang-digunakan)
13. [Cara Instalasi](#13-cara-instalasi)

---

## 1. Latar Belakang

Perkembangan teknologi smartphone yang semakin pesat menyebabkan banyaknya pilihan smartphone di pasaran dengan berbagai macam spesifikasi dan harga. Kondisi ini membuat konsumen, khususnya pelajar dan mahasiswa, kesulitan dalam memilih smartphone yang sesuai dengan kebutuhan pembelajaran jarak jauh (PJJ).

Pembelajaran jarak jauh menuntut perangkat smartphone dengan kemampuan yang memadai, seperti kualitas kamera untuk mengikuti video conference, layar yang nyaman untuk membaca materi, kapasitas baterai yang tahan lama agar tidak sering mengisi daya, serta harga yang terjangkau sesuai dengan kemampuan ekonomi pelajar.

Oleh karena itu, dibangunlah Sistem Pendukung Keputusan (SPK) yang menerapkan metode **Simple Additive Weighting (SAW)** untuk memberikan rekomendasi smartphone terbaik berdasarkan kriteria-kriteria yang telah ditentukan secara objektif.

---

## 2. Tujuan Sistem

- Merancang dan membangun sistem pendukung keputusan berbasis web untuk pemilihan smartphone.
- Menerapkan metode SAW dalam proses pemilihan smartphone untuk pembelajaran jarak jauh.
- Memberikan rekomendasi smartphone terbaik secara objektif berdasarkan kriteria yang telah ditentukan.
- Memudahkan pengguna dalam membandingkan beberapa alternatif smartphone tanpa perhitungan manual.

---

## 3. Metode SAW

### Pengertian

Metode **Simple Additive Weighting (SAW)** sering juga dikenal sebagai metode penjumlahan terbobot. Konsep dasar metode SAW adalah mencari penjumlahan terbobot dari rating kinerja pada setiap alternatif pada semua atribut (Fishburn, 1967).

SAW dianggap sebagai metode yang paling mudah dan intuitif untuk menangani masalah **Multiple Criteria Decision-Making (MCDM)**, karena fungsi linear additive dapat mewakili preferensi pembuat keputusan.

### Rumus Normalisasi

Normalisasi matriks keputusan dilakukan dengan rumus berikut:

**Atribut Benefit (semakin besar semakin baik):**
```
rij = Xij / Max(Xij)
```

**Atribut Cost (semakin kecil semakin baik):**
```
rij = Min(Xij) / Xij
```

### Rumus Nilai Preferensi

```
Vi = Σ (wj × rij)
```

Keterangan:
- `Vi`  = Nilai preferensi / skor akhir alternatif ke-i
- `wj`  = Bobot kriteria ke-j
- `rij` = Nilai normalisasi alternatif ke-i pada kriteria ke-j

Nilai `Vi` yang lebih besar menunjukkan bahwa alternatif tersebut lebih direkomendasikan.

### Langkah-Langkah Penyelesaian

1. **Menentukan kriteria** dan bobot kepentingan tiap kriteria (Wi), dengan syarat total bobot = 1 (Σwi = 1).
2. **Menentukan rating kecocokan** (skor crisp) setiap alternatif pada setiap kriteria (X).
3. **Normalisasi matriks keputusan** X menggunakan rumus benefit atau cost menjadi matriks ternormalisasi R.
4. **Menghitung nilai preferensi (Vi)** dengan menjumlahkan hasil perkalian bobot dan nilai normalisasi. Alternatif dengan Vi terbesar adalah yang terbaik.

---

## 4. Penentuan Kriteria

Kriteria dipilih berdasarkan kebutuhan utama pelajar/mahasiswa dalam menjalankan aktivitas pembelajaran jarak jauh. Terdapat 4 kriteria yang digunakan:

| Kode | Nama Kriteria | Tipe    | Bobot | Satuan |
|------|--------------|---------|-------|--------|
| C1   | Kamera       | Benefit | 0.25  | MP     |
| C2   | Layar        | Benefit | 0.25  | inci   |
| C3   | Baterai      | Benefit | 0.25  | mAh    |
| C4   | Harga        | Cost    | 0.25  | Rp     |

---

## 5. Alasan Pemilihan Tipe Benefit dan Cost

### C1 — Kamera (Benefit)

**Alasan tipe Benefit:**
Kamera pada smartphone sangat penting untuk kebutuhan pembelajaran jarak jauh, terutama untuk mengikuti video conference, mengirimkan foto tugas, dan merekam presentasi. Semakin tinggi resolusi kamera (dalam satuan MP/megapiksel), semakin baik kualitas gambar dan video yang dihasilkan. Oleh karena itu, **semakin besar nilai MP maka semakin baik**, sehingga dikategorikan sebagai atribut **Benefit**.

### C2 — Layar (Benefit)

**Alasan tipe Benefit:**
Ukuran layar memengaruhi kenyamanan pengguna saat membaca materi, menonton video pembelajaran, dan mengikuti kelas online. Layar yang lebih lebar memberikan tampilan yang lebih nyaman dan tidak mudah membuat mata lelah. Oleh karena itu, **semakin besar ukuran layar (inci) maka semakin baik**, sehingga dikategorikan sebagai atribut **Benefit**.

### C3 — Baterai (Benefit)

**Alasan tipe Benefit:**
Kapasitas baterai sangat krusial untuk kebutuhan PJJ karena aktivitas seperti video call, streaming materi, dan mengerjakan tugas secara online membutuhkan daya yang besar. Baterai dengan kapasitas lebih besar (mAh) memungkinkan smartphone digunakan lebih lama tanpa perlu sering mengisi daya, yang sangat membantu ketika belajar dari jarak jauh. Oleh karena itu, **semakin besar kapasitas baterai maka semakin baik**, sehingga dikategorikan sebagai atribut **Benefit**.

### C4 — Harga (Cost)

**Alasan tipe Cost:**
Harga merupakan faktor pembatas yang sangat penting bagi pelajar dan mahasiswa, mengingat daya beli yang umumnya terbatas. Tujuan pemilihan smartphone adalah mendapatkan spesifikasi terbaik dengan harga yang paling terjangkau. Oleh karena itu, **semakin kecil/murah harga maka semakin baik**, sehingga dikategorikan sebagai atribut **Cost**.

---

## 6. Penentuan Bobot Kriteria

Setiap kriteria diberikan bobot yang sama yaitu **0.25 (25%)** untuk masing-masing kriteria.

| Kriteria | Bobot | Persentase |
|----------|-------|-----------|
| Kamera   | 0.25  | 25%        |
| Layar    | 0.25  | 25%        |
| Baterai  | 0.25  | 25%        |
| Harga    | 0.25  | 25%        |
| **Total**| **1.00** | **100%** |

**Alasan pemberian bobot sama (equal weight):**

Keempat kriteria tersebut dianggap **sama pentingnya** dalam konteks pemilihan smartphone untuk pembelajaran jarak jauh. Tidak ada satu kriteria yang secara mutlak lebih penting dari yang lain karena:

- Kamera penting untuk komunikasi visual dalam kelas online.
- Layar penting untuk kenyamanan membaca dan belajar.
- Baterai penting agar aktivitas belajar tidak terganggu karena daya habis.
- Harga penting karena keterbatasan ekonomi target pengguna (pelajar/mahasiswa).

Pendekatan ini mengikuti prinsip objektivitas dalam pengambilan keputusan, di mana ketika tidak ada dasar kuat untuk memprioritaskan satu kriteria di atas yang lain, pemberian bobot yang merata adalah pilihan yang paling adil dan tidak bias (equal weight assumption).

Total bobot = 0.25 + 0.25 + 0.25 + 0.25 = **1.00** ✓ (memenuhi syarat Σwi = 1)

---

## 7. Data Alternatif Smartphone

Terdapat 5 alternatif smartphone yang dievaluasi. Alternatif dipilih berdasarkan smartphone kelas menengah bawah yang umum digunakan oleh pelajar di Indonesia dan datanya diambil dari spesifikasi resmi masing-masing produk.

| Kode | Nama Smartphone     | Kamera      | Layar        | Baterai       | Harga                  |
|------|---------------------|-------------|--------------|---------------|------------------------|
| A1   | Realme C2           | >= 13 MP    | 6.0–6.2 inci | 4.000–4.500 mAh | <= Rp 1.000.000        |
| A2   | Realme 5            | 12–13 MP    | 6.3–6.5 inci | >= 4.500 mAh    | Rp 1.500.000–2.000.000 |
| A3   | Samsung Galaxy A20s | 12–13 MP    | 6.3–6.5 inci | 3.500–4.000 mAh | Rp 2.000.000–2.500.000 |
| A4   | Infinix Smart 5     | 12–13 MP    | >= 6.5 inci  | >= 4.500 mAh    | Rp 1.000.000–1.500.000 |
| A5   | Xiaomi Mi A2        | 10–12 MP    | 6.2–6.3 inci | 3.500–4.000 mAh | Rp 1.500.000–2.000.000 |

Data di atas adalah data hasil analisis spesifikasi (sebelum dikonversi ke skor crisp). Nilai yang dimasukkan ke sistem adalah **skor crisp** berdasarkan himpunan yang telah ditentukan.

---

## 8. Crisp / Himpunan Nilai

Crisp adalah proses konversi nilai aktual spesifikasi smartphone menjadi **skor numerik (1–5)** agar dapat diperbandingkan secara kuantitatif dalam matriks keputusan SAW.

### Mengapa Diperlukan Crisp?

Nilai spesifikasi asli memiliki satuan yang berbeda-beda (MP, inci, mAh, Rupiah) sehingga tidak dapat langsung dibandingkan. Proses crisp menyamakan skala semua nilai ke rentang 1–5 berdasarkan kategori yang telah ditentukan, sebelum dilakukan normalisasi SAW.

---

### Crisp C1 — Kamera (Benefit)

| Skor | Rentang Nilai       | Keterangan                        |
|------|--------------------|------------------------------------|
| 1    | <= 5 MP            | Kamera sangat rendah, tidak layak PJJ |
| 2    | 5 – 10 MP          | Kamera rendah, masih kurang memadai   |
| 3    | 10 – 12 MP         | Kamera cukup, memadai untuk PJJ       |
| 4    | 12 – 13 MP         | Kamera baik, cukup untuk video dan foto |
| 5    | >= 13 MP           | Kamera sangat baik, ideal untuk PJJ   |

**Alasan pembagian range:**
Resolusi kamera smartphone kelas menengah pada umumnya berada di kisaran 8–48 MP. Untuk kebutuhan PJJ seperti video conference dan foto dokumen, kamera minimal 12 MP sudah dianggap baik. Range dibagi menjadi 5 level secara proporsional dari yang terendah hingga tertinggi.

**Skor Alternatif pada C1:**

| Alternatif          | Spesifikasi   | Skor |
|---------------------|--------------|------|
| Realme C2           | >= 13 MP     | **5** |
| Realme 5            | 12–13 MP     | **4** |
| Samsung Galaxy A20s | 12–13 MP     | **4** |
| Infinix Smart 5     | 12–13 MP     | **4** |
| Xiaomi Mi A2        | 10–12 MP     | **3** |

---

### Crisp C2 — Layar (Benefit)

| Skor | Rentang Nilai       | Keterangan                              |
|------|--------------------|-----------------------------------------|
| 1    | <= 6.0 inci        | Layar kecil, kurang nyaman untuk belajar |
| 2    | 6.0 – 6.2 inci     | Layar sedang kecil, cukup terbatas       |
| 3    | 6.2 – 6.3 inci     | Layar sedang, cukup nyaman               |
| 4    | 6.3 – 6.5 inci     | Layar lebar, nyaman untuk belajar        |
| 5    | >= 6.5 inci        | Layar sangat lebar, sangat nyaman        |

**Alasan pembagian range:**
Smartphone kelas menengah umumnya memiliki layar 5.5–7.0 inci. Untuk PJJ, layar minimal 6.3 inci dianggap sudah nyaman untuk membaca materi dan mengikuti kelas online. Range dibagi berdasarkan standar ukuran layar yang umum di pasaran.

**Skor Alternatif pada C2:**

| Alternatif          | Spesifikasi   | Skor |
|---------------------|--------------|------|
| Realme C2           | 6.0–6.2 inci | **2** |
| Realme 5            | 6.3–6.5 inci | **4** |
| Samsung Galaxy A20s | 6.3–6.5 inci | **4** |
| Infinix Smart 5     | >= 6.5 inci  | **5** |
| Xiaomi Mi A2        | 6.2–6.3 inci | **3** |

---

### Crisp C3 — Baterai (Benefit)

| Skor | Rentang Nilai          | Keterangan                                      |
|------|------------------------|------------------------------------------------|
| 1    | <= 3.000 mAh           | Baterai sangat kecil, tidak cocok untuk PJJ    |
| 2    | 3.000 – 3.500 mAh      | Baterai kecil, tahan 4–6 jam aktivitas PJJ     |
| 3    | 3.500 – 4.000 mAh      | Baterai cukup, tahan sekitar 6–8 jam           |
| 4    | 4.000 – 4.500 mAh      | Baterai baik, tahan 8–10 jam aktivitas PJJ     |
| 5    | >= 4.500 mAh           | Baterai besar, sangat ideal untuk PJJ seharian |

**Alasan pembagian range:**
Aktivitas PJJ seperti video call, streaming, dan mengerjakan tugas online membutuhkan daya baterai yang besar. Baterai >= 4.500 mAh mampu menopang kegiatan belajar seharian tanpa perlu mengisi ulang. Range dibagi berdasarkan kapasitas baterai umum smartphone kelas menengah (3.000–6.000 mAh).

**Skor Alternatif pada C3:**

| Alternatif          | Spesifikasi       | Skor |
|---------------------|------------------|------|
| Realme C2           | 4.000–4.500 mAh  | **4** |
| Realme 5            | >= 4.500 mAh     | **5** |
| Samsung Galaxy A20s | 3.500–4.000 mAh  | **3** |
| Infinix Smart 5     | >= 4.500 mAh     | **5** |
| Xiaomi Mi A2        | 3.500–4.000 mAh  | **3** |

---

### Crisp C4 — Harga (Cost)

| Skor | Rentang Nilai               | Keterangan                                         |
|------|-----------------------------|---------------------------------------------------|
| 1    | >= Rp 2.500.000             | Harga sangat mahal, tidak terjangkau pelajar      |
| 2    | Rp 2.000.000 – 2.500.000    | Harga mahal, cukup berat untuk pelajar            |
| 3    | Rp 1.500.000 – 2.000.000    | Harga sedang, masih terjangkau sebagian pelajar   |
| 4    | Rp 1.000.000 – 1.500.000    | Harga murah, terjangkau bagi pelajar              |
| 5    | <= Rp 1.000.000             | Harga sangat murah, sangat terjangkau             |

**Alasan pembagian range:**
Karena Harga adalah atribut **Cost** (semakin murah semakin baik), maka skor tertinggi (5) diberikan untuk harga terendah. Range dibagi berdasarkan segmen harga smartphone kelas entry-to-mid yang umum beredar dan terjangkau oleh target pengguna (pelajar/mahasiswa Indonesia).

**Skor Alternatif pada C4:**

| Alternatif          | Harga                   | Skor |
|---------------------|------------------------|------|
| Realme C2           | <= Rp 1.000.000        | **5** |
| Realme 5            | Rp 1.500.000–2.000.000 | **3** |
| Samsung Galaxy A20s | Rp 2.000.000–2.500.000 | **2** |
| Infinix Smart 5     | Rp 1.000.000–1.500.000 | **4** |
| Xiaomi Mi A2        | Rp 1.500.000–2.000.000 | **3** |

---

## 9. Proses Perhitungan SAW

### Langkah 1 — Matriks Keputusan (X)

Matriks keputusan berisi skor crisp masing-masing alternatif pada setiap kriteria:

|        | C1 (Kamera) | C2 (Layar) | C3 (Baterai) | C4 (Harga) |
|--------|:-----------:|:----------:|:------------:|:----------:|
| **A1** | 5           | 2          | 4            | 5          |
| **A2** | 4           | 4          | 5            | 3          |
| **A3** | 4           | 4          | 3            | 2          |
| **A4** | 4           | 5          | 5            | 4          |
| **A5** | 3           | 3          | 3            | 3          |

### Langkah 2 — Nilai Maks dan Min per Kriteria

| Kriteria | Tipe    | Max | Min | Nilai yang Digunakan |
|----------|---------|-----|-----|----------------------|
| C1       | Benefit | 5   | 3   | Max = 5              |
| C2       | Benefit | 5   | 2   | Max = 5              |
| C3       | Benefit | 5   | 3   | Max = 5              |
| C4       | Cost    | 5   | 2   | Min = 2              |

### Langkah 3 — Normalisasi Matriks (rij)

**Rumus Benefit:** `rij = Xij / Max(Xij)`
**Rumus Cost:** `rij = Min(Xij) / Xij`

**A1 – Realme C2:**
- C1: 5/5 = **1.00000**
- C2: 2/5 = **0.40000**
- C3: 4/5 = **0.80000**
- C4: 2/5 = **0.40000** ← (Min=2, X=5, maka 2/5)

**A2 – Realme 5:**
- C1: 4/5 = **0.80000**
- C2: 4/5 = **0.80000**
- C3: 5/5 = **1.00000**
- C4: 2/3 = **0.66667** ← (Min=2, X=3, maka 2/3)

**A3 – Samsung Galaxy A20s:**
- C1: 4/5 = **0.80000**
- C2: 4/5 = **0.80000**
- C3: 3/5 = **0.60000**
- C4: 2/2 = **1.00000** ← (Min=2, X=2, maka 2/2, nilai terendah = terbaik untuk cost)

**A4 – Infinix Smart 5:**
- C1: 4/5 = **0.80000**
- C2: 5/5 = **1.00000**
- C3: 5/5 = **1.00000**
- C4: 2/4 = **0.50000** ← (Min=2, X=4, maka 2/4)

**A5 – Xiaomi Mi A2:**
- C1: 3/5 = **0.60000**
- C2: 3/5 = **0.60000**
- C3: 3/5 = **0.60000**
- C4: 2/3 = **0.66667** ← (Min=2, X=3, maka 2/3)

**Tabel Matriks Ternormalisasi (R):**

|        | r(C1)   | r(C2)   | r(C3)   | r(C4)   |
|--------|:-------:|:-------:|:-------:|:-------:|
| **A1** | 1.00000 | 0.40000 | 0.80000 | 0.40000 |
| **A2** | 0.80000 | 0.80000 | 1.00000 | 0.66667 |
| **A3** | 0.80000 | 0.80000 | 0.60000 | 1.00000 |
| **A4** | 0.80000 | 1.00000 | 1.00000 | 0.50000 |
| **A5** | 0.60000 | 0.60000 | 0.60000 | 0.66667 |

### Langkah 4 — Nilai Preferensi (Vi)

**Rumus:** `Vi = Σ (wj × rij)` dengan semua bobot wj = 0.25

**A1 – Realme C2:**
```
Vi = (0.25 × 1.00000) + (0.25 × 0.40000) + (0.25 × 0.80000) + (0.25 × 0.40000)
Vi = 0.25000 + 0.10000 + 0.20000 + 0.10000
Vi = 0.65000
```

**A2 – Realme 5:**
```
Vi = (0.25 × 0.80000) + (0.25 × 0.80000) + (0.25 × 1.00000) + (0.25 × 0.66667)
Vi = 0.20000 + 0.20000 + 0.25000 + 0.16667
Vi = 0.81667
```

**A3 – Samsung Galaxy A20s:**
```
Vi = (0.25 × 0.80000) + (0.25 × 0.80000) + (0.25 × 0.60000) + (0.25 × 1.00000)
Vi = 0.20000 + 0.20000 + 0.15000 + 0.25000
Vi = 0.80000
```

**A4 – Infinix Smart 5:**
```
Vi = (0.25 × 0.80000) + (0.25 × 1.00000) + (0.25 × 1.00000) + (0.25 × 0.50000)
Vi = 0.20000 + 0.25000 + 0.25000 + 0.12500
Vi = 0.82500
```

**A5 – Xiaomi Mi A2:**
```
Vi = (0.25 × 0.60000) + (0.25 × 0.60000) + (0.25 × 0.60000) + (0.25 × 0.66667)
Vi = 0.15000 + 0.15000 + 0.15000 + 0.16667
Vi = 0.61667
```

---

## 10. Hasil dan Peringkat

| Peringkat | Kode | Smartphone          | Nilai Vi   |
|:---------:|------|---------------------|:----------:|
| **1**     | A4   | Infinix Smart 5     | **0.82500** |
| **2**     | A2   | Realme 5            | **0.81667** |
| **3**     | A3   | Samsung Galaxy A20s | **0.80000** |
| **4**     | A1   | Realme C2           | **0.65000** |
| **5**     | A5   | Xiaomi Mi A2        | **0.61667** |

### Analisis Hasil

**Infinix Smart 5 (Rank 1 — Vi = 0.82500)**

Infinix Smart 5 meraih peringkat pertama karena memiliki kombinasi nilai yang paling seimbang di semua kriteria:
- Layar >= 6.5 inci (skor 5 — tertinggi): memberikan nilai normalisasi C2 = 1.00 yang mendongkrak total Vi secara signifikan.
- Baterai >= 4.500 mAh (skor 5): nilai normalisasi C3 = 1.00, sangat ideal untuk aktivitas PJJ seharian.
- Kamera 12–13 MP (skor 4): memadai untuk kebutuhan video conference.
- Harga Rp 1.000.000–1.500.000 (skor 4): harga yang sangat terjangkau untuk pelajar.

Meski tidak unggul mutlak di setiap kriteria, Infinix Smart 5 memiliki profil nilai yang paling konsisten tinggi di semua kriteria, menghasilkan total Vi tertinggi.

**Realme C2 (Rank 4 — Vi = 0.65000)**

Meski memiliki kamera terbaik (>= 13 MP, skor 5) dan harga termurah (<= Rp 1.000.000, skor 5), Realme C2 terjatuh di peringkat 4 karena:
- Layar 6.0–6.2 inci hanya mendapat skor 2 (terendah dari semua alternatif), menghasilkan r(C2) = 0.40.
- Kelemahan pada kriteria Layar sangat memengaruhi total Vi karena bobot setiap kriteria sama (0.25).

Ini membuktikan bahwa dalam metode SAW dengan bobot seimbang, alternatif dengan nilai sangat rendah di salah satu kriteria akan tertarik ke bawah meskipun unggul di kriteria lain.

**Xiaomi Mi A2 (Rank 5 — Vi = 0.61667)**

Xiaomi Mi A2 mendapat peringkat terendah karena memiliki nilai yang konsisten sedang (skor 3 di semua kriteria), tidak ada kriteria yang menonjol dibanding alternatif lain.

---

## 11. Kesimpulan

Berdasarkan hasil perhitungan menggunakan metode **Simple Additive Weighting (SAW)** dengan 4 kriteria (Kamera, Layar, Baterai, Harga) dan bobot masing-masing 0.25, diperoleh rekomendasi sebagai berikut:

> **Infinix Smart 5** merupakan smartphone terbaik untuk kebutuhan Pembelajaran Jarak Jauh dengan nilai preferensi Vi = **0.82500**, diikuti oleh Realme 5 (0.81667) dan Samsung Galaxy A20s (0.80000).

Sistem ini membuktikan bahwa metode SAW mampu memberikan solusi yang objektif dan terukur dalam pengambilan keputusan pemilihan smartphone, karena mempertimbangkan semua kriteria secara bersamaan dengan bobot yang telah ditentukan.

---

## 12. Teknologi yang Digunakan

| Komponen     | Teknologi                  |
|--------------|---------------------------|
| Backend      | PHP 8.x (Native/Vanilla)  |
| Database     | MySQL                     |
| Frontend     | Bootstrap 5.3             |
| Icon         | Bootstrap Icons 1.11      |
| Chart/Grafik | Chart.js 4.4              |
| Environment  | Laragon (Windows)         |
| DB Client    | phpMyAdmin                |

### Struktur Folder

```
spk_saw/
├── index.php               # Dashboard utama
├── database.sql            # Script SQL (import ke phpMyAdmin)
├── config/
│   └── database.php        # Konfigurasi koneksi database
├── includes/
│   ├── auth.php            # Middleware proteksi halaman (login)
│   ├── SAW.php             # Class utama logika perhitungan SAW
│   ├── header.php          # Template header + sidebar navigasi
│   └── footer.php          # Template footer + script JS
├── pages/
│   ├── login.php           # Halaman login
│   ├── logout.php          # Proses logout
│   ├── kriteria.php        # CRUD manajemen kriteria
│   ├── crisp.php           # CRUD himpunan nilai (crisp)
│   ├── alternatif.php      # CRUD manajemen alternatif smartphone
│   ├── nilai.php           # Input matriks nilai/skor per alternatif
│   ├── hitung.php          # Proses perhitungan SAW step-by-step
│   └── hasil.php           # Tampilan hasil peringkat & visualisasi
└── assets/
    ├── css/style.css       # Custom styling
    └── js/main.js          # JavaScript utilitas
```

---

## 13. Cara Instalasi

### Prasyarat
- Laragon (sudah terinstall PHP & MySQL)
- Browser modern (Chrome, Firefox, Edge)

### Langkah Instalasi

**1. Letakkan folder project**
```
Extract file ZIP ke:
C:\laragon\www\spk_saw\
```

**2. Import database**
- Buka browser, akses `http://localhost/phpmyadmin`
- Buat database baru dengan nama `spk_saw`
- Klik tab **Import** → pilih file `database.sql` → klik **Go**

**3. Konfigurasi koneksi (jika diperlukan)**

Edit file `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');          // Laragon default: kosong
define('DB_NAME', 'spk_saw');
define('DB_PORT', 3306);
```

**4. Jalankan aplikasi**
- Buka browser, akses `http://localhost/spk_saw`
- Login dengan kredensial default:
  - **Username:** `admin`
  - **Password:** `admin123`

**5. Jalankan perhitungan**
- Masuk ke menu **Proses SAW**
- Klik tombol **Jalankan Perhitungan**
- Lihat hasil di menu **Hasil & Ranking**

---

*Sistem Pendukung Keputusan Pemilihan Smartphone untuk Pembelajaran Jarak Jauh — Metode Simple Additive Weighting (SAW)*
