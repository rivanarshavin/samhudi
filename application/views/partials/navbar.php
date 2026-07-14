<nav class="<?= !$this->uri->segment(1) ? '' : 'bg-[#274d4f] shadow-[0_4px_6px_-1px_rgba(0,0,0,0.3)]' ?> sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">

        <div class="font-display font-bold text-lg text-white tracking-tight">
            HM Samhudi
        </div>

        <!-- Desktop Menu -->
        <ul class="hidden md:flex items-center gap-20 font-display font-semibold text-base tracking-wide text-white/90">
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
                    Data Keluarga
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
        <div class="hidden md:flex items-center">
            <?php if ($this->session->userdata('logged_in')): ?>
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-full bg-white text-[#274d4f] flex items-center justify-center font-bold text-sm flex-shrink-0">
                    <?= strtoupper(substr($this->session->userdata('full_name'), 0, 1)) ?>
                </div>
                <span class="font-display font-semibold text-sm text-white">
                    <?= $this->session->userdata('full_name') ?>
                </span>
                <?php if (in_array($this->session->userdata('role'), ['admin', 'super_admin'])): ?>
                <a href="<?= base_url('admin') ?>" class="ml-2 font-display font-semibold text-xs bg-white/15 text-white px-4 py-2 rounded-full hover:bg-white/25 transition-all duration-200">Dashboard</a>
                <?php endif; ?>
                <a href="<?= base_url('auth/logout') ?>" class="font-display font-semibold text-xs bg-white/15 text-white px-4 py-2 rounded-full hover:bg-white/25 transition-all duration-200">Keluar</a>
            </div>
            <?php else: ?>
            <a href="<?= base_url('auth/') ?>" class="font-display font-semibold text-sm bg-white text-[#274d4f] px-5 py-2.5 rounded-full shadow-sm hover:bg-gray-100 transition-all duration-300 transform hover:-translate-y-0.5">
                Masuk
            </a>
            <?php endif; ?>
        </div>

        <!-- Mobile Button -->
        <button id="menu-btn" class="block md:hidden text-3xl text-white focus:outline-none">
            ☰
        </button>

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
            <div class="flex items-center gap-3 bg-white/15 rounded-md px-4 py-3">
                <div class="w-10 h-10 rounded-full bg-white text-teal-900 flex items-center justify-center font-bold text-base flex-shrink-0">
                    <?= strtoupper(substr($this->session->userdata('full_name'), 0, 1)) ?>
                </div>
                <span class="font-display font-semibold text-sm text-white truncate">
                    <?= $this->session->userdata('full_name') ?>
                </span>
            </div>
        </div>
        <?php endif; ?>

        <ul class="font-display font-semibold text-base tracking-wide text-white/80 space-y-4 p-6 pl-2 flex-1">
            <li>
                <a href="<?= base_url() ?>" class="mobile-link group block py-2 pl-8 hover:text-white transition-colors duration-200" data-page="home"><span class="arrow-icon inline-block -ml-5 mr-2 opacity-0 -translate-x-2 transition-all duration-200 group-hover:opacity-100 group-hover:translate-x-0">></span><?php if ($this->session->userdata('logged_in')): ?><i class="bi bi-house mr-2"></i><?php endif; ?>Home</a>
            </li>
            <li>
                <a href="<?= base_url('Wasiat') ?>" class="mobile-link group block py-2 pl-8 hover:text-white transition-colors duration-200" data-page="wasiat"><span class="arrow-icon inline-block -ml-5 mr-2 opacity-0 -translate-x-2 transition-all duration-200 group-hover:opacity-100 group-hover:translate-x-0">></span><?php if ($this->session->userdata('logged_in')): ?><i class="bi bi-book mr-2"></i><?php endif; ?>Wasiat</a>
            </li>
            <li>
                <a href="#" class="mobile-link group block py-2 pl-8 hover:text-white transition-colors duration-200" data-page="yayasan"><span class="arrow-icon inline-block -ml-5 mr-2 opacity-0 -translate-x-2 transition-all duration-200 group-hover:opacity-100 group-hover:translate-x-0">></span><?php if ($this->session->userdata('logged_in')): ?><i class="bi bi-house-heart mr-2"></i><?php endif; ?>Yayasan</a>
            </li>
            <li>
                <a href="<?= base_url('Familytree') ?>" class="mobile-link group block py-2 pl-8 hover:text-white transition-colors duration-200" data-page="familytree"><span class="arrow-icon inline-block -ml-5 mr-2 opacity-0 -translate-x-2 transition-all duration-200 group-hover:opacity-100 group-hover:translate-x-0">></span><?php if ($this->session->userdata('logged_in')): ?><i class="bi bi-people mr-2"></i><?php endif; ?>Data Keluarga</a>
            </li>
            <li>
                <a href="<?= base_url('forum') ?>" class="mobile-link group block py-2 pl-8 hover:text-white transition-colors duration-200" data-page="forum"><span class="arrow-icon inline-block -ml-5 mr-2 opacity-0 -translate-x-2 transition-all duration-200 group-hover:opacity-100 group-hover:translate-x-0">></span><?php if ($this->session->userdata('logged_in')): ?><i class="bi bi-chat-dots mr-2"></i><?php endif; ?>Forum Diskusi</a>
            </li>
            <li>
                <a href="<?= base_url('Berita') ?>" class="mobile-link group block py-2 pl-8 hover:text-white transition-colors duration-200" data-page="berita"><span class="arrow-icon inline-block -ml-5 mr-2 opacity-0 -translate-x-2 transition-all duration-200 group-hover:opacity-100 group-hover:translate-x-0">></span><?php if ($this->session->userdata('logged_in')): ?><i class="bi bi-newspaper mr-2"></i><?php endif; ?>Berita</a>
            </li>
        <?php if (!$this->session->userdata('logged_in')): ?>
            <li class="px-6 pt-2">
                <hr class="border-white/20 mb-3">
                <a href="<?= base_url('auth/') ?>" class="block w-full text-center font-display font-semibold text-sm bg-white text-teal-900 px-5 py-2.5 rounded-full hover:bg-gray-100 transition-colors duration-200">Masuk</a>
            </li>
        <?php endif; ?>
        </ul>
    </div>
</nav>

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

// Active page indicator
const currentPath = window.location.pathname.replace(/\/$/, '').toLowerCase();
const mobileLinks = document.querySelectorAll('.mobile-link');
mobileLinks.forEach(link => {
    if (link.getAttribute('href') === '#') return;
    const linkPath = link.pathname.replace(/\/$/, '').toLowerCase();
    const arrow = link.querySelector('.arrow-icon');
    if (currentPath === linkPath || currentPath.endsWith(linkPath)) {
        arrow.classList.remove('opacity-0', '-translate-x-2');
        arrow.classList.add('opacity-100', 'translate-x-0');
        link.style.color = 'white';
    }
});
</script>