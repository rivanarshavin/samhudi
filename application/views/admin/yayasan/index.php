<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Calon Yayasan | Admin Panel</title>
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
            </div>

            <!-- Filters & Search -->
            <div class="bg-brand-dark/20 border border-brand-medium/20 rounded-2xl p-6 shadow-sm">
                <form method="GET" action="<?= base_url('admin/yayasan') ?>" class="grid grid-cols-1 md:grid-cols-12 gap-4">
                    
                    <!-- Search Input -->
                    <div class="relative md:col-span-8">
                        <i class="bi bi-search absolute left-4 top-3.5 text-white/40"></i>
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama calon, yayasan, buyut..." class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 pl-11 pr-4 text-sm text-white placeholder-white/40 focus:outline-none focus:border-brand-medium transition-all">
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
                    <table class="w-full text-left border-collapse">
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
                                            <?= htmlspecialchars($c['ancestor_name']) ?>
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
                                    <td colspan="7" class="py-8 text-center text-white/40 text-sm">Belum ada data pencalonan ketua yayasan.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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
                    <h3 class="text-lg font-bold text-amber-300 flex items-center gap-2">
                        <i class="bi bi-person-fill"></i> Tabel Pencalonan Individu (Rekap)
                    </h3>
                    <?php if (empty($individu_candidates)): ?>
                        <p class="text-white/40 text-sm italic">Belum ada data pencalonan individu yang approved.</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse" style="min-width: 800px;">
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
                                            <td class="py-3.5 pr-6 text-white/55 whitespace-nowrap">#<?= $index + 1 ?></td>
                                            <td class="py-3.5 pr-6 font-bold text-white whitespace-nowrap"><?= htmlspecialchars($c['candidate_name']) ?></td>
                                            <td class="py-3.5 pr-6 whitespace-nowrap text-xs">
                                                 <span class="px-2 py-0.5 rounded bg-amber-500/10 text-amber-300 border border-amber-500/25">
                                                     <?= htmlspecialchars($c['roles_text']) ?>
                                                 </span>
                                            </td>
                                            <td class="py-3.5 pr-6 text-white/80 whitespace-nowrap"><?= htmlspecialchars($c['nominator_name']) ?></td>
                                            <td class="py-3.5 pr-6 text-white/80 whitespace-nowrap"><?= htmlspecialchars($c['ancestor_name']) ?></td>
                                            <td class="py-3.5 pr-6 text-amber-300 font-bold whitespace-nowrap"><?= $c['votes_count'] ?> suara</td>
                                            <td class="py-3.5 pr-6 text-emerald-400 font-semibold whitespace-nowrap"><?= $c['breakdown_text'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- 2. RUNDAYAN TABLE -->
                <div class="bg-gradient-to-b from-[#112d30] to-[#0c1f21] border border-cyan-500/20 rounded-2xl p-6 shadow-xl space-y-4">
                    <h3 class="text-lg font-bold text-cyan-300 flex items-center gap-2">
                        <i class="bi bi-people-fill"></i> Tabel Pencalonan Rundayan (Rekap)
                    </h3>
                    <?php if (empty($rundayan_candidates)): ?>
                        <p class="text-white/40 text-sm italic">Belum ada data pencalonan rundayan yang approved.</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse" style="min-width: 800px;">
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
                                            <td class="py-3.5 pr-6 text-white/55 whitespace-nowrap">#<?= $index + 1 ?></td>
                                            <td class="py-3.5 pr-6 font-bold text-white whitespace-nowrap"><?= htmlspecialchars($c['candidate_name']) ?></td>
                                            <td class="py-3.5 pr-6 whitespace-nowrap text-xs">
                                                 <span class="px-2 py-0.5 rounded bg-cyan-500/10 text-cyan-300 border border-cyan-500/25">
                                                     <?= htmlspecialchars($c['roles_text']) ?>
                                                 </span>
                                            </td>
                                            <td class="py-3.5 pr-6 text-white/80 whitespace-nowrap"><?= htmlspecialchars($c['nominator_name']) ?></td>
                                            <td class="py-3.5 pr-6 text-white/80 whitespace-nowrap"><?= htmlspecialchars($c['ancestor_name']) ?></td>
                                            <td class="py-3.5 pr-6 text-cyan-300 font-bold whitespace-nowrap"><?= $c['votes_count'] ?> suara</td>
                                            <td class="py-3.5 pr-6 text-emerald-400 font-semibold whitespace-nowrap"><?= $c['breakdown_text'] ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
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

    <!-- JS untuk Modal -->
    <script>
        function showConfirm(event, url, message) {
            event.preventDefault();
            const modal = document.getElementById('confirmModal');
            const card = document.getElementById('confirmModalCard');
            
            document.getElementById('confirmMessage').innerText = message;
            
            const actionBtn = document.getElementById('confirmActionBtn');
            actionBtn.onclick = function() {
                window.location.href = url;
            };
            
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
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        }
    </script>
</body>
</html>
