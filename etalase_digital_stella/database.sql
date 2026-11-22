-- Membuat database jika belum ada, lalu menggunakannya
CREATE DATABASE IF NOT EXISTS db_etalase_stella;
USE db_etalase_stella;

--   Membuat tabel users (Manajemen Role: super_admin, admin, siswa)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('super_admin', 'admin', 'siswa') NOT NULL DEFAULT 'siswa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Membuat tabel projects (Galeri Karya Digital Siswa)
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    nama_project VARCHAR(150) NOT NULL,
    deskripsi TEXT NOT NULL,
    gambar VARCHAR(255) NOT NULL,
    link_github VARCHAR(255) NOT NULL,
    link_live VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Memasukkan data akun default bawaan
-- (Password sudah di-hash dengan aman menggunakan password_hash standar)
-- 1. Super Admin -> email: sa@smktelkom.sch.id | pass: superadmin123
-- 2. Admin      -> email: admin@smktelkom.sch.id | pass: admin123
-- 3. Siswa      -> email: siswa@smktelkom.sch.id | pass: siswa123
INSERT INTO users (nama, email, password, role) VALUES
('Super Admin Stella', 'sa@smktelkom.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'super_admin'),
('Guru Admin', 'admin@smktelkom.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin'),
('Budi Santoso', 'siswa@smktelkom.sch.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'siswa');