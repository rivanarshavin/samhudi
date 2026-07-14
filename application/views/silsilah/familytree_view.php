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
            <a href="<?php echo site_url('familytree/add'); ?>" class="btn-tambah-anggota" style="text-decoration:none;">+ Tambah Anggota</a>
        </div>
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

        <div class="modal-footer">
            <button class="btn-tutup-modal" id="btnTutupModal">Tutup</button>
        </div>
    </div>
</div>

<script>
    const baseUrl = "<?php echo base_url(); ?>";
    const treeApiUrl = "<?php echo site_url('familytree/get_family_tree'); ?>";
    const detailApiUrl = "<?php echo site_url('familytree/get_member_detail'); ?>";
</script>
<script src="<?php echo base_url('assets/js/tree.js?v=' . time()); ?>"></script>