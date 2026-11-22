<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}
if ($_SESSION['role'] === 'super_admin') header("Location: admin/dashboard_sa.php");
elseif ($_SESSION['role'] === 'admin')   header("Location: admin/dashboard_admin.php");
else                                      header("Location: admin/siswa.php");
exit;
?>