<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$current_page = isset($active_menu) ? $active_menu : ($this->uri->segment(2) ? $this->uri->segment(2) : $this->uri->segment(1));
?>
<!-- ================= SIDEBAR ================= -->
<aside id="adminSidebar" class="w-72 bg-brand-dark border-r border-brand-medium/30 flex flex-col shrink-0 fixed inset-y-0 left-0 z-50 transform -translate-x-full md:sticky md:top-0 md:translate-x-0 transition-transform duration-300 h-screen">
    
    <button onclick="document.getElementById('adminSidebar').classList.add('-translate-x-full');" class="md:hidden absolute top-4 right-4 text-white/50 hover:text-white">
        <i class="bi bi-x-lg text-xl"></i>
    </button>
    <!-- Profile Section -->
    <div class="p-6 border-b border-brand-medium/30 flex items-center gap-4 mt-6 md:mt-0">
        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand-medium to-brand-dark flex items-center justify-center text-white font-bold text-xl border border-brand-medium shadow-sm">
            <?= strtoupper(substr($admin_name ?? 'A', 0, 1)) ?>
        </div>
        <div>
            <h2 class="font-display font-bold text-sm tracking-tight text-white"><?= htmlspecialchars($admin_name ?? 'Admin') ?></h2>
            <p class="text-xs text-brand-light/80 mt-0.5 capitalize"><?= htmlspecialchars(str_replace('_', ' ', $admin_role ?? 'Administrator')) ?></p>
        </div>
    </div>

    <!-- Sidebar Navigation -->
    <nav class="flex-1 p-4 space-y-1.5 overflow-y-auto overscroll-contain">
        
        <!-- Dashboard -->
        <a href="<?= base_url('admin') ?>" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'admin' || $current_page == 'dashboard') ? 'bg-gradient-to-r from-brand-medium to-brand-dark/20 text-white border-l-4 border-white shadow-md' : 'text-white/80 hover:text-white hover:bg-brand-medium/20' ?>">
            <img src="<?= base_url('assets/images/3d_dashboard.png') ?>" class="w-9 h-9 object-contain" alt="Dashboard">
            <span>Dashboard</span>
        </a>

        <!-- Kelola Silsilah -->
        <a href="<?= base_url('admin/silsilah') ?>" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'silsilah') ? 'bg-gradient-to-r from-brand-medium to-brand-dark/20 text-white border-l-4 border-white shadow-md' : 'text-white/80 hover:text-white hover:bg-brand-medium/20' ?>">
            <img src="<?= base_url('assets/images/3d_silsilah.png') ?>" class="w-9 h-9 object-contain" alt="Kelola Silsilah">
            <span>Kelola Silsilah</span>
        </a>

        <!-- Kelola Wasiat -->
        <a href="#" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'wasiat') ? 'bg-gradient-to-r from-brand-medium to-brand-dark/20 text-white border-l-4 border-white shadow-md' : 'text-white/80 hover:text-white hover:bg-brand-medium/20' ?>">
            <img src="<?= base_url('assets/images/3d_wasiat.png') ?>" class="w-9 h-9 object-contain" alt="Kelola Wasiat">
            <span>Kelola Wasiat</span>
        </a>

        <!-- Kelola Forum Diskusi -->
        <a href="<?= base_url('admin/forum') ?>" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'forum') ? 'bg-gradient-to-r from-brand-medium to-brand-dark/20 text-white border-l-4 border-white shadow-md' : 'text-white/80 hover:text-white hover:bg-brand-medium/20' ?>">
            <img src="<?= base_url('assets/images/3d_forum.png') ?>" class="w-9 h-9 object-contain" alt="Kelola Forum Diskusi">
            <span>Kelola Forum Diskusi</span>
        </a>

        <!-- Kelola Berita -->
        <a href="<?= base_url('admin/berita') ?>" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'berita') ? 'bg-gradient-to-r from-brand-medium to-brand-dark/20 text-white border-l-4 border-white shadow-md' : 'text-white/80 hover:text-white hover:bg-brand-medium/20' ?>">
            <img src="<?= base_url('assets/images/3d_berita.png') ?>" class="w-9 h-9 object-contain" alt="Kelola Berita">
            <span>Kelola Berita</span>
        </a>

        <!-- Kelola Yayasan -->
        <a href="#" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'yayasan') ? 'bg-gradient-to-r from-brand-medium to-brand-dark/20 text-white border-l-4 border-white shadow-md' : 'text-white/80 hover:text-white hover:bg-brand-medium/20' ?>">
            <img src="<?= base_url('assets/images/3d_yayasan.png') ?>" class="w-9 h-9 object-contain" alt="Kelola Yayasan">
            <span>Kelola Yayasan</span>
        </a>

        <!-- Kelola Banner Profil -->
        <a href="<?= base_url('admin/banner_profil') ?>" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'banner_profil') ? 'bg-gradient-to-r from-brand-medium to-brand-dark/20 text-white border-l-4 border-white shadow-md' : 'text-white/80 hover:text-white hover:bg-brand-medium/20' ?>">
            <img src="<?= base_url('assets/images/3d_banner.png') ?>" class="w-9 h-9 object-contain" alt="Kelola Banner Profil">
            <span>Kelola Banner Profil</span>
        </a>

        <!-- Kelola Pengguna -->
        <a href="<?= base_url('admin/pengguna') ?>" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'pengguna') ? 'bg-gradient-to-r from-brand-medium to-brand-dark/20 text-white border-l-4 border-white shadow-md' : 'text-white/80 hover:text-white hover:bg-brand-medium/20' ?>">
            <img src="<?= base_url('assets/images/3d_pengguna.png') ?>" class="w-9 h-9 object-contain" alt="Kelola Pengguna">
            <span>Kelola Pengguna</span>
        </a>

        <!-- History Log -->
        <a href="<?= base_url('admin/history_log') ?>" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'history_log') ? 'bg-gradient-to-r from-brand-medium to-brand-dark/20 text-white border-l-4 border-white shadow-md' : 'text-white/80 hover:text-white hover:bg-brand-medium/20' ?>">
            <img src="<?= base_url('assets/images/3d_history.png') ?>" class="w-9 h-9 object-contain" alt="History Log">
            <span>History Log</span>
        </a>
            
        <!-- Kelola Lowongan -->
        <a href="<?= base_url('admin/lowongan') ?>" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'lowongan') ? 'bg-gradient-to-r from-brand-medium to-brand-dark/20 text-white border-l-4 border-white shadow-md' : 'text-white/80 hover:text-white hover:bg-brand-medium/20' ?>">
            <img src="<?= base_url('assets/images/3d_lowongan.png') ?>" class="w-9 h-9 object-contain" alt="Kelola Lowongan">
            <span>Kelola Lowongan</span>
        </a>

        <!-- Kelola Pekerja -->
        <a href="<?= base_url('admin/pekerja') ?>" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'pekerja') ? 'bg-gradient-to-r from-brand-medium to-brand-dark/20 text-white border-l-4 border-white shadow-md' : 'text-white/80 hover:text-white hover:bg-brand-medium/20' ?>">
            <img src="<?= base_url('assets/images/3d_pekerja.png') ?>" class="w-9 h-9 object-contain" alt="Kelola Pekerja">
            <span>Kelola Pekerja</span>
        </a>

        <!-- Pengaturan -->
        <a href="#" 
           class="flex items-center gap-3.5 px-4 py-3 rounded-lg text-sm font-medium transition-all <?= ($current_page == 'pengaturan') ? 'bg-gradient-to-r from-brand-medium to-brand-dark/20 text-white border-l-4 border-white shadow-md' : 'text-white/80 hover:text-white hover:bg-brand-medium/20' ?>">
            <img src="<?= base_url('assets/images/3d_settings.png') ?>" class="w-9 h-9 object-contain" alt="Pengaturan">
            <span>Pengaturan</span>
        </a>

    </nav>

    <!-- Footer / Logout -->
	<div class="p-4 border-t border-brand-medium/30">
		<a href="<?= base_url('auth/logout') ?>" 
		class="flex items-center justify-center gap-3 px-4 py-3 rounded-lg text-sm font-bold text-white bg-red-500 hover:bg-red-600 transition-all shadow-md">
			<i class="bi bi-box-arrow-right text-lg"></i>
			<span>Logout</span>
		</a>
	</div>

</aside>

<!-- Admin Theme Styles Override -->
<style>
    /* Dark Mode styles (Default admin theme is dark) */
    body {
        transition: background-color 0.3s ease, color 0.3s ease !important;
    }
    
    /* Light Mode overrides */
    body[data-theme="light"] {
        background-color: #F8F9FA !important;
        color: #1e293b !important;
    }
    
    body[data-theme="light"] .bg-teal-950 {
        background-color: #F8F9FA !important;
    }
    
    body[data-theme="light"] main {
        background-color: #F8F9FA !important;
    }
    
    body[data-theme="light"] #adminSidebar {
        background-color: #274D4F !important;
        border-right: 1px solid rgba(255,255,255,0.1) !important;
    }
    
    body[data-theme="light"] header {
        background: #274D4F !important;
        border-bottom: 1px solid rgba(255,255,255,0.1) !important;
    }
    
    /* Welcome Widget */
    body[data-theme="light"] .relative.overflow-hidden.bg-gradient-to-r.from-teal-900 {
        background: linear-gradient(135deg, #274D4F, #3D6C63) !important;
        border-color: #3D6C63 !important;
    }
    body[data-theme="light"] .relative.overflow-hidden.bg-gradient-to-r.from-teal-900 h2,
    body[data-theme="light"] .relative.overflow-hidden.bg-gradient-to-r.from-teal-900 p {
        color: #ffffff !important;
    }
    
    /* Cards and Table Wrappers */
    body[data-theme="light"] .bg-teal-900\/60,
    body[data-theme="light"] .bg-teal-900\/50,
    body[data-theme="light"] .bg-teal-900\/40,
    body[data-theme="light"] .bg-teal-900\/30,
    body[data-theme="light"] .bg-teal-900,
    body[data-theme="light"] .bg-teal-800,
    body[data-theme="light"] .bg-brand-dark\/20,
    body[data-theme="light"] .bg-slate-900,
    body[data-theme="light"] .bg-teal-950\/30 {
        background-color: #ffffff !important;
        color: #1e293b !important;
        border-color: #cbd5e1 !important;
        box-shadow: 0 4px 6px -1px rgba(39, 77, 79, 0.05), 0 2px 4px -1px rgba(39, 77, 79, 0.03) !important;
    }
    
    body[data-theme="light"] .hover\:bg-teal-900:hover,
    body[data-theme="light"] .hover\:bg-teal-850:hover {
        background-color: #f8fafc !important;
    }
    
    /* Table headers and rows */
    body[data-theme="light"] .bg-teal-950\/50 {
        background-color: #e2e8f0 !important;
        color: #1e293b !important;
    }
    
    body[data-theme="light"] tr {
        border-color: #e2e8f0 !important;
    }
    
    body[data-theme="light"] tr:hover {
        background-color: #f1f5f9 !important;
    }
    
    body[data-theme="light"] th {
        color: #475569 !important;
        border-color: #e2e8f0 !important;
    }
    
    body[data-theme="light"] td {
        color: #1e293b !important;
        border-color: #e2e8f0 !important;
    }
    
    /* Text colors */
    body[data-theme="light"] .text-white,
    body[data-theme="light"] .text-brand-light {
        color: #1e293b !important;
    }
    
    body[data-theme="light"] .text-white\/90,
    body[data-theme="light"] .text-white\/80,
    body[data-theme="light"] .text-white\/70,
    body[data-theme="light"] .text-white\/60,
    body[data-theme="light"] .text-white\/40,
    body[data-theme="light"] .text-white\/30,
    body[data-theme="light"] .text-brand-light\/90,
    body[data-theme="light"] .text-brand-light\/80,
    body[data-theme="light"] .text-brand-light\/70,
    body[data-theme="light"] .text-brand-light\/60,
    body[data-theme="light"] .text-brand-light\/40,
    body[data-theme="light"] .text-teal-100,
    body[data-theme="light"] .text-teal-200,
    body[data-theme="light"] .text-teal-300 {
        color: #475569 !important;
    }
    
    body[data-theme="light"] .text-teal-400,
    body[data-theme="light"] .text-teal-500 {
        color: #274D4F !important;
    }

    /* Primary buttons gradient text color */
    body[data-theme="light"] a[class*="bg-gradient"] *,
    body[data-theme="light"] a[class*="bg-gradient"] span,
    body[data-theme="light"] a[class*="bg-gradient"] i,
    body[data-theme="light"] a[class*="bg-gradient"] {
        color: #ffffff !important;
    }

    /* Gender Badge */
    body[data-theme="light"] .text-blue-300 {
        color: #1d4ed8 !important;
    }
    body[data-theme="light"] .bg-blue-500\/20 {
        background-color: rgba(59, 130, 246, 0.15) !important;
    }
    body[data-theme="light"] .text-pink-300 {
        color: #be185d !important;
    }
    body[data-theme="light"] .bg-pink-500\/20 {
        background-color: rgba(236, 72, 153, 0.15) !important;
    }

    /* Status & Approval Badges (Green/Red/Yellow overrides) */
    body[data-theme="light"] .text-emerald-300 {
        color: #15803d !important; /* dark green */
    }
    body[data-theme="light"] .bg-emerald-500\/20 {
        background-color: rgba(16, 185, 129, 0.15) !important;
    }
    body[data-theme="light"] .border-emerald-500\/30 {
        border-color: rgba(16, 185, 129, 0.3) !important;
    }

    body[data-theme="light"] .text-red-300 {
        color: #b91c1c !important; /* dark red */
    }
    body[data-theme="light"] .bg-red-500\/20 {
        background-color: rgba(239, 68, 68, 0.15) !important;
    }
    body[data-theme="light"] .border-red-500\/30 {
        border-color: rgba(239, 68, 68, 0.3) !important;
    }

    body[data-theme="light"] .text-yellow-300 {
        color: #a16207 !important; /* dark gold/yellow */
    }
    body[data-theme="light"] .bg-yellow-500\/20 {
        background-color: rgba(234, 179, 8, 0.15) !important;
    }
    body[data-theme="light"] .border-yellow-500\/30 {
        border-color: rgba(234, 179, 8, 0.3) !important;
    }
    
    /* Borders */
    body[data-theme="light"] .border-teal-800,
    body[data-theme="light"] .border-teal-700,
    body[data-theme="light"] .border-teal-900,
    body[data-theme="light"] .border-brand-medium\/30,
    body[data-theme="light"] .divide-teal-800\/50 > :not([hidden]) ~ :not([hidden]),
    body[data-theme="light"] .divide-y > :not([hidden]) ~ :not([hidden]) {
        border-color: #cbd5e1 !important;
    }
    
    /* Inputs */
    body[data-theme="light"] input,
    body[data-theme="light"] textarea,
    body[data-theme="light"] select {
        background-color: #ffffff !important;
        color: #1e293b !important;
        border-color: #cbd5e1 !important;
    }
    
    body[data-theme="light"] input:focus,
    body[data-theme="light"] textarea:focus,
    body[data-theme="light"] select:focus {
        border-color: #274D4F !important;
        --tw-ring-color: #274D4F !important;
    }
    
    body[data-theme="light"] input::placeholder,
    body[data-theme="light"] textarea::placeholder {
        color: #64748b !important;
    }
    
    body[data-theme="light"] .bi-search {
        color: #64748b !important;
    }
    
    /* Header text must stay white in both themes */
    body[data-theme="light"] header .text-white {
        color: #ffffff !important;
    }
    body[data-theme="light"] header .text-white\/90,
    body[data-theme="light"] header .text-white\/80,
    body[data-theme="light"] header .text-white\/70 {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    
    /* Maintain sidebar look for contrast */
    body[data-theme="light"] #adminSidebar * {
        color: inherit;
    }
    body[data-theme="light"] #adminSidebar .text-white {
        color: #ffffff !important;
    }
    body[data-theme="light"] #adminSidebar .text-brand-light\/80 {
        color: rgba(255,255,255,0.7) !important;
    }
    body[data-theme="light"] #adminSidebar a {
        color: rgba(255, 255, 255, 0.8) !important;
    }
    body[data-theme="light"] #adminSidebar a:hover,
    body[data-theme="light"] #adminSidebar a.active,
    body[data-theme="light"] #adminSidebar a[class*="bg-gradient"] {
        color: #ffffff !important;
    }
    
    /* Modal content */
    body[data-theme="light"] .modal-content,
    body[data-theme="light"] [class*="bg-slate-900"],
    body[data-theme="light"] [class*="bg-teal-950"] {
        background-color: #ffffff !important;
        color: #1e293b !important;
    }

    /* Custom Primary Button for Admin panel (Gold in Dark Mode, Green in Light Mode) */
    .btn-admin-primary {
        background-color: #C8A84E !important;
        color: #0F211F !important;
    }
    .btn-admin-primary:hover {
        background-color: #b8963c !important;
    }
    .btn-admin-primary * {
        color: inherit !important;
    }

    body[data-theme="light"] .btn-admin-primary {
        background-color: #274D4F !important;
        color: #ffffff !important;
    }
    body[data-theme="light"] .btn-admin-primary:hover {
        background-color: #1a3638 !important;
    }

    /* Force Filter button (bg-brand-medium) text to be white in Light Mode */
    body[data-theme="light"] .bg-brand-medium {
        background-color: #274D4F !important;
        color: #ffffff !important;
    }
    body[data-theme="light"] .bg-brand-medium * {
        color: #ffffff !important;
    }

    /* Enforce dark header for all pages (including banner_profil) in both themes */
    header {
        background: linear-gradient(to right, #374D49, #3E6C65) !important;
        border-bottom: 1px solid rgba(255,255,255,0.1) !important;
        color: #ffffff !important;
    }
    header * {
        color: inherit;
    }
    header h1, header h2, header p, header span, header i {
        color: #ffffff !important;
    }
    header .text-gray-500, header .text-sm {
        color: rgba(255, 255, 255, 0.7) !important;
    }
    
    /* Tambah Banner button alignment */
    header a.bg-brand-medium,
    header a.bg-brand-medium span,
    header a.bg-brand-medium i {
        background-color: #C8A84E !important;
        color: #0F211F !important;
    }
    header a.bg-brand-medium:hover {
        background-color: #b8963c !important;
    }
    
    body[data-theme="light"] header a.bg-brand-medium,
    body[data-theme="light"] header a.bg-brand-medium span,
    body[data-theme="light"] header a.bg-brand-medium i {
        background-color: #e2e8f0 !important;
        color: #274D4F !important;
    }
    body[data-theme="light"] header a.bg-brand-medium:hover {
        background-color: #cbd5e1 !important;
    }

    /* Enforce Dark Theme on hardcoded light elements for custom pages in Dark Mode */
    body[data-theme="dark"] main .bg-white,
    body[data-theme="dark"] main .bg-gray-50,
    body[data-theme="dark"] main .bg-gray-100 {
        background-color: #1B3835 !important;
        color: #ffffff !important;
        border-color: rgba(77, 107, 103, 0.3) !important;
    }
    body[data-theme="dark"] main .text-gray-900,
    body[data-theme="dark"] main .text-gray-800,
    body[data-theme="dark"] main .text-gray-600,
    body[data-theme="dark"] main .text-gray-500,
    body[data-theme="dark"] main .text-gray-400 {
        color: #B1CDCE !important;
    }
    body[data-theme="dark"] main .border-gray-200,
    body[data-theme="dark"] main .border-gray-300,
    body[data-theme="dark"] main .border-gray-100 {
        border-color: rgba(77, 107, 103, 0.2) !important;
    }
    
    /* Ensure action buttons inside main content remain clean in Dark Mode */
    body[data-theme="dark"] main a.bg-red-50 {
        background-color: rgba(225, 67, 67, 0.15) !important;
        color: #E14343 !important;
    }
    body[data-theme="dark"] main a.bg-red-50:hover {
        background-color: rgba(225, 67, 67, 0.25) !important;
    }
    
    /* Form wrappers on custom pages (like add.php) in Dark Mode */
    body[data-theme="dark"] .max-w-2xl.mx-auto.bg-white {
        background-color: #1B3835 !important;
        border-color: rgba(77, 107, 103, 0.3) !important;
    }
</style>

<!-- Theme Toggle Logic -->
<script>
    // Set theme attribute immediately to prevent light mode flash
    document.body.setAttribute('data-theme', localStorage.getItem('theme') || 'dark');

    document.addEventListener('DOMContentLoaded', function() {
        const themeToggle = document.getElementById('theme-toggle');
        const themeIcon = document.getElementById('theme-icon');
        
        const savedTheme = localStorage.getItem('theme') || 'dark';
        updateIcon(savedTheme);
        
        function updateIcon(theme) {
            if (!themeIcon) return;
            const iconClass = theme === 'dark' ? 'bi-moon-stars' : 'bi-sun-fill';
            themeIcon.className = 'bi ' + iconClass + ' text-base';
        }
        
        function toggleTheme() {
            const currentTheme = document.body.getAttribute('data-theme') || 'dark';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateIcon(newTheme);
        }
        
        if (themeToggle) {
            themeToggle.addEventListener('click', toggleTheme);
        }
    });
</script>
