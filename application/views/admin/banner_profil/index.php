<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Banner Profil - Admin Panel</title>
    <link rel="icon" type="image/jpeg" href="<?= base_url('assets/favicon.jpeg') ?>">
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        teal: {
                            950: '#15201E',
                            900: '#1D2A27',
                            800: '#273834',
                            700: '#324742',
                            600: '#435E59',
                            500: '#5F7F7A',
                            400: '#8DAAA4',
                        },
                        brand: {
                            dark: '#374D49',
                            medium: '#4D6B67',
                            light: '#E3E3E3',
                            red: '#E14343',
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-teal-950 text-white font-sans">
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php $this->load->view('admin/sidebar', ['active_menu' => 'banner_profil']); ?>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-y-auto">
            <!-- Header -->
            <header class="h-20 bg-gradient-to-r from-[#374D49] to-[#3E6C65] border-b border-[#4D6B67]/30 flex items-center justify-between px-4 md:px-8 shrink-0 shadow-md">
                <div class="flex items-center gap-4">
                    <button onclick="document.getElementById('adminSidebar').classList.remove('-translate-x-full');" class="md:hidden text-white/80 hover:text-white p-1">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-white/10 flex items-center justify-center text-white">
                            <i class="bi bi-images text-xl"></i>
                        </div>
                        <div>
                            <h1 class="font-display font-bold text-lg text-white">Kelola Banner Profil</h1>
                            <p class="text-xs text-white/80 mt-0.5">Manajemen pilihan banner untuk profil user</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button id="theme-toggle" class="text-white hover:text-gray-300 focus:outline-none p-2 rounded-full hover:bg-white/10 transition-colors" title="Toggle Tema">
                        <i id="theme-icon" class="bi bi-moon-stars text-base"></i>
                    </button>
                    <a href="<?= base_url('admin/banner_profil_add') ?>" class="flex items-center gap-2 border border-white/20 bg-white/10 hover:bg-white/20 text-[#E3E3E3] hover:text-white px-4 py-2.5 rounded-xl text-xs font-semibold tracking-wide transition-all shadow-sm backdrop-blur-sm">
                        <i class="bi bi-plus-lg"></i>
                        <span>Tambah Banner</span>
                    </a>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-8">
                <!-- Messages -->
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 border border-green-200 flex items-center gap-3">
                        <i class="bi bi-check-circle-fill"></i> <?= $this->session->flashdata('success') ?>
                    </div>
                <?php endif; ?>
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 border border-red-200 flex items-center gap-3">
                        <i class="bi bi-exclamation-triangle-fill"></i> <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if(!empty($banners)): ?>
                        <?php foreach($banners as $banner): ?>
                        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm group">
                            <div class="h-40 w-full bg-gray-100 relative">
                                <img src="<?= base_url($banner['image_path']) ?>" class="w-full h-full object-cover" alt="Banner">
                            </div>
                            <div class="p-4 flex justify-between items-center bg-white border-t border-gray-100">
                                <div class="text-xs text-gray-400"><i class="bi bi-clock"></i> <?= date('d M Y, H:i', strtotime($banner['created_at'])) ?></div>
                                <a href="<?= base_url('admin/banner_profil_delete/'.$banner['id']) ?>" onclick="return confirm('Hapus banner ini?')" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-lg transition-colors" title="Hapus">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="col-span-full py-12 text-center text-gray-400 bg-white border border-dashed border-gray-300 rounded-xl">
                            <i class="bi bi-images text-4xl mb-2 block"></i>
                            <p>Belum ada banner profil. Silakan tambah banner baru.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </main>
    </div>
</body>
</html>
