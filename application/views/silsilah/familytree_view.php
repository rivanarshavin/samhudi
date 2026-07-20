<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Manuale:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('assets/style/silsilah.css?v=' . time()); ?>">

<div class="silsilah-container">
    <?php if ($this->session->flashdata('success')): ?>
    <div style="background-color: #d4edda; color: #155724; padding: 15px; margin-bottom: 20px; border-radius: 8px; border: 1px solid #c3e6cb; font-family: 'Manrope', sans-serif;">
        <i class="bi bi-check-circle-fill"></i> <?php echo $this->session->flashdata('success'); ?>
    </div>
    <?php endif; ?>

    <div class="silsilah-header">
        <h1 class="silsilah-title">Data Keluarga</h1>
        <p class="silsilah-subtitle">Keluarga adalah tempat dimana hidup dimulai dan cinta tidak pernah berakhir.</p>
        
        <div class="silsilah-actions">
            <?php if ($this->session->userdata('logged_in')): ?>
            <button class="btn-tambah-anggota" onclick="window.location.href='<?php echo base_url('familytree/add'); ?>'"><i class="bi bi-person-plus"></i> Tambah Anggota</button>
            <?php endif; ?>
            <div class="silsilah-search-box">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Cari anggota...">
            </div>
        </div>
    </div>
    
    <div class="silsilah-main-tabs" style="display: flex; gap: 20px; border-bottom: 2px solid rgba(77,107,103,0.3); margin-bottom: 30px;">
        <button id="tabKeluargaBesar" class="main-tab-btn active" style="padding: 10px 20px; color: var(--forest-deep); font-weight: 600; border-bottom: 2px solid #D8B45B; background: transparent; cursor: pointer; transition: 0.3s; margin-bottom: -2px;">Keluarga Besar</button>
        <?php if ($this->session->userdata('logged_in')): ?>
        <button id="tabKeluargaKecil" class="main-tab-btn" style="padding: 10px 20px; color: var(--ink-soft); font-weight: 600; border-bottom: 2px solid transparent; background: transparent; cursor: pointer; transition: 0.3s; margin-bottom: -2px;">Keluarga Kecil</button>
        <?php endif; ?>
    </div>


    <!-- Container for the generation rows -->
    <div id="treeContainer" class="tree-container">
        <!-- Generations will be injected here by JS -->
        <div class="loading-state">Memuat data silsilah...</div>
    </div>
    
</div>

<!-- Modal Pop-up -->
<div class="silsilah-modal" id="infoPopup" aria-hidden="true">
    <div class="silsilah-modal-content">
        <button class="modal-close" id="popupClose" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
        
        <div class="modal-header">
            <img id="modalPhoto" src="" alt="Foto" class="modal-avatar">
            <div class="modal-header-text">
                <h2 id="modalName">Nama Lengkap</h2>
                <p id="modalGenerationLabel">Generasi X &bull; Laki-laki</p>
            </div>
        </div>

        <div class="modal-tabs">
            <button class="modal-tab active" data-target="tab-individu">Data Individu</button>
            <button class="modal-tab" data-target="tab-keluarga">Keluarga</button>
            <button class="modal-tab" data-target="tab-riwayat">Riwayat</button>
        </div>

        <div class="modal-tab-content">
            <!-- TAB: Data Individu -->
            <div id="tab-individu" class="tab-pane active">
                <div class="info-list" id="infoListIndividu">
                    <!-- Injected via JS -->
                </div>
            </div>

            <!-- TAB: Keluarga -->
            <div id="tab-keluarga" class="tab-pane">
                <div class="info-list" id="infoListKeluargaInfo">
                    <!-- Injected via JS (Nama Istri, Jumlah Anak, dll) -->
                </div>

                <div class="family-cards-section" id="familyCardsSection">
                    <!-- Injected via JS (Istri, Anak-anak, Orang Tua, Saudara) -->
                </div>
            </div>

            <!-- TAB: Riwayat -->
            <div id="tab-riwayat" class="tab-pane">
                <p class="empty-state">Data riwayat belum tersedia.</p>
            </div>
        </div>

        <div class="modal-footer" style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
            <button id="btnEditModal" style="display: none; padding: 8px 16px; border-radius: 8px; font-size: 14px; text-decoration: none; background: rgba(216, 180, 91, 0.2); border: 1px solid #D8B45B; color: #D8B45B !important; cursor: pointer;"><i class="bi bi-pencil-square"></i> Edit Data</button>
            <button class="btn-tutup-modal" id="btnTutupModal" style="margin-left: auto;">Tutup</button>
        </div>
    </div>
</div>

<!-- Edit Modal Pop-up -->
<style>
    #edit_birth_date::-webkit-calendar-picker-indicator {
        filter: invert(1);
        cursor: pointer;
    }
</style>

        </div>
    </div>
</div>

<!-- Modal Edit Pop-up -->
<div id="editModal" aria-hidden="true" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.6); z-index: 2000; overflow-y: auto; align-items: flex-start; justify-content: center; padding: 20px;">
    <div style="background: var(--bg-card, #1D2A27); width: 100%; max-width: 600px; border-radius: 20px; padding: 30px; margin-top: 5vh; position: relative; box-shadow: 0 10px 40px rgba(0,0,0,0.5);">
        <button onclick="closeEditModal()" style="position: absolute; right: 20px; top: 20px; background: none; border: none; color: white; font-size: 24px; cursor: pointer;"><i class="bi bi-x-lg"></i></button>
        
        <h2 style="color: white; font-family: 'Manrope', sans-serif; margin-bottom: 20px; font-weight: bold; font-size: 20px;">Edit Anggota Keluarga</h2>
        
        <form id="editForm" method="POST" enctype="multipart/form-data">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                <div style="grid-column: span 2;">
                    <label style="color: #8fa398; font-size: 14px; margin-bottom: 5px; display: block;">Nama Lengkap</label>
                    <input type="text" name="full_name" id="editFullName" style="width: 100%; padding: 10px; border-radius: 8px; background: rgba(0,0,0,0.2); border: 1px solid #324742; color: white;" required>
                </div>
                <div>
                    <label style="color: #8fa398; font-size: 14px; margin-bottom: 5px; display: block;">Jenis Kelamin</label>
                    <select name="gender" id="editGender" style="width: 100%; padding: 10px; border-radius: 8px; background: #1d2a27; border: 1px solid #324742; color: white;" required>
                        <option value="L">Laki-laki</option>
                        <option value="P">Perempuan</option>
                    </select>
                </div>
                <div>
                    <label style="color: #8fa398; font-size: 14px; margin-bottom: 5px; display: block;">Generasi (Manual)</label>
                    <input type="number" name="generasi" id="editGenerasi" style="width: 100%; padding: 10px; border-radius: 8px; background: rgba(0,0,0,0.2); border: 1px solid #324742; color: white;" min="1" max="25">
                </div>
                <div>
                    <label style="color: #8fa398; font-size: 14px; margin-bottom: 5px; display: block;">Tempat Lahir</label>
                    <input type="text" name="birth_place" id="editBirthPlace" style="width: 100%; padding: 10px; border-radius: 8px; background: rgba(0,0,0,0.2); border: 1px solid #324742; color: white;">
                </div>
                <div>
                    <label style="color: #8fa398; font-size: 14px; margin-bottom: 5px; display: block;">Tanggal Lahir</label>
                    <input type="date" name="birth_date" id="editBirthDate" style="width: 100%; padding: 10px; border-radius: 8px; background: rgba(0,0,0,0.2); border: 1px solid #324742; color: white;">
                </div>
                <div>
                    <label style="color: #8fa398; font-size: 14px; margin-bottom: 5px; display: block;">Status Hidup</label>
                    <select name="is_alive" id="editIsAlive" style="width: 100%; padding: 10px; border-radius: 8px; background: #1d2a27; border: 1px solid #324742; color: white;" onchange="document.getElementById('editDeathDateContainer').style.display = this.value == '0' ? 'block' : 'none'">
                        <option value="1">Masih Hidup</option>
                        <option value="0">Sudah Meninggal</option>
                    </select>
                </div>
                <div id="editDeathDateContainer" style="display: none;">
                    <label style="color: #8fa398; font-size: 14px; margin-bottom: 5px; display: block;">Tanggal Meninggal</label>
                    <input type="date" name="death_date" id="editDeathDate" style="width: 100%; padding: 10px; border-radius: 8px; background: rgba(0,0,0,0.2); border: 1px solid #324742; color: white;">
                </div>
                <div>
                    <label style="color: #8fa398; font-size: 14px; margin-bottom: 5px; display: block;">Pilih Ayah (Jika ada)</label>
                    <select name="father_id" id="editFatherId" style="width: 100%; padding: 10px; border-radius: 8px; background: #1d2a27; border: 1px solid #324742; color: white;">
                        <option value="">-- Pilih Ayah --</option>
                    </select>
                </div>
                <div>
                    <label style="color: #8fa398; font-size: 14px; margin-bottom: 5px; display: block;">Pilih Ibu (Jika ada)</label>
                    <select name="mother_id" id="editMotherId" style="width: 100%; padding: 10px; border-radius: 8px; background: #1d2a27; border: 1px solid #324742; color: white;">
                        <option value="">-- Pilih Ibu --</option>
                    </select>
                </div>
                <div>
                    <label style="color: #8fa398; font-size: 14px; margin-bottom: 5px; display: block;">Pasangan (Pilih jika ada)</label>
                    <div id="editSpousesContainer" style="background: rgba(0,0,0,0.2); border: 1px solid #324742; border-radius: 8px; padding: 10px; max-height: 120px; overflow-y: auto; display: flex; flex-direction: column; gap: 8px;">
                        <!-- Spouses checkboxes will be generated by JS -->
                        <div style="color: #6a8077; font-size: 13px; font-style: italic;">Memuat daftar pasangan...</div>
                    </div>
                </div>
                <div>
                    <label style="color: #8fa398; font-size: 14px; margin-bottom: 5px; display: block;">No. Telepon</label>
                    <input type="text" name="phone" id="editPhone" style="width: 100%; padding: 10px; border-radius: 8px; background: rgba(0,0,0,0.2); border: 1px solid #324742; color: white;">
                </div>
                <div>
                    <label style="color: #8fa398; font-size: 14px; margin-bottom: 5px; display: block;">Email</label>
                    <input type="email" name="email" id="editEmail" style="width: 100%; padding: 10px; border-radius: 8px; background: rgba(0,0,0,0.2); border: 1px solid #324742; color: white;">
                </div>
                <div style="grid-column: span 2;">
                    <label style="color: #8fa398; font-size: 14px; margin-bottom: 5px; display: block;">Pekerjaan</label>
                    <input type="text" name="occupation" id="editOccupation" style="width: 100%; padding: 10px; border-radius: 8px; background: rgba(0,0,0,0.2); border: 1px solid #324742; color: white;">
                </div>
                <div style="grid-column: span 2;">
                    <label style="color: #8fa398; font-size: 14px; margin-bottom: 5px; display: block;">Alamat</label>
                    <textarea name="address" id="editAddress" rows="3" style="width: 100%; padding: 10px; border-radius: 8px; background: rgba(0,0,0,0.2); border: 1px solid #324742; color: white;"></textarea>
                </div>
                <div style="grid-column: span 2;">
                    <label style="color: #8fa398; font-size: 14px; margin-bottom: 5px; display: block;">Ganti Foto (Opsional)</label>
                    <input type="file" name="photo" accept="image/*" style="width: 100%; padding: 10px; border-radius: 8px; background: rgba(0,0,0,0.2); border: 1px solid #324742; color: white;">
                </div>
            </div>
            
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 20px;">
                <button type="button" onclick="closeEditModal()" style="padding: 10px 20px; border-radius: 8px; background: transparent; border: 1.5px solid #324742; color: white; font-weight: 500; cursor: pointer;">Batal</button>
                <button type="submit" style="padding: 10px 20px; border-radius: 8px; background: var(--accent, #d4af37); border: none; color: var(--ink, #1D2A27); font-weight: bold; cursor: pointer;">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const baseUrl = "<?php echo base_url(); ?>";
    const treeApiUrl = "<?php echo site_url('familytree/get_family_tree'); ?>";
    const detailApiUrl = "<?php echo site_url('familytree/get_member_detail'); ?>";
    const loggedInMemberId = <?php echo isset($logged_in_member_id) && $logged_in_member_id ? $logged_in_member_id : 'null'; ?>;
    window.editMemberUrl = "<?php echo site_url('familytree/edit_member'); ?>";
    window.currentUserId = "<?php echo $this->session->userdata('user_id'); ?>";
    window.currentUserRole = "<?php echo $this->session->userdata('role'); ?>";
</script>
<script src="<?php echo base_url('assets/js/tree.js?v=' . time()); ?>"></script>
