<?php
// includes/header.php
require_once __DIR__ . '/auth.php';
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle ?? 'SPK SAW') ?> — Sistem SPK Smartphone</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="<?= str_repeat('../', substr_count($_SERVER['PHP_SELF'], '/') - 2) ?>assets/css/style.css" rel="stylesheet">
</head>
<body>

<div class="sidebar" id="sidebar">
    <div class="sidebar-brand">
        <i class="bi bi-cpu me-2"></i>
        <span>SPK Smartphone</span>
    </div>
    <div class="sidebar-subtitle">Pemilihan Smartphone untuk Pembelajaran Jarak Jauh</div>
    <hr class="sidebar-divider">
    <nav class="sidebar-nav">
        <a href="<?= str_repeat('../', substr_count($_SERVER['PHP_SELF'], '/') - 2) ?>index.php"
           class="nav-link <?= $currentPage === 'index' ? 'active' : '' ?>">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <div class="nav-section-label">Master Data</div>
        <a href="<?= str_repeat('../', substr_count($_SERVER['PHP_SELF'], '/') - 2) ?>pages/kriteria.php"
           class="nav-link <?= $currentPage === 'kriteria' ? 'active' : '' ?>">
            <i class="bi bi-sliders"></i> Kriteria
        </a>
        <a href="<?= str_repeat('../', substr_count($_SERVER['PHP_SELF'], '/') - 2) ?>pages/crisp.php"
           class="nav-link <?= $currentPage === 'crisp' ? 'active' : '' ?>">
            <i class="bi bi-list-columns-reverse"></i> Crisp Nilai
        </a>
        <a href="<?= str_repeat('../', substr_count($_SERVER['PHP_SELF'], '/') - 2) ?>pages/alternatif.php"
           class="nav-link <?= $currentPage === 'alternatif' ? 'active' : '' ?>">
            <i class="bi bi-phone"></i> Alternatif
        </a>
        <a href="<?= str_repeat('../', substr_count($_SERVER['PHP_SELF'], '/') - 2) ?>pages/nilai.php"
           class="nav-link <?= $currentPage === 'nilai' ? 'active' : '' ?>">
            <i class="bi bi-table"></i> Input Nilai
        </a>
        <div class="nav-section-label">Perhitungan</div>
        <a href="<?= str_repeat('../', substr_count($_SERVER['PHP_SELF'], '/') - 2) ?>pages/hitung.php"
           class="nav-link <?= $currentPage === 'hitung' ? 'active' : '' ?>">
            <i class="bi bi-calculator"></i> Proses SAW
        </a>
        <a href="<?= str_repeat('../', substr_count($_SERVER['PHP_SELF'], '/') - 2) ?>pages/hasil.php"
           class="nav-link <?= $currentPage === 'hasil' ? 'active' : '' ?>">
            <i class="bi bi-trophy"></i> Hasil & Ranking
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="sidebar-user">
            <i class="bi bi-person-circle me-2"></i>
            <span><?= htmlspecialchars($_SESSION['nama_lengkap'] ?? $_SESSION['username'] ?? '') ?></span>
        </div>
        <div class="sidebar-user">
        <a href="<?= str_repeat('../', substr_count($_SERVER['PHP_SELF'], '/') - 2) ?>pages/logout.php"
           class="nav-link sidebar-logout">
            <i class="bi bi-box-arrow-right me-2"></i> Keluar
        </a>
        </div>
    </div>
</div>

<div class="main-wrapper">
    <div class="topbar">
        <button class="btn btn-sm btn-outline-secondary" id="sidebarToggle" aria-label="Toggle sidebar">
            <i class="bi bi-list"></i>
        </button>
        <div class="topbar-title"><?= htmlspecialchars($pageTitle ?? 'Dashboard') ?></div>
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="badge bg-primary-subtle text-primary">Metode SAW</span>
            <span class="text-muted small d-none d-md-inline">
                <i class="bi bi-person me-1"></i><?= htmlspecialchars($_SESSION['username'] ?? '') ?>
            </span>
        </div>
    </div>
    <div class="page-content">
<?php
if (!empty($_SESSION['success'])) {
    echo '<div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>' . htmlspecialchars($_SESSION['success']) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
    unset($_SESSION['success']);
}
if (!empty($_SESSION['error'])) {
    echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>' . htmlspecialchars($_SESSION['error']) . '
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
          </div>';
    unset($_SESSION['error']);
}
?>
