<nav class="bg-white/80 backdrop-blur-md border-b border-gray-100 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        
        <div class="font-display font-bold text-lg text-teal-900 tracking-tight">
            <a href="<?= base_url() ?>">HM Samhudi</a>
        </div>

        <ul class="hidden md:flex items-center gap-20 font-display font-semibold text-sm tracking-wide text-teal-900/90">
            <li>
                <a href="<?= base_url() ?>" class="relative py-2 hover:text-teal-600 transition-colors duration-300 group">
                    Home
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
            </li>
            <li>
                <a href="<?= base_url('Wasiat') ?>" class="relative py-2 hover:text-teal-600 transition-colors duration-300 group">
                    Wasiat 
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
            </li>
            <li>
                <a href="#" class="relative py-2 hover:text-teal-600 transition-colors duration-300 group">
                    Yayasan
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
            </li>
            <li>
                <a href="<?= base_url('Familytree') ?>" class="relative py-2 hover:text-teal-600 transition-colors duration-300 group">
                    Data Keluarga 
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
            </li>
            <li>
                <a href="<?= base_url('forum') ?>" class="relative py-2 hover:text-teal-600 transition-colors duration-300 group">
                    Forum Diskusi
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
            </li>
            <li>
                <a href="<?= base_url('berita') ?>" class="relative py-2 hover:text-teal-600 transition-colors duration-300 group">
                    Berita
                    <span class="absolute bottom-0 left-0 w-0 h-0.5 bg-teal-600 transition-all duration-300 group-hover:w-full"></span>
                </a>
            </li>
        </ul>

        <div class="flex items-center">
            <a href="<?= base_url('auth/') ?>" class="font-display font-semibold text-sm bg-teal-900 text-white px-5 py-2.5 rounded-full shadow-sm hover:bg-teal-800 transition-all duration-300 transform hover:-translate-y-0.5">
                Masuk
            </a>
        </div>

    </div>
</nav>
