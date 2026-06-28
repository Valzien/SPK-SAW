<?php
require_once __DIR__ . '/../includes/auth.php';

require_once __DIR__ . '/../includes/SAW.php';

$pageTitle = 'Input Nilai';
$saw = new SAW();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        foreach ($_POST['nilai'] as $altId => $kriMap) {
            foreach ($kriMap as $kriId => $val) {
                $saw->simpanNilai((int)$altId, (int)$kriId, (float)str_replace(',','.',$val));
            }
        }
        $_SESSION['success'] = 'Nilai berhasil disimpan.';
    } catch (Exception $e) {
        $_SESSION['error'] = 'Gagal menyimpan: ' . $e->getMessage();
    }
    header('Location: nilai.php');
    exit;
}

$matrix   = $saw->getNilaiMatrix();
$kriteria = $saw->getAllKriteria();
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-600">Input Nilai / Matriks Keputusan</h5>
        <small class="text-muted">Isi nilai tiap alternatif untuk setiap kriteria</small>
    </div>
</div>

<?php if (empty($matrix) || empty($kriteria)): ?>
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bi bi-exclamation-circle display-4 text-warning d-block mb-3"></i>
        <p class="text-muted">Tambahkan kriteria dan alternatif terlebih dahulu.</p>
        <a href="kriteria.php" class="btn btn-outline-primary me-2">Kelola Kriteria</a>
        <a href="alternatif.php" class="btn btn-outline-primary">Kelola Alternatif</a>
    </div>
</div>
<?php else: ?>

<!-- Legenda tipe -->
<div class="mb-3 d-flex gap-3 flex-wrap">
    <?php foreach ($kriteria as $k): ?>
    <div class="d-flex align-items-center gap-2 p-2 rounded-3" style="background:#f8fafc;border:1px solid #e8eaf0">
        <code class="text-primary fw-bold"><?= htmlspecialchars($k['kode']) ?></code>
        <span><?= htmlspecialchars($k['nama']) ?></span>
        <span class="badge <?= $k['tipe']==='benefit'?'badge-benefit':'badge-cost' ?>"><?= ucfirst($k['tipe']) ?></span>
        <span class="text-muted small"><?= htmlspecialchars($k['satuan']) ?></span>
    </div>
    <?php endforeach; ?>
</div>

<form method="POST">
    <div class="card mb-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered mb-0" style="font-size:13.5px">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3" style="min-width:60px">Kode</th>
                            <th style="min-width:160px">Smartphone</th>
                            <?php foreach ($kriteria as $k): ?>
                            <th class="text-center" style="min-width:110px">
                                <?= htmlspecialchars($k['kode']) ?><br>
                                <small class="fw-normal text-muted"><?= htmlspecialchars($k['nama']) ?></small><br>
                                <span class="badge <?= $k['tipe']==='benefit'?'badge-benefit':'badge-cost' ?> mt-1">
                                    <?= $k['tipe'] ?>
                                </span>
                            </th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($matrix as $alt): ?>
                        <tr>
                            <td class="ps-3"><code class="text-primary fw-bold"><?= htmlspecialchars($alt['kode']) ?></code></td>
                            <td class="fw-500"><?= htmlspecialchars($alt['nama']) ?></td>
                            <?php foreach ($kriteria as $k): ?>
                            <td class="text-center">
                                <input
                                    type="number"
                                    name="nilai[<?= $alt['id'] ?>][<?= $k['id'] ?>]"
                                    value="<?= htmlspecialchars($alt['nilai'][$k['id']]['nilai'] ?? '') ?>"
                                    class="form-control form-control-sm text-center"
                                    step="any"
                                    placeholder="0"
                                    style="max-width:100px;margin:auto"
                                >
                            </td>
                            <?php endforeach; ?>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i> Simpan Semua Nilai
        </button>
        <a href="hitung.php" class="btn btn-success">
            <i class="bi bi-calculator me-1"></i> Lanjut ke Perhitungan
        </a>
    </div>
</form>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
