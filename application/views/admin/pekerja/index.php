<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Pekerja | Admin Panel</title>
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
                    <h2 class="font-display font-extrabold text-2xl text-white">Kelola Pekerja (Open to Work)</h2>
                    <p class="text-brand-light/70 text-xs mt-1">Kelola data keluarga yang sedang mencari pekerjaan.</p>
                </div>
            </div>

            <!-- Filter & Search -->
            <div class="bg-brand-dark/20 border border-brand-medium/20 rounded-2xl p-6 shadow-sm">
                <form method="GET" action="<?= base_url('admin/pekerja') ?>" class="flex gap-4">
                    <!-- Search -->
                    <div class="relative flex-1">
                        <i class="bi bi-search absolute left-4 top-3.5 text-white/40"></i>
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                               placeholder="Cari nama atau jenis pekerjaan..."
                               class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 pl-11 pr-4 text-sm text-white placeholder-white/40 focus:outline-none focus:border-brand-medium transition-all">
                    </div>

                    <!-- Filter Button -->
                    <div class="flex gap-2 w-32">
                        <button type="submit"
                                class="flex-1 bg-brand-medium hover:bg-brand-medium/90 border border-brand-medium text-white px-4 py-3 rounded-xl flex items-center justify-center gap-2 text-sm font-semibold transition-all">
                            <i class="bi bi-search"></i>
                            <span>Cari</span>
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
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Nama Pekerja</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Pekerjaan Diharapkan</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Tanggal Lahir</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">CV</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#4D6B67]/10">
                            <?php if (!empty($pekerja)): ?>
                                <?php foreach ($pekerja as $p): ?>
                                    <tr class="hover:bg-brand-dark/10 transition-colors">
                                        <td class="py-4 pr-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full overflow-hidden bg-brand-medium/40 border border-brand-medium/20 shrink-0">
                                                    <img src="<?= !empty($p['avatar']) ? base_url($p['avatar']) : base_url('assets/images/photo.png') ?>" alt="Avatar" class="w-full h-full object-cover">
                                                </div>
                                                <div class="min-w-0">
                                                    <div class="font-semibold text-white text-sm truncate">
                                                        <?= htmlspecialchars($p['full_name']) ?>
                                                    </div>
                                                    <div class="text-xs text-white/40 mt-0.5">
                                                        <?= htmlspecialchars($p['email']) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="py-4 pr-4">
                                            <span class="text-sm text-teal-300 font-semibold"><?= htmlspecialchars($p['desired_job']) ?></span>
                                        </td>
                                        
                                        <td class="py-4 text-sm text-white/60">
                                            <?= !empty($p['birth_date']) ? date('d M Y', strtotime($p['birth_date'])) : '-' ?>
                                        </td>
                                        
                                        <td class="py-4 text-sm text-white/60">
                                            <?php if (!empty($p['cv_path'])): ?>
                                                <a href="<?= base_url($p['cv_path']) ?>" target="_blank" class="text-blue-400 hover:underline flex items-center gap-1">
                                                    <i class="bi bi-file-earmark-text"></i> Lihat CV
                                                </a>
                                            <?php else: ?>
                                                -
                                            <?php endif; ?>
                                        </td>
                                        
                                        <td class="py-4 text-right space-x-1.5">
                                            <!-- Edit -->
                                            <a href="<?= base_url('admin/pekerja_edit/' . $p['id']) ?>"
                                               title="Edit"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-500/10 text-yellow-400 hover:bg-yellow-500 hover:text-black border border-yellow-500/20 transition-all">
                                                <i class="bi bi-pencil-square text-sm"></i>
                                            </a>
                                            <!-- Hapus -->
                                            <a href="<?= base_url('admin/pekerja_delete/' . $p['id']) ?>"
                                               onclick="return confirm('Apakah Anda yakin ingin menghapus profil pekerja ini? (Status user akan kembali normal)')"
                                               title="Hapus"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-red/10 text-brand-red hover:bg-brand-red hover:text-white border border-brand-red/20 transition-all">
                                                <i class="bi bi-trash-fill text-sm"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="py-16 text-center">
                                        <div class="flex flex-col items-center gap-3 text-white/30">
                                            <i class="bi bi-person-slash text-4xl"></i>
                                            <span class="text-sm">
                                                <?= (!empty($search)) ? 'Tidak ada pekerja yang cocok dengan pencarian.' : 'Belum ada anggota yang mendaftar Open to Work.' ?>
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
