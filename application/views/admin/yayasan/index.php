<?php
if (!function_exists('build_nomination_trees')) {
    function build_nomination_trees($candidates_list) {
        $candidates_by_name = [];
        foreach ($candidates_list as $c) {
            $candidates_by_name[strtolower(trim($c['candidate_name']))] = $c;
        }
        
        $children = [];
        $roots = [];
        
        foreach ($candidates_list as $c) {
            $nom = strtolower(trim($c['nominator_name']));
            if (isset($candidates_by_name[$nom])) {
                $children[$nom][] = $c;
            } else {
                $roots[$c['nominator_name']][] = $c;
            }
        }
        
        return ['roots' => $roots, 'children' => $children];
    }
}

if (!function_exists('render_tree_node')) {
    function render_tree_node($cand, $children) {
        $cand_key = strtolower(trim($cand['candidate_name']));
        $has_children = isset($children[$cand_key]);
        ?>
        <div class="flex flex-col gap-3 text-left">
            <!-- Candidate Node Card -->
            <div class="flex items-center gap-3">
                <div class="w-3 h-0.5 bg-emerald-500/50"></div>
                <div class="bg-gradient-to-r from-emerald-500/10 to-emerald-500/0 border border-emerald-500/25 rounded-2xl px-5 py-3 flex items-center justify-between gap-6 transition-all duration-300">
                    <div>
                        <?php 
                        $role_raw = trim((isset($cand['roles_text']) && $cand['roles_text'] !== '-') ? $cand['roles_text'] : ($cand['description'] ?: ''));
                        $is_ketua      = preg_match('/ketua/i', $role_raw);
                        $is_bendahara  = preg_match('/bendahara/i', $role_raw);
                        $is_sekretaris = preg_match('/sekretaris/i', $role_raw);
                        if ($is_ketua)           { $role_lbl = 'Kandidat Ketua'; }
                        elseif ($is_bendahara)   { $role_lbl = 'Kandidat Bendahara'; }
                        elseif ($is_sekretaris)  { $role_lbl = 'Kandidat Sekretaris'; }
                        else                     { $role_lbl = 'Kandidat Ketua'; }
                        ?>
                        <span class="text-[10px] uppercase font-bold text-emerald-400 tracking-wider block mb-0.5"><?= htmlspecialchars($role_lbl) ?></span>
                        <strong class="text-white text-base font-semibold"><?= htmlspecialchars($cand['candidate_name']) ?></strong>
                    </div>
                </div>
            </div>
            
            <!-- Recursive Children -->
            <?php if ($has_children): ?>
                <div class="flex flex-col gap-3 pl-8 border-l border-emerald-500/25 ml-[26px] pt-1">
                    <?php foreach ($children[$cand_key] as $child): ?>
                        <?php render_tree_node($child, $children); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}

if (!function_exists('render_custom_pagination')) {
    function render_custom_pagination($total_rows, $limit, $current_page, $param_name) {
        $total_pages = ceil($total_rows / $limit);
        if ($total_pages <= 1) return '';
        
        $get = $_GET;
        unset($get[$param_name]);
        unset($get['page']);
        $query = http_build_query($get);
        $url_prefix = current_url() . ($query ? '?' . $query . '&' : '?') . $param_name . '=';
        
        $html = '<div class="flex items-center justify-center gap-1.5 mt-4">';
        
        if ($current_page > 1) {
            $html .= '<a href="' . $url_prefix . ($current_page - 1) . '" class="px-3.5 py-2 rounded-xl bg-[#1A2824] hover:bg-[#2c3f3a] text-white text-xs font-semibold border border-[#4D6B67]/30 transition-all"><i class="bi bi-chevron-left"></i></a>';
        }
        
        for ($i = 1; $i <= $total_pages; $i++) {
            if ($i === $current_page) {
                $html .= '<span class="px-3.5 py-2 rounded-xl bg-brand-medium text-white text-xs font-bold border border-brand-medium/50 shadow-md shadow-brand-medium/10">' . $i . '</span>';
            } else {
                $html .= '<a href="' . $url_prefix . $i . '" class="px-3.5 py-2 rounded-xl bg-[#1A2824] hover:bg-[#2c3f3a] text-white text-xs font-semibold border border-[#4D6B67]/30 transition-all">' . $i . '</a>';
            }
        }
        
        if ($current_page < $total_pages) {
            $html .= '<a href="' . $url_prefix . ($current_page + 1) . '" class="px-3.5 py-2 rounded-xl bg-[#1A2824] hover:bg-[#2c3f3a] text-white text-xs font-semibold border border-[#4D6B67]/30 transition-all"><i class="bi bi-chevron-right"></i></a>';
        }
        
        $html .= '</div>';
        return $html;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Calon Yayasan | Admin Panel</title>
    <link rel="icon" type="image/jpeg" href="<?= base_url('assets/favicon.jpeg') ?>">
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

    <!-- Highcharts 3D Pie Chart Library -->
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/highcharts-3d.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>

    <style>
        * { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #15201E; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }

        .highcharts-credits { display: none !important; }
        .highcharts-background { fill: transparent !important; }

        .rundayan-hover {
            position: relative;
            cursor: pointer;
            text-decoration: underline;
            text-decoration-style: dotted;
            text-underline-offset: 3px;
        }
    </style>
</head>
<body class="bg-teal-950 text-white font-body h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <?php $this->load->view('admin/sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-y-auto overflow-x-hidden">
        
        <!-- Header -->
        <?php $this->load->view('admin/header'); ?>

        <!-- Content Area -->
        <div class="p-4 md:p-8 space-y-8">
            
            <!-- Toast Alert for success/error messages -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="bg-emerald-500/20 border border-emerald-500 text-emerald-300 px-6 py-4 rounded-xl flex items-center gap-3">
                    <i class="bi bi-check-circle-fill text-lg"></i>
                    <span class="text-sm font-semibold"><?= $this->session->flashdata('success') ?></span>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
                <div class="bg-red-500/20 border border-red-500 text-red-300 px-6 py-4 rounded-xl flex items-center gap-3">
                    <i class="bi bi-exclamation-triangle-fill text-lg"></i>
                    <span class="text-sm font-semibold"><?= $this->session->flashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <!-- Title & Action -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="font-display font-extrabold text-2xl text-white">Kelola Calon Yayasan</h2>
                    <p class="text-brand-light/70 text-xs mt-1">Daftar calon ketua yayasan hasil pencalonan keluarga besar beserta jumlah suara (votes).</p>
                </div>
                
                <!-- Action Buttons: Dewan Pembina QR Code -->
                <div class="flex items-center gap-3">
                    <button onclick="openQrModal()" class="px-4 py-2.5 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-teal-950 font-display font-bold text-xs rounded-xl shadow-lg shadow-amber-500/20 flex items-center gap-2 transition-all">
                        <i class="bi bi-qr-code-scan text-base"></i> QR Code Dewan Pembina
                    </button>
                </div>
            </div>

            <!-- SECTION: CHART 3D PIE (REKAPITULASI DUKUNGAN ADMIN PALING ATAS) -->
            <div class="bg-gradient-to-b from-[#182c29] to-[#122220] border border-teal-700/40 rounded-2xl p-6 shadow-xl space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-teal-700/30 pb-4">
                    <div>
                        <h3 class="font-display font-bold text-xl text-white flex items-center gap-2">
                            <i class="bi bi-pie-chart-fill text-amber-400"></i> Chart 3D Pie Rekapitulasi Suara
                        </h3>
                        <p class="text-xs text-white/60 mt-0.5">Grafik 3D perolehan suara pencalonan ketua yayasan.</p>
                    </div>
                    <!-- Chart Type Switcher Buttons -->
                    <div class="flex bg-black/40 p-1 rounded-xl border border-white/10 text-xs">
                        <button id="btn_chart_individu" onclick="switchChart('individu')" class="px-4 py-1.5 rounded-lg font-bold transition-all bg-emerald-500 text-white shadow">
                            Individu
                        </button>
                        <button id="btn_chart_rundayan" onclick="switchChart('rundayan')" class="px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all">
                            Rundayan
                        </button>
                    </div>
                </div>

                <div class="relative min-h-[380px] flex items-center justify-center">
                    <div id="container_chart_3d" class="w-full h-[400px]"></div>
                </div>
            </div>

            <!-- Filters & Search -->
            <div class="bg-brand-dark/20 border border-brand-medium/20 rounded-2xl p-6 shadow-sm">
                <form method="GET" action="<?= base_url('admin/yayasan') ?>" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    
                    <!-- Search Input -->
                    <div class="relative md:col-span-8">
                        <i class="bi bi-search absolute left-4 top-3.5 text-white/40"></i>
                        <input type="text" name="search" id="input_search_main" value="<?= htmlspecialchars($search) ?>" autocomplete="off" placeholder="Cari nama calon, yayasan, buyut..." class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 pl-11 pr-4 text-sm text-white placeholder-white/40 focus:outline-none focus:border-brand-medium transition-all">
                        <div id="search_main_suggestions_box" class="absolute left-0 right-0 top-full mt-1 bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden z-[11000] divide-y divide-white/5"></div>
                    </div>

                    <!-- Status Filter -->
                    <div class="md:col-span-3">
                        <select name="status" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            <option value="">Semua Status</option>
                            <option value="pending" <?= ($status ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="approved" <?= ($status ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="rejected" <?= ($status ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>

                    <!-- Filter Button -->
                    <div class="md:col-span-1">
                        <button type="submit" class="w-full h-full bg-brand-medium hover:bg-brand-medium/90 border border-brand-medium text-white rounded-xl flex items-center justify-center transition-all py-3">
                            <i class="bi bi-funnel-fill"></i>
                        </button>
                    </div>

                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-gradient-to-b from-brand-dark/20 to-brand-dark/5 border border-brand-medium/20 rounded-2xl p-6 shadow-lg">
                <div class="overflow-x-auto">
                    <table id="table_main" class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#4D6B67]/20">
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap">Calon</th>
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap">Pencalon</th>
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap">Undayan / Buyut</th>
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap">Jenis</th>
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap">Jumlah Suara</th>
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap">Status</th>
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap">Tanggal Masuk</th>
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#4D6B67]/10">
                            <?php if (!empty($candidates)): ?>
                                <?php foreach ($candidates as $c): ?>
                                    <tr>
                                        <!-- Candidate Name -->
                                        <td class="py-4 px-4 whitespace-nowrap">
                                            <div class="font-bold text-white"><?= htmlspecialchars($c['candidate_name']) ?></div>
                                            <div class="text-xs text-brand-light/60 max-w-xs truncate" title="<?= htmlspecialchars($c['description'] ?? '') ?>">
                                                <?= htmlspecialchars($c['description'] ?: 'Tidak ada keterangan') ?>
                                            </div>
                                        </td>
                                        
                                        <!-- Nominator Name -->
                                        <td class="py-4 px-4 whitespace-nowrap text-sm text-brand-light">
                                            <?= htmlspecialchars($c['nominator_name']) ?>
                                        </td>

                                        <!-- Ancestor Name -->
                                        <td class="py-4 px-4 whitespace-nowrap text-sm text-brand-light">
                                            <span class="rundayan-hover text-emerald-300 font-semibold" onmouseenter="showRundayanHover(event, '<?= htmlspecialchars(addslashes($c['ancestor_name'])) ?>')" onmouseleave="hideRundayanHover()">
                                                <?= htmlspecialchars($c['ancestor_name']) ?>
                                            </span>
                                        </td>

                                        <!-- Type -->
                                        <td class="py-4 px-4 whitespace-nowrap text-sm">
                                             <span class="px-2.5 py-1 rounded-full text-xs font-semibold <?= ($c['type'] ?? 'individu') === 'rundayan' ? 'bg-cyan-500/20 text-cyan-300 border border-cyan-500/30' : 'bg-amber-500/20 text-amber-300 border border-amber-500/30' ?>">
                                                 <?= ucfirst(htmlspecialchars($c['type'] ?? 'individu')) ?>
                                             </span>
                                        </td>

                                        <!-- Votes Count -->
                                        <td class="py-4 px-4 whitespace-nowrap text-sm">
                                            <span class="px-3 py-1 rounded bg-[#112426] border border-amber-500/20 text-amber-400 font-extrabold text-base">
                                                <?= $c['votes_count'] ?>
                                            </span>
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="py-4 px-4 whitespace-nowrap text-sm">
                                            <?php if ($c['status'] == 'approved'): ?>
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Approved</span>
                                            <?php elseif ($c['status'] == 'rejected'): ?>
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-500/20 text-red-300 border border-red-500/30">Rejected</span>
                                            <?php else: ?>
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-300 border border-yellow-500/30 flex items-center w-max gap-1">
                                                    <i class="bi bi-hourglass-split"></i> Pending
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Created At -->
                                        <td class="py-4 px-4 whitespace-nowrap text-sm text-brand-light/70">
                                            <?= date('d M Y H:i', strtotime($c['created_at'])) ?>
                                        </td>

                                        <!-- Actions -->
                                        <td class="py-4 px-4 text-right space-x-2 whitespace-nowrap">
                                            <?php if ($c['status'] !== 'approved'): ?>
                                                <a href="<?= base_url('admin/yayasan/status/' . $c['id'] . '/approved') ?>" 
                                                   onclick="showConfirm(event, this.href, 'Setujui pencalonan ini agar tampil di halaman voting?')" 
                                                   class="inline-flex items-center justify-center px-3 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-white border border-emerald-500/20 transition-all text-xs font-bold">
                                                    <i class="bi bi-check-lg mr-1"></i> Approve
                                                </a>
                                            <?php endif; ?>
                                            <?php if ($c['status'] !== 'rejected'): ?>
                                                <a href="<?= base_url('admin/yayasan/status/' . $c['id'] . '/rejected') ?>" 
                                                   onclick="showConfirm(event, this.href, 'Tolak pencalonan ini?')" 
                                                   class="inline-flex items-center justify-center px-3 h-8 rounded-lg bg-yellow-500/10 text-yellow-400 hover:bg-yellow-500 hover:text-black border border-yellow-500/20 transition-all text-xs font-bold">
                                                    <i class="bi bi-x-lg mr-1"></i> Reject
                                                </a>
                                            <?php endif; ?>
                                            
                                            <a href="<?= base_url('admin/yayasan/edit/' . $c['id']) ?>" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-500/10 text-yellow-400 hover:bg-yellow-500 hover:text-black border border-yellow-500/20 transition-all" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="<?= base_url('admin/yayasan/delete/' . $c['id']) ?>" 
                                               onclick="showConfirm(event, this.href, 'Apakah Anda yakin ingin menghapus calon ini? Seluruh data suara akan terhapus.')" 
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-red/10 text-brand-red hover:bg-brand-red hover:text-white border border-brand-red/20 transition-all">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="8" class="py-8 text-center text-white/40 text-sm">Belum ada data pencalonan ketua yayasan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if (isset($this->pagination) && !empty($this->pagination->create_links())): ?>
                    <div class="mt-6 flex flex-col items-center justify-between gap-4 border-t border-white/5 pt-4 sm:flex-row">
                        <span class="text-xs text-white/55">
                            Menampilkan <?= count($candidates) ?> dari <?= $total_rows ?> data pencalonan
                        </span>
                        <?= $this->pagination->create_links() ?>
                    </div>
                <?php endif; ?>

            </div>

            <!-- SECTION: CHART 3D PIE (REKAPITULASI DUKUNGAN ADMIN) -->
            <div class="bg-gradient-to-b from-[#182c29] to-[#122220] border border-teal-700/40 rounded-2xl p-6 shadow-xl space-y-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-teal-700/30 pb-4">
                    <div>
                        <h3 class="font-display font-bold text-xl text-white flex items-center gap-2">
                            <i class="bi bi-pie-chart-fill text-amber-400"></i> Chart 3D Pie Rekapitulasi Suara
                        </h3>
                        <p class="text-xs text-white/60 mt-0.5">Grafik 3D perolehan suara pencalonan ketua yayasan.</p>
                    </div>
                    <!-- Chart Type Switcher Buttons -->
                    <div class="flex bg-black/40 p-1 rounded-xl border border-white/10 text-xs">
                        <button id="btn_chart_individu" onclick="switchChart('individu')" class="px-4 py-1.5 rounded-lg font-bold transition-all bg-emerald-500 text-white shadow">
                            Individu
                        </button>
                        <button id="btn_chart_rundayan" onclick="switchChart('rundayan')" class="px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all">
                            Rundayan
                        </button>
                    </div>
                </div>

                <div class="relative min-h-[380px] flex items-center justify-center">
                    <div id="container_chart_3d" class="w-full h-[400px]"></div>
                </div>
            </div>

            <!-- Rekapitulasi Hasil Pencalonan (Admin Only) -->
            <div class="mt-12 space-y-8">
                <div>
                    <h2 class="font-display font-extrabold text-2xl text-white">Rekapitulasi Hasil Pencalonan</h2>
                    <p class="text-brand-light/70 text-xs mt-1">Hasil pengelompokan calon ketua yayasan beserta rincian pemilih per keturunan/rundayan.</p>
                </div>

                <!-- 1. INDIVIDU TABLE -->
                <div class="bg-gradient-to-b from-[#1b3638] to-[#122829] border border-amber-500/20 rounded-2xl p-6 shadow-xl space-y-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h3 class="text-lg font-bold text-amber-300 flex items-center gap-2">
                            <i class="bi bi-person-fill"></i> Tabel Pencalonan Individu (Rekap)
                        </h3>
                        <!-- Search Individu -->
                        <form method="GET" action="<?= base_url('admin/yayasan') ?>" class="flex gap-2 w-full sm:max-w-xs">
                            <?php 
                            foreach ($_GET as $key => $val) {
                                if ($key !== 'search_individu' && $key !== 'page_individu') {
                                    echo '<input type="hidden" name="'.htmlspecialchars($key).'" value="'.htmlspecialchars($val).'">';
                                }
                            }
                            ?>
                            <div class="relative flex-1">
                                <i class="bi bi-search absolute left-3 top-2.5 text-white/40 text-xs"></i>
                                <input type="text" name="search_individu" id="input_search_individu" value="<?= htmlspecialchars($search_individu ?? '') ?>" autocomplete="off" placeholder="Cari rekap individu..." class="w-full bg-[#1A2824]/50 border border-[#4D6B67]/30 rounded-xl py-2 pl-9 pr-4 text-xs text-white placeholder-white/40 focus:outline-none focus:border-brand-medium transition-all">
                                <div id="search_individu_suggestions_box" class="absolute left-0 right-0 top-full mt-1 bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden z-[11000] divide-y divide-white/5"></div>
                            </div>
                            <button type="submit" class="px-3.5 bg-brand-medium hover:bg-brand-medium/90 border border-brand-medium text-white rounded-xl flex items-center justify-center transition-all py-2 text-xs">
                                Cari
                            </button>
                        </form>
                    </div>

                    <?php if (empty($individu_candidates)): ?>
                        <p class="text-white/40 text-sm italic">Belum ada data pencalonan individu yang approved.</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table id="table_individu" class="w-full text-left border-collapse" style="min-width: 800px;">
                                <thead>
                                    <tr class="border-b border-white/10 text-white/40 text-xs uppercase tracking-wider">
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">No. Urut</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Nama Calon</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Sebagai Calon</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Pencalon / Nominator</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Rundayan / Buyut</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap text-amber-300">Total Dukungan</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap text-emerald-400">Rincian Pemilih</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 text-sm">
                                    <?php foreach ($individu_candidates as $index => $c): ?>
                                        <tr>
                                            <td class="py-3.5 pr-6 text-white/55 whitespace-nowrap">#<?= (($page_individu - 1) * $limit_individu) + $index + 1 ?></td>
                                            <td class="py-3.5 pr-6 font-bold text-white whitespace-nowrap"><?= htmlspecialchars($c['candidate_name']) ?></td>
                                            <td class="py-3.5 pr-6 whitespace-nowrap text-xs">
                                                 <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-300 border border-amber-500/25">
                                                     <?= htmlspecialchars($c['roles_text']) ?>
                                                 </span>
                                            </td>
                                            <td class="py-3.5 pr-6 text-white/80 whitespace-nowrap"><?= htmlspecialchars($c['nominator_name']) ?></td>
                                            <td class="py-3.5 pr-6 text-white/80 whitespace-nowrap">
                                                <span class="rundayan-hover text-emerald-300 font-semibold" onmouseenter="showRundayanHover(event, '<?= htmlspecialchars(addslashes($c['ancestor_name'])) ?>')" onmouseleave="hideRundayanHover()">
                                                    <?= htmlspecialchars($c['ancestor_name']) ?>
                                                </span>
                                            </td>
                                            <td class="py-3.5 pr-6 text-amber-300 font-bold whitespace-nowrap"><?= $c['votes_count'] ?> suara</td>
                                            <td class="py-3.5 pr-6 text-emerald-400 font-semibold whitespace-nowrap"><?= $c['breakdown_text'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Rekap Individu -->
                        <div class="mt-4 flex flex-col items-center justify-between gap-4 border-t border-white/5 pt-4 sm:flex-row">
                            <span class="text-xs text-white/55">
                                Menampilkan <?= count($individu_candidates) ?> dari <?= $total_rows_individu ?> data rekap individu
                            </span>
                            <?= render_custom_pagination($total_rows_individu, $limit_individu, $page_individu, 'page_individu') ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- 2. RUNDAYAN TABLE -->
                <div class="bg-gradient-to-b from-[#112d30] to-[#0c1f21] border border-cyan-500/20 rounded-2xl p-6 shadow-xl space-y-4">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h3 class="text-lg font-bold text-cyan-300 flex items-center gap-2">
                            <i class="bi bi-people-fill"></i> Tabel Pencalonan Rundayan (Rekap)
                        </h3>
                        <!-- Search Rundayan -->
                        <form method="GET" action="<?= base_url('admin/yayasan') ?>" class="flex gap-2 w-full sm:max-w-xs">
                            <?php 
                            foreach ($_GET as $key => $val) {
                                if ($key !== 'search_rundayan' && $key !== 'page_rundayan') {
                                    echo '<input type="hidden" name="'.htmlspecialchars($key).'" value="'.htmlspecialchars($val).'">';
                                }
                            }
                            ?>
                            <div class="relative flex-1">
                                <i class="bi bi-search absolute left-3 top-2.5 text-white/40 text-xs"></i>
                                <input type="text" name="search_rundayan" id="input_search_rundayan" value="<?= htmlspecialchars($search_rundayan ?? '') ?>" autocomplete="off" placeholder="Cari rekap rundayan..." class="w-full bg-[#1A2824]/50 border border-[#4D6B67]/30 rounded-xl py-2 pl-9 pr-4 text-xs text-white placeholder-white/40 focus:outline-none focus:border-brand-medium transition-all">
                                <div id="search_rundayan_suggestions_box" class="absolute left-0 right-0 top-full mt-1 bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden z-[11000] divide-y divide-white/5"></div>
                            </div>
                            <button type="submit" class="px-3.5 bg-brand-medium hover:bg-brand-medium/90 border border-brand-medium text-white rounded-xl flex items-center justify-center transition-all py-2 text-xs">
                                Cari
                            </button>
                        </form>
                    </div>

                    <?php if (empty($rundayan_candidates)): ?>
                        <p class="text-white/40 text-sm italic">Belum ada data pencalonan rundayan yang approved.</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table id="table_rundayan" class="w-full text-left border-collapse" style="min-width: 800px;">
                                <thead>
                                    <tr class="border-b border-white/10 text-white/40 text-xs uppercase tracking-wider">
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">No. Urut</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Nama Calon</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Sebagai Calon</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Pencalon / Nominator</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap">Rundayan / Buyut</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap text-cyan-300">Total Dukungan</th>
                                        <th class="pb-3 pr-6 font-bold whitespace-nowrap text-emerald-400">Rincian Pemilih</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5 text-sm">
                                    <?php foreach ($rundayan_candidates as $index => $c): ?>
                                        <tr>
                                            <td class="py-3.5 pr-6 text-white/55 whitespace-nowrap">#<?= (($page_rundayan - 1) * $limit_rundayan) + $index + 1 ?></td>
                                            <td class="py-3.5 pr-6 font-bold text-white whitespace-nowrap"><?= htmlspecialchars($c['candidate_name']) ?></td>
                                            <td class="py-3.5 pr-6 whitespace-nowrap text-xs">
                                                 <span class="px-2 py-0.5 rounded bg-cyan-500/10 text-cyan-300 border border-cyan-500/25">
                                                     <?= htmlspecialchars($c['roles_text']) ?>
                                                 </span>
                                            </td>
                                            <td class="py-3.5 pr-6 text-white/80 whitespace-nowrap"><?= htmlspecialchars($c['nominator_name']) ?></td>
                                            <td class="py-3.5 pr-6 text-white/80 whitespace-nowrap">
                                                <span class="rundayan-hover text-emerald-300 font-semibold" onmouseenter="showRundayanHover(event, '<?= htmlspecialchars(addslashes($c['ancestor_name'])) ?>')" onmouseleave="hideRundayanHover()">
                                                    <?= htmlspecialchars($c['ancestor_name']) ?>
                                                </span>
                                            </td>
                                            <td class="py-3.5 pr-6 text-cyan-300 font-bold whitespace-nowrap"><?= $c['votes_count'] ?> suara</td>
                                            <td class="py-3.5 pr-6 text-emerald-400 font-semibold whitespace-nowrap"><?= $c['breakdown_text'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Rekap Rundayan -->
                        <div class="mt-4 flex flex-col items-center justify-between gap-4 border-t border-white/5 pt-4 sm:flex-row">
                            <span class="text-xs text-white/55">
                                Menampilkan <?= count($rundayan_candidates) ?> dari <?= $total_rows_rundayan ?> data rekap rundayan
                            </span>
                            <?= render_custom_pagination($total_rows_rundayan, $limit_rundayan, $page_rundayan, 'page_rundayan') ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Bagan Silsilah Pencalonan (Admin Only) -->
            <div class="mt-12 space-y-8">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                    <div>
                        <h2 class="font-display font-extrabold text-2xl text-white">Bagan Silsilah Pencalonan (Rekap)</h2>
                        <p class="text-brand-light/70 text-xs mt-1">Struktur bagan hubungan pengusul dan calon ketua berdasarkan rundayan masing-masing.</p>
                    </div>
                    <!-- Search Bagan -->
                    <form method="GET" action="<?= base_url('admin/yayasan') ?>" class="flex gap-2 w-full sm:max-w-xs">
                        <?php 
                        foreach ($_GET as $key => $val) {
                            if ($key !== 'search_bagan') {
                                echo '<input type="hidden" name="'.htmlspecialchars($key).'" value="'.htmlspecialchars($val).'">';
                            }
                        }
                        ?>
                        <div class="relative flex-1">
                            <i class="bi bi-search absolute left-3 top-2.5 text-white/40 text-xs"></i>
                            <input type="text" name="search_bagan" id="input_search_bagan" value="<?= htmlspecialchars($search_bagan ?? '') ?>" autocomplete="off" placeholder="Cari di silsilah..." class="w-full bg-[#1A2824]/50 border border-[#4D6B67]/30 rounded-xl py-2 pl-9 pr-4 text-xs text-white placeholder-white/40 focus:outline-none focus:border-brand-medium transition-all">
                            <div id="search_bagan_suggestions_box" class="absolute left-0 right-0 top-full mt-1 bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden z-[11000] divide-y divide-white/5"></div>
                        </div>
                        <button type="submit" class="px-3.5 bg-brand-medium hover:bg-brand-medium/90 border border-brand-medium text-white rounded-xl flex items-center justify-center transition-all py-2 text-xs">
                            Cari
                        </button>
                    </form>
                </div>

                <div class="space-y-8">
                    <?php 
                        $raw_grouped_ancestor = [];
                        foreach ($approved_candidates as $c) {
                            $raw_grouped_ancestor[$c['ancestor_name']][] = $c;
                        }
                    ?>
                    <?php if (empty($raw_grouped_ancestor)): ?>
                        <div class="bg-gradient-to-b from-[#1A2824] to-[#121c19] border border-[#4D6B67]/20 rounded-2xl p-6 text-center text-white/40 text-sm italic">
                            Belum ada bagan pencalonan (karena belum ada data disetujui).
                        </div>
                    <?php else: ?>
                        <?php foreach ($raw_grouped_ancestor as $ancestor => $cand_list): ?>
                            <div class="bagan-ancestor-card bg-gradient-to-b from-[#1A2824] to-[#121c19] border border-[#4D6B67]/20 rounded-2xl p-6 shadow-xl">
                                <h3 class="text-xl font-bold text-emerald-300 border-b border-[#4D6B67]/20 pb-3 mb-6 flex items-center gap-2">
                                    <i class="bi bi-diagram-3-fill"></i> Rundayan: 
                                    <span class="rundayan-hover text-white" onmouseenter="showRundayanHover(event, '<?= htmlspecialchars(addslashes($ancestor)) ?>')" onmouseleave="hideRundayanHover()">
                                        <?= htmlspecialchars($ancestor) ?>
                                    </span>
                                </h3>
                                
                                <?php 
                                    $tree_data = build_nomination_trees($cand_list); 
                                    $roots = $tree_data['roots'];
                                    $children = $tree_data['children'];
                                ?>
                                
                                <div class="overflow-x-auto pb-4">
                                    <div class="flex flex-col gap-6" style="min-width: 600px;">
                                        <?php foreach ($roots as $nominator => $root_cands): ?>
                                            <div class="flex flex-col gap-4 pl-4 border-l-2 border-emerald-500/30">
                                                <div class="flex items-center gap-2">
                                                    <span class="px-2.5 py-1 rounded-xl bg-white/5 border border-white/10 text-[10px] font-bold text-white/55 uppercase tracking-wider">Anggota Keluarga Samhudi</span>
                                                    <strong class="text-white text-sm font-semibold"><?= htmlspecialchars($nominator) ?></strong>
                                                </div>
                                                
                                                <div class="flex flex-col gap-3 pl-6">
                                                    <?php foreach ($root_cands as $rc): ?>
                                                        <?php render_tree_node($rc, $children); ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>

        </div>

    </main>
    
    <!-- Modal Confirm -->
    <div id="confirmModal" class="fixed inset-0 z-50 hidden bg-black/60 flex items-center justify-center p-4">
        <div class="bg-[#1A2824] border border-[#4D6B67]/30 rounded-2xl p-6 max-w-sm w-full shadow-2xl transform transition-all scale-95 opacity-0" id="confirmModalCard">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-full bg-brand-red/20 flex items-center justify-center text-brand-red border border-brand-red/30">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h3 class="text-lg font-bold text-white" id="confirmTitle">Konfirmasi</h3>
            </div>
            <p class="text-sm text-white/70 mb-6 leading-relaxed" id="confirmMessage">Apakah Anda yakin?</p>
            <div class="flex justify-end gap-3">
                <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 rounded-xl text-sm font-semibold text-white/70 hover:text-white hover:bg-white/10 transition-colors">Batal</button>
                <button type="button" id="confirmActionBtn" class="px-4 py-2 rounded-xl text-sm font-semibold bg-brand-red text-white hover:bg-red-600 transition-colors shadow-lg shadow-brand-red/20">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    <!-- MODAL QR CODE DEWAN PEMBINA -->
    <?php $pembina_url = base_url('yayasan/rekapitulasi'); ?>
    <div id="qrModal" class="fixed inset-0 z-50 hidden bg-black/80 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-[#152421] border border-amber-500/40 rounded-3xl p-6 sm:p-8 max-w-md w-full shadow-2xl transform transition-all scale-95 opacity-0 relative text-center space-y-6" id="qrModalCard">
            <button onclick="closeQrModal()" class="absolute top-4 right-4 w-8 h-8 rounded-full bg-white/10 text-white/70 hover:text-white flex items-center justify-center transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>

            <div class="space-y-2">
                <span class="px-3 py-1 rounded-full text-[10px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/30 uppercase tracking-wider">
                    QR Code Laporan Dewan Pembina
                </span>
                <h3 class="font-display font-extrabold text-xl text-white">Akses Rekapitulasi Pembina</h3>
                <p class="text-xs text-white/60">Scan QR Code ini menggunakan HP Dewan Pembina untuk membuka laporan rekapitulasi real-time.</p>
            </div>

            <!-- QR Code Render Box -->
            <div class="flex flex-col items-center justify-center bg-white p-6 rounded-2xl shadow-inner border border-amber-500/30 mx-auto w-max">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($pembina_url) ?>" alt="QR Code Dewan Pembina" id="qrCodeImage" class="w-48 h-48 rounded">
            </div>

            <div class="pt-2">
                <button type="button" onclick="downloadQrCodeImage()" id="downloadQrBtn" class="w-full py-3 bg-gradient-to-r from-amber-500 to-amber-600 hover:from-amber-600 hover:to-amber-700 text-teal-950 font-display font-extrabold rounded-xl text-xs flex items-center justify-center gap-2 shadow-lg shadow-amber-500/20 transition-all">
                    <i class="bi bi-download text-sm"></i> <span id="downloadBtnText">Download QR Code</span>
                </button>
            </div>
        </div>
    </div>

    <!-- FLOATING HOVER TOOLTIP CARD FOR RUNDAYAN -->
    <div id="rundayanHoverTooltip" class="fixed z-[12000] hidden bg-[#142623] border border-emerald-500/40 rounded-2xl p-5 shadow-2xl max-w-sm w-80 text-left backdrop-blur-md pointer-events-none transition-all duration-200 opacity-0 transform scale-95">
        <div class="flex items-center justify-between border-b border-emerald-500/20 pb-2 mb-3">
            <h4 class="font-display font-bold text-sm text-emerald-300 flex items-center gap-1.5" id="hover_rundayan_title">
                <i class="bi bi-people-fill"></i> Detail Rundayan
            </h4>
            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30" id="hover_rundayan_votes">
                0 Suara
            </span>
        </div>
        
        <div class="space-y-3 text-xs">
            <div>
                <span class="text-white/40 uppercase tracking-wider font-bold block mb-1">Pengusul / Nominator:</span>
                <p class="text-white font-medium bg-black/30 rounded-xl p-2.5 leading-relaxed border border-white/5" id="hover_rundayan_nominators">-</p>
            </div>
            <div>
                <span class="text-white/40 uppercase tracking-wider font-bold block mb-1">Calon yang Diusulkan:</span>
                <p class="text-amber-300 font-medium bg-black/30 rounded-xl p-2.5 leading-relaxed border border-white/5" id="hover_rundayan_candidates">-</p>
            </div>
        </div>
    </div>

    <!-- JS Scripts -->
    <script>
        // Modal Confirm
        function showConfirm(event, url, message) {
            event.preventDefault();
            const modal = document.getElementById('confirmModal');
            const card = document.getElementById('confirmModalCard');
            document.getElementById('confirmMessage').innerText = message;
            const actionBtn = document.getElementById('confirmActionBtn');
            actionBtn.onclick = function() { window.location.href = url; };
            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
        }
        
        function closeConfirmModal() {
            const modal = document.getElementById('confirmModal');
            const card = document.getElementById('confirmModalCard');
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); }, 200);
        }

        // Modal QR Code Dewan Pembina
        function openQrModal() {
            const modal = document.getElementById('qrModal');
            const card = document.getElementById('qrModalCard');
            modal.classList.remove('hidden');
            setTimeout(() => {
                card.classList.remove('scale-95', 'opacity-0');
                card.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeQrModal() {
            const modal = document.getElementById('qrModal');
            const card = document.getElementById('qrModalCard');
            card.classList.remove('scale-100', 'opacity-100');
            card.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); }, 200);
        }

        function downloadQrCodeImage() {
            const qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=" + encodeURIComponent("<?= $pembina_url ?>");
            const btnText = document.getElementById('downloadBtnText');
            if (btnText) btnText.innerText = 'Mengunduh...';

            fetch(qrUrl)
                .then(res => res.blob())
                .then(blob => {
                    const blobUrl = URL.createObjectURL(blob);
                    const link = document.createElement('a');
                    link.href = blobUrl;
                    link.download = 'QR_Code_Dewan_Pembina.png';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                    URL.revokeObjectURL(blobUrl);
                    if (btnText) btnText.innerText = 'Download QR Code';
                })
                .catch(() => {
                    const img = new Image();
                    img.crossOrigin = 'anonymous';
                    img.onload = function() {
                        const canvas = document.createElement('canvas');
                        canvas.width = img.width;
                        canvas.height = img.height;
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(img, 0, 0);
                        const link = document.createElement('a');
                        link.href = canvas.toDataURL('image/png');
                        link.download = 'QR_Code_Dewan_Pembina.png';
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                        if (btnText) btnText.innerText = 'Download QR Code';
                    };
                    img.src = qrUrl;
                });
        }

        // Chart 3D Pie Script
        const chartDataIndividu = <?= json_encode($chart_data_individu ?? []) ?>;
        const chartDataRundayan = <?= json_encode($chart_data_rundayan ?? []) ?>;
        const rundayanDetailMap = <?= json_encode($rundayan_detail_map ?? []) ?>;

        let highChartInstance = null;

        function getDistinctColorsForData(dataSeries) {
            const presetPalette = [
                '#F59E0B', // Bright Amber
                '#06B6D4', // Vibrant Cyan
                '#EF4444', // Vivid Red
                '#10B981', // Emerald Green
                '#8B5CF6', // Electric Purple
                '#EC4899', // Hot Pink
                '#3B82F6', // Royal Blue
                '#84CC16', // Lime Green
                '#F97316', // Neon Orange
                '#14B8A6', // Deep Teal
                '#A855F7', // Vivid Violet
                '#EAB308', // Gold Yellow
                '#6366F1', // Indigo
                '#0284C7', // Sky Blue
                '#D97706', // Deep Amber
                '#059669', // Mint
                '#C026D3', // Magenta
                '#DC2626', // Crimson
                '#2563EB', // Cobalt Blue
                '#4D7C0F', // Olive Green
                '#B45309', // Rust Orange
                '#4F46E5', // Deep Indigo
                '#0891B2', // Ocean Blue
                '#DB2777'  // Rose Pink
            ];

            const count = dataSeries ? dataSeries.length : 0;
            if (count <= presetPalette.length) {
                return presetPalette.slice(0, Math.max(count, 1));
            }

            const colors = [];
            for (let i = 0; i < count; i++) {
                const hue = Math.round((i * 137.508) % 360);
                const saturation = 85 + (i % 3) * 5;
                const lightness = 50 + (i % 4) * 4;
                colors.push(`hsl(${hue}, ${saturation}%, ${lightness}%)`);
            }
            return colors;
        }

        function render3DPieChart(dataSeries, titleText) {
            highChartInstance = Highcharts.chart('container_chart_3d', {
                chart: {
                    type: 'pie',
                    options3d: {
                        enabled: true,
                        alpha: 45,
                        beta: 0
                    },
                    backgroundColor: 'transparent'
                },
                title: {
                    text: titleText,
                    style: {
                        color: '#FFFFFF',
                        fontFamily: 'Plus Jakarta Sans',
                        fontWeight: '700',
                        fontSize: '16px'
                    }
                },
                subtitle: {
                    text: 'Arahkan kursor ke grafik untuk melihat rincian pemilih & pengusul',
                    style: { color: 'rgba(255,255,255,0.7)', fontSize: '11px' }
                },
                legend: {
                    enabled: true,
                    itemStyle: {
                        color: '#FFFFFF',
                        fontWeight: '600',
                        fontSize: '12px'
                    },
                    itemHoverStyle: {
                        color: '#D4B571'
                    }
                },
                tooltip: {
                    useHTML: true,
                    backgroundColor: '#152421',
                    borderColor: '#4D6B67',
                    borderRadius: 12,
                    style: { color: '#FFFFFF', fontFamily: 'Inter', fontSize: '12px' },
                    formatter: function() {
                        const p = this.point;
                        let html = `<div style="padding: 4px 6px;">`;
                        html += `<div style="font-weight: 800; font-size: 14px; color: #D4B571; margin-bottom: 4px;">${p.name}</div>`;
                        html += `<div><b>Total Suara:</b> <span style="color:#10b981;">${p.y} Suara (${p.percentage.toFixed(1)}%)</span></div>`;
                        if (p.nominators) {
                            html += `<div style="margin-top: 4px;"><b>Pengusul / Pemilih:</b> <span style="color:#e2e8f0;">${p.nominators}</span></div>`;
                        }
                        if (p.breakdown) {
                            html += `<div style="margin-top: 2px;"><b>Rincian:</b> <span style="color:#38bdf8;">${p.breakdown}</span></div>`;
                        }
                        html += `</div>`;
                        return html;
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        depth: 35,
                        showInLegend: true,
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b><br>{point.y} suara ({point.percentage:.1f}%)',
                            style: {
                                color: '#FFFFFF',
                                textOutline: '2px #000000',
                                fontFamily: 'Inter',
                                fontSize: '12px',
                                fontWeight: '700'
                            }
                        }
                    }
                },
                colors: getDistinctColorsForData(dataSeries),
                series: [{
                    name: 'Dukungan',
                    data: dataSeries
                }]
            });
        }

        function switchChart(type) {
            const btnIndividu = document.getElementById('btn_chart_individu');
            const btnRundayan = document.getElementById('btn_chart_rundayan');

            if (type === 'individu') {
                btnIndividu.className = "px-4 py-1.5 rounded-lg font-bold transition-all bg-emerald-500 text-white shadow";
                btnRundayan.className = "px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all";
                render3DPieChart(chartDataIndividu, 'Perolehan Suara Kandidat Individu');
            } else {
                btnRundayan.className = "px-4 py-1.5 rounded-lg font-bold transition-all bg-cyan-500 text-white shadow";
                btnIndividu.className = "px-4 py-1.5 rounded-lg font-bold text-white/60 hover:text-white transition-all";
                render3DPieChart(chartDataRundayan, 'Perolehan Suara Kandidat Rundayan');
            }
        }

        // HOVER RUNDAYAN TOOLTIP INTERACTION
        function showRundayanHover(e, ancName) {
            const tooltip = document.getElementById('rundayanHoverTooltip');
            const detail = rundayanDetailMap[ancName];

            document.getElementById('hover_rundayan_title').innerHTML = `<i class="bi bi-people-fill"></i> Rundayan: ${ancName}`;
            
            if (detail) {
                document.getElementById('hover_rundayan_votes').innerText = `${detail.total_votes} Suara`;
                document.getElementById('hover_rundayan_nominators').innerText = detail.nominators.join(', ') || '-';
                document.getElementById('hover_rundayan_candidates').innerText = detail.candidates.join(', ') || '-';
            } else {
                document.getElementById('hover_rundayan_votes').innerText = `0 Suara`;
                document.getElementById('hover_rundayan_nominators').innerText = `-`;
                document.getElementById('hover_rundayan_candidates').innerText = `-`;
            }

            let x = e.clientX + 15;
            let y = e.clientY + 15;

            if (x + 320 > window.innerWidth) x = e.clientX - 330;
            if (y + 200 > window.innerHeight) y = e.clientY - 210;

            tooltip.style.left = `${x}px`;
            tooltip.style.top = `${y}px`;

            tooltip.classList.remove('hidden');
            setTimeout(() => {
                tooltip.classList.remove('opacity-0', 'scale-95');
                tooltip.classList.add('opacity-100', 'scale-100');
            }, 10);
        }

        function hideRundayanHover() {
            const tooltip = document.getElementById('rundayanHoverTooltip');
            tooltip.classList.remove('opacity-100', 'scale-100');
            tooltip.classList.add('opacity-0', 'scale-95');
            setTimeout(() => { tooltip.classList.add('hidden'); }, 150);
        }

        // Auto Complete Suggestions
        const namesData = <?= json_encode($all_names ?? []) ?>;

        function setupSuggestions(inputId, boxId, dataArray) {
            const input = document.getElementById(inputId);
            const box = document.getElementById(boxId);
            if (!input || !box) return;

            input.addEventListener('input', () => {
                const val = input.value.trim().toLowerCase();
                box.innerHTML = '';
                if (!val) {
                    box.classList.add('hidden');
                    return;
                }

                const matched = dataArray.filter(name => name.toLowerCase().includes(val)).slice(0, 5);
                if (matched.length === 0) {
                    box.classList.add('hidden');
                    return;
                }

                matched.forEach(name => {
                    const initial = name.charAt(0).toUpperCase();
                    const itemHtml = `
                        <div onclick="selectSuggestion('${inputId}', '${boxId}', \`${name.replace(/'/g, "\\'")}\`)" class="px-4 py-2.5 hover:bg-[#2c3f3a] cursor-pointer flex items-center gap-3 transition-colors text-left border-b border-[#4D6B67]/10">
                            <div class="w-6 h-6 rounded-full bg-brand-medium/20 text-brand-medium flex items-center justify-center font-bold text-xs shrink-0">
                                ${initial}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-white truncate">${name}</p>
                            </div>
                        </div>
                    `;
                    box.innerHTML += itemHtml;
                });
                box.classList.remove('hidden');
            });

            document.addEventListener('click', (e) => {
                if (!input.contains(e.target) && !box.contains(e.target)) {
                    box.classList.add('hidden');
                }
            });
        }

        function selectSuggestion(inputId, boxId, value) {
            const input = document.getElementById(inputId);
            input.value = value;
            document.getElementById(boxId).classList.add('hidden');
            input.dispatchEvent(new Event('input'));
        }

        document.addEventListener('DOMContentLoaded', () => {
            render3DPieChart(chartDataIndividu, 'Perolehan Suara Kandidat Individu');

            setupSuggestions('input_search_main', 'search_main_suggestions_box', namesData);
            setupSuggestions('input_search_individu', 'search_individu_suggestions_box', namesData);
            setupSuggestions('input_search_rundayan', 'search_rundayan_suggestions_box', namesData);
            setupSuggestions('input_search_bagan', 'search_bagan_suggestions_box', namesData);

            // Live filters
            const mainInput = document.getElementById('input_search_main');
            if (mainInput) {
                mainInput.addEventListener('input', function() {
                    const val = this.value.trim().toLowerCase();
                    const rows = document.querySelectorAll('#table_main tbody tr');
                    rows.forEach(row => {
                        if (row.cells.length < 2) return;
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.includes(val) ? '' : 'none';
                    });
                });
            }

            const individuInput = document.getElementById('input_search_individu');
            if (individuInput) {
                individuInput.addEventListener('input', function() {
                    const val = this.value.trim().toLowerCase();
                    const rows = document.querySelectorAll('#table_individu tbody tr');
                    rows.forEach(row => {
                        if (row.cells.length < 2) return;
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.includes(val) ? '' : 'none';
                    });
                });
            }

            const rundayanInput = document.getElementById('input_search_rundayan');
            if (rundayanInput) {
                rundayanInput.addEventListener('input', function() {
                    const val = this.value.trim().toLowerCase();
                    const rows = document.querySelectorAll('#table_rundayan tbody tr');
                    rows.forEach(row => {
                        if (row.cells.length < 2) return;
                        const text = row.innerText.toLowerCase();
                        row.style.display = text.includes(val) ? '' : 'none';
                    });
                });
            }

            const baganInput = document.getElementById('input_search_bagan');
            if (baganInput) {
                baganInput.addEventListener('input', function() {
                    const val = this.value.trim().toLowerCase();
                    const cards = document.querySelectorAll('.bagan-ancestor-card');
                    cards.forEach(card => {
                        const text = card.innerText.toLowerCase();
                        card.style.display = text.includes(val) ? '' : 'none';
                    });
                });
            }
        });
    </script>
</body>
</html>
