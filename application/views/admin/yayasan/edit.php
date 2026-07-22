<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Calon Yayasan | Admin Panel</title>
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
</head>
<body class="bg-teal-950 text-white font-body h-screen flex overflow-hidden">

    <!-- Sidebar -->
    <?php $this->load->view('admin/sidebar'); ?>

    <!-- Main Content -->
    <main class="flex-1 flex flex-col overflow-y-auto overflow-x-hidden">
        
        <!-- Header -->
        <?php $this->load->view('admin/header'); ?>

        <!-- Content Area -->
        <div class="p-4 md:p-8 max-w-2xl mx-auto w-full space-y-6">
            
            <!-- Back Link -->
            <a href="<?= base_url('admin/yayasan') ?>" class="inline-flex items-center gap-2 text-brand-light hover:text-white font-semibold transition-colors">
                <i class="bi bi-arrow-left"></i>
                Kembali ke Daftar Calon
            </a>

            <!-- Form Card -->
            <div class="bg-gradient-to-b from-brand-dark/20 to-brand-dark/5 border border-brand-medium/20 rounded-2xl p-6 md:p-8 shadow-lg">
                <h2 class="font-display font-extrabold text-xl text-white mb-2">Edit Calon Yayasan</h2>
                <p class="text-brand-light/70 text-xs mb-6">Ubah data informasi calon ketua yayasan beserta status persetujuan dan jumlah suaranya.</p>

                <form action="<?= base_url('admin/yayasan/edit/' . $candidate['id']) ?>" method="POST" class="space-y-5">
                    
                    <!-- Candidate Name -->
                    <div>
                        <label class="block text-xs font-semibold text-white/70 uppercase tracking-wider mb-2">Nama Calon Ketua</label>
                        <input type="text" name="candidate_name" value="<?= htmlspecialchars($candidate['candidate_name']) ?>" required
                               class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                    </div>

                    <!-- Nominator Name -->
                    <div>
                        <label class="block text-xs font-semibold text-white/70 uppercase tracking-wider mb-2">Nama Pencalon / Yang Mencalonkan</label>
                        <input type="text" name="nominator_name" value="<?= htmlspecialchars($candidate['nominator_name']) ?>" required
                               class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                    </div>

                    <!-- Ancestor Name -->
                    <div>
                        <label class="block text-xs font-semibold text-white/70 uppercase tracking-wider mb-2">Undayan / Buyut</label>
                        <input type="text" name="ancestor_name" value="<?= htmlspecialchars($candidate['ancestor_name']) ?>" required
                               class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                    </div>

                    <!-- Votes Count -->
                    <div>
                        <label class="block text-xs font-semibold text-white/70 uppercase tracking-wider mb-2">Jumlah Suara (Votes)</label>
                        <input type="number" name="votes_count" value="<?= $candidate['votes_count'] ?>" required min="0"
                               class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-xs font-semibold text-white/70 uppercase tracking-wider mb-2">Status Persetujuan</label>
                        <select name="status" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            <option value="pending" <?= $candidate['status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="approved" <?= $candidate['status'] == 'approved' ? 'selected' : '' ?>>Approved</option>
                            <option value="rejected" <?= $candidate['status'] == 'rejected' ? 'selected' : '' ?>>Rejected</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-semibold text-white/70 uppercase tracking-wider mb-2">Jabatan / Peran Calon</label>
                        <?php
                        $desc_val = trim($candidate['description'] ?? '');
                        $standard_roles = ['Ketua', 'Bendahara', 'Sekretaris'];
                        $is_custom = !empty($desc_val) && !in_array($desc_val, $standard_roles);
                        ?>
                        <select name="description" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            <option value="Ketua"      <?= $desc_val === 'Ketua'      ? 'selected' : '' ?>>Ketua</option>
                            <option value="Bendahara"  <?= $desc_val === 'Bendahara'  ? 'selected' : '' ?>>Bendahara</option>
                            <option value="Sekretaris" <?= $desc_val === 'Sekretaris' ? 'selected' : '' ?>>Sekretaris</option>
                            <?php if ($is_custom): ?>
                                <option value="<?= htmlspecialchars($desc_val) ?>" selected><?= htmlspecialchars($desc_val) ?> (data lama)</option>
                            <?php endif; ?>
                        </select>
                        <p class="text-[10px] text-white/30 mt-1.5">Pilih jabatan yang sesuai. Data lama dengan keterangan bebas akan tetap tampil apa adanya.</p>
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-4 border-t border-[#4D6B67]/20 flex justify-end gap-3">
                        <a href="<?= base_url('admin/yayasan') ?>" 
                           class="px-5 py-2.5 rounded-xl border border-[#4D6B67]/30 text-white/80 hover:bg-white/5 transition-all text-sm font-semibold">
                            Batal
                        </a>
                        <button type="submit" 
                                class="px-5 py-2.5 rounded-xl bg-brand-medium hover:bg-brand-medium/90 border border-brand-medium text-white transition-all text-sm font-bold shadow-md">
                            Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </main>
</body>
</html>
