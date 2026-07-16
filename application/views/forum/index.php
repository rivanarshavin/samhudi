<?php
/**
 * @var object $user
 * @var array $forums
 * @var array $popular_weekly
 * @var string $filter
 * @var string $search
 */
?>

<!-- Custom Style for Forum Diskusi -->
<style>
    /* Color Palette matching premium mockup and Figma stops */
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

    .nav-sidebar-link:hover {
        transform: translateX(4px);
    }

    /* Custom scrollbar for chat and feed */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.05);
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: var(--color-light-teal);
        border-radius: 3px;
    }
</style>

<div class="forum-container font-display pb-12">

    <!-- MAIN BODY LAYOUT -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8">
        <!-- Toast Notification Container -->
        <div class="toast-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <div id="actionToast" class="toast align-items-center text-white bg-[#377C80] border-0" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="d-flex">
                    <div class="toast-body" id="toastMessage">
                        Notifikasi
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            <!-- LEFT SIDEBAR: Nav & Chats -->
            <div class="lg:col-span-3 flex flex-col gap-8 lg:border-r lg:border-[#374D49]/40 lg:pr-6">
                <!-- Navigation Options -->
                <div>
                    <ul class="space-y-4 font-semibold">
                        <li>
                            <a href="<?= base_url('forum?filter=all') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all <?= ($filter === 'all' && empty($search)) ? 'bg-[#374D49] text-white shadow-sm' : 'text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40' ?>">
                                <i class="bi bi-house text-xl"></i> Beranda
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('forum?filter=populer') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all <?= ($filter === 'populer') ? 'bg-[#374D49] text-white shadow-sm' : 'text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40' ?>">
                                <i class="bi bi-star-fill text-[#E49438] text-xl"></i> Populer
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('forum?filter=my_posts') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all <?= ($filter === 'my_posts') ? 'active bg-[#374D49] text-white shadow-sm' : 'text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40' ?>">
                                <i class="bi bi-clock text-xl"></i> Terbaru
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('forum?filter=saved') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all <?= ($filter === 'saved') ? 'bg-[#374D49] text-white shadow-sm' : 'text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40' ?>">
                                <i class="bi bi-bookmark-fill text-[#E49438] text-xl"></i> Simpan
                            </a>
                        </li>
                        <li>
                            <a href="<?= base_url('linkedin') ?>" class="nav-sidebar-link flex items-center gap-4 py-2.5 px-4 rounded-xl transition-all text-[#B1CDCE] hover:text-white hover:bg-[#374D49]/40">
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

                <hr class="border-[#374D49]/40">

                <!-- Realtime Chat sidebar list -->
                <div class="flex-1 flex flex-col min-h-[300px]">
                    <h3 class="text-xl font-bold text-white mb-4">Chat</h3>
                    
                    <!-- Chat Search Bar -->
                    <div class="mb-4 relative">
                        <input type="text" id="chatSearchInput" oninput="filterChatContacts()" placeholder="Cari kontak..." 
                               class="w-full bg-[#15201E] text-white placeholder-white/30 text-xs rounded-xl py-2.5 pl-9 pr-4 border border-[#374D49]/50 focus:outline-none focus:ring-1 focus:ring-[#377C80] transition-all">
                        <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-white/40 text-xs"></i>
                    </div>

                    <!-- Contacts List Container -->
                    <div id="chatContactsList" class="space-y-2 overflow-y-auto max-h-[250px] custom-scrollbar flex-1">
                        <!-- Loaded dynamically via JS -->
                        <div class="text-center text-[#B1CDCE]/50 text-xs py-10">Memuat chat...</div>
                    </div>

                    <button onclick="focusChatContact()" class="mt-6 w-full bg-[#15201E] text-white hover:bg-[#374D49] text-xs font-semibold py-2.5 px-4 rounded-xl flex items-center justify-center gap-2 border border-[#374D49]/50 transition-all duration-200">
                        <i class="bi bi-chat-left-text"></i> Lihat semua chat
                    </button>
                </div>
            </div>

            <!-- CENTER CONTENT: feeds -->
            <div class="lg:col-span-9 flex flex-col gap-6">

                <!-- TOP BAR: Active Filter Indicator & Search / Create Actions -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
                    <!-- Active Category Pill (Left) -->
                    <div class="px-4 py-1.5 bg-[#374D49] text-white rounded-full text-xs font-semibold shadow-sm w-fit">
                        <?= ($filter === 'all') ? 'Beranda' : (($filter === 'populer') ? 'Populer' : (($filter === 'my_posts') ? 'Terbaru' : 'Simpan')) ?>
                    </div>

                    <!-- Search + Create Button (Right) -->
                    <div class="flex items-center gap-3 flex-1 sm:flex-initial">
                        <form action="<?= base_url('forum') ?>" method="GET" class="relative flex-1 sm:w-64">
                            <input type="hidden" name="filter" value="<?= $filter ?>">
                            <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Cari topik..." 
                                   class="w-full bg-[#15201E] text-white placeholder-white/30 text-xs rounded-full py-2 pl-9 pr-4 border border-[#374D49]/50 focus:outline-none focus:ring-1 focus:ring-[#377C80] transition-all">
                            <i class="bi bi-search absolute left-3 top-1/2 -translate-y-1/2 text-white/40 text-xs"></i>
                        </form>
                        <button onclick="toggleCreateForm()" class="bg-[#E49438] hover:bg-[#c87e2b] hover:scale-105 active:scale-95 text-white font-bold text-xs px-4 py-2 rounded-full transition-all flex items-center gap-1.5 shadow-md flex-shrink-0">
                            <i class="bi bi-plus-lg"></i> Buat
                        </button>
                    </div>
                </div>

                <!-- INLINE CREATE POST FORM (HIDDEN BY DEFAULT, NO POPUP) -->
                <div id="createPostForm" class="hidden bg-[#15201E] rounded-2xl p-6 border border-[#374D49]/50 transition-all duration-300 shadow-xl">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-lg text-white">Mulai Diskusi Baru</h3>
                        <button onclick="toggleCreateForm()" class="text-white/60 hover:text-white text-lg">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>

                    <?= form_open_multipart('forum/create') ?>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-[#B1CDCE] uppercase tracking-wide mb-1">Judul Topik</label>
                                <input type="text" name="title" required placeholder="Judul diskusi kamu..." 
                                       class="w-full bg-[#0d1314] text-white placeholder-white/20 text-sm rounded-xl py-3 px-4 border border-[#374D49]/50 focus:outline-none focus:ring-1 focus:ring-[#377C80] transition-all">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-[#B1CDCE] uppercase tracking-wide mb-1">Isi Diskusi</label>
                                <textarea name="content" required rows="5" placeholder="Tulis apa yang ingin kamu bagikan dengan keluarga..." 
                                          class="w-full bg-[#0d1314] text-white placeholder-white/20 text-sm rounded-xl py-3 px-4 border border-[#374D49]/50 focus:outline-none focus:ring-1 focus:ring-[#377C80] transition-all resize-none"></textarea>
                            </div>

                            <!-- Media Upload Block (Photo/Video) -->
                            <div id="dragDropZone" class="bg-[#0d1314]/50 border border-dashed border-[#374D49]/60 rounded-xl p-4 flex flex-col items-center justify-center text-center transition-all duration-200">
                                <label class="cursor-pointer flex flex-col items-center gap-2 w-full h-full py-2">
                                    <i class="bi bi-camera text-2xl text-[#377C80]"></i>
                                    <span class="text-xs font-semibold text-white/80">Unggah atau seret Foto/Video ke sini</span>
                                    <span class="text-[10px] text-white/40">Dukungan: JPG, PNG, MP4, WebM (Maks. 20MB)</span>
                                    <input type="file" name="media" id="mediaFileInput" accept="image/*,video/*" onchange="previewSelectedMedia(this)" class="hidden">
                                </label>
                                <div id="mediaPreviewContainer" class="hidden mt-2 w-full max-h-96 rounded-lg relative bg-[#0d1314]/50 flex items-center justify-center overflow-hidden border border-[#374D49]/30 p-2">
                                    <button type="button" onclick="clearMediaSelection()" class="absolute top-2 right-2 bg-black/60 text-white rounded-full p-1.5 text-xs hover:bg-black/80 z-10 transition-all">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                    <img id="imagePreview" class="hidden max-h-80 w-auto object-contain rounded-lg">
                                    <video id="videoPreview" class="hidden max-h-80 w-auto object-contain rounded-lg" controls></video>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 mt-6">
                            <button type="button" onclick="toggleCreateForm()" class="font-semibold text-sm text-white/60 hover:text-white px-5 py-2">Batal</button>
                            <button type="submit" class="bg-[#E49438] hover:bg-[#c87e2b] hover:scale-102 hover:shadow-lg active:scale-98 text-white font-bold text-sm px-6 py-2.5 rounded-full transition-all duration-200">
                                Posting Diskusi
                            </button>
                        </div>
                    <?= form_close() ?>
                </div>

                <!-- MAIN POSTS FEED -->
                <div class="space-y-6">

                    <?php if (empty($forums)): ?>
                        <div class="bg-[#15201E] rounded-2xl p-10 text-center border border-[#374D49]/30">
                            <i class="bi bi-chat-square-text text-4xl text-[#B1CDCE]/40 mb-3 block"></i>
                            <p class="text-[#B1CDCE] text-sm">Tidak ada postingan dalam kategori ini.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($forums as $forum): ?>
                            <!-- Post Card -->
                            <div class="bg-[#15201E] rounded-2xl p-6 border border-[#374D49]/40 flex flex-col gap-4 relative shadow-lg">
                                <!-- Delete Button for post creator -->
                                <?php if ($this->session->userdata('user_id') == $forum->created_by): ?>
                                    <button onclick="confirmDelete('<?= base_url('forum/delete/' . $forum->id) ?>')" 
                                       class="absolute top-6 right-6 text-[#B1CDCE]/50 hover:text-red-400 transition-all duration-200"
                                       title="Hapus Postingan">
                                        <i class="bi bi-trash text-base"></i>
                                    </button>
                                <?php endif; ?>

                                <!-- Author info -->
                                <div class="flex items-center gap-3">
                                    <?php if (!empty($forum->author_avatar)): ?>
                                        <div class="w-10 h-10 rounded-full overflow-hidden bg-teal-800 flex-shrink-0 border border-[#374D49]/30">
                                            <img src="<?= base_url($forum->author_avatar) ?>" alt="Avatar" class="w-full h-full object-cover">
                                        </div>
                                    <?php else: ?>
                                        <div class="w-10 h-10 rounded-full bg-teal-700/60 border border-[#374D49]/40 flex items-center justify-center font-bold text-white text-sm select-none flex-shrink-0">
                                            <?= strtoupper(substr($forum->author_name ?? 'U', 0, 1)) ?>
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <h4 class="text-sm font-bold text-white"><?= htmlspecialchars($forum->author_name ?? 'Anggota Keluarga') ?></h4>
                                        <p class="text-[10px] text-[#B1CDCE]/70 mt-0.5">
                                            <?= time_elapsed_string($forum->created_at) ?>
                                        </p>
                                    </div>
                                </div>

                                <!-- Content Body -->
                                <div>
                                    <h3 class="text-base font-bold text-white hover:text-[#E49438] transition-all">
                                        <a href="<?= base_url('forum/view/' . $forum->id) ?>">
                                            <?= htmlspecialchars($forum->title) ?>
                                        </a>
                                    </h3>
                                    <p class="text-xs text-[#B1CDCE] leading-relaxed mt-2 whitespace-pre-line">
                                        <?= htmlspecialchars($forum->content) ?>
                                    </p>
                                </div>

                                <!-- Attached Media -->
                                <?php if (!empty($forum->media_url)): ?>
                                    <div class="rounded-xl overflow-hidden max-h-[450px] w-full border border-[#374D49]/30">
                                        <?php if ($forum->media_type === 'image'): ?>
                                            <img src="<?= base_url($forum->media_url) ?>" alt="Post image" class="w-full h-full object-cover max-h-[450px]">
                                        <?php elseif ($forum->media_type === 'video'): ?>
                                            <video src="<?= base_url($forum->media_url) ?>" controls class="w-full h-full object-cover max-h-[450px]"></video>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <!-- Action Buttons Row -->
                                <div class="flex items-center gap-6 text-xs font-semibold text-[#B1CDCE] pt-2">
                                    <!-- Like -->
                                    <button onclick="likePost(<?= $forum->id ?>, this)" 
                                            class="flex items-center gap-1.5 hover:text-white hover:scale-105 active:scale-95 transition-all duration-200 <?= $forum->liked_by_user ? 'text-red-400 font-bold' : '' ?>">
                                        <i class="<?= $forum->liked_by_user ? 'bi bi-heart-fill text-red-500' : 'bi bi-heart' ?>"></i> 
                                        <span class="like-count"><?= $forum->likes_count ?></span>
                                    </button>
                                    <span class="text-white/20">|</span>

                                    <!-- Comment Link -->
                                    <button onclick="toggleComments(<?= $forum->id ?>, this)" 
                                       class="flex items-center gap-1.5 hover:text-white hover:scale-105 transition-all duration-200">
                                        <i class="bi bi-chat"></i> <span id="comment-count-<?= $forum->id ?>"><?= $forum->comments_count ?></span>
                                    </button>
                                    <span class="text-white/20">|</span>

                                    <!-- Bookmark Save -->
                                    <button onclick="savePost(<?= $forum->id ?>, this)" 
                                            class="flex items-center gap-1.5 hover:text-white hover:scale-105 active:scale-95 transition-all duration-200 <?= $forum->saved_by_user ? 'text-[#E49438] font-bold' : '' ?>">
                                        <i class="<?= $forum->saved_by_user ? 'bi bi-bookmark-fill text-[#E49438]' : 'bi bi-bookmark' ?>"></i> Simpan
                                    </button>
                                    <span class="text-white/20">|</span>

                                    <!-- Share Link (Copy to Clipboard) -->
                                    <button onclick="sharePost('<?= base_url('forum/view/' . $forum->id) ?>')" 
                                            class="flex items-center gap-1.5 hover:text-white hover:scale-105 active:scale-95 transition-all duration-200">
                                        <i class="bi bi-share"></i> Bagikan
                                    </button>
                                </div>

                                <!-- Collapsible Comments & Reply Container -->
                                <div id="comments-section-<?= $forum->id ?>" class="hidden border-t border-[#374D49]/30 pt-4 mt-2">
                                    <!-- Dynamic Comments List -->
                                    <div id="comments-list-<?= $forum->id ?>" class="space-y-3 mb-4 max-h-60 overflow-y-auto custom-scrollbar">
                                        <!-- Comments injected dynamically -->
                                    </div>

                                    <!-- Replying to Indicator Bar -->
                                    <div id="replying-to-bar-<?= $forum->id ?>" class="hidden flex items-center justify-between text-[10px] text-[#B1CDCE] bg-white/5 px-3 py-1.5 rounded-t-xl border-b border-[#374D49]/20 mb-1">
                                        <span>Membalas <span id="replying-to-name-<?= $forum->id ?>" class="font-bold text-white"></span></span>
                                        <button type="button" onclick="cancelReply(<?= $forum->id ?>)" class="text-red-400 hover:underline">Batal</button>
                                    </div>

                                    <!-- Inline Reply Form -->
                                    <?= form_open('forum/comment/' . $forum->id, ['class' => 'flex items-center gap-3', 'onsubmit' => 'submitInlineComment(event, ' . $forum->id . ', this)']) ?>
                                        <input type="hidden" name="parent_id" id="parent-id-field-<?= $forum->id ?>" value="">
                                        <?php if (!empty($user->avatar)): ?>
                                            <div class="w-8 h-8 rounded-full overflow-hidden bg-teal-800 flex-shrink-0 border border-[#374D49]/30">
                                                <img src="<?= base_url($user->avatar) ?>" alt="Avatar" class="w-full h-full object-cover">
                                            </div>
                                        <?php else: ?>
                                            <div class="w-8 h-8 rounded-full bg-teal-700/60 border border-[#374D49]/40 flex items-center justify-center font-bold text-white text-xs select-none flex-shrink-0">
                                                <?= strtoupper(substr($this->session->userdata('full_name') ?? 'U', 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="flex-1 relative">
                                            <input type="text" name="comment" id="comment-input-field-<?= $forum->id ?>" required placeholder="Tulis balasan Anda..." 
                                                   class="w-full bg-[#0d1314] hover:bg-[#0d1314]/80 text-white placeholder-white/20 text-xs rounded-full py-2.5 pl-4 pr-12 border border-[#374D49]/50 focus:outline-none focus:ring-1 focus:ring-[#377C80] transition-all">
                                            <button type="submit" class="absolute right-1 top-1/2 -translate-y-1/2 w-8 h-8 rounded-full bg-[#E49438] hover:bg-[#c87e2b] hover:scale-105 active:scale-95 text-white flex items-center justify-center transition-all duration-200">
                                                <i class="bi bi-arrow-right-short text-lg"></i>
                                            </button>
                                        </div>
                                    <?= form_close() ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</div>
<!-- Custom Confirmation Modal -->
<div id="customConfirmModal" class="fixed inset-0 bg-black/60 z-[9999] flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
    <div class="bg-[#15201E] border border-[#374D49]/60 rounded-2xl p-6 max-w-sm w-full mx-4 shadow-[0_10px_30px_rgba(0,0,0,0.5)] transform scale-95 transition-transform duration-300" id="customConfirmContent">
        <h4 class="text-base font-bold text-white mb-2">Hapus Postingan</h4>
        <p class="text-xs text-[#B1CDCE] mb-6">Apakah Anda yakin ingin menghapus postingan ini? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="flex justify-end gap-3">
            <button onclick="closeConfirmModal()" class="px-4 py-2 text-xs font-semibold text-white/60 hover:text-white transition-all">Batal</button>
            <a id="customConfirmDeleteBtn" href="#" class="px-5 py-2 bg-red-500 hover:bg-red-600 active:scale-95 text-white text-xs font-bold rounded-full shadow-md transition-all">Hapus</a>
        </div>
    </div>
</div>


<!-- FLOATING CHAT PANEL SYSTEM (100% INLINE AND REALTIME) -->
<div id="floatingChatWidget" class="fixed bottom-0 right-6 w-80 sm:w-96 bg-[#274D4F] border border-teal-800/50 rounded-t-2xl shadow-[0_-8px_30px_rgba(0,0,0,0.5)] z-50 flex flex-col hidden transition-all duration-300">
    <!-- Chat Header -->
    <div class="bg-[#1F3637] px-4 py-3 flex items-center justify-between rounded-t-2xl border-b border-teal-800/30">
        <div class="flex items-center gap-2 min-w-0">
            <div id="chatActiveAvatarContainer" class="w-8 h-8 rounded-full overflow-hidden bg-teal-800 flex-shrink-0 flex items-center justify-center font-bold text-white text-xs select-none border border-teal-700/50">
                <img id="chatActiveAvatar" src="" alt="Contact avatar" class="w-full h-full object-cover">
                <span id="chatActiveInitial" class="hidden"></span>
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

<!-- SCRIPTS FOR INLINE AND REALTIME CHAT -->
<script>
    const CURRENT_USER_ID = <?= (int) $this->session->userdata('user_id') ?>;
    let activeChatUserId = null;
    let chatRefreshInterval = null;
    let allContacts = [];

    // Toast alert helper
    function showToast(message) {
        const toastEl = document.getElementById('actionToast');
        const toastMsg = document.getElementById('toastMessage');
        toastMsg.innerText = message;
        const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
        toast.show();
    }

    // Custom Confirmation Modal Actions
    function confirmDelete(url) {
        const modal = document.getElementById('customConfirmModal');
        const content = document.getElementById('customConfirmContent');
        const deleteBtn = document.getElementById('customConfirmDeleteBtn');
        
        if (modal && content && deleteBtn) {
            deleteBtn.href = url;
            modal.classList.remove('hidden');
            // Subtle transition show
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
            }, 10);
        }
    }

    function closeConfirmModal() {
        const modal = document.getElementById('customConfirmModal');
        const content = document.getElementById('customConfirmContent');
        
        if (modal && content) {
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            // Delay hide for animation exit
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    }

    // Toggle create post form inline
    function toggleCreateForm() {
        const form = document.getElementById('createPostForm');
        form.classList.toggle('hidden');
        if(!form.classList.contains('hidden')){
            form.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }



    // Media selector preview
    function previewSelectedMedia(input) {
        const container = document.getElementById('mediaPreviewContainer');
        const img = document.getElementById('imagePreview');
        const vid = document.getElementById('videoPreview');

        if (input.files && input.files[0]) {
            const file = input.files[0];
            const fileType = file.type;
            const reader = new FileReader();

            reader.onload = function(e) {
                container.classList.remove('hidden');
                if (fileType.startsWith('image/')) {
                    img.src = e.target.result;
                    img.classList.remove('hidden');
                    vid.classList.add('hidden');
                } else if (fileType.startsWith('video/')) {
                    vid.src = e.target.result;
                    vid.classList.remove('hidden');
                    img.classList.add('hidden');
                }
            };
            reader.readAsDataURL(file);
        }
    }

    // Clear selected media upload
    function clearMediaSelection() {
        const container = document.getElementById('mediaPreviewContainer');
        const img = document.getElementById('imagePreview');
        const vid = document.getElementById('videoPreview');
        const input = document.querySelector('input[name="media"]');

        input.value = '';
        container.classList.add('hidden');
        img.src = '';
        vid.src = '';
    }

    // Ajax Like Post
    function likePost(id, btn) {
        fetch(`<?= base_url('forum/like/') ?>${id}`, { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const icon = btn.querySelector('i');
                    const label = btn.querySelector('.like-count');
                    
                    label.textContent = data.likes_count;
                    if (data.action === 'liked') {
                        icon.className = 'bi bi-heart-fill text-red-500';
                        btn.classList.add('text-red-400', 'font-bold');
                        showToast('Menyukai kiriman!');
                    } else {
                        icon.className = 'bi bi-heart';
                        btn.classList.remove('text-red-400', 'font-bold');
                        showToast('Batal menyukai.');
                    }
                } else {
                    showToast(data.message);
                }
            })
            .catch(() => showToast('Gagal memproses.'));
    }

    // Ajax Save Post
    function savePost(id, btn) {
        fetch(`<?= base_url('forum/save/') ?>${id}`, { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const icon = btn.querySelector('i');
                    if (data.action === 'saved') {
                        icon.className = 'bi bi-bookmark-fill text-[#E49438]';
                        btn.classList.add('text-[#E49438]', 'font-bold');
                        showToast('Diskusi disimpan ke penanda!');
                    } else {
                        icon.className = 'bi bi-bookmark';
                        btn.classList.remove('text-[#E49438]', 'font-bold');
                        showToast('Dihapus dari penanda.');
                    }
                } else {
                    showToast(data.message);
                }
            })
            .catch(() => showToast('Gagal menyimpan.'));
    }

    // Share link copy
    function sharePost(url) {
        navigator.clipboard.writeText(url).then(() => {
            showToast('Tautan disalin ke papan klip!');
        }).catch(() => {
            showToast('Gagal menyalin tautan.');
        });
    }

    // Toggle Comments Section inline
    function toggleComments(forumId, btn) {
        const section = document.getElementById(`comments-section-${forumId}`);
        if (!section) return;

        section.classList.toggle('hidden');
        if (!section.classList.contains('hidden')) {
            loadInlineComments(forumId);
        }
    }

    // Load comments using Ajax
    function loadInlineComments(forumId) {
        const listDiv = document.getElementById(`comments-list-${forumId}`);
        if (!listDiv) return;

        listDiv.innerHTML = '<div class="text-xs text-white/40 py-2">Memuat komentar...</div>';

        fetch(`<?= base_url('forum/get_comments_ajax/') ?>${forumId}`)
            .then(res => res.json())
            .then(comments => {
                if (comments.length === 0) {
                    listDiv.innerHTML = '<div class="text-xs text-white/30 py-2">Belum ada komentar.</div>';
                    return;
                }

                let html = '';
                comments.forEach(c => {
                    const canDeleteComment = (CURRENT_USER_ID && parseInt(c.user_id) === CURRENT_USER_ID);
                    const deleteCommentBtn = canDeleteComment
                        ? `<button type="button" onclick="deleteCommentInline(${c.id}, ${forumId}, this)" class="text-[9px] text-red-400 hover:text-red-300 hover:underline ml-2 mt-1.5 font-bold transition-all"><i class="bi bi-trash3"></i> Hapus</button>`
                        : '';

                    html += `
                        <div class="bg-[#0d1314]/30 p-2.5 rounded-xl border border-[#374D49]/10 text-xs" data-comment-id="${c.id}">
                            <div class="flex items-center gap-2 mb-1">
                                <img src="${c.avatar_url}" class="w-5 h-5 rounded-full object-cover">
                                <span class="font-bold text-white">${c.author_name}</span>
                                <span class="text-[9px] text-[#B1CDCE]/50">${c.created_at}</span>
                            </div>
                            <p class="text-[#B1CDCE] ml-7 leading-relaxed">${c.comment}</p>
                            <div class="ml-7 flex items-center gap-2">
                                <button type="button" onclick="startReply(${forumId}, ${c.id}, '${c.author_name.replace(/'/g, "\\'")}')"
                                    class="text-[9px] text-[#E49438] hover:underline mt-1.5 font-bold transition-all">Balas</button>
                                ${deleteCommentBtn}
                            </div>
                        </div>
                    `;

                    // Render nested replies
                    if (c.replies && c.replies.length > 0) {
                        html += `<div class="border-l-2 border-[#374D49]/30 ml-5 pl-4 space-y-2 mt-1.5">`;
                        c.replies.forEach(r => {
                            const canDeleteReply = (CURRENT_USER_ID && parseInt(r.user_id) === CURRENT_USER_ID);
                            const deleteReplyBtn = canDeleteReply
                                ? `<button type="button" onclick="deleteCommentInline(${r.id}, ${forumId}, this)" class="text-[9px] text-red-400 hover:text-red-300 hover:underline ml-6 mt-1 font-bold transition-all block"><i class="bi bi-trash3"></i> Hapus</button>`
                                : '';

                            html += `
                                <div class="bg-[#0d1314]/15 p-2 rounded-xl border border-[#374D49]/5 text-xs shadow-sm" data-comment-id="${r.id}">
                                    <div class="flex items-center gap-2 mb-1">
                                        <img src="${r.avatar_url}" class="w-4 h-4 rounded-full object-cover">
                                        <span class="font-bold text-white">${r.author_name}</span>
                                        <span class="text-[9px] text-[#B1CDCE]/50">${r.created_at}</span>
                                    </div>
                                    <p class="text-[#B1CDCE] ml-6 leading-relaxed">${r.comment}</p>
                                    ${deleteReplyBtn}
                                </div>
                            `;
                        });
                        html += `</div>`;
                    }
                });
                listDiv.innerHTML = html;
                listDiv.scrollTop = listDiv.scrollHeight;
            })
            .catch(() => {
                listDiv.innerHTML = '<div class="text-xs text-red-400 py-2">Gagal memuat komentar.</div>';
            });
    }

    // Reply triggers
    function startReply(forumId, commentId, authorName) {
        const bar = document.getElementById(`replying-to-bar-${forumId}`);
        const nameField = document.getElementById(`replying-to-name-${forumId}`);
        const idField = document.getElementById(`parent-id-field-${forumId}`);
        const inputField = document.getElementById(`comment-input-field-${forumId}`);

        if (bar && nameField && idField && inputField) {
            idField.value = commentId;
            nameField.textContent = authorName;
            bar.classList.remove('hidden');
            inputField.placeholder = `Balas @${authorName}...`;
            inputField.focus();
        }
    }

    // Cancel reply triggers
    function cancelReply(forumId) {
        const bar = document.getElementById(`replying-to-bar-${forumId}`);
        const nameField = document.getElementById(`replying-to-name-${forumId}`);
        const idField = document.getElementById(`parent-id-field-${forumId}`);
        const inputField = document.getElementById(`comment-input-field-${forumId}`);

        if (bar && nameField && idField && inputField) {
            idField.value = '';
            nameField.textContent = '';
            bar.classList.add('hidden');
            inputField.placeholder = "Tulis balasan Anda...";
        }
    }

    // Submit inline comment using Ajax
    function submitInlineComment(e, forumId, form) {
        e.preventDefault();
        const input = form.querySelector('input[name="comment"]');
        const commentText = input.value.trim();
        if (!commentText) return;

        const formData = new FormData(form);
        input.value = ''; // Clear input field

        fetch(form.action, {
            method: 'POST',
            body: formData
        })
        .then(() => {
            loadInlineComments(forumId);
            cancelReply(forumId); // clear reply state after posting
            showToast('Komentar terkirim!');
            const countSpan = document.getElementById(`comment-count-${forumId}`);
            if (countSpan) {
                countSpan.textContent = parseInt(countSpan.textContent) + 1;
            }
        });
    }

    // Delete comment inline (AJAX)
    function deleteCommentInline(commentId, forumId, btn) {
        if (!confirm('Hapus komentar ini?')) return;

        const card = btn.closest('[data-comment-id]');
        if (card) {
            card.style.opacity = '0.5';
            card.style.pointerEvents = 'none';
        }

        fetch(`<?= base_url('forum/delete_comment/') ?>${commentId}`, { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    if (card) {
                        card.style.transition = 'all 0.3s ease';
                        card.style.opacity = '0';
                        card.style.maxHeight = '0';
                        card.style.overflow = 'hidden';
                        setTimeout(() => {
                            card.remove();
                            // Update comment count
                            const countSpan = document.getElementById(`comment-count-${forumId}`);
                            if (countSpan) {
                                const current = parseInt(countSpan.textContent);
                                if (current > 0) countSpan.textContent = current - 1;
                            }
                        }, 350);
                    }
                    showToast('Komentar berhasil dihapus.');
                } else {
                    if (card) {
                        card.style.opacity = '1';
                        card.style.pointerEvents = 'auto';
                    }
                    showToast(data.message || 'Gagal menghapus komentar.');
                }
            })
            .catch(() => {
                if (card) {
                    card.style.opacity = '1';
                    card.style.pointerEvents = 'auto';
                }
                showToast('Terjadi kesalahan, coba lagi.');
            });
    }

    // Load Chat Contacts
    function loadChatContacts() {
        fetch('<?= base_url('forum/chat_contacts') ?>')
            .then(res => res.json())
            .then(users => {
                allContacts = users;
                filterChatContacts(); // Render contacts with current search filter if any
            });
    }

    // Filter contacts based on search query
    function filterChatContacts() {
        const query = document.getElementById('chatSearchInput').value.toLowerCase().trim();
        const filtered = allContacts.filter(u => u.full_name.toLowerCase().includes(query));
        renderChatContacts(filtered);
    }

    // Render contacts to the DOM
    function renderChatContacts(users) {
        const list = document.getElementById('chatContactsList');
        if (users.length === 0) {
            list.innerHTML = '<div class="text-center text-white/40 text-xs py-10">Kontak tidak ditemukan.</div>';
            return;
        }

        let html = '';
        users.forEach(u => {
            let avatarHtml = '';
            if (u.avatar) {
                avatarHtml = `<div class="w-9 h-9 rounded-full overflow-hidden bg-teal-800 flex-shrink-0 border border-[#374D49]/30 shadow-sm">
                                  <img src="<?= base_url() ?>${u.avatar}" class="w-full h-full object-cover">
                              </div>`;
            } else {
                const initial = (u.full_name || 'U').charAt(0).toUpperCase();
                avatarHtml = `<div class="w-9 h-9 rounded-full bg-teal-700/60 border border-[#374D49]/40 flex items-center justify-center font-bold text-white text-xs select-none flex-shrink-0">
                                  ${initial}
                              </div>`;
            }
            const badge = u.unread_count > 0 ? `<span class="bg-emerald-500 text-white text-[10px] font-bold w-5 h-5 rounded-full flex items-center justify-center flex-shrink-0">${u.unread_count}</span>` : '';
            
            html += `
                <div onclick="openChatWidget(${u.id}, '${u.full_name.replace(/'/g, "\\'")}', '${u.avatar || ''}')" 
                     class="flex items-center justify-between gap-3 p-2 rounded-xl hover:bg-white/5 cursor-pointer transition-all border border-transparent hover:border-[#374D49]/30">
                    <div class="flex items-center gap-3 min-w-0">
                        ${avatarHtml}
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

    // Focus on first chat contact to show chat
    function focusChatContact() {
        const first = document.getElementById('chatContactsList').firstElementChild;
        if(first) {
            first.click();
        } else {
            showToast('Tidak ada kontak aktif.');
        }
    }

    // Open Chat panel
    function openChatWidget(userId, userName, avatar) {
        activeChatUserId = userId;
        document.getElementById('chatReceiverId').value = userId;
        document.getElementById('chatActiveName').textContent = userName;
        
        const img = document.getElementById('chatActiveAvatar');
        const initial = document.getElementById('chatActiveInitial');
        
        if (avatar) {
            img.src = "<?= base_url() ?>" + avatar;
            img.classList.remove('hidden');
            initial.classList.add('hidden');
        } else {
            img.classList.add('hidden');
            initial.textContent = (userName || 'U').charAt(0).toUpperCase();
            initial.classList.remove('hidden');
        }

        const widget = document.getElementById('floatingChatWidget');
        widget.classList.remove('hidden');
        
        loadChatMessages();

        // Start realtime interval
        clearInterval(chatRefreshInterval);
        chatRefreshInterval = setInterval(loadChatMessages, 2500); // 2.5 seconds real-time polling
    }

    // Minimize widget
    function minimizeChatWidget() {
        const box = document.getElementById('chatMessagesBox');
        const form = document.getElementById('chatInputForm').parentElement;
        box.classList.toggle('hidden');
        form.classList.toggle('hidden');
    }

    // Close chat
    function closeChatWidget() {
        document.getElementById('floatingChatWidget').classList.add('hidden');
        activeChatUserId = null;
        clearInterval(chatRefreshInterval);
        loadChatContacts(); // Refresh list to clear badge
    }

    // Load messages list
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
                        // Logged user messages
                        html += `
                            <div class="flex flex-col items-end gap-1 max-w-[80%] self-end">
                                <div class="bg-[#377C80] text-white text-xs py-2 px-3 rounded-2-xl rounded-tr-none shadow-sm">
                                    ${escapeHtml(m.message)}
                                </div>
                                <span class="text-[8px] text-white/40 pr-1">${m.formatted_time}</span>
                            </div>
                        `;
                    } else {
                        // Other user messages
                        html += `
                            <div class="flex gap-2 max-w-[80%]">
                                ${m.sender_avatar ? `
                                    <div class="w-6 h-6 rounded-full overflow-hidden bg-teal-800 flex-shrink-0 mt-1 border border-[#374D49]/20 shadow-sm">
                                        <img src="<?= base_url() ?>${m.sender_avatar}" class="w-full h-full object-cover">
                                    </div>
                                ` : `
                                    <div class="w-6 h-6 rounded-full bg-teal-700/60 border border-[#374D49]/30 flex items-center justify-center font-bold text-white text-[9px] select-none flex-shrink-0 mt-1">
                                        ${(m.sender_name || 'U').charAt(0).toUpperCase()}
                                    </div>
                                `}
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

                // Detect if content changed to scroll down
                const shouldScroll = box.scrollTop + box.clientHeight >= box.scrollHeight - 50 || box.innerHTML.length < 50;
                box.innerHTML = html;
                if (shouldScroll) {
                    box.scrollTop = box.scrollHeight;
                }
            });
    }

    // Send Message Realtime Form Submission
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

        fetch('<?= base_url('forum/send_chat_message') ?>', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                loadChatMessages();
                loadChatContacts();
            } else {
                showToast(data.message);
            }
        });
    }

    // Helper HTML escaper
    function escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Initial loading
    document.addEventListener("DOMContentLoaded", function() {
        loadChatContacts();
        setInterval(loadChatContacts, 6000); // refresh contact list sidebar badges every 6 seconds

        // Drag and Drop media files
        const dropZone = document.getElementById('dragDropZone');
        const fileInput = document.getElementById('mediaFileInput');

        if (dropZone && fileInput) {
            // Highlight drop zone on drag
            ['dragenter', 'dragover'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropZone.classList.add('border-[#E49438]', 'bg-[#15201E]/40');
                }, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                dropZone.addEventListener(eventName, (e) => {
                    e.preventDefault();
                    dropZone.classList.remove('border-[#E49438]', 'bg-[#15201E]/40');
                }, false);
            });

            // Handle dropped files
            dropZone.addEventListener('drop', (e) => {
                const dt = e.dataTransfer;
                const files = dt.files;
                if (files.length) {
                    fileInput.files = files;
                    previewSelectedMedia(fileInput);
                }
            }, false);
        }
    });
</script>

<?php
// Helper to print elapsed times (e.g. 13 jam lalu)
function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'tahun',
        'm' => 'bulan',
        'w' => 'minggu',
        'd' => 'hari',
        'h' => 'jam',
        'i' => 'menit',
        's' => 'detik',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' yang lalu' : 'baru saja';
}
?>