<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pekerja | Admin Panel</title>
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
        <div class="p-8 space-y-8 max-w-4xl mx-auto w-full">

            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="bg-red-500/20 border border-red-500 text-red-300 px-6 py-4 rounded-xl flex items-center gap-3">
                    <i class="bi bi-exclamation-circle-fill text-lg"></i>
                    <span class="text-sm font-semibold"><?= $this->session->flashdata('error') ?></span>
                </div>
            <?php endif; ?>

            <!-- Title -->
            <div>
                <a href="<?= base_url('admin/pekerja') ?>" class="inline-flex items-center gap-2 text-brand-light/60 hover:text-white transition-colors text-sm mb-4">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Pekerja
                </a>
                <h2 class="font-display font-extrabold text-2xl text-white">Edit Profil Pekerja</h2>
                <p class="text-brand-light/70 text-xs mt-1">Ubah data untuk "<?= htmlspecialchars($pekerja['full_name']) ?>"</p>
            </div>

            <!-- Form Card -->
            <div class="bg-gradient-to-b from-brand-dark/20 to-brand-dark/5 border border-brand-medium/20 rounded-2xl p-8 shadow-lg">
                <?= form_open_multipart('admin/pekerja_edit/' . $pekerja['id'], ['class' => 'space-y-6']) ?>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tanggal Lahir -->
                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2">Tanggal Lahir</label>
                            <input type="date" name="birth_date" value="<?= htmlspecialchars($pekerja['birth_date'] ?? '') ?>"
                                   class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                        </div>

                        <!-- Pekerjaan yang Diharapkan -->
                        <div>
                            <label class="block text-sm font-semibold text-white/80 mb-2">Pekerjaan yang Diharapkan <span class="text-red-400">*</span></label>
                            <input type="text" name="desired_job" value="<?= htmlspecialchars($pekerja['desired_job'] ?? '') ?>" required
                                   class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                        </div>
                    </div>

                    <!-- Riwayat Pekerjaan -->
                    <div>
                        <label class="block text-sm font-semibold text-white/80 mb-2">Riwayat Pekerjaan</label>
                        <textarea name="work_history" rows="4"
                                  class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-medium transition-all"><?= htmlspecialchars($pekerja['work_history'] ?? '') ?></textarea>
                    </div>

                    <!-- Tentang Saya -->
                    <div>
                        <label class="block text-sm font-semibold text-white/80 mb-2">Tentang Saya</label>
                        <textarea name="about" rows="4"
                                  class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-brand-medium transition-all"><?= htmlspecialchars($pekerja['about'] ?? '') ?></textarea>
                    </div>

                    <!-- Upload CV -->
                    <div>
                        <label class="block text-sm font-semibold text-white/80 mb-2">Upload CV Baru <span class="font-normal text-white/40">(opsional, akan menimpa CV lama jika ada)</span></label>
                        <?php if (!empty($pekerja['cv_path'])): ?>
                            <div class="mb-3">
                                <span class="text-xs text-brand-medium">CV Saat Ini: </span>
                                <a href="<?= base_url($pekerja['cv_path']) ?>" target="_blank" class="text-blue-400 hover:underline text-sm font-semibold">
                                    <i class="bi bi-file-earmark-text"></i> Lihat CV
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="relative group cursor-pointer">
                            <input type="file" name="cv_file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                                   onchange="document.getElementById('cv-file-name').textContent = this.files[0] ? this.files[0].name : 'Pilih file (PDF/DOC/IMG, Maks 2MB)'">
                            <div class="w-full bg-[#1A2824] border border-dashed border-[#4D6B67]/50 rounded-xl px-4 py-6 text-center group-hover:border-brand-medium transition-all flex flex-col items-center justify-center gap-2">
                                <i class="bi bi-cloud-arrow-up text-3xl text-brand-medium group-hover:text-brand-light transition-colors"></i>
                                <span id="cv-file-name" class="text-sm text-white/60 group-hover:text-white/90">
                                    Klik atau seret file CV ke sini (PDF/DOC/IMG, Maks 2MB)
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-[#4D6B67]/20 flex gap-4">
                        <button type="submit"
                                class="flex-1 bg-gradient-to-r from-brand-medium to-brand-dark hover:from-brand-medium/90 hover:to-brand-dark/90 border border-brand-medium text-white px-6 py-3.5 rounded-xl font-bold shadow-md shadow-brand-dark/20 transition-all text-center">
                            Simpan Perubahan
                        </button>
                        <a href="<?= base_url('admin/pekerja') ?>"
                           class="px-8 py-3.5 rounded-xl border border-brand-medium/30 text-white hover:bg-brand-medium/10 transition-all font-semibold text-center">
                            Batal
                        </a>
                    </div>

                <?= form_close() ?>
            </div>

        </div>

    </main>

</body>
</html>
