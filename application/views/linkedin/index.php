<?php
/**
 * @var object $user
 * @var array $jobs
 * @var array $workers
 */
if (!function_exists('time_elapsed_string')) {
    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime();
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        $string = [];
        $units = ['y'=>'tahun','m'=>'bulan','w'=>'minggu','d'=>'hari','h'=>'jam','i'=>'menit','s'=>'detik'];
        foreach ($units as $k=>$v) {
            if ($diff->$k) {
                $string[$k] = $diff->$k . ' ' . $v;
            }
        }
        if (!$full) $string = array_slice($string,0,1);
        return $string ? implode(', ', $string) . ' yang lalu' : 'baru saja';
    }
}
?>

<style>
    :root {
        --color-bg-dark: #15201E;
        --color-border-dark: #374D49;
        --color-light-teal: #377C80;
        --color-orange-accent: #E49438;
        --color-text-muted: #B1CDCE;
    }
    
    body {
        background-color: var(--color-bg-dark) !important;
        color: #FFFFFF;
    }

    .linkedin-container {
        background: linear-gradient(135deg, #15201E 0%, #41635D 88%, #58867E 100%);
        min-height: 100vh;
    }

    .nav-sidebar-link {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .nav-sidebar-link:hover {
        transform: translateX(4px);
    }

    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--color-light-teal); border-radius: 3px; }

    /* Tabs */
    .tab-btn {
        padding: 12px 24px;
        font-weight: bold;
        color: var(--color-text-muted);
        border-bottom: 2px solid transparent;
        transition: all 0.2s;
    }
    .tab-btn:hover {
        color: #fff;
    }
    .tab-btn.active {
        color: var(--color-orange-accent);
        border-bottom-color: var(--color-orange-accent);
    }

    /* Modals */
    .modal-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 9999;
        display: none; align-items: center; justify-content: center;
        padding: 20px;
    }
    .modal-overlay.open { display: flex; }
    .modal-content {
        background: #1E2E2B; border: 1px solid #374D49; border-radius: 20px;
        width: 100%; max-width: 600px; margin: auto; padding: 24px;
        max-height: 90vh; overflow-y: auto;
    }

    /* Forms */
    .form-input {
        width: 100%; background: rgba(13,19,20,0.8); border: 1px solid #374D49;
        border-radius: 12px; color: #fff; font-size: 0.85rem; padding: 10px 14px;
        margin-bottom: 16px; outline: none; transition: border-color 0.2s;
    }
    .form-input:focus { border-color: var(--color-light-teal); }
    .form-label { display: block; font-size: 0.75rem; font-weight: 700; color: var(--color-text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em; }
    
    .form-input[readonly] {
        background: rgba(0,0,0,0.2);
        color: #B1CDCE;
        cursor: not-allowed;
    }

    .btn-primary {
        background: var(--color-orange-accent);
        color: white;
        padding: 10px 24px;
        border-radius: 50px;
        font-weight: bold;
        transition: all 0.2s;
        display: inline-flex; align-items: center; gap: 8px;
    }
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(228, 148, 56, 0.4);
    }
    
    /* Job list & detail panel layout */
    .job-list-container {
        flex: 1;
        transition: all 0.3s;
    }
    .job-detail-panel {
        width: 400px;
        flex-shrink: 0;
        background: #1E2E2B;
        border: 1px solid #374D49;
        border-radius: 16px;
        display: none;
        flex-direction: column;
        overflow: hidden;
    }
    .job-detail-panel.active {
        display: flex;
    }
    .job-item {
        background: rgba(21, 32, 30, 0.8);
        border: 1px solid rgba(55, 77, 73, 0.4);
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .job-item:hover, .job-item.active {
        border-color: var(--color-light-teal);
        background: rgba(55, 124, 128, 0.1);
    }

    /* Worker Card */
    .worker-card {
        background: rgba(21, 32, 30, 0.8);
        border: 1px solid rgba(55, 77, 73, 0.4);
        border-radius: 16px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 16px;
        transition: all 0.2s;
    }
    .worker-card:hover {
        transform: translateY(-2px);
        border-color: var(--color-light-teal);
    }
    .worker-avatar {
        width: 64px; height: 64px; border-radius: 50%; object-fit: cover;
        border: 2px solid var(--color-light-teal);
    }
</style>

<div class="linkedin-container font-display pb-12">
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        
        <?php if ($this->session->flashdata('success_msg')): ?>
            <div class="bg-[#374D49] text-white p-3 rounded-xl mb-4 border border-[#377C80] flex items-center gap-2 text-sm">
                <i class="bi bi-check-circle-fill text-[#7ecdd1]"></i> <?= $this->session->flashdata('success_msg') ?>
            </div>
        <?php endif; ?>
        <?php if ($this->session->flashdata('error_msg')): ?>
            <div class="bg-red-900/50 text-white p-3 rounded-xl mb-4 border border-red-500/50 flex items-center gap-2 text-sm">
                <i class="bi bi-exclamation-triangle-fill text-red-400"></i> <?= $this->session->flashdata('error_msg') ?>
            </div>
        <?php endif; ?>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- LEFT SIDEBAR: Nav -->
            <div class="lg:col-span-3 flex flex-col gap-8 lg:border-r lg:border-[#374D49]/40 lg:pr-6">
                <div>
                    <ul class="space-y-4 font-semibold">
                        <li>
                            <a href="<?= base_url('forum?filter=all') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40">
                                <i class="bi bi-house text-xl"></i> Beranda
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('forum?filter=populer') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40">
                                <i class="bi bi-star-fill text-[#E49438] text-xl"></i> Populer
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('forum?filter=my_posts') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40">
                                <i class="bi bi-clock text-xl"></i> Terbaru
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('forum?filter=saved') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40">
                                <i class="bi bi-bookmark-fill text-[#E49438] text-xl"></i> Simpan
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('linkedin') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all bg-[#374D49] text-white shadow-sm">
                                <i class="bi bi-linkedin text-[#0077b5] text-xl bg-white rounded flex items-center justify-center h-5 w-5 leading-none"></i> LinkedIn Alumni
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('profile') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40">
                                <div class="w-6 h-6 rounded-full overflow-hidden bg-teal-800 flex-shrink-0">
                                    <img src="<?= !empty($user->avatar) ? base_url($user->avatar) : base_url('assets/images/photo.png') ?>" alt="Avatar" class="w-full h-full object-cover">
                                </div>
                                Profil Saya
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- RIGHT CONTENT -->
            <div class="lg:col-span-9 flex flex-col">
                <!-- Header / Tabs -->
                <div class="flex items-center justify-between border-b border-[#374D49] mb-6">
                    <div class="flex">
                        <button class="tab-btn active" onclick="switchTab('lowongan', this)">Lowongan Pekerjaan</button>
                        <button class="tab-btn" onclick="switchTab('pekerja', this)">Mencari Pekerjaan</button>
                    </div>
                </div>

                <!-- TAB: Lowongan -->
                <div id="tab-lowongan" class="tab-content">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold text-white">Daftar Lowongan</h2>
                        <button onclick="openModal('addJobModal')" class="btn-primary">
                            <i class="bi bi-plus-lg"></i> Tambah Lowongan
                        </button>
                    </div>

                    <!-- Search Filter -->
                    <form method="GET" action="<?= base_url('linkedin') ?>" class="mb-5 flex flex-wrap gap-3 items-end">
                        <div class="flex-1 min-w-[160px]">
                            <label class="form-label">Cari Pekerjaan</label>
                            <input type="text" name="search" class="form-input" style="margin-bottom:0" placeholder="Posisi, perusahaan..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                        </div>
                        <div class="flex-1 min-w-[130px]">
                            <label class="form-label">Lokasi</label>
                            <input type="text" name="location" class="form-input" style="margin-bottom:0" placeholder="Jakarta, Bandung..." value="<?= htmlspecialchars($filters['location'] ?? '') ?>">
                        </div>
                        <div class="flex-1 min-w-[130px]">
                            <label class="form-label">Tipe Kerja</label>
                            <select name="type" class="form-input" style="margin-bottom:0">
                                <option value="">Semua Tipe</option>
                                <option value="Full-time" <?= ($filters['type'] ?? '') === 'Full-time' ? 'selected' : '' ?>>Full-time</option>
                                <option value="Part-time" <?= ($filters['type'] ?? '') === 'Part-time' ? 'selected' : '' ?>>Part-time</option>
                                <option value="Freelance" <?= ($filters['type'] ?? '') === 'Freelance' ? 'selected' : '' ?>>Freelance</option>
                                <option value="Remote" <?= ($filters['type'] ?? '') === 'Remote' ? 'selected' : '' ?>>Remote</option>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary" style="padding: 10px 20px;">Filter</button>
                        <a href="<?= base_url('linkedin') ?>" class="btn-primary" style="padding:10px 14px; background: rgba(55,77,73,0.6); border:1px solid #374D49;"><i class="bi bi-arrow-clockwise"></i></a>
                    </form>

                    <div class="flex gap-6 items-start relative">
                        <!-- List Panel -->
                        <div class="job-list-container">
                            <?php if(!empty($jobs)): ?>
                                <?php foreach($jobs as $job): ?>
                                    <div class="job-item" onclick="loadJobDetail(<?= $job->id ?>, this)">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="text-lg font-bold text-white mb-1"><?= htmlspecialchars($job->job_title) ?></h3>
                                                <p class="text-sm text-[#E49438] font-semibold mb-2"><?= htmlspecialchars($job->company_name) ?></p>
                                            </div>
                                            <span class="text-[10px] text-[#B1CDCE]/60"><?= time_elapsed_string($job->created_at) ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-12 bg-[#1E2E2B] rounded-xl border border-[#374D49]">
                                    <i class="bi bi-briefcase text-4xl text-[#B1CDCE]/30 mb-3 block"></i>
                                    <p class="text-[#B1CDCE]/50">Belum ada lowongan pekerjaan tersedia.</p>
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- Detail Panel -->
                        <div class="job-detail-panel custom-scrollbar overflow-y-auto max-h-[600px] sticky top-24" id="jobDetailPanel">
                            <div class="p-6">
                                <button onclick="closeJobDetail()" class="text-[#B1CDCE] hover:text-white mb-4 lg:hidden">
                                    <i class="bi bi-arrow-left"></i> Kembali
                                </button>
                                
                                <div id="jobDetailLoader" class="text-center py-10 text-[#B1CDCE]/50">
                                    Memuat detail...
                                </div>
                                
                                <div id="jobDetailContent" class="hidden">
                                    <h2 id="jdTitle" class="text-2xl font-bold text-white mb-1"></h2>
                                    <p id="jdCompany" class="text-lg text-[#E49438] font-bold mb-3"></p>

                                    <div id="applyActionArea" class="mb-6"></div>

                                    <div class="space-y-4 mb-6">
                                        <div class="flex items-start gap-3">
                                            <i class="bi bi-geo-alt mt-1 text-[#377C80]"></i>
                                            <div>
                                                <div class="text-xs text-[#B1CDCE]">Lokasi</div>
                                                <div id="jdLocation" class="text-sm text-white font-semibold"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <i class="bi bi-briefcase mt-1 text-[#377C80]"></i>
                                            <div>
                                                <div class="text-xs text-[#B1CDCE]">Jenis Pekerjaan</div>
                                                <div id="jdType" class="text-sm text-white font-semibold"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <i class="bi bi-cash mt-1 text-[#377C80]"></i>
                                            <div>
                                                <div class="text-xs text-[#B1CDCE]">Gaji</div>
                                                <div id="jdSalary" class="text-sm text-white font-semibold"></div>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <i class="bi bi-clock mt-1 text-[#377C80]"></i>
                                            <div>
                                                <div class="text-xs text-[#B1CDCE]">Jam Kerja</div>
                                                <div id="jdHours" class="text-sm text-white font-semibold"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="border-[#374D49] mb-4">
                                    
                                    <div class="mb-6">
                                        <h3 class="text-sm font-bold text-white mb-2">Deskripsi Pekerjaan</h3>
                                        <div id="jdDescription" class="text-sm text-[#B1CDCE] leading-relaxed whitespace-pre-wrap"></div>
                                    </div>

                                    <hr class="border-[#374D49] mb-4">

                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-full bg-teal-800 flex items-center justify-center text-white font-bold">
                                            <i class="bi bi-person"></i>
                                        </div>
                                        <div>
                                            <div class="text-[10px] text-[#B1CDCE]">Dipublikasikan oleh:</div>
                                            <div id="jdPublisher" class="text-sm font-bold text-white"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TAB: Pekerja -->
                <div id="tab-pekerja" class="tab-content hidden">
                    <h2 class="text-xl font-bold text-white mb-6">Mencari Pekerjaan (Open to Work)</h2>
                    
                    <?php if(!empty($workers)): ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php foreach($workers as $worker): ?>
                                <div class="worker-card">
                                    <img src="<?= !empty($worker->avatar) ? base_url($worker->avatar) : base_url('assets/images/photo.png') ?>" alt="Avatar" class="worker-avatar">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-base font-bold text-white truncate"><?= htmlspecialchars($worker->full_name) ?></h3>
                                        <p class="text-sm text-[#E49438] font-semibold mb-2 truncate"><?= htmlspecialchars($worker->work_role ?? 'Tidak ada role spesifik') ?></p>
                                        
                                        <div class="flex gap-2">
                                            <span class="px-2 py-1 bg-[#377C80]/20 text-[#7ecdd1] text-[10px] font-bold rounded">
                                                Open to Work
                                            </span>
                                            <?php if($worker->is_fresh_graduate): ?>
                                                <span class="px-2 py-1 bg-green-900/40 text-green-400 text-[10px] font-bold rounded border border-green-700/50">
                                                    Fresh Graduate
                                                </span>
                                            <?php else: ?>
                                                <span class="px-2 py-1 bg-blue-900/40 text-blue-400 text-[10px] font-bold rounded border border-blue-700/50">
                                                    Berpengalaman
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12 bg-[#1E2E2B] rounded-xl border border-[#374D49]">
                            <i class="bi bi-people text-4xl text-[#B1CDCE]/30 mb-3 block"></i>
                            <p class="text-[#B1CDCE]/50">Belum ada anggota keluarga yang sedang mencari pekerjaan.</p>
                        </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </main>
</div>

<!-- Modal Tambah Lowongan -->
<div class="modal-overlay" id="addJobModal" onclick="closeModalOutside(event, 'addJobModal')">
    <div class="modal-content custom-scrollbar" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 border-b border-[#374D49] pb-4">
            <h3 class="font-bold text-lg text-white flex items-center gap-2">
                <i class="bi bi-briefcase text-[#377C80]"></i> Tambah Lowongan Pekerjaan
            </h3>
            <button onclick="closeModal('addJobModal')" class="text-[#B1CDCE]/60 hover:text-white transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <?= form_open('linkedin/create_job') ?>
            
            <label class="form-label">Nama Perusahaan <span class="text-red-500">*</span></label>
            <input type="text" name="company_name" class="form-input" required placeholder="Contoh: PT. ABC Sejahtera">

            <label class="form-label">Jenis Pekerjaan / Posisi <span class="text-red-500">*</span></label>
            <input type="text" name="job_title" class="form-input" required placeholder="Contoh: Software Engineer">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Tipe Pekerjaan</label>
                    <input type="text" name="job_type" class="form-input" placeholder="Contoh: Full-time / Part-time">
                </div>
                <div>
                    <label class="form-label">Gaji</label>
                    <input type="text" name="salary" class="form-input" placeholder="Contoh: Rp 5.000.000">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="form-label">Jam Kerja</label>
                    <input type="text" name="working_hours" class="form-input" placeholder="Contoh: 08:00 - 17:00">
                </div>
                <div>
                    <label class="form-label">Lokasi</label>
                    <input type="text" name="location" class="form-input" placeholder="Contoh: Jakarta Selatan">
                </div>
            </div>

            <label class="form-label">Deskripsi Pekerjaan</label>
            <textarea name="description" class="form-input" rows="4" placeholder="Jelaskan kualifikasi dan tanggung jawab..."></textarea>

            <label class="form-label">Nama Publisher (Dipublikasikan Oleh)</label>
            <input type="text" class="form-input" value="<?= htmlspecialchars($user->full_name) ?>" readonly>

            <div class="flex justify-end mt-4">
                <button type="button" onclick="closeModal('addJobModal')" class="px-4 py-2 text-sm text-[#B1CDCE] hover:text-white mr-2">Batal</button>
                <button type="submit" class="btn-primary py-2 px-6">Simpan Lowongan</button>
            </div>

        <?= form_close() ?>
    </div>
</div>

<!-- Modal Lamar Pekerjaan -->
<div class="modal-overlay" id="applyJobModal" onclick="closeModalOutside(event, 'applyJobModal')">
    <div class="modal-content custom-scrollbar" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 border-b border-[#374D49] pb-4">
            <h3 class="font-bold text-lg text-white flex items-center gap-2">
                <i class="bi bi-send text-[#377C80]"></i> Lamar Pekerjaan
            </h3>
            <button onclick="closeModal('applyJobModal')" class="text-[#B1CDCE]/60 hover:text-white transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <?= form_open_multipart('linkedin/apply_job') ?>

            <input type="hidden" name="job_id" id="applyJobId">

            <label class="form-label">Posisi / Pekerjaan</label>
            <input type="text" id="applyJobTitle" class="form-input" readonly>

            <label class="form-label">Perusahaan</label>
            <input type="text" id="applyJobCompany" class="form-input" readonly>

            <label class="form-label">Upload CV <span class="text-red-500">*</span></label>
            <p class="text-[10px] text-[#B1CDCE]/60 mb-2">Format: PDF, DOC, DOCX, JPG, PNG. Maks 2MB.</p>
            <input type="file" name="cv" class="form-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" required style="padding:8px 14px;">

            <label class="form-label" style="margin-top:4px;">Keterangan / Motivasi <span class="text-[#B1CDCE]/50 font-normal">(opsional)</span></label>
            <textarea name="keterangan" class="form-input" rows="4" placeholder="Ceritakan singkat mengapa Anda tertarik dengan posisi ini..."></textarea>

            <div class="flex justify-end mt-4">
                <button type="button" onclick="closeModal('applyJobModal')" class="px-4 py-2 text-sm text-[#B1CDCE] hover:text-white mr-2">Batal</button>
                <button type="submit" class="btn-primary py-2 px-6"><i class="bi bi-send"></i> Kirim Lamaran</button>
            </div>

        <?= form_close() ?>
    </div>
</div>

<script>
    // Tabs
    function switchTab(tab, btn) {
        document.querySelectorAll('.tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.remove('hidden');
        btn.classList.add('active');
    }

    // Modal
    function openModal(id) {
        document.getElementById(id).classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeModal(id) {
        document.getElementById(id).classList.remove('open');
        document.body.style.overflow = '';
    }
    function closeModalOutside(e, id) {
        if (e.target.id === id) {
            closeModal(id);
        }
    }
    document.addEventListener('keydown', e => { 
        if (e.key === 'Escape') {
            document.querySelectorAll('.modal-overlay').forEach(el => {
                el.classList.remove('open');
            });
            document.body.style.overflow = '';
        }
    });

    // Job Detail
    function loadJobDetail(id, el) {
        // Highlight active item
        document.querySelectorAll('.job-item').forEach(item => item.classList.remove('active'));
        el.classList.add('active');

        const panel = document.getElementById('jobDetailPanel');
        const loader = document.getElementById('jobDetailLoader');
        const content = document.getElementById('jobDetailContent');

        // Layout adjustment for mobile vs desktop
        if (window.innerWidth < 1024) {
            document.querySelector('.job-list-container').style.display = 'none';
            panel.style.width = '100%';
        }
        
        panel.classList.add('active');
        loader.style.display = 'block';
        content.classList.add('hidden');

        fetch(`<?= base_url('linkedin/get_job/') ?>${id}`)
            .then(res => res.json())
            .then(res => {
                loader.style.display = 'none';
                if(res.status === 'success') {
                    const data = res.data;
                    document.getElementById('jdTitle').textContent = data.job_title;
                    document.getElementById('jdCompany').textContent = data.company_name;
                    document.getElementById('jdLocation').textContent = data.location || '-';
                    document.getElementById('jdType').textContent = data.job_type || '-';
                    document.getElementById('jdSalary').textContent = data.salary || '-';
                    document.getElementById('jdHours').textContent = data.working_hours || '-';
                    document.getElementById('jdDescription').textContent = data.description || 'Tidak ada deskripsi.';
                    document.getElementById('jdPublisher').textContent = data.publisher_name;

                    // Apply action area
                    const actionArea = document.getElementById('applyActionArea');
                    if (res.has_applied) {
                        actionArea.innerHTML = `<div style="display:inline-flex;align-items:center;gap:8px;background:rgba(55,124,128,0.15);border:1px solid rgba(55,124,128,0.4);color:#7ecdd1;padding:10px 18px;border-radius:50px;font-weight:bold;font-size:0.85rem;"><i class="bi bi-check-circle-fill"></i> Sudah Melamar</div>`;
                    } else {
                        actionArea.innerHTML = `<button onclick="openApplyModal(${data.id}, '${escapeAttr(data.job_title)}', '${escapeAttr(data.company_name)}')" class="btn-primary w-full" style="justify-content:center;"><i class="bi bi-send"></i> Lamar Pekerjaan</button>`;
                    }

                    content.classList.remove('hidden');
                } else {
                    loader.textContent = 'Gagal memuat detail pekerjaan.';
                    loader.style.display = 'block';
                }
            })
            .catch(() => {
                loader.textContent = 'Terjadi kesalahan jaringan.';
                loader.style.display = 'block';
            });
    }

    function closeJobDetail() {
        document.getElementById('jobDetailPanel').classList.remove('active');
        if (window.innerWidth < 1024) {
            document.querySelector('.job-list-container').style.display = 'block';
        }
        document.querySelectorAll('.job-item').forEach(item => item.classList.remove('active'));
    }

    function openApplyModal(jobId, jobTitle, companyName) {
        document.getElementById('applyJobId').value = jobId;
        document.getElementById('applyJobTitle').value = jobTitle;
        document.getElementById('applyJobCompany').value = companyName;
        openModal('applyJobModal');
    }

    function escapeAttr(str) {
        return (str || '').replace(/'/g, "\\'").replace(/"/g, '&quot;');
    }
</script>
