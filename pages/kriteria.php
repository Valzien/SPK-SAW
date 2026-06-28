<?php
require_once __DIR__ . '/../includes/auth.php';

require_once __DIR__ . '/../includes/SAW.php';

$pageTitle = 'Kriteria';
$saw = new SAW();

// Handle POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'tambah') {
            $saw->tambahKriteria(
                trim($_POST['kode']),
                trim($_POST['nama']),
                $_POST['tipe'],
                (float)$_POST['bobot'],
                trim($_POST['satuan'])
            );
            $_SESSION['success'] = 'Kriteria berhasil ditambahkan.';
        } elseif ($action === 'edit') {
            $saw->updateKriteria(
                (int)$_POST['id'],
                trim($_POST['nama']),
                $_POST['tipe'],
                (float)$_POST['bobot'],
                trim($_POST['satuan'])
            );
            $_SESSION['success'] = 'Kriteria berhasil diperbarui.';
        } elseif ($action === 'hapus') {
            $saw->hapusKriteria((int)$_POST['id']);
            $_SESSION['success'] = 'Kriteria berhasil dihapus.';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Gagal: ' . $e->getMessage();
    }
    header('Location: kriteria.php');
    exit;
}

$kriteria   = $saw->getAllKriteria();
$totalBobot = $saw->getTotalBobot();

include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-600">Manajemen Kriteria</h5>
        <small class="text-muted">Total bobot: <strong><?= number_format($totalBobot,2) ?></strong>
            <?php if (abs($totalBobot - 1) > 0.001): ?>
                <span class="badge bg-warning text-dark ms-1"><i class="bi bi-exclamation-triangle"></i> Sebaiknya = 1.00</span>
            <?php else: ?>
                <span class="badge bg-success-subtle text-success ms-1"><i class="bi bi-check-circle"></i> Valid</span>
            <?php endif; ?>
        </small>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i> Tambah Kriteria
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3" style="width:60px">Kode</th>
                    <th>Nama Kriteria</th>
                    <th>Tipe</th>
                    <th>Bobot</th>
                    <th>Satuan</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($kriteria)): ?>
                <tr><td colspan="6" class="text-center py-4 text-muted">Belum ada data kriteria.</td></tr>
                <?php endif; ?>
                <?php foreach ($kriteria as $k): ?>
                <tr>
                    <td class="ps-3"><code class="text-primary fw-bold"><?= htmlspecialchars($k['kode']) ?></code></td>
                    <td class="fw-500"><?= htmlspecialchars($k['nama']) ?></td>
                    <td>
                        <span class="badge <?= $k['tipe']==='benefit'?'badge-benefit':'badge-cost' ?>">
                            <?= ucfirst($k['tipe']) ?>
                        </span>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="vi-bar" style="width:60px">
                                <div class="vi-fill" style="width:<?= ($k['bobot']*100) ?>%"></div>
                            </div>
                            <span><?= number_format($k['bobot']*100,0) ?>%</span>
                        </div>
                    </td>
                    <td class="text-muted"><?= htmlspecialchars($k['satuan']) ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary btn-edit me-1"
                            data-id="<?= $k['id'] ?>"
                            data-nama="<?= htmlspecialchars($k['nama']) ?>"
                            data-tipe="<?= $k['tipe'] ?>"
                            data-bobot="<?= $k['bobot'] ?>"
                            data-satuan="<?= htmlspecialchars($k['satuan']) ?>">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="action" value="hapus">
                            <input type="hidden" name="id" value="<?= $k['id'] ?>">
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
</div>

<!-- Modal Tambah -->
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" class="modal-content">
            <input type="hidden" name="action" value="tambah">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Kriteria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-4">
                    <label class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" name="kode" class="form-control" placeholder="C5" required maxlength="10">
                </div>
                <div class="col-8">
                    <label class="form-label">Nama Kriteria <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" placeholder="RAM" required>
                </div>
                <div class="col-6">
                    <label class="form-label">Tipe <span class="text-danger">*</span></label>
                    <select name="tipe" class="form-select" required>
                        <option value="benefit">Benefit (semakin besar semakin baik)</option>
                        <option value="cost">Cost (semakin kecil semakin baik)</option>
                    </select>
                </div>
                <div class="col-3">
                    <label class="form-label">Bobot <span class="text-danger">*</span></label>
                    <input type="number" name="bobot" class="form-control" step="0.01" min="0" max="1" placeholder="0.25" required>
                </div>
                <div class="col-3">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="satuan" class="form-control" placeholder="GB">
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
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Kriteria</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-12">
                    <label class="form-label">Nama Kriteria</label>
                    <input type="text" name="nama" id="editNama" class="form-control" required>
                </div>
                <div class="col-6">
                    <label class="form-label">Tipe</label>
                    <select name="tipe" id="editTipe" class="form-select">
                        <option value="benefit">Benefit</option>
                        <option value="cost">Cost</option>
                    </select>
                </div>
                <div class="col-3">
                    <label class="form-label">Bobot</label>
                    <input type="number" name="bobot" id="editBobot" class="form-control" step="0.01" min="0" max="1">
                </div>
                <div class="col-3">
                    <label class="form-label">Satuan</label>
                    <input type="text" name="satuan" id="editSatuan" class="form-control">
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
        document.getElementById("editId").value     = btn.dataset.id;
        document.getElementById("editNama").value   = btn.dataset.nama;
        document.getElementById("editTipe").value   = btn.dataset.tipe;
        document.getElementById("editBobot").value  = btn.dataset.bobot;
        document.getElementById("editSatuan").value = btn.dataset.satuan;
        new bootstrap.Modal(document.getElementById("modalEdit")).show();
    });
});
</script>';
include __DIR__ . '/../includes/footer.php';
?>
