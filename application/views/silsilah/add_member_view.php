<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Manuale:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo base_url('assets/style/wizard.css?v=' . time()); ?>">

<div class="wizard-wrapper">
<div class="wizard-container">
    <!-- Progress Indicator (Hidden on Intro) -->
    <div class="wizard-header" id="wizardHeader" style="display: none;">
        <div class="step-indicator" id="stepIndicator">
            <span class="step-num">1</span>
            <span class="step-title" id="stepTitle">Siapa Kamu?</span>
        </div>
        <div class="leaves-progress">
            <img src="<?php echo base_url('assets/img/leaf-1.svg'); ?>" class="leaf" id="leaf1" alt="">
            <div class="leaf-line"></div>
            <img src="<?php echo base_url('assets/img/leaf-2.svg'); ?>" class="leaf" id="leaf2" alt="">
            <div class="leaf-line"></div>
            <img src="<?php echo base_url('assets/img/leaf-3.svg'); ?>" class="leaf" id="leaf3" alt="">
        </div>
    </div>

    <!-- Step 0: Intro -->
    <div class="wizard-step active" id="step0">
        <h1 class="intro-title">Bergabung ke Keluarga</h1>
        <div class="intro-image">
            <img src="<?php echo base_url('assets/images/Family.png'); ?>" alt="Family Illustration" style="max-width: 100%; height: auto; object-fit: contain;">
        </div>
        <p class="intro-text">Mari tambahkan dirimu<br>ke dalam silsilah keluarga</p>
        <button class="btn-primary" onclick="nextStep(1)">Mulai Sekarang <i class="bi bi-chevron-right"></i></button>
    </div>

    <!-- Step 1: Role -->
    <div class="wizard-step" id="step1">
        <p class="step-desc">Pilih peranmu<br>dalam keluarga inti</p>
        <div class="role-cards">
            <label class="role-card">
                <input type="radio" name="role" value="anak" onchange="enableNext(1)">
                <div class="role-card-content">
                    <i class="bi bi-people-fill icon-role"></i>
                    <span>Saya adalah</span>
                    <strong>Anak</strong>
                </div>
            </label>
            <label class="role-card">
                <input type="radio" name="role" value="pasangan" onchange="enableNext(1)">
                <div class="role-card-content">
                    <i class="bi bi-hearts icon-role" style="color: #ff6b8b;"></i>
                    <span>Saya adalah</span>
                    <strong>Pasangan</strong>
                </div>
            </label>
            <label class="role-card">
                <input type="radio" name="role" value="orangtua" onchange="enableNext(1)">
                <div class="role-card-content">
                    <i class="bi bi-person-hearts icon-role"></i>
                    <span>Saya adalah</span>
                    <strong>Orang Tua</strong>
                </div>
            </label>
        </div>
        <div class="step-footer">
            <button class="btn-primary" id="btnNext1" onclick="nextStep(2)" disabled>Lanjut <i class="bi bi-chevron-right"></i></button>
        </div>
    </div>

    <!-- Step 2: Relation -->
    <div class="wizard-step" id="step2">
        <p class="step-desc" id="relationQuestion">Siapa orang tua kamu?</p>
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="searchMember" placeholder="Cari nama.." onkeyup="searchMember(this.value)">
        </div>
        <div class="member-list" id="memberList">
            <!-- List goes here -->
        </div>
        <div class="step-footer">
            <button class="btn-secondary" onclick="prevStep(1)"><i class="bi bi-chevron-left"></i> Kembali</button>
            <button class="btn-primary" id="btnNext2" onclick="nextStep(3)" disabled>Lanjut <i class="bi bi-chevron-right"></i></button>
        </div>
    </div>

    <!-- Step 3: Profile Info -->
    <div class="wizard-step" id="step3">
        <div class="profile-upload" onclick="document.getElementById('photoInput').click()" style="cursor: pointer;">
            <input type="file" id="photoInput" accept="image/*" style="display: none;" onchange="previewUpload(this)">
            <div class="upload-circle">
                <img src="https://placehold.co/100x100/CBD9CF/4A6055?text=Foto" id="previewPhoto" alt="">
                <div class="upload-badge"><i class="bi bi-camera"></i></div>
            </div>
            <p>Upload dirimu</p>
        </div>
        
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" id="fullName" placeholder="Masukkan nama lengkap" oninput="checkForm3()">
        </div>
        
        <div class="form-group">
            <label>Tanggal Lahir</label>
            <input type="date" id="birthDate" onchange="checkForm3()">
        </div>
        
        <div class="gender-selection">
            <label class="gender-radio">
                <input type="radio" name="gender" value="L" onchange="checkForm3()">
                <span>Laki-laki</span>
            </label>
            <label class="gender-radio">
                <input type="radio" name="gender" value="P" onchange="checkForm3()">
                <span>Perempuan</span>
            </label>
        </div>

        <div id="errorMsg" style="color: #b3543f; font-size: 13px; text-align: center; margin-bottom: 10px; display: none;"></div>

        <div class="step-footer">
            <button class="btn-secondary" onclick="prevStep(2)"><i class="bi bi-chevron-left"></i> Kembali</button>
            <button class="btn-primary" id="btnSubmit" onclick="submitForm()" disabled>Selesai <i class="bi bi-check2"></i></button>
        </div>
    </div>

    <!-- Step 4: Berhasil -->
    <div class="wizard-step" id="step4">
        <div style="text-align: center; padding: 20px 0;">
            <h2 style="font-size: 24px; font-weight: 700; color: var(--ink); margin-bottom: 20px;">Berhasil</h2>
            
            <div class="success-avatar-wrapper">
                <img src="" id="successPhoto" alt="" class="success-avatar">
            </div>
            
            <div style="width: 40px; height: 2px; background-color: var(--forest-deep); margin: 25px auto; border-radius: 2px; opacity: 0.5;"></div>
            
            <p style="font-size: 15px; font-weight: 600; color: var(--ink); margin-bottom: 5px;">
                Selamat, <span id="successName"></span>
            </p>
            <p style="font-size: 14px; color: #4A6055; margin-bottom: 30px;">
                Kamu telah bergabung dalam<br>keluarga H.M Samhudi
            </p>

            <button class="btn-primary" style="width: 100%;" onclick="loadMiniTree()">Lanjut <i class="bi bi-chevron-right"></i></button>
        </div>
    </div>

    <!-- Step 5: Posisi Keluarga Inti -->
    <div class="wizard-step" id="step5">
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="font-size: 20px; font-weight: 700; color: var(--ink); margin: 0;">Data Berhasil disimpan</h2>
            <p style="font-size: 13px; color: var(--text-muted); margin-top: 5px;">Berikut adalah posisi Anda dalam keluarga inti</p>
        </div>

        <div class="mini-tree-container">
            <!-- Node Orang Tua -->
            <div class="mini-node-group" id="nodeOrangTua" style="display: none;">
                <div class="mini-node-label">Orang Tua</div>
                <div class="mini-node-cards" id="cardsOrangTua">
                    <!-- Dinamis render HTML di JS -->
                </div>
            </div>

            <!-- Garis Vertikal (dari ortu ke Anda) -->
            <div class="mini-line-v" id="lineOrtuToAnda" style="display: none;"></div>

            <!-- Node Anda -->
            <div class="mini-node-group">
                <div class="mini-node-label" style="margin-top: 0;">Anda</div>
                <div class="mini-node-cards" id="cardsAnda">
                    <div class="mini-card mini-card-lg">
                        <img src="" id="miniPhotoAnda" alt="">
                    </div>
                </div>
            </div>

            <!-- Garis Vertikal (dari Anda ke bawah) -->
            <div class="mini-line-v" id="lineAndaToBottom" style="display: none;"></div>

            <!-- Baris Bawah (Pasangan & Anak) -->
            <div class="mini-bottom-row" id="rowBawah" style="display: none;">
                <!-- Garis Horizontal penghubung Pasangan - Garis Tengah - Anak -->
                <div class="mini-line-h"></div>
                
                <div class="mini-col">
                    <div class="mini-node-group" id="nodePasangan" style="display: none;">
                        <div class="mini-node-label">Pasangan</div>
                        <div class="mini-node-cards" id="cardsPasangan"></div>
                    </div>
                </div>
                
                <div class="mini-col">
                    <div class="mini-node-group" id="nodeAnak" style="display: none;">
                        <div class="mini-node-label">Anak</div>
                        <div class="mini-node-cards" id="cardsAnak"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="step-footer" style="margin-top: 40px;">
            <button class="btn-primary" style="width: 100%;" onclick="finishWizard()">Selesai <i class="bi bi-chevron-right"></i></button>
        </div>
    </div>

</div>
</div>

<script>
    const searchApiUrl = "<?php echo site_url('familytree/api_search_members'); ?>";
    const saveApiUrl = "<?php echo site_url('familytree/api_save_member'); ?>";
    const baseTreeUrl = "<?php echo site_url('familytree'); ?>";
    const detailApiUrl = "<?php echo site_url('familytree/get_member_detail'); ?>";
</script>
<script src="<?php echo base_url('assets/js/wizard.js?v=' . time()); ?>"></script>
