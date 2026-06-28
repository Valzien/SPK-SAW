<?php
session_start();
if (!empty($_SESSION['user_id'])) {
    header('Location: ../index.php'); exit;
}
require_once __DIR__ . '/../config/database.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');
    if ($username && $password) {
        $db   = getDB();
        $stmt = $db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id']      = $user['id'];
            $_SESSION['username']     = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            header('Location: ../index.php'); exit;
        }
        $error = 'Username atau password salah.';
    } else {
        $error = 'Username dan password wajib diisi.';
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Sistem SPK SAW</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <style>
        *{box-sizing:border-box}
        body{min-height:100vh;background:#f0f4ff;display:flex;align-items:center;justify-content:center;font-family:'Segoe UI',sans-serif}
        .login-wrap{width:100%;max-width:420px;padding:1rem}
        .login-card{background:#fff;border-radius:16px;box-shadow:0 4px 24px rgba(37,99,235,.10);overflow:hidden}
        .login-header{background:linear-gradient(135deg,#1e40af 0%,#2563eb 100%);padding:2rem 2rem 1.75rem;text-align:center;color:#fff}
        .login-header .app-icon{width:56px;height:56px;background:rgba(255,255,255,.15);border-radius:14px;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;font-size:26px}
        .login-header h1{font-size:1rem;font-weight:600;margin:0;line-height:1.4}
        .login-header p{font-size:12px;opacity:.75;margin:.25rem 0 0}
        .login-body{padding:2rem}
        .form-label{font-size:13px;font-weight:500;color:#374151;margin-bottom:5px}
        .form-control{font-size:14px;border-radius:8px;border-color:#d1d9e6;padding:.6rem .85rem}
        .form-control:focus{border-color:#2563eb;box-shadow:0 0 0 3px rgba(37,99,235,.12)}
        .btn-login{width:100%;padding:.7rem;font-size:14px;font-weight:600;border-radius:8px;background:#2563eb;border:none;color:#fff}
        .btn-login:hover{background:#1d4ed8}
        .input-group-text{border-radius:8px 0 0 8px;border-color:#d1d9e6;background:#f8fafc;color:#6b7a8d;cursor:pointer}
        .alert-danger{font-size:13px;border-radius:8px;padding:.6rem .85rem}
        .footer-note{text-align:center;font-size:12px;color:#9ca3af;margin-top:1.25rem}
    </style>
</head>
<body>
<div class="login-wrap">
    <div class="login-card">
        <div class="login-header">
            <div class="app-icon"><i class="bi bi-cpu"></i></div>
            <h1>Sistem Keputusan Pemilihan Smartphone<br>Untuk Pembelajaran Jarak Jauh</h1>
            <p>Metode Simple Additive Weighting (SAW)</p>
        </div>
        <div class="login-body">
            <?php if ($error): ?>
            <div class="alert alert-danger d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-exclamation-circle-fill"></i> <?= htmlspecialchars($error) ?>
            </div>
            <?php endif; ?>
            <form method="POST" autocomplete="off">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan username"
                               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" required autofocus>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <span class="input-group-text" onclick="togglePwd()" id="eyeBtn">
                            <i class="bi bi-eye" id="eyeIcon"></i>
                        </span>
                        <input type="password" name="password" id="pwdInput" class="form-control" placeholder="Masukkan password" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-login">
                    <i class="bi bi-box-arrow-in-right me-2"></i>Masuk ke Sistem
                </button>
            </form>
            <div class="footer-note">Default: admin / admin123</div>
        </div>
    </div>
</div>
<script>
function togglePwd(){
    const i=document.getElementById('pwdInput');
    const ic=document.getElementById('eyeIcon');
    if(i.type==='password'){i.type='text';ic.className='bi bi-eye-slash';}
    else{i.type='password';ic.className='bi bi-eye';}
}
</script>
</body>
</html>
