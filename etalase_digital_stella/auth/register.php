<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Siswa - Etalase Digital Stella</title>
    <link rel="icon" type="image/png" href="../assets/img/logo.png">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
<div class="auth-wrapper">
    <div class="auth-card">
        <img src="../assets/img/logo.png" alt="Logo Telkom" class="auth-logo">
        <h1 class="auth-title">Daftar Siswa</h1>
        <p class="auth-subtitle">Buat akun untuk memamerkan karya digitalmu</p>

        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-error"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <form action="../includes/helpers.php" method="POST">
            <input type="hidden" name="action" value="register">
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" placeholder="Masukkan nama lengkap" required>
            </div>
            <div class="form-group">
                <label>Email Sekolah</label>
                <input type="email" name="email" placeholder="nama@stella.sch.id" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" placeholder="Buat password" required>
            </div>
            <button type="submit" class="btn-maroon">Daftar Sekarang</button>
        </form>

        <p class="auth-footer-text">Sudah punya akun? <a href="login.php">Login di sini</a></p>
    </div>
</div>

<!-- FOOTER DENGAN NAMA PEMILIK -->
<footer class="footer">
    <p>&copy; 2026 Etalase Stella Digital &bull; Inez Safira Az-Zahra</p>
</footer>

</body>
</html>