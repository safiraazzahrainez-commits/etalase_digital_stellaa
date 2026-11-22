<?php
require_once '../middleware/auth_check.php';
authorizeRole(['super_admin']);
require_once '../config/kuis_db.php';

$cari = $_GET['cari'] ?? '';

if (!empty($cari)) {
    $stmt = $pdo->prepare("SELECT p.*, u.nama AS pembuat FROM projects p JOIN users u ON p.user_id = u.id WHERE p.nama_project LIKE ? OR u.nama LIKE ? ORDER BY p.created_at DESC");
    $stmt->execute(["%$cari%", "%$cari%"]);
    $projects = $stmt->fetchAll();
} else {
    $projects = $pdo->query("SELECT p.*, u.nama AS pembuat FROM projects p JOIN users u ON p.user_id = u.id ORDER BY p.created_at DESC")->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Super Admin - Etalase Digital Stella</title>
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
            <a href="dashboard_sa.php" class="nav-link active">Galeri Karya</a>
            <a href="users.php" class="nav-link">Kelola Siswa</a>
        </div>
        
        <span class="user-badge">Halo, <?= htmlspecialchars($_SESSION['nama'] ?? 'User'); ?></span>
        <a href="../auth/logout.php" class="btn-logout">Logout</a>
    </div>
</nav>

<div class="container">
    <div class="top-bar">
        <div class="banner-box">
            <h2>Halo, <?= htmlspecialchars($_SESSION['nama']) ?>! 👋</h2>
            <p>Kelola seluruh galeri karya digital siswa dan manajemen pengguna.</p>
        </div>
        
        <!-- Action Bar: Kolom Pencarian dengan Tombol Dinamis (Cari / Reset) -->
        <div class="action-bar">
            <form method="GET" action="" class="search-wrapper">
                <input type="text" name="cari" class="search-input" placeholder="Cari karya..." value="<?= htmlspecialchars($_GET['cari'] ?? '') ?>">
                
                <?php if (empty($_GET['cari'])): ?>
                    <button type="submit" class="btn-search">Cari</button>
                <?php else: ?>
                    <a href="dashboard_sa.php" class="btn-reset">Reset</a>
                <?php endif; ?>
            </form>
            <button class="btn-add" onclick="openAddProject()">+ Tambah Project</button>
        </div>
    </div>

    <?php if (!empty($cari)): ?>
        <div style="margin-bottom: 18px; font-size: 1rem; color: #4A0404; font-weight: 500;">
            🔍 Hasil penelusuran untuk: &ldquo;<strong><?= htmlspecialchars($cari) ?></strong>&rdquo;
        </div>
    <?php endif; ?>

    <h3 class="section-title">Galeri Project Siswa</h3>
    <div class="project-grid" id="projectGrid">
        <?php if (!empty($projects)): ?>
            <?php foreach ($projects as $p): ?>
            <div class="card project-item" onclick='openDetail(<?= json_encode($p) ?>)'>
                <?php if (!empty($p['gambar'])): ?>
                    <img src="../uploads/<?= htmlspecialchars($p['gambar']) ?>" alt="Karya" class="card-img">
                <?php endif; ?>
                <div class="card-body">
                    <h4 class="card-title"><?= htmlspecialchars($p['nama_project']) ?></h4>
                    <p class="card-desc"><?= htmlspecialchars($p['deskripsi']) ?></p>
                    <div class="card-links" onclick="event.stopPropagation()">
                        <?php if (!empty($p['link_github'])): ?>
                            <a href="<?= htmlspecialchars($p['link_github']) ?>" target="_blank" class="btn-link btn-github">GitHub</a>
                        <?php endif; ?>
                        <?php if (!empty($p['link_live'])): ?>
                            <a href="<?= htmlspecialchars($p['link_live']) ?>" target="_blank" class="btn-link btn-live">Live</a>
                        <?php endif; ?>
                    </div>
                    <div class="card-meta">
                        Oleh: <strong><?= htmlspecialchars($p['pembuat']) ?></strong><br>
                        Diupload: <?= date('d M Y', strtotime($p['created_at'])) ?>
                    </div>
                    <div class="card-actions" onclick="event.stopPropagation()">
                        <button class="btn-edit" onclick='openEditProject(<?= json_encode($p) ?>)'>Edit</button>
                        <a href="../project_hapus.php?delete=<?= $p['id'] ?>" class="btn-delete" onclick="return confirm('Hapus karya ini?')">Hapus</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px; background: #ffffff; border-radius: 12px; border: 1.5px dashed #D5BDBD;">
                <span style="font-size: 2.5rem;">📭</span>
                <h4 style="color: #4A0404; margin: 10px 0 5px; font-size: 1.1rem;">Oops, karya tidak ditemukan!</h4>
                <p style="color: #666; font-size: 0.9rem; margin-bottom: 15px;">Tidak ada karya yang sesuai dengan kata kunci "<strong><?= htmlspecialchars($cari) ?></strong>".</p>
                <a href="dashboard_sa.php" class="btn-reset" style="display: inline-block; text-decoration: none;">Kembali ke Semua Karya</a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php include 'project.php'; ?>

<?php if (isset($_SESSION['toast'])): ?>
    <div class="toast-container">
        <div class="toast-alert success">
            <span>✅ <?= $_SESSION['toast']; unset($_SESSION['toast']); ?></span>
        </div>
    </div>
<?php endif; ?>

<footer class="footer">
    <p>&copy; 2026 Etalase Stella Digital &bull; Inez Safira Az-Zahra</p>
</footer>

<script src="../assets/js/popup.js"></script>
</body>
</html>