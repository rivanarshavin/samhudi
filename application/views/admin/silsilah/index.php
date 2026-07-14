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
<body class="bg-teal-950 text-white font-body min-h-screen flex">

    <!-- Sidebar -->
    <?php $this->load->view('admin/sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-y-auto">
        
        <!-- Header -->
        <?php $this->load->view('admin/header'); ?>

        <!-- Content Area -->
        <div class="p-8 space-y-8">
            
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
                <a href="<?= base_url('admin/silsilah_add') ?>" class="flex items-center justify-center gap-2 bg-gradient-to-r from-brand-medium to-brand-dark hover:from-brand-medium/90 hover:to-brand-dark/90 border border-brand-medium text-white px-5 py-3 rounded-xl text-sm font-bold shadow-md transition-all">
                    <i class="bi bi-person-plus-fill"></i>
                    <span>Tambah Anggota</span>
                </a>
            </div>

            <!-- Filters & Search -->
            <div class="bg-brand-dark/20 border border-brand-medium/20 rounded-2xl p-6 shadow-sm">
                <form method="GET" action="<?= base_url('admin/silsilah') ?>" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    
                    <!-- Search Input -->
                    <div class="relative md:col-span-2">
                        <i class="bi bi-search absolute left-4 top-3.5 text-white/40"></i>
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari nama, email, pekerjaan..." class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 pl-11 pr-4 text-sm text-white placeholder-white/40 focus:outline-none focus:border-brand-medium transition-all">
                    </div>

                    <!-- Gender Filter -->
                    <div>
                        <select name="gender" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            <option value="">Semua Jenis Kelamin</option>
                            <option value="L" <?= $gender == 'L' ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="P" <?= $gender == 'P' ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </div>

                    <!-- Generasi Filter -->
                    <div>
                        <select name="generasi" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            <option value="">Semua Generasi</option>
                            <?php for ($i = 1; $i <= $max_generasi; $i++): ?>
                                <option value="<?= $i ?>" <?= (string)$generasi === (string)$i ? 'selected' : '' ?>>Gen-<?= $i ?></option>
                            <?php endfor; ?>
                        </select>
                    </div>

                    <!-- Status Filter -->
                    <div class="flex gap-2">
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
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#4D6B67]/20">
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Anggota</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">L/P</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Generasi</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Orang Tua</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Kontak / TTL</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Status</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#4D6B67]/10">
                            <?php if (!empty($members)): ?>
                                <?php foreach ($members as $member): ?>
                                    <tr>
                                        <!-- Photo & Name -->
                                        <td class="py-4 flex items-center gap-3">
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
                                        <td class="py-4 text-sm">
                                            <span class="px-2 py-1 rounded text-xs font-bold <?= $member['gender'] == 'L' ? 'bg-blue-500/20 text-blue-300' : 'bg-pink-500/20 text-pink-300' ?>">
                                                <?= $member['gender'] == 'L' ? 'L' : 'P' ?>
                                            </span>
                                        </td>

                                        <!-- Generasi -->
                                        <td class="py-4 text-sm text-brand-light">
                                            Gen-<?= $member['generasi'] ?? '-' ?>
                                        </td>

                                        <!-- Parents -->
                                        <td class="py-4 text-sm">
                                            <div class="text-xs text-white/80">
                                                <strong>Ayah:</strong> <?= htmlspecialchars($member['father_name'] ?: '-') ?><br>
                                                <strong>Ibu:</strong> <?= htmlspecialchars($member['mother_name'] ?: '-') ?>
                                            </div>
                                        </td>

                                        <!-- Contact / TTL -->
                                        <td class="py-4 text-sm">
                                            <div class="text-xs text-white/80">
                                                <i class="bi bi-geo-alt-fill text-white/40"></i> <?= htmlspecialchars($member['birth_place'] ?: '-') ?>, <?= $member['birth_date'] ? date('j M Y', strtotime($member['birth_date'])) : '-' ?><br>
                                                <i class="bi bi-telephone-fill text-white/40"></i> <?= htmlspecialchars($member['phone'] ?: '-') ?>
                                            </div>
                                        </td>

                                        <!-- Status Hidup / Wafat -->
                                        <td class="py-4 text-sm">
                                            <?php if ($member['is_alive'] == 1): ?>
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-500/30">Hidup</span>
                                            <?php else: ?>
                                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-red-500/20 text-red-300 border border-red-500/30">Wafat (<?= $member['death_date'] ? date('Y', strtotime($member['death_date'])) : '?' ?>)</span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Actions -->
                                        <td class="py-4 text-right space-x-2">
                                            <a href="<?= base_url('admin/silsilah_edit/' . $member['id']) ?>" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-500/10 text-yellow-400 hover:bg-yellow-500 hover:text-black border border-yellow-500/20 transition-all">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                            <a href="<?= base_url('admin/silsilah_delete/' . $member['id']) ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini? Data relasi silsilah anak juga akan terputus.')" class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-red/10 text-brand-red hover:bg-brand-red hover:text-white border border-brand-red/20 transition-all">
                                                <i class="bi bi-trash-fill"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-white/40 text-sm">Belum ada data anggota silsilah keluarga.</td>
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
