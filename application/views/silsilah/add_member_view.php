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
            <div class="leaf-line"></div>
            <img src="<?php echo base_url('assets/img/leaf-1.svg'); ?>" class="leaf" id="leaf4" alt="">
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
            <?php if ($this->input->get('generasi') != '1'): ?>
            <label class="role-card">
                <input type="radio" name="role" value="anak" onchange="enableNext(1)">
                <div class="role-card-content">
                    <i class="bi bi-people-fill icon-role"></i>
                    <span>Saya adalah</span>
                    <strong>Anak</strong>
                </div>
            </label>
            <?php endif; ?>
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

    <!-- Step 2: Generasi -->
    <div class="wizard-step" id="step2">
        <p class="step-desc">Pilih generasimu</p>
        <div class="form-group">
            <label>Generasi</label>
            <select id="generasi" onchange="enableNext(2)" style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid var(--border-color); background: var(--input-bg); color: var(--ink); font-family: 'Manrope', sans-serif; margin-bottom: 20px;">
                <option value="">Pilih Generasi...</option>
                <?php for($i=1; $i<=25; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select>
        </div>
        <div class="step-footer">
            <button class="btn-secondary" onclick="prevStep(1)"><i class="bi bi-chevron-left"></i> Kembali</button>
            <button class="btn-primary" id="btnNext2" onclick="nextStep(3)">Lanjut <i class="bi bi-chevron-right"></i></button>
        </div>
    </div>

    <!-- Step 3: Relation -->
    <div class="wizard-step" id="step3">
        <p class="step-desc" id="relationQuestion">Siapa orang tua kamu?</p>
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="searchMember" placeholder="Cari nama.." onkeyup="searchMember(this.value)" autocomplete="off" style="background: transparent !important; color: var(--ink, inherit) !important;">
        </div>
        <div style="text-align: right; margin-bottom: 15px;">
            <button class="btn-secondary" style="font-size: 12px; padding: 5px 10px; border-radius: 8px; border: 1px solid var(--forest-deep, #4a6055); background: transparent; color: var(--forest-deep, #4a6055);" onclick="promptNewRelative(document.getElementById('searchMember').value)"><i class="bi bi-plus"></i> Tambah Manual</button>
        </div>
        <div class="selected-members-container" id="selectedMembers"></div>
        <div id="relationError" style="color: #b3543f; font-size: 13px; text-align: center; margin-bottom: 10px; padding: 10px; background: #ffebee; border-radius: 8px; display: none;"></div>
        <div class="member-list" id="memberList">
            <!-- List goes here -->
        </div>
        <div class="step-footer">
            <button class="btn-secondary" onclick="prevStep(2)"><i class="bi bi-chevron-left"></i> Kembali</button>
            <button class="btn-primary" id="btnNext3" onclick="nextStep(4)" disabled>Lanjut <i class="bi bi-chevron-right"></i></button>
        </div>
    </div>

    <!-- Step 4: Profile Info -->
    <div class="wizard-step" id="step4">
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
            <?php 
                $signup_basic = $this->session->userdata('signup_basic_info');
                $default_name = !empty($signup_basic) ? $signup_basic['full_name'] : (isset($pending_user) ? $pending_user->full_name : '');
            ?>
            <input type="text" id="fullName" placeholder="Masukkan nama lengkap" value="<?= htmlspecialchars($default_name) ?>" oninput="checkForm4()">
        </div>
        
        <div class="form-group">
            <label>Tanggal Lahir</label>
            <input type="date" id="birthDate" onchange="checkForm4()">
        </div>
        
        <div class="gender-selection">
            <label class="gender-radio">
                <input type="radio" name="gender" value="L" onchange="checkForm4()">
                <span>Laki-laki</span>
            </label>
            <label class="gender-radio">
                <input type="radio" name="gender" value="P" onchange="checkForm4()">
                <span>Perempuan</span>
            </label>
        </div>

        <div id="errorMsg" style="color: #b3543f; font-size: 13px; text-align: center; margin-bottom: 10px; display: none;"></div>

        <div class="step-footer">
            <button class="btn-secondary" onclick="prevStep(3)"><i class="bi bi-chevron-left"></i> Kembali</button>
            <button class="btn-primary" id="btnSubmit" onclick="submitForm()" disabled>Selesai <i class="bi bi-check2"></i></button>
        </div>
    </div>

    <!-- Step 5: Menunggu Persetujuan (Terakhir) -->
    <div class="wizard-step" id="step5">
        <div style="text-align: center; padding: 20px 0;">
            <h2 style="font-size: 24px; font-weight: 700; color: var(--ink); margin-bottom: 20px;">Menunggu Persetujuan</h2>
            
            <div class="success-avatar-wrapper">
                <img src="" id="successPhoto" alt="" class="success-avatar">
            </div>
            
            <div style="width: 40px; height: 2px; background-color: var(--forest-deep); margin: 25px auto; border-radius: 2px; opacity: 0.5;"></div>
            
            <p style="font-size: 15px; font-weight: 600; color: var(--ink); margin-bottom: 5px;">
                Halo, <span id="successName"></span>
            </p>
            <p style="font-size: 14px; color: #4A6055; margin-bottom: 30px;">
                Data kamu berhasil dikirim dan sedang<br>menunggu persetujuan Admin untuk bergabung.
            </p>

            <button class="btn-primary" style="width: 100%;" onclick="finishWizard()">Selesai <i class="bi bi-check2-circle"></i></button>
        </div>
    </div>

    <!-- Step 6: Posisi Keluarga Inti -->
    <div class="wizard-step" id="step6">
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="font-size: 20px; font-weight: 700; color: var(--ink); margin: 0;">Posisi Keluarga Inti</h2>
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
            <button class="btn-primary" style="width: 100%;" onclick="goToFinalStep()">Lanjut <i class="bi bi-chevron-right"></i></button>
        </div>
    </div>

</div>
</div>

<!-- Modal Tambah Relasi Baru -->
<div id="newRelModal" class="modal-overlay" onclick="if(event.target === this) closeNewRelModal()" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(5px);">
    <div style="background: var(--container-bg, white); padding: 30px; border-radius: 20px; max-width: 400px; width: 90%; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
        <h3 style="margin-top: 0; color: var(--ink, #2b3d34); font-size: 18px; margin-bottom: 10px;">Tambah Kerabat Baru</h3>
        <p style="color: var(--ink-soft, #6a7b73); font-size: 14px; margin-bottom: 20px;">Silakan lengkapi data kerabat baru ini:</p>
        
        <div style="margin-bottom: 20px;">
            <p style="color: var(--ink-soft, #6a7b73); font-size: 13px; margin-bottom: 8px;">Nama Lengkap:</p>
            <input type="text" id="newRelNameInput" class="form-control" placeholder="Ketik nama lengkap..." autocomplete="off">
            <div id="newRelNameError" style="color: #b3543f; font-size: 12px; margin-top: 5px; display: none;">Nama harus diisi</div>
        </div>
        
        <div style="margin-bottom: 20px;">
            <p style="color: var(--ink-soft, #6a7b73); font-size: 13px; margin-bottom: 8px;">Generasi:</p>
            <select id="newRelGenerasi" class="form-control" style="width: 100%; padding: 12px 15px; border-radius: 12px; border: 1.5px solid var(--border-color); background: var(--input-bg); color: var(--ink); font-family: 'Manrope', sans-serif;">
                <option value="">Pilih Generasi...</option>
                <?php for($i=1; $i<=25; $i++): ?>
                    <option value="<?= $i ?>"><?= $i ?></option>
                <?php endfor; ?>
            </select>
            <div id="newRelGenerasiError" style="color: #b3543f; font-size: 12px; margin-top: 5px; display: none;">Generasi harus dipilih</div>
        </div>

        <div style="margin-bottom: 25px; position: relative;">
            <p style="color: var(--ink-soft, #6a7b73); font-size: 13px; margin-bottom: 8px;">Tautkan ke orang tua (Opsional jika Anda tahu kakek/neneknya):</p>
            <div class="search-box" style="position: relative;">
                <i class="bi bi-search search-icon"></i>
                <input type="text" id="parentSearch" class="form-control" placeholder="Cari nama orang tua dari kerabat ini..." autocomplete="off">
            </div>
            <div id="parentSearchResult" style="position: absolute; top: 100%; left: 0; right: 0; background: var(--container-bg, white); color: var(--ink); max-height: 200px; overflow-y: auto; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.15); z-index: 10; margin-top: 5px; display: none; border: 1px solid var(--line, #eee);"></div>
            
            <div id="selectedParentContainer" style="display: none; align-items: center; background: #e8f4fc; padding: 10px; border-radius: 8px; margin-top: 10px;">
                <span style="font-size: 14px; color: #2b3d34; flex: 1;">Orang Tua terpilih: <strong id="selectedParentName"></strong></span>
                <button type="button" onclick="clearSelectedParent()" style="background: none; border: none; color: #c2185b; cursor: pointer;"><i class="bi bi-x-circle-fill"></i></button>
                <input type="hidden" id="selectedParentId">
            </div>
        </div>

        <p style="color: #6a7b73; font-size: 13px; margin-bottom: 8px;">Pilih Jenis Kelamin:</p>
        <div style="display: flex; gap: 15px; margin-bottom: 25px;">
            <input type="hidden" id="newRelGenderVal">
            <div id="btnMale" style="flex: 1; padding: 15px; border: 2px solid #e2e8e5; border-radius: 12px; background: white; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 10px; transition: 0.2s;" onclick="selectGender('L')">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: #e8f4fc; color: #0288d1; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="bi bi-gender-male"></i>
                </div>
                <span style="font-weight: 600; color: #2b3d34;">Laki-laki</span>
            </div>
            <div id="btnFemale" style="flex: 1; padding: 15px; border: 2px solid #e2e8e5; border-radius: 12px; background: white; cursor: pointer; display: flex; flex-direction: column; align-items: center; gap: 10px; transition: 0.2s;" onclick="selectGender('P')">
                <div style="width: 50px; height: 50px; border-radius: 50%; background: #fce8ef; color: #c2185b; display: flex; align-items: center; justify-content: center; font-size: 24px;">
                    <i class="bi bi-gender-female"></i>
                </div>
                <span style="font-weight: 600; color: #2b3d34;">Perempuan</span>
            </div>
        </div>
        <div id="newRelGenderError" style="color: #b3543f; font-size: 12px; text-align: center; margin-top: -15px; margin-bottom: 20px; display: none;">Jenis kelamin harus dipilih</div>
        <div id="newRelParentError" style="color: #b3543f; font-size: 12px; text-align: center; margin-top: -10px; margin-bottom: 20px; display: none; padding: 8px; background: #ffebee; border-radius: 6px;"></div>
        
        <div style="display: flex; justify-content: flex-end; gap: 10px;">
            <button onclick="closeNewRelModal()" style="padding: 10px 20px; background: none; border: none; color: #6a7b73; font-weight: 600; cursor: pointer;">Batal</button>
            <button onclick="submitNewRelative()" style="padding: 10px 20px; background: #2b3d34; border: none; color: white; border-radius: 8px; font-weight: 600; cursor: pointer;">Simpan</button>
        </div>
    </div>
</div>

<script>
    const isOnboarding = <?php echo isset($is_onboarding) && $is_onboarding ? 'true' : 'false'; ?>;
    const searchApiUrl = "<?php echo site_url('familytree/api_search_members'); ?>";
    const saveApiUrl = isOnboarding ? "<?php echo site_url('auth/api_add_self_member'); ?>" : "<?php echo site_url('familytree/api_save_member'); ?>";
    const baseTreeUrl = "<?php echo site_url('familytree'); ?>";
    const baseUrl = "<?php echo base_url(); ?>";
    const detailApiUrl = "<?php echo site_url('familytree/get_member_detail'); ?>";
</script>
<script src="<?php echo base_url('assets/js/wizard.js?v=' . time() . rand()); ?>"></script>
