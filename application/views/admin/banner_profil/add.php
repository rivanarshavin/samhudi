<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Banner Profil - Admin Panel</title>
    <link rel="icon" type="image/jpeg" href="<?= base_url('assets/favicon.jpeg') ?>">
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
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        display: ['Plus Jakarta Sans', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body class="bg-teal-950 text-white font-sans">
    
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <?php $this->load->view('admin/sidebar', ['active_menu' => 'banner_profil']); ?>

        <!-- Main Content -->
        <main class="flex-1 flex flex-col overflow-y-auto">
            <!-- Header -->
            <header class="h-20 bg-gradient-to-r from-[#374D49] to-[#3E6C65] border-b border-[#4D6B67]/30 flex items-center justify-between px-4 md:px-8 shrink-0 shadow-md">
                <div class="flex items-center gap-4">
                    <button onclick="document.getElementById('adminSidebar').classList.remove('-translate-x-full');" class="md:hidden text-white/80 hover:text-white p-1">
                        <i class="bi bi-list text-2xl"></i>
                    </button>
                    <div class="flex items-center gap-3">
                        <a href="<?= base_url('admin/banner_profil') ?>" class="w-10 h-10 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white transition-colors">
                            <i class="bi bi-arrow-left text-xl"></i>
                        </a>
                        <div>
                            <h1 class="font-display font-bold text-lg text-white">Tambah Banner Profil</h1>
                            <p class="text-xs text-white/80 mt-0.5">Upload banner baru untuk profil user</p>
                        </div>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <button id="theme-toggle" class="text-white hover:text-gray-300 focus:outline-none p-2 rounded-full hover:bg-white/10 transition-colors" title="Toggle Tema">
                        <i id="theme-icon" class="bi bi-moon-stars text-base"></i>
                    </button>
                </div>
            </header>

            <!-- Scrollable Content -->
            <div class="flex-1 overflow-y-auto p-8">
                <div class="max-w-2xl mx-auto bg-white rounded-2xl border border-gray-200 p-8 shadow-sm">
                    <?php if ($this->session->flashdata('error')): ?>
                        <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6 border border-red-200 flex items-center gap-3">
                            <i class="bi bi-exclamation-triangle-fill"></i> <?= $this->session->flashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/banner_profil_add') ?>" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-6">
                            <label class="block text-sm font-bold text-gray-700 mb-2">Pilih File Gambar Banner</label>
                            
                            <!-- Drag and Drop Zone -->
                            <div id="dragDropZone" class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center bg-gray-50 hover:bg-brand-medium/5 hover:border-brand-medium transition-all cursor-pointer relative">
                                <input type="file" name="banner_file" id="bannerFileInput" accept="image/*" required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                                <div class="pointer-events-none">
                                    <i class="bi bi-cloud-arrow-up text-4xl text-gray-400 mb-3 block"></i>
                                    <p class="text-sm font-semibold text-gray-700">Tarik & Lepas file ke sini, atau klik untuk memilih</p>
                                    <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, GIF, WEBP. Maks 5MB.<br>Disarankan lanskap.</p>
                                </div>
                                <div id="previewContainer" class="hidden mt-4 pt-4 border-t border-gray-200 pointer-events-none">
                                    <img id="imagePreview" class="max-h-40 mx-auto rounded-lg shadow-sm">
                                </div>
                            </div>
                        </div>

                        <script>
                            const fileInput = document.getElementById('bannerFileInput');
                            const dropZone = document.getElementById('dragDropZone');
                            const previewContainer = document.getElementById('previewContainer');
                            const imagePreview = document.getElementById('imagePreview');

                            ['dragenter', 'dragover'].forEach(eventName => {
                                dropZone.addEventListener(eventName, preventDefaults, false);
                            });

                            ['dragleave', 'drop'].forEach(eventName => {
                                dropZone.addEventListener(eventName, preventDefaults, false);
                            });

                            function preventDefaults(e) {
                                e.preventDefault();
                                e.stopPropagation();
                            }

                            ['dragenter', 'dragover'].forEach(eventName => {
                                dropZone.addEventListener(eventName, highlight, false);
                            });

                            ['dragleave', 'drop'].forEach(eventName => {
                                dropZone.addEventListener(eventName, unhighlight, false);
                            });

                            function highlight(e) {
                                dropZone.classList.add('border-brand-medium', 'bg-brand-medium/5');
                            }

                            function unhighlight(e) {
                                dropZone.classList.remove('border-brand-medium', 'bg-brand-medium/5');
                            }

                            dropZone.addEventListener('drop', handleDrop, false);

                            function handleDrop(e) {
                                const dt = e.dataTransfer;
                                const files = dt.files;
                                if (files.length > 0) {
                                    fileInput.files = files;
                                    handleFiles(files);
                                }
                            }

                            fileInput.addEventListener('change', function() {
                                if(this.files.length > 0) {
                                    handleFiles(this.files);
                                }
                            });

                            function handleFiles(files) {
                                const file = files[0];
                                if (file.type.startsWith('image/')) {
                                    const reader = new FileReader();
                                    reader.onload = e => {
                                        imagePreview.src = e.target.result;
                                        previewContainer.classList.remove('hidden');
                                    }
                                    reader.readAsDataURL(file);
                                }
                            }
                        </script>

                        <div class="flex justify-end gap-3 pt-6 border-t border-gray-100">
                            <a href="<?= base_url('admin/banner_profil') ?>" class="px-6 py-2.5 rounded-xl font-semibold text-gray-600 hover:bg-gray-100 transition-all">Batal</a>
                            <button type="submit" class="bg-brand-medium hover:bg-brand-medium/90 text-white px-8 py-2.5 rounded-xl font-semibold transition-all shadow-sm flex items-center gap-2">
                                <i class="bi bi-cloud-upload"></i> Upload Banner
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
