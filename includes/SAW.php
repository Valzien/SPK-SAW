<?php
// includes/SAW.php
require_once __DIR__ . '/../config/database.php';

class SAW {
    private PDO $db;

    public function __construct() {
        $this->db = getDB();
    }

    // ── KRITERIA ────────────────────────────────────────────────
    public function getAllKriteria(): array {
        return $this->db->query("SELECT * FROM kriteria ORDER BY kode")->fetchAll();
    }

    public function getKriteriaById(int $id): array|false {
        return $this->db->prepare("SELECT * FROM kriteria WHERE id=?")->execute([$id])
            ? $this->db->query("SELECT * FROM kriteria WHERE id=$id")->fetch() : false;
    }

    public function tambahKriteria(string $kode, string $nama, string $tipe, float $bobot, string $satuan): void {
        $this->db->prepare("INSERT INTO kriteria (kode,nama,tipe,bobot,satuan) VALUES (?,?,?,?,?)")
            ->execute([$kode,$nama,$tipe,$bobot,$satuan]);
    }

    public function updateKriteria(int $id, string $nama, string $tipe, float $bobot, string $satuan): void {
        $this->db->prepare("UPDATE kriteria SET nama=?,tipe=?,bobot=?,satuan=? WHERE id=?")
            ->execute([$nama,$tipe,$bobot,$satuan,$id]);
    }

    public function hapusKriteria(int $id): void {
        $this->db->prepare("DELETE FROM kriteria WHERE id=?")->execute([$id]);
    }

    public function getTotalBobot(): float {
        return (float)($this->db->query("SELECT SUM(bobot) AS t FROM kriteria")->fetch()['t'] ?? 0);
    }

    // ── CRISP ───────────────────────────────────────────────────
    public function getCrispByKriteria(int $kriId): array {
        $stmt = $this->db->prepare("SELECT * FROM crisp WHERE kriteria_id=? ORDER BY skor");
        $stmt->execute([$kriId]);
        return $stmt->fetchAll();
    }

    public function getAllCrisp(): array {
        return $this->db->query("
            SELECT c.*, k.nama AS kri_nama, k.kode AS kri_kode, k.satuan
            FROM crisp c JOIN kriteria k ON k.id=c.kriteria_id
            ORDER BY k.kode, c.skor
        ")->fetchAll();
    }

    public function tambahCrisp(int $kriId, string $namaRange, float $min, float $max, int $skor): void {
        $this->db->prepare("INSERT INTO crisp (kriteria_id,nama_range,nilai_min,nilai_max,skor) VALUES (?,?,?,?,?)")
            ->execute([$kriId,$namaRange,$min,$max,$skor]);
    }

    public function updateCrisp(int $id, string $namaRange, float $min, float $max, int $skor): void {
        $this->db->prepare("UPDATE crisp SET nama_range=?,nilai_min=?,nilai_max=?,skor=? WHERE id=?")
            ->execute([$namaRange,$min,$max,$skor,$id]);
    }

    public function hapusCrisp(int $id): void {
        $this->db->prepare("DELETE FROM crisp WHERE id=?")->execute([$id]);
    }

    // ── ALTERNATIF ──────────────────────────────────────────────
    public function getAllAlternatif(): array {
        return $this->db->query("SELECT * FROM alternatif ORDER BY kode")->fetchAll();
    }

    public function tambahAlternatif(string $kode, string $nama): void {
        $this->db->prepare("INSERT INTO alternatif (kode,nama) VALUES (?,?)")->execute([$kode,$nama]);
    }

    public function updateAlternatif(int $id, string $kode, string $nama): void {
        $this->db->prepare("UPDATE alternatif SET kode=?,nama=? WHERE id=?")->execute([$kode,$nama,$id]);
    }

    public function hapusAlternatif(int $id): void {
        $this->db->prepare("DELETE FROM alternatif WHERE id=?")->execute([$id]);
    }

    // ── NILAI ───────────────────────────────────────────────────
    public function getNilaiMatrix(): array {
        $rows = $this->db->query("
            SELECT a.id AS alt_id, a.kode AS alt_kode, a.nama AS alt_nama,
                   k.id AS kri_id, k.kode AS kri_kode, k.nama AS kri_nama,
                   k.tipe, k.bobot, k.satuan, n.nilai
            FROM alternatif a
            CROSS JOIN kriteria k
            LEFT JOIN nilai n ON n.alternatif_id=a.id AND n.kriteria_id=k.id
            ORDER BY a.kode, k.kode
        ")->fetchAll();

        $matrix = [];
        foreach ($rows as $r) {
            if (!isset($matrix[$r['alt_id']])) {
                $matrix[$r['alt_id']] = ['id'=>$r['alt_id'],'kode'=>$r['alt_kode'],'nama'=>$r['alt_nama'],'nilai'=>[]];
            }
            $matrix[$r['alt_id']]['nilai'][$r['kri_id']] = [
                'kri_kode'=>$r['kri_kode'],'kri_nama'=>$r['kri_nama'],
                'tipe'=>$r['tipe'],'bobot'=>(float)$r['bobot'],
                'satuan'=>$r['satuan'],'nilai'=>$r['nilai']!==null?(float)$r['nilai']:0,
            ];
        }
        return array_values($matrix);
    }

    public function simpanNilai(int $altId, int $kriId, float $nilai): void {
        $this->db->prepare("
            INSERT INTO nilai (alternatif_id,kriteria_id,nilai) VALUES (?,?,?)
            ON DUPLICATE KEY UPDATE nilai=VALUES(nilai)
        ")->execute([$altId,$kriId,$nilai]);
    }

    // ── PERHITUNGAN SAW ─────────────────────────────────────────
    public function hitung(): array {
        $kriteria = $this->getAllKriteria();
        $matrix   = $this->getNilaiMatrix();

        if (empty($kriteria) || empty($matrix)) {
            return [];
        }

        // 1. Cari nilai Max & Min tiap kriteria
        $maxMin = [];
        foreach ($kriteria as $k) {
            $vals = array_map(
                fn($a) => $a['nilai'][$k['id']]['nilai'] ?? 0,
                $matrix
            );

            $maxMin[$k['id']] = [
                'max' => max($vals),
                'min' => min($vals)
            ];
        }

        // 2. Normalisasi
        $normMatrix = [];

        foreach ($matrix as $alt) {
            foreach ($kriteria as $k) {

                $xij = $alt['nilai'][$k['id']]['nilai'] ?? 0;
                $max = $maxMin[$k['id']]['max'];
                $min = $maxMin[$k['id']]['min'];

                if ($k['tipe'] === 'benefit') {
                    $normMatrix[$alt['id']][$k['id']] = ($max > 0)
                        ? $xij / $max
                        : 0;
                } else {
                    $normMatrix[$alt['id']][$k['id']] = ($xij > 0)
                        ? $min / $xij
                        : 0;
                }
            }
        }

        // 3. Hitung nilai preferensi (Vi)
        $hasil = [];

        foreach ($matrix as $alt) {

            $vi = 0;

            foreach ($kriteria as $k) {
                $vi += $k['bobot'] * $normMatrix[$alt['id']][$k['id']];
            }

            $hasil[] = [
                'alt_id'   => $alt['id'],
                'alt_kode' => $alt['kode'],
                'alt_nama' => $alt['nama'],
                'vi'       => $vi,
                'norm'     => $normMatrix[$alt['id']],
                'raw'      => $alt['nilai']
            ];
        }

        // 4. Ranking
        usort($hasil, fn($a, $b) => $b['vi'] <=> $a['vi']);

        // TANPA REFERENCE (&)
        foreach ($hasil as $i => $item) {
            $hasil[$i]['peringkat'] = $i + 1;
        }

        // 5. Simpan ke database
        $this->db->beginTransaction();

        try {

            $this->db->exec("DELETE FROM hasil");

            $stmt = $this->db->prepare("
                INSERT INTO hasil (alternatif_id, nilai_vi, peringkat)
                VALUES (?, ?, ?)
            ");

            foreach ($hasil as $h) {
                $stmt->execute([
                    $h['alt_id'],
                    $h['vi'],
                    $h['peringkat']
                ]);
            }

            $this->db->commit();

        } catch (Exception $e) {

            $this->db->rollBack();
            throw $e;

        }

        return [
            'hasil'     => $hasil,
            'kriteria'  => $kriteria,
            'maxMin'    => $maxMin,
            'matrix'    => $matrix,
            'norm'      => $normMatrix
        ];
    }

    public function getHasilTerakhir(): array {
        return $this->db->query("
            SELECT h.*,a.kode,a.nama FROM hasil h
            JOIN alternatif a ON a.id=h.alternatif_id
            ORDER BY h.peringkat
        ")->fetchAll();
    }
}

