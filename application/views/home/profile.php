<?php
/**
 * @var object $user
 * @var array  $user_forums
 * @var array  $user_comments
 * @var array  $user_news_likes
 * @var array  $most_viewed_news
 * @var array  $available_banners
 */
$months_map = [1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
function fmt_date_pf($date_str, $months_map) {
    $ts = strtotime($date_str);
    return $ts ? date('j', $ts) . ' ' . $months_map[(int)date('n', $ts)] . ' ' . date('Y', $ts) : '-';
}
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
<!-- Custom Style for Profile -->
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

    .forum-container {
        background: linear-gradient(135deg, #15201E 0%, #41635D 88%, #58867E 100%);
        min-height: 100vh;
    }

    .nav-sidebar-link {
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .nav-sidebar-link:hover { transform: translateX(4px); }
    .custom-scrollbar::-webkit-scrollbar { width: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255, 255, 255, 0.05); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: var(--color-light-teal); border-radius: 3px; }

    /* ---------- BANNER & PROFILE INFO ---------- */
    .pf-banner {
        position: relative;
        height: 200px;
        background: linear-gradient(135deg, #0d1614 0%, #15201E 40%, #1a3530 80%, #2a4a42 100%);
        border-radius: 20px;
        overflow: hidden;
        margin-bottom: 24px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    .pf-banner-cover {
        width: 100%; height: 100%;
        object-fit: cover;
        position: absolute;
        inset: 0;
        opacity: 0.6;
    }
    .pf-banner-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to bottom, transparent 20%, rgba(21,32,30,0.95) 100%);
    }

    .pf-profile-bar {
        position: absolute;
        bottom: 20px;
        left: 24px;
        right: 24px;
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 16px;
        z-index: 10;
    }
    .pf-avatar-wrap {
        position: relative;
        flex-shrink: 0;
    }
    .pf-avatar {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid var(--color-bg-dark);
        object-fit: cover;
        background: #2a4a42;
        box-shadow: 0 4px 15px rgba(0,0,0,0.5);
    }
    .pf-user-info { flex: 1; }
    .pf-user-name {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 1.4rem;
        font-weight: 800;
        color: #fff;
        line-height: 1.2;
        margin-bottom: 4px;
    }
    .pf-user-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        font-size: 0.75rem;
        color: var(--color-text-muted);
    }
    .pf-edit-btn {
        background: rgba(55,124,128,0.2);
        border: 1px solid rgba(55,124,128,0.5);
        color: #7ecdd1;
        font-size: 0.8rem;
        font-weight: 700;
        padding: 8px 18px;
        border-radius: 50px;
        transition: all 0.2s;
        cursor: pointer;
        display: flex; align-items: center; gap: 6px;
    }
    .pf-edit-btn:hover {
        background: rgba(55,124,128,0.4);
        color: #fff;
    }

    /* ---------- TAB NAV (PILL SHAPE) ---------- */
    .pf-tabs {
        display: flex;
        gap: 12px;
        overflow-x: auto;
        scrollbar-width: none;
        margin-bottom: 20px;
    }
    .pf-tabs::-webkit-scrollbar { display: none; }
    .pf-tab-btn {
        flex-shrink: 0;
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--color-text-muted);
        background: rgba(21, 32, 30, 0.6);
        border: 1px solid rgba(55, 77, 73, 0.4);
        border-radius: 50px; /* Pill shape */
        cursor: pointer;
        transition: all 0.25s;
    }
    .pf-tab-btn:hover {
        background: rgba(55, 77, 73, 0.6);
        color: #fff;
    }
    .pf-tab-btn.active {
        background: var(--color-light-teal);
        color: #fff;
        border-color: var(--color-light-teal);
        box-shadow: 0 4px 12px rgba(55,124,128,0.4);
    }
    .pf-tab-badge {
        background: rgba(255,255,255,0.2);
        padding: 2px 6px;
        border-radius: 20px;
        font-size: 0.65rem;
    }

    /* ---------- CONTENT CARDS ---------- */
    .pf-card {
        background: rgba(21, 32, 30, 0.8);
        border: 1px solid rgba(55, 77, 73, 0.4);
        border-radius: 16px;
        padding: 18px;
        margin-bottom: 16px;
        transition: transform 0.2s, border-color 0.2s;
    }
    .pf-card:hover {
        border-color: rgba(55, 124, 128, 0.5);
        transform: translateY(-2px);
    }
    .pf-title { font-weight: 700; color: #fff; font-size: 0.95rem; margin-bottom: 6px; display: block; text-decoration: none; }
    .pf-title:hover { color: var(--color-light-teal); }
    .pf-body { font-size: 0.8rem; color: rgba(177,205,206,0.7); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin-bottom: 10px; }
    .pf-meta { font-size: 0.72rem; color: rgba(177,205,206,0.5); display: flex; gap: 12px; }

    /* ---------- MODAL (Drag & Drop + Grid Select) ---------- */
    .pf-modal-overlay {
        position: fixed; inset: 0; background: rgba(0,0,0,0.8); z-index: 9999;
        display: flex; items-center; justify-content: center;
        opacity: 0; pointer-events: none; transition: opacity 0.3s;
        padding: 20px;
    }
    .pf-modal-overlay.open { opacity: 1; pointer-events: all; }
    .pf-modal {
        background: #1E2E2B; border: 1px solid #374D49; border-radius: 20px;
        width: 100%; max-width: 550px; margin: auto; padding: 24px;
        transform: scale(0.95); transition: transform 0.3s;
        max-height: 90vh; overflow-y: auto;
    }
    .pf-modal-overlay.open .pf-modal { transform: scale(1); }
    .pf-input {
        width: 100%; background: rgba(13,19,20,0.8); border: 1px solid #374D49;
        border-radius: 12px; color: #fff; font-size: 0.85rem; padding: 10px 14px;
        margin-bottom: 16px; outline: none;
    }
    .pf-input:focus { border-color: var(--color-light-teal); }
    .pf-label { display: block; font-size: 0.75rem; font-weight: 700; color: var(--color-text-muted); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em; }
    
    /* Drag & Drop Avatar */
    .avatar-drop-zone {
        border: 2px dashed #374D49;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        background: rgba(13,19,20,0.5);
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        margin-bottom: 16px;
    }
    .avatar-drop-zone:hover, .avatar-drop-zone.dragover {
        border-color: var(--color-light-teal);
        background: rgba(55,124,128,0.1);
    }
    .avatar-preview-img { width: 80px; height: 80px; border-radius: 50%; object-fit: cover; margin: 0 auto 10px; display: block; border: 2px solid #374D49; }

    /* Grid Select Banner */
    .banner-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
        margin-bottom: 20px;
    }
    .banner-option {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        border: 2px solid transparent;
        height: 80px;
    }
    .banner-option img { width: 100%; height: 100%; object-fit: cover; }
    .banner-option input[type="radio"] { display: none; }
    .banner-option.selected { border-color: var(--color-light-teal); }
    .banner-option.selected::after {
        content: '\F26A'; /* bi-check-circle-fill */
        font-family: bootstrap-icons;
        position: absolute;
        bottom: 6px; right: 6px;
        color: var(--color-light-teal);
        font-size: 1.2rem;
        background: #fff;
        border-radius: 50%;
        width: 18px; height: 18px;
        display: flex; align-items: center; justify-content: center;
    }
    .pf-save-btn { width: 100%; background: var(--color-light-teal); color: #fff; font-weight: 700; padding: 12px; border-radius: 50px; transition: opacity 0.2s; }
    .pf-save-btn:hover { opacity: 0.9; }
</style>

<div class="forum-container font-display pb-12">
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
            <!-- LEFT SIDEBAR: Nav & Chats (From Forum Diskusi) -->
            <div class="lg:col-span-3 flex flex-col gap-8 lg:border-r lg:border-[#374D49]/40 lg:pr-6">
                <!-- Navigation Options -->
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
                            <a href="<?= base_url('linkedin') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40">
                                <i class="bi bi-linkedin text-[#0077b5] text-xl bg-white rounded flex items-center justify-center h-5 w-5 leading-none"></i> LinkedIn Alumni
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('profile') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all bg-[#374D49] text-white shadow-sm">
                                <div class="w-6 h-6 rounded-full overflow-hidden bg-teal-800 flex-shrink-0">
                                    <img src="<?= !empty($user->avatar) ? base_url($user->avatar) : base_url('assets/images/photo.png') ?>" alt="Avatar" class="w-full h-full object-cover">
                                </div>
                                Profil Saya
                            </a>
                        </li>
                    </ul>
                </div>
                
                <hr class="border-[#374D49]/40">

                <!-- Realtime Chat sidebar list -->
                <div class="flex-1 flex flex-col min-h-[300px]">
                    <h3 class="text-xl font-bold text-white mb-4">Chat</h3>
                    <div class="mb-4 relative">
                        <input type="text" id="chatSearchInput" oninput="filterChatContacts()" placeholder="Cari kontak..." 
                               class="w-full bg-[#15201E] text-white placeholder-white/30 text-xs rounded-xl py-2.5 pl-9 pr-4 border border-[#374D49]/50 focus:outline-none focus:ring-1 focus:ring-[#377C80] transition-all">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-white/40 text-xs"></i>
                    </div>
                    <div id="chatContactsList" class="space-y-2 overflow-y-auto max-h-[250px] custom-scrollbar flex-1">
                        <div class="text-center text-[#B1CDCE]/50 text-xs py-10">Chat terhubung di forum...</div>
                    </div>
                </div>
            </div>

            <!-- RIGHT CONTENT: Profile Banner, Tabs, Feeds -->
            <div class="lg:col-span-9 flex flex-col">
                
                <!-- Profile Banner & Info -->
                <div class="pf-banner">
                    <?php if (!empty($user->cover_banner) && file_exists('./' . $user->cover_banner)): ?>
                        <img src="<?= base_url($user->cover_banner) ?>" class="pf-banner-cover" alt="Cover">
                    <?php endif; ?>
                    <div class="pf-banner-overlay"></div>
                    
                    <div class="pf-profile-bar">
                        <div class="flex items-end gap-4">
                            <div class="pf-avatar-wrap">
                                <img src="<?= !empty($user->avatar) && file_exists('./' . $user->avatar) ? base_url($user->avatar) : base_url('assets/images/photo.png') ?>" alt="Avatar" class="pf-avatar">
                            </div>
                            <div class="pf-user-info pb-2">
                                <div class="pf-user-name"><?= htmlspecialchars($user->full_name) ?></div>
                                <div class="pf-user-meta mb-2">
                                    <?php if (!empty($user->username)): ?>
                                    <span><i class="bi bi-at"></i> <?= htmlspecialchars($user->username) ?></span>
                                    <?php endif; ?>
                                    <?php if (!empty($user->location)): ?>
                                    <span><i class="bi bi-geo-alt"></i> <?= htmlspecialchars($user->location) ?></span>
                                    <?php endif; ?>
                                    <span><i class="bi bi-calendar3"></i> Bergabung <?= fmt_date_pf($user->created_at ?? date('Y-m-d'), $months_map) ?></span>
                                </div>
                                <?php if(isset($user->open_to_work) && $user->open_to_work == 1): ?>
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-0.5 bg-[#377C80]/30 text-[#7ecdd1] text-[10px] font-bold rounded border border-[#377C80]/50">
                                            Open to Work
                                        </span>
                                        <?php if(!empty($user->work_role)): ?>
                                            <span class="text-xs text-[#E49438] font-bold"><?= htmlspecialchars($user->work_role) ?></span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($user->bio)): ?>
                                    <p class="text-xs text-[#B1CDCE]/80 mt-1 mb-0 max-w-lg"><?= htmlspecialchars($user->bio) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                        <button onclick="openEditModal()" class="pf-edit-btn mb-2">
                            <i class="bi bi-pencil-square"></i> Edit Profil
                        </button>
                    </div>
                </div>

                <!-- Split Content: Tabs+Feeds (Left) and Popular News (Right) -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                    
                    <!-- LEFT FEED COLUMN (Col-8) -->
                    <div class="lg:col-span-8">
                        
                        <!-- TAB NAV (Pill Shaped, aligned with top of right sidebar) -->
                        <div class="pf-tabs">
                            <button class="pf-tab-btn active" onclick="switchTab('posts', this)">
                                <i class="bi bi-grid-3x3-gap"></i> Postingan
                                <span class="pf-tab-badge"><?= count($user_forums) ?></span>
                            </button>
                            <button class="pf-tab-btn" onclick="switchTab('comments', this)">
                                <i class="bi bi-chat-left-text"></i> Komentar
                                <span class="pf-tab-badge"><?= count($user_comments) ?></span>
                            </button>
                            <button class="pf-tab-btn" onclick="switchTab('likes', this)">
                                <i class="bi bi-heart"></i> Disukai
                                <span class="pf-tab-badge"><?= count($user_news_likes) + count($user_forum_likes) ?></span>
                            </button>
                        </div>

                        <!-- TAB: Posts -->
                        <div id="tab-posts" class="pf-tab-content">
                            <?php if (!empty($user_forums)): ?>
                                <?php foreach ($user_forums as $f): ?>
                                <div class="pf-card">
                                    <a href="<?= base_url('forum/view/' . $f->id) ?>" class="pf-title"><?= htmlspecialchars($f->title) ?></a>
                                    <div class="pf-body"><?= htmlspecialchars($f->content) ?></div>
                                    <div class="pf-meta">
                                        <span><i class="bi bi-heart text-[#E49438]"></i> <?= (int)($f->likes_count ?? 0) ?></span>
                                        <span><i class="bi bi-chat text-[#377C80]"></i> <?= (int)($f->comments_count ?? 0) ?></span>
                                        <span><i class="bi bi-clock"></i> <?= time_elapsed_string($f->created_at) ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-10 text-[#B1CDCE]/50">
                                    <i class="bi bi-grid-3x3-gap text-3xl block mb-2"></i> Belum ada postingan.
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- TAB: Comments -->
                        <div id="tab-comments" class="pf-tab-content hidden">
                            <?php if (!empty($user_comments)): ?>
                                <?php foreach ($user_comments as $c): ?>
                                <div class="pf-card">
                                    <a href="<?= base_url('forum/view/' . ($c->forum_id ?? '')) ?>" class="text-xs text-[#377C80] font-bold mb-2 block hover:underline">
                                        <i class="bi bi-reply"></i> Di: <?= htmlspecialchars($c->forum_title ?? '-') ?>
                                    </a>
                                    <div class="text-sm text-[#B1CDCE] mb-2 leading-relaxed"><?= htmlspecialchars($c->comment) ?></div>
                                    <div class="pf-meta">
                                        <span><i class="bi bi-clock"></i> <?= time_elapsed_string($c->created_at) ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-10 text-[#B1CDCE]/50">
                                    <i class="bi bi-chat-left-text text-3xl block mb-2"></i> Belum ada komentar.
                                </div>
                            <?php endif; ?>
                        </div>

                        <!-- TAB: Likes -->
                        <div id="tab-likes" class="pf-tab-content hidden">
                            <h4 class="text-xs font-bold text-[#B1CDCE] mb-3">Berita</h4>
                            <?php if (!empty($user_news_likes)): ?>
                                <?php foreach ($user_news_likes as $nl): ?>
                                <?php $nlThumb = !empty($nl['thumbnail']) && file_exists('./' . $nl['thumbnail']) ? $nl['thumbnail'] : 'assets/images/berita/berita1.png'; ?>
                                <a href="<?= base_url('berita/' . ($nl['slug'] ?? '')) ?>" class="pf-card flex flex-row gap-4 !p-3">
                                    <div class="w-24 h-20 rounded-xl overflow-hidden shrink-0">
                                        <img src="<?= base_url($nlThumb) ?>" class="w-full h-full object-cover">
                                    </div>
                                    <div class="flex-1 py-1">
                                        <div class="font-bold text-sm text-white mb-1 leading-tight line-clamp-2"><?= htmlspecialchars($nl['title'] ?? '') ?></div>
                                        <div class="pf-meta">
                                            <span class="text-red-400"><i class="bi bi-heart-fill"></i> <?= (int)($nl['likes'] ?? 0) ?></span>
                                            <span><i class="bi bi-eye"></i> <?= (int)($nl['views'] ?? 0) ?></span>
                                        </div>
                                    </div>
                                </a>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-[#B1CDCE]/50 text-xs mb-4">Belum ada berita disukai.</div>
                            <?php endif; ?>

                            <h4 class="text-xs font-bold text-[#B1CDCE] mb-3 mt-4">Postingan Forum</h4>
                            <?php if (!empty($user_forum_likes)): ?>
                                <?php foreach ($user_forum_likes as $fl): ?>
                                <div class="pf-card">
                                    <a href="<?= base_url('forum/view/' . $fl->id) ?>" class="pf-title"><?= htmlspecialchars($fl->title) ?></a>
                                    <div class="pf-body"><?= htmlspecialchars($fl->content) ?></div>
                                    <div class="pf-meta">
                                        <span class="text-[#E49438]"><i class="bi bi-heart-fill"></i> <?= (int)($fl->likes_count ?? 0) ?></span>
                                        <span><i class="bi bi-chat text-[#377C80]"></i> <?= (int)($fl->comments_count ?? 0) ?></span>
                                        <span><i class="bi bi-clock"></i> <?= time_elapsed_string($fl->created_at) ?></span>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-[#B1CDCE]/50 text-xs">Belum ada postingan disukai.</div>
                            <?php endif; ?>
                        </div>

                    </div>

                    <!-- RIGHT COLUMN: Popular News (Col-4) -->
                    <div class="lg:col-span-4">
                        <div class="sticky top-24">
                            <h3 class="text-xs font-bold text-[#B1CDCE] uppercase tracking-widest mb-4 flex items-center gap-2">
                                <i class="bi bi-fire text-[#E49438] text-sm"></i> Berita Terpopuler
                                <div class="flex-1 h-px bg-gradient-to-r from-[#377C80] to-transparent ml-2"></div>
                            </h3>
                            
                            <div class="bg-[#1E2E2B] border border-[#374D49] rounded-2xl p-4">
                                <?php if (!empty($most_viewed_news)): ?>
                                    <div class="space-y-3">
                                        <?php foreach ($most_viewed_news as $i => $mv): ?>
                                        <?php $mvThumb = !empty($mv['thumbnail']) && file_exists('./' . $mv['thumbnail']) ? $mv['thumbnail'] : 'assets/images/berita/berita1.png'; ?>
                                        <a href="<?= base_url('berita/' . ($mv['slug'] ?? '')) ?>" class="flex gap-3 items-center group">
                                            <div class="font-bold text-[#E49438] text-xs w-4"><?= $i+1 ?>.</div>
                                            <div class="w-12 h-12 rounded-lg overflow-hidden shrink-0">
                                                <img src="<?= base_url($mvThumb) ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-xs font-bold text-white line-clamp-2 leading-tight group-hover:text-[#377C80] transition-colors mb-1"><?= htmlspecialchars($mv['title']) ?></div>
                                                <div class="text-[10px] text-[#B1CDCE]/60"><i class="bi bi-eye"></i> <?= number_format($mv['views'] ?? 0) ?> views</div>
                                            </div>
                                        </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <div class="text-center py-6 text-[#B1CDCE]/40 text-xs">Belum ada data.</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </main>
</div>

<!-- EDIT PROFILE MODAL -->
<div class="pf-modal-overlay" id="editProfileModal" onclick="closeEditModalOutside(event)">
    <div class="pf-modal" onclick="event.stopPropagation()">
        <div class="flex justify-between items-center mb-6 border-b border-[#374D49] pb-4">
            <h3 class="font-bold text-lg text-white flex items-center gap-2">
                <i class="bi bi-person-gear text-[#377C80]"></i> Edit Profil
            </h3>
            <button onclick="closeEditModal()" class="text-[#B1CDCE]/60 hover:text-white transition-colors">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <?= form_open_multipart('profile/update') ?>
            
            <!-- Drag & Drop Avatar -->
            <label class="pf-label">Foto Profil</label>
            <div class="avatar-drop-zone" id="avatarDropZone">
                <img src="<?= !empty($user->avatar) && file_exists('./' . $user->avatar) ? base_url($user->avatar) : base_url('assets/images/photo.png') ?>" 
                     alt="Avatar Preview" class="avatar-preview-img" id="avatarPreview">
                <p class="text-xs text-[#B1CDCE] mb-1 font-semibold">Tarik & Lepas foto ke sini</p>
                <p class="text-[10px] text-[#B1CDCE]/50">atau klik untuk menelusuri file (Max 2MB)</p>
                <input type="file" name="avatar" id="avatarInput" accept="image/*" class="hidden">
            </div>

            <label class="pf-label">Bio</label>
            <textarea name="bio" class="pf-input" rows="2" placeholder="Ceritakan tentang Anda..."><?= htmlspecialchars($user->bio ?? '') ?></textarea>

            <label class="pf-label">Lokasi</label>
            <input type="text" name="location" class="pf-input" value="<?= htmlspecialchars($user->location ?? '') ?>">

            <hr class="border-[#374D49] my-4">
            <h4 class="text-sm font-bold text-[#E49438] mb-3">Pengaturan Karir</h4>
            
            <label class="flex items-center gap-2 cursor-pointer mb-3">
                <input type="checkbox" name="open_to_work" value="1" <?= (isset($user->open_to_work) && $user->open_to_work == 1) ? 'checked' : '' ?> class="w-4 h-4 accent-[#377C80]">
                <span class="text-sm font-bold text-white">Open to Work (Mencari Pekerjaan)</span>
            </label>

            <div class="grid grid-cols-2 gap-4 mb-2">
                <div>
                    <label class="pf-label">Peran yang dicari</label>
                    <input type="text" name="work_role" class="pf-input !mb-0" value="<?= htmlspecialchars($user->work_role ?? '') ?>" placeholder="Contoh: Software Engineer">
                </div>
                <div>
                    <label class="pf-label">Status Pengalaman</label>
                    <label class="flex items-center gap-2 cursor-pointer mt-2 bg-[rgba(13,19,20,0.8)] border border-[#374D49] rounded-xl px-3 py-2">
                        <input type="checkbox" name="is_fresh_graduate" value="1" <?= (isset($user->is_fresh_graduate) && $user->is_fresh_graduate == 1) ? 'checked' : '' ?> class="w-4 h-4 accent-[#377C80]">
                        <span class="text-xs text-white">Fresh Graduate</span>
                    </label>
                </div>
            </div>
            
            <hr class="border-[#374D49] my-4">

            <!-- Selectable Banners -->
            <label class="pf-label">Pilih Banner</label>
            <div class="banner-grid">
                <?php if(!empty($available_banners)): ?>
                    <?php foreach($available_banners as $ban): ?>
                        <label class="banner-option <?= ($user->cover_banner == $ban['image_path']) ? 'selected' : '' ?>" onclick="selectBanner(this)">
                            <img src="<?= base_url($ban['image_path']) ?>" alt="Banner">
                            <input type="radio" name="cover_banner" value="<?= $ban['image_path'] ?>" <?= ($user->cover_banner == $ban['image_path']) ? 'checked' : '' ?>>
                        </label>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-span-2 text-xs text-[#B1CDCE]/50 py-2">Belum ada pilihan banner tersedia.</div>
                <?php endif; ?>
            </div>

            <button type="submit" class="pf-save-btn mt-2">Simpan Perubahan</button>
        <?= form_close() ?>
    </div>
</div>

<script>
    // Tab switching
    function switchTab(tab, btn) {
        document.querySelectorAll('.pf-tab-content').forEach(el => el.classList.add('hidden'));
        document.querySelectorAll('.pf-tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + tab).classList.remove('hidden');
        btn.classList.add('active');
    }

    // Modal behavior
    function openEditModal() {
        document.getElementById('editProfileModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal() {
        document.getElementById('editProfileModal').classList.remove('open');
        document.body.style.overflow = '';
    }
    function closeEditModalOutside(e) {
        closeEditModal();
    }
    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeEditModal(); });

    // Drag and Drop Avatar logic
    const dropZone = document.getElementById('avatarDropZone');
    const fileInput = document.getElementById('avatarInput');
    const preview = document.getElementById('avatarPreview');

    dropZone.addEventListener('click', () => fileInput.click());
    
    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('dragover');
    });
    
    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('dragover');
    });
    
    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('dragover');
        
        if (e.dataTransfer.files && e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            updatePreview(e.dataTransfer.files[0]);
        }
    });

    fileInput.addEventListener('change', (e) => {
        if (e.target.files && e.target.files.length > 0) {
            updatePreview(e.target.files[0]);
        }
    });

    function updatePreview(file) {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = e => { preview.src = e.target.result; };
            reader.readAsDataURL(file);
        }
    }

    // Select Banner visually
    function selectBanner(labelEl) {
        document.querySelectorAll('.banner-option').forEach(el => el.classList.remove('selected'));
        labelEl.classList.add('selected');
    }
</script>

<!-- CHAT WIDGET & JS -->
<div id="floatingChatWidget" class="fixed bottom-0 right-6 w-80 sm:w-96 bg-[#274D4F] border border-teal-800/50 rounded-t-2xl shadow-[0_-8px_30px_rgba(0,0,0,0.5)] z-50 flex flex-col hidden transition-all duration-300">
    <!-- Chat Header -->
    <div class="bg-[#1F3637] px-4 py-3 flex items-center justify-between rounded-t-2xl border-b border-teal-800/30">
        <div class="flex items-center gap-2 min-w-0">
            <div class="w-8 h-8 rounded-full overflow-hidden bg-teal-800 flex-shrink-0">
                <img id="chatActiveAvatar" src="<?= base_url('assets/images/photo.png') ?>" alt="Contact avatar" class="w-full h-full object-cover">
            </div>
            <div class="min-w-0">
                <h4 id="chatActiveName" class="text-xs font-bold text-white truncate">Nama Kontak</h4>
                <p class="text-[9px] text-teal-400 font-semibold flex items-center gap-1">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> online
                </p>
            </div>
        </div>
        <div class="flex items-center gap-2">
            <button onclick="minimizeChatWidget()" class="text-white/60 hover:text-white p-1 hover:bg-white/5 rounded transition-all">
                <i class="bi bi-dash-lg"></i>
            </button>
            <button onclick="closeChatWidget()" class="text-white/60 hover:text-white p-1 hover:bg-white/5 rounded transition-all">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>
    </div>

    <!-- Chat Messages History Box -->
    <div id="chatMessagesBox" class="h-80 overflow-y-auto p-4 space-y-3 custom-scrollbar bg-[#274D4F] flex flex-col">
        <!-- Messages loaded dynamically via API -->
    </div>

    <!-- Chat Input Form -->
    <div class="p-3 border-t border-teal-800/30 bg-[#1F3637]">
        <form id="chatInputForm" onsubmit="sendChatMessage(event)" class="flex items-center gap-2">
            <input type="hidden" id="chatReceiverId" value="">
            <input type="text" id="chatMessageField" placeholder="Ketik pesan..." required autocomplete="off"
                   class="flex-1 bg-[#274D4F] text-white placeholder-teal-300/40 text-xs rounded-full py-2.5 px-4 focus:outline-none focus:ring-1 focus:ring-[#377C80] border border-teal-800/30">
            <button type="submit" class="w-9 h-9 rounded-full bg-[#377C80] hover:bg-teal-700 text-white flex items-center justify-center flex-shrink-0 shadow-md transition-all">
                <i class="bi bi-send-fill text-xs"></i>
            </button>
        </form>
    </div>
</div>

<script>
    let activeChatUserId = null;
    let chatRefreshInterval = null;
    let allContacts = [];

    function loadChatContacts() {
        fetch('<?= base_url('forum/chat_contacts') ?>')
            .then(res => res.json())
            .then(users => {
                allContacts = users;
                filterChatContacts();
            });
    }

    function filterChatContacts() {
        const query = document.getElementById('chatSearchInput').value.toLowerCase().trim();
        const filtered = allContacts.filter(u => u.full_name.toLowerCase().includes(query));
        renderChatContacts(filtered);
    }

    function renderChatContacts(users) {
        const list = document.getElementById('chatContactsList');
        if (users.length === 0) {
            list.innerHTML = '<div class="text-center text-white/40 text-xs py-10">Kontak tidak ditemukan.</div>';
            return;
        }

        let html = '';
        users.forEach(u => {
            const avatarSrc = u.avatar ? `<?= base_url() ?>${u.avatar}` : `<?= base_url('assets/images/photo.png') ?>`;
            const badge = u.unread_count > 0 ? `<span class="bg-emerald-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0">${u.unread_count}</span>` : '';
            
            html += `
                <div onclick="openChatWidget(${u.id}, '${u.full_name.replace(/'/g, "\\'")}', '${avatarSrc}')" 
                     class="flex items-center justify-between gap-3 p-2 rounded-xl hover:bg-white/5 cursor-pointer transition-all border border-transparent hover:border-[#374D49]/30">
                    <div class="flex items-center gap-3 min-w-0">
                        <div class="w-9 h-9 rounded-full overflow-hidden bg-teal-800 flex-shrink-0">
                            <img src="${avatarSrc}" class="w-full h-full object-cover">
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-xs font-bold text-white truncate">${u.full_name}</h4>
                            <p class="text-[10px] text-white/50 truncate mt-0.5">${u.last_message || 'Belum ada pesan'}</p>
                        </div>
                    </div>
                    <div class="flex flex-col items-end gap-1 flex-shrink-0">
                        <span class="text-[9px] text-white/40">${u.last_time}</span>
                        ${badge}
                    </div>
                </div>
            `;
        });
        list.innerHTML = html;
    }

    function openChatWidget(userId, userName, avatarUrl) {
        activeChatUserId = userId;
        document.getElementById('chatReceiverId').value = userId;
        document.getElementById('chatActiveName').textContent = userName;
        document.getElementById('chatActiveAvatar').src = avatarUrl;
        document.getElementById('floatingChatWidget').classList.remove('hidden');
        loadChatMessages();
        clearInterval(chatRefreshInterval);
        chatRefreshInterval = setInterval(loadChatMessages, 2500);
    }

    function minimizeChatWidget() {
        document.getElementById('chatMessagesBox').classList.toggle('hidden');
        document.getElementById('chatInputForm').parentElement.classList.toggle('hidden');
    }

    function closeChatWidget() {
        document.getElementById('floatingChatWidget').classList.add('hidden');
        activeChatUserId = null;
        clearInterval(chatRefreshInterval);
        loadChatContacts();
    }

    function loadChatMessages() {
        if (!activeChatUserId) return;
        fetch(`<?= base_url('forum/chat_messages/') ?>${activeChatUserId}`)
            .then(res => res.json())
            .then(messages => {
                const box = document.getElementById('chatMessagesBox');
                let html = '';
                if (messages.length === 0) {
                    box.innerHTML = '<div class="text-center text-white/30 text-[10px] my-auto py-10">Mulai kirim pesan pertama!</div>';
                    return;
                }
                const loggedUser = <?= $this->session->userdata('user_id') ?>;
                messages.forEach(m => {
                    const isSender = parseInt(m.sender_id) === loggedUser;
                    if (isSender) {
                        html += `
                            <div class="flex flex-col items-end gap-1 max-w-[80%] self-end">
                                <div class="bg-[#377C80] text-white text-xs py-2 px-3 rounded-2-xl rounded-tr-none shadow-sm">
                                    ${escapeHtml(m.message)}
                                </div>
                                <span class="text-[8px] text-white/40 pr-1">${m.formatted_time}</span>
                            </div>
                        `;
                    } else {
                        html += `
                            <div class="flex gap-2 max-w-[80%]">
                                <div class="w-6 h-6 rounded-full overflow-hidden bg-teal-800 flex-shrink-0 mt-1">
                                    <img src="${m.sender_avatar ? `<?= base_url() ?>${m.sender_avatar}` : `<?= base_url('assets/images/photo.png') ?>`}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex flex-col gap-1">
                                    <div class="bg-[#1b3435] text-white text-xs py-2 px-3 rounded-2-xl rounded-tl-none shadow-sm">
                                        ${escapeHtml(m.message)}
                                    </div>
                                    <span class="text-[8px] text-white/40 pl-1">${m.formatted_time}</span>
                                </div>
                            </div>
                        `;
                    }
                });
                const shouldScroll = box.scrollTop + box.clientHeight >= box.scrollHeight - 50 || box.innerHTML.length < 50;
                box.innerHTML = html;
                if (shouldScroll) box.scrollTop = box.scrollHeight;
            });
    }

    function sendChatMessage(e) {
        e.preventDefault();
        const receiverId = document.getElementById('chatReceiverId').value;
        const msgField = document.getElementById('chatMessageField');
        const message = msgField.value.trim();
        if (message === '' || !receiverId) return;

        const formData = new FormData();
        formData.append('receiver_id', receiverId);
        formData.append('message', message);
        msgField.value = '';

        fetch('<?= base_url('forum/send_chat_message') ?>', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                loadChatMessages();
                loadChatContacts();
            }
        });
    }

    function escapeHtml(text) {
        return text.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
    }

    document.addEventListener("DOMContentLoaded", function() {
        loadChatContacts();
        setInterval(loadChatContacts, 6000);
    });
</script>
