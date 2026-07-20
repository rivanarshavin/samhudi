<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Berita | Admin Panel</title>
    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
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
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #15201E; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }

        /* Highlighted row glow */
        .row-highlighted {
            background: linear-gradient(90deg, rgba(212,181,113,0.08) 0%, rgba(212,181,113,0.04) 100%) !important;
        }
        .row-highlighted td:first-child {
            border-left: 3px solid #D4B571;
        }

        /* Star button styles */
        .btn-star-on  { color: #D4B571; background: rgba(212,181,113,0.15); border-color: rgba(212,181,113,0.4); }
        .btn-star-off { color: rgba(255,255,255,0.25); background: transparent; border-color: rgba(255,255,255,0.08); }
        .btn-star-on:hover  { background: rgba(212,181,113,0.3); }
        .btn-star-off:hover { color: #D4B571; background: rgba(212,181,113,0.1); border-color: rgba(212,181,113,0.3); }
    </style>
</head>
<body class="bg-teal-950 text-white font-body min-h-screen flex">

    <!-- Sidebar -->
    <?php $this->load->view('admin/sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-y-auto">

        <!-- Header -->
        <?php $this->load->view('admin/header'); ?>

        <!-- Content Area -->
        <div class="p-8 space-y-8">

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-300 px-6 py-4 rounded-xl flex items-center gap-3">
                    <i class="bi bi-check-circle-fill text-lg"></i>
                    <span class="text-sm font-semibold"><?= $this->session->flashdata('success') ?></span>
                </div>
            <?php endif; ?>
            <?php if ($this->session->flashdata('error')): ?>
                <div class="bg-red-500/20 border border-red-500 text-red-300 px-6 py-4 rounded-xl flex items-center gap-3">
                    <i class="bi bi-exclamation-circle-fill text-lg"></i>
                    <span class="text-sm font-semibold"><?= $this->session->flashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <!-- Title & Add Button -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="font-display font-extrabold text-2xl text-white">Kelola Berita</h2>
                    <p class="text-brand-light/70 text-xs mt-1">Tambah, kelola, dan highlight berita yang ditampilkan di halaman utama.</p>
                </div>
                <a href="<?= base_url('admin/berita_add') ?>"
                   class="flex items-center justify-center gap-2 bg-gradient-to-r from-brand-medium to-brand-dark hover:from-brand-medium/90 hover:to-brand-dark/90 border border-brand-medium text-white px-5 py-3 rounded-xl text-sm font-bold shadow-md transition-all">
                    <i class="bi bi-plus-circle-fill"></i>
                    <span>Tambah Berita</span>
                </a>
            </div>

            <!-- Highlight Info Banner -->
            <?php
            $highlighted_id = null;
            foreach ($news_list as $n) {
                if (!empty($n['is_highlight'])) { $highlighted_id = $n['id']; break; }
            }
            ?>
            <?php if ($highlighted_id): ?>
            <div class="flex items-center gap-3 bg-yellow-500/10 border border-yellow-500/30 rounded-xl px-5 py-3">
                <i class="bi bi-star-fill text-yellow-400"></i>
                <span class="text-sm text-yellow-200 font-medium">
                    Ada 1 berita yang sedang di-highlight — akan tampil sebagai <strong>featured card</strong> di halaman publik.
                </span>
            </div>
            <?php else: ?>
            <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-xl px-5 py-3">
                <i class="bi bi-star text-white/30"></i>
                <span class="text-sm text-white/40">
                    Belum ada berita yang di-highlight. Klik ikon ⭐ pada baris berita untuk men-highlight-nya.
                </span>
            </div>
            <?php endif; ?>

            <!-- Filter & Search -->
            <div class="bg-brand-dark/20 border border-brand-medium/20 rounded-2xl p-6 shadow-sm">
                <form method="GET" action="<?= base_url('admin/berita') ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="relative md:col-span-2">
                        <i class="bi bi-search absolute left-4 top-3.5 text-white/40"></i>
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                               placeholder="Cari judul berita..."
                               class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 pl-11 pr-4 text-sm text-white placeholder-white/40 focus:outline-none focus:border-brand-medium transition-all">
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <select name="status"
                                class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            <option value="">Semua Status</option>
                            <option value="publish" <?= $status == 'publish' ? 'selected' : '' ?>>Publish</option>
                            <option value="draft"   <?= $status == 'draft'   ? 'selected' : '' ?>>Draft</option>
                        </select>
                    </div>

                    <!-- Filter Button -->
                    <div class="flex gap-2">
                        <button type="submit"
                                class="flex-1 bg-brand-medium hover:bg-brand-medium/90 border border-brand-medium text-white px-4 py-3 rounded-xl flex items-center justify-center gap-2 text-sm font-semibold transition-all">
                            <i class="bi bi-funnel-fill"></i>
                            <span>Filter</span>
                        </button>
                        <?php if (!empty($search) || !empty($status)): ?>
                            <a href="<?= base_url('admin/berita') ?>"
                               class="border border-brand-medium/30 text-white/60 hover:text-white px-4 py-3 rounded-xl flex items-center justify-center transition-all">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-gradient-to-b from-brand-dark/20 to-brand-dark/5 border border-brand-medium/20 rounded-2xl p-6 shadow-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#4D6B67]/20">
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Berita</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Link Eksternal</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Penulis</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Status</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Tanggal</th>
                                <th class="pb-4 text-xs font-bold text-yellow-400/70 uppercase tracking-wider text-center">
                                    <i class="bi bi-star-fill mr-1"></i>Highlight
                                </th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#4D6B67]/10">
                            <?php if (!empty($news_list)): ?>
                                <?php foreach ($news_list as $news): ?>
                                    <?php $isHighlighted = !empty($news['is_highlight']); ?>
                                    <tr class="transition-colors <?= $isHighlighted ? 'row-highlighted' : 'hover:bg-brand-dark/10' ?>">
                                        <!-- Thumbnail & Judul -->
                                        <td class="py-4 pr-4">
                                            <div class="flex items-center gap-3">
                                                <?php if (!empty($news['thumbnail']) && file_exists('./' . $news['thumbnail'])): ?>
                                                    <div class="relative shrink-0">
                                                        <img src="<?= base_url($news['thumbnail']) ?>"
                                                             alt="<?= htmlspecialchars($news['title']) ?>"
                                                             class="w-12 h-10 object-cover rounded-lg border <?= $isHighlighted ? 'border-yellow-500/50' : 'border-brand-medium/20' ?>">
                                                        <?php if ($isHighlighted): ?>
                                                        <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-yellow-400 rounded-full flex items-center justify-center">
                                                            <i class="bi bi-star-fill text-[8px] text-yellow-900"></i>
                                                        </span>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php else: ?>
                                                    <div class="w-12 h-10 rounded-lg <?= $isHighlighted ? 'bg-yellow-500/20 border border-yellow-500/30' : 'bg-brand-medium/20 border border-brand-medium/10' ?> flex items-center justify-center shrink-0">
                                                        <i class="bi bi-newspaper text-white/30 text-lg"></i>
                                                    </div>
                                                <?php endif; ?>
                                                <div class="min-w-0">
                                                    <div class="flex items-center gap-2">
                                                        <div class="font-semibold text-white text-sm truncate max-w-[200px]">
                                                            <?= htmlspecialchars($news['title']) ?>
                                                        </div>
                                                        <?php if ($isHighlighted): ?>
                                                        <span class="shrink-0 inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                                                            <i class="bi bi-star-fill text-[8px]"></i> HIGHLIGHT
                                                        </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-xs text-white/40 mt-0.5">
                                                        <?= number_format($news['views'] ?? 0) ?> views
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Link Eksternal -->
                                        <td class="py-4 pr-4">
                                            <?php if (!empty($news['external_link'])): ?>
                                                <a href="<?= htmlspecialchars($news['external_link']) ?>" target="_blank"
                                                   class="flex items-center gap-1.5 text-teal-400 hover:text-teal-300 text-xs font-medium transition-colors max-w-[180px]">
                                                    <i class="bi bi-box-arrow-up-right shrink-0"></i>
                                                    <span class="truncate"><?= htmlspecialchars(parse_url($news['external_link'], PHP_URL_HOST) ?: $news['external_link']) ?></span>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-xs text-white/30 italic">Tidak ada</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Penulis -->
                                        <td class="py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-brand-medium/40 flex items-center justify-center text-xs font-bold text-white border border-brand-medium/20">
                                                    <?= strtoupper(substr($news['author_name'] ?? 'A', 0, 1)) ?>
                                                </div>
                                                <span class="text-sm text-white/80">
                                                    <?= htmlspecialchars($news['author_name'] ?? 'Admin') ?>
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Status -->
                                        <td class="py-4">
                                            <?php if ($news['status'] === 'publish'): ?>
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">
                                                    <i class="bi bi-broadcast me-1"></i>Publish
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-300 border border-yellow-500/30">
                                                    <i class="bi bi-file-earmark-text me-1"></i>Draft
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Tanggal -->
                                        <td class="py-4 text-sm text-white/60">
                                            <?php
                                                $ts = strtotime($news['created_at']);
                                                $months_id = [1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                                                echo date('j', $ts) . ' ' . $months_id[(int)date('n', $ts)] . ' ' . date('Y', $ts);
                                            ?>
                                        </td>

                                        <!-- Highlight Toggle -->
                                        <td class="py-4 text-center">
                                            <a href="<?= base_url('admin/berita_highlight/' . $news['id']) ?>"
                                               title="<?= $isHighlighted ? 'Cabut Highlight' : 'Set sebagai Highlight' ?>"
                                               onclick="return confirm('<?= $isHighlighted ? 'Cabut highlight berita ini?' : 'Set berita ini sebagai featured/highlight? Highlight berita lain akan dicabut.' ?>')"
                                               class="inline-flex items-center justify-center w-9 h-9 rounded-lg border transition-all <?= $isHighlighted ? 'btn-star-on' : 'btn-star-off' ?>">
                                                <i class="bi bi-star<?= $isHighlighted ? '-fill' : '' ?> text-sm"></i>
                                            </a>
                                        </td>

                                        <!-- Aksi -->
                                        <td class="py-4 text-right space-x-1.5">
                                            <!-- Toggle Status -->
                                            <a href="<?= base_url('admin/berita_toggle_status/' . $news['id']) ?>"
                                               title="<?= $news['status'] === 'publish' ? 'Jadikan Draft' : 'Publish' ?>"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg <?= $news['status'] === 'publish' ? 'bg-yellow-500/10 text-yellow-400 hover:bg-yellow-500 hover:text-black border border-yellow-500/20' : 'bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-black border border-emerald-500/20' ?> transition-all">
                                                <i class="bi bi-<?= $news['status'] === 'publish' ? 'eye-slash-fill' : 'broadcast' ?> text-sm"></i>
                                            </a>
                                            <!-- Edit -->
                                            <a href="<?= base_url('admin/berita_edit/' . $news['id']) ?>"
                                               title="Edit"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-500/10 text-yellow-400 hover:bg-yellow-500 hover:text-black border border-yellow-500/20 transition-all">
                                                <i class="bi bi-pencil-square text-sm"></i>
                                            </a>
                                            <!-- Hapus -->
                                            <a href="<?= base_url('admin/berita_delete/' . $news['id']) ?>"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?')"
                                               title="Hapus"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-red/10 text-brand-red hover:bg-brand-red hover:text-white border border-brand-red/20 transition-all">
                                                <i class="bi bi-trash-fill text-sm"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="py-16 text-center">
                                        <div class="flex flex-col items-center gap-3 text-white/30">
                                            <i class="bi bi-newspaper text-4xl"></i>
                                            <span class="text-sm">
                                                <?= (!empty($search) || !empty($status)) ? 'Tidak ada berita yang cocok dengan filter.' : 'Belum ada berita. Klik "Tambah Berita" untuk memulai.' ?>
                                            </span>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </main>

</body>
</html>
