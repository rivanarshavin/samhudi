<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
    body {
        font-family: 'Manrope', sans-serif;
        background: #f4ede3;
        margin: 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .onboarding-container {
        background: #fff;
        max-width: 600px;
        width: 100%;
        margin: 20px;
        border-radius: 24px;
        box-shadow: 0 10px 30px rgba(74, 96, 85, 0.08);
        padding: 40px;
        text-align: center;
    }
    .onboarding-title {
        font-size: 24px;
        font-weight: 700;
        color: #2b3d34;
        margin-bottom: 10px;
    }
    .onboarding-desc {
        color: #6a7b73;
        font-size: 15px;
        margin-bottom: 30px;
    }
    .options-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 30px;
    }
    .option-card {
        border: 2px solid #e2e8e5;
        border-radius: 16px;
        padding: 24px 20px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }
    .option-card:hover {
        border-color: #4a6055;
        background: #f8faf9;
        transform: translateY(-2px);
    }
    .option-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #edf2f0;
        color: #4a6055;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
    }
    .option-title {
        font-weight: 700;
        color: #2b3d34;
        font-size: 16px;
    }
    .option-subtitle {
        font-size: 13px;
        color: #6a7b73;
    }

    /* Section for Link */
    #linkSection {
        display: none;
        text-align: left;
        margin-top: 30px;
        border-top: 1px solid #e2e8e5;
        padding-top: 30px;
    }
    .search-box {
        position: relative;
        margin-bottom: 20px;
    }
    .search-box i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #6a7b73;
    }
    .search-box input {
        width: 100%;
        padding: 12px 15px 12px 45px;
        border: 2px solid #e2e8e5;
        border-radius: 12px;
        font-family: 'Manrope', sans-serif;
        font-size: 15px;
        outline: none;
        transition: 0.3s;
    }
    .search-box input:focus {
        border-color: #4a6055;
    }
    .member-list {
        max-height: 250px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .member-item {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        border: 1px solid #e2e8e5;
        border-radius: 12px;
        cursor: pointer;
        transition: 0.2s;
    }
    .member-item:hover, .member-item.selected {
        border-color: #4a6055;
        background: #f8faf9;
    }
    .member-avatar {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        object-fit: cover;
    }
    .member-info {
        flex: 1;
    }
    .member-name {
        font-weight: 600;
        color: #2b3d34;
        font-size: 14px;
        margin-bottom: 2px;
    }
    .member-detail {
        font-size: 12px;
        color: #6a7b73;
    }
    .btn-submit {
        background: #4a6055;
        color: white;
        border: none;
        width: 100%;
        padding: 14px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: 0.3s;
        margin-top: 20px;
    }
    .btn-submit:hover {
        background: #394a42;
    }
    .btn-submit:disabled {
        background: #ccc;
        cursor: not-allowed;
    }

    @media (max-width: 600px) {
        .options-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="onboarding-container">
    <h1 class="onboarding-title">Selamat Datang di Silsilah Keluarga</h1>
    <p class="onboarding-desc">Sebelum memulai, apakah profil Anda sudah pernah ditambahkan oleh anggota keluarga lain ke dalam sistem?</p>

    <div class="options-grid">
        <div class="option-card" onclick="showLinkSection()">
            <div class="option-icon"><i class="bi bi-person-check-fill"></i></div>
            <div class="option-text">
                <div class="option-title">Ya, Sudah Ada</div>
                <div class="option-subtitle">Pilih nama Anda dari daftar silsilah yang sudah ada</div>
            </div>
        </div>
        <div class="option-card" onclick="goToWizard()">
            <div class="option-icon"><i class="bi bi-person-plus-fill"></i></div>
            <div class="option-text">
                <div class="option-title">Belum Ada</div>
                <div class="option-subtitle">Tambahkan data diri Anda ke silsilah sekarang</div>
            </div>
        </div>
    </div>

    <div id="linkSection">
        <h3 class="onboarding-title" style="font-size: 18px; text-align: left;">Cari Nama Anda</h3>
        <p class="onboarding-desc" style="text-align: left; margin-bottom: 15px;">Ketik nama Anda untuk menautkan akun.</p>
        
        <div class="search-box">
            <i class="bi bi-search"></i>
            <input type="text" id="searchName" placeholder="Ketik nama Anda..." onkeyup="searchMember(this.value)">
        </div>

        <div class="member-list" id="memberList">
            <div style="text-align: center; color: #888; padding: 20px; font-size: 13px;">Hasil pencarian akan muncul di sini</div>
        </div>

        <button class="btn-submit" id="btnLink" disabled onclick="submitLink()">Tautkan Akun Saya</button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    let selectedMemberId = null;

    function goToWizard() {
        window.location.href = "<?php echo site_url('auth/onboarding_wizard'); ?>";
    }

    function showLinkSection() {
        $('#linkSection').slideDown();
        $('html, body').animate({
            scrollTop: $("#linkSection").offset().top
        }, 500);
        
        // Load default unlinked members
        loadDefaultMembers();
    }

    function loadDefaultMembers() {
        $('#memberList').html('<div style="text-align: center; color: #888; padding: 20px; font-size: 13px;">Memuat data...</div>');
        $.ajax({
            url: "<?php echo site_url('familytree/api_get_unlinked_members'); ?>",
            method: 'GET',
            success: function(res) {
                renderMembers(res, 'Belum ada data tersedia');
            },
            error: function() {
                $('#memberList').html('<div style="text-align: center; color: red; padding: 20px; font-size: 13px;">Gagal memuat data</div>');
            }
        });
    }

    function renderMembers(res, emptyMsg) {
        if (res.length === 0) {
            $('#memberList').html(`<div style="text-align: center; color: #888; padding: 20px; font-size: 13px;">${emptyMsg}</div>`);
            return;
        }

        let html = '';
        res.forEach(item => {
            if (!item.user_id) {
                let foto = item.photo ? "<?php echo base_url(); ?>" + item.photo : `https://placehold.co/40x40/CBD9CF/4A6055?text=${item.full_name.charAt(0)}`;
                html += `
                    <div class="member-item" id="member-${item.id}" onclick="selectMember(${item.id})">
                        <img src="${foto}" alt="avatar" class="member-avatar">
                        <div class="member-info">
                            <div class="member-name">${item.full_name}</div>
                            <div class="member-detail">${item.gender === 'L' ? 'Laki-laki' : 'Perempuan'} ${item.birth_date ? '• ' + item.birth_date : ''}</div>
                        </div>
                    </div>
                `;
            }
        });

        if (html === '') {
            html = '<div style="text-align: center; color: #888; padding: 20px; font-size: 13px;">Semua nama yang cocok sudah memiliki akun</div>';
        } else if (res.length >= 20) {
            html += '<div style="text-align: center; color: #888; padding: 15px; font-size: 12px; font-style: italic;">Hanya menampilkan 20 nama terbaru.<br>Jika nama Anda tidak ada di daftar ini, silakan ketik nama Anda di kotak pencarian.</div>';
        }
        $('#memberList').html(html);
    }

    function searchMember(term) {
        if (term.length === 0) {
            loadDefaultMembers();
            return;
        }

        if (term.length < 2) {
            $('#memberList').html('<div style="text-align: center; color: #888; padding: 20px; font-size: 13px;">Ketik minimal 2 huruf</div>');
            return;
        }

        $.ajax({
            url: "<?php echo site_url('familytree/api_search_members'); ?>?term=" + encodeURIComponent(term),
            method: 'GET',
            success: function(res) {
                renderMembers(res, 'Tidak ditemukan nama yang cocok');
            },
            error: function() {
                $('#memberList').html('<div style="text-align: center; color: red; padding: 20px; font-size: 13px;">Gagal memuat pencarian</div>');
            }
        });
    }

    function selectMember(id) {
        $('.member-item').removeClass('selected');
        $('#member-' + id).addClass('selected');
        selectedMemberId = id;
        $('#btnLink').prop('disabled', false);
    }

    function submitLink() {
        if (!selectedMemberId) return;
        
        let btn = $('#btnLink');
        btn.prop('disabled', true).html('Memproses...');

        $.ajax({
            url: "<?php echo site_url('auth/api_link_member'); ?>",
            method: 'POST',
            data: { member_id: selectedMemberId },
            success: function(res) {
                if (res.status) {
                    alert('Berhasil! Akun Anda sudah tertaut.');
                    window.location.href = "<?php echo site_url('/'); ?>";
                } else {
                    alert(res.message);
                    btn.prop('disabled', false).html('Tautkan Akun Saya');
                }
            },
            error: function() {
                alert('Terjadi kesalahan server.');
                btn.prop('disabled', false).html('Tautkan Akun Saya');
            }
        });
    }
</script>
