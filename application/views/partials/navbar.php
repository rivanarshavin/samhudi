<?php $is_home = !$this->uri->segment(1); ?>
<nav class="navbar-custom bg-[#274d4f] shadow-[0_4px_6px_-1px_rgba(0,0,0,0.3)]">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between xl:gap-x-12 lg:gap-x-6 md:gap-x-4 gap-x-2">

        <div class="font-display font-bold text-lg tracking-tight leading-none flex items-center gap-2 flex-shrink-0" style="color: #C8A84E;">
            <img src="<?= base_url('assets/images/icon-beringin.png') ?>" alt="" style="height: 28px; width: auto; flex-shrink: 0;">
            HM Samhudi
        </div>

        <!-- Desktop Menu -->
        <ul class="hidden md:flex items-center xl:gap-8 lg:gap-5 md:gap-3 font-display font-semibold text-sm lg:text-base tracking-wide text-white/90 whitespace-nowrap">
            <li>
                <a href="<?= base_url() ?>" class="relative py-2 hover:text-white transition-colors duration-300 group">
                    Home
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-white transition-all duration-300 group-hover:w-full"></span>
                </a>
            </li>
            <li>
                <a href="<?= base_url('Wasiat') ?>" class="relative py-2 hover:text-white transition-colors duration-300 group">
                    Wasiat
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-white transition-all duration-300 group-hover:w-full"></span>
                </a>
            </li>
            <li>
                <a href="#" class="relative py-2 hover:text-white transition-colors duration-300 group">
                    Yayasan
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-white transition-all duration-300 group-hover:w-full"></span>
                </a>
            </li>
            <li>
                <a href="<?= base_url('Familytree') ?>" class="relative py-2 hover:text-white transition-colors duration-300 group">
                    Silsilah Keluarga
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-white transition-all duration-300 group-hover:w-full"></span>
                </a>
            </li>
            <li>
                <a href="<?= base_url('forum') ?>" class="relative py-2 hover:text-white transition-colors duration-300 group">
                    Forum Diskusi
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-white transition-all duration-300 group-hover:w-full"></span>
                </a>
            </li>
            <li>
                <a href="<?= base_url('berita') ?>" class="relative py-2 hover:text-teal-600 transition-colors duration-300 group">
                    Berita
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
            </li>
        </ul>

        <!-- Desktop Login -->
        <div class="hidden md:flex items-center gap-5">
            <button id="theme-toggle" class="text-white hover:text-gray-300 focus:outline-none p-2 rounded-full hover:bg-white/10 transition-colors" title="Toggle Tema">
                <i id="theme-icon" class="bi bi-moon-stars text-xl"></i>
            </button>
            <?php if ($this->session->userdata('logged_in')): ?>
            <div id="desktop-user-menu" class="relative cursor-pointer select-none group">
                <div id="user-pill" class="flex items-center gap-2 bg-white hover:bg-gray-100 px-5 py-2.5 rounded-full transition-all duration-200" title="<?= htmlspecialchars($this->session->userdata('full_name')) ?>">
                    <i id="user-pill-icon" class="bi bi-person-fill text-[#274d4f] text-base flex-shrink-0"></i>
                    <span id="user-pill-text" class="font-display font-semibold text-sm text-[#274d4f] max-w-[120px] truncate">
                        <?= $this->session->userdata('full_name') ?>
                    </span>
                </div>
                <div id="desktop-dropdown" class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden" style="opacity:0;visibility:hidden;transform:translateY(8px);transition:all 0.2s ease;z-index:9999;">
                    <?php if (in_array($this->session->userdata('role'), ['admin', 'super_admin'])): ?>
                    <a href="<?= base_url('admin') ?>" class="dd-item flex items-center gap-3 px-5 py-3 text-sm text-gray-700 hover:bg-gray-100 transition-colors font-display font-medium no-underline">
                        <span class="dd-icon"><i class="bi bi-speedometer2"></i></span>
                        Dashboard
                    </a>
                    <?php endif; ?>
                    <a href="<?= base_url('profile') ?>" class="dd-item flex items-center gap-3 px-5 py-3 text-sm text-gray-700 hover:bg-gray-100 transition-colors font-display font-medium no-underline">
                        <span class="dd-icon"><i class="bi bi-pencil-square"></i></span>
                        Edit Profil
                    </a>
                    <hr class="border-gray-200 m-0">
                    <a href="<?= base_url('auth/logout') ?>" class="dd-item flex items-center gap-3 px-5 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors font-display font-medium no-underline">
                        <span class="dd-icon"><i class="bi bi-box-arrow-right"></i></span>
                        Logout
                    </a>
                </div>
            </div>
            <?php else: ?>
            <a href="<?= base_url('auth/') ?>" class="font-display font-semibold text-sm bg-white text-[#274d4f] px-5 py-2.5 rounded-full shadow-sm hover:bg-gray-100 transition-all duration-300 transform hover:-translate-y-0.5">
                Masuk
            </a>
            <?php endif; ?>
        </div>

        <!-- Mobile Actions -->
        <div class="flex md:hidden items-center gap-2">
            <button id="theme-toggle-mobile" class="text-white hover:text-gray-300 focus:outline-none p-2 rounded-full hover:bg-white/10 transition-colors" title="Toggle Tema">
                <i id="theme-icon-mobile" class="bi bi-moon-stars text-xl"></i>
            </button>
            <button id="menu-btn" class="text-3xl text-white focus:outline-none ml-1">
                ☰
            </button>
        </div>

    </div>

    <!-- Mobile Overlay -->
    <div id="sidebar-overlay" class="fixed inset-0 bg-black/50 z-40 md:hidden opacity-0 pointer-events-none transition-opacity duration-300"></div>

    <!-- Mobile Sidebar -->
    <div id="mobile-menu" class="fixed top-0 left-0 h-full w-2/3 max-w-sm bg-teal-900 z-50 md:hidden -translate-x-full transition-transform duration-300 ease-in-out shadow-[5px_0_20px_rgba(0,0,0,0.3)] flex flex-col">
        <div class="flex items-center justify-between p-6 border-b border-white/20">
            <span class="font-display font-bold text-lg text-white">Menu</span>
            <button id="close-sidebar" class="text-3xl text-white/80 hover:text-white focus:outline-none">&times;</button>
        </div>

        <?php if ($this->session->userdata('logged_in')): ?>
        <div class="px-6 pt-4 pb-2">
            <div class="flex items-center gap-3 bg-white/15 rounded-xl px-4 py-3">
                <div class="w-11 h-11 rounded-full bg-white text-teal-900 flex items-center justify-center font-bold text-base flex-shrink-0">
                    <?= strtoupper(substr($this->session->userdata('full_name'), 0, 1)) ?>
                </div>
                <span class="font-display font-semibold text-xl text-white truncate">
                    <?= $this->session->userdata('full_name') ?>
                </span>
            </div>
        </div>
        <?php endif; ?>

        <ul class="font-display font-semibold text-base tracking-wide text-white/80 space-y-4 p-6 pl-2 flex-1">
            <li>
                <a href="<?= base_url() ?>" class="mobile-link group block py-2 pl-8 hover:text-white transition-colors duration-200" data-page="home"><span class="arrow-icon" style="display:none;margin-right:8px;">&gt;</span><?php if ($this->session->userdata('logged_in')): ?><img src="<?= base_url('assets/images/3d_house.png') ?>" class="w-7 h-7 inline-block mr-2 align-middle object-contain" alt="Home"><?php endif; ?>Home</a>
            </li>
            <li>
                <a href="<?= base_url('Wasiat') ?>" class="mobile-link group block py-2 pl-8 hover:text-white transition-colors duration-200" data-page="wasiat"><span class="arrow-icon" style="display:none;margin-right:8px;">&gt;</span><?php if ($this->session->userdata('logged_in')): ?><img src="<?= base_url('assets/images/3d_wasiat.png') ?>" class="w-7 h-7 inline-block mr-2 align-middle object-contain" alt="Wasiat"><?php endif; ?>Wasiat alm. H.M Samhudi</a>
            </li>
            <li>
                <a href="#" class="mobile-link group block py-2 pl-8 hover:text-white transition-colors duration-200" data-page="yayasan"><span class="arrow-icon" style="display:none;margin-right:8px;">&gt;</span><?php if ($this->session->userdata('logged_in')): ?><img src="<?= base_url('assets/images/3d_yayasan.png') ?>" class="w-7 h-7 inline-block mr-2 align-middle object-contain" alt="Yayasan"><?php endif; ?>Yayasan</a>
            </li>
            <li>
                <a href="<?= base_url('Familytree') ?>" class="mobile-link group block py-2 pl-8 hover:text-white transition-colors duration-200" data-page="familytree"><span class="arrow-icon" style="display:none;margin-right:8px;">&gt;</span><?php if ($this->session->userdata('logged_in')): ?><img src="<?= base_url('assets/images/3d_silsilah.png') ?>" class="w-7 h-7 inline-block mr-2 align-middle object-contain" alt="Silsilah Keluarga"><?php endif; ?>Silsilah Keluarga</a>
            </li>
            <li>
                <a href="<?= base_url('forum') ?>" class="mobile-link group block py-2 pl-8 hover:text-white transition-colors duration-200" data-page="forum"><span class="arrow-icon" style="display:none;margin-right:8px;">&gt;</span><?php if ($this->session->userdata('logged_in')): ?><img src="<?= base_url('assets/images/3d_forum.png') ?>" class="w-7 h-7 inline-block mr-2 align-middle object-contain" alt="Forum Diskusi"><?php endif; ?>Forum Diskusi</a>
            </li>
            <li>
                <a href="<?= base_url('Berita') ?>" class="mobile-link group block py-2 pl-8 hover:text-white transition-colors duration-200" data-page="berita"><span class="arrow-icon" style="display:none;margin-right:8px;">&gt;</span><?php if ($this->session->userdata('logged_in')): ?><img src="<?= base_url('assets/images/3d_berita.png') ?>" class="w-7 h-7 inline-block mr-2 align-middle object-contain" alt="Berita"><?php endif; ?>Berita</a>
            </li>
        <?php if ($this->session->userdata('logged_in')): ?>
            <?php if (in_array($this->session->userdata('role'), ['admin', 'super_admin'])): ?>
            <li>
                <a href="<?= base_url('admin') ?>" class="mobile-link group block py-2 pl-8 hover:text-white transition-colors duration-200" data-page="admin"><span class="arrow-icon" style="display:none;margin-right:8px;">&gt;</span><img src="<?= base_url('assets/images/3d_dashboard.png') ?>" class="w-7 h-7 inline-block mr-2 align-middle object-contain" alt="Dashboard">Dashboard</a>
            </li>
            <?php endif; ?>
            <li>
                <a href="<?= base_url('profile') ?>" class="mobile-link group block py-2 pl-8 hover:text-white transition-colors duration-200" data-page="profile"><span class="arrow-icon" style="display:none;margin-right:8px;">&gt;</span><img src="<?= base_url('assets/images/3d_edit_profile.png') ?>" class="w-7 h-7 inline-block mr-2 align-middle object-contain" alt="Edit Profil">Edit Profil</a>
            </li>
        <?php endif; ?>
        <?php if (!$this->session->userdata('logged_in')): ?>
            <li class="px-6 pt-2">
                <hr class="border-white/20 mb-3">
                <a href="<?= base_url('auth/') ?>" class="block w-full text-center font-display font-semibold text-sm bg-white text-teal-900 px-5 py-2.5 rounded-full hover:bg-gray-100 transition-colors duration-200">Masuk</a>
            </li>
        <?php else: ?>
            <li class="px-6 pt-2">
                <hr class="border-white/20 mb-3">
                <a href="<?= base_url('auth/logout') ?>" class="flex items-center justify-center gap-2 w-full font-display font-semibold text-sm bg-red-500/80 text-white px-5 py-2.5 rounded-full hover:bg-red-600 transition-colors duration-200">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </li>
        <?php endif; ?>
        </ul>
    </div>
</nav>

<style>
/* Fixed-width icon wrapper to keep all dropdown icons perfectly aligned */
.dd-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 18px;
    flex-shrink: 0;
    font-size: 15px;
}

#desktop-user-menu:hover #desktop-dropdown {
    opacity: 1 !important;
    visibility: visible !important;
    transform: translateY(0) !important;
}

/* Enforce dropdown styles - Dark Mode */
body[data-theme="dark"] #desktop-dropdown {
    background-color: rgba(255,255,255,0.1) !important;
    border-color: rgba(255,255,255,0.15) !important;
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}
body[data-theme="dark"] #desktop-dropdown a {
    color: #ffffff !important;
}
body[data-theme="dark"] #desktop-dropdown a:hover {
    background-color: rgba(255,255,255,0.12) !important;
}
body[data-theme="dark"] #desktop-dropdown i {
    color: #C8A84E !important;
}
body[data-theme="dark"] #desktop-dropdown hr {
    border-color: rgba(255,255,255,0.15) !important;
}

/* Enforce dropdown styles - Light Mode (match white user pill) */
body:not([data-theme="dark"]) #desktop-dropdown {
    background-color: #ffffff !important;
    border-color: rgba(39,77,79,0.12) !important;
    box-shadow: 0 10px 30px rgba(39,77,79,0.15) !important;
}
body:not([data-theme="dark"]) #desktop-dropdown a {
    color: #274D4F !important;
}
body:not([data-theme="dark"]) #desktop-dropdown a:hover {
    background-color: #f3f4f6 !important;
}
body:not([data-theme="dark"]) #desktop-dropdown i {
    color: #274D4F !important;
}
body:not([data-theme="dark"]) #desktop-dropdown a.text-red-600 {
    color: #dc2626 !important;
}
body:not([data-theme="dark"]) #desktop-dropdown a.text-red-600:hover {
    background-color: #fef2f2 !important;
}
body:not([data-theme="dark"]) #desktop-dropdown hr {
    border-color: rgba(39,77,79,0.12) !important;
}

/* User pill - Light Mode: white bg, teal text (same as default) */
body:not([data-theme="dark"]) #user-pill {
    background-color: #ffffff !important;
}
body:not([data-theme="dark"]) #user-pill:hover {
    background-color: #f3f4f6 !important;
}
body:not([data-theme="dark"]) #user-pill-icon,
body:not([data-theme="dark"]) #user-pill-text {
    color: #274D4F !important;
}

/* User pill - Dark Mode: teal-dark bg, white text */
body[data-theme="dark"] #user-pill {
    background-color: rgba(255,255,255,0.1) !important;
}
body[data-theme="dark"] #user-pill:hover {
    background-color: rgba(255,255,255,0.18) !important;
}
body[data-theme="dark"] #user-pill-icon,
body[data-theme="dark"] #user-pill-text {
    color: #ffffff !important;
}
</style>

<script>
const menuBtn = document.getElementById('menu-btn');
const closeBtn = document.getElementById('close-sidebar');
const mobileMenu = document.getElementById('mobile-menu');
const overlay = document.getElementById('sidebar-overlay');

function openSidebar() {
    mobileMenu.classList.remove('-translate-x-full');
    mobileMenu.classList.add('translate-x-0');
    overlay.classList.remove('opacity-0', 'pointer-events-none');
    overlay.classList.add('opacity-100');
    document.body.style.overflow = 'hidden';
}

function closeSidebar() {
    mobileMenu.classList.remove('translate-x-0');
    mobileMenu.classList.add('-translate-x-full');
    overlay.classList.remove('opacity-100');
    overlay.classList.add('opacity-0', 'pointer-events-none');
    document.body.style.overflow = '';
}

menuBtn.addEventListener('click', openSidebar);
closeBtn.addEventListener('click', closeSidebar);
overlay.addEventListener('click', closeSidebar);

// Active page indicator — uses inline style, no Tailwind dependency
const currentPath = window.location.pathname.replace(/\/$/, '').toLowerCase();
const mobileLinks = document.querySelectorAll('.mobile-link');
const basePath = '<?= rtrim(base_url(), '/') ?>'.replace(/^https?:\/\/[^\/]+/, '').replace(/\/$/, '').toLowerCase();

mobileLinks.forEach(link => {
    if (link.getAttribute('href') === '#') return;
    const arrow = link.querySelector('.arrow-icon');
    if (!arrow) return;
    
    const page = link.getAttribute('data-page');
    let isActive = false;
    
    if (page === 'home') {
        // Home only active on exact base path
        isActive = (currentPath === basePath || currentPath === basePath + '/index.php' || currentPath === '');
    } else {
        // Other pages: check if current path contains the page keyword
        isActive = currentPath.indexOf('/' + page) !== -1;
    }
    
    if (isActive) {
        arrow.style.display = 'inline-block';
        link.style.color = 'white';
    }
});

</script>