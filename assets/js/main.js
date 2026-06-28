// assets/js/main.js

// Sidebar toggle
document.getElementById('sidebarToggle')?.addEventListener('click', () => {
    document.body.classList.toggle('sidebar-collapsed');
});

// Auto-dismiss alert after 4s
document.querySelectorAll('.alert').forEach(el => {
    setTimeout(() => {
        const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
        bsAlert?.close();
    }, 4000);
});

// Konfirmasi hapus
document.querySelectorAll('.btn-hapus').forEach(btn => {
    btn.addEventListener('click', e => {
        if (!confirm('Yakin ingin menghapus data ini?')) e.preventDefault();
    });
});
