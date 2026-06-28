<?php
require_once __DIR__ . '/../includes/auth.php';

require_once __DIR__ . '/../includes/SAW.php';

$pageTitle = 'Hasil & Ranking';
$saw    = new SAW();
$hasil  = $saw->getHasilTerakhir();
$kriteria = $saw->getAllKriteria();

include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-600">Hasil Ranking Akhir</h5>
        <small class="text-muted">Berdasarkan metode Simple Additive Weighting (SAW)</small>
    </div>
    <?php if (!empty($hasil)): ?>
    <a href="hitung.php" class="btn btn-sm btn-outline-primary">
        <i class="bi bi-arrow-repeat me-1"></i> Hitung Ulang
    </a>
    <?php endif; ?>
</div>

<?php if (empty($hasil)): ?>
<div class="card">
    <div class="card-body text-center py-5">
        <i class="bi bi-calculator display-4 text-muted d-block mb-3"></i>
        <p class="text-muted mb-3">Belum ada hasil. Jalankan perhitungan terlebih dahulu.</p>
        <a href="hitung.php" class="btn btn-primary">
            <i class="bi bi-play-fill me-1"></i> Ke Halaman Perhitungan
        </a>
    </div>
</div>
<?php else:
    $winner = $hasil[0];
    $colors = ['#2563eb','#3b82f6','#60a5fa','#93c5fd','#bfdbfe'];
?>

<!-- Winner -->
<div class="winner-card mb-4">
    <div class="winner-icon-wrap">
        <i class="bi bi-award-fill"></i>
    </div>
    <div style="flex:1">
        <div class="winner-label">Rekomendasi Terbaik</div>
        <div class="winner-name"><?= htmlspecialchars($winner['nama']) ?></div>
        <div style="font-size:12px;opacity:.75">
            <code style="background:rgba(255,255,255,.2);padding:2px 6px;border-radius:4px"><?= htmlspecialchars($winner['kode']) ?></code>
            &nbsp;Ranking #1 dari <?= count($hasil) ?> alternatif
        </div>
    </div>
    <div class="text-end">
        <div style="font-size:11px;opacity:.7;margin-bottom:4px">Nilai Vi</div>
        <div class="winner-score"><?= number_format($winner['nilai_vi'],4) ?></div>
    </div>
</div>

<div class="row g-3 mb-4">
    <!-- Tabel ranking -->
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-list-ol me-2"></i>Tabel Peringkat</div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-3">Rank</th>
                            <th>Kode</th>
                            <th>Smartphone</th>
                            <th>Nilai Vi</th>
                            <th>Skor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($hasil as $h):
                            $rc = match((int)$h['peringkat']) {
                                1=>'rank-1',2=>'rank-2',3=>'rank-3',default=>'rank-n'
                            };
                            
                        ?>
                        <tr <?= $h['peringkat']==1?'style="background:#f0fdf4"':'' ?>>
                            <td class="ps-3">
                                <span class="rank-badge <?= $rc ?>"><?= $h['peringkat'] ?></span>
                                
                            </td>
                            <td><code class="text-primary"><?= htmlspecialchars($h['kode']) ?></code></td>
                            <td class="fw-500"><?= htmlspecialchars($h['nama']) ?></td>
                            <td><strong><?= number_format($h['nilai_vi'],5) ?></strong></td>
                            <td style="min-width:130px">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="vi-bar" style="width:90px">
                                        <div class="vi-fill" style="width:<?= number_format($h['nilai_vi']*100,1) ?>%;background:<?= $colors[$h['peringkat']-1]??'#93c5fd' ?>"></div>
                                    </div>
                                    <small><?= number_format($h['nilai_vi']*100,1) ?>%</small>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Grafik -->
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-bar-chart-fill me-2"></i>Visualisasi</div>
            <div class="card-body">
                <canvas id="chartHasil" style="max-height:230px" role="img" aria-label="Grafik peringkat SAW">Grafik nilai Vi.</canvas>
            </div>
        </div>
    </div>
</div>

<!-- Kesimpulan -->
<div class="card border-0" style="background:#eff6ff">
    <div class="card-body">
        <h6 class="fw-bold text-primary mb-2"><i class="bi bi-lightbulb me-2"></i>Kesimpulan</h6>
        <p class="mb-0" style="font-size:14px;color:#1e40af">
            Berdasarkan perhitungan metode SAW dengan <?= count($kriteria) ?> kriteria
            (<?= implode(', ', array_column($kriteria, 'nama')) ?>),
            smartphone <strong><?= htmlspecialchars($winner['nama']) ?></strong> mendapatkan nilai preferensi
            tertinggi sebesar <strong><?= number_format($winner['nilai_vi'],5) ?></strong>
            dan direkomendasikan sebagai pilihan terbaik untuk pembelajaran jarak jauh.
        </p>
    </div>
</div>

<?php
$labelsJs = json_encode(array_column($hasil, 'kode'));
$dataJs   = json_encode(array_map(fn($h) => round($h['nilai_vi'],4), $hasil));
$colorsJs = json_encode(array_slice($colors, 0, count($hasil)));
$extraScript = "<script>
new Chart(document.getElementById('chartHasil'), {
    type: 'bar',
    data: {
        labels: $labelsJs,
        datasets: [{
            label: 'Nilai Vi',
            data: $dataJs,
            backgroundColor: $colorsJs,
            borderRadius: 6,
            borderSkipped: false
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: { x: { min: 0, max: 1 } }
    }
});
</script>";
endif;
include __DIR__ . '/../includes/footer.php';
?>
