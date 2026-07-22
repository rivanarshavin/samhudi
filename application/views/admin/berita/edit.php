<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Berita | Admin Panel</title>
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
    <style>
        * { font-family: 'Inter', sans-serif; }
        .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #15201E; }
        ::-webkit-scrollbar-thumb { background: rgba(255, 255, 255, 0.1); border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255, 255, 255, 0.2); }
        .form-input {
            width: 100%;
            background: #1A2824;
            border: 1px solid rgba(77, 107, 103, 0.3);
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            font-size: 0.875rem;
            color: white;
            transition: border-color 0.2s;
            outline: none;
        }
        .form-input:focus { border-color: #4D6B67; }
        .form-input::placeholder { color: rgba(255,255,255,0.3); }
        .form-label { display: block; font-size: 0.8rem; font-weight: 600; color: rgba(255,255,255,0.7); margin-bottom: 0.4rem; letter-spacing: 0.03em; }
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

            <!-- Breadcrumb & Title -->
            <div class="flex flex-col gap-2">
                <div class="flex items-center gap-2 text-xs text-white/40">
                    <a href="<?= base_url('admin') ?>" class="hover:text-white transition-colors">Dashboard</a>
                    <i class="bi bi-chevron-right"></i>
                    <a href="<?= base_url('admin/berita') ?>" class="hover:text-white transition-colors">Kelola Berita</a>
                    <i class="bi bi-chevron-right"></i>
                    <span class="text-white/60">Edit Berita</span>
                </div>
                <h2 class="font-display font-extrabold text-2xl text-white">Edit Berita</h2>
                <p class="text-brand-light/60 text-xs">Perbarui informasi berita di bawah ini.</p>
            </div>

            <!-- Form Card -->
            <div class="bg-gradient-to-b from-brand-dark/20 to-brand-dark/5 border border-brand-medium/20 rounded-2xl p-8 shadow-lg">

                <!-- Validation Errors / Upload Errors -->
                <?php if (validation_errors() || isset($upload_error)): ?>
                    <div class="bg-red-500/10 border border-red-500/30 text-red-300 px-5 py-4 rounded-xl mb-6 text-sm space-y-1">
                        <div class="font-bold flex items-center gap-2 mb-2">
                            <i class="bi bi-exclamation-triangle-fill"></i>
                            <span>Terdapat kesalahan:</span>
                        </div>
                        <?php if (validation_errors()) echo validation_errors('<div class="ml-5 list-disc">• ', '</div>'); ?>
                        <?php if (isset($upload_error)) echo '<div class="ml-5 list-disc">• ' . $upload_error . '</div>'; ?>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('admin/berita_edit/' . $news['id']) ?>" method="POST" enctype="multipart/form-data" class="space-y-6">

                    <!-- Judul Berita -->
                    <div>
                        <label class="form-label">Judul Berita <span class="text-red-400">*</span></label>
                        <input type="text" name="title" id="title"
                               value="<?= set_value('title', htmlspecialchars($news['title'])) ?>"
                               placeholder="Masukkan judul berita..."
                               class="form-input">
                    </div>



                    <!-- Thumbnail -->
                    <div>
                        <label class="form-label">
                            Thumbnail / Foto Berita
                            <span class="ml-2 text-white/30 font-normal">(kosongkan jika tidak ingin diubah)</span>
                        </label>

                        <!-- Thumbnail Saat Ini -->
                        <?php if (!empty($news['thumbnail']) && file_exists('./' . $news['thumbnail'])): ?>
                            <div class="mb-3 relative inline-block">
                                <img src="<?= base_url($news['thumbnail']) ?>" alt="Thumbnail saat ini"
                                     id="thumbnail-current"
                                     class="w-48 h-auto max-h-48 object-contain rounded-xl border border-brand-medium/30 bg-black/20">
                                <span class="absolute top-2 left-2 bg-black/60 text-white text-xs px-2 py-0.5 rounded-md">Saat ini</span>
                            </div>
                        <?php endif; ?>

                        <div id="thumbnail-dropzone"
                             class="border-2 border-dashed border-brand-medium/30 rounded-xl p-6 text-center cursor-pointer hover:border-brand-medium/60 transition-all"
                             onclick="document.getElementById('thumbnail').click()">
                            <div id="thumbnail-preview-wrapper" class="hidden mb-3">
                                <img id="thumbnail-preview" src="#" alt="Preview"
                                     class="w-full h-auto max-h-64 object-contain rounded-lg mx-auto">
                            </div>
                            <div id="thumbnail-placeholder">
                                <i class="bi bi-image text-2xl text-white/30"></i>
                                <p class="text-sm text-white/40 mt-1">Klik untuk ganti thumbnail</p>
                                <p class="text-xs text-white/20 mt-0.5">JPG, PNG, WEBP, GIF — Maks 2MB</p>
                            </div>
                        </div>
                        <input type="file" name="thumbnail" id="thumbnail" accept="image/*" class="hidden">
                    </div>

                    <!-- Isi Berita -->
                    <div>
                        <label class="form-label">
                            Isi Berita <span class="text-red-400">*</span>
                            <span class="ml-2 text-white/30 font-normal">(tulis isi lengkap berita di sini)</span>
                        </label>
                        <textarea name="content" id="content" rows="12"
                                  placeholder="Tulis isi berita secara lengkap di sini..."
                                  class="form-input resize-y" required><?= set_value('content', htmlspecialchars($news['content'] ?? '')) ?></textarea>
                        <p class="text-xs text-white/30 mt-1.5">Isi berita akan ditampilkan di halaman detail berita.</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="form-label">Status Publikasi</label>
                        <div class="flex gap-4">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <div class="relative">
                                    <input type="radio" name="status" value="draft"
                                           <?= (set_value('status', $news['status']) === 'draft') ? 'checked' : '' ?>
                                           class="sr-only peer">
                                    <div class="w-5 h-5 rounded-full border-2 border-brand-medium/40 peer-checked:border-yellow-400 peer-checked:bg-yellow-400/20 transition-all flex items-center justify-center">
                                        <div class="w-2.5 h-2.5 rounded-full bg-yellow-400 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-white">Draft</span>
                                    <p class="text-xs text-white/40">Belum ditampilkan ke publik</p>
                                </div>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <div class="relative">
                                    <input type="radio" name="status" value="publish"
                                           <?= (set_value('status', $news['status']) === 'publish') ? 'checked' : '' ?>
                                           class="sr-only peer">
                                    <div class="w-5 h-5 rounded-full border-2 border-brand-medium/40 peer-checked:border-emerald-400 peer-checked:bg-emerald-400/20 transition-all flex items-center justify-center">
                                        <div class="w-2.5 h-2.5 rounded-full bg-emerald-400 opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                                    </div>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-white">Publish</span>
                                    <p class="text-xs text-white/40">Tampil di halaman utama</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center justify-between pt-4 border-t border-brand-medium/20">
                        <a href="<?= base_url('admin/berita_delete/' . $news['id']) ?>"
                           onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini? Tindakan ini tidak dapat dibatalkan.')"
                           class="flex items-center gap-2 px-5 py-3 rounded-xl text-sm font-semibold text-brand-red border border-brand-red/30 hover:bg-brand-red/10 transition-all">
                            <i class="bi bi-trash-fill"></i>
                            <span>Hapus Berita</span>
                        </a>
                        <div class="flex items-center gap-3">
                            <a href="<?= base_url('admin/berita') ?>"
                               class="px-6 py-3 rounded-xl text-sm font-semibold text-white/70 border border-brand-medium/30 hover:text-white hover:border-brand-medium transition-all">
                                Batal
                            </a>
                            <button type="submit"
                                    class="flex items-center gap-2 bg-gradient-to-r from-brand-medium to-brand-dark hover:from-brand-medium/90 hover:to-brand-dark/90 border border-brand-medium text-white px-8 py-3 rounded-xl text-sm font-bold shadow-md transition-all">
                                <i class="bi bi-check2-circle"></i>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </div>

                </form>
            </div>

        </div>

    </main>

    <script>
        // Thumbnail preview
        document.getElementById('thumbnail').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(ev) {
                    document.getElementById('thumbnail-preview').src = ev.target.result;
                    document.getElementById('thumbnail-preview-wrapper').classList.remove('hidden');
                    document.getElementById('thumbnail-placeholder').classList.add('hidden');
                };
                reader.readAsDataURL(file);
            }
        });

        // Radio button visual update
        document.querySelectorAll('input[type="radio"]').forEach(function(radio) {
            radio.addEventListener('change', function() {
                document.querySelectorAll('input[type="radio"][name="' + this.name + '"]').forEach(function(r) {
                    const dot = r.parentElement.querySelector('div > div');
                    if (dot) dot.style.opacity = r.checked ? '1' : '0';
                });
            });
        });
    </script>

</body>
</html>
