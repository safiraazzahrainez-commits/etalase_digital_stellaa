<!-- Modal Detail Project (Pop-up saat Card diklik) -->
<div id="detailModal" class="modal" onclick="closeModalOnBg(event, 'detailModal')">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('detailModal')">&times;</button>
        <h2 id="detTitle" class="auth-title" style="margin-bottom: 6px; color: #4A0404;"></h2>
        <p id="detMeta" class="card-meta" style="margin-bottom: 14px; border-top: none; padding-top: 0;"></p>
        
        <!-- Gambar Project -->
        <img id="detImg" src="" alt="Preview Karya" class="popup-img" style="width: 100%; max-height: 260px; object-fit: cover; border-radius: 8px; margin-bottom: 15px; display: none;">
        
        <!-- Deskripsi dengan Word Wrap Otomatis -->
        <p id="detDesc" style="margin: 12px 0; line-height: 1.6; font-size: 0.95rem; color: #444; overflow-wrap: break-word; word-break: break-word;"></p>
        
        <div class="card-links" style="margin-top: 20px;">
            <a id="detGithub" href="#" target="_blank" class="btn-link btn-github" style="display: none;">Repository GitHub</a>
            <a id="detLive" href="#" target="_blank" class="btn-link btn-live" style="display: none;">Live Demo</a>
        </div>
    </div>
</div>

<!-- Modal Tambah / Edit Project -->
<div id="projectModal" class="modal" onclick="closeModalOnBg(event, 'projectModal')">
    <div class="modal-content">
        <button class="modal-close" onclick="closeModal('projectModal')">&times;</button>
        <h2 id="projModalTitle" class="auth-title">Tambah Project Baru</h2>
        <form action="../project_tambah.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" id="projAction" value="create">
            <input type="hidden" name="id" id="projId">
            
            <div class="form-group">
                <label>Nama Project</label>
                <input type="text" name="nama_project" id="projNama" required>
            </div>
            
            <div class="form-group">
                <label>Deskripsi Karya</label>
                <textarea name="deskripsi" id="projDesc" rows="3" required style="overflow-wrap: break-word; word-break: break-word;"></textarea>
            </div>
            
            <div class="form-group">
                <label>Gambar Karya (Thumbnail)</label>
                <input type="file" name="gambar" id="projGambar" accept="image/*">
                <small style="color: #666; font-size: 0.78rem; display: block; margin-top: 4px;">Kosongkan jika tidak ingin mengubah gambar saat edit.</small>
            </div>
            
            <div class="form-group">
                <label>Link Repository GitHub</label>
                <input type="url" name="link_github" id="projGithub">
            </div>
            
            <div class="form-group">
                <label>Link Live Preview</label>
                <input type="url" name="link_live" id="projLive">
            </div>
            
            <button type="submit" class="btn-maroon">Simpan Karya</button>
        </form>
    </div>
</div>