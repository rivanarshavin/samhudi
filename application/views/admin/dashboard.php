<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | Keluarga H.M Samhudi</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
                        gold: {
                            400: '#D4B571',
                            500: '#C29A4E',
                        },
                        brand: {
                            dark: '#374D49',
                            medium: '#4D6B67',
                            light: '#E3E3E3',
                            red: '#E14343',
                        }
                    },
                    fontFamily: {
                        display: ['"Plus Jakarta Sans"', 'sans-serif'],
                        body: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        * { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        /* Slim Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #15201E; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }
    </style>
</head>
<body class="bg-teal-950 text-white font-body min-h-screen flex">

    <!-- ================= SIDEBAR ================= -->
    <?php $this->load->view('admin/sidebar'); ?>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 flex flex-col overflow-y-auto">
        
        <!-- Header -->
        <?php $this->load->view('admin/header'); ?>

        <!-- Body / Dashboard Content -->
        <div class="p-4 md:p-8 space-y-6 md:space-y-8">

            <!-- Welcome Message Widget -->
            <div class="relative overflow-hidden bg-gradient-to-r from-teal-900 to-teal-800 border border-teal-800 rounded-2xl p-8 flex items-center justify-between shadow-lg">
                <div class="space-y-2 z-10">
                    <h2 class="font-display font-extrabold text-2xl text-white">Halo, <?= htmlspecialchars($admin_name) ?>!</h2>
                    <p class="text-teal-300 text-sm max-w-xl">Halaman ini digunakan untuk mengelola data silsilah keluarga besar, persetujuan forum diskusi, publikasi berita terbaru, penginputan data yayasan, dan pengelolaan data wasiat.</p>
                </div>
                <i class="bi bi-shield-lock-fill text-8xl text-teal-700/20 absolute right-8 bottom-0"></i>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

                <!-- Stat Card 1 -->
                <div class="bg-teal-900/60 hover:bg-teal-900 border border-teal-800 rounded-xl p-6 transition-all duration-300 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-teal-400 uppercase tracking-wider">Total Anggota</span>
                        <h3 class="text-3xl font-extrabold font-display mt-2 text-white"><?= number_format($total_members) ?></h3>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 text-xl border border-teal-700">
                        <i class="bi bi-people-fill"></i>
                    </div>
                </div>

                <!-- Stat Card 2 -->
                <div class="bg-teal-900/60 hover:bg-teal-900 border border-teal-800 rounded-xl p-6 transition-all duration-300 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-teal-400 uppercase tracking-wider">Berita Aktif</span>
                        <h3 class="text-3xl font-extrabold font-display mt-2 text-white"><?= number_format($total_news) ?></h3>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 text-xl border border-teal-700">
                        <i class="bi bi-newspaper"></i>
                    </div>
                </div>

                <!-- Stat Card 3 -->
                <div class="bg-teal-900/60 hover:bg-teal-900 border border-teal-800 rounded-xl p-6 transition-all duration-300 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-teal-400 uppercase tracking-wider">Forum Diskusi</span>
                        <h3 class="text-3xl font-extrabold font-display mt-2 text-white"><?= number_format($total_forums) ?></h3>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 text-xl border border-teal-700">
                        <i class="bi bi-chat-left-text-fill"></i>
                    </div>
                </div>

                <!-- Stat Card 4 -->
                <div class="bg-teal-900/60 hover:bg-teal-900 border border-teal-800 rounded-xl p-6 transition-all duration-300 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-teal-400 uppercase tracking-wider">Data Wasiat</span>
                        <h3 class="text-3xl font-extrabold font-display mt-2 text-white"><?= number_format($total_wills) ?></h3>
                    </div>
                    <div class="w-12 h-12 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 text-xl border border-teal-700">
                        <i class="bi bi-file-earmark-text-fill"></i>
                    </div>
                </div>

            </div>

            <!-- Aktivitas Terbaru Section -->
            <div class="bg-gradient-to-b from-[#374D49]/20 to-[#374D49]/5 border border-[#4D6B67]/20 rounded-2xl p-8 shadow-lg">
                <h3 class="font-display font-bold text-xl text-white mb-6">Aktivitas Terbaru</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#4D6B67]/20">
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Aktivitas</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Pengguna</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Waktu</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Status</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#4D6B67]/10">
                            <?php 
                            // Helper function to format date into Indonesian
                            if (!function_exists('format_indo_date')) {
                                function format_indo_date($datetime) {
                                    $months = [
                                        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                                    ];
                                    $timestamp = strtotime($datetime);
                                    $d = date('j', $timestamp);
                                    $m = $months[(int)date('n', $timestamp)];
                                    $y = date('Y', $timestamp);
                                    return "$d $m $y";
                                }
                            }
                            ?>
                            <?php if (!empty($recent_activities)): ?>
                                <?php foreach ($recent_activities as $activity): ?>
                                    <tr>
                                        <td class="py-4 text-sm text-white/90 font-medium"><?= htmlspecialchars($activity['aktivitas']) ?></td>
                                        <td class="py-4 text-sm text-white/80"><?= htmlspecialchars($activity['pengguna']) ?></td>
                                        <td class="py-4 text-sm text-white/60"><?= format_indo_date($activity['waktu']) ?></td>
                                        <td class="py-4 text-sm">
                                            <span class="text-white/80"><?= htmlspecialchars($activity['status']) ?></span>
                                        </td>
                                        <td class="py-4 text-sm">
                                            <a href="<?= base_url('admin/' . $activity['tipe'] . '/detail/' . $activity['reff_id']) ?>" class="font-bold text-white hover:underline transition-all">Detail</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Berita Highlight Preview -->
            <div class="bg-gradient-to-r from-yellow-500/10 to-yellow-600/5 border border-yellow-500/25 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-lg bg-yellow-500/20 border border-yellow-500/30 flex items-center justify-center text-yellow-400">
                            <i class="bi bi-star-fill"></i>
                        </div>
                        <div>
                            <h3 class="font-display font-bold text-white text-sm">Berita Highlight</h3>
                            <p class="text-xs text-white/40">Berita yang tampil sebagai featured card di halaman publik</p>
                        </div>
                    </div>
                    <a href="<?= base_url('admin/berita') ?>" class="text-xs text-teal-400 hover:text-teal-300 flex items-center gap-1 transition-colors">
                        Kelola <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <?php if (!empty($highlighted_news)): ?>
                <div class="flex items-center gap-4 bg-white/5 border border-yellow-500/20 rounded-xl p-4">
                    <?php if (!empty($highlighted_news['thumbnail']) && file_exists('./' . $highlighted_news['thumbnail'])): ?>
                        <img src="<?= base_url($highlighted_news['thumbnail']) ?>"
                             alt="<?= htmlspecialchars($highlighted_news['title']) ?>"
                             class="w-20 h-16 object-cover rounded-lg border border-yellow-500/30 shrink-0">
                    <?php else: ?>
                        <div class="w-20 h-16 rounded-lg bg-yellow-500/10 border border-yellow-500/20 flex items-center justify-center shrink-0">
                            <i class="bi bi-newspaper text-yellow-400/50 text-2xl"></i>
                        </div>
                    <?php endif; ?>
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                                <i class="bi bi-star-fill text-[8px]"></i> HIGHLIGHT AKTIF
                            </span>
                        </div>
                        <p class="font-semibold text-white text-sm leading-snug line-clamp-2">
                            <?= htmlspecialchars($highlighted_news['title']) ?>
                        </p>
                        <p class="text-xs text-white/40 mt-1">
                            Oleh <?= htmlspecialchars($highlighted_news['author_name'] ?? 'Admin') ?>
                        </p>
                    </div>
                    <a href="<?= base_url('admin/berita_highlight/' . $highlighted_news['id']) ?>"
                       onclick="return confirm('Cabut highlight berita ini?')"
                       title="Cabut Highlight"
                       class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-yellow-500/15 text-yellow-300 border border-yellow-500/30 hover:bg-yellow-500/30 transition-all">
                        <i class="bi bi-star-fill"></i> Cabut
                    </a>
                </div>
                <?php else: ?>
                <div class="flex flex-col items-center justify-center py-6 gap-2 text-white/30">
                    <i class="bi bi-star text-3xl"></i>
                    <p class="text-sm">Belum ada berita yang di-highlight.</p>
                    <a href="<?= base_url('admin/berita') ?>" class="text-xs text-teal-400 hover:text-teal-300 transition-colors">
                        → Pergi ke Kelola Berita untuk set highlight
                    </a>
                </div>
                <?php endif; ?>
            </div>

            <!-- Banner Settings -->
            <div id="banner-section" class="bg-teal-900/60 border border-teal-800 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 border border-teal-700">
                        <i class="bi bi-images"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white">Banner Landing Page</h3>
                        <p class="text-xs text-teal-400">Drag & drop gambar atau klik upload dari file explorer</p>
                    </div>
                </div>

                <?php if ($this->session->flashdata('banner_success')): ?>
                <div class="bg-green-500/20 border border-green-500/40 text-green-200 px-5 py-3 rounded-lg text-sm mb-4">
                    <?= $this->session->flashdata('banner_success') ?>
                </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('banner_error')): ?>
                <div class="bg-red-500/20 border border-red-500/40 text-red-200 px-5 py-3 rounded-lg text-sm mb-4">
                    <?= $this->session->flashdata('banner_error') ?>
                </div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="upload_banner" value="1">

                    <div class="relative group cursor-pointer" onclick="previewBanner(this)">
                        <img src="<?= base_url('assets/images/' . $selected_banner) ?>" class="w-full h-64 object-cover rounded-xl border border-teal-700" id="banner-preview-img">
                        <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all rounded-xl flex items-center justify-center">
                            <i class="bi bi-arrows-fullscreen text-white text-2xl opacity-0 group-hover:opacity-100 transition-all"></i>
                        </div>
                    </div>

                    <div class="drop-zone border-2 border-dashed border-teal-700 rounded-xl p-6 text-center cursor-pointer hover:border-teal-500 transition-all" onclick="document.getElementById('banner-upload').click()" ondragover="event.preventDefault();this.classList.add('border-teal-400','bg-teal-800/50')" ondragleave="this.classList.remove('border-teal-400','bg-teal-800/50')" ondrop="handleDrop(event)">
                        <i class="bi bi-cloud-arrow-up text-3xl text-teal-400"></i>
                        <p class="text-sm text-teal-300 mt-2">Klik atau drag & drop gambar banner</p>
                        <input id="banner-upload" type="file" name="banner_file" accept="image/*" class="hidden" onchange="previewFile(this, 'banner-preview-img')">
                    </div>

                    <div class="text-center">
                        <button type="submit" class="bg-white text-teal-900 font-display font-semibold px-8 py-2.5 rounded-full hover:bg-gray-100 transition-all shadow-lg text-sm">
                            <i class="bi bi-check-lg mr-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Carousel Settings -->
            <div id="carousel-section" class="bg-teal-900/60 border border-teal-800 rounded-2xl p-6 shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 border border-teal-700">
                        <i class="bi bi-images"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white">Carousel Keluarga</h3>
                        <p class="text-xs text-teal-400">Kelola gambar & caption carousel di halaman utama</p>
                    </div>
                </div>

                <?php if ($this->session->flashdata('carousel_success')): ?>
                <div class="bg-green-500/20 border border-green-500/40 text-green-200 px-5 py-3 rounded-lg text-sm mb-4">
                    <?= $this->session->flashdata('carousel_success') ?>
                </div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data" class="space-y-4" id="carousel-form">

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="carousel-grid">
                        <?php foreach ($carousel_items as $i => $item): ?>
                        <div class="bg-teal-800/40 border border-teal-700 rounded-xl p-4 space-y-3">
                            <div class="relative group cursor-pointer" onclick="previewCarousel(this)">
                                <img src="<?= base_url('assets/images/' . $item['file']) ?>" class="w-full h-36 object-cover rounded-lg border border-teal-700">
                                <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all rounded-lg flex items-center justify-center">
                                    <i class="bi bi-arrows-fullscreen text-white text-xl opacity-0 group-hover:opacity-100 transition-all"></i>
                                </div>
                            </div>
                            <input type="text" name="captions[]" value="<?= htmlspecialchars($item['caption']) ?>" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Caption">
                            <div class="flex gap-2">
                                <div class="drop-zone-carousel flex-1 border-2 border-dashed border-teal-700 rounded-lg p-3 text-center cursor-pointer hover:border-teal-500 transition-all text-xs" onclick="document.getElementById('carousel-upload-<?= $i ?>').click()" ondragover="event.preventDefault();this.classList.add('border-teal-400','bg-teal-800/50')" ondragleave="this.classList.remove('border-teal-400','bg-teal-800/50')" ondrop="handleCarouselDrop(event, <?= $i ?>)">
                                    <i class="bi bi-cloud-arrow-up text-teal-400"></i>
                                    <p class="text-teal-400 mt-1">Ganti</p>
                                    <input id="carousel-upload-<?= $i ?>" type="file" name="carousel_file[]" accept="image/*" class="hidden" onchange="previewCarouselInput(this, <?= $i ?>)">
                                </div>
                                <button type="button" onclick="deleteCarousel(<?= $i ?>)" class="px-3 py-1.5 bg-red-500/20 text-red-300 rounded-lg hover:bg-red-500/40 transition-all text-xs">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <input type="hidden" name="delete_index" value="">
                    <div class="flex gap-3">
                        <button type="submit" name="save_carousel" value="1" class="bg-white text-teal-900 font-display font-semibold px-6 py-2.5 rounded-full hover:bg-gray-100 transition-all shadow-lg text-sm">
                            <i class="bi bi-check-lg mr-1"></i> Simpan Carousel
                        </button>
                        <button type="button" onclick="addCarouselCard()" class="border border-dashed border-teal-600 text-teal-400 hover:text-white font-display font-semibold px-6 py-2.5 rounded-full hover:bg-teal-800/50 transition-all text-sm">
                            <i class="bi bi-plus-lg mr-1"></i> Tambah
                        </button>
                    </div>
                </form>
            </div>

            <!-- Intro Text Settings -->
            <div id="intro-section" class="bg-teal-900/60 border border-teal-800 rounded-2xl p-4 md:p-6 shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 border border-teal-700">
                        <i class="bi bi-quote"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white">Teks Intro</h3>
                        <p class="text-xs text-teal-400">Edit teks sambutan di halaman utama (bagian foto + card)</p>
                    </div>
                </div>

                <?php if ($this->session->flashdata('intro_success')): ?>
                <div class="bg-green-500/20 border border-green-500/40 text-green-200 px-5 py-3 rounded-lg text-sm mb-4">
                    <?= $this->session->flashdata('intro_success') ?>
                </div>
                <?php endif; ?>

                <form method="post" class="space-y-4">
                    <input type="hidden" name="save_intro" value="1">
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Teks Intro</label>
                        <textarea name="intro_text" rows="5" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Tulis teks intro..."><?= htmlspecialchars($intro_text) ?></textarea>
                    </div>
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Nama Pengirim</label>
                        <input type="text" name="intro_sender" value="<?= htmlspecialchars($intro_sender) ?>" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="From (nama)">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="bg-white text-teal-900 font-display font-semibold px-8 py-2.5 rounded-full hover:bg-gray-100 transition-all shadow-lg text-sm">
                            <i class="bi bi-check-lg mr-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Sambutan Text Settings -->
            <div id="sambutan-section" class="bg-teal-900/60 border border-teal-800 rounded-2xl p-4 md:p-6 shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 border border-teal-700">
                        <i class="bi bi-envelope-paper"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white">Teks Sambutan</h3>
                        <p class="text-xs text-teal-400">Edit teks sambutan di halaman utama</p>
                    </div>
                </div>

                <?php if ($this->session->flashdata('sambutan_success')): ?>
                <div class="bg-green-500/20 border border-green-500/40 text-green-200 px-5 py-3 rounded-lg text-sm mb-4">
                    <?= $this->session->flashdata('sambutan_success') ?>
                </div>
                <?php endif; ?>

                <form method="post" class="space-y-4">
                    <input type="hidden" name="save_sambutan" value="1">
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Judul</label>
                        <input type="text" name="sambutan_title" value="<?= htmlspecialchars($sambutan_title) ?>" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm">
                    </div>
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Paragraf (masing-masing di baris terpisah)</label>
                        <?php $par_text = implode("\n\n", is_array($sambutan_pars) ? $sambutan_pars : []); ?>
                        <textarea name="sambutan_pars" rows="8" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm"><?= htmlspecialchars($par_text) ?></textarea>
                    </div>
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Penutup</label>
                        <input type="text" name="sambutan_closing" value="<?= htmlspecialchars($sambutan_closing) ?>" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm">
                    </div>
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Pengirim</label>
                        <input type="text" name="sambutan_sender" value="<?= htmlspecialchars($sambutan_sender) ?>" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm">
                    </div>
                    <div class="text-center">
                        <button type="submit" class="bg-white text-teal-900 font-display font-semibold px-8 py-2.5 rounded-full hover:bg-gray-100 transition-all shadow-lg text-sm">
                            <i class="bi bi-check-lg mr-1"></i> Simpan Sambutan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Lokasi Pemakaman Settings -->
            <div id="makam-section" class="bg-teal-900/60 border border-teal-800 rounded-2xl p-4 md:p-6 shadow-lg">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-lg bg-teal-800 flex items-center justify-center text-teal-300 border border-teal-700">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>
                    <div>
                        <h3 class="font-display font-bold text-white">Lokasi Pemakaman</h3>
                        <p class="text-xs text-teal-400">Edit alamat, link maps, dan foto pemakaman</p>
                    </div>
                </div>

                <?php if ($this->session->flashdata('makam_success')): ?>
                <div class="bg-green-500/20 border border-green-500/40 text-green-200 px-5 py-3 rounded-lg text-sm mb-4">
                    <?= $this->session->flashdata('makam_success') ?>
                </div>
                <?php endif; ?>

                <form method="post" enctype="multipart/form-data" class="space-y-4">
                    <input type="hidden" name="save_makam" value="1">
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Alamat</label>
                        <textarea name="makam_address" rows="3" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Alamat pemakaman..."><?= htmlspecialchars($makam_address) ?></textarea>
                    </div>
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Link Embed (buat peta)</label>
                        <input type="text" name="makam_maps_url" value="<?= htmlspecialchars($makam_maps_url) ?>" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="https://www.google.com/maps/embed?pb=...">
                    </div>
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Link Google Maps (buat tombol Lihat Detail & Rute)</label>
                        <input type="text" name="makam_maps_link" value="<?= htmlspecialchars($makam_maps_link) ?>" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-4 py-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="https://www.google.com/maps/search/?api=1&query=...">
                    </div>
                    <div>
                        <label class="text-sm text-teal-400 font-semibold mb-1 block">Foto Pemakaman</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3" id="makam-photo-grid">
                            <?php foreach ($makam_photos as $i => $photo): ?>
                            <div class="bg-teal-800/40 border border-teal-700 rounded-xl p-3 space-y-2 relative">
                                <button type="button" onclick="deleteMakamPhoto(<?= $i ?>)" class="absolute top-1 right-1 w-6 h-6 bg-red-500/80 hover:bg-red-500 text-white rounded-full flex items-center justify-center text-sm leading-none z-10">&times;</button>
                                <div class="relative group cursor-pointer" onclick="previewCarousel(this)">
                                    <img src="<?= base_url($photo) ?>" class="w-full h-24 object-cover rounded-lg border border-teal-700">
                                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all rounded-lg flex items-center justify-center">
                                        <i class="bi bi-arrows-fullscreen text-white text-xl opacity-0 group-hover:opacity-100 transition-all"></i>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3">
                            <label class="text-sm text-teal-400 font-semibold mb-1 block">Tambah Foto Baru</label>
                            <div class="drop-zone border-2 border-dashed border-teal-700 rounded-xl p-4 text-center cursor-pointer hover:border-teal-500 transition-all" onclick="document.getElementById('makam-photo-new').click()" ondragover="event.preventDefault();this.classList.add('border-teal-400','bg-teal-800/50')" ondragleave="this.classList.remove('border-teal-400','bg-teal-800/50')" ondrop="handleMakamDrop(event)">
                                <i class="bi bi-cloud-arrow-up text-2xl text-teal-400"></i>
                                <p class="text-sm text-teal-300 mt-1">Klik atau drag & drop foto</p>
                                <input id="makam-photo-new" type="file" name="makam_photo_new[]" accept="image/*" multiple class="hidden" onchange="handleMakamFiles(this)">
                            </div>
                            <div id="makam-new-previews" class="row g-2 mt-2"></div>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="bg-white text-teal-900 font-display font-semibold px-8 py-2.5 rounded-full hover:bg-gray-100 transition-all shadow-lg text-sm">
                            <i class="bi bi-check-lg mr-1"></i> Simpan
                        </button>
                    </div>
                </form>
            </div>

        </div>

    </main>

    <!-- Modal Preview -->
    <div id="banner-modal" class="fixed inset-0 bg-black/80 z-[9999] hidden items-center justify-center" onclick="if(event.target===this)closePreview()">
        <button onclick="closePreview()" class="absolute top-6 right-6 text-white text-4xl hover:text-gray-300 transition-all">&times;</button>
        <img id="banner-modal-img" class="max-w-[90vw] max-h-[90vh] rounded-2xl shadow-2xl">
    </div>

    <script>
        function previewBanner(el) {
            const img = el.querySelector('img');
            const modal = document.getElementById('banner-modal');
            const modalImg = document.getElementById('banner-modal-img');
            modalImg.src = img.src;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closePreview() {
            const modal = document.getElementById('banner-modal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
        }

function handleDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    const zone = e.target.closest('.drop-zone');
    zone.classList.remove('border-teal-400', 'bg-teal-800/50');

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const fileInput = document.getElementById('banner-upload');
        if (fileInput) {
            fileInput.files = files;
            const reader = new FileReader();
            reader.onload = function(ev) {
                document.getElementById('banner-preview-img').src = ev.target.result;
            };
            reader.readAsDataURL(files[0]);
        }
    }
}

function previewFile(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById(previewId).src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewCarousel(el) {
    const img = el.querySelector('img');
    const modal = document.getElementById('banner-modal');
    const modalImg = document.getElementById('banner-modal-img');
    modalImg.src = img.src;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function addCarouselCard() {
    const grid = document.getElementById('carousel-grid');
    const idx = grid.children.length;
    const card = document.createElement('div');
    card.className = 'bg-teal-800/40 border border-teal-700 rounded-xl p-4 space-y-3';
    card.innerHTML = `
        <div class="w-full h-36 bg-teal-800 rounded-lg border border-dashed border-teal-600 flex items-center justify-center text-teal-500 text-xs">Preview</div>
        <input type="text" name="captions[]" value="Keluarga" class="w-full bg-teal-800 border border-teal-700 rounded-lg px-3 py-2 text-white text-sm focus:outline-none focus:ring-2 focus:ring-teal-500" placeholder="Caption">
        <div class="flex gap-2">
            <div class="drop-zone-carousel flex-1 border-2 border-dashed border-teal-700 rounded-lg p-3 text-center cursor-pointer hover:border-teal-500 transition-all text-xs" onclick="document.getElementById('carousel-upload-new-${idx}').click()" ondragover="event.preventDefault();this.classList.add('border-teal-400','bg-teal-800/50')" ondragleave="this.classList.remove('border-teal-400','bg-teal-800/50')" ondrop="handleCarouselDrop(event, -1)">
                <i class="bi bi-cloud-arrow-up text-teal-400"></i>
                <p class="text-teal-400 mt-1">Ganti</p>
                <input id="carousel-upload-new-${idx}" type="file" name="carousel_file[]" accept="image/*" class="hidden" onchange="previewCarouselInput(this, -1)">
            </div>
        </div>
    `;
    grid.appendChild(card);
}

function handleCarouselDrop(e, idx) {
    e.preventDefault();
    e.stopPropagation();
    const zone = e.target.closest('.drop-zone-carousel');
    zone.classList.remove('border-teal-400', 'bg-teal-800/50');

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        const inputId = idx >= 0 ? 'carousel-upload-' + idx : zone.querySelector('input[type="file"]').id;
        const input = document.getElementById(inputId);
        if (input) {
            input.files = files;
            const reader = new FileReader();
            reader.onload = function(ev) {
                const box = zone.closest('.space-y-3');
                const existingImg = box.querySelector('.relative.group');
                if (existingImg) {
                    existingImg.querySelector('img').src = ev.target.result;
                } else {
                    const placeholder = box.querySelector('div:first-child');
                    if (placeholder) {
                        placeholder.outerHTML =
                            '<div class="relative group cursor-pointer" onclick="previewCarousel(this)">' +
                                '<img src="' + ev.target.result + '" class="w-full h-36 object-cover rounded-lg border border-teal-700">' +
                                '<div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all rounded-lg flex items-center justify-center">' +
                                    '<i class="bi bi-arrows-fullscreen text-white text-xl opacity-0 group-hover:opacity-100 transition-all"></i>' +
                                '</div>' +
                            '</div>';
                    }
                }
            };
            reader.readAsDataURL(files[0]);
        }
    }
}

function previewCarouselInput(input, idx) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const box = input.closest('.space-y-3');
            const wrapper = box.querySelector('.relative.group');
            if (wrapper) {
                wrapper.querySelector('img').src = e.target.result;
            } else {
                const placeholder = box.querySelector('div:first-child');
                if (placeholder) {
                    placeholder.outerHTML =
                        '<div class="relative group cursor-pointer" onclick="previewCarousel(this)">' +
                            '<img src="' + e.target.result + '" class="w-full h-36 object-cover rounded-lg border border-teal-700">' +
                            '<div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-all rounded-lg flex items-center justify-center">' +
                                '<i class="bi bi-arrows-fullscreen text-white text-xl opacity-0 group-hover:opacity-100 transition-all"></i>' +
                            '</div>' +
                        '</div>';
                }
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

var makamNewFiles = [];

function deleteMakamPhoto(idx) {
    window.location.href = '<?= base_url('admin') ?>?delete_makam_photo=' + idx + '#makam-section';
}

function renderMakamPreviews() {
    var container = document.getElementById('makam-new-previews');
    container.innerHTML = '';
    makamNewFiles.forEach(function(file, idx) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var col = document.createElement('div');
            col.className = 'inline-block mr-2 mb-2 align-top';
            col.style.cssText = 'width:calc(20% - 0.5rem);min-width:100px;';
            col.innerHTML = '<div style="position:relative;border-radius:8px;overflow:hidden;">' +
                '<img src="' + e.target.result + '" style="width:100%;height:80px;object-fit:cover;border-radius:8px;border:1px solid rgba(77,107,103,.3);display:block;">' +
                '<button type="button" onclick="removeMakamNewFile(' + idx + ')" style="position:absolute;top:2px;right:2px;width:20px;height:20px;background:rgba(225,67,67,.85);color:#fff;border:none;border-radius:50%;font-size:14px;line-height:1;cursor:pointer;display:flex;align-items:center;justify-content:center;">&times;</button>' +
                '</div>';
            container.appendChild(col);
        };
        reader.readAsDataURL(file);
    });
}

function handleMakamFiles(input) {
    if (input.files && input.files.length > 0) {
        for (var i = 0; i < input.files.length; i++) {
            makamNewFiles.push(input.files[i]);
        }
        input.value = '';
        renderMakamPreviews();
    }
}

function handleMakamDrop(e) {
    e.preventDefault();
    e.stopPropagation();
    var zone = e.target.closest('.drop-zone');
    zone.classList.remove('border-teal-400', 'bg-teal-800/50');
    if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
        for (var i = 0; i < e.dataTransfer.files.length; i++) {
            makamNewFiles.push(e.dataTransfer.files[i]);
        }
        renderMakamPreviews();
    }
}

function removeMakamNewFile(idx) {
    makamNewFiles.splice(idx, 1);
    renderMakamPreviews();
}

// Before form submit, populate file input with makamNewFiles
document.querySelector('form input[name="save_makam"]').closest('form').addEventListener('submit', function(e) {
    if (makamNewFiles.length > 0) {
        var dt = new DataTransfer();
        makamNewFiles.forEach(function(f) { dt.items.add(f); });
        document.getElementById('makam-photo-new').files = dt.files;
    }
});

function deleteCarousel(index) {
    if (confirm('Hapus item ini?')) {
        const form = document.getElementById('carousel-form');
        const saveBtn = form.querySelector('button[name="save_carousel"]');
        if (saveBtn) saveBtn.disabled = true;
        const saveInput = form.querySelector('input[name="save_carousel"]');
        if (saveInput) saveInput.remove();
        const exists = form.querySelector('input[name="delete_carousel"]');
        if (exists) exists.remove();
        const inp = document.createElement('input');
        inp.type = 'hidden';
        inp.name = 'delete_carousel';
        inp.value = '1';
        form.appendChild(inp);
        const idxInp = document.createElement('input');
        idxInp.type = 'hidden';
        idxInp.name = 'delete_index';
        idxInp.value = index;
        form.appendChild(idxInp);
        form.submit();
    }
}
    </script>
</body>
</html>
