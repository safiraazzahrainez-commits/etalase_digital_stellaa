<?php
session_start();
require_once '../config/kuis_db.php';
require_once '../middleware/auth_check.php';
authorizeRole(['super_admin']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $nama     = trim($_POST['nama']);
        $email    = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $role     = $_POST['role'];

        $stmt = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama, $email, $password, $role]);
        $_SESSION['toast'] = "User baru berhasil ditambahkan!";
    }

    if ($action === 'update') {
        $id    = $_POST['id'];
        $nama  = trim($_POST['nama']);
        $email = trim($_POST['email']);
        $role  = $_POST['role'];

        if (!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET nama=?, email=?, password=?, role=? WHERE id=?");
            $stmt->execute([$nama, $email, $password, $role, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET nama=?, email=?, role=? WHERE id=?");
            $stmt->execute([$nama, $email, $role, $id]);
        }
        $_SESSION['toast'] = "Data user berhasil diperbarui!";
    }
    header("Location: ../admin/users.php");
    exit;
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    if ($id != $_SESSION['user_id']) {
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
        $_SESSION['toast'] = "User berhasil dihapus!";
    }
    header("Location: ../admin/users.php");
    exit;
}
?>