<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Silsilah | Admin Panel</title>
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

            <!-- Title & Action -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="font-display font-extrabold text-2xl text-white">Kelola Silsilah Keluarga</h2>
                    <p class="text-brand-light/70 text-xs mt-1">Daftar anggota keluarga besar H.M Samhudi beserta relasi silsilah.</p>
                </div>
                <a href="<?= base_url('admin/silsilah_add') ?>" class="flex items-center justify-center gap-2 bg-gradient-to-r from-brand-medium to-brand-dark hover:from-brand-medium/90 hover:to-brand-dark/90 border border-brand-medium text-white px-5 py-3 rounded-xl text-sm font-bold shadow-md transition-all w-full md:w-auto">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Tambah Anggota</span>
                </a>
            </div>

            <!-- Filters & Search -->
            <div class="bg-brand-dark/20 border border-brand-medium/20 rounded-2xl p-6 shadow-sm">
                <form method="GET" action="<?= base_url('admin/silsilah') ?>" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-12 gap-4">
                    
                    <!-- Search Input -->
                    <div class="relative md:col-span-2 xl:col-span-3">
                        <i class="bi bi-search absolute left-4 top-3.5 text-white/40"></i>
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama, email, pekerjaan..." class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 pl-11 pr-4 text-sm text-white placeholder-white/40 focus:outline-none focus:border-brand-medium transition-all">
                    </div>

                    <!-- Gender Filter -->
                    <div class="xl:col-span-2">
                        <select name="gender" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            <option value="">Semua Jenis Kelamin</option>
                            <option value="L" <?= $gender == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= $gender == 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>

                    <!-- Generasi Filter -->
                    <div class="xl:col-span-2">
                        <select name="generasi" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            <option value="">Semua Generasi</option>
                            <?php for ($i = 1; $i <= $max_generasi; $i++): ?>
                                <option value="<?= $i ?>" <?= (string)$generasi === (string)$i ? 'selected' : '' ?>>Gen-<?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Persetujuan Filter -->
                    <div class="xl:col-span-2">
                        <select name="status" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            <option value="">Semua Status</option>
                            <option value="pending" <?= ($status ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="approved" <?= ($status ?? '') === 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="rejected" <?= ($status ?? '') === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="flex gap-2 xl:col-span-3">
                        <select name="is_alive" class="flex-1 bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            <option value="">Semua Status Hidup</option>
                            <option value="1" <?= $is_alive === '1' ? 'selected' : '' ?>>Masih Hidup</option>
                            <option value="0" <?= $is_alive === '0' ? 'selected' : '' ?>>Sudah Wafat</option>
                        </select>
                        <button type="submit" class="bg-brand-medium hover:bg-brand-medium/90 border border-brand-medium text-white px-4 rounded-xl flex items-center justify-center transition-all">
                            <i class="bi bi-funnel-fill"></i>
                        </button>
                    </div>

                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-gradient-to-b from-brand-dark/20 to-brand-dark/5 border border-brand-medium/20 rounded-2xl p-6 shadow-lg">
                <form id="bulkDeleteForm" action="<?= base_url('admin/silsilah_delete_multiple') ?>" method="POST" onsubmit="showConfirm(event, null, 'Apakah Anda yakin ingin menghapus anggota yang dipilih beserta seluruh data dan akunnya?', true, 'bulkDeleteForm');">
                    <!-- Bulk Delete Action Bar -->
                    <div id="bulkActionBar" class="mb-4 hidden">
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl flex items-center transition-all text-sm font-bold shadow-lg shadow-red-500/20">
                            <i class="bi bi-trash-fill mr-2"></i> Hapus Terpilih (<span id="selectedCount">0</span>)
                        </button>
                    </div>
                
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#4D6B67]/20">
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap">
                                    <input type="checkbox" id="selectAllCheckbox" class="w-4 h-4 text-brand-medium bg-transparent border-[#4D6B67]/30 rounded focus:ring-brand-medium/50 focus:ring-2 cursor-pointer transition-all">
                                </th>
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap">Anggota</th>
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap">L/P</th>
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap">Generasi</th>
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap">Orang Tua</th>
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap">Kontak / TTL</th>
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap">Persetujuan</th>
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap">Status Hidup</th>
                                <th class="pb-4 px-4 text-xs font-bold text-white/40 uppercase tracking-wider whitespace-nowrap text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#4D6B67]/10">
                            <?php if (!empty($members)): ?>
                                <?php foreach ($members as $member): ?>
                                    <tr>
                                        <!-- Checkbox -->
                                        <td class="py-4 px-4 whitespace-nowrap">
                                            <input type="checkbox" name="ids[]" value="<?= $member['id'] ?>" class="member-checkbox w-4 h-4 text-brand-medium bg-transparent border-[#4D6B67]/30 rounded focus:ring-brand-medium/50 focus:ring-2 cursor-pointer transition-all">
                                        </td>
                                        <!-- Photo & Name -->
                                        <td class="py-4 px-4 whitespace-nowrap flex items-center gap-3">
                                            <?php if ($member['photo'] && file_exists('./' . $member['photo'])): ?>
                                                <img src="<?= base_url($member['photo']) ?>" alt="<?= htmlspecialchars($member['full_name']) ?>" class="w-10 h-10 rounded-full object-cover border border-[#4D6B67]/30">
                                            <?php else: ?>
                                                <div class="w-10 h-10 rounded-full bg-brand-medium/30 flex items-center justify-center text-white font-bold border border-[#4D6B67]/20">
                                                    <?= strtoupper(substr($member['full_name'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <div>
                                                <div class="font-bold text-white"><?= htmlspecialchars($member['full_name']) ?></div>
                                                <div class="text-xs text-brand-light/60"><?= htmlspecialchars($member['occupation'] ?: 'Tidak bekerja/Lainnya') ?></div>
                                            </div>
                                        </td>
                                        
                                        <!-- Gender -->
                                        <td class="py-4 px-4 whitespace-nowrap text-sm">
                                            <span class="px-2 py-1 rounded text-xs font-bold <?= $member['gender'] == 'L' ? 'bg-blue-500/20 text-blue-300' : 'bg-pink-500/20 text-pink-300' ?>">
                                                <?= $member['gender'] == 'L' ? 'L' : 'P' ?>
                                            </span>
                                        </td>

                                        <!-- Generasi -->
                                        <td class="py-4 px-4 whitespace-nowrap text-sm text-brand-light">
                                            Gen-<?= $member['generasi'] ?? '-' ?>
                                        </td>

                                        <!-- Parents -->
                                        <td class="py-4 px-4 whitespace-nowrap text-sm">
                                            <div class="text-xs text-white/80">
                                                <strong>Ayah:</strong> <?= htmlspecialchars($member['father_name'] ?: '-') ?><br>
                                                <strong>Ibu:</strong> <?= htmlspecialchars($member['mother_name'] ?: '-') ?>
                                            </div>
                                        </td>

                                        <!-- Contact / TTL -->
                                        <td class="py-4 px-4 whitespace-nowrap text-sm">
                                            <div class="text-xs text-white/80">
                                                <i class="bi bi-geo-alt-fill text-white/40"></i> <?= htmlspecialchars($member['birth_place'] ?: '-') ?>, <?= $member['birth_date'] ? date('j M Y', strtotime($member['birth_date'])) : '-' ?><br>
                                                <i class="bi bi-telephone-fill text-white/40"></i> <?= htmlspecialchars($member['phone'] ?: '-') ?>
                                            </div>
                                        </td>

                                        <!-- Persetujuan -->
                                        <td class="py-4 px-4 whitespace-nowrap text-sm">
                                            <?php if ($member['status'] == 'approved'): ?>
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Approved</span>
                                            <?php elseif ($member['status'] == 'rejected'): ?>
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-500/20 text-red-300 border border-red-500/30">Rejected</span>
                                            <?php else: ?>
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-yellow-500/20 text-yellow-300 border border-yellow-500/30 flex items-center w-max gap-1">
                                                    <i class="bi bi-hourglass-split"></i> Pending
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Status Hidup / Wafat -->
                                        <td class="py-4 px-4 whitespace-nowrap text-sm">
                                            <?php if ($member['is_alive'] == 1): ?>
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Hidup</span>
                                            <?php else: ?>
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-500/20 text-red-300 border border-red-500/30">Wafat (<?= $member['death_date'] ? date('Y', strtotime($member['death_date'])) : '?' ?>)</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Actions -->
                                        <td class="py-4 px-4 text-right space-x-2 whitespace-nowrap">
                                            <?php if ($member['status'] == 'pending'): ?>
                                                <a href="<?= base_url('admin/silsilah_approve/' . $member['id']) ?>" onclick="showConfirm(event, this.href, 'Setujui anggota ini untuk tampil di silsilah keluarga?')" class="inline-flex items-center justify-center px-3 h-8 rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500 hover:text-white border border-emerald-500/20 transition-all text-xs font-bold">
                                                    <i class="bi bi-check-lg mr-1"></i> Terima
                                                </a>
                                                <a href="<?= base_url('admin/silsilah_reject/' . $member['id']) ?>" onclick="showConfirm(event, this.href, 'Tolak pendaftaran anggota ini?')" class="inline-flex items-center justify-center px-3 h-8 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white border border-red-500/20 transition-all text-xs font-bold">
                                                    <i class="bi bi-x-lg mr-1"></i> Tolak
                                                </a>
                                            <?php endif; ?>
                                            
                                            <a href="<?= base_url('admin/silsilah_edit/' . $member['id']) ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-500/10 text-yellow-400 hover:bg-yellow-500 hover:text-black border border-yellow-500/20 transition-all" title="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="<?= base_url('admin/silsilah_delete/' . $member['id']) ?>" onclick="showConfirm(event, this.href, 'Apakah Anda yakin ingin menghapus anggota ini? Data relasi silsilah anak juga akan terputus.')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-red/10 text-brand-red hover:bg-brand-red hover:text-white border border-brand-red/20 transition-all">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="9" class="py-8 text-center text-white/40 text-sm">Belum ada data anggota silsilah keluarga.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                </form>
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

    <!-- JS untuk Bulk Delete dan Modal -->
    <script>
        // Modal Logic
        function showConfirm(event, url, message, isForm = false, formId = null) {
            event.preventDefault();
            const modal = document.getElementById('confirmModal');
            const card = document.getElementById('confirmModalCard');
            
            document.getElementById('confirmMessage').innerText = message;
            
            const actionBtn = document.getElementById('confirmActionBtn');
            
            actionBtn.onclick = function() {
                if (isForm && formId) {
                    document.getElementById(formId).submit();
                } else {
                    window.location.href = url;
                }
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

        // Bulk Delete Checkbox Logic
        document.addEventListener('DOMContentLoaded', function() {
            const selectAllCheckbox = document.getElementById('selectAllCheckbox');
            const memberCheckboxes = document.querySelectorAll('.member-checkbox');
            const bulkActionBar = document.getElementById('bulkActionBar');
            const selectedCount = document.getElementById('selectedCount');

            function updateBulkActionBar() {
                const checkedCount = document.querySelectorAll('.member-checkbox:checked').length;
                selectedCount.textContent = checkedCount;
                if (checkedCount > 0) {
                    bulkActionBar.classList.remove('hidden');
                } else {
                    bulkActionBar.classList.add('hidden');
                }
                
                // Update "Select All" checkbox state
                if (checkedCount === memberCheckboxes.length && memberCheckboxes.length > 0) {
                    selectAllCheckbox.checked = true;
                } else {
                    selectAllCheckbox.checked = false;
                }
            }

            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', function() {
                    memberCheckboxes.forEach(cb => {
                        cb.checked = selectAllCheckbox.checked;
                    });
                    updateBulkActionBar();
                });
            }

            memberCheckboxes.forEach(cb => {
                cb.addEventListener('change', updateBulkActionBar);
            });
        });
    </script>
</body>
</html>
