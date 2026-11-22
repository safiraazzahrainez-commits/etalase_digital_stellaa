<?php
session_start();
require_once 'config/kuis_db.php';
require_once 'middleware/auth_check.php';

function redirectDashboard() {
    $role = $_SESSION['role'];
    if ($role === 'super_admin') header("Location: admin/dashboard_sa.php");
    elseif ($role === 'admin')   header("Location: admin/dashboard_admin.php");
    else                         header("Location: admin/siswa.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'create') {
        $nama_project = trim($_POST['nama_project']);
        $deskripsi    = trim($_POST['deskripsi']);
        $link_github  = trim($_POST['link_github']);
        $link_live    = trim($_POST['link_live']);
        $user_id      = $_SESSION['user_id'];

        $gambar = 'default.png';
        if (!empty($_FILES['gambar']['name'])) {
            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $gambar = time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads/' . $gambar);
        }

        $stmt = $pdo->prepare("INSERT INTO projects (user_id, nama_project, deskripsi, gambar, link_github, link_live) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $nama_project, $deskripsi, $gambar, $link_github, $link_live]);
        $_SESSION['toast'] = "Karya berhasil ditambahkan!";
        redirectDashboard();
    }

    if ($action === 'update') {
        $id           = $_POST['id'];
        $nama_project = trim($_POST['nama_project']);
        $deskripsi    = trim($_POST['deskripsi']);
        $link_github  = trim($_POST['link_github']);
        $link_live    = trim($_POST['link_live']);

        $cek = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
        $cek->execute([$id]);
        $proj = $cek->fetch();

        if (!$proj || ($_SESSION['role'] === 'siswa' && $proj['user_id'] != $_SESSION['user_id'])) {
            $_SESSION['toast'] = "Akses ditolak!";
            redirectDashboard();
        }

        $gambar = $proj['gambar'];
        if (!empty($_FILES['gambar']['name'])) {
            if ($gambar && file_exists('uploads/' . $gambar)) unlink('uploads/' . $gambar);
            $ext = pathinfo($_FILES['gambar']['name'], PATHINFO_EXTENSION);
            $gambar = time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES['gambar']['tmp_name'], 'uploads/' . $gambar);
        }

        $stmt = $pdo->prepare("UPDATE projects SET nama_project=?, deskripsi=?, gambar=?, link_github=?, link_live=? WHERE id=?");
        $stmt->execute([$nama_project, $deskripsi, $gambar, $link_github, $link_live, $id]);
        $_SESSION['toast'] = "Karya berhasil diperbarui!";
        redirectDashboard();
    }
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $cek = $pdo->prepare("SELECT * FROM projects WHERE id = ?");
    $cek->execute([$id]);
    $proj = $cek->fetch();

    if ($proj && ($_SESSION['role'] !== 'siswa' || $proj['user_id'] == $_SESSION['user_id'])) {
        if ($proj['gambar'] && file_exists('uploads/' . $proj['gambar'])) unlink('uploads/' . $proj['gambar']);
        $pdo->prepare("DELETE FROM projects WHERE id = ?")->execute([$id]);
        $_SESSION['toast'] = "Karya berhasil dihapus!";
    }
    redirectDashboard();
}
?>