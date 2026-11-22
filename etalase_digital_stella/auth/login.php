<?php session_start(); ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Etalase Digital Stella</title>
    <link rel="icon" type="image/png" href="../assets/img/logo.png">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <img src="../assets/img/logo.png" alt="Logo Telkom" class="auth-logo">
        <h1 class="auth-title">Etalase Digital Stella</h1>
        <p class="auth-subtitle">Wadah Inovasi & Karya Digital Siswa SMK Telkom</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success"><?= htmlspecialchars($_GET['success']) ?></div>
        <?php endif; ?>

        <form action="../includes/helpers.php" method="POST">
            <input type="hidden" name="action" value="login">
            <div class="form-group">
                <label>Email Sekolah</label>
                <input type="email" name="email" placeholder="nama@stella.sch.id" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Masukkan password" required>
            </div>
            <button type="submit" class="btn-maroon">Login</button>
        </form>

        <p class="auth-footer-text">Belum punya akun? <a href="register.php">Daftar Siswa</a></p>
    </div>
</div>

<!-- FOOTER DENGAN NAMA PEMILIK -->
<footer class="footer">
    <p>&copy; 2026 Etalase Stella Digital &bull; Inez Safira Az-Zahra</p>
</footer>

</body>
</html>