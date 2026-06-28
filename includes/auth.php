<?php
// includes/auth.php — wajib dipanggil di setiap halaman protected
if (session_status() === PHP_SESSION_NONE) session_start();
if (empty($_SESSION['user_id'])) {
    header('Location: ' . str_repeat('../', substr_count($_SERVER['PHP_SELF'], '/') - 2) . 'pages/login.php');
    exit;
}
