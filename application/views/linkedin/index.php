<?php
/**
 * @var object $user
 * @var array $jobs
 * @var array $workers
 * @var object|null $my_open_to_work
 */
if (!function_exists('time_elapsed_string')) {
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime();
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        $string = [];
        $units = ['y'=>'tahun','m'=>'bulan','w'=>'minggu','d'=>'hari','h'=>'jam','i'=>'menit','s'=>'detik'];
        foreach ($units as $k=>$v) {
            if ($diff->$k) {
                $string[$k] = $diff->$k . ' ' . $v;
            }
        }
        if (!$full) $string = array_slice($string,0,1);
        return $string ? implode(', ', $string) . ' yang lalu' : 'baru saja';
    }
}
?>
<!-- Leaflet CSS & JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<style>
    :root {
        --color-bg-dark: #F8F9FA;
        --color-border-dark: #8fa5a2;
        --color-light-teal: #274D4F;
        --color-orange-accent: #E49438;
        --color-text-muted: #4b5e5b;
        --color-text-main: #15201E;
        --color-card-bg: #ffffff;
        --color-input-bg: #F8F9FA;
        --color-chat-bubble-bg: #eef0ef;
        --color-forum-gradient: #F8F9FA;
        --color-card-shadow: 0 6px 24px rgba(39, 77, 79, 0.08);
    }
    
    body[data-theme="dark"] {
        --color-bg-dark: #0F211F;
        --color-border-dark: #22443F;
        --color-light-teal: #C8A84E;
        --color-orange-accent: #C8A84E;
        --color-text-muted: #B1CDCE;
        --color-text-main: #FFFFFF;
        --color-card-bg: #1B3835;
        --color-input-bg: #0d1314;
        --color-chat-bubble-bg: rgba(255, 255, 255, 0.05);
        --color-forum-gradient: #0F211F;
        --color-card-shadow: 0 8px 30px rgba(0, 0, 0, 0.35);
    }
    
    body {
        background-color: var(--color-bg-dark) !important;
        color: var(--color-text-main) !important;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .linkedin-container {
        background: var(--color-forum-gradient) !important;
        min-height: 100vh;
        transition: background 0.3s ease;
    }

    .nav-sidebar-link {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .nav-sidebar-link:hover {
        transform: translateX(4px);
    }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--color-light-teal); border-radius: 3px; }

    /* Tabs */
    .tab-btn {
        padding: 12px 24px;
        font-weight: bold;
        color: var(--color-text-muted) !important;
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
    }
    .tab-btn:hover {
        color: var(--color-text-main) !important;
    }
    .tab-btn.active {
        color: var(--color-orange-accent) !important;
        border-bottom-color: var(--color-orange-accent) !important;
    }

    /* Modals */
    .modal-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 9999;
        display: none; align-items: center; justify-content: center;
        padding: 20px;
    }
    .modal-overlay.open { display: flex; }
    .modal-content {
        background: var(--color-card-bg) !important;
        border: 1px solid var(--color-border-dark) !important;
        border-radius: 20px;
        width: 100%; max-width: 600px; margin: auto; padding: 24px;
        max-height: 90vh; overflow-y: auto;
        box-shadow: var(--color-card-shadow) !important;
    }

    /* Forms */
    .form-input {
        width: 100%;
        background: var(--color-input-bg) !important;
        border: 1px solid var(--color-border-dark) !important;
        border-radius: 12px;
        color: var(--color-text-main) !important;
        font-size: 0.85rem;
        padding: 10px 14px;
        margin-bottom: 16px;
        outline: none;
        transition: border-color 0.2s, background-color 0.3s;
    }
    .form-input:focus { border-color: var(--color-light-teal) !important; }
    .form-label { display: block; font-size: 0.75rem; font-weight: 700; color: var(--color-text-muted) !important; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em; }
    
    .form-input[readonly] {
        background: rgba(0,0,0,0.05) !important;
        color: var(--color-text-muted) !important;
        cursor: not-allowed;
    }

    .btn-primary {
        background: var(--color-orange-accent);
        color: white !important;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: bold;
        transition: all 0.2s;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(228, 148, 56, 0.4);
    }

    .btn-teal {
        background: var(--color-light-teal);
        color: white;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: bold;
        transition: all 0.2s;
        display: inline-flex; align-items: center; gap: 8px;
        border: none; cursor: pointer;
    }
    .btn-teal:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(55, 124, 128, 0.4);
        background: #4a9da1;
    }

    .btn-edit-otw {
        background: rgba(228,148,56,0.15);
        border: 1px solid rgba(228,148,56,0.5);
        color: #E49438;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: bold;
        transition: all 0.2s;
        display: inline-flex; align-items: center; gap: 8px;
        cursor: pointer;
    }
    .btn-edit-otw:hover {
        background: rgba(228,148,56,0.25);
        transform: translateY(-2px);
    }
    
    /* Job list & detail panel layout */
    .job-list-container {
        flex: 1;
        transition: all 0.3s;
    }
    .job-detail-panel {
        width: 400px;
        flex-shrink: 0;
        background: var(--color-card-bg) !important;
        border: 1px solid var(--color-border-dark) !important;
        border-radius: 16px;
        display: none;
        flex-direction: column;
        overflow: hidden;
        box-shadow: var(--color-card-shadow) !important;
    }
    .job-detail-panel.active {
        display: flex;
    }
    .job-item {
        background: var(--color-card-bg) !important;
        border: 1px solid var(--color-border-dark) !important;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        cursor: pointer;
        box-shadow: var(--color-card-shadow) !important;
        transition: all 0.2s;
    }
    .job-item:hover, .job-item.active {
        border-color: var(--color-light-teal) !important;
        background: var(--color-chat-bubble-bg) !important;
    }

    /* Worker Card */
    .worker-card {
        background: var(--color-card-bg) !important;
        border: 1px solid var(--color-border-dark) !important;
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: var(--color-card-shadow) !important;
        transition: all 0.2s;
        cursor: pointer;
    }
    .worker-card:hover {
        transform: translateY(-2px);
        border-color: var(--color-light-teal) !important;
        background: rgba(55, 124, 128, 0.08);
    }
    .worker-avatar {
        width: 64px; height: 64px; border-radius: 50%; object-fit: cover;
        border: 2px solid var(--color-light-teal);
    }

    /* Premium 3D Icon Effect */
    .text-3d {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transform: perspective(150px) rotateX(12deg) rotateY(-15deg);
        text-shadow: 
            1px 1px 0px #2a4a42,
            2px 2px 0px #2a4a42,
            3px 3px 4px rgba(0, 0, 0, 0.5);
        filter: drop-shadow(0 2px 4px rgba(0,0,0,0.3));
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .nav-sidebar-link:hover .text-3d {
        transform: perspective(150px) rotateX(15deg) rotateY(-20deg) translateZ(8px);
        text-shadow: 
            1px 1px 0px #377C80,
            2px 2px 0px #377C80,
            3px 3px 0px #377C80,
            4px 4px 0px #377C80,
            5px 5px 6px rgba(0,0,0,0.6);
        color: #ffffff !important;
    }

    /* CSS Overrides for hardcoded Tailwind arbitrary backgrounds and text */
    /* Prepended with .linkedin-container to increase CSS specificity over Tailwind CDN runtime styles */
    .linkedin-container .bg-\[\#15201E\] {
        background-color: var(--color-card-bg) !important;
        transition: background-color 0.3s ease;
    }
    .linkedin-container .bg-\[\#1E2E2B\] {
        background-color: var(--color-card-bg) !important;
        transition: background-color 0.3s ease;
    }
    .linkedin-container .bg-\[\#374D49\] {
        background-color: var(--color-border-dark) !important;
        transition: background-color 0.3s ease;
    }
    .linkedin-container .bg-\[\#374D49\]\/40:hover {
        background-color: var(--color-border-dark) !important;
        opacity: 0.8;
    }
    .linkedin-container .border-\[\#374D49\]\/50, 
    .linkedin-container .border-\[\#374D49\]\/40, 
    .linkedin-container .border-\[\#374D49\]\/30, 
    .linkedin-container .border-\[\#374D49\] {
        border-color: var(--color-border-dark) !important;
        transition: border-color 0.3s ease;
    }
    .linkedin-container .text-\[\#B1CDCE\] {
        color: var(--color-text-muted) !important;
        transition: color 0.3s ease;
    }
    .linkedin-container .text-\[\#B1CDCE\]\/50, 
    .linkedin-container .text-\[\#B1CDCE\]\/70,
    .linkedin-container .text-\[\#B1CDCE\]\/80,
    .linkedin-container .text-\[\#B1CDCE\]\/60 {
        color: var(--color-text-muted) !important;
        opacity: 0.75;
    }
    .linkedin-container .bg-\[\#0d1314\] {
        background-color: var(--color-input-bg) !important;
        transition: background-color 0.3s ease;
    }
    .linkedin-container .bg-\[\#0d1314\]\/50 {
        background-color: var(--color-input-bg) !important;
        opacity: 0.85;
    }
    .linkedin-container .bg-white\/5 {
        background-color: var(--color-chat-bubble-bg) !important;
        transition: background-color 0.3s ease;
    }
    .linkedin-container .bg-\[\#377C80\] {
        background-color: var(--color-light-teal) !important;
        transition: background-color 0.3s ease;
    }
    .linkedin-container .text-\[\#377C80\] {
        color: var(--color-light-teal) !important;
        transition: color 0.3s ease;
    }
    .linkedin-container .focus\:ring-\[\#377C80\]:focus {
        --tw-ring-color: var(--color-light-teal) !important;
    }
    .linkedin-container h3, 
    .linkedin-container h4, 
    .linkedin-container h5, 
    .linkedin-container h6,
    .linkedin-container .text-white {
        color: var(--color-text-main) !important;
        transition: color 0.3s ease;
    }
    .linkedin-container input::placeholder, 
    .linkedin-container textarea::placeholder {
        color: var(--color-text-muted) !important;
        opacity: 0.55 !important;
    }

    /* Specific Light Mode text overrides for opacity classes that default to white */
    body[data-theme="light"] .linkedin-container .text-white {
        color: var(--color-text-main) !important;
    }
    body[data-theme="light"] .linkedin-container .text-white\/20 {
        color: var(--color-border-dark) !important;
        opacity: 0.5 !important;
    }
    body[data-theme="light"] .linkedin-container .text-white\/30 {
        color: var(--color-text-muted) !important;
        opacity: 0.5 !important;
    }
    body[data-theme="light"] .linkedin-container .text-white\/40 {
        color: var(--color-text-muted) !important;
        opacity: 0.65 !important;
    }
    body[data-theme="light"] .linkedin-container .text-white\/50 {
        color: var(--color-text-muted) !important;
        opacity: 0.8 !important;
    }
    body[data-theme="light"] .linkedin-container .text-white\/60 {
        color: var(--color-text-muted) !important;
        opacity: 0.9 !important;
    }
    body[data-theme="light"] .linkedin-container .text-white\/80 {
        color: var(--color-text-main) !important;
        opacity: 0.9 !important;
    }

    /* Keep button text and icons dark on Gold accent buttons in Dark Mode */
    body[data-theme="dark"] .linkedin-container .bg-\[\#377C80\] {
        color: #0F211F !important;
    }
    body[data-theme="dark"] .linkedin-container .bg-\[\#377C80\] i {
        color: #0F211F !important;
    }
    body[data-theme="dark"] .linkedin-container .bg-\[\#377C80\]:hover {
        opacity: 0.9;
    }

    /* OTW Avatar in modal */
    .otw-profile-avatar {
        width: 72px; height: 72px; border-radius: 50%; object-fit: cover;
        border: 3px solid var(--color-light-teal);
        box-shadow: 0 4px 12px rgba(55,124,128,0.3);
    }

    /* LinkedIn Brand Icon Custom */
    .ln-brand-icon {
        width: 20px; height: 20px;
        background: linear-gradient(135deg, #0077b5, #00a0dc);
        border-radius: 4px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 900; color: white;
        letter-spacing: -1px;
        flex-shrink: 0;
    }

    /* Range Slider Styling */
    .slider-input {
        pointer-events: none;
        appearance: none;
        background: none;
        border: none;
    }
    .slider-input::-webkit-slider-thumb {
        pointer-events: auto;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid var(--color-light-teal);
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        -webkit-appearance: none;
        transition: transform 0.1s;
    }
    .slider-input::-webkit-slider-thumb:hover {
        transform: scale(1.1);
    }
    .slider-input::-moz-range-thumb {
        pointer-events: auto;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        background: #ffffff;
        border: 2px solid var(--color-light-teal);
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.3);
        transition: transform 0.1s;
    }
    .slider-input::-moz-range-thumb:hover {
        transform: scale(1.1);
    }
    .slider-input::-webkit-slider-runnable-track {
        background: transparent;
        border: none;
    }
    .slider-input::-moz-range-track {
        background: transparent;
        border: none;
    }
</style>

<div class="linkedin-container font-display pb-12">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        
        <?php if ($this->session->flashdata('success_msg')): ?>
            <div class="bg-[#374D49] text-white p-3 rounded-xl mb-4 border border-[#377C80] flex items-center gap-2 text-sm">
                <i class="bi bi-check-circle-fill text-[#7ecdd1]"></i> <?= $this->session->flashdata('success_msg') ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_msg')): ?>
            <div class="bg-red-900/50 text-white p-3 rounded-xl mb-4 border border-red-500/50 flex items-center gap-2 text-sm">
                <i class="bi bi-exclamation-triangle-fill text-red-400"></i> <?= $this->session->flashdata('error_msg') ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- LEFT SIDEBAR: Nav -->
            <div class="lg:col-span-3 flex flex-col gap-8 lg:border-r lg:border-[#374D49]/40 lg:pr-6">
                <div>
                    <ul class="space-y-4 font-semibold">
                        <li>
                            <a href="<?= base_url('forum?filter=all') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40">
                                <img src="<?= base_url('assets/images/3d_house.png') ?>" class="w-9 h-9 object-contain" alt="Beranda"> Beranda
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('forum?filter=populer') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40">
                                <img src="<?= base_url('assets/images/3d_star.png') ?>" class="w-9 h-9 object-contain" alt="Populer"> Populer
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('forum?filter=my_posts') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40">
                                <img src="<?= base_url('assets/images/3d_clock.png') ?>" class="w-9 h-9 object-contain" alt="Terbaru"> Terbaru
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('forum?filter=saved') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40">
                                <img src="<?= base_url('assets/images/3d_bookmark.png') ?>" class="w-9 h-9 object-contain" alt="Simpan"> Simpan
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('linkedin') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all bg-[#374D49] text-white shadow-sm">
                                <img src="<?= base_url('assets/images/3d_linkedin.png') ?>" class="w-9 h-9 object-contain" alt="Project Samhudi"> Project Samhudi
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('profile') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40">
                                <?php if (!empty($user->avatar)): ?>
                                    <div class="w-6 h-6 rounded-full overflow-hidden bg-teal-800 flex-shrink-0">
                                        <img src="<?= base_url($user->avatar) ?>" alt="Avatar" class="w-full h-full object-cover">
                                    </div>
                                <?php else: ?>
                                    <div class="w-6 h-6 rounded-full bg-teal-700/60 border border-[#374D49]/40 flex items-center justify-center font-bold text-white text-xs select-none flex-shrink-0">
                                        <?= strtoupper(substr($this->session->userdata('full_name') ?? 'U', 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                Profil Saya
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- RIGHT CONTENT -->
            <div class="lg:col-span-9 flex flex-col">
                <!-- Header / Tabs -->
                <div class="flex items-center justify-between border-b border-[#374D49] mb-6">
                    <div class="flex">
                        <button class="tab-btn active" id="tab-btn-lowongan" onclick="switchTab('lowongan', this)">Lowongan Pekerjaan</button>
                        <button class="tab-btn" id="tab-btn-pekerja" onclick="switchTab('pekerja', this)">Rekan Kerja</button>
                    </div>
                </div>

                <!-- TAB: Lowongan -->
                <div id="tab-lowongan" class="tab-content">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-white">Daftar Lowongan</h2>
                        <button onclick="openModal('addJobModal')" class="btn-primary">
                            <i class="bi bi-plus-lg"></i> Tambah Lowongan
                        </button>
                    </div>

                    <!-- Search Filter -->
                    <form method="GET" action="<?= base_url('linkedin') ?>" class="mb-5 flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-[160px]">
                            <label class="form-label">Cari Pekerjaan</label>
                            <input type="text" name="search" class="form-input" style="margin-bottom:0" placeholder="Posisi, perusahaan..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                        </div>
                        <div class="flex-1 min-w-[130px]">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="location" class="form-input" style="margin-bottom:0" placeholder="Jakarta, Bandung..." value="<?= htmlspecialchars($filters['location'] ?? '') ?>">
                        </div>
                        <div class="flex-1 min-w-[130px]">
                            <label class="form-label">Tipe Kerja</label>
                            <select name="type" class="form-input" style="margin-bottom:0">
                                <option value="">Semua Tipe</option>
                                <option value="Full-time" <?= ($filters['type'] ?? '') === 'Full-time' ? 'selected' : '' ?>>Full-time</option>
                                <option value="Part-time" <?= ($filters['type'] ?? '') === 'Part-time' ? 'selected' : '' ?>>Part-time</option>
                                <option value="Freelance" <?= ($filters['type'] ?? '') === 'Freelance' ? 'selected' : '' ?>>Freelance</option>
                                <option value="Remote" <?= ($filters['type'] ?? '') === 'Remote' ? 'selected' : '' ?>>Remote</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary" style="padding: 10px 20px;">Filter</button>
                        <a href="<?= base_url('linkedin') ?>" class="btn-primary" style="padding:10px 14px; background: rgba(55,77,73,0.6); border:1px solid #374D49;"><i class="bi bi-arrow-clockwise"></i></a>
                    </form>

                    <div class="flex gap-6 items-start relative">
                        <!-- List Panel -->
                        <div class="job-list-container">
                            <?php if(!empty($jobs)): ?>
                                <?php foreach($jobs as $job): ?>
                                    <div class="job-item" onclick="loadJobDetail(<?= $job->id ?>, this)">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="text-lg font-bold text-white mb-1"><?= htmlspecialchars($job->job_title) ?></h3>
                                                <p class="text-sm text-[#E49438] font-semibold mb-2"><?= htmlspecialchars($job->company_name) ?></p>
                                            </div>
                                            <span class="text-[10px] text-[#B1CDCE]/60"><?= time_elapsed_string($job->created_at) ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-12 bg-[#1E2E2B] rounded-xl border border-[#374D49]">
                                    <i class="bi bi-briefcase text-4xl text-[#B1CDCE]/30 mb-3 block"></i>
                                    <p class="text-[#B1CDCE]/50">Belum ada lowongan pekerjaan tersedia.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Detail Panel -->
                        <div class="job-detail-panel custom-scrollbar overflow-y-auto max-h-[600px] sticky top-24" id="jobDetailPanel">
                            <div class="p-6">
                                <button onclick="closeJobDetail()" class="text-[#B1CDCE] hover:text-white mb-4 lg:hidden">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </button>
                                
                                <div id="jobDetailLoader" class="text-center py-10 text-[#B1CDCE]/50">
                                    Memuat detail...
                                </div>
                                
                                <div id="jobDetailContent" class="hidden">
                                    <h2 id="jdTitle" class="text-2xl font-bold text-white mb-1"></h2>
                                    <p id="jdCompany" class="text-lg text-[#E49438] font-bold mb-3"></p>

                                    <div id="applyActionArea" class="mb-6"></div>

                                    <div class="space-y-4 mb-6">
                                        <div class="flex items-start gap-3">
                                            <i class="bi bi-geo-alt mt-1 text-[#377C80]"></i>
                                            <div>
                                                <div class="text-xs text-[#B1CDCE]">Lokasi</div>
                                                <div id="jdLocation" class="text-sm text-white font-semibold"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <i class="bi bi-briefcase mt-1 text-[#377C80]"></i>
                                            <div>
                                                <div class="text-xs text-[#B1CDCE]">Jenis Pekerjaan</div>
                                                <div id="jdType" class="text-sm text-white font-semibold"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <i class="bi bi-cash mt-1 text-[#377C80]"></i>
                                            <div>
                                                <div class="text-xs text-[#B1CDCE]">Gaji</div>
                                                <div id="jdSalary" class="text-sm text-white font-semibold"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <i class="bi bi-clock mt-1 text-[#377C80]"></i>
                                            <div>
                                                <div class="text-xs text-[#B1CDCE]">Jam Kerja</div>
                                                <div id="jdHours" class="text-sm text-white font-semibold"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="border-[#374D49] mb-4">
                                    
                                    <div class="mb-6">
                                        <h3 class="text-sm font-bold text-white mb-2">Deskripsi Pekerjaan</h3>
                                        <div id="jdDescription" class="text-sm text-[#B1CDCE] leading-relaxed whitespace-pre-wrap"></div>
                                    </div>

                                    <hr class="border-[#374D49] mb-4">

                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-teal-800 flex items-center justify-center text-white font-bold">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-[#B1CDCE]">Dipublikasikan oleh:</div>
                                            <div id="jdPublisher" class="text-sm font-bold text-white"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB: Pekerja -->
                <div id="tab-pekerja" class="tab-content hidden">

                    <!-- Header Rekan Kerja + Button OTW -->
                    <div class="flex flex-wrap justify-between items-center mb-5 gap-3">
                        <h2 class="text-xl font-bold text-white">Rekan Kerja</h2>
                        <?php if ($my_open_to_work): ?>
                            <button onclick="openOTWModal(true)" class="btn-edit-otw" id="btn-otw">
                                <i class="bi bi-pencil-square"></i> Edit Open to Work
                            </button>
                        <?php else: ?>
                            <button onclick="openOTWModal(false)" class="btn-teal" id="btn-otw">
                                <i class="bi bi-person-check"></i> Open to Work
                            </button>
                        <?php endif; ?>
                    </div>

                    <!-- Search Filter Rekan Kerja -->
                    <form method="GET" action="<?= base_url('linkedin') ?>" class="mb-5 flex flex-wrap gap-3 items-end" id="worker-search-form">
                        <input type="hidden" name="tab" value="pekerja">
                        <div class="flex-1 min-w-[180px]">
                            <label class="form-label">Cari Nama Rekan Kerja</label>
                            <input type="text" name="worker_search" class="form-input" style="margin-bottom:0" placeholder="Cari nama..." value="<?= htmlspecialchars($filters['worker_search'] ?? '') ?>">
                        </div>
                        <div class="flex-1 min-w-[180px]">
                            <label class="form-label">Jenis Pekerjaan</label>
                            <input type="text" name="worker_job" class="form-input" style="margin-bottom:0" placeholder="Contoh: Software Engineer..." value="<?= htmlspecialchars($filters['worker_job'] ?? '') ?>">
                        </div>
                        <button type="submit" class="btn-primary" style="padding: 10px 20px;"><i class="bi bi-search"></i> Cari</button>
                        <a href="<?= base_url('linkedin?tab=pekerja') ?>" class="btn-primary" style="padding:10px 14px; background: rgba(55,77,73,0.6); border:1px solid #374D49;"><i class="bi bi-arrow-clockwise"></i></a>
                    </form>

                    <?php if(!empty($workers)): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php foreach($workers as $worker): ?>
                                <div class="worker-card" onclick="openWorkerDetailModal(<?= htmlspecialchars(json_encode([
                                    'full_name'    => $worker->full_name,
                                    'avatar'       => !empty($worker->avatar) ? base_url($worker->avatar) : '',
                                    'work_role'    => $worker->work_role ?? '',
                                    'desired_job'  => $worker->desired_job ?? '',
                                    'birth_date'   => $worker->birth_date ?? '',
                                    'work_history' => $worker->work_history ?? '',
                                    'about'        => $worker->about ?? '',
                                    'is_fresh_graduate' => $worker->is_fresh_graduate ?? 0,
                                    'cv_path'      => $worker->cv_path ?? '',
                                 ]), ENT_QUOTES) ?>)">
                                    <?php if (!empty($worker->avatar)): ?>
                                        <img src="<?= base_url($worker->avatar) ?>" alt="Avatar" class="worker-avatar">
                                    <?php else: ?>
                                        <div class="worker-avatar flex items-center justify-center font-bold text-white text-xl select-none bg-teal-700/60 border border-[#374D49]/40 shrink-0">
                                            <?= strtoupper(substr($worker->full_name ?? 'U', 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-bold text-white truncate"><?= htmlspecialchars($worker->full_name) ?></h3>
                                        <p class="text-sm text-[#E49438] font-semibold mb-2 truncate"><?= htmlspecialchars($worker->desired_job ?: ($worker->work_role ?? 'Tidak ada role spesifik')) ?></p>
                                        
                                        <div class="flex gap-2 flex-wrap">
                                            <span class="px-2 py-1 bg-[#377C80]/20 text-[#7ecdd1] text-[10px] font-bold rounded">
                                                Open to Work
                                            </span>
                                            <?php if($worker->is_fresh_graduate): ?>
                                                <span class="px-2 py-1 bg-green-900/40 text-green-400 text-[10px] font-bold rounded border border-green-700/50">
                                                    Fresh Graduate
                                                </span>
                                            <?php elseif(!empty($worker->work_history)): ?>
                                                <span class="px-2 py-1 bg-blue-900/40 text-blue-400 text-[10px] font-bold rounded border border-blue-700/50">
                                                    Berpengalaman
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <i class="bi bi-chevron-right text-[#B1CDCE]/40"></i>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12 bg-[#1E2E2B] rounded-xl border border-[#374D49]">
                            <i class="bi bi-people text-4xl text-[#B1CDCE]/30 mb-3 block"></i>
                            <p class="text-[#B1CDCE]/50">Belum ada anggota keluarga yang sedang mencari pekerjaan.</p>
                            <?php if (!$my_open_to_work): ?>
                            <p class="text-[#B1CDCE]/40 text-sm mt-2">Klik tombol <strong class="text-[#377C80]">Open to Work</strong> di atas untuk mendaftarkan diri.</p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                </div>

            </div>
        </div>
    </main>
</div>

<!-- Modal Tambah Lowongan -->
<div class="modal-overlay" id="addJobModal" onclick="closeModalOutside(event, 'addJobModal')">
    <div class="modal-content custom-scrollbar" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 border-b border-[#374D49] pb-4">
            <h3 class="font-bold text-lg text-white flex items-center gap-2">
                <i class="bi bi-briefcase text-[#377C80]"></i> Tambah Lowongan Pekerjaan
            </h3>
            <button onclick="closeModal('addJobModal')" class="text-[#B1CDCE]/60 hover:text-white transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <?= form_open('linkedin/create_job') ?>
            
            <label class="form-label">Nama Perusahaan <span class="text-red-500">*</span></label>
            <input type="text" name="company_name" class="form-input" required placeholder="Contoh: PT. ABC Sejahtera">

            <label class="form-label">Jenis Pekerjaan / Posisi <span class="text-red-500">*</span></label>
            <input type="text" name="job_title" class="form-input" required placeholder="Contoh: Software Engineer">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Tipe Pekerjaan</label>
                    <input type="text" name="job_type" class="form-input" placeholder="Contoh: Full-time / Part-time">
                </div>
                <div>
                    <label class="form-label">Jam Kerja</label>
                    <input type="text" name="working_hours" class="form-input" placeholder="Contoh: 08:00 - 17:00">
                </div>
            </div>

            <!-- Gaji Range Slider -->
            <div class="mb-4">
                <label class="form-label">Gaji / Rate Pekerjaan</label>
                <div class="relative w-full h-2 bg-[#22443F] rounded-full my-6 select-none">
                    <div id="slider-track" class="absolute h-full bg-[#377C80] rounded-full" style="left: 0%; width: 100%;"></div>
                    <!-- Minimal thumb slider -->
                    <input type="range" id="salary-min" min="1000000" max="50000000" step="500000" value="5000000" class="absolute pointer-events-none appearance-none bg-transparent w-full h-2 top-0 left-0 outline-none slider-input">
                    <!-- Maksimal thumb slider -->
                    <input type="range" id="salary-max" min="1000000" max="50000000" step="500000" value="20000000" class="absolute pointer-events-none appearance-none bg-transparent w-full h-2 top-0 left-0 outline-none slider-input">
                </div>
                <div class="flex items-center justify-between gap-4 mt-2">
                    <div class="flex-1 bg-black/20 border border-[#374D49] rounded-xl px-4 py-2 flex items-center">
                        <span class="text-xs text-[#B1CDCE] mr-1">IDR</span>
                        <input type="text" id="salary-min-display" class="bg-transparent border-none outline-none text-white text-sm font-semibold w-full" value="5.000.000">
                    </div>
                    <span class="text-[#B1CDCE]">—</span>
                    <div class="flex-1 bg-black/20 border border-[#374D49] rounded-xl px-4 py-2 flex items-center">
                        <span class="text-xs text-[#B1CDCE] mr-1">IDR</span>
                        <input type="text" id="salary-max-display" class="bg-transparent border-none outline-none text-white text-sm font-semibold w-full" value="20.000.000">
                    </div>
                </div>
                <input type="hidden" name="salary" id="salary-hidden-input">
            </div>

            <!-- Lokasi dengan Peta Leaflet -->
            <div class="mb-4">
                <label class="form-label">Lokasi Perusahaan <span class="text-red-500">*</span></label>
                <div class="flex gap-2 mb-2">
                    <input type="text" name="location" id="job-location-input" class="form-input" style="margin-bottom:0" required placeholder="Contoh: Jakarta Selatan">
                    <button type="button" id="btn-search-location" class="btn-primary shrink-0" style="padding: 10px 16px; border-radius: 12px;"><i class="bi bi-search"></i> Cari</button>
                </div>
                <div id="map-container" class="relative w-full h-[220px] rounded-xl overflow-hidden border border-[#374D49] mb-4">
                    <div id="map" class="w-full h-full" style="z-index: 1;"></div>
                </div>
            </div>

            <label class="form-label">Deskripsi Pekerjaan</label>
            <textarea name="description" class="form-input" rows="4" placeholder="Jelaskan kualifikasi dan tanggung jawab..."></textarea>

            <label class="form-label">Nama Publisher (Dipublikasikan Oleh)</label>
            <input type="text" class="form-input" value="<?= htmlspecialchars($user->full_name) ?>" readonly>

            <div class="flex justify-end mt-4">
                <button type="button" onclick="closeModal('addJobModal')" class="px-4 py-2 text-sm text-[#B1CDCE] hover:text-white mr-2">Batal</button>
                <button type="submit" class="btn-primary py-2 px-6">Simpan Lowongan</button>
            </div>

        <?= form_close() ?>
    </div>
</div>

<!-- Modal Lamar Pekerjaan -->
<div class="modal-overlay" id="applyJobModal" onclick="closeModalOutside(event, 'applyJobModal')">
    <div class="modal-content custom-scrollbar" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 border-b border-[#374D49] pb-4">
            <h3 class="font-bold text-lg text-white flex items-center gap-2">
                <i class="bi bi-send text-[#377C80]"></i> Lamar Pekerjaan
            </h3>
            <button onclick="closeModal('applyJobModal')" class="text-[#B1CDCE]/60 hover:text-white transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <?= form_open_multipart('linkedin/apply_job') ?>

            <input type="hidden" name="job_id" id="applyJobId">

            <label class="form-label">Posisi / Pekerjaan</label>
            <input type="text" id="applyJobTitle" class="form-input" readonly>

            <label class="form-label">Perusahaan</label>
            <input type="text" id="applyJobCompany" class="form-input" readonly>

            <label class="form-label">Upload CV <span class="text-red-500">*</span></label>
            <p class="text-[10px] text-[#B1CDCE]/60 mb-2">Format: PDF, DOC, DOCX, JPG, PNG. Maks 2MB.</p>
            
            <div id="dropzone" class="border-2 border-dashed border-[#374D49] hover:border-[#377C80] rounded-xl p-6 text-center cursor-pointer transition-all bg-black/25 flex flex-col items-center justify-center gap-2 mb-4 group">
                <input type="file" name="cv" id="cvInput" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required class="hidden">
                <div id="dropzonePrompt" class="flex flex-col items-center gap-2 text-[#B1CDCE]">
                    <i class="bi bi-cloud-arrow-up text-3xl text-[#377C80] group-hover:scale-110 transition-transform"></i>
                    <span class="text-xs font-semibold">Tarik &amp; lepas file CV di sini, atau <span class="text-[#E49438] underline">pilih file</span></span>
                </div>
                <div id="dropzonePreview" class="hidden flex flex-col items-center gap-2 w-full">
                    <div id="previewIconContainer" class="text-4xl text-[#E49438]">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </div>
                    <span id="previewFileName" class="text-xs font-bold text-white truncate max-w-[250px]">filename.pdf</span>
                    <span id="previewFileSize" class="text-[10px] text-[#B1CDCE]/70">1.2 MB</span>
                    <button type="button" onclick="resetDropzone(event)" class="text-[10px] text-red-400 hover:text-red-300 underline mt-1">Hapus &amp; Ganti File</button>
                </div>
            </div>

            <label class="form-label" style="margin-top:4px;">Keterangan / Motivasi <span class="text-[#B1CDCE]/50 font-normal">(opsional)</span></label>
            <textarea name="keterangan" class="form-input" rows="4" placeholder="Ceritakan singkat mengapa Anda tertarik dengan posisi ini..."></textarea>

            <div class="flex justify-end mt-4">
                <button type="button" onclick="closeModal('applyJobModal')" class="px-4 py-2 text-sm text-[#B1CDCE] hover:text-white mr-2">Batal</button>
                <button type="submit" class="btn-primary py-2 px-6"><i class="bi bi-send"></i> Kirim Lamaran</button>
            </div>

        <?= form_close() ?>
    </div>
</div>

<!-- Modal Open to Work -->
<div class="modal-overlay" id="otwModal" onclick="closeModalOutside(event, 'otwModal')">
    <div class="modal-content custom-scrollbar" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 border-b border-[#374D49] pb-4">
            <h3 class="font-bold text-lg text-white flex items-center gap-2" id="otwModalTitle">
                <i class="bi bi-person-check text-[#377C80]"></i> Open to Work
            </h3>
            <button onclick="closeModal('otwModal')" class="text-[#B1CDCE]/60 hover:text-white transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <?= form_open_multipart('linkedin/save_open_to_work') ?>

            <!-- Foto Profil (preview) -->
            <div class="flex items-center gap-4 mb-5 p-4 bg-black/20 rounded-xl border border-[#374D49]">
                <?php if (!empty($user->avatar)): ?>
                    <img src="<?= base_url($user->avatar) ?>" alt="Foto Profil" class="otw-profile-avatar">
                <?php else: ?>
                    <div class="otw-profile-avatar flex items-center justify-center font-bold text-white text-2xl select-none bg-teal-700/60 border border-[#374D49]/40 shrink-0">
                        <?= strtoupper(substr($user->full_name ?? 'U', 0, 1)) ?>
                    </div>
                <?php endif; ?>
                <div>
                    <div class="text-xs text-[#B1CDCE] mb-1 font-bold uppercase tracking-wider">Foto Profil</div>
                    <div class="text-base font-bold text-white"><?= htmlspecialchars($user->full_name) ?></div>
                    <div class="text-xs text-[#B1CDCE]/60 mt-1">Foto diambil dari profil Anda</div>
                </div>
            </div>

            <label class="form-label">Nama Lengkap</label>
            <input type="text" class="form-input" value="<?= htmlspecialchars($user->full_name) ?>" readonly>

            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="birth_date" id="otw_birth_date" class="form-input"
                   value="<?= htmlspecialchars($my_open_to_work->birth_date ?? '') ?>">

            <label class="form-label">Riwayat Pekerjaan</label>
            <textarea name="work_history" id="otw_work_history" class="form-input" rows="3" 
                      placeholder="Contoh: Staff IT di PT. XYZ (2020-2023), Freelance Designer (2018-2020)..."><?= htmlspecialchars($my_open_to_work->work_history ?? '') ?></textarea>

            <label class="form-label">Jenis Pekerjaan yang Diharapkan</label>
            <input type="text" name="desired_job" id="otw_desired_job" class="form-input" 
                   placeholder="Contoh: Software Engineer, Graphic Designer..."
                   value="<?= htmlspecialchars($my_open_to_work->desired_job ?? '') ?>">

            <label class="form-label">Upload CV <span class="text-[#B1CDCE]/50 font-normal">(opsional)</span></label>
            <p class="text-[10px] text-[#B1CDCE]/60 mb-2">Format: PDF, DOC, DOCX, JPG, PNG. Maks 2MB.</p>

            <?php if ($my_open_to_work && !empty($my_open_to_work->cv_path)): ?>
            <!-- Preview CV yang sudah ada (mode Edit) -->
            <div id="otwCurrentCvBox" class="mb-3 rounded-xl border border-[#374D49] bg-black/20 overflow-hidden">
                <div class="px-4 py-2 flex items-center justify-between bg-[#1a2a28] border-b border-[#374D49]">
                    <span class="text-[10px] font-bold text-[#B1CDCE] uppercase tracking-wider">
                        <i class="bi bi-file-earmark-check mr-1 text-[#7ecdd1]"></i>CV Saat Ini
                    </span>
                    <a href="<?= base_url($my_open_to_work->cv_path) ?>" target="_blank" download
                       class="text-[10px] text-[#7ecdd1] hover:text-white flex items-center gap-1 transition-colors">
                        <i class="bi bi-download"></i> Unduh
                    </a>
                </div>
                <?php
                    $cvExt = strtolower(pathinfo($my_open_to_work->cv_path, PATHINFO_EXTENSION));
                    $cvUrl = base_url($my_open_to_work->cv_path);
                ?>
                <?php if (in_array($cvExt, ['jpg','jpeg','png','webp','gif'])): ?>
                    <img src="<?= $cvUrl ?>" alt="Preview CV"
                         class="w-full max-h-[280px] object-contain bg-black/30 p-2">
                <?php elseif ($cvExt === 'pdf'): ?>
                    <iframe src="<?= $cvUrl ?>" class="w-full h-[280px] border-0" loading="lazy"></iframe>
                <?php else: ?>
                    <div class="flex items-center gap-3 px-4 py-4">
                        <i class="bi bi-file-earmark-word text-3xl text-blue-400"></i>
                        <span class="text-xs text-[#B1CDCE]"><?= htmlspecialchars(basename($my_open_to_work->cv_path)) ?></span>
                    </div>
                <?php endif; ?>
            </div>
            <?php endif; ?>

            <div id="otwDropzone" class="border-2 border-dashed border-[#374D49] hover:border-[#377C80] rounded-xl p-6 text-center cursor-pointer transition-all bg-black/25 flex flex-col items-center justify-center gap-2 mb-4 group">
                <input type="file" name="cv_file" id="otwCvInput" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" class="hidden">
                <div id="otwDropzonePrompt" class="flex flex-col items-center gap-2 text-[#B1CDCE]">
                    <i class="bi bi-cloud-arrow-up text-3xl text-[#377C80] group-hover:scale-110 transition-transform"></i>
                    <?php if ($my_open_to_work && !empty($my_open_to_work->cv_path)): ?>
                    <span class="text-xs font-semibold">Ganti CV — <span class="text-[#E49438] underline">pilih file baru</span></span>
                    <span class="text-[10px] text-[#B1CDCE]/50">Biarkan kosong jika tidak ingin mengganti</span>
                    <?php else: ?>
                    <span class="text-xs font-semibold">Tarik &amp; lepas file CV di sini, atau <span class="text-[#E49438] underline">pilih file</span></span>
                    <?php endif; ?>
                </div>
                <div id="otwDropzonePreview" class="hidden flex flex-col items-center gap-2 w-full">
                    <!-- Preview gambar baru (jika image) -->
                    <img id="otwNewImgPreview" src="" alt="Preview" class="hidden w-full max-h-[200px] object-contain rounded-lg mb-2">
                    <div id="otwPreviewIconContainer" class="text-4xl text-[#E49438]">
                        <i class="bi bi-file-earmark-pdf"></i>
                    </div>
                    <span id="otwPreviewFileName" class="text-xs font-bold text-white truncate max-w-[250px]">filename.pdf</span>
                    <span id="otwPreviewFileSize" class="text-[10px] text-[#B1CDCE]/70">1.2 MB</span>
                    <button type="button" onclick="resetOtwDropzone(event)" class="text-[10px] text-red-400 hover:text-red-300 underline mt-1">Hapus &amp; Ganti File</button>
                </div>
            </div>

            <label class="form-label">Tentang Saya</label>
            <textarea name="about" id="otw_about" class="form-input" rows="3" 
                      placeholder="Ceritakan tentang diri Anda, keahlian, dan tujuan karir..."><?= htmlspecialchars($my_open_to_work->about ?? '') ?></textarea>

            <div class="flex justify-between items-center mt-4">
                <div>
                    <?php if ($my_open_to_work): ?>
                        <a href="<?= base_url('linkedin/delete_open_to_work') ?>" class="px-4 py-2 text-sm text-red-400 hover:text-red-300 font-bold" onclick="return confirm('Yakin ingin menghapus profil Open to Work Anda?');"><i class="bi bi-trash"></i> Hapus Profil</a>
                    <?php endif; ?>
                </div>
                <div class="flex gap-2">
                    <button type="button" onclick="closeModal('otwModal')" class="px-4 py-2 text-sm text-[#B1CDCE] hover:text-white">Batal</button>
                    <button type="submit" class="btn-teal py-2 px-6"><i class="bi bi-check-lg"></i> Simpan</button>
                </div>
            </div>

        <?= form_close() ?>
    </div>
</div>

<!-- Modal Detail Pekerja -->
<div class="modal-overlay" id="workerDetailModal" onclick="closeModalOutside(event, 'workerDetailModal')">
    <div class="modal-content custom-scrollbar" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 border-b border-[#374D49] pb-4">
            <h3 class="font-bold text-lg text-white flex items-center gap-2">
                <i class="bi bi-person-circle text-[#377C80]"></i> Detail Rekan Kerja
            </h3>
            <button onclick="closeModal('workerDetailModal')" class="text-[#B1CDCE]/60 hover:text-white transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <!-- Worker Info Header -->
        <div class="flex items-center gap-4 mb-5">
            <div id="wdAvatarContainer" class="w-[72px] h-[72px] rounded-full overflow-hidden bg-teal-800 flex-shrink-0 flex items-center justify-center font-bold text-white text-2xl select-none border-3 border-teal-600/50 shadow-md">
                <img id="wdAvatar" src="" alt="Avatar" class="w-full h-full object-cover">
                <span id="wdAvatarInitial" class="hidden"></span>
            </div>
            <div>
                <h4 id="wdName" class="text-lg font-bold text-white"></h4>
                <p id="wdDesiredJob" class="text-sm text-[#E49438] font-semibold"></p>
                <div id="wdBadges" class="flex gap-2 mt-1"></div>
            </div>
        </div>

        <hr class="border-[#374D49] mb-4">

        <div class="space-y-4">
            <div id="wdBirthRow" class="hidden">
                <div class="text-xs text-[#B1CDCE] font-bold uppercase tracking-wider mb-1"><i class="bi bi-calendar3 mr-1"></i>Tanggal Lahir</div>
                <div id="wdBirth" class="text-sm text-white"></div>
            </div>
            <div id="wdWorkHistoryRow" class="hidden">
                <div class="text-xs text-[#B1CDCE] font-bold uppercase tracking-wider mb-1"><i class="bi bi-briefcase mr-1"></i>Riwayat Pekerjaan</div>
                <div id="wdWorkHistory" class="text-sm text-white leading-relaxed whitespace-pre-wrap"></div>
            </div>
            <div id="wdAboutRow" class="hidden">
                <div class="text-xs text-[#B1CDCE] font-bold uppercase tracking-wider mb-1"><i class="bi bi-info-circle mr-1"></i>Tentang</div>
                <div id="wdAbout" class="text-sm text-white leading-relaxed"></div>
            </div>
            <div id="wdCvRow" class="hidden pt-2">
                <div class="text-xs text-[#B1CDCE] font-bold uppercase tracking-wider mb-2"><i class="bi bi-file-earmark-text mr-1"></i>Curriculum Vitae</div>

                <div id="wdCvPreview" class="hidden mb-3 rounded-lg overflow-hidden border border-[#374D49] bg-black/20"></div>

                <div id="wdCvNoPreview" class="hidden mb-3 flex items-center gap-3 p-3 rounded-lg border border-[#374D49] bg-black/20">
                    <i id="wdCvNoPreviewIcon" class="text-3xl text-[#E49438]"></i>
                    <span class="text-xs text-[#B1CDCE]">Pratinjau tidak tersedia untuk tipe file ini.</span>
                </div>

                <a id="wdCvLink" href="#" download class="inline-flex items-center gap-2 bg-[#377C80]/20 hover:bg-[#377C80]/40 border border-[#377C80]/50 text-[#7ecdd1] hover:text-white px-4 py-2 rounded-lg text-sm font-semibold transition-all">
                    <i class="bi bi-download"></i> Unduh CV
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    // ======================== Tab switching ========================
    function switchTab(tab, btn) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.remove('hidden');
        btn.classList.add('active');
    }

    // Auto-switch to pekerja tab if URL has tab=pekerja
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('tab') === 'pekerja' || urlParams.has('worker_search') || urlParams.has('worker_job')) {
            switchTab('pekerja', document.getElementById('tab-btn-pekerja'));
        }
    });

    // ======================== Modal ========================
    function openModal(id) {
        document.getElementById(id).classList.add('open');
        document.body.style.overflow = 'hidden';
        if (id === 'addJobModal') {
            setTimeout(initMapAndSlider, 200);
        }
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
        document.body.style.overflow = '';
    }

    let jobMap = null;
    let jobMarker = null;

    function initMapAndSlider() {
        initSalarySlider();

        if (!jobMap) {
            const defaultLat = -6.175392;
            const defaultLng = 106.827153;
            
            jobMap = L.map('map').setView([defaultLat, defaultLng], 13);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(jobMap);
            
            jobMarker = L.marker([defaultLat, defaultLng], { draggable: true }).addTo(jobMap);
            
            jobMarker.on('dragend', function (e) {
                const position = jobMarker.getLatLng();
                updateLocationFromCoords(position.lat, position.lng);
            });
            
            jobMap.on('click', function(e) {
                jobMarker.setLatLng(e.latlng);
                updateLocationFromCoords(e.latlng.lat, e.latlng.lng);
            });

            const btnSearch = document.getElementById('btn-search-location');
            if (btnSearch) {
                btnSearch.addEventListener('click', function() {
                    const query = document.getElementById('job-location-input').value;
                    if (!query) return;
                    
                    fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`)
                        .then(response => response.json())
                        .then(data => {
                            if (data && data.length > 0) {
                                const lat = parseFloat(data[0].lat);
                                const lon = parseFloat(data[0].lon);
                                jobMap.setView([lat, lon], 15);
                                jobMarker.setLatLng([lat, lon]);
                            }
                        });
                });
            }
        } else {
            setTimeout(() => {
                jobMap.invalidateSize();
            }, 100);
        }
    }

    function updateLocationFromCoords(lat, lng) {
        const locationInput = document.getElementById('job-location-input');
        locationInput.value = `Memuat alamat... (${lat.toFixed(6)}, ${lng.toFixed(6)})`;
        
        fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.display_name) {
                    locationInput.value = data.display_name;
                } else {
                    locationInput.value = `Lokasi (${lat.toFixed(6)}, ${lng.toFixed(6)})`;
                }
            })
            .catch(err => {
                locationInput.value = `Lokasi (${lat.toFixed(6)}, ${lng.toFixed(6)})`;
            });
    }

    let sliderInitialized = false;
    function initSalarySlider() {
        if (sliderInitialized) return;
        sliderInitialized = true;

        const minInput = document.getElementById('salary-min');
        const maxInput = document.getElementById('salary-max');
        const minDisplay = document.getElementById('salary-min-display');
        const maxDisplay = document.getElementById('salary-max-display');
        const track = document.getElementById('slider-track');
        const hiddenInput = document.getElementById('salary-hidden-input');

        function formatRupiah(value) {
            return new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(value);
        }

        function parseRupiah(value) {
            return parseInt(value.replace(/[^0-9]/g, '')) || 0;
        }

        function updateSlider() {
            let minVal = parseInt(minInput.value);
            let maxVal = parseInt(maxInput.value);

            if (minVal > maxVal) {
                let temp = minVal;
                minVal = maxVal;
                maxVal = temp;
                minInput.value = minVal;
                maxInput.value = maxVal;
            }

            const minPercent = ((minVal - minInput.min) / (minInput.max - minInput.min)) * 100;
            const maxPercent = ((maxVal - maxInput.min) / (maxInput.max - maxInput.min)) * 100;

            track.style.left = minPercent + '%';
            track.style.width = (maxPercent - minPercent) + '%';

            minDisplay.value = formatRupiah(minVal);
            maxDisplay.value = formatRupiah(maxVal);

            hiddenInput.value = `Rp ${formatRupiah(minVal)} - Rp ${formatRupiah(maxVal)}`;
        }

        minInput.addEventListener('input', updateSlider);
        maxInput.addEventListener('input', updateSlider);

        minDisplay.addEventListener('change', function() {
            let val = parseRupiah(minDisplay.value);
            if (val < parseInt(minInput.min)) val = parseInt(minInput.min);
            if (val > parseInt(maxInput.value)) val = parseInt(maxInput.value);
            minInput.value = val;
            updateSlider();
        });

        maxDisplay.addEventListener('change', function() {
            let val = parseRupiah(maxDisplay.value);
            if (val > parseInt(maxInput.max)) val = parseInt(maxInput.max);
            if (val < parseInt(minInput.value)) val = parseInt(minInput.value);
            maxInput.value = val;
            updateSlider();
        });

        updateSlider();
    }
    function closeModalOutside(e, id) {
        if (e.target.id === id) {
            closeModal(id);
        }
    }
    document.addEventListener('keydown', e => { 
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay').forEach(el => {
                el.classList.remove('open');
            });
            document.body.style.overflow = '';
        }
    });

    // ======================== Open to Work Modal ========================
    function openOTWModal(isEdit) {
        const title = document.getElementById('otwModalTitle');
        if (isEdit) {
            title.innerHTML = '<i class="bi bi-pencil-square text-[#377C80]"></i> Edit Open to Work';
        } else {
            title.innerHTML = '<i class="bi bi-person-check text-[#377C80]"></i> Open to Work';
        }
        openModal('otwModal');
    }

    // ======================== Worker Detail Modal ========================
    function openWorkerDetailModal(data) {
        const img = document.getElementById('wdAvatar');
        const initial = document.getElementById('wdAvatarInitial');
        
        if (data.avatar) {
            img.src = data.avatar;
            img.classList.remove('hidden');
            initial.classList.add('hidden');
        } else {
            img.classList.add('hidden');
            initial.textContent = (data.full_name || 'U').charAt(0).toUpperCase();
            initial.classList.remove('hidden');
        }
        
        document.getElementById('wdName').textContent = data.full_name;
        document.getElementById('wdDesiredJob').textContent = data.desired_job || data.work_role || 'Tidak ada role spesifik';

        // Badges
        let badges = '<span class="px-2 py-1 bg-[#377C80]/20 text-[#7ecdd1] text-[10px] font-bold rounded">Open to Work</span>';
        if (parseInt(data.is_fresh_graduate)) {
            badges += '<span class="px-2 py-1 bg-green-900/40 text-green-400 text-[10px] font-bold rounded border border-green-700/50">Fresh Graduate</span>';
        } else if (data.work_history && data.work_history.trim() !== '') {
            badges += '<span class="px-2 py-1 bg-blue-900/40 text-blue-400 text-[10px] font-bold rounded border border-blue-700/50">Berpengalaman</span>';
        }
        document.getElementById('wdBadges').innerHTML = badges;

        // Tanggal lahir
        if (data.birth_date) {
            document.getElementById('wdBirth').textContent = formatDate(data.birth_date);
            document.getElementById('wdBirthRow').classList.remove('hidden');
        } else {
            document.getElementById('wdBirthRow').classList.add('hidden');
        }

        // Riwayat pekerjaan
        if (data.work_history) {
            document.getElementById('wdWorkHistory').textContent = data.work_history;
            document.getElementById('wdWorkHistoryRow').classList.remove('hidden');
        } else {
            document.getElementById('wdWorkHistoryRow').classList.add('hidden');
        }

        // Tentang
        if (data.about) {
            document.getElementById('wdAbout').textContent = data.about;
            document.getElementById('wdAboutRow').classList.remove('hidden');
        } else {
            document.getElementById('wdAboutRow').classList.add('hidden');
        }

        // CV
        if (data.cv_path) {
            const cvUrl = '<?= base_url() ?>' + data.cv_path;
            const ext = data.cv_path.split('.').pop().toLowerCase();

            const wdCvLink = document.getElementById('wdCvLink');
            wdCvLink.href = cvUrl;
            wdCvLink.setAttribute('download', '');

            const previewBox = document.getElementById('wdCvPreview');
            const noPreviewBox = document.getElementById('wdCvNoPreview');

            if (ext === 'pdf') {
                previewBox.innerHTML = `<iframe src="${cvUrl}" class="w-full" style="height:400px;border:0;"></iframe>`;
                previewBox.classList.remove('hidden');
                noPreviewBox.classList.add('hidden');
            } else if (['jpg','jpeg','png'].includes(ext)) {
                previewBox.innerHTML = `<img src="${cvUrl}" class="w-full object-contain max-h-[400px]">`;
                previewBox.classList.remove('hidden');
                noPreviewBox.classList.add('hidden');
            } else {
                previewBox.classList.add('hidden');
                previewBox.innerHTML = '';
                const icon = (ext === 'doc' || ext === 'docx') ? 'bi-file-earmark-word-fill text-blue-400' : 'bi-file-earmark';
                document.getElementById('wdCvNoPreviewIcon').className = 'text-3xl ' + icon;
                noPreviewBox.classList.remove('hidden');
            }

            document.getElementById('wdCvRow').classList.remove('hidden');
        } else {
            document.getElementById('wdCvRow').classList.add('hidden');
            document.getElementById('wdCvPreview').classList.add('hidden');
            document.getElementById('wdCvNoPreview').classList.add('hidden');
        }

        openModal('workerDetailModal');
    }

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        const d = new Date(dateStr);
        if (isNaN(d)) return dateStr;
        const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
        return d.getDate() + ' ' + months[d.getMonth()] + ' ' + d.getFullYear();
    }

    // ======================== Job Detail ========================
    function loadJobDetail(id, el) {
        document.querySelectorAll('.job-item').forEach(item => item.classList.remove('active'));
        el.classList.add('active');

        const panel = document.getElementById('jobDetailPanel');
        const loader = document.getElementById('jobDetailLoader');
        const content = document.getElementById('jobDetailContent');

        if (window.innerWidth < 1024) {
            document.querySelector('.job-list-container').style.display = 'none';
            panel.style.width = '100%';
        }
        
        panel.classList.add('active');
        loader.style.display = 'block';
        content.classList.add('hidden');

        fetch(`<?= base_url('linkedin/get_job/') ?>${id}`)
            .then(res => res.json())
            .then(res => {
                loader.style.display = 'none';
                if(res.status === 'success') {
                    const data = res.data;
                    document.getElementById('jdTitle').textContent = data.job_title;
                    document.getElementById('jdCompany').textContent = data.company_name;
                    document.getElementById('jdLocation').textContent = data.location || '-';
                    document.getElementById('jdType').textContent = data.job_type || '-';
                    document.getElementById('jdSalary').textContent = data.salary || '-';
                    document.getElementById('jdHours').textContent = data.working_hours || '-';
                    document.getElementById('jdDescription').textContent = data.description || 'Tidak ada deskripsi.';
                    document.getElementById('jdPublisher').textContent = data.publisher_name;

                    const actionArea = document.getElementById('applyActionArea');
                    if (res.is_owner) {
                        actionArea.innerHTML = `<div style="display:inline-flex;align-items:center;gap:8px;background:rgba(228,148,56,0.15);border:1px solid rgba(228,148,56,0.4);color:#E49438;padding:10px 18px;border-radius:50px;font-weight:bold;font-size:0.85rem;"><i class="bi bi-person-fill"></i> Lowongan Anda</div>`;
                    } else if (res.has_applied) {
                        actionArea.innerHTML = `<div style="display:inline-flex;align-items:center;gap:8px;background:rgba(55,124,128,0.15);border:1px solid rgba(55,124,128,0.4);color:#7ecdd1;padding:10px 18px;border-radius:50px;font-weight:bold;font-size:0.85rem;"><i class="bi bi-check-circle-fill"></i> Sudah Melamar</div>`;
                    } else {
                        actionArea.innerHTML = `<button onclick="openApplyModal(${data.id}, '${escapeAttr(data.job_title)}', '${escapeAttr(data.company_name)}')" class="btn-primary w-full" style="justify-content:center;"><i class="bi bi-send"></i> Lamar Pekerjaan</button>`;
                    }

                    content.classList.remove('hidden');
                } else {
                    loader.textContent = 'Gagal memuat detail pekerjaan.';
                    loader.style.display = 'block';
                }
            })
            .catch(() => {
                loader.textContent = 'Terjadi kesalahan jaringan.';
                loader.style.display = 'block';
            });
    }

    function closeJobDetail() {
        document.getElementById('jobDetailPanel').classList.remove('active');
        if (window.innerWidth < 1024) {
            document.querySelector('.job-list-container').style.display = 'block';
        }
        document.querySelectorAll('.job-item').forEach(item => item.classList.remove('active'));
    }

    function openApplyModal(jobId, jobTitle, companyName) {
        document.getElementById('applyJobId').value = jobId;
        document.getElementById('applyJobTitle').value = jobTitle;
        document.getElementById('applyJobCompany').value = companyName;
        resetDropzone();
        openModal('applyJobModal');
    }

    function escapeAttr(str) {
        return (str || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }

    // --- Drag & Drop logic ---
    const dropzone = document.getElementById('dropzone');
    const cvInput = document.getElementById('cvInput');
    const dropzonePrompt = document.getElementById('dropzonePrompt');
    const dropzonePreview = document.getElementById('dropzonePreview');
    const previewFileName = document.getElementById('previewFileName');
    const previewFileSize = document.getElementById('previewFileSize');
    const previewIconContainer = document.getElementById('previewIconContainer');

    if (dropzone) {
        dropzone.addEventListener('click', () => {
            cvInput.click();
        });

        cvInput.addEventListener('change', (e) => {
            handleFiles(e.target.files);
        });

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-[#377C80]', 'bg-[#377C80]/10');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-[#377C80]', 'bg-[#377C80]/10');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-[#377C80]', 'bg-[#377C80]/10');
            if (e.dataTransfer.files.length) {
                cvInput.files = e.dataTransfer.files;
                handleFiles(e.dataTransfer.files);
            }
        });
    }

    function handleFiles(files) {
        if (files.length === 0) return;
        const file = files[0];
        
        previewFileName.textContent = file.name;
        previewFileSize.textContent = formatBytes(file.size);
        
        let icon = '<i class="bi bi-file-earmark"></i>';
        if (file.type.includes('pdf')) {
            icon = '<i class="bi bi-file-earmark-pdf-fill text-red-400"></i>';
        } else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
            icon = '<i class="bi bi-file-earmark-word-fill text-blue-400"></i>';
        } else if (file.type.includes('image')) {
            icon = '<i class="bi bi-file-earmark-image-fill text-green-400"></i>';
        }
        previewIconContainer.innerHTML = icon;
        
        dropzonePrompt.classList.add('hidden');
        dropzonePreview.classList.remove('hidden');
    }

    function resetDropzone(e) {
        if (e) e.stopPropagation();
        cvInput.value = '';
        dropzonePrompt.classList.remove('hidden');
        dropzonePreview.classList.add('hidden');
    }

    function formatBytes(bytes, decimals = 2) {
        if (!+bytes) return '0 Bytes';
        const k = 1024;
        const dm = decimals < 0 ? 0 : decimals;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return `${parseFloat((bytes / Math.pow(k, i)).toFixed(dm))} ${sizes[i]}`;
    }

    // --- OTW Drag & Drop logic ---
    const otwDropzone = document.getElementById('otwDropzone');
    const otwCvInput = document.getElementById('otwCvInput');
    const otwDropzonePrompt = document.getElementById('otwDropzonePrompt');
    const otwDropzonePreview = document.getElementById('otwDropzonePreview');
    const otwPreviewFileName = document.getElementById('otwPreviewFileName');
    const otwPreviewFileSize = document.getElementById('otwPreviewFileSize');
    const otwPreviewIconContainer = document.getElementById('otwPreviewIconContainer');

    if (otwDropzone) {
        otwDropzone.addEventListener('click', () => {
            otwCvInput.click();
        });

        otwCvInput.addEventListener('change', (e) => {
            handleOtwFiles(e.target.files);
        });

        otwDropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            otwDropzone.classList.add('border-[#377C80]', 'bg-[#377C80]/10');
        });

        otwDropzone.addEventListener('dragleave', () => {
            otwDropzone.classList.remove('border-[#377C80]', 'bg-[#377C80]/10');
        });

        otwDropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            otwDropzone.classList.remove('border-[#377C80]', 'bg-[#377C80]/10');
            if (e.dataTransfer.files.length) {
                otwCvInput.files = e.dataTransfer.files;
                handleOtwFiles(e.dataTransfer.files);
            }
        });
    }

    function handleOtwFiles(files) {
        if (files.length === 0) return;
        const file = files[0];
        
        otwPreviewFileName.textContent = file.name;
        otwPreviewFileSize.textContent = formatBytes(file.size);
        
        const imgPreview = document.getElementById('otwNewImgPreview');

        if (file.type.includes('image')) {
            // Tampilkan preview gambar langsung
            const reader = new FileReader();
            reader.onload = (ev) => {
                imgPreview.src = ev.target.result;
                imgPreview.classList.remove('hidden');
            };
            reader.readAsDataURL(file);
            otwPreviewIconContainer.innerHTML = '';
        } else {
            imgPreview.classList.add('hidden');
            imgPreview.src = '';
            let icon = '<i class="bi bi-file-earmark"></i>';
            if (file.type.includes('pdf')) {
                icon = '<i class="bi bi-file-earmark-pdf-fill text-red-400"></i>';
            } else if (file.type.includes('word') || file.name.endsWith('.doc') || file.name.endsWith('.docx')) {
                icon = '<i class="bi bi-file-earmark-word-fill text-blue-400"></i>';
            }
            otwPreviewIconContainer.innerHTML = icon;
        }
        
        otwDropzonePrompt.classList.add('hidden');
        otwDropzonePreview.classList.remove('hidden');
    }

    function resetOtwDropzone(e) {
        if (e) e.stopPropagation();
        otwCvInput.value = '';
        otwDropzonePrompt.classList.remove('hidden');
        otwDropzonePreview.classList.add('hidden');
        const imgPreview = document.getElementById('otwNewImgPreview');
        if (imgPreview) { imgPreview.classList.add('hidden'); imgPreview.src = ''; }
    }
</script>