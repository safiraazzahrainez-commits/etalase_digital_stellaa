/* =================================================imal
   1. MANAJEMEN MODAL PROJECT (Tambah, Edit, Detail)
   ================================================== */

// Membuka Modal Tambah Project
function openAddProject() {
    const modal = document.getElementById('projectModal');
    if (modal) {
        modal.style.display = 'flex';
        const form = document.querySelector('#projectModal form');
        if (form) form.reset();
        
        const action = document.getElementById('projAction');
        if (action) action.value = 'create';

        const title = document.getElementById('projModalTitle');
        if (title) title.innerText = 'Tambah Project Baru';
        
        const inputId = document.getElementById('projId');
        if (inputId) inputId.value = '';
    }
}

// Membuka Modal Edit Project
function openEditProject(data) {
    const modal = document.getElementById('projectModal');
    if (modal) {
        modal.style.display = 'flex';
        
        const action = document.getElementById('projAction');
        if (action) action.value = 'update';

        const title = document.getElementById('projModalTitle');
        if (title) title.innerText = 'Edit Project';

        // Masukkan data lama ke form
        if(document.getElementById('projId')) document.getElementById('projId').value = data.id || '';
        if(document.getElementById('projNama')) document.getElementById('projNama').value = data.nama_project || '';
        if(document.getElementById('projDesc')) document.getElementById('projDesc').value = data.deskripsi || '';
        if(document.getElementById('projGithub')) document.getElementById('projGithub').value = data.link_github || '';
        if(document.getElementById('projLive')) document.getElementById('projLive').value = data.link_live || '';
    }
}

// Membuka Modal Detail Project (Saat Card diklik)
function openDetail(data) {
    const modal = document.getElementById('detailModal');
    if (modal) {
        modal.style.display = 'flex';
        
        if(document.getElementById('detTitle')) document.getElementById('detTitle').innerText = data.nama_project || '-';
        if(document.getElementById('detMeta')) document.getElementById('detMeta').innerHTML = `Oleh: <strong>${data.pembuat || '-'}</strong><br>Diupload: ${data.created_at || '-'}`;
        if(document.getElementById('detDesc')) document.getElementById('detDesc').innerText = data.deskripsi || '-';
        
        // Gambar
        const imgEl = document.getElementById('detImg');
        if (imgEl) {
            if (data.gambar && data.gambar.trim() !== '') {
                imgEl.src = '../uploads/' + data.gambar;
                imgEl.style.display = 'block';
            } else {
                imgEl.style.display = 'none';
            }
        }

        // Tombol GitHub
        const ghEl = document.getElementById('detGithub');
        if (ghEl) {
            if (data.link_github && data.link_github.trim() !== '') {
                ghEl.href = data.link_github;
                ghEl.style.display = 'inline-block';
            } else {
                ghEl.style.display = 'none';
            }
        }

        // Tombol Live Demo
        const liveEl = document.getElementById('detLive');
        if (liveEl) {
            if (data.link_live && data.link_live.trim() !== '') {
                liveEl.href = data.link_live;
                liveEl.style.display = 'inline-block';
            } else {
                liveEl.style.display = 'none';
            }
        }
    }
}


/* =================================================
   2. MANAJEMEN MODAL USER (Kelola User - Super Admin)
   ================================================== */

// Membuka Modal Tambah User
function openAddUser() {
    const modal = document.getElementById('userModal');
    if (modal) {
        modal.style.display = 'flex';
        const title = document.getElementById('userModalTitle');
        if (title) title.innerText = 'Tambah User Baru';

        const action = document.getElementById('userAction');
        if (action) action.value = 'create';

        const id = document.getElementById('userId');
        if (id) id.value = '';

        const nama = document.getElementById('userNama');
        if (nama) nama.value = '';

        const email = document.getElementById('userEmail');
        if (email) email.value = '';

        const pass = document.getElementById('userPassword');
        if (pass) {
            pass.value = '';
            pass.required = true;
        }

        const role = document.getElementById('userRole');
        if (role) role.value = 'siswa';
    }
}

// Membuka Modal Edit User
function openEditUser(data) {
    const modal = document.getElementById('userModal');
    if (modal) {
        modal.style.display = 'flex';
        const title = document.getElementById('userModalTitle');
        if (title) title.innerText = 'Edit User';

        const action = document.getElementById('userAction');
        if (action) action.value = 'update';

        const id = document.getElementById('userId');
        if (id) id.value = data.id || '';

        const nama = document.getElementById('userNama');
        if (nama) nama.value = data.nama || '';

        const email = document.getElementById('userEmail');
        if (email) email.value = data.email || '';

        const pass = document.getElementById('userPassword');
        if (pass) {
            pass.value = '';
            pass.required = false; // Password opsional saat diedit
        }

        const role = document.getElementById('userRole');
        if (role) role.value = data.role || 'siswa';
    }
}


/* =================================================
   3. FUNGSI UMUM (Tutup Modal / Background Click)
   ================================================== */

// Fungsi Menutup Modal Berdasarkan ID
function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = 'none';
    }
}

// Fungsi Menutup Modal jika klik di luar kotak modal (background transparan)
function closeModalOnBg(event, modalId) {
    if (event.target.id === modalId) {
        closeModal(modalId);
    }
}