<?php
require_once __DIR__ . '/../includes/auth.php';

require_once __DIR__ . '/../includes/SAW.php';

$pageTitle = 'Crisp Nilai';
$saw = new SAW();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'tambah') {
            $saw->tambahCrisp(
                (int)$_POST['kriteria_id'],
                trim($_POST['nama_range']),
                (float)str_replace(',','.',$_POST['nilai_min']),
                (float)str_replace(',','.',$_POST['nilai_max']),
                (int)$_POST['skor']
            );
            $_SESSION['success'] = 'Data crisp berhasil ditambahkan.';
        } elseif ($action === 'edit') {
            $saw->updateCrisp(
                (int)$_POST['id'],
                trim($_POST['nama_range']),
                (float)str_replace(',','.',$_POST['nilai_min']),
                (float)str_replace(',','.',$_POST['nilai_max']),
                (int)$_POST['skor']
            );
            $_SESSION['success'] = 'Data crisp berhasil diperbarui.';
        } elseif ($action === 'hapus') {
            $saw->hapusCrisp((int)$_POST['id']);
            $_SESSION['success'] = 'Data crisp berhasil dihapus.';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Gagal: ' . $e->getMessage();
    }
    header('Location: crisp.php');
    exit;
}

$allCrisp  = $saw->getAllCrisp();
$kriteria  = $saw->getAllKriteria();

// Group crisp by kriteria_id
$crispGrouped = [];
foreach ($allCrisp as $c) {
    $crispGrouped[$c['kriteria_id']][] = $c;
}

include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-600">Crisp / Himpunan Nilai</h5>
        <small class="text-muted">Range nilai untuk setiap kriteria pemilihan smartphone</small>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i> Tambah Crisp
    </button>
</div>

<?php if (empty($kriteria)): ?>
<div class="card">
    <div class="card-body text-center py-5 text-muted">
        <i class="bi bi-exclamation-circle display-4 d-block mb-3"></i>
        Tambahkan kriteria terlebih dahulu sebelum mengisi crisp.
        <br><a href="kriteria.php" class="btn btn-outline-primary mt-3">Kelola Kriteria</a>
    </div>
</div>
<?php else: ?>

<!-- Tampil per kriteria -->
<?php foreach ($kriteria as $k): ?>
<div class="card mb-3">
    <div class="card-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <code class="text-primary fw-bold"><?= htmlspecialchars($k['kode']) ?></code>
            <span class="fw-500"><?= htmlspecialchars($k['nama']) ?></span>
            <span class="badge <?= $k['tipe']==='benefit'?'badge-benefit':'badge-cost' ?>">
                <?= ucfirst($k['tipe']) ?>
            </span>
            <span class="text-muted small">· Bobot <?= number_format($k['bobot']*100,0) ?>% · Satuan: <?= htmlspecialchars($k['satuan']) ?></span>
        </div>
        <span class="badge bg-secondary-subtle text-secondary">
            <?= count($crispGrouped[$k['id']] ?? []) ?> range
        </span>
    </div>
    <div class="card-body p-0">
        <?php if (empty($crispGrouped[$k['id']])): ?>
        <div class="text-center py-4 text-muted small">Belum ada data crisp untuk kriteria ini.</div>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover mb-0" style="font-size:13.5px">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">No</th>
                        <th>Nama / Keterangan Range</th>
                        <th>Nilai Min</th>
                        <th>Nilai Max</th>
                        <th>Skor</th>
                        <th style="width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($crispGrouped[$k['id']] as $i => $c): ?>
                    <tr>
                        <td class="ps-3 text-muted"><?= $i+1 ?></td>
                        <td class="fw-500"><?= htmlspecialchars($c['nama_range']) ?></td>
                        <td><?= number_format($c['nilai_min'],0,'.','.') ?></td>
                        <td><?= number_format($c['nilai_max'],0,'.','.') ?></td>
                        <td>
                            <span class="badge rounded-pill"
                                style="background:<?= ['#fee2e2','#fef3c7','#e0f2fe','#dcfce7','#ede9fe'][$c['skor']-1]??'#f1f5f9' ?>;
                                       color:<?= ['#991b1b','#854d0e','#075985','#166534','#5b21b6'][$c['skor']-1]??'#475569' ?>">
                                <?= $c['skor'] ?>
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-secondary btn-edit me-1"
                                data-id="<?= $c['id'] ?>"
                                data-nama="<?= htmlspecialchars($c['nama_range']) ?>"
                                data-min="<?= $c['nilai_min'] ?>"
                                data-max="<?= $c['nilai_max'] ?>"
                                data-skor="<?= $c['skor'] ?>">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <form method="POST" class="d-inline">
                                <input type="hidden" name="action" value="hapus">
                                <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger btn-hapus">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>
<?php endif; ?>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="action" value="tambah">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Crisp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">Kriteria <span class="text-danger">*</span></label>
                    <select name="kriteria_id" class="form-select" required>
                        <option value="">-- Pilih Kriteria --</option>
                        <?php foreach ($kriteria as $k): ?>
                        <option value="<?= $k['id'] ?>"><?= htmlspecialchars($k['kode'].' - '.$k['nama']) ?> (<?= $k['tipe'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-12">
                    <label class="form-label">Nama / Keterangan Range <span class="text-danger">*</span></label>
                    <input type="text" name="nama_range" class="form-control" placeholder="Contoh: 12 - 13 mp" required>
                </div>
                <div class="col-4">
                    <label class="form-label">Nilai Min <span class="text-danger">*</span></label>
                    <input type="number" name="nilai_min" class="form-control" step="any" placeholder="0" required>
                </div>
                <div class="col-4">
                    <label class="form-label">Nilai Max <span class="text-danger">*</span></label>
                    <input type="number" name="nilai_max" class="form-control" step="any" placeholder="9999" required>
                </div>
                <div class="col-4">
                    <label class="form-label">Skor <span class="text-danger">*</span></label>
                    <input type="number" name="skor" class="form-control" min="1" max="10" placeholder="1-5" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Edit -->
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="action" value="edit">
            <input type="hidden" name="id" id="editId">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Crisp</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">Nama / Keterangan Range</label>
                    <input type="text" name="nama_range" id="editNama" class="form-control" required>
                </div>
                <div class="col-4">
                    <label class="form-label">Nilai Min</label>
                    <input type="number" name="nilai_min" id="editMin" class="form-control" step="any" required>
                </div>
                <div class="col-4">
                    <label class="form-label">Nilai Max</label>
                    <input type="number" name="nilai_max" id="editMax" class="form-control" step="any" required>
                </div>
                <div class="col-4">
                    <label class="form-label">Skor</label>
                    <input type="number" name="skor" id="editSkor" class="form-control" min="1" max="10" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i>Update</button>
            </div>
        </form>
    </div>
</div>

<?php
$extraScript = '<script>
document.querySelectorAll(".btn-edit").forEach(btn => {
    btn.addEventListener("click", () => {
        document.getElementById("editId").value   = btn.dataset.id;
        document.getElementById("editNama").value = btn.dataset.nama;
        document.getElementById("editMin").value  = btn.dataset.min;
        document.getElementById("editMax").value  = btn.dataset.max;
        document.getElementById("editSkor").value = btn.dataset.skor;
        new bootstrap.Modal(document.getElementById("modalEdit")).show();
    });
});
</script>';
include __DIR__ . '/../includes/footer.php';
?>
