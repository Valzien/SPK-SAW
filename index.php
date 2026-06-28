<?php
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/SAW.php';

$pageTitle  = 'Dashboard';
$saw        = new SAW();
$kriteria   = $saw->getAllKriteria();
$alternatif = $saw->getAllAlternatif();
$hasilDb    = $saw->getHasilTerakhir();
$totalBobot = $saw->getTotalBobot();

include __DIR__ . '/includes/header.php';
?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-4 px-4">
        <div class="d-flex align-items-center">
            <div class="rounded-circle bg-primary bg-opacity-10 d-flex align-items-center justify-content-center"
                 style="width:60px;height:60px;">
                <i class="bi bi-phone text-primary fs-3"></i>
            </div>

            <div class="ms-3">
                <h4 class="fw-bold mb-1">
                    Sistem Pendukung Keputusan
                </h4>

                <div class="text-muted mb-2">
                    Pemilihan Smartphone untuk Pembelajaran Jarak Jauh
                </div>

                <span class="badge bg-primary-subtle text-primary border">
                    Metode Simple Additive Weighting (SAW)
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Stat cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#eff6ff">
                <i class="bi bi-sliders text-primary"></i>
            </div>
            <div>
                <div class="stat-label">Total Kriteria</div>
                <div class="stat-value"><?= count($kriteria) ?></div>
                <div class="stat-sub">Total bobot: <?= number_format($totalBobot,2) ?></div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#f0fdf4">
                <i class="bi bi-phone text-success"></i>
            </div>
            <div>
                <div class="stat-label">Total Alternatif</div>
                <div class="stat-value"><?= count($alternatif) ?></div>
                <div class="stat-sub">Smartphone yang dinilai</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fef3c7">
                <i class="bi bi-diagram-3 text-warning"></i>
            </div>
            <div>
                <div class="stat-label">Metode</div>
                <div class="stat-value" style="font-size:15px">SAW</div>
                <div class="stat-sub">Simple Additive Weighting</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card">
            <div class="stat-icon" style="background:#fdf4ff">
                <i class="bi bi-bar-chart-steps" style="color:#7c3aed"></i>
            </div>
            <div>
                <div class="stat-label">Hasil Ranking</div>
                <div class="stat-value"><?= empty($hasilDb) ? '—' : count($hasilDb) ?></div>
                <div class="stat-sub"><?= empty($hasilDb) ? 'Belum dihitung' : 'Alternatif teranking' ?></div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($hasilDb)): $winner = $hasilDb[0]; ?>
<!-- Winner banner -->
<div class="winner-card mb-4">
    <div class="winner-icon-wrap">
        <i class="bi bi-award-fill"></i>
    </div>
    <div class="flex-grow-1">
        <div class="winner-label">Rekomendasi Terbaik</div>
        <div class="winner-name"><?= htmlspecialchars($winner['nama']) ?></div>
        <div style="font-size:12px;opacity:.7">
            <?= htmlspecialchars($winner['kode']) ?> &mdash; Ranking #1 dari <?= count($hasilDb) ?> alternatif
        </div>
    </div>
    <div class="text-end">
        <div style="font-size:11px;opacity:.65;margin-bottom:4px">Nilai Preferensi (Vi)</div>
        <div class="winner-score"><?= number_format($winner['nilai_vi'],4) ?></div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-list-ol me-2 text-primary"></i>Hasil Ranking</span>
                <a href="pages/hasil.php" class="btn btn-sm btn-outline-primary">Lihat Detail</a>
            </div>
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
                        <?php foreach ($hasilDb as $h):
                            $rc = match((int)$h['peringkat']) {1=>'rank-1',2=>'rank-2',3=>'rank-3',default=>'rank-n'};
                        ?>
                        <tr>
                            <td class="ps-3"><span class="rank-badge <?= $rc ?>"><?= $h['peringkat'] ?></span></td>
                            <td><code><?= htmlspecialchars($h['kode']) ?></code></td>
                            <td class="fw-500"><?= htmlspecialchars($h['nama']) ?></td>
                            <td><?= number_format($h['nilai_vi'],5) ?></td>
                            <td style="min-width:120px">
                                <div class="d-flex align-items-center gap-2">
                                    <div class="vi-bar flex-grow-1">
                                        <div class="vi-fill" style="width:<?= number_format($h['nilai_vi']*100,1) ?>%"></div>
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
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header"><i class="bi bi-bar-chart me-2 text-primary"></i>Grafik Nilai Vi</div>
            <div class="card-body d-flex align-items-center">
                <canvas id="chartVi" style="max-height:210px" role="img" aria-label="Grafik nilai Vi tiap alternatif"></canvas>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="card mb-4">
    <div class="card-body text-center py-5">
        <i class="bi bi-calculator display-4 text-muted d-block mb-3"></i>
        <h5 class="text-muted">Belum ada hasil perhitungan</h5>
        <p class="text-muted small mb-3">Pastikan data kriteria, alternatif, dan nilai sudah diisi.</p>
        <a href="pages/hitung.php" class="btn btn-primary">
            <i class="bi bi-play-fill me-1"></i>Mulai Perhitungan SAW
        </a>
    </div>
</div>
<?php endif; ?>

<!-- Penjelasan Metode SAW -->
<div class="row g-3 mb-4">
    <div class="col-lg-8">
        <div class="info-box">
            <h6><i class="bi bi-info-circle me-2 text-primary"></i>Tentang Metode SAW</h6>
            <p>Metode Simple Additive Weighting (SAW) sering juga dikenal istilah metode penjumlahan terbobot. Konsep dasar metode SAW adalah mencari penjumlahan terbobot dari rating kinerja pada setiap alternatif pada semua atribut (Fishburn 1967). SAW dapat dianggap sebagai cara yang paling mudah dan intuitif untuk menangani masalah Multiple Criteria Decision-Making (MCDM), karena fungsi linear additive dapat mewakili preferensi pembuat keputusan (Decision-Making, DM). Hal tersebut dapat dibenarkan, namun, hanya ketika asumsi <em>preference independence</em> (Keeney &amp; Raiffa 1976) atau <em>preference separability</em> (Gorman 1968) terpenuhi.</p>
            <hr class="info-divider">
            <h6><i class="bi bi-list-check me-2 text-primary"></i>Langkah Penyelesaian SAW</h6>
            <ol>
                <li>Menentukan kriteria-kriteria dan nilai bobot kriteria (Wi) yang akan dijadikan acuan dalam pengambilan keputusan, yaitu Ci. Dimana total bobot sama dengan 1 (&Sigma;wi = 1).</li>
                <li>Menentukan rating kecocokan setiap alternatif pada setiap kriteria (X).</li>
                <li>Normalisasi Matriks. Berdasarkan persamaan yang disesuaikan dengan jenis atribut (atribut keuntungan ataupun atribut biaya) sehingga diperoleh matriks ternormalisasi R.</li>
                <li>Preferensi (Vi) yaitu hasil akhir yang diperoleh dari proses perankingan, yaitu penjumlahan dari perkalian matriks ternormalisasi R dengan vektor bobot sehingga diperoleh nilai terbesar yang dipilih sebagai alternatif terbaik (Ai) sebagai solusi.</li>
            </ol>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="info-box h-100">
            <h6><i class="bi bi-sliders me-2 text-primary"></i>Kriteria yang Digunakan</h6>
            <div class="d-flex flex-column gap-2 mt-2">
                <?php foreach ($kriteria as $k): ?>
                <div class="d-flex align-items-center justify-content-between p-2 rounded-3" style="background:#f8fafc;border:1px solid #e8eaf0">
                    <div>
                        <code class="text-primary fw-bold"><?= htmlspecialchars($k['kode']) ?></code>
                        <span class="ms-2 fw-500" style="font-size:13px"><?= htmlspecialchars($k['nama']) ?></span>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge <?= $k['tipe']==='benefit'?'badge-benefit':'badge-cost' ?>"><?= ucfirst($k['tipe']) ?></span>
                        <span class="text-muted" style="font-size:12px"><?= number_format($k['bobot']*100,0) ?>%</span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<?php
if (!empty($hasilDb)) {
    $labels   = json_encode(array_column($hasilDb,'kode'));
    $data     = json_encode(array_map(fn($h)=>round($h['nilai_vi'],4),$hasilDb));

    $extraScript = "<script>
    new Chart(document.getElementById('chartVi'),{
        type:'bar',
        data:{
            labels:$labels,
            datasets:[{
                label:'Nilai Vi',
                data:$data,
                backgroundColor:['#2563eb','#3b82f6','#60a5fa','#93c5fd','#bfdbfe'],
                borderRadius:6,borderSkipped:false
            }]
        },
        options:{
            responsive:true,maintainAspectRatio:false,
            plugins:{legend:{display:false}},
            scales:{y:{min:0,max:1,ticks:{callback:v=>v.toFixed(1)}}}
        }
    });
    </script>";
}
include __DIR__ . '/includes/footer.php';
?>
