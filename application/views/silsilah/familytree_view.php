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
            <?php if ($this->session->userdata('logged_in')): ?>
            <a href="<?php echo site_url('familytree/add'); ?>" class="btn-tambah-anggota" style="text-decoration:none;">+ Tambah Anggota</a>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="silsilah-main-tabs" style="display: flex; gap: 20px; border-bottom: 2px solid rgba(77,107,103,0.3); margin-bottom: 30px;">
        <button id="tabKeluargaBesar" class="main-tab-btn active" style="padding: 10px 20px; color: #fff; font-weight: 600; border-bottom: 2px solid #D8B45B; background: transparent; cursor: pointer; transition: 0.3s; margin-bottom: -2px;">Keluarga Besar</button>
        <?php if ($this->session->userdata('logged_in')): ?>
        <button id="tabKeluargaKecil" class="main-tab-btn" style="padding: 10px 20px; color: rgba(255,255,255,0.6); font-weight: 600; border-bottom: 2px solid transparent; background: transparent; cursor: pointer; transition: 0.3s; margin-bottom: -2px;">Keluarga Kecil</button>
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

<script>
    const baseUrl = "<?php echo base_url(); ?>";
    const treeApiUrl = "<?php echo site_url('familytree/get_family_tree'); ?>";
    const detailApiUrl = "<?php echo site_url('familytree/get_member_detail'); ?>";
    const loggedInMemberId = <?php echo isset($logged_in_member_id) && $logged_in_member_id ? $logged_in_member_id : 'null'; ?>;
</script>
<script src="<?php echo base_url('assets/js/tree.js?v=' . time()); ?>"></script>