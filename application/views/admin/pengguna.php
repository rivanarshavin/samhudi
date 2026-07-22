<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pengguna | Admin Keluarga H.M Samhudi</title>
    <link rel="icon" type="image/jpeg" href="<?= base_url('assets/favicon.jpeg') ?>">
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
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #15201E; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }
        .modal-backdrop {
            position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 9999;
            display: none; align-items: center; justify-content: center;
            padding: 16px;
        }
        .modal-backdrop.open { display: flex; }
        .modal-box {
            background: #1D2A27; border: 1px solid #324742; border-radius: 20px;
            width: 100%; max-width: 640px; padding: 28px; max-height: 90vh;
            overflow-y: auto; transform: scale(0.95); transition: transform 0.25s;
        }
        .modal-backdrop.open .modal-box { transform: scale(1); }
    </style>
</head>
<body class="bg-teal-950 text-white font-body min-h-screen flex">

    <!-- ================= SIDEBAR ================= -->
    <?php $this->load->view('admin/sidebar'); ?>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 flex flex-col overflow-y-auto">
        
        <!-- Header -->
        <?php $this->load->view('admin/header'); ?>

        <!-- Body Content -->
        <div class="p-4 md:p-8 space-y-6">

            <!-- Title & Feedback Alert -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight">Kelola Pengguna</h1>
                    <p class="text-xs md:text-sm text-teal-400 mt-1">Daftar akun pengguna terdaftar dan proses persetujuan akun baru.</p>
                </div>
            </div>

            <?php if ($this->session->flashdata('success')): ?>
            <div class="bg-green-500/20 border border-green-500/40 text-green-200 px-5 py-3.5 rounded-xl text-sm shadow-md flex items-center gap-3">
                <i class="bi bi-check-circle-fill text-lg text-green-400"></i>
                <span><?= $this->session->flashdata('success') ?></span>
            </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')): ?>
            <div class="bg-red-500/20 border border-red-500/40 text-red-200 px-5 py-3.5 rounded-xl text-sm shadow-md flex items-center gap-3">
                <i class="bi bi-exclamation-triangle-fill text-lg text-red-400"></i>
                <span><?= $this->session->flashdata('error') ?></span>
            </div>
            <?php endif; ?>

            <!-- Filter Card -->
            <div class="bg-teal-900/60 border border-teal-800 rounded-2xl p-6 shadow-lg">
                <form method="GET" action="<?= base_url('admin/pengguna') ?>" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search input -->
                    <div class="space-y-1.5 col-span-1 md:col-span-2">
                        <label class="text-xs font-bold text-teal-400 uppercase tracking-wider">Cari Pengguna</label>
                        <div class="relative">
                            <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" 
                                   placeholder="Nama, email, atau no. telepon..." 
                                   class="w-full bg-teal-800 border border-teal-700 rounded-xl pl-10 pr-4 py-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-gold-500">
                            <i class="bi bi-search absolute left-3.5 top-3.5 text-teal-400"></i>
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="space-y-1.5">
                        <label class="text-xs font-bold text-teal-400 uppercase tracking-wider">Status Akun</label>
                        <select name="status" class="w-full bg-teal-800 border border-teal-700 rounded-xl px-4 py-3 text-white text-sm focus:outline-none focus:ring-2 focus:ring-gold-500">
                            <option value="">Semua Status</option>
                            <option value="active" <?= (isset($status) && $status === 'active') ? 'selected' : '' ?>>Aktif / Disetujui</option>
                            <option value="inactive" <?= (isset($status) && $status === 'inactive') ? 'selected' : '' ?>>Menunggu Verifikasi / Nonaktif</option>
                        </select>
                    </div>

                    <!-- Filter buttons -->
                    <div class="flex items-end gap-2.5">
                        <button type="submit" class="flex-1 bg-white text-teal-950 hover:bg-gray-100 font-display font-bold text-sm rounded-xl py-3 shadow-md transition-all">
                            Filter
                        </button>
                        <a href="<?= base_url('admin/pengguna') ?>" class="bg-teal-800 hover:bg-teal-700 text-white font-display font-semibold text-sm rounded-xl px-4 py-3 border border-teal-700 transition-all flex items-center justify-center">
                            <i class="bi bi-arrow-clockwise text-base"></i>
                        </a>
                    </div>
                </form>
            </div>

            <!-- Users Table Card -->
            <div class="bg-teal-900/40 border border-teal-850 rounded-2xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-teal-900/60 border-b border-teal-800">
                                <th class="p-5 text-xs font-bold text-teal-400 uppercase tracking-wider">Nama Lengkap</th>
                                <th class="p-5 text-xs font-bold text-teal-400 uppercase tracking-wider">Kontak</th>
                                <th class="p-5 text-xs font-bold text-teal-400 uppercase tracking-wider">Role</th>
                                <th class="p-5 text-xs font-bold text-teal-400 uppercase tracking-wider">Status</th>
                                <th class="p-5 text-xs font-bold text-teal-400 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-teal-900/50">
                            <?php if (!empty($users)): ?>
                                <?php foreach ($users as $user): ?>
                                    <tr class="hover:bg-teal-900/20 transition-colors">
                                        <!-- Avatar & Name -->
                                        <td class="p-5">
                                            <div class="flex items-center gap-3.5">
                                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-800 to-teal-700 flex items-center justify-center text-gold-400 font-bold border border-teal-700">
                                                    <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                                                </div>
                                                <div>
                                                    <span class="font-display font-bold text-white block text-sm"><?= htmlspecialchars($user['full_name']) ?></span>
                                                    <span class="text-xs text-white/40">ID: #<?= $user['id'] ?></span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Contact details -->
                                        <td class="p-5">
                                            <div class="space-y-0.5">
                                                <div class="flex items-center gap-2 text-xs text-white/80">
                                                    <i class="bi bi-envelope text-teal-400"></i>
                                                    <span><?= htmlspecialchars($user['email']) ?></span>
                                                </div>
                                                <div class="flex items-center gap-2 text-xs text-white/60">
                                                    <i class="bi bi-telephone text-teal-500"></i>
                                                    <span><?= htmlspecialchars($user['phone']) ?></span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Role -->
                                        <td class="p-5 text-sm">
                                            <span class="px-2.5 py-1 rounded-md text-xs font-semibold uppercase tracking-wider <?= ($user['role'] === 'admin' || $user['role'] === 'super_admin') ? 'bg-red-500/10 text-red-400 border border-red-500/20' : 'bg-teal-800/50 text-teal-300 border border-teal-700/50' ?>">
                                                <?= htmlspecialchars($user['role']) ?>
                                            </span>
                                        </td>

                                        <!-- Status -->
                                        <td class="p-5 text-sm">
                                            <?php if ($user['status'] === 'active'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-500/10 text-green-400 border border-green-500/25">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                                    Aktif
                                                </span>
                                            <?php elseif ((int)$user['is_verified'] === 1): ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/25">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                                    Menunggu Verifikasi
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-white/5 text-white/40 border border-white/10">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-white/30"></span>
                                                    Belum Verifikasi OTP
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                         <!-- Actions -->
                                         <td class="p-5 text-center">
                                             <div class="flex items-center justify-center gap-2">
                                                 <?php if ($user['status'] !== 'active'): ?>
                                                     <button onclick="showConfirm('<?= base_url('admin/pengguna_approve/' . $user['id']) ?>', 'Apakah Anda yakin ingin menyetujui pendaftaran akun dari <?= htmlspecialchars($user['full_name']) ?>?', 'Setujui Pengguna', 'success')" 
                                                        class="px-3.5 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-display text-xs font-bold shadow-md transition-all active:scale-95 flex items-center gap-1">
                                                         <i class="bi bi-check-lg text-sm"></i>
                                                         Setujui
                                                     </button>
                                                 <?php endif; ?>
                                                 
                                                 <?php if ($user['role'] !== 'super_admin'): ?>
                                                     <button onclick="showConfirm('<?= base_url('admin/pengguna_delete/' . $user['id']) ?>', 'Apakah Anda yakin ingin menghapus akun milik <?= htmlspecialchars($user['full_name']) ?>? Tindakan ini tidak dapat dibatalkan.', 'Hapus Pengguna', 'danger')" 
                                                        class="px-3.5 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg font-display text-xs font-semibold border border-red-500/20 transition-all flex items-center gap-1">
                                                         <i class="bi bi-trash text-sm"></i>
                                                         Hapus
                                                     </button>
                                                 <?php endif; ?>
                                             </div>
                                         </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="p-10 text-center text-white/40 text-sm">
                                        <i class="bi bi-people text-3xl block mb-2 opacity-50"></i>
                                        Tidak ada data pengguna ditemukan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>

    </main>

    <!-- ================= MODAL: Konfirmasi Kustom ================= -->
    <div id="confirmModal" class="modal-backdrop" onclick="closeConfirmModal()">
        <div class="modal-box max-w-sm" onclick="event.stopPropagation()">
            <div class="text-center space-y-4">
                <div class="w-12 h-12 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center mx-auto text-2xl" id="confirmIcon">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <div>
                    <h3 class="font-display font-bold text-lg text-white" id="confirmTitle">Konfirmasi</h3>
                    <p class="text-xs text-white/60 mt-1.5" id="confirmMessage">Apakah Anda yakin ingin melanjutkan tindakan ini?</p>
                </div>
                <div class="flex justify-center gap-3 pt-2">
                    <button type="button" onclick="closeConfirmModal()" class="px-4 py-2 text-sm text-white/60 hover:text-white transition-colors bg-teal-900/40 border border-teal-800 rounded-xl">Batal</button>
                    <a id="confirmBtn" href="#" class="px-6 py-2 bg-gold-400 hover:bg-gold-500 text-teal-950 font-display font-bold rounded-xl text-sm shadow-lg transition-all active:scale-95">Ya, Setuju</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            document.getElementById(id).classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
            document.body.style.overflow = '';
        }
        function closeConfirmModal() {
            closeModal('confirmModal');
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                const modal = document.getElementById('confirmModal');
                if (modal) modal.classList.remove('open');
                document.body.style.overflow = '';
            }
        });

        // --- Custom confirmation helpers ---
        function showConfirm(url, message, title = 'Konfirmasi', type = 'warning') {
            document.getElementById('confirmTitle').textContent = title;
            document.getElementById('confirmMessage').textContent = message;
            
            const confirmBtn = document.getElementById('confirmBtn');
            confirmBtn.href = url;
            
            const iconEl = document.getElementById('confirmIcon');
            if (type === 'danger') {
                iconEl.className = "w-12 h-12 rounded-full bg-red-500/10 text-red-400 border border-red-500/20 flex items-center justify-center mx-auto text-2xl";
                iconEl.innerHTML = '<i class="bi bi-trash-fill"></i>';
                confirmBtn.className = "px-6 py-2 bg-red-650 hover:bg-red-700 text-white font-display font-bold rounded-xl text-sm shadow-lg transition-all active:scale-95";
            } else if (type === 'success') {
                iconEl.className = "w-12 h-12 rounded-full bg-green-500/10 text-green-400 border border-green-500/20 flex items-center justify-center mx-auto text-2xl";
                iconEl.innerHTML = '<i class="bi bi-check-circle-fill"></i>';
                confirmBtn.className = "px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-display font-bold rounded-xl text-sm shadow-lg transition-all active:scale-95";
            } else {
                iconEl.className = "w-12 h-12 rounded-full bg-amber-500/10 text-amber-400 border border-amber-500/20 flex items-center justify-center mx-auto text-2xl";
                iconEl.innerHTML = '<i class="bi bi-exclamation-triangle-fill"></i>';
                confirmBtn.className = "px-6 py-2 bg-gold-400 hover:bg-gold-500 text-teal-950 font-display font-bold rounded-xl text-sm shadow-lg transition-all active:scale-95";
            }
            
            openModal('confirmModal');
        }
    </script>

</body>
</html>
