<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$page_type = $page_type ?? 'individu';
$is_rundayan = ($page_type === 'rundayan');
$theme_primary = $is_rundayan ? 'cyan' : 'amber';
$theme_dark_text = $is_rundayan ? 'text-slate-950' : 'text-teal-950';

$section_title = $is_rundayan ? 'Pencalonan Rundayan' : 'Pencalonan Individu';
$form_action_url = $is_rundayan ? base_url('rundayan/nominate') : base_url('anggota/nominate');

// Helper to format nominator list
if (!function_exists('format_nominators')) {
    function format_nominators($nominators_array, $ancestors_array) {
        $items = [];
        foreach ($nominators_array as $idx => $nom) {
            $anc = $ancestors_array[$idx] ?? '';
            $items[] = htmlspecialchars($nom) . (!empty($anc) ? ' ('.htmlspecialchars($anc).')' : '');
        }
        return implode(', ', $items);
    }
}

// Tree builder helper for Bagan Tab
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
                        <?php 
                        $role_raw = (isset($cand['roles_text']) && $cand['roles_text'] !== '-') ? $cand['roles_text'] : ($cand['description'] ?: 'Ketua');
                        $is_standard = preg_match('/(ketua|bendahara|sekretaris)/i', $role_raw);
                        $role_lbl = $is_standard ? 'Kandidat ' . $role_raw : $role_raw;
                        ?>
                        <span class="text-[10px] uppercase font-bold text-<?= $theme_primary ?>-400 tracking-wider block mb-0.5"><?= htmlspecialchars($role_lbl) ?></span>
                        <strong class="text-white text-base font-semibold"><?= htmlspecialchars($cand['candidate_name']) ?></strong>
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
?>

<main class="min-h-screen bg-gradient-to-b from-[#274d4f] via-[#1a3638] to-[#0f2122] text-white pt-32 sm:pt-36 pb-16 px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center">
    <div class="max-w-md w-full">
        <!-- Hero Section / Title -->
        <div class="text-center mb-8 animate-fade-in">
            <span class="px-4 py-1.5 rounded-full text-xs font-semibold uppercase tracking-wider bg-<?= $theme_primary ?>-500/20 text-<?= $theme_primary ?>-400 border border-<?= $theme_primary ?>-500/30"><?= $section_title ?></span>
            <h1 class="mt-4 text-3xl sm:text-4xl font-display font-bold text-transparent bg-clip-text bg-gradient-to-r from-<?= $theme_primary ?>-200 via-<?= $theme_primary ?>-400 to-<?= $theme_primary ?>-200 tracking-tight leading-tight">
                Pencalonan Ketua Yayasan (<?= $is_rundayan ? 'Rundayan' : 'Individu' ?>)
            </h1>
            <p class="mt-3 text-sm text-emerald-100/80 max-w-sm mx-auto leading-relaxed">
                Lihat daftar nama kandidat, siapa yang mencalonkannya, alur pencalonan kandidat, dan bagikan informasi calon.
            </p>
            <?php if ($is_rundayan): ?>
                <p class="mt-4 text-xs sm:text-sm text-cyan-300 max-w-2xl mx-auto font-medium leading-relaxed bg-cyan-950/40 border border-cyan-500/20 px-5 py-3.5 rounded-2xl flex items-start gap-2.5 shadow-sm text-left">
                    <i class="bi bi-info-circle-fill text-cyan-400 text-base shrink-0 mt-0.5"></i>
                    <span>Pengusulan rundayan hanya 1 paket (3 nama) untuk setiap rundayan / keluarga besar gen 2. Sehingga diperlukan kesepakatan dari keluarga tersebut utk memunculkan 3 nama</span>
                </p>
            <?php endif; ?>
        </div>

        <!-- Success/Error Alert messages -->
        <?php if ($this->session->flashdata('error')): ?>
            <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-500/30 text-red-200 flex items-center gap-3 animate-slide-in">
                <i class="bi bi-exclamation-triangle-fill text-xl text-red-400"></i>
                <span class="text-xs font-semibold"><?= $this->session->flashdata('error') ?></span>
            </div>
        <?php endif; ?>

        <!-- Form Card Container -->
        <div class="bg-gradient-to-b from-[#1b3638] to-[#122829] border border-white/15 rounded-3xl w-full shadow-2xl overflow-hidden backdrop-blur-md">
            <!-- Step Indicator -->
            <div class="px-6 pt-6 flex items-center justify-center gap-2 shrink-0 border-b border-white/5 pb-5">
                <div id="indicator-step1" class="flex items-center gap-1.5 text-<?= $theme_primary ?>-300">
                    <span id="indicator-step1-circle" class="w-5 h-5 rounded-full bg-<?= $theme_primary ?>-500 <?= $theme_dark_text ?> text-[10px] font-bold flex items-center justify-center">1</span>
                    <span class="text-[11px] font-bold">Data Pemilih</span>
                </div>
                <div class="w-8 h-0.5 bg-white/10" id="indicator-line"></div>
                <div id="indicator-step2" class="flex items-center gap-1.5 text-white/40">
                    <span id="indicator-step2-circle" class="w-5 h-5 rounded-full bg-white/10 text-white/55 text-[10px] font-bold flex items-center justify-center">2</span>
                    <span class="text-[11px] font-bold">Data Calon Formatur</span>
                </div>
            </div>

            <!-- Form -->
            <form id="multiStepNominateForm" action="<?= $form_action_url ?>" method="POST" autocomplete="off" class="flex flex-col">
                <input type="hidden" name="type" id="input_nominate_type" value="<?= $page_type ?>">
                
                <!-- Step 1 Content -->
                <div id="nominateStep-1" class="px-6 py-6 space-y-4">
                    <div class="relative">
                        <label class="block text-xs font-semibold text-<?= $theme_primary ?>-400/80 uppercase tracking-wider mb-2">Nama Pemilih</label>
                        <input type="text" name="nominator_name" id="input_nominator_name" required placeholder="Contoh: Budi Samhudi" autocomplete="off"
                               class="w-full px-4 py-2.5 bg-black/20 border border-white/10 rounded-xl focus:outline-none transition-all text-white text-sm placeholder-white/30">
                        <div id="nominator_suggestions_box" class="absolute left-0 right-0 top-full mt-1 bg-[#1b3638] border border-white/15 rounded-xl shadow-2xl max-h-48 overflow-y-auto hidden z-[11000] divide-y divide-white/5"></div>
                    </div>

                    <div class="relative">
                        <label class="block text-xs font-semibold text-<?= $theme_primary ?>-400/80 uppercase tracking-wider mb-2">Rundayan (Keturunan)</label>
                        <select name="ancestor_name" id="input_ancestor_name" required
                                class="w-full px-4 py-2.5 bg-black/20 border border-white/10 rounded-xl focus:outline-none transition-all text-white text-sm placeholder-white/30 [&>option]:bg-[#1b3638]">
                            <option value="" disabled selected class="text-white/40">-- Pilih Rundayan --</option>
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
                <div id="nominateStep-2" class="px-6 py-6 space-y-4 hidden pb-12">
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

                <!-- Footer Buttons -->
                <div class="px-6 pb-6 flex gap-3 shrink-0">
                    <!-- Step 1 Footer -->
                    <div id="footer-step-1" class="flex w-full gap-3">
                        <button type="button" id="btn-next-step" onclick="goToStep(2)"
                                class="w-full py-3 rounded-xl bg-<?= $theme_primary ?>-500 hover:bg-<?= $theme_primary ?>-600 active:scale-95 <?= $theme_dark_text ?> text-sm font-bold transition-all shadow-[0_4px_12px_rgba(<?= $is_rundayan ? '6,182,212' : '245,158,11' ?>,0.3)] flex items-center justify-center gap-1.5">
                            Selanjutnya <i class="bi bi-arrow-right"></i>
                        </button>
                    </div>
                    
                    <!-- Step 2 Footer (Hidden by default) -->
                    <div id="footer-step-2" class="flex w-full gap-3 hidden">
                        <button type="button" onclick="goToStep(1)" 
                                class="flex-1 py-3 rounded-xl border border-white/20 text-white/70 hover:bg-white/5 hover:text-white transition-all text-sm font-semibold flex items-center justify-center gap-1.5">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </button>
                        <button type="submit" id="btn-submit-form"
                                class="flex-1 py-3 rounded-xl bg-<?= $theme_primary ?>-500 hover:bg-<?= $theme_primary ?>-600 active:scale-95 <?= $theme_dark_text ?> text-sm font-bold transition-all shadow-[0_4px_12px_rgba(<?= $is_rundayan ? '6,182,212' : '245,158,11' ?>,0.3)]">
                            Kirim
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>

<?php /* ==========================================
      KODE DINONAKTIFKAN (KARTU CALON & BAGAN SILSILAH)
      Bisa diaktifkan kembali sewaktu-waktu jika dibutuhkan.
      ==========================================

    <div class="max-w-7xl mx-auto mt-12 opacity-50">
        <!-- Control Bar (Search & Nominate Buttons) -->
        <div class="flex flex-col lg:flex-row gap-4 justify-between items-center mb-8 bg-white/5 backdrop-blur-md p-4 rounded-2xl border border-white/10">
            <form action="" method="GET" class="w-full lg:max-w-md relative">
                <input type="text" name="search" value="" placeholder="Cari nama calon, pencalon, atau buyut..." 
                       class="w-full pl-11 pr-4 py-3 bg-[#112426] border border-white/10 rounded-xl focus:border-<?= $theme_primary ?>-400 focus:ring-1 focus:ring-<?= $theme_primary ?>-400 transition-all text-white placeholder-white/40">
                <i class="bi bi-search absolute left-4 top-1/2 -translate-y-1/2 text-white/40"></i>
            </form>
        </div>

        <!-- View Tabs -->
        <div class="flex border-b border-white/10 mb-8 w-full">
            <button class="tab-btn flex-1 pb-3 font-semibold text-xs sm:text-sm border-b-2 border-<?= $theme_primary ?>-500 text-<?= $theme_primary ?>-300 transition-all flex items-center justify-center gap-1.5">
                <i class="bi bi-grid-fill"></i> Kartu Calon
            </button>
            <button class="tab-btn flex-1 pb-3 font-semibold text-xs sm:text-sm border-b-2 border-transparent text-white/60 hover:text-white transition-all flex items-center justify-center gap-1.5">
                <i class="bi bi-diagram-3-fill"></i> Bagan Pencalonan
            </button>
        </div>

        <!-- Candidate Grid Tab -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($candidates as $c): ?>
                <div class="bg-gradient-to-br from-[#1b3638] to-[#122829] border border-white/10 rounded-2xl p-5 shadow-lg relative overflow-hidden flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start mb-3">
                            <span class="px-2.5 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-bold text-white/60 uppercase tracking-wider">Kandidat</span>
                            <span class="text-xs text-white/45"><?= $c['votes_count'] ?> Dukungan</span>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-1"><?= htmlspecialchars($c['candidate_name']) ?></h3>
                        <p class="text-xs text-white/60 mb-4"><?= htmlspecialchars($c['ancestor_name']) ?></p>
                        <div class="border-t border-white/5 pt-3 mt-3">
                            <span class="text-[9px] uppercase font-bold text-white/40 block mb-1">Dicalonkan Oleh</span>
                            <p class="text-xs text-white/80 font-medium line-clamp-2"><?= htmlspecialchars($c['nominator_name']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
========================================== */ ?>

<script>
    // Safe Autocomplete Data Injection
    const namesData = <?= json_encode($all_names ?? []) ?>;

    // Direct helper to setup drop suggestions block
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
                // If query exists, always allow creating as-is
                const addHtml = `
                    <div onclick="selectSuggestion('${inputId}', '${boxId}', \`${input.value.replace(/'/g, "\\'")}\`)" class="px-4 py-2.5 hover:bg-white/5 cursor-pointer text-center text-xs text-<?= $theme_primary ?>-300 font-bold flex items-center justify-center gap-1.5 transition-colors">
                        <i class="bi bi-plus-circle-fill"></i> Gunakan "<strong>${input.value}</strong>"
                    </div>
                `;
                box.innerHTML = addHtml;
                box.classList.remove('hidden');
                return;
            }

            matched.forEach(name => {
                const initial = name.charAt(0).toUpperCase();
                const itemHtml = `
                    <div onclick="selectSuggestion('${inputId}', '${boxId}', \`${name.replace(/'/g, "\\'")}\`)" class="px-4 py-3 hover:bg-white/5 cursor-pointer flex items-center gap-3 transition-colors text-left border-b border-white/5">
                        <div class="w-8 h-8 rounded-full bg-<?= $theme_primary ?>-500/20 text-<?= $theme_primary ?>-300 flex items-center justify-center font-bold text-xs shrink-0">
                            ${initial}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-white truncate">${name}</p>
                        </div>
                    </div>
                `;
                box.innerHTML += itemHtml;
            });
            box.classList.remove('hidden');
        });

        // Close dropdown when clicking outside
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
</script>
