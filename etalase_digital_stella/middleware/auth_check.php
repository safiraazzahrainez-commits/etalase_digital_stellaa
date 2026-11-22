<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php?error=Silakan login terlebih dahulu");
    exit;
}
function authorizeRole(array $allowedRoles) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $allowedRoles)) {
        header("Location: ../index.php?error=Akses ditolak!");
        exit;
    }
}
?>