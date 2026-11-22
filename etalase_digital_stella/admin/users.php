<?php
require_once '../middleware/auth_check.php';
authorizeRole(['super_admin']);
require_once '../config/kuis_db.php';

$cari = $_GET['cari'] ?? '';

if (!empty($cari)) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE nama LIKE ? OR email LIKE ? ORDER BY FIELD(role, 'siswa', 'admin', 'super_admin') ASC, created_at DESC");
    $stmt->execute(["%$cari%", "%$cari%"]);
    $users = $stmt->fetchAll();
} else {
    // Urutan: Siswa di atas, Admin di tengah, Super Admin di paling bawah
    $users = $pdo->query("SELECT * FROM users ORDER BY FIELD(role, 'siswa', 'admin', 'super_admin') ASC, created_at DESC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola User - Etalase Digital Stella</title>
    <link rel="icon" type="image/png" href="../assets/img/logo.png">
    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="../assets/css/dashboard.css">
    <link rel="stylesheet" href="../assets/css/popup.css">
</head>
<body>

<nav class="navbar">
    <div class="nav-left">
        <a href="dashboard_sa.php" class="nav-brand">
            <img src="../assets/img/logo.png" alt="Logo Stella">
            <span>Etalase Digital Stella</span>
        </a>
    </div>

    <div class="nav-right">
        <div class="nav-menu-right" style="display: flex; gap: 10px; align-items: center;">
            <a href="dashboard_sa.php" class="nav-link">Galeri Karya</a>
            <a href="users.php" class="nav-link active">Kelola Siswa</a>
        </div>
        
        <span class="user-badge">Halo, <?= htmlspecialchars($_SESSION['nama'] ?? 'User'); ?></span>
        <a href="../auth/logout.php" class="btn-logout">Logout</a>
    </div>
</nav>

<div class="container">
    <div class="top-bar">
        <div class="banner-box">
            <h2>Manajemen Pengguna 👥</h2>
            <p>Kelola data akun siswa, admin, dan super admin aplikasi.</p>
        </div>
        
        <!-- Action Bar: Kolom Pencarian & Tombol Tambah User -->
        <div class="action-bar">
            <form method="GET" action="" class="search-wrapper">
                <input type="text" name="cari" class="search-input" placeholder="Cari user..." value="<?= htmlspecialchars($_GET['cari'] ?? '') ?>">
                
                <?php if (empty($_GET['cari'])): ?>
                    <button type="submit" class="btn-search">Cari</button>
                <?php else: ?>
                    <a href="users.php" class="btn-reset">Reset</a>
                <?php endif; ?>
            </form>
            <button class="btn-add" onclick="openAddUser()">+ Tambah User</button>
        </div>
    </div>

    <?php if (!empty($cari)): ?>
        <div style="margin-bottom: 18px; font-size: 1rem; color: #4A0404; font-weight: 500;">
            🔍 Hasil penelusuran untuk: &ldquo;<strong><?= htmlspecialchars($cari) ?></strong>&rdquo;
        </div>
    <?php endif; ?>

    <h3 class="section-title">Daftar Pengguna Sistem</h3>
    
    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>NAMA LENGKAP</th>
                    <th>EMAIL</th>
                    <th>ROLE / HAK AKSES</th>
                    <th style="text-align: center; width: 140px;">AKSI</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($users)): ?>
                    <?php $no = 1; foreach ($users as $u): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><strong><?= htmlspecialchars($u['nama']) ?></strong></td>
                        <td style="color: #555;"><?= htmlspecialchars($u['email']) ?></td>
                        <td>
                            <span class="role-pill"><?= strtoupper(str_replace('_', ' ', $u['role'])) ?></span>
                        </td>
                        <td style="text-align: center;">
                            <div style="display: flex; gap: 6px; justify-content: center;">
                                <button class="btn-edit" onclick='openEditUser(<?= json_encode($u) ?>)' style="padding: 4px 10px; font-size: 0.78rem; border-radius: 6px;">Edit</button>
                                <?php if ($u['id'] != $_SESSION['user_id'] && $u['role'] !== 'super_admin'): ?>
                                    <a href="../includes/navbar.php?delete=<?= $u['id'] ?>" class="btn-delete" onclick="return confirm('Hapus user ini?')" style="padding: 4px 10px; font-size: 0.78rem; text-decoration: none; display: inline-flex; align-items: center; justify-content: center; border-radius: 6px;">Hapus</a>
                                <?php endif; ?>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 30px; color: #666;">
                            Tidak ada pengguna yang sesuai dengan kata kunci "<strong><?= htmlspecialchars($cari) ?></strong>".
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Tambah / Edit User -->
<div id="userModal" class="modal" onclick="closeModalOnBg(event, 'userModal')">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('userModal')">&times;</button>
        <h2 id="userModalTitle" class="auth-title">Tambah User Baru</h2>
        <form action="../includes/navbar.php" method="POST">
            <input type="hidden" name="action" id="userAction" value="create">
            <input type="hidden" name="id" id="userId">
            
            <div class="form-group">
                <label>Nama Lengkap</label>
                <input type="text" name="nama" id="userNama" required>
            </div>
            
            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" id="userEmail" required>
            </div>

            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" id="userPassword" placeholder="Kosongkan jika tidak ingin mengubah password">
                <small style="color: #666; font-size: 0.78rem; display: block; margin-top: 4px;">*Wajib diisi saat tambah user baru.</small>
            </div>

            <div class="form-group">
                <label>Role / Akses</label>
                <select name="role" id="userRole" required style="width: 100%; padding: 10px; border: 1.5px solid #D5BDBD; border-radius: 8px; font-size: 0.9rem; outline: none; background: #fff;">
                    <option value="siswa">SISWA</option>
                    <option value="admin">ADMIN</option>
                    <option value="super_admin">SUPER ADMIN</option>
                </select>
            </div>
            
            <button type="submit" class="btn-maroon" style="width: 100%; padding: 11px; background: #4A0404; color: #fff; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">Simpan User</button>
        </form>
    </div>
</div>

<footer class="footer">
    <p>&copy; 2026 Etalase Stella Digital &bull; Inez Safira Az-Zahra</p>
</footer>

<!-- Memuat file JavaScript utama -->
<script src="../assets/js/popup.js"></script>
</body>
</html>