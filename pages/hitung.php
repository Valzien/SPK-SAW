<?php
require_once __DIR__ . '/../includes/auth.php';

require_once __DIR__ . '/../includes/SAW.php';

$pageTitle = 'Proses SAW';
$saw = new SAW();
$result = null;
$error  = null;

// Jalankan perhitungan jika POST
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'hitung') {
    try {
        $result = $saw->hitung();
        $_SESSION['success'] = 'Perhitungan SAW berhasil!';
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$kriteria = $saw->getAllKriteria();
$matrix   = $saw->getNilaiMatrix();
$totalBobot = $saw->getTotalBobot();

include __DIR__ . '/../includes/header.php';
?>

<h5 class="fw-600 mb-1">Proses Perhitungan SAW</h5>
<p class="text-muted small mb-3">Simple Additive Weighting — langkah demi langkah</p>

<?php if ($error): ?>
<div class="alert alert-danger"><i class="bi bi-x-circle me-2"></i><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<!-- Tombol hitung -->
<div class="card mb-4">
    <div class="card-body d-flex align-items-center gap-3">
        <div>
            <div class="fw-500 mb-1">Siap untuk dihitung?</div>
            <small class="text-muted">
                <?= count($kriteria) ?> kriteria · <?= count($matrix) ?> alternatif ·
                Total bobot: <strong><?= number_format($totalBobot,2) ?></strong>
                <?php if (abs($totalBobot-1)>0.001): ?>
                    <span class="text-warning"><i class="bi bi-exclamation-triangle"></i> Bobot ≠ 1.00</span>
                <?php endif; ?>
            </small>
        </div>
        <form method="POST" class="ms-auto">
            <input type="hidden" name="action" value="hitung">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-play-fill me-1"></i> Jalankan Perhitungan
            </button>
        </form>
    </div>
</div>

<?php if ($result): $kriteria=$result['kriteria'];$matrix=$result['matrix'];$maxMin=$result['maxMin'];$hasil=$result['hasil'];$norm=$result['norm']; ?>

<!-- STEP 1: Matriks Keputusan -->
<div class="step-card">
    <div class="step-header">
        <div class="step-number">1</div>
        <div>
            <div class="step-title">Matriks Keputusan (X)</div>
            <div class="step-desc">Nilai asli tiap alternatif untuk setiap kriteria</div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Alternatif</th>
                    <?php foreach ($kriteria as $k): ?>
                    <th class="text-center"><?= htmlspecialchars($k['kode']) ?> (<?= htmlspecialchars($k['nama']) ?>)</th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matrix as $alt): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($alt['kode']) ?></strong> — <?= htmlspecialchars($alt['nama']) ?></td>
                    <?php foreach ($kriteria as $k): ?>
                    <td class="text-center"><?= number_format($alt['nilai'][$k['id']]['nilai'],2) ?></td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- STEP 2: Max/Min -->
<div class="step-card">
    <div class="step-header">
        <div class="step-number">2</div>
        <div>
            <div class="step-title">Nilai Maks & Min Tiap Kriteria</div>
            <div class="step-desc">Benefit → gunakan Max · Cost → gunakan Min</div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Statistik</th>
                    <?php foreach ($kriteria as $k): ?>
                    <th class="text-center">
                        <?= htmlspecialchars($k['kode']) ?>
                        <span class="badge <?= $k['tipe']==='benefit'?'badge-benefit':'badge-cost' ?> ms-1"><?= $k['tipe'] ?></span>
                    </th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-muted">Max</td>
                    <?php foreach ($kriteria as $k): ?>
                    <td class="text-center <?= $k['tipe']==='benefit'?'text-success fw-500':'' ?>">
                        <?= number_format($maxMin[$k['id']]['max'],2) ?>
                        <?= $k['tipe']==='benefit'?'<i class="bi bi-check-circle-fill text-success ms-1"></i>':'' ?>
                    </td>
                    <?php endforeach; ?>
                </tr>
                <tr>
                    <td class="text-muted">Min</td>
                    <?php foreach ($kriteria as $k): ?>
                    <td class="text-center <?= $k['tipe']==='cost'?'text-danger fw-500':'' ?>">
                        <?= number_format($maxMin[$k['id']]['min'],2) ?>
                        <?= $k['tipe']==='cost'?'<i class="bi bi-check-circle-fill text-danger ms-1"></i>':'' ?>
                    </td>
                    <?php endforeach; ?>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="mt-2 p-2 rounded-3" style="background:#f8fafc;border:1px solid #e8eaf0">
        <small class="text-muted">
            <strong>Rumus:</strong>
            Benefit → r<sub>ij</sub> = x<sub>ij</sub> / Max(x<sub>j</sub>) &nbsp;|&nbsp;
            Cost → r<sub>ij</sub> = Min(x<sub>j</sub>) / x<sub>ij</sub>
        </small>
    </div>
</div>

<!-- STEP 3: Normalisasi -->
<div class="step-card">
    <div class="step-header">
        <div class="step-number">3</div>
        <div>
            <div class="step-title">Normalisasi Matriks (r<sub>ij</sub>)</div>
            <div class="step-desc">Semua nilai ternormalisasi ke rentang [0, 1]</div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Alternatif</th>
                    <?php foreach ($kriteria as $k): ?>
                    <th class="text-center">r(<?= htmlspecialchars($k['kode']) ?>)</th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($matrix as $alt): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($alt['kode']) ?></strong></td>
                    <?php foreach ($kriteria as $k):
                        $rij = $norm[$alt['id']][$k['id']] ?? 0; ?>
                    <td class="text-center <?= $rij >= 0.999 ? 'table-success fw-500' : '' ?>">
                        <?= number_format($rij,5) ?>
                    </td>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- STEP 4: Nilai Vi -->
<div class="step-card">
    <div class="step-header">
        <div class="step-number">4</div>
        <div>
            <div class="step-title">Nilai Preferensi (V<sub>i</sub>)</div>
            <div class="step-desc">V<sub>i</sub> = Σ (w<sub>j</sub> × r<sub>ij</sub>)</div>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered mb-0">
            <thead class="table-light">
                <tr>
                    <th>Rank</th>
                    <th>Alternatif</th>
                    <?php foreach ($kriteria as $k): ?>
                    <th class="text-center">w<?= htmlspecialchars($k['kode']) ?>(<?= $k['bobot'] ?>) × r</th>
                    <?php endforeach; ?>
                    <th class="text-center">V<sub>i</sub></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($hasil as $h):
                    $rankClass = match($h['peringkat']) {1=>'rank-1',2=>'rank-2',3=>'rank-3',default=>'rank-n'};
                ?>
                <tr class="<?= $h['peringkat']===1?'table-success':'' ?>">
                    <td class="text-center">
                        <span class="rank-badge <?= $rankClass ?>"><?= $h['peringkat'] ?></span>
                    </td>
                    <td><strong><?= htmlspecialchars($h['alt_kode']) ?></strong> — <?= htmlspecialchars($h['alt_nama']) ?></td>
                    <?php foreach ($kriteria as $k):
                        $r = $h['norm'][$k['id']] ?? 0;
                        $contrib = $k['bobot'] * $r;
                    ?>
                    <td class="text-center text-muted"><?= number_format($contrib,5) ?></td>
                    <?php endforeach; ?>
                    <td class="text-center fw-bold"><?= number_format($h['vi'],5) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="mt-3">
        <a href="hasil.php" class="btn btn-success">
            <i class="bi bi-trophy me-1"></i> Lihat Hasil Ranking
        </a>
    </div>
</div>

<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
