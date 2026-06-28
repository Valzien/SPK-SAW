<?php
require_once __DIR__ . '/../includes/auth.php';

require_once __DIR__ . '/../includes/SAW.php';

$pageTitle = 'Alternatif';
$saw = new SAW();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    try {
        if ($action === 'tambah') {
            $saw->tambahAlternatif(trim($_POST['kode']), trim($_POST['nama']));
            $_SESSION['success'] = 'Alternatif berhasil ditambahkan.';
        } elseif ($action === 'edit') {
            $saw->updateAlternatif((int)$_POST['id'], trim($_POST['kode']), trim($_POST['nama']));
            $_SESSION['success'] = 'Alternatif berhasil diperbarui.';
        } elseif ($action === 'hapus') {
            $saw->hapusAlternatif((int)$_POST['id']);
            $_SESSION['success'] = 'Alternatif berhasil dihapus.';
        }
    } catch (Exception $e) {
        $_SESSION['error'] = 'Gagal: ' . $e->getMessage();
    }
    header('Location: alternatif.php');
    exit;
}

$alternatif = $saw->getAllAlternatif();
include __DIR__ . '/../includes/header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h5 class="mb-0 fw-600">Manajemen Alternatif</h5>
        <small class="text-muted"><?= count($alternatif) ?> smartphone terdaftar</small>
    </div>
    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
        <i class="bi bi-plus-lg me-1"></i> Tambah Alternatif
    </button>
</div>

<div class="card">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th class="ps-3" style="width:50px">No</th>
                    <th style="width:80px">Kode</th>
                    <th>Nama Smartphone</th>
                    <th style="width:120px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($alternatif)): ?>
                <tr><td colspan="4" class="text-center py-4 text-muted">Belum ada data alternatif.</td></tr>
                <?php endif; ?>
                <?php foreach ($alternatif as $i => $a): ?>
                <tr>
                    <td class="ps-3 text-muted"><?= $i + 1 ?></td>
                    <td><code class="text-primary fw-bold"><?= htmlspecialchars($a['kode']) ?></code></td>
                    <td class="fw-500">
                        <i class="bi bi-phone me-2 text-muted"></i>
                        <?= htmlspecialchars($a['nama']) ?>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary btn-edit me-1"
                            data-id="<?= $a['id'] ?>"
                            data-kode="<?= htmlspecialchars($a['kode']) ?>"
                            data-nama="<?= htmlspecialchars($a['nama']) ?>">
                            <i class="bi bi-pencil"></i>
                        </button>
                        <form method="POST" class="d-inline">
                            <input type="hidden" name="action" value="hapus">
                            <input type="hidden" name="id" value="<?= $a['id'] ?>">
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
                <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Tambah Alternatif</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-4">
                    <label class="form-label">Kode <span class="text-danger">*</span></label>
                    <input type="text" name="kode" class="form-control" placeholder="A6" required maxlength="10">
                </div>
                <div class="col-8">
                    <label class="form-label">Nama Smartphone <span class="text-danger">*</span></label>
                    <input type="text" name="nama" class="form-control" placeholder="Samsung Galaxy A25" required>
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
                <h5 class="modal-title"><i class="bi bi-pencil-square me-2"></i>Edit Alternatif</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body row g-3">
                <div class="col-4">
                    <label class="form-label">Kode</label>
                    <input type="text" name="kode" id="editKode" class="form-control" required>
                </div>
                <div class="col-8">
                    <label class="form-label">Nama Smartphone</label>
                    <input type="text" name="nama" id="editNama" class="form-control" required>
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
        document.getElementById("editKode").value = btn.dataset.kode;
        document.getElementById("editNama").value = btn.dataset.nama;
        new bootstrap.Modal(document.getElementById("modalEdit")).show();
    });
});
</script>';
include __DIR__ . '/../includes/footer.php';
?>
