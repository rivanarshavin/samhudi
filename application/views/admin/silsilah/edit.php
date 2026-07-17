<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Anggota Silsilah | Admin Panel</title>
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
        <div class="p-8 max-w-4xl mx-auto w-full space-y-8">
            
            <!-- Breadcrumbs -->
            <div class="flex items-center gap-2 text-xs text-brand-light/60">
                <a href="<?= base_url('admin/silsilah') ?>" class="hover:text-white transition-all">Kelola Silsilah</a>
                <i class="bi bi-chevron-right"></i>
                <span class="text-white font-medium">Edit Anggota</span>
            </div>

            <!-- Title -->
            <div>
                <h2 class="font-display font-extrabold text-2xl text-white">Edit Anggota Keluarga</h2>
                <p class="text-brand-light/70 text-xs mt-1">Ubah informasi data diri dan silsilah anggota keluarga.</p>
            </div>

            <!-- Form Card -->
            <div class="bg-gradient-to-b from-brand-dark/20 to-brand-dark/5 border border-brand-medium/20 rounded-2xl p-8 shadow-lg">
                
                <?php if (validation_errors()): ?>
                    <div class="bg-brand-red/20 border border-brand-red text-red-300 px-6 py-4 rounded-xl mb-6 text-sm">
                        <?= validation_errors() ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="bg-brand-red/20 border border-brand-red text-red-300 px-6 py-4 rounded-xl mb-6 text-sm">
                        <?= $this->session->flashdata('error') ?>
                    </div>
                <?php endif; ?>

                <?= form_open_multipart('admin/silsilah_edit/' . $member['id'], ['class' => 'space-y-6']) ?>

                    <!-- Section 1: Data Diri Utama -->
                    <div class="border-b border-[#4D6B67]/20 pb-6">
                        <h3 class="font-display font-bold text-base text-white mb-4"><i class="bi bi-person-fill text-brand-medium mr-2"></i>Data Diri Utama</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Lengkap -->
                            <div>
                                <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Nama Lengkap <span class="text-brand-red">*</span></label>
                                <input type="text" name="full_name" required value="<?= set_value('full_name', $member['full_name']) ?>" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            </div>

                            <!-- Jenis Kelamin -->
                            <div>
                                <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Jenis Kelamin <span class="text-brand-red">*</span></label>
                                <select name="gender" required class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="L" <?= set_select('gender', 'L', $member['gender'] == 'L') ?>>Laki-laki</option>
                                    <option value="P" <?= set_select('gender', 'P', $member['gender'] == 'P') ?>>Perempuan</option>
                                </select>
                            </div>

                            <!-- Generasi -->
                            <div>
                                <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Generasi</label>
                                <input type="number" name="generasi" min="1" max="10" placeholder="Kosongkan untuk otomatis" value="<?= set_value('generasi', $member['generasi']) ?>" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            </div>

                            <!-- Hubungkan Akun Pengguna -->
                            <div>
                                <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Hubungkan Akun User (Opsional)</label>
                                <select name="user_id" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                                    <option value="">Bukan Akun Terdaftar</option>
                                    <?php foreach ($unlinked_users as $usr): ?>
                                        <option value="<?= $usr['id'] ?>" <?= set_select('user_id', $usr['id'], $member['user_id'] == $usr['id']) ?>><?= htmlspecialchars($usr['full_name']) ?> (<?= htmlspecialchars($usr['email']) ?>)</option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="text-[10px] text-white/40 mt-1.5">Hubungkan jika anggota keluarga ini sudah mendaftar akun di web.</p>
                            </div>

                            <!-- Foto Profil -->
                            <div class="flex items-center gap-4">
                                <div class="flex-1">
                                    <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Foto Profil</label>
                                    <input type="file" name="photo" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-2 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all file:mr-4 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-brand-medium file:text-white hover:file:bg-brand-medium/90 cursor-pointer">
                                </div>
                                <?php if ($member['photo'] && file_exists('./' . $member['photo'])): ?>
                                    <img src="<?= base_url($member['photo']) ?>" alt="Old Photo" class="w-12 h-12 rounded-xl object-cover border border-[#4D6B67]/30 mt-5">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2: Hubungan Keluarga (Silsilah) -->
                    <div class="border-b border-[#4D6B67]/20 pb-6">
                        <h3 class="font-display font-bold text-base text-white mb-4"><i class="bi bi-diagram-3-fill text-brand-medium mr-2"></i>Hubungan Keluarga (Silsilah)</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Ayah -->
                            <div>
                                <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Ayah</label>
                                <select name="father_id" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                                    <option value="">Pilih Ayah (Jika ada)</option>
                                    <?php foreach ($fathers as $fat): ?>
                                        <?php if ($fat['id'] != $member['id']): // Avoid self-parenting ?>
                                            <option value="<?= $fat['id'] ?>" <?= set_select('father_id', $fat['id'], $member['father_id'] == $fat['id']) ?>><?= htmlspecialchars($fat['full_name']) ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <!-- Ibu -->
                            <div>
                                <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Ibu</label>
                                <select name="mother_id" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                                    <option value="">Pilih Ibu (Jika ada)</option>
                                    <?php foreach ($mothers as $mot): ?>
                                        <?php if ($mot['id'] != $member['id']): // Avoid self-parenting ?>
                                            <option value="<?= $mot['id'] ?>" <?= set_select('mother_id', $mot['id'], $member['mother_id'] == $mot['id']) ?>><?= htmlspecialchars($mot['full_name']) ?></option>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Section 2.5: Data Pasangan -->
                    <div class="border-b border-[#4D6B67]/20 pb-6">
                        <h3 class="font-display font-bold text-base text-white mb-4"><i class="bi bi-heart-fill text-brand-medium mr-2"></i>Data Pasangan (Suami / Istri)</h3>
                        <p class="text-xs text-white/50 mb-4">Centang kotak pada nama untuk menjadikan anggota tersebut sebagai pasangan.</p>
                        
                        <div>
                            <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Pilih Pasangan</label>
                            <div class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl max-h-48 overflow-y-auto custom-scrollbar p-2">
                                <?php foreach ($spouse_options as $spouse): ?>
                                    <?php $selected = in_array($spouse['id'], $current_spouses) ? 'checked' : ''; ?>
                                    <label class="flex items-center gap-3 p-3 hover:bg-[#4D6B67]/20 rounded-lg cursor-pointer transition-all border border-transparent hover:border-[#4D6B67]/30 group">
                                        <input type="checkbox" name="spouses[]" value="<?= $spouse['id'] ?>" <?= $selected ?> class="w-4 h-4 text-brand-medium bg-[#15201E] border-[#4D6B67]/50 rounded focus:ring-brand-medium/50 focus:ring-2 cursor-pointer transition-all">
                                        <span class="text-sm text-white/80 group-hover:text-white transition-colors font-medium"><?= htmlspecialchars($spouse['full_name']) ?></span>
                                    </label>
                                <?php endforeach; ?>
                                <?php if (empty($spouse_options)): ?>
                                    <p class="text-sm text-white/40 p-4 text-center italic">Tidak ada kandidat pasangan yang tersedia.</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <!-- Section 3: Informasi Kelahiran & Status -->
                    <div class="border-b border-[#4D6B67]/20 pb-6">
                        <h3 class="font-display font-bold text-base text-white mb-4"><i class="bi bi-calendar-event-fill text-brand-medium mr-2"></i>Informasi Kelahiran & Status</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Tempat Lahir -->
                            <div>
                                <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Tempat Lahir</label>
                                <input type="text" name="birth_place" value="<?= set_value('birth_place', $member['birth_place']) ?>" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            </div>

                            <!-- Tanggal Lahir -->
                            <div>
                                <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Tanggal Lahir</label>
                                <input type="date" name="birth_date" value="<?= set_value('birth_date', $member['birth_date']) ?>" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            </div>

                            <!-- Status Hidup -->
                            <div>
                                <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Status Hidup</label>
                                <select name="is_alive" id="is_alive" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                                    <option value="1" <?= set_select('is_alive', '1', $member['is_alive'] == 1) ?>>Masih Hidup</option>
                                    <option value="0" <?= set_select('is_alive', '0', $member['is_alive'] == 0) ?>>Sudah Wafat</option>
                                </select>
                            </div>

                            <!-- Tanggal Wafat -->
                            <div id="death_date_container" class="hidden md:col-span-3">
                                <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Tanggal Wafat</label>
                                <input type="date" name="death_date" value="<?= set_value('death_date', $member['death_date']) ?>" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            </div>
                        </div>
                    </div>

                    <!-- Section 4: Kontak & Informasi Tambahan -->
                    <div class="pb-4">
                        <h3 class="font-display font-bold text-base text-white mb-4"><i class="bi bi-info-circle-fill text-brand-medium mr-2"></i>Kontak & Informasi Lainnya</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Nomor HP -->
                            <div>
                                <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Nomor Telepon/WA</label>
                                <input type="text" name="phone" value="<?= set_value('phone', $member['phone']) ?>" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            </div>

                            <!-- Email -->
                            <div>
                                <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Email</label>
                                <input type="email" name="email" value="<?= set_value('email', $member['email']) ?>" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            </div>

                            <!-- Pekerjaan -->
                            <div>
                                <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Pekerjaan</label>
                                <input type="text" name="occupation" value="<?= set_value('occupation', $member['occupation']) ?>" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all">
                            </div>

                            <!-- Alamat Lengkap -->
                            <div class="md:col-span-3">
                                <label class="block text-xs font-semibold text-white/70 mb-2 uppercase tracking-wide">Alamat Lengkap</label>
                                <textarea name="address" rows="3" class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 px-4 text-sm text-white focus:outline-none focus:border-brand-medium transition-all"><?= set_value('address', $member['address']) ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Hidden Fields -->
                    <input type="hidden" name="family_id" value="<?= $member['family_id'] ?>">

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-[#4D6B67]/20">
                        <a href="<?= base_url('admin/silsilah') ?>" class="bg-transparent border border-white/20 hover:border-white/40 text-[#E3E3E3] hover:text-white px-5 py-3 rounded-xl text-sm font-semibold transition-all">Batal</a>
                        <button type="submit" class="bg-brand-medium hover:bg-brand-medium/90 border border-brand-medium text-white px-6 py-3 rounded-xl text-sm font-bold shadow-md transition-all">Simpan Perubahan</button>
                    </div>

                <?= form_close() ?>

            </div>

        </div>

    </main>

    <!-- Custom Script for dynamic inputs -->
    <script>
        const isAliveSelect = document.getElementById('is_alive');
        const deathDateContainer = document.getElementById('death_date_container');

        function toggleDeathDate() {
            if (isAliveSelect.value === '0') {
                deathDateContainer.classList.remove('hidden');
            } else {
                deathDateContainer.classList.add('hidden');
            }
        }

        isAliveSelect.addEventListener('change', toggleDeathDate);
        // Run on load
        toggleDeathDate();
    </script>

</body>
</html>
