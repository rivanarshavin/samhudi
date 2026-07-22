<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Forum Diskusi | Admin Panel</title>
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

            <!-- Title & Stats -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="font-display font-extrabold text-2xl text-white">Kelola Forum Diskusi</h2>
                    <p class="text-brand-light/70 text-xs mt-1">Moderasi semua topik diskusi dan komentar dari anggota keluarga.</p>
                </div>
                <div class="flex items-center gap-3">
                    <div class="bg-teal-900/60 border border-teal-800 rounded-xl px-4 py-2 flex items-center gap-2 text-sm text-teal-300">
                        <i class="bi bi-chat-left-text-fill"></i>
                        <span class="font-bold"><?= count($forums) ?></span>
                        <span class="text-white/50">topik</span>
                    </div>
                </div>
            </div>

            <!-- Search Filter -->
            <div class="bg-brand-dark/20 border border-brand-medium/20 rounded-2xl p-6 shadow-sm">
                <form method="GET" action="<?= base_url('admin/forum') ?>" class="flex flex-col md:flex-row gap-4">
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <i class="bi bi-search absolute left-4 top-3.5 text-white/40"></i>
                        <input type="text" name="search" value="<?= htmlspecialchars($search) ?>"
                               placeholder="Cari judul forum..."
                               class="w-full bg-[#1A2824] border border-[#4D6B67]/30 rounded-xl py-3 pl-11 pr-4 text-sm text-white placeholder-white/40 focus:outline-none focus:border-brand-medium transition-all">
                    </div>
                    <button type="submit"
                            class="bg-brand-medium hover:bg-brand-medium/90 border border-brand-medium text-white px-6 py-3 rounded-xl flex items-center gap-2 text-sm font-semibold transition-all">
                        <i class="bi bi-funnel-fill"></i>
                        <span>Filter</span>
                    </button>
                    <?php if (!empty($search)): ?>
                        <a href="<?= base_url('admin/forum') ?>"
                           class="border border-brand-medium/30 text-white/60 hover:text-white px-6 py-3 rounded-xl flex items-center gap-2 text-sm font-semibold transition-all">
                            <i class="bi bi-x-circle"></i>
                            <span>Reset</span>
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Table Card -->
            <div class="bg-gradient-to-b from-brand-dark/20 to-brand-dark/5 border border-brand-medium/20 rounded-2xl p-6 shadow-lg">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-[#4D6B67]/20">
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Topik Forum</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Dibuat Oleh</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Komentar</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider">Tanggal</th>
                                <th class="pb-4 text-xs font-bold text-white/40 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#4D6B67]/10">
                            <?php if (!empty($forums)): ?>
                                <?php foreach ($forums as $forum): ?>
                                    <tr class="hover:bg-brand-dark/10 transition-colors">
                                        <!-- Judul & Preview -->
                                        <td class="py-4 pr-4">
                                            <div class="font-semibold text-white text-sm max-w-xs">
                                                <?= htmlspecialchars($forum['title']) ?>
                                            </div>
                                            <?php if (!empty($forum['content'])): ?>
                                                <div class="text-xs text-white/40 mt-1 line-clamp-1 max-w-xs">
                                                    <?= htmlspecialchars(substr(strip_tags($forum['content']), 0, 80)) ?>...
                                                </div>
                                            <?php endif; ?>
                                        </td>

                                        <!-- Author -->
                                        <td class="py-4">
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-full bg-brand-medium/40 flex items-center justify-center text-xs font-bold text-white border border-brand-medium/20">
                                                    <?= strtoupper(substr($forum['author_name'] ?? 'U', 0, 1)) ?>
                                                </div>
                                                <span class="text-sm text-white/80">
                                                    <?= htmlspecialchars($forum['author_name'] ?? 'Unknown') ?>
                                                </span>
                                            </div>
                                        </td>

                                        <!-- Jumlah Komentar -->
                                        <td class="py-4">
                                            <div class="flex items-center gap-1.5">
                                                <i class="bi bi-chat-dots text-teal-400 text-sm"></i>
                                                <span class="text-sm font-semibold text-white/80">
                                                    <?= number_format($forum['comment_count']) ?>
                                                </span>
                                                <span class="text-xs text-white/40">komentar</span>
                                            </div>
                                        </td>

                                        <!-- Tanggal -->
                                        <td class="py-4 text-sm text-white/60">
                                            <?php
                                                $ts = strtotime($forum['created_at']);
                                                $months_id = [1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
                                                echo date('j', $ts) . ' ' . $months_id[(int)date('n', $ts)] . ' ' . date('Y', $ts);
                                            ?>
                                        </td>

                                        <!-- Aksi -->
                                        <td class="py-4 text-right space-x-2">
                                             <button onclick="openForumDetail(<?= $forum['id'] ?>)"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-teal-500/10 text-teal-400 hover:bg-teal-500 hover:text-white border border-teal-500/20 transition-all"
                                               title="Lihat Detail & Moderasi Komentar">
                                                <i class="bi bi-eye-fill text-sm"></i>
                                             </button>
                                            <a href="<?= base_url('admin/forum_delete/' . $forum['id']) ?>"
                                               onclick="return confirm('Hapus topik forum ini? Semua komentar juga akan ikut terhapus.')"
                                               class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-brand-red/10 text-brand-red hover:bg-brand-red hover:text-white border border-brand-red/20 transition-all"
                                               title="Hapus Forum">
                                                <i class="bi bi-trash-fill text-sm"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5" class="py-16 text-center">
                                        <div class="flex flex-col items-center gap-3 text-white/30">
                                            <i class="bi bi-chat-left-text text-4xl"></i>
                                            <span class="text-sm">
                                                <?= !empty($search) ? 'Tidak ada forum yang cocok dengan pencarian.' : 'Belum ada topik forum diskusi.' ?>
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

    <!-- DETAIL MODAL OVERLAY -->
    <div id="forumDetailModal" class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/65 backdrop-blur-sm p-4 transition-all duration-300">
        <div class="bg-[#1D2A27] border border-[#4D6B67]/30 rounded-3xl w-full max-w-4xl max-h-[85vh] flex flex-col shadow-2xl overflow-hidden transform scale-95 transition-all duration-300">
            
            <!-- Modal Header -->
            <div class="flex items-center justify-between border-b border-[#4D6B67]/20 p-5">
                <h3 class="font-display font-bold text-lg text-white">Detail & Moderasi Forum</h3>
                <button onclick="closeForumDetail()" class="text-white/60 hover:text-white p-1 transition-colors">
                    <i class="bi bi-x-lg text-xl"></i>
                </button>
            </div>
            
            <!-- Modal Body (Two-column layout) -->
            <div class="flex-1 overflow-y-auto p-6 grid grid-cols-1 lg:grid-cols-5 gap-6">
                <!-- Left panel: Post details (3 cols) -->
                <div class="lg:col-span-3 space-y-4">
                    <div class="flex items-center gap-3">
                        <div id="modalAuthorAvatar" class="w-10 h-10 rounded-full bg-brand-medium/30 flex items-center justify-center font-bold text-white border border-[#4D6B67]/20 text-sm">
                            A
                        </div>
                        <div>
                            <h4 id="modalAuthorName" class="font-bold text-white text-sm">Author</h4>
                            <span id="modalPostDate" class="text-xs text-white/40">Date</span>
                        </div>
                    </div>
                    <h2 id="modalPostTitle" class="font-display font-bold text-lg text-white pt-2">Post Title</h2>
                    <p id="modalPostContent" class="text-sm text-white/80 leading-relaxed whitespace-pre-line"></p>
                    <div id="modalMediaContainer" class="hidden border border-[#4D6B67]/10 rounded-xl overflow-hidden bg-black/20 mt-3">
                        <!-- Image or Video injected here -->
                    </div>
                </div>
                <!-- Right panel: Comments list (2 cols) -->
                <div class="lg:col-span-2 border-t lg:border-t-0 lg:border-l border-[#4D6B67]/20 pt-6 lg:pt-0 lg:pl-6 flex flex-col">
                    <h4 class="font-display font-bold text-sm text-white/90 border-b border-[#4D6B67]/20 pb-3 mb-3 flex items-center gap-2">
                        <i class="bi bi-chat-left-text-fill text-teal-400"></i>
                        <span>Komentar</span>
                        <span id="modalCommentsCount" class="text-xs bg-brand-medium/30 px-2 py-0.5 rounded-full text-white ml-auto">0</span>
                    </h4>
                    <div id="modalCommentsList" class="flex-1 overflow-y-auto space-y-3 max-h-[350px] pr-1">
                        <!-- Comments injected here -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function openForumDetail(id) {
        const modal = document.getElementById('forumDetailModal');
        const modalDialog = modal.querySelector('div');
        
        // Show overlay
        modal.classList.remove('hidden');
        setTimeout(() => modalDialog.classList.remove('scale-95'), 50);

        // Fetch detail
        fetch("<?= base_url('admin/api_get_forum_details/') ?>" + id)
            .then(res => res.json())
            .then(data => {
                if (data.status) {
                    const f = data.forum;
                    
                    // Populate author & post
                    document.getElementById('modalAuthorName').innerText = f.author_name || 'Unknown';
                    document.getElementById('modalAuthorAvatar').innerText = (f.author_name || 'U').charAt(0).toUpperCase();
                    
                    const postDate = new Date(f.created_at);
                    document.getElementById('modalPostDate').innerText = postDate.toLocaleDateString('id-ID', {day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit'});
                    
                    document.getElementById('modalPostTitle').innerText = f.title;
                    document.getElementById('modalPostContent').innerText = f.content;

                    // Media preview
                    const mediaContainer = document.getElementById('modalMediaContainer');
                    mediaContainer.innerHTML = '';
                    if (f.media_url) {
                        mediaContainer.classList.remove('hidden');
                        const ext = f.media_url.split('.').pop().toLowerCase();
                        if (['mp4', 'webm', 'ogg'].includes(ext)) {
                            mediaContainer.innerHTML = `<video src="<?= base_url() ?>` + f.media_url + `" class="w-full max-h-64 object-contain" controls></video>`;
                        } else {
                            mediaContainer.innerHTML = `<img src="<?= base_url() ?>` + f.media_url + `" class="w-full max-h-64 object-contain">`;
                        }
                    } else {
                        mediaContainer.classList.add('hidden');
                    }

                    // Populate comments
                    const commentsList = document.getElementById('modalCommentsList');
                    commentsList.innerHTML = '';
                    document.getElementById('modalCommentsCount').innerText = data.comments.length;

                    if (data.comments.length > 0) {
                        data.comments.forEach(c => {
                            const dateStr = new Date(c.created_at).toLocaleDateString('id-ID', {day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit'});
                            const html = `
                                <div class="bg-[#15201E]/60 border border-[#4D6B67]/15 p-3.5 rounded-2xl relative space-y-1">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-[#4D6B67]/40 flex items-center justify-center text-[10px] font-bold text-white">
                                                ${(c.author_name || 'U').charAt(0).toUpperCase()}
                                            </div>
                                            <div>
                                                <span class="text-xs font-bold text-white/90">${c.author_name || 'Unknown'}</span>
                                                <span class="text-[9px] text-white/40 block">${dateStr}</span>
                                            </div>
                                        </div>
                                        <a href="<?= base_url('admin/forum_comment_delete/') ?>${c.id}/${f.id}" 
                                           onclick="return confirm('Hapus komentar ini karena jorok/tidak pantas?')" 
                                           class="text-red-400 hover:text-red-300 p-1 transition-colors" 
                                           title="Hapus Komentar">
                                            <i class="bi bi-trash-fill text-xs"></i>
                                        </a>
                                    </div>
                                    <p class="text-xs text-white/70 leading-relaxed pt-1">${c.comment}</p>
                                </div>
                            `;
                            commentsList.insertAdjacentHTML('beforeend', html);
                        });
                    } else {
                        commentsList.innerHTML = `
                            <div class="text-center py-10 text-white/30 text-xs flex flex-col items-center gap-2">
                                <i class="bi bi-chat-left-dots text-3xl"></i>
                                <span>Belum ada komentar.</span>
                            </div>
                        `;
                    }
                }
            });
    }

    function closeForumDetail() {
        const modal = document.getElementById('forumDetailModal');
        const modalDialog = modal.querySelector('div');
        
        modalDialog.classList.add('scale-95');
        setTimeout(() => modal.classList.add('hidden'), 200);
    }
    </script>
</body>
</html>
