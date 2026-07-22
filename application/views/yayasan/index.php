<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
// Define page type and theme colors
$page_type = $page_type ?? 'individu';
$is_rundayan = ($page_type === 'rundayan');
$theme_primary = $is_rundayan ? 'cyan' : 'amber';
$theme_text_primary = $is_rundayan ? 'text-cyan-400' : 'text-amber-400';
$theme_text_bright = $is_rundayan ? 'text-cyan-300' : 'text-amber-300';
$theme_border_hover = $is_rundayan ? 'hover:border-cyan-400/40' : 'hover:border-amber-400/40';
$theme_bg_glow = $is_rundayan ? 'bg-cyan-500/5 group-hover:bg-cyan-500/10' : 'bg-amber-500/5 group-hover:bg-amber-500/10';
$theme_shadow_hover = $is_rundayan ? 'hover:shadow-[0_10px_30px_rgba(6,182,212,0.3)]' : 'hover:shadow-[0_10px_30px_rgba(245,158,11,0.3)]';
$theme_bg = $is_rundayan ? 'bg-cyan-500' : 'bg-amber-500';
$theme_bg_hover = $is_rundayan ? 'hover:bg-cyan-600' : 'hover:bg-amber-600';
$theme_dark_text = $is_rundayan ? 'text-slate-950' : 'text-teal-950';

$section_title = $is_rundayan ? 'Pencalonan Rundayan' : 'Pencalonan Individu';
$section_icon = $is_rundayan ? 'bi-people-fill' : 'bi-person-fill';
$form_action_url = $is_rundayan ? base_url('rundayan/nominate') : base_url('anggota/nominate');
$search_action_url = $is_rundayan ? base_url('rundayan') : base_url('anggota');
$detail_base_url = $is_rundayan ? 'rundayan/detail/' : 'anggota/detail/';

// Group candidates by ancestor for the chart silsilah tab
$grouped_candidates = [];
foreach ($candidates as $c) {
    $grouped_candidates[$c['ancestor_name']][] = $c;
}

// Tree builder helper logic
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
        
        // Resolve theme dynamically inside function
        $page_type = isset($GLOBALS['page_type']) ? $GLOBALS['page_type'] : 'individu';
        $is_rundayan = ($page_type === 'rundayan');
        $theme_primary = $is_rundayan ? 'cyan' : 'amber';
        $detail_base_url = $is_rundayan ? 'rundayan/detail/' : 'anggota/detail/';
        ?>
        <div class="flex flex-col gap-3">
            <!-- Candidate Node Card -->
            <div class="flex items-center gap-3">
                <div class="w-3 h-0.5 bg-<?= $theme_primary ?>-500/50"></div>
                <div class="bg-gradient-to-r from-<?= $theme_primary ?>-500/10 to-<?= $theme_primary ?>-500/0 hover:from-<?= $theme_primary ?>-500/20 border border-<?= $theme_primary ?>-500/25 rounded-2xl px-5 py-3 flex items-center justify-between gap-6 transition-all duration-300">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-<?= $theme_primary ?>-400 tracking-wider block mb-0.5">Kandidat Ketua</span>
                        <strong class="text-white text-base font-semibold"><?= htmlspecialchars($cand['candidate_name']) ?></strong>
                        <?php if (!empty($cand['description'])): ?>
                            <span class="text-xs text-white/55 block mt-1 line-clamp-1 max-w-sm"><?= htmlspecialchars($cand['description']) ?></span>
                        <?php endif; ?>
                    </div>
                    <a href="<?= base_url($detail_base_url.$cand['id']) ?>" class="w-8 h-8 rounded-lg bg-white/5 hover:bg-white/15 text-white flex items-center justify-center transition-all" title="Lihat Detail & QR">
                        <i class="bi bi-arrow-right-short text-xl"></i>
                    </a>
                </div>
            </div>
            
            <!-- Recursive Children -->
            <?php if ($has_children): ?>
                <div class="flex flex-col gap-3 pl-8 border-l border-<?= $theme_primary ?>-500/25 ml-[26px] pt-1">
                    <?php foreach ($children[$cand_key] as $child): ?>
                        <?php render_tree_node($child, $children); ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
}

if (!function_exists('format_nominators')) {
    function format_nominators($noms) {
        $page_type = isset($GLOBALS['page_type']) ? $GLOBALS['page_type'] : 'individu';
        $theme_primary = $page_type === 'rundayan' ? 'cyan' : 'amber';
        if (!is_array($noms)) {
            $noms = array_filter(array_map('trim', explode(',', $noms)));
        }
        if (count($noms) <= 3) {
            return htmlspecialchars(implode(', ', $noms));
        } else {
            return htmlspecialchars(implode(', ', array_slice($noms, 0, 3))) . ' <span class="text-' . $theme_primary . '-400 font-semibold">dan ' . (count($noms) - 3) . ' lainnya</span>';
        }
    }
}
?>

<main class="min-h-screen bg-gradient-to-b from-[#274d4f] via-[#1a3638] to-[#0f2122] text-white pt-32 sm:pt-36 pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Hero Section / Title -->
        <div class="text-center mb-12 animate-fade-in">
            <span class="px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-<?= $theme_primary ?>-500/20 text-<?= $theme_primary ?>-400 border border-<?= $theme_primary ?>-500/30"><?= $section_title ?></span>
            <h1 class="mt-4 text-4xl sm:text-5xl font-display font-bold text-transparent bg-clip-text bg-gradient-to-r from-<?= $theme_primary ?>-200 via-<?= $theme_primary ?>-400 to-<?= $theme_primary ?>-200 tracking-tight">
                Pencalonan Ketua Yayasan (<?= $is_rundayan ? 'Rundayan' : 'Individu' ?>)
            </h1>
            <p class="mt-3 text-lg text-emerald-100/80 max-w-2xl mx-auto">
                Lihat daftar nama kandidat, siapa yang mencalonkannya, alur pencalonan kandidat, dan bagikan informasi calon.
            </p>
            <?php if ($is_rundayan): ?>
                <p class="mt-4 text-xs sm:text-sm text-cyan-300 max-w-2xl mx-auto font-medium leading-relaxed bg-cyan-950/40 border border-cyan-500/20 px-5 py-3.5 rounded-2xl flex items-start gap-2.5 shadow-sm text-left sm:text-center">
                    <i class="bi bi-info-circle-fill text-cyan-400 text-base shrink-0 mt-0.5 sm:mt-0"></i>
                    <span>Pengusulan rundayan hanya 1 paket (3 nama) untuk setiap rundayan / keluarga besar gen 2. Sehingga diperlukan kesepakatan dari keluarga tersebut untuk memunculkan 3 nama.</span>
                </p>
            <?php endif; ?>
        </div>

        <!-- Success/Error Alert messages -->
        <?php if ($this->session->flashdata('success')): ?>
            <div class="mb-8 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/30 text-emerald-200 flex items-center gap-3 animate-slide-in">
                <i class="bi bi-check-circle-fill text-xl text-emerald-400"></i>
                <span><?= $this->session->flashdata('success') ?></span>
            </div>
        <?php endif; ?>

        <?php if ($this->session->flashdata('error')): ?>
            <div class="mb-8 p-4 rounded-xl bg-red-500/20 border border-red-500/30 text-red-200 flex items-center gap-3 animate-slide-in">
                <i class="bi bi-exclamation-triangle-fill text-xl text-red-400"></i>
                <span><?= $this->session->flashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <!-- Control Bar (Search & Nominate Buttons) -->
        <div class="flex flex-col lg:flex-row gap-4 justify-between items-center mb-8 bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10">
            <form action="<?= $search_action_url ?>" method="GET" class="w-full lg:max-w-md relative">
                <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Cari nama calon, pencalon, atau buyut..." 
                       class="w-full pl-11 pr-4 py-3 bg-[#112426] border border-white/10 rounded-xl focus:border-<?= $theme_primary ?>-400 focus:ring-1 focus:ring-<?= $theme_primary ?>-400 transition-all text-white placeholder-white/40">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-white/40"></i>
            </form>

            <div class="flex flex-col sm:flex-row gap-3 w-full lg:w-auto">
                <button onclick="openNominateModal('<?= $page_type ?>')" 
                        class="px-5 py-3 bg-gradient-to-r from-<?= $theme_primary ?>-500 to-<?= $theme_primary ?>-600 hover:from-<?= $theme_primary ?>-600 hover:to-<?= $theme_primary ?>-700 font-bold rounded-xl transition-all shadow-[0_4px_15px_rgba(<?= $is_rundayan ? '6,182,212' : '245,158,11' ?>,0.2)] hover:-translate-y-0.5 active:translate-y-0 flex items-center justify-center gap-2 text-xs sm:text-sm <?= $theme_dark_text ?>">
                    <i class="<?= $is_rundayan ? 'bi bi-people-fill' : 'bi bi-person-plus-fill' ?>"></i>
                    Usulkan Calon <?= $is_rundayan ? 'Rundayan' : 'Individu' ?>
                </button>
            </div>
        </div>

        <!-- View Tabs -->
        <div class="flex border-b border-white/10 mb-8 w-full">
            <button onclick="switchTab('grid')" id="tabBtn-grid" class="tab-btn flex-1 pb-3 font-semibold text-xs sm:text-sm border-b-2 border-<?= $theme_primary ?>-500 text-<?= $theme_primary ?>-300 transition-all flex items-center justify-center gap-1.5">
                <i class="bi bi-grid-fill"></i> Kartu Calon
            </button>
            <?php if ($is_authorized): ?>
                <button onclick="switchTab('table')" id="tabBtn-table" class="tab-btn flex-1 pb-3 font-semibold text-xs sm:text-sm border-b-2 border-transparent text-white/60 hover:text-white transition-all flex items-center justify-center gap-1.5">
                    <i class="bi bi-table"></i> Tabel Data
                </button>
            <?php endif; ?>
            <button onclick="switchTab('chart')" id="tabBtn-chart" class="tab-btn flex-1 pb-3 font-semibold text-xs sm:text-sm border-b-2 border-transparent text-white/60 hover:text-white transition-all flex items-center justify-center gap-1.5">
                <i class="bi bi-diagram-3-fill"></i> Bagan Pencalonan
            </button>
        </div>
 
        <!-- Candidate Grid -->
        <?php if (empty($candidates)): ?>
            <div class="text-center py-20 bg-white/5 border border-white/10 rounded-3xl backdrop-blur-sm">
                <div class="w-20 h-20 mx-auto rounded-2xl bg-white/10 flex items-center justify-center text-white/50 text-4xl mb-4">
                    <i class="bi bi-person-x-fill"></i>
                </div>
                <h3 class="text-xl font-bold">Belum Ada Calon</h3>
                <p class="text-white/60 mt-1 max-w-sm mx-auto">Silakan daftarkan calon ketua baru yang menurut Anda layak memimpin yayasan.</p>
                <div class="flex flex-wrap justify-center gap-3 mt-5">
                    <button onclick="openNominateModal('<?= $page_type ?>')" class="px-5 py-2.5 bg-<?= $theme_primary ?>-500 hover:bg-<?= $theme_primary ?>-600 font-bold text-sm rounded-xl transition-all <?= $theme_dark_text ?>">
                        Usulkan Calon <?= $is_rundayan ? 'Rundayan' : 'Individu' ?>
                    </button>
                </div>
            </div>
        <?php else: ?>
            <!-- Tab Contents -->
            <!-- 1. GRID TAB -->
            <div id="tabContent-grid" class="tab-content animate-fade-in space-y-12">
                <div>
                    <h2 class="text-xl font-bold text-<?= $theme_primary ?>-400 mb-6 flex items-center gap-2">
                        <i class="bi <?= $section_icon ?>"></i> <?= $section_title ?>
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($candidates as $index => $c): ?>
                            <div class="bg-gradient-to-br from-<?= $is_rundayan ? '[#112d30] to-[#0c1f21]' : '[#1b3638] to-[#122829]' ?> border border-<?= $is_rundayan ? 'cyan-500/20' : 'white/10' ?> rounded-3xl p-6 relative overflow-hidden group hover:border-<?= $theme_primary ?>-400/40 transition-all duration-300 <?= $theme_shadow_hover ?> flex flex-col justify-between">
                                <div class="absolute -right-16 -top-16 w-36 h-36 bg-<?= $theme_primary ?>-500/5 rounded-full blur-2xl group-hover:bg-<?= $theme_primary ?>-500/10 transition-all pointer-events-none"></div>
                                
                                <div>
                                    <div class="flex justify-between items-start mb-4">
                                        <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-<?= $theme_primary ?>-500/10 text-<?= $theme_primary ?>-400 border border-<?= $theme_primary ?>-500/20">
                                            No. Urut #<?= $index + 1 ?>
                                        </span>
                                        <div class="flex items-center gap-2">
                                            <button onclick="copyNomineeLink(<?= $c['id'] ?>)" 
                                                    class="w-9 h-9 inline-flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/15 text-white/80 hover:text-white transition-all" title="Salin Tautan">
                                                <i class="bi bi-link-45deg text-lg"></i>
                                            </button>
                                            <button onclick="showQRModal(<?= $c['id'] ?>, '<?= htmlspecialchars(addslashes($c['candidate_name'])) ?>')" 
                                                    class="w-9 h-9 inline-flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/15 text-white/80 hover:text-white transition-all" title="QR Code">
                                                <i class="bi bi-qr-code text-sm"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <h3 class="text-2xl font-bold font-display text-white tracking-tight leading-tight mb-2 group-hover:text-<?= $theme_primary ?>-300 transition-colors">
                                        <?= htmlspecialchars($c['candidate_name']) ?>
                                    </h3>
                                    
                                    <div class="space-y-2 mt-4 text-sm text-white/70">
                                        <div class="flex items-center gap-2.5">
                                            <i class="bi bi-person-badge text-<?= $theme_primary ?>-400"></i>
                                            <span>Pencalon: <strong class="text-white"><?= format_nominators($c['nominator_name']) ?></strong></span>
                                        </div>
                                        <div class="flex items-center gap-2.5">
                                            <i class="bi bi-people-fill text-<?= $theme_primary ?>-400"></i>
                                            <span>Rundayan/Buyut: <strong class="text-white"><?= htmlspecialchars($c['ancestor_name']) ?></strong></span>
                                        </div>
                                        <?php if ($is_authorized): ?>
                                            <div class="flex items-center gap-2.5">
                                                <i class="bi bi-check2-circle text-emerald-400"></i>
                                                <span>Dukungan: <strong class="text-emerald-400"><?= $c['votes_count'] ?> suara</strong></span>
                                            </div>
                                            <div class="text-[11px] text-emerald-400/80 mt-1 border-t border-white/5 pt-2">
                                                Rincian: <?= $c['breakdown_text'] ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div class="mt-8 pt-4 border-t border-white/5 flex justify-end">
                                    <a href="<?= base_url($detail_base_url.$c['id']) ?>" 
                                       class="px-5 py-2 rounded-xl text-xs font-bold bg-<?= $theme_primary ?>-500 hover:bg-<?= $theme_primary ?>-600 <?= $theme_dark_text ?> shadow-md hover:-translate-y-0.5 transition-all text-center">
                                        Detail Yayasan
                                    </a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <!-- 2. TABLE TAB -->
            <?php if ($is_authorized): ?>
                <div id="tabContent-table" class="tab-content hidden animate-fade-in space-y-12">
                    <div class="bg-gradient-to-b from-<?= $is_rundayan ? '[#112d30] to-[#0c1f21]' : '[#1b3638] to-[#122829]' ?> border border-<?= $is_rundayan ? 'cyan-500/20' : 'white/10' ?> rounded-3xl p-6 overflow-hidden shadow-xl">
                        <h3 class="text-lg font-bold text-<?= $theme_primary ?>-300 mb-4 flex items-center gap-2">
                            <i class="bi <?= $section_icon ?>"></i> Tabel <?= $section_title ?>
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse" style="min-width: 900px;">
                                <thead>
                                    <tr class="border-b border-<?= $is_rundayan ? 'cyan-500/10' : 'white/10' ?>">
                                        <th class="pb-4 pr-6 text-xs font-bold text-<?= $theme_primary ?>-300 uppercase tracking-wider whitespace-nowrap">No. Urut</th>
                                        <th class="pb-4 pr-6 text-xs font-bold text-<?= $theme_primary ?>-300 uppercase tracking-wider whitespace-nowrap">Nama Calon</th>
                                        <th class="pb-4 pr-6 text-xs font-bold text-<?= $theme_primary ?>-300 uppercase tracking-wider whitespace-nowrap">Sebagai Calon</th>
                                        <th class="pb-4 pr-6 text-xs font-bold text-<?= $theme_primary ?>-300 uppercase tracking-wider whitespace-nowrap">Pencalon / Nominator</th>
                                        <th class="pb-4 pr-6 text-xs font-bold text-<?= $theme_primary ?>-300 uppercase tracking-wider whitespace-nowrap">Rundayan / Buyut</th>
                                        <th class="pb-4 pr-6 text-xs font-bold text-<?= $theme_primary ?>-300 uppercase tracking-wider whitespace-nowrap">Jumlah Dukungan</th>
                                        <th class="pb-4 pr-6 text-xs font-bold text-<?= $theme_primary ?>-300 uppercase tracking-wider whitespace-nowrap">Rincian Pemilih</th>
                                        <th class="pb-4 text-xs font-bold text-<?= $theme_primary ?>-300 uppercase tracking-wider text-right whitespace-nowrap">Detail</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    <?php foreach ($candidates as $index => $c): ?>
                                        <tr>
                                            <td class="py-4 pr-6 text-sm text-white/55 whitespace-nowrap">#<?= $index + 1 ?></td>
                                            <td class="py-4 pr-6 text-sm font-bold text-white whitespace-nowrap"><?= htmlspecialchars($c['candidate_name']) ?></td>
                                            <td class="py-4 pr-6 text-sm text-white/80 whitespace-nowrap">
                                                <span class="px-2 py-0.5 rounded text-xs bg-<?= $theme_primary ?>-500/10 text-<?= $theme_primary ?>-300 border border-<?= $theme_primary ?>-500/20">
                                                    <?= htmlspecialchars($c['roles_text']) ?>
                                                </span>
                                            </td>
                                            <td class="py-4 pr-6 text-sm text-white/80 whitespace-nowrap"><?= format_nominators($c['nominator_name']) ?></td>
                                            <td class="py-4 pr-6 text-sm text-white/80 whitespace-nowrap"><?= htmlspecialchars($c['ancestor_name']) ?></td>
                                            <td class="py-4 pr-6 text-sm text-emerald-400 font-bold whitespace-nowrap"><?= $c['votes_count'] ?> suara</td>
                                            <td class="py-4 pr-6 text-xs text-white/60 whitespace-nowrap"><?= $c['breakdown_text'] ?></td>
                                            <td class="py-4 text-right whitespace-nowrap">
                                                <a href="<?= base_url($detail_base_url.$c['id']) ?>" class="inline-flex items-center gap-1 text-xs text-<?= $theme_primary ?>-400 hover:text-<?= $theme_primary ?>-300 font-semibold transition-colors">
                                                    Lihat <i class="bi bi-chevron-right text-[10px]"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- 3. CHART TAB -->
            <div id="tabContent-chart" class="tab-content hidden animate-fade-in">
                <div class="space-y-8">
                    <?php 
                        $raw_grouped_ancestor = [];
                        foreach ($candidates as $c) {
                            $raw_grouped_ancestor[$c['ancestor_name']][] = $c;
                        }
                    ?>
                    <?php foreach ($raw_grouped_ancestor as $ancestor => $cand_list): ?>
                        <div class="bg-gradient-to-br from-<?= $is_rundayan ? '[#112d30] to-[#0c1f21]' : '[#1b3638] to-[#122829]' ?> border border-<?= $is_rundayan ? 'cyan-500/20' : 'white/10' ?> rounded-3xl p-6 shadow-xl">
                            <h3 class="text-xl font-bold text-<?= $theme_primary ?>-300 border-b border-white/5 pb-3 mb-6 flex items-center gap-2">
                                <i class="bi bi-people-fill"></i> Rundayan: <?= htmlspecialchars($ancestor) ?>
                            </h3>
                            
                            <?php 
                                $tree_data = build_nomination_trees($cand_list); 
                                $roots = $tree_data['roots'];
                                $children = $tree_data['children'];
                            ?>
                            
                            <div class="overflow-x-auto pb-4">
                                <div class="inline-flex flex-col gap-6 min-w-full">
                                    <?php foreach ($roots as $nominator => $root_cands): ?>
                                        <div class="flex flex-col gap-4 pl-4 border-l-2 border-<?= $theme_primary ?>-500/30">
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
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Nominate Candidate Modal -->
    <div id="nominateModal" class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm opacity-0 pointer-events-none transition-opacity duration-300">
        <div class="w-full max-w-md">
            <div id="nominateModalBox" class="bg-gradient-to-b from-[#1b3638] to-[#122829] border border-white/15 rounded-2xl w-full shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] flex flex-col overflow-hidden">
                <!-- Modal Header -->
                <div class="px-6 py-4 border-b border-white/10 flex justify-between items-center shrink-0">
                    <div>
                        <h3 class="text-base font-bold text-amber-300" id="modalTitle">Calonkan Ketua Yayasan</h3>
                        <p class="text-white/50 text-xs mt-0.5">Isi data berikut secara bertahap</p>
                    </div>
                    <button onclick="toggleNominateModal(false)" class="w-8 h-8 flex items-center justify-center rounded-lg bg-white/5 hover:bg-white/15 text-white/60 hover:text-white transition-all text-lg font-bold">&times;</button>
                </div>

                <!-- Step Indicator -->
                <div class="px-6 pt-5 flex items-center justify-center gap-2 shrink-0">
                    <div id="indicator-step1" class="flex items-center gap-1.5 text-<?= $theme_primary ?>-300">
                        <span id="indicator-step1-circle" class="w-5 h-5 rounded-full bg-<?= $theme_primary ?>-500 <?= $theme_dark_text ?> text-[10px] font-bold flex items-center justify-center">1</span>
                        <span class="text-[11px] font-bold">Data Pemilih</span>
                    </div>
                    <div class="w-8 h-0.5 bg-white/10" id="indicator-line"></div>
                    <div id="indicator-step2" class="flex items-center gap-1.5 text-white/40">
                        <span id="indicator-step2-circle" class="w-5 h-5 rounded-full bg-white/10 text-white/50 text-[10px] font-bold flex items-center justify-center">2</span>
                        <span class="text-[11px] font-bold">Data Calon Formatur</span>
                    </div>
                </div>
                
                <!-- Modal Form -->
                <form id="multiStepNominateForm" action="<?= $form_action_url ?>" method="POST" autocomplete="off" class="flex flex-col overflow-hidden">
                    <input type="hidden" name="type" id="input_nominate_type" value="<?= $page_type ?>">
                    
                    <!-- Scrollable Steps Content Area -->
                    <div class="overflow-y-auto max-h-[45vh] sm:max-h-[55vh]">
                        <!-- Step 1 Content -->
                        <div id="nominateStep-1" class="px-6 py-5 space-y-4">
                            <div class="relative">
                                <label class="block text-xs font-semibold text-<?= $theme_primary ?>-400/80 uppercase tracking-wider mb-2">Nama Pemilih</label>
                                <input type="text" name="nominator_name" id="input_nominator_name" required placeholder="Contoh: Budi Samhudi" autocomplete="off"
                                       class="w-full px-4 py-2.5 bg-black/20 border border-white/10 rounded-xl focus:outline-none transition-all text-white text-sm placeholder-white/30">
                                <div id="nominator_suggestions_box" class="absolute left-0 right-0 top-full mt-1 bg-[#1b3638] border border-white/15 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden z-[11000] divide-y divide-white/5"></div>
                            </div>
 
                            <div class="relative">
                                <label class="block text-xs font-semibold text-<?= $theme_primary ?>-400/80 uppercase tracking-wider mb-2">Rundayan / Buyut (Keturunan)</label>
                                <select name="ancestor_name" id="input_ancestor_name" required
                                        class="w-full px-4 py-2.5 bg-black/20 border border-white/10 rounded-xl focus:outline-none transition-all text-white text-sm placeholder-white/30 [&>option]:bg-[#1b3638]">
                                    <option value="" disabled selected class="text-white/40">-- Pilih Rundayan / Buyut --</option>
                                    <option value="HIDAYAT SAMHUDI">HIDAYAT SAMHUDI</option>
                                    <option value="HM. SALEH SAMHUDI">HM. SALEH SAMHUDI</option>
                                    <option value="Hj SA'ADIAH SAMHUDI">Hj SA'ADIAH SAMHUDI</option>
                                    <option value="H. AMIDIN SAMHUDI">H. AMIDIN SAMHUDI</option>
                                    <option value="BUSTOMI (TOMI) SAMHUDI">BUSTOMI (TOMI) SAMHUDI</option>
                                    <option value="ABDUL FATAH (UTUN) SAMHUDI">ABDUL FATAH (UTUN) SAMHUDI</option>
                                    <option value="Hj DJUMENAH (CUCU) SAMHUDI">Hj DJUMENAH (CUCU) SAMHUDI</option>
                                    <option value="Hj NANI SOMARNI (ENAN) SAMHUDI">Hj NANI SOMARNI (ENAN) SAMHUDI</option>
                                    <option value="Hj MARIAM (MARI) SAMHUDI">Hj MARIAM (MARI) SAMHUDI</option>
                                    <option value="H. ABDUL HAMID (ACEP) SAMHUDI">H. ABDUL HAMID (ACEP) SAMHUDI</option>
                                    <!-- 
                                    <option value="SUPRAPTI SAMHUDI (TUTI)">SUPRAPTI SAMHUDI (TUTI)</option>
                                    <option value="KARTINI SAMHUDI (TINTIN)">KARTINI SAMHUDI (TINTIN)</option>
                                    <option value="LUKMAN SAMHUDI">LUKMAN SAMHUDI</option>
                                    <option value="KAMIL SAMHUDI">KAMIL SAMHUDI</option>
                                    <option value="KARDINAH SAMHUDI">KARDINAH SAMHUDI</option>
                                    -->
                                </select>
                            </div>
                        </div>
 
                        <!-- Step 2 Content (Hidden by default) -->
                        <div id="nominateStep-2" class="px-6 py-5 space-y-4 hidden pb-12">
                            <div class="relative">
                                <label class="block text-xs font-semibold text-<?= $theme_primary ?>-400/80 uppercase tracking-wider mb-2">Nama Calon Ketua (Formatur 1) <span class="text-red-400">*</span></label>
                                <input type="text" name="candidate_name_1" id="input_candidate_name_1" required placeholder="Contoh: H. Bahrudin" autocomplete="off"
                                       class="w-full px-4 py-2.5 bg-black/20 border border-white/10 rounded-xl focus:outline-none transition-all text-white text-sm placeholder-white/30">
                                <div id="candidate_suggestions_box_1" class="absolute left-0 right-0 top-full mt-1 bg-[#1b3638] border border-white/15 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden z-[11000] divide-y divide-white/5"></div>
                            </div>
 
                            <div class="relative">
                                <label class="block text-xs font-semibold text-<?= $theme_primary ?>-400/80 uppercase tracking-wider mb-2">Nama Calon Bendahara (Formatur 2) <span class="text-white/30 normal-case font-normal">(opsional)</span></label>
                                <input type="text" name="candidate_name_2" id="input_candidate_name_2" placeholder="Masukkan calon kedua..." autocomplete="off"
                                       class="w-full px-4 py-2.5 bg-black/20 border border-white/10 rounded-xl focus:outline-none transition-all text-white text-sm placeholder-white/30">
                                <div id="candidate_suggestions_box_2" class="absolute left-0 right-0 top-full mt-1 bg-[#1b3638] border border-white/15 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden z-[11000] divide-y divide-white/5"></div>
                            </div>
 
                            <div class="relative">
                                <label class="block text-xs font-semibold text-<?= $theme_primary ?>-400/80 uppercase tracking-wider mb-2">Nama Calon Sekretaris (Formatur 3) <span class="text-white/30 normal-case font-normal">(opsional)</span></label>
                                <input type="text" name="candidate_name_3" id="input_candidate_name_3" placeholder="Masukkan calon ketiga..." autocomplete="off"
                                       class="w-full px-4 py-2.5 bg-black/20 border border-white/10 rounded-xl focus:outline-none transition-all text-white text-sm placeholder-white/30">
                                <div id="candidate_suggestions_box_3" class="absolute left-0 right-0 top-full mt-1 bg-[#1b3638] border border-white/15 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden z-[11000] divide-y divide-white/5"></div>
                            </div>
                            <p id="duplicate-warning-text" class="text-red-400 text-xs font-semibold hidden mt-2">Nama calon formatur 1, 2, dan 3 tidak boleh ada yang sama!</p>
                        </div>
                    </div>
 
                    <!-- Modal Footer Buttons -->
                    <div class="px-6 pb-5 flex gap-3 shrink-0">
                        <!-- Step 1 Footer -->
                        <div id="footer-step-1" class="flex w-full gap-3">
                            <button type="button" onclick="toggleNominateModal(false)" 
                                    class="flex-1 py-2.5 rounded-xl border border-white/20 text-white/70 hover:bg-white/5 hover:text-white transition-all text-sm font-semibold">
                                Batal
                            </button>
                            <button type="button" id="btn-next-step" onclick="goToStep(2)"
                                    class="flex-1 py-2.5 rounded-xl bg-<?= $theme_primary ?>-500 hover:bg-<?= $theme_primary ?>-600 active:scale-95 <?= $theme_dark_text ?> text-sm font-bold transition-all shadow-[0_4px_12px_rgba(<?= $is_rundayan ? '6,182,212' : '245,158,11' ?>,0.3)] flex items-center justify-center gap-1.5">
                                Selanjutnya <i class="bi bi-arrow-right"></i>
                            </button>
                        </div>
                        
                        <!-- Step 2 Footer (Hidden by default) -->
                        <div id="footer-step-2" class="flex w-full gap-3 hidden">
                            <button type="button" onclick="goToStep(1)" 
                                    class="flex-1 py-2.5 rounded-xl border border-white/20 text-white/70 hover:bg-white/5 hover:text-white transition-all text-sm font-semibold flex items-center justify-center gap-1.5">
                                <i class="bi bi-arrow-left"></i> Kembali
                            </button>
                            <button type="submit" id="btn-submit-form"
                                    class="flex-1 py-2.5 rounded-xl bg-<?= $theme_primary ?>-500 hover:bg-<?= $theme_primary ?>-600 active:scale-95 <?= $theme_dark_text ?> text-sm font-bold transition-all shadow-[0_4px_12px_rgba(<?= $is_rundayan ? '6,182,212' : '245,158,11' ?>,0.3)]">
                                Kirim
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- QR Code Modal -->
    <div id="shareModal" class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm opacity-0 pointer-events-none transition-all duration-300">
        <div class="bg-gradient-to-b from-[#1b3638] to-[#122829] border border-white/15 rounded-3xl max-w-sm w-full overflow-hidden shadow-2xl transform scale-95 transition-all duration-300 relative text-center p-6">
            <button onclick="toggleShareModal(false)" class="absolute right-4 top-4 text-white/60 hover:text-white text-2xl font-bold">&times;</button>
            
            <h3 class="text-xl font-bold text-<?= $theme_primary ?>-300 mt-2">QR Code Kandidat</h3>
            <p id="shareTargetName" class="text-white/80 text-sm mt-1 mb-4 font-semibold"></p>

            <div class="bg-white p-3 rounded-2xl inline-block shadow-lg mb-4 relative min-w-[216px] min-h-[216px] flex items-center justify-center">
                <!-- Loading Spinner -->
                <div id="qrSpinner" class="absolute inset-0 flex items-center justify-center bg-white rounded-2xl">
                    <div class="animate-spin rounded-full h-8 w-8 border-4 border-<?= $theme_primary ?>-500 border-t-transparent"></div>
                </div>
                <img id="shareQRCode" src="" alt="QR Code" class="w-48 h-48 opacity-0 transition-opacity duration-300" onload="document.getElementById('qrSpinner').style.display='none'; this.classList.remove('opacity-0');">
            </div>

            <div class="flex flex-col gap-3.5 mt-2">
                <button onclick="downloadQRCode()" 
                        class="w-full py-2.5 bg-gradient-to-r from-<?= $theme_primary ?>-500 to-<?= $theme_primary ?>-600 hover:from-<?= $theme_primary ?>-600 hover:to-<?= $theme_primary ?>-700 <?= $theme_dark_text ?> font-bold rounded-xl text-xs transition-all flex items-center justify-center gap-2 shadow-[0_4px_12px_rgba(<?= $is_rundayan ? '6,182,212' : '245,158,11' ?>,0.2)]">
                    <i class="bi bi-download"></i>
                    Unduh Gambar QR Code
                </button>
                <div class="relative">
                    <input type="text" id="shareLinkInput" readonly 
                           class="w-full pl-4 pr-16 py-3 bg-[#112426] border border-white/10 rounded-xl text-white text-xs select-all text-center">
                    <button onclick="copyShareLink()" 
                            class="absolute right-2 top-1/2 -translate-y-1/2 px-3 py-1.5 bg-<?= $theme_primary ?>-500 hover:bg-<?= $theme_primary ?>-600 <?= $theme_dark_text ?> font-bold rounded-lg text-xs transition-all">
                        Copy
                    </button>
                </div>
            </div>
            <p id="copyFeedback" class="text-emerald-400 text-xs mt-2 opacity-0 transition-opacity h-4">Link berhasil disalin!</p>
        </div>
    </div>

    <!-- Floating Toast Notification -->
    <div id="toastNotification" class="fixed bottom-5 right-5 z-[20000] bg-gradient-to-r from-teal-950 to-[#122829] border border-<?= $theme_primary ?>-500/30 text-<?= $theme_primary ?>-300 px-5 py-3.5 rounded-xl shadow-2xl flex items-center gap-2.5 opacity-0 pointer-events-none transition-all duration-300 transform translate-y-2">
        <i class="bi bi-check-circle-fill text-emerald-400 text-lg"></i>
        <span class="text-sm font-semibold">Tautan kandidat berhasil disalin!</span>
    </div>
</main>

<!-- Page specific CSS and JS -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.8s ease-out forwards;
    }
    .animate-slide-in {
        animation: fadeIn 0.4s ease-out forwards;
    }
</style>

<script>
    const themePrimary = '<?= $theme_primary ?>';
    const borderActive = 'border-' + themePrimary + '-500';
    const textActive = 'text-' + themePrimary + '-300';

    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.getElementById('tabContent-' + tabId).classList.remove('hidden');
        
        document.querySelectorAll('.tab-btn').forEach(btn => {
            btn.classList.remove(borderActive, textActive);
            btn.classList.add('border-transparent', 'text-white/60');
        });
        const activeBtn = document.getElementById('tabBtn-' + tabId);
        if (activeBtn) {
            activeBtn.classList.remove('border-transparent', 'text-white/60');
            activeBtn.classList.add(borderActive, textActive);
        }
    }

    const ancestorsData = <?= json_encode(array_column($ancestors, 'ancestor_name')) ?>;
    const namesData = <?= json_encode($all_names) ?>;

    function setupSuggestions(inputId, boxId, dataArray) {
        const input = document.getElementById(inputId);
        const box = document.getElementById(boxId);

        function render(filterText) {
            const query = filterText.toLowerCase().trim();
            box.innerHTML = '';
            
            // Filter options that contain search query
            const filtered = dataArray.filter(name => name.toLowerCase().includes(query));

            if (filtered.length > 0) {
                filtered.forEach(name => {
                    const initial = name.charAt(0).toUpperCase();
                    const itemHtml = `
                        <div onclick="selectSuggestion('${inputId}', '${boxId}', \`${name.replace(/'/g, "\\'")}\`)" class="px-4 py-3 hover:bg-white/5 cursor-pointer flex items-center gap-3 transition-colors text-left border-b border-white/5">
                            <div class="w-8 h-8 rounded-full bg-${themePrimary}-500/20 text-${themePrimary}-300 flex items-center justify-center font-bold text-xs shrink-0">
                                ${initial}
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-white text-xs font-semibold truncate">${name}</div>
                                <div class="text-white/40 text-[9px]">Data Keluarga</div>
                            </div>
                        </div>
                    `;
                    box.insertAdjacentHTML('beforeend', itemHtml);
                });
            }

            // Always provide option to add manual text currently typed if not fully matched
            if (query.length > 0 && !dataArray.some(name => name.toLowerCase() === query)) {
                const addHtml = `
                    <div onclick="selectSuggestion('${inputId}', '${boxId}', \`${filterText.replace(/'/g, "\\'")}\`)" class="px-4 py-2.5 hover:bg-white/5 cursor-pointer text-center text-xs text-${themePrimary}-300 font-bold flex items-center justify-center gap-1.5 transition-colors">
                        <i class="bi bi-plus-circle-fill"></i> Gunakan "<strong>${filterText}</strong>"
                    </div>
                `;
                box.insertAdjacentHTML('beforeend', addHtml);
            }

            if (box.children.length > 0) {
                box.classList.remove('hidden');
            } else {
                box.classList.add('hidden');
            }
        }

        input.addEventListener('focus', () => {
            render(input.value);
        });

        input.addEventListener('input', (e) => {
            render(e.target.value);
        });

        // Close on clicking outside
        document.addEventListener('click', (e) => {
            if (!input.contains(e.target) && !box.contains(e.target)) {
                box.classList.add('hidden');
            }
        });
    }

    function selectSuggestion(inputId, boxId, value) {
        document.getElementById(inputId).value = value;
        document.getElementById(boxId).classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', () => {
        setupSuggestions('input_nominator_name', 'nominator_suggestions_box', namesData);
        setupSuggestions('input_candidate_name_1', 'candidate_suggestions_box_1', namesData);
        setupSuggestions('input_candidate_name_2', 'candidate_suggestions_box_2', namesData);
        setupSuggestions('input_candidate_name_3', 'candidate_suggestions_box_3', namesData);

        // Client-side live validation for duplicate candidate names
        const input1 = document.getElementById('input_candidate_name_1');
        const input2 = document.getElementById('input_candidate_name_2');
        const input3 = document.getElementById('input_candidate_name_3');
        const warning = document.getElementById('duplicate-warning-text');
        const submitBtn = document.getElementById('btn-submit-form');

        function checkDuplicates() {
            const c1 = input1.value.trim().toLowerCase();
            const c2 = input2.value.trim().toLowerCase();
            const c3 = input3.value.trim().toLowerCase();

            const activeCands = [];
            if (c1) activeCands.push(c1);
            if (c2) activeCands.push(c2);
            if (c3) activeCands.push(c3);

            const uniqueCands = [...new Set(activeCands)];
            if (uniqueCands.length < activeCands.length) {
                warning.classList.remove('hidden');
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                return true;
            } else {
                warning.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                return false;
            }
        }

        [input1, input2, input3].forEach(input => {
            input.addEventListener('input', checkDuplicates);
            input.addEventListener('change', checkDuplicates);
        });

        document.getElementById('multiStepNominateForm').addEventListener('submit', function(e) {
            if (checkDuplicates()) {
                e.preventDefault();
            }
        });
    });

    function goToStep(stepNum) {
        const type = document.getElementById('input_nominate_type').value;
        const colorClass = type === 'individu' ? 'amber-500' : 'cyan-500';
        const textClass = type === 'individu' ? 'text-amber-300' : 'text-cyan-300';
        const bgClass = type === 'individu' ? 'bg-amber-500' : 'bg-cyan-500';
        const darkTextClass = type === 'individu' ? 'text-teal-950' : 'text-slate-950';

        if (stepNum === 2) {
            const nominator = document.getElementById('input_nominator_name');
            const ancestor = document.getElementById('input_ancestor_name');
            
            if (!nominator.value.trim()) {
                nominator.reportValidity();
                return;
            }
            if (!ancestor.value.trim()) {
                ancestor.reportValidity();
                return;
            }
            
            // Transition to Step 2
            document.getElementById('nominateStep-1').classList.add('hidden');
            document.getElementById('footer-step-1').classList.add('hidden');
            document.getElementById('nominateStep-2').classList.remove('hidden');
            document.getElementById('footer-step-2').classList.remove('hidden');
            
            // Update Indicator Styling
            document.getElementById('indicator-step2').className = `flex items-center gap-1.5 ${textClass}`;
            
            const circle2 = document.getElementById('indicator-step2-circle');
            circle2.className = `w-5 h-5 rounded-full ${bgClass} ${darkTextClass} text-[10px] font-bold flex items-center justify-center`;
            
            const line = document.getElementById('indicator-line');
            line.className = `w-8 h-0.5 ${bgClass}`;
        } else {
            // Transition back to Step 1
            document.getElementById('nominateStep-2').classList.add('hidden');
            document.getElementById('footer-step-2').classList.add('hidden');
            document.getElementById('nominateStep-1').classList.remove('hidden');
            document.getElementById('footer-step-1').classList.remove('hidden');
            
            // Reset Indicator Styling
            document.getElementById('indicator-step2').className = 'flex items-center gap-1.5 text-white/40';
            
            const circle2 = document.getElementById('indicator-step2-circle');
            circle2.className = 'w-5 h-5 rounded-full bg-white/10 text-white/55 text-[10px] font-bold flex items-center justify-center';
            
            const line = document.getElementById('indicator-line');
            line.className = 'w-8 h-0.5 bg-white/10';
        }
    }

    function openNominateModal(type) {
        // Set type value
        document.getElementById('input_nominate_type').value = type;
        
        // Dynamically style based on type
        const modalBox = document.getElementById('nominateModalBox');
        const modalTitle = document.getElementById('modalTitle');
        const step1Circle = document.getElementById('indicator-step1-circle');
        const step1Text = document.getElementById('indicator-step1');
        const step2Circle = document.getElementById('indicator-step2-circle');
        const step2Text = document.getElementById('indicator-step2');
        const line = document.getElementById('indicator-line');
        
        // Reset steps
        goToStep(1); 
        document.getElementById('multiStepNominateForm').reset();
        
        // Apply type-specific colors & texts
        if (type === 'individu') {
            modalBox.className = "bg-gradient-to-b from-[#1b3638] to-[#122829] border border-amber-500/20 rounded-2xl w-full shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] flex flex-col overflow-hidden";
            modalTitle.innerText = "Usulkan Calon Individu";
            modalTitle.className = "text-base font-bold text-amber-300";
            
            // Adjust form border focuses to Amber
            document.querySelectorAll('#multiStepNominateForm input').forEach(el => {
                el.classList.remove('focus:border-cyan-400');
                el.classList.add('focus:border-amber-400');
            });
            
            // Set nominate button color
            const nextBtn = document.getElementById('btn-next-step');
            nextBtn.className = "flex-1 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-teal-950 text-sm font-bold transition-all shadow-[0_4px_12px_rgba(245,158,11,0.3)] flex items-center justify-center gap-1.5";
            
            const submitBtn = document.getElementById('btn-submit-form');
            submitBtn.className = "flex-1 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-teal-950 text-sm font-bold transition-all shadow-[0_4px_12px_rgba(245,158,11,0.3)]";
            
            // Step 1 Active (Amber)
            step1Text.className = "flex items-center gap-1.5 text-amber-300";
            step1Circle.className = "w-5 h-5 rounded-full bg-amber-500 text-teal-950 text-[10px] font-bold flex items-center justify-center";
            
            // Step 2 Inactive
            step2Text.className = "flex items-center gap-1.5 text-white/40";
            step2Circle.className = "w-5 h-5 rounded-full bg-white/10 text-white/50 text-[10px] font-bold flex items-center justify-center";
            line.className = "w-8 h-0.5 bg-white/10";
        } else {
            modalBox.className = "bg-gradient-to-b from-[#112d30] to-[#0c1f21] border border-cyan-500/20 rounded-2xl w-full shadow-2xl transform scale-95 transition-transform duration-300 max-h-[90vh] flex flex-col overflow-hidden";
            modalTitle.innerText = "Usulkan Calon Rundayan";
            modalTitle.className = "text-base font-bold text-cyan-300";
            
            // Adjust form border focuses to Cyan
            document.querySelectorAll('#multiStepNominateForm input').forEach(el => {
                el.classList.remove('focus:border-amber-400');
                el.classList.add('focus:border-cyan-400');
            });
            
            // Set nominate button color
            const nextBtn = document.getElementById('btn-next-step');
            nextBtn.className = "flex-1 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-600 text-slate-950 text-sm font-bold transition-all shadow-[0_4px_12px_rgba(6,182,212,0.3)] flex items-center justify-center gap-1.5";
            
            const submitBtn = document.getElementById('btn-submit-form');
            submitBtn.className = "flex-1 py-2.5 rounded-xl bg-cyan-500 hover:bg-cyan-600 text-slate-950 text-sm font-bold transition-all shadow-[0_4px_12px_rgba(6,182,212,0.3)]";
            
            // Step 1 Active (Cyan)
            step1Text.className = "flex items-center gap-1.5 text-cyan-300";
            step1Circle.className = "w-5 h-5 rounded-full bg-cyan-500 text-slate-950 text-[10px] font-bold flex items-center justify-center";
            
            // Step 2 Inactive
            step2Text.className = "flex items-center gap-1.5 text-white/40";
            step2Circle.className = "w-5 h-5 rounded-full bg-white/10 text-white/50 text-[10px] font-bold flex items-center justify-center";
            line.className = "w-8 h-0.5 bg-white/10";
        }
        
        // Show modal
        const modal = document.getElementById('nominateModal');
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modalBox.classList.remove('scale-95');
        modalBox.classList.add('scale-100');
        modal.scrollTop = 0;
        document.body.style.overflow = 'hidden';
    }

    function toggleNominateModal(show) {
        if (!show) {
            const modal = document.getElementById('nominateModal');
            const box = document.getElementById('nominateModalBox');
            modal.classList.add('opacity-0', 'pointer-events-none');
            box.classList.remove('scale-100');
            box.classList.add('scale-95');
            document.body.style.overflow = ''; // restore page scroll
        }
    }



    function toggleShareModal(show) {
        const modal = document.getElementById('shareModal');
        const box = modal.querySelector('div');
        if (show) {
            modal.classList.remove('opacity-0', 'pointer-events-none');
            box.classList.remove('scale-95');
            box.classList.add('scale-100');
            document.body.style.overflow = 'hidden'; // lock page scroll
        } else {
            modal.classList.add('opacity-0', 'pointer-events-none');
            box.classList.remove('scale-100');
            box.classList.add('scale-95');
            document.getElementById('copyFeedback').classList.add('opacity-0');
            document.getElementById('copyFeedback').classList.remove('opacity-100');
            document.body.style.overflow = ''; // restore page scroll
        }
    }

    function showQRModal(id, name) {
        const shareUrl = "<?= base_url($page_type === 'rundayan' ? 'rundayan' : 'anggota') ?>";
        document.getElementById('shareTargetName').textContent = name;
        document.getElementById('shareLinkInput').value = shareUrl;
        
        // Reset QR code image and display loader spinner before loading
        const img = document.getElementById('shareQRCode');
        const spinner = document.getElementById('qrSpinner');
        img.classList.add('opacity-0');
        img.src = '';
        spinner.style.display = 'flex';
        
        // Generate QR code URL using standard public service
        const qrCodeUrl = "https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=" + encodeURIComponent(shareUrl);
        img.src = qrCodeUrl;

        toggleShareModal(true);
    }

    function downloadQRCode() {
        const qrImg = document.getElementById('shareQRCode');
        if (!qrImg.src) return;

        fetch(qrImg.src)
            .then(response => response.blob())
            .then(blob => {
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'qrcode-' + document.getElementById('shareTargetName').textContent.replace(/\s+/g, '-').toLowerCase() + '.png';
                document.body.appendChild(a);
                a.click();
                a.remove();
                window.URL.revokeObjectURL(url);
            })
            .catch(err => {
                window.open(qrImg.src + "&download=1", '_blank');
            });
    }

    function copyTextToClipboard(text) {
        if (!navigator.clipboard) {
            const textArea = document.createElement("textarea");
            textArea.value = text;
            textArea.style.position = "fixed";
            document.body.appendChild(textArea);
            textArea.focus();
            textArea.select();
            try {
                document.execCommand('copy');
            } catch (err) {
                console.error('Fallback copy failed', err);
            }
            document.body.removeChild(textArea);
            return Promise.resolve();
        }
        return navigator.clipboard.writeText(text);
    }

    let copyTimeout = null;
    function copyShareLink() {
        const copyText = document.getElementById('shareLinkInput');
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        
        copyTextToClipboard(copyText.value).then(() => {
            const feedback = document.getElementById('copyFeedback');
            feedback.classList.remove('opacity-0');
            feedback.classList.add('opacity-100');
            
            if (copyTimeout) clearTimeout(copyTimeout);
            copyTimeout = setTimeout(() => {
                feedback.classList.add('opacity-0');
                feedback.classList.remove('opacity-100');
            }, 2000);
        });
    }

    let toastTimeout = null;
    function copyNomineeLink(id) {
        const shareUrl = "<?= base_url($page_type === 'rundayan' ? 'rundayan' : 'anggota') ?>";
        copyTextToClipboard(shareUrl).then(() => {
            const toast = document.getElementById('toastNotification');
            toast.classList.remove('opacity-0', 'pointer-events-none', 'translate-y-2');
            toast.classList.add('opacity-100', 'translate-y-0');
            
            if (toastTimeout) clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => {
                toast.classList.add('opacity-0', 'pointer-events-none', 'translate-y-2');
                toast.classList.remove('opacity-100', 'translate-y-0');
            }, 2500);
        });
    }




    /*
    function castVote(id, btnElement) {
        if (btnElement.disabled) return;
        
        btnElement.disabled = true;
        const btnText = btnElement.querySelector('span');
        const btnIcon = btnElement.querySelector('i');
        const originalHtml = btnElement.innerHTML;
        
        // Show loading spinner
        btnElement.innerHTML = `<span class="spinner-border spinner-border-sm mr-1" role="status" aria-hidden="true"></span> Processing...`;

        fetch("<?= base_url('anggota/vote/') ?>" + id, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Update votes UI
                const countSpan = document.querySelector('.count-number-' + id);
                if (countSpan) {
                    countSpan.textContent = data.votes_count;
                    // Trigger short micro-animation
                    countSpan.classList.add('scale-125', 'text-emerald-400');
                    setTimeout(() => {
                        countSpan.classList.remove('scale-125', 'text-emerald-400');
                    }, 500);
                }

                // Update Button
                btnElement.className = "px-6 py-3 rounded-xl font-bold transition-all flex items-center gap-2 bg-emerald-500/20 text-emerald-400 border border-emerald-500/30 cursor-not-allowed";
                btnElement.innerHTML = `<i class="bi bi-check2-circle"></i> <span>Voted</span>`;
                
                // Show floating success message
                alert(data.message);
            } else {
                alert(data.message);
                btnElement.innerHTML = originalHtml;
                btnElement.disabled = false;
            }
        })
        .catch(err => {
            console.error(err);
            alert("Terjadi kesalahan koneksi. Silakan coba lagi.");
            btnElement.innerHTML = originalHtml;
            btnElement.disabled = false;
        });
    }
    */
</script>
