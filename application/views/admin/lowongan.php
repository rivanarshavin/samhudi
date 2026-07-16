<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Lowongan | Admin Keluarga H.M Samhudi</title>
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
        .form-field {
            width: 100%; background: rgba(13,19,20,0.8); border: 1px solid #324742;
            border-radius: 10px; color: #fff; font-size: 0.85rem; padding: 10px 14px;
            outline: none; transition: border-color 0.2s;
        }
        .form-field:focus { border-color: #5F7F7A; }
    </style>
</head>
<body class="bg-teal-950 text-white font-body min-h-screen flex">

    <!-- Embed jobs data for JS -->
    <script>
        const jobsData = <?= json_encode($jobs) ?>;
    </script>

    <!-- ================= SIDEBAR ================= -->
    <?php $this->load->view('admin/sidebar'); ?>

    <!-- ================= MAIN CONTENT ================= -->
    <main class="flex-1 flex flex-col overflow-y-auto">

        <!-- Header -->
        <?php $this->load->view('admin/header'); ?>

        <!-- Body Content -->
        <div class="p-4 md:p-8 space-y-6">

            <!-- Title & Actions -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="font-display font-extrabold text-2xl md:text-3xl tracking-tight">Kelola Lowongan</h1>
                    <p class="text-xs md:text-sm text-teal-400 mt-1">Verifikasi dan kelola semua lowongan pekerjaan yang diajukan anggota.</p>
                </div>
                <button onclick="openModal('addJobModal')"
                        class="btn-admin-primary inline-flex items-center gap-2 px-5 py-2.5 font-display font-bold rounded-xl shadow-lg transition-all active:scale-95 text-sm whitespace-nowrap">
                    <i class="bi bi-plus-lg"></i>
                    Tambah Lowongan
                </button>
            </div>

            <!-- Flash Messages -->
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

            <!-- Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <?php
                    $total    = count($jobs);
                    $pending  = count(array_filter($jobs, fn($j) => $j['status'] === 'pending'));
                    $approved = count(array_filter($jobs, fn($j) => $j['status'] === 'approved'));
                    $rejected = count(array_filter($jobs, fn($j) => $j['status'] === 'rejected'));
                ?>
                <div class="bg-teal-900/50 border border-teal-800 rounded-xl p-4">
                    <p class="text-xs text-teal-400 font-semibold mb-1">Total Lowongan</p>
                    <p class="text-2xl font-display font-extrabold"><?= $total ?></p>
                </div>
                <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4">
                    <p class="text-xs text-amber-400 font-semibold mb-1">Pending</p>
                    <p class="text-2xl font-display font-extrabold text-amber-400"><?= $pending ?></p>
                </div>
                <div class="bg-green-500/10 border border-green-500/30 rounded-xl p-4">
                    <p class="text-xs text-green-400 font-semibold mb-1">Aktif</p>
                    <p class="text-2xl font-display font-extrabold text-green-400"><?= $approved ?></p>
                </div>
                <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4">
                    <p class="text-xs text-red-400 font-semibold mb-1">Ditolak</p>
                    <p class="text-2xl font-display font-extrabold text-red-400"><?= $rejected ?></p>
                </div>
            </div>

            <!-- Table -->
            <div class="bg-teal-900/40 border border-teal-800 rounded-2xl shadow-xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-teal-900/60 border-b border-teal-800">
                                <th class="p-5 text-xs font-bold text-teal-400 uppercase tracking-wider">Pekerjaan / Perusahaan</th>
                                <th class="p-5 text-xs font-bold text-teal-400 uppercase tracking-wider hidden md:table-cell">Detail</th>
                                <th class="p-5 text-xs font-bold text-teal-400 uppercase tracking-wider text-center">Pelamar</th>
                                <th class="p-5 text-xs font-bold text-teal-400 uppercase tracking-wider text-center">Status</th>
                                <th class="p-5 text-xs font-bold text-teal-400 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-teal-900/50">
                            <?php if (!empty($jobs)): ?>
                                <?php foreach ($jobs as $job): ?>
                                    <tr class="hover:bg-teal-900/20 transition-colors">
                                        <!-- Job Title & Company -->
                                        <td class="p-5">
                                            <div>
                                                <span class="font-display font-bold text-white block text-sm"><?= htmlspecialchars($job['job_title']) ?></span>
                                                <span class="text-xs text-amber-400 font-semibold"><?= htmlspecialchars($job['company_name']) ?></span>
                                                <span class="block text-xs text-white/40 mt-0.5"><?= date('d M Y', strtotime($job['created_at'])) ?></span>
                                            </div>
                                        </td>

                                        <!-- Detail Info -->
                                        <td class="p-5 hidden md:table-cell">
                                            <div class="space-y-1 text-xs text-white/70">
                                                <?php if (!empty($job['location'])): ?>
                                                <div class="flex items-center gap-1.5">
                                                    <i class="bi bi-geo-alt text-teal-400"></i>
                                                    <span><?= htmlspecialchars($job['location']) ?></span>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (!empty($job['job_type'])): ?>
                                                <div class="flex items-center gap-1.5">
                                                    <i class="bi bi-briefcase text-teal-400"></i>
                                                    <span><?= htmlspecialchars($job['job_type']) ?></span>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (!empty($job['salary'])): ?>
                                                <div class="flex items-center gap-1.5">
                                                    <i class="bi bi-cash text-teal-400"></i>
                                                    <span><?= htmlspecialchars($job['salary']) ?></span>
                                                </div>
                                                <?php endif; ?>
                                                <div class="flex items-center gap-1.5 text-white/40">
                                                    <i class="bi bi-person text-teal-500"></i>
                                                    <span>Oleh: <?= htmlspecialchars($job['publisher_name']) ?></span>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Applicants Count -->
                                        <td class="p-5 text-center">
                                            <?php $applicant_count = count($job['applicants']); ?>
                                            <?php if ($job['status'] === 'approved' && $applicant_count > 0): ?>
                                                <button onclick="openApplicantsModal(<?= $job['id'] ?>)"
                                                        class="inline-flex flex-col items-center gap-1 group">
                                                    <span class="text-xl font-display font-extrabold text-teal-400 group-hover:text-white transition-colors"><?= $applicant_count ?></span>
                                                    <span class="text-[10px] text-teal-500 group-hover:text-teal-400 transition-colors underline">Lihat Pelamar</span>
                                                </button>
                                            <?php else: ?>
                                                <span class="text-xl font-display font-bold text-white/30"><?= $applicant_count ?></span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Status Badge -->
                                        <td class="p-5 text-center">
                                            <?php if ($job['status'] === 'approved'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-green-500/10 text-green-400 border border-green-500/25">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-green-400"></span>
                                                    Aktif
                                                </span>
                                            <?php elseif ($job['status'] === 'pending'): ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-500/10 text-amber-400 border border-amber-500/25">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                                    Pending
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-red-500/10 text-red-400 border border-red-500/25">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                                    Ditolak
                                                </span>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Actions -->
                                        <td class="p-5 text-center">
                                            <div class="flex items-center justify-center gap-2 flex-wrap">
                                                <?php if ($job['status'] !== 'approved'): ?>
                                                    <button onclick="showConfirm('<?= base_url('admin/lowongan_approve/' . $job['id']) ?>', 'Apakah Anda yakin ingin menyetujui lowongan pekerjaan dari perusahaan <?= htmlspecialchars($job['company_name']) ?> untuk posisi <?= htmlspecialchars($job['job_title']) ?>?', 'Setujui Lowongan', 'success')"
                                                       class="px-3 py-1.5 bg-green-600 hover:bg-green-700 text-white rounded-lg font-display text-xs font-bold shadow-md transition-all active:scale-95 flex items-center gap-1">
                                                        <i class="bi bi-check-lg"></i> Setujui
                                                    </button>
                                                <?php endif; ?>
                                                <?php if ($job['status'] !== 'rejected'): ?>
                                                    <button onclick="showConfirm('<?= base_url('admin/lowongan_reject/' . $job['id']) ?>', 'Apakah Anda yakin ingin menolak lowongan pekerjaan dari perusahaan <?= htmlspecialchars($job['company_name']) ?> untuk posisi <?= htmlspecialchars($job['job_title']) ?>?', 'Tolak Lowongan', 'warning')"
                                                       class="px-3 py-1.5 bg-amber-500/10 hover:bg-amber-500/20 text-amber-400 rounded-lg font-display text-xs font-semibold border border-amber-500/25 transition-all flex items-center gap-1">
                                                        <i class="bi bi-x-lg"></i> Tolak
                                                    </button>
                                                <?php endif; ?>
                                                <button onclick="showConfirm('<?= base_url('admin/lowongan_delete/' . $job['id']) ?>', 'Apakah Anda yakin ingin menghapus lowongan pekerjaan dari perusahaan <?= htmlspecialchars($job['company_name']) ?> untuk posisi <?= htmlspecialchars($job['job_title']) ?> beserta semua data pelamar yang melamar?', 'Hapus Lowongan', 'danger')"
                                                   class="px-3 py-1.5 bg-red-500/10 hover:bg-red-500/20 text-red-400 rounded-lg font-display text-xs font-semibold border border-red-500/20 transition-all flex items-center gap-1">
                                                    <i class="bi bi-trash"></i> Hapus
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="p-12 text-center text-white/40 text-sm">
                                        <i class="bi bi-briefcase text-4xl block mb-3 opacity-40"></i>
                                        Belum ada lowongan pekerjaan.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div><!-- /p-8 -->
    </main>

    <!-- ================= MODAL: Tambah Lowongan ================= -->
    <div class="modal-backdrop" id="addJobModal" onclick="closeModalOutside(event,'addJobModal')">
        <div class="modal-box" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-6 border-b border-teal-700 pb-4">
                <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                    <i class="bi bi-briefcase text-gold-400"></i> Tambah Lowongan
                </h3>
                <button onclick="closeModal('addJobModal')" class="text-white/40 hover:text-white transition-colors">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            <form action="<?= base_url('admin/lowongan_add') ?>" method="post">
                <div class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-teal-400 uppercase tracking-wider mb-1.5">Nama Perusahaan <span class="text-red-400">*</span></label>
                            <input type="text" name="company_name" class="form-field" required placeholder="PT. Contoh Sejahtera">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-teal-400 uppercase tracking-wider mb-1.5">Posisi / Jabatan <span class="text-red-400">*</span></label>
                            <input type="text" name="job_title" class="form-field" required placeholder="Software Engineer">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-teal-400 uppercase tracking-wider mb-1.5">Tipe Pekerjaan</label>
                            <input type="text" name="job_type" class="form-field" placeholder="Full-time / Part-time">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-teal-400 uppercase tracking-wider mb-1.5">Gaji</label>
                            <input type="text" name="salary" class="form-field" placeholder="Rp 5.000.000">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-teal-400 uppercase tracking-wider mb-1.5">Jam Kerja</label>
                            <input type="text" name="working_hours" class="form-field" placeholder="08:00 - 17:00">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-teal-400 uppercase tracking-wider mb-1.5">Lokasi</label>
                            <input type="text" name="location" class="form-field" placeholder="Jakarta Selatan">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-teal-400 uppercase tracking-wider mb-1.5">Deskripsi</label>
                        <textarea name="description" class="form-field" rows="4" placeholder="Kualifikasi dan tanggung jawab..."></textarea>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeModal('addJobModal')" class="px-4 py-2 text-sm text-white/60 hover:text-white transition-colors">Batal</button>
                        <button type="submit"
                                class="btn-admin-primary px-6 py-2.5 font-display font-bold rounded-xl text-sm shadow-lg transition-all active:scale-95">
                            <i class="bi bi-plus-lg mr-1"></i> Simpan Lowongan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- ================= MODAL: Lihat Pelamar ================= -->
    <div class="modal-backdrop" id="applicantsModal" onclick="closeModalOutside(event,'applicantsModal')">
        <div class="modal-box max-w-2xl" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-4 border-b border-teal-700 pb-4">
                <div>
                    <h3 class="font-display font-bold text-lg text-white flex items-center gap-2">
                        <i class="bi bi-people text-teal-400"></i> Daftar Pelamar
                    </h3>
                    <p id="applicantsJobTitle" class="text-xs text-amber-400 mt-0.5"></p>
                </div>
                <button onclick="closeModal('applicantsModal')" class="text-white/40 hover:text-white transition-colors">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            <div id="applicantsList" class="space-y-3 max-h-[60vh] overflow-y-auto pr-1">
                <!-- Populated by JS -->
            </div>
        </div>
    </div>

    <script>
        // --- Modal helpers ---
        function openModal(id) {
            document.getElementById(id).classList.add('open');
            document.body.style.overflow = 'hidden';
        }
        function closeModal(id) {
            document.getElementById(id).classList.remove('open');
            document.body.style.overflow = '';
        }
        function closeModalOutside(e, id) {
            closeModal(id);
        }
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                document.querySelectorAll('.modal-backdrop').forEach(el => el.classList.remove('open'));
                document.body.style.overflow = '';
            }
        });

        // --- Applicants modal ---
        function openApplicantsModal(jobId) {
            const job = jobsData.find(j => j.id == jobId);
            if (!job) return;

            document.getElementById('applicantsJobTitle').textContent = job.job_title + ' — ' + job.company_name;

            const listEl = document.getElementById('applicantsList');
            const applicants = job.applicants || [];

            if (applicants.length === 0) {
                listEl.innerHTML = '<p class="text-center text-white/40 py-8 text-sm">Belum ada pelamar untuk lowongan ini.</p>';
            } else {
                listEl.innerHTML = applicants.map(app => {
                    const avatarInitial = (app.full_name || 'U').charAt(0).toUpperCase();
                    const date = new Date(app.created_at).toLocaleDateString('id-ID', {day:'numeric', month:'long', year:'numeric'});
                    const cvLink = app.cv_path
                        ? `<a href="${baseUrl}${app.cv_path}" target="_blank" class="inline-flex items-center gap-1.5 text-xs px-3 py-1.5 rounded-lg bg-teal-800 hover:bg-teal-700 text-teal-300 border border-teal-700 transition-all"><i class="bi bi-file-earmark-arrow-down"></i> Lihat CV</a>`
                        : '<span class="text-xs text-white/30">Tidak ada CV</span>';
                    const keterangan = app.keterangan ? `<p class="text-xs text-white/60 mt-1 italic">"${escapeHtml(app.keterangan)}"</p>` : '';

                    return `
                    <div class="bg-teal-900/50 border border-teal-800 rounded-xl p-4 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-700 to-teal-900 flex items-center justify-center text-gold-400 font-bold border border-teal-700 flex-shrink-0">
                            ${avatarInitial}
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <div>
                                    <span class="font-display font-bold text-sm text-white block">${escapeHtml(app.full_name || 'Pengguna')}</span>
                                    <span class="text-xs text-teal-400">${date}</span>
                                </div>
                                ${cvLink}
                            </div>
                            ${keterangan}
                        </div>
                    </div>`;
                }).join('');
            }

            openModal('applicantsModal');
        }

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        }

        const baseUrl = '<?= base_url() ?>';

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

        function closeConfirmModal() {
            closeModal('confirmModal');
        }
    </script>

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

</body>
</html>
