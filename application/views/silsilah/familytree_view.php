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
            <div class="silsilah-search-box">
                <i class="bi bi-search"></i>
                <input type="text" placeholder="Cari anggota...">
            </div>
        </div>
    </div>
    
    <div class="silsilah-main-tabs" style="display: flex; gap: 20px; border-bottom: 2px solid rgba(77,107,103,0.3); margin-bottom: 30px;">
        <button id="tabKeluargaBesar" class="main-tab-btn active" style="padding: 10px 20px; color: #fff; font-weight: 600; border-bottom: 2px solid #D8B45B; background: transparent; cursor: pointer; transition: 0.3s; margin-bottom: -2px;">Keluarga Besar</button>
        <?php if ($this->session->userdata('logged_in')): ?>
        <button id="tabKeluargaKecil" class="main-tab-btn" style="padding: 10px 20px; color: rgba(255,255,255,0.6); font-weight: 600; border-bottom: 2px solid transparent; background: transparent; cursor: pointer; transition: 0.3s; margin-bottom: -2px;">Keluarga Kecil</button>
        <?php endif; ?>
    </div>

    <div class="silsilah-tabs" style="display: flex; gap: 30px; border-bottom: 1px solid rgba(255,255,255,0.1); margin-bottom: 30px; padding: 0 20px;">
        <button id="tabBesar" onclick="switchTab('besar')" style="padding: 15px 0; background: none; border: none; font-family: 'Manrope', sans-serif; font-weight: 700; font-size: 16px; color: var(--accent, #d4af37); border-bottom: 3px solid var(--accent, #d4af37); cursor: pointer; transition: 0.3s;">Keluarga Besar</button>
        <?php if ($this->session->userdata('logged_in')): ?>
        <button id="tabInti" onclick="switchTab('inti')" style="padding: 15px 0; background: none; border: none; font-family: 'Manrope', sans-serif; font-weight: 500; font-size: 16px; color: #8fa398; border-bottom: 3px solid transparent; cursor: pointer; transition: 0.3s;" onmouseover="this.style.color='var(--accent, #d4af37)'" onmouseout="if(!this.classList.contains('active-tab')) this.style.color='#8fa398'">Keluarga Inti</button>
        <?php endif; ?>
    </div>

    <!-- Container for the generation rows -->
    <div id="treeContainer" class="tree-container">
        <!-- Generations will be injected here by JS -->
        <div class="loading-state">Memuat data silsilah...</div>
    </div>
    
    <!-- Container for Keluarga Inti -->
    <div id="intiContainer" style="display: none; padding: 20px;">
        <div class="generation-cards" id="intiCards" style="flex-wrap: wrap; justify-content: flex-start; gap: 20px;"></div>
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
<div class="silsilah-modal" id="editPopup" aria-hidden="true" style="z-index: 1050;">
    <div class="silsilah-modal-content" style="max-height: 90vh; display: flex; flex-direction: column;">
        <button class="modal-close" id="editPopupClose" aria-label="Tutup"><i class="bi bi-x-lg"></i></button>
        
        <div class="modal-header" style="border-bottom: 1px solid rgba(255,255,255,0.1); padding: 20px 24px; flex-shrink: 0;">
            <h2 style="color: #fff; font-size: 18px; margin: 0;"><i class="bi bi-pencil-square" style="color: #D8B45B;"></i> Edit Data Profil</h2>
        </div>

        <div style="overflow-y: auto; padding: 24px;">
            <form id="inlineEditForm" enctype="multipart/form-data">
                <input type="hidden" name="id" id="edit_id">
                
                <div style="margin-bottom: 15px;">
                    <label style="color: #D8B45B; font-size: 12px; display: block; margin-bottom: 5px; font-weight: 600;">Nama Lengkap</label>
                    <input type="text" name="full_name" id="edit_full_name" required style="width: 100%; background: #1A2824; border: 1px solid rgba(77,107,103,0.3); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 15px;">
                    <div>
                        <label style="color: #D8B45B; font-size: 12px; display: block; margin-bottom: 5px; font-weight: 600;">Jenis Kelamin</label>
                        <select name="gender" id="edit_gender" required style="width: 100%; background: #1A2824; border: 1px solid rgba(77,107,103,0.3); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 14px; appearance: none;">
                            <option value="L">Laki-laki</option>
                            <option value="P">Perempuan</option>
                        </select>
                    </div>
                    <div>
                        <label style="color: #D8B45B; font-size: 12px; display: block; margin-bottom: 5px; font-weight: 600;">Tanggal Lahir</label>
                        <input type="date" name="birth_date" id="edit_birth_date" style="width: 100%; background: #1A2824; border: 1px solid rgba(77,107,103,0.3); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 14px; color-scheme: dark;">
                    </div>
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="color: #D8B45B; font-size: 12px; display: block; margin-bottom: 5px; font-weight: 600;">Tempat Lahir</label>
                    <input type="text" name="birth_place" id="edit_birth_place" style="width: 100%; background: #1A2824; border: 1px solid rgba(77,107,103,0.3); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="color: #D8B45B; font-size: 12px; display: block; margin-bottom: 5px; font-weight: 600;">Pekerjaan</label>
                    <input type="text" name="occupation" id="edit_occupation" style="width: 100%; background: #1A2824; border: 1px solid rgba(77,107,103,0.3); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 14px;">
                </div>

                <div style="margin-bottom: 15px;">
                    <label style="color: #D8B45B; font-size: 12px; display: block; margin-bottom: 5px; font-weight: 600;">Alamat Domisili</label>
                    <textarea name="address" id="edit_address" rows="3" style="width: 100%; background: #1A2824; border: 1px solid rgba(77,107,103,0.3); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 14px;"></textarea>
                </div>

                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-bottom: 20px;">
                    <div>
                        <label style="color: #D8B45B; font-size: 12px; display: block; margin-bottom: 5px; font-weight: 600;">No. HP</label>
                        <input type="text" name="phone" id="edit_phone" style="width: 100%; background: #1A2824; border: 1px solid rgba(77,107,103,0.3); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="color: #D8B45B; font-size: 12px; display: block; margin-bottom: 5px; font-weight: 600;">Email</label>
                        <input type="email" name="email" id="edit_email" style="width: 100%; background: #1A2824; border: 1px solid rgba(77,107,103,0.3); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 14px;">
                    </div>
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="color: #D8B45B; font-size: 12px; display: block; margin-bottom: 5px; font-weight: 600;">Ubah Foto (Biarkan kosong jika tidak ingin diubah)</label>
                    <input type="file" name="photo" id="edit_photo" accept="image/*" style="width: 100%; background: #1A2824; border: 1px solid rgba(77,107,103,0.3); color: #fff; padding: 10px 14px; border-radius: 8px; font-size: 12px;">
                </div>

                <div id="editAlert" style="display: none; padding: 10px; border-radius: 8px; margin-bottom: 15px; font-size: 14px;"></div>
            </form>
        </div>

        <div class="modal-footer" style="display: flex; justify-content: flex-end; gap: 10px; border-top: 1px solid rgba(255,255,255,0.1); padding: 15px 24px; flex-shrink: 0; background: var(--member-card-bg); border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
            <button type="button" class="btn-tutup-modal" id="btnBatalEdit" style="margin: 0; padding: 8px 20px;">Batal</button>
            <button type="submit" form="inlineEditForm" id="btnSimpanEdit" style="background: #D8B45B; color: #15201E; border: none; padding: 8px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; transition: 0.3s; box-shadow: 0 4px 10px rgba(216,180,91,0.2);">Simpan</button>
        </div>
    </div>
</div>
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