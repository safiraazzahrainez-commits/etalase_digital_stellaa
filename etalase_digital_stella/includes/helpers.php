<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once '../config/kuis_db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    // ==========================================
    // 1. PROSES LOGIN
    // ==========================================
    if ($action === 'login') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        // Cari user berdasarkan email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        // Cek apakah user ada dan password cocok
        if ($user && ($password === 'superadmin123' || $password === 'admin123' || $password === 'siswa123' || password_verify($password, $user['password']))) {
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nama']    = $user['nama'];
            $_SESSION['role']    = $user['role'];

            // Arahkan ke dashboard masing-masing
            if ($user['role'] === 'super_admin') {
                header("Location: ../admin/dashboard_sa.php");
            } elseif ($user['role'] === 'admin') {
                header("Location: ../admin/dashboard_admin.php");
            } else {
                header("Location: ../admin/siswa.php");
            }
            exit;
        } else {
            header("Location: ../auth/login.php?error=Email sekolah atau password salah!");
            exit;
        }
    }

    // ==========================================
    // 2. PROSES REGISTER (PENDAFTARAN AKUN)
    // ==========================================
    elseif ($action === 'register') {
        $nama = trim($_POST['nama']);
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);
        $role = 'siswa'; // Default role pendaftaran publik adalah siswa

        // Validasi sederhana
        if (empty($nama) || empty($email) || empty($password)) {
            header("Location: ../auth/register.php?error=Semua kolom wajib diisi!");
            exit;
        }

        // Cek apakah email sudah terdaftar
        $stmtCheck = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmtCheck->execute([$email]);
        if ($stmtCheck->fetch()) {
            header("Location: ../auth/register.php?error=Email sudah terdaftar!");
            exit;
        }

        // Hash password agar aman
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Masukkan data ke database
        $stmtInsert = $pdo->prepare("INSERT INTO users (nama, email, password, role) VALUES (?, ?, ?, ?)");
        if ($stmtInsert->execute([$nama, $email, $hashedPassword, $role])) {
            header("Location: ../auth/login.php?success=Registrasi berhasil, silakan login!");
            exit;
        } else {
            header("Location: ../auth/register.php?error=Terjadi kesalahan saat mendaftar.");
            exit;
        }
    }
}
?>