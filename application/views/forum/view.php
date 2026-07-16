<?php
/**
 * @var object $forum
 * @var array $comments
 * @var object $user
 */
?>

<style>
    /* Color Palette */
    :root {
        --color-dark-teal: #274D4F;
        --color-light-teal: #274D4F;
        --color-orange-accent: #E49438;
        --color-bg-main: #F8F9FA;
        --color-text-main: #15201E;
        --color-text-muted: #4b5e5b;
        --color-card-bg: #ffffff;
        --color-input-bg: #F8F9FA;
        --color-bubble-bg: #eef0ef;
        --color-border-dark: #8fa5a2;
    }
    
    body[data-theme="dark"] {
        --color-dark-teal: #274D4F;
        --color-light-teal: #C8A84E;
        --color-orange-accent: #C8A84E;
        --color-bg-main: #0F211F;
        --color-text-main: #FFFFFF;
        --color-text-muted: #B1CDCE;
        --color-card-bg: #1B3835;
        --color-input-bg: #0d1314;
        --color-bubble-bg: rgba(255, 255, 255, 0.05);
        --color-border-dark: #22443F;
    }
    
    body {
        background-color: var(--color-bg-main) !important;
        color: var(--color-text-main) !important;
        transition: background-color 0.3s ease, color 0.3s ease;
    }

    .forum-container {
        background-color: var(--color-bg-main) !important;
        min-height: 100vh;
        transition: background-color 0.3s ease;
    }

    /* Custom scrollbar for comments list */
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

    /* Override hardcoded classes for detail page */
    /* Prepended with .forum-container to increase CSS specificity over Tailwind CDN runtime styles */
    .forum-container .bg-\[\#274D4F\]\/50 {
        background-color: var(--color-card-bg) !important;
        transition: background-color 0.3s ease;
    }
    .forum-container .bg-\[\#1F3637\]\/40 {
        background-color: var(--color-bubble-bg) !important;
        transition: background-color 0.3s ease;
    }
    .forum-container .bg-\[\#1F3637\]\/20 {
        background-color: var(--color-bubble-bg) !important;
        opacity: 0.8;
        transition: background-color 0.3s ease;
    }
    .forum-container .bg-\[\#1b3435\] {
        background-color: var(--color-card-bg) !important;
        transition: background-color 0.3s ease;
    }
    .forum-container .bg-\[\#1F3637\] {
        background-color: var(--color-input-bg) !important;
        transition: background-color 0.3s ease;
    }
    .forum-container .bg-\[\#377C80\] {
        background-color: var(--color-light-teal) !important;
        transition: background-color 0.3s ease;
    }
    .forum-container .text-white {
        color: var(--color-text-main) !important;
        transition: color 0.3s ease;
    }
    .forum-container .text-white\/70 {
        color: var(--color-text-main) !important;
        opacity: 0.7;
    }
    .forum-container .text-white\/80 {
        color: var(--color-text-main) !important;
        opacity: 0.8;
    }
    .forum-container .text-teal-200 {
        color: var(--color-text-muted) !important;
        transition: color 0.3s ease;
    }
    .forum-container .text-teal-300 {
        color: var(--color-light-teal) !important;
        transition: color 0.3s ease;
    }
    .forum-container .border-teal-800\/30, 
    .forum-container .border-teal-800\/20, 
    .forum-container .border-teal-800\/40 {
        border-color: var(--color-border-dark) !important;
        transition: border-color 0.3s ease;
    }
    .forum-container .focus\:ring-\[\#377C80\]:focus {
        --tw-ring-color: var(--color-light-teal) !important;
    }
    .forum-container input::placeholder, 
    .forum-container textarea::placeholder {
        color: var(--color-text-muted) !important;
        opacity: 0.55 !important;
    }

    /* Specific Light Mode text overrides for opacity classes that default to white */
    body[data-theme="light"] .forum-container .text-white {
        color: var(--color-text-main) !important;
    }
    body[data-theme="light"] .forum-container .text-white\/20 {
        color: var(--color-border-dark) !important;
        opacity: 0.5 !important;
    }
    body[data-theme="light"] .forum-container .text-white\/30 {
        color: var(--color-text-muted) !important;
        opacity: 0.5 !important;
    }
    body[data-theme="light"] .forum-container .text-white\/40 {
        color: var(--color-text-muted) !important;
        opacity: 0.65 !important;
    }
    body[data-theme="light"] .forum-container .text-white\/50 {
        color: var(--color-text-muted) !important;
        opacity: 0.8 !important;
    }
    body[data-theme="light"] .forum-container .text-white\/60 {
        color: var(--color-text-muted) !important;
        opacity: 0.9 !important;
    }
    body[data-theme="light"] .forum-container .text-white\/70 {
        color: var(--color-text-main) !important;
        opacity: 0.75 !important;
    }
    body[data-theme="light"] .forum-container .text-white\/80 {
        color: var(--color-text-main) !important;
        opacity: 0.9 !important;
    }

    /* Define cards highlight in Light Mode */
    body[data-theme="light"] .forum-container .bg-\[\#274D4F\]\/50 {
        box-shadow: 0 6px 24px rgba(39, 77, 79, 0.08) !important;
        border: 1px solid var(--color-border-dark) !important;
    }

    /* Keep button text and icons dark on Gold accent buttons in Dark Mode */
    body[data-theme="dark"] .forum-container .bg-\[\#377C80\] {
        color: #0F211F !important;
    }
    body[data-theme="dark"] .forum-container .bg-\[\#377C80\] i {
        color: #0F211F !important;
    }
    body[data-theme="dark"] .forum-container .bg-\[\#377C80\]:hover {
        opacity: 0.9;
    }
</style>

<div class="forum-container font-display pb-12">

    <main class="max-w-4xl mx-auto px-4 sm:px-6 py-8">
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

        <div class="flex flex-col gap-6">
            <!-- Main Thread Card -->
            <div class="bg-[#274D4F]/50 rounded-3xl overflow-hidden border border-teal-800/30 p-6 sm:p-8 flex flex-col gap-6">
                <!-- Author & Time -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <?php if (!empty($forum->author_avatar)): ?>
                            <div class="w-12 h-12 rounded-full overflow-hidden bg-teal-800 border-2 border-[#377C80] flex-shrink-0">
                                <img src="<?= base_url($forum->author_avatar) ?>" alt="Avatar" class="w-full h-full object-cover">
                            </div>
                        <?php else: ?>
                            <div class="w-12 h-12 rounded-full bg-teal-700/60 border border-[#377C80] flex items-center justify-center font-bold text-white text-base select-none flex-shrink-0">
                                <?= strtoupper(substr($forum->author_name ?? 'U', 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h4 class="text-sm font-bold text-white"><?= htmlspecialchars($forum->author_name ?? 'Anggota Keluarga') ?></h4>
                            <p class="text-xs text-white/50 mt-0.5">
                                <?= date('d M Y, H.i', strtotime($forum->created_at)) ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Thread Title & Content -->
                <div>
                    <h1 class="text-xl sm:text-2xl font-bold text-white leading-tight">
                        <?= htmlspecialchars($forum->title) ?>
                    </h1>
                    <p class="text-sm text-white/80 leading-relaxed mt-4 whitespace-pre-line">
                        <?= htmlspecialchars($forum->content) ?>
                    </p>
                </div>

                <!-- Attached Media -->
                <?php if (!empty($forum->media_url)): ?>
                    <div class="rounded-2xl overflow-hidden max-h-[500px] w-full border border-teal-800/30">
                        <?php if ($forum->media_type === 'image'): ?>
                            <img src="<?= base_url($forum->media_url) ?>" alt="Post media" class="w-full h-full object-cover max-h-[500px]">
                        <?php elseif ($forum->media_type === 'video'): ?>
                            <video src="<?= base_url($forum->media_url) ?>" controls class="w-full h-full object-cover max-h-[500px]"></video>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <!-- Action row -->
                <div class="flex items-center justify-between border-t border-b border-teal-800/30 py-4 text-xs font-semibold text-white/70">
                    <!-- Like -->
                    <button onclick="likePostDetail(<?= $forum->id ?>, this)" 
                            class="flex items-center gap-1.5 py-1.5 px-4 rounded-full hover:bg-white/5 transition-all text-white/70 <?= $forum->liked_by_user ? 'text-red-400 font-bold' : '' ?>">
                        <i class="<?= $forum->liked_by_user ? 'bi bi-heart-fill text-red-500' : 'bi bi-heart' ?>"></i> 
                        <span class="like-count"><?= $forum->likes_count ?> Likes</span>
                    </button>

                    <!-- Saves -->
                    <button onclick="savePostDetail(<?= $forum->id ?>, this)" 
                            class="flex items-center gap-1.5 py-1.5 px-4 rounded-full hover:bg-white/5 transition-all text-white/70 <?= $forum->saved_by_user ? 'text-[#E49438] font-bold' : '' ?>">
                        <i class="<?= $forum->saved_by_user ? 'bi bi-bookmark-fill text-[#E49438]' : 'bi bi-bookmark' ?>"></i> 
                        <span><?= $forum->saved_by_user ? 'Tersimpan' : 'Simpan' ?></span>
                    </button>

                    <!-- Share -->
                    <button onclick="sharePostDetail('<?= base_url('forum/view/' . $forum->id) ?>')" 
                            class="flex items-center gap-1.5 py-1.5 px-4 rounded-full hover:bg-white/5 transition-all text-white/70">
                        <i class="bi bi-share"></i> Bagikan
                    </button>
                </div>

                <!-- Comments List Title -->
                <div>
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i class="bi bi-chat-left-text text-[#E49438]"></i> Komentar (<?= count($comments) ?>)
                    </h3>
                </div>

                <!-- Comments List Container -->
                <div class="space-y-6 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                    <?php if (empty($comments)): ?>
                        <p class="text-white/40 text-sm text-center py-6">Belum ada komentar. Tulis komentar pertama!</p>
                    <?php else: ?>
                        <?php foreach ($comments as $comment): ?>
                            <!-- Comment Item -->
                            <div class="flex items-start gap-3 bg-[#1F3637]/40 p-4 rounded-2xl border border-teal-800/20">
                                <?php if (!empty($comment->author_avatar)): ?>
                                    <div class="w-9 h-9 rounded-full overflow-hidden bg-teal-800 flex-shrink-0">
                                        <img src="<?= base_url($comment->author_avatar) ?>" alt="Avatar" class="w-full h-full object-cover">
                                    </div>
                                <?php else: ?>
                                    <div class="w-9 h-9 rounded-full bg-teal-700/60 border border-[#374D49]/40 flex items-center justify-center font-bold text-white text-xs select-none flex-shrink-0">
                                        <?= strtoupper(substr($comment->author_name ?? 'U', 0, 1)) ?>
                                    </div>
                                <?php endif; ?>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between gap-2">
                                        <h5 class="text-xs font-bold text-white"><?= htmlspecialchars($comment->author_name ?? 'Anggota Keluarga') ?></h5>
                                        <span class="text-[10px] text-white/40"><?= time_elapsed_string_view($comment->created_at) ?></span>
                                    </div>
                                    <p class="text-xs text-white/80 mt-1 leading-relaxed whitespace-pre-line">
                                        <?= htmlspecialchars($comment->comment) ?>
                                    </p>
                                    
                                    <!-- Comment Action (Reply Trigger, fully inline) -->
                                    <div class="flex items-center gap-4 mt-2">
                                        <button onclick="toggleReplyForm(<?= $comment->id ?>)" class="text-[10px] font-bold text-teal-300 hover:text-white transition-all flex items-center gap-1">
                                            <i class="bi bi-reply"></i> Balas
                                        </button>
                                        <?php if ($user && $comment->user_id == $user->id): ?>
                                        <button onclick="deleteComment(<?= $comment->id ?>, this)" class="text-[10px] font-bold text-red-400 hover:text-red-300 transition-all flex items-center gap-1">
                                            <i class="bi bi-trash3"></i> Hapus
                                        </button>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Inline Reply Form -->
                                    <div id="replyFormContainer_<?= $comment->id ?>" class="hidden mt-3">
                                        <?= form_open('forum/comment/' . $forum->id) ?>
                                            <input type="hidden" name="parent_id" value="<?= $comment->id ?>">
                                            <div class="flex items-center gap-2">
                                                <input type="text" name="comment" placeholder="Ketik balasan..." required autocomplete="off"
                                                       class="flex-1 bg-[#1F3637] text-white placeholder-teal-300/30 text-xs rounded-full py-2 px-4 focus:outline-none focus:ring-1 focus:ring-[#377C80] border border-teal-800/40">
                                                <button type="submit" class="w-8 h-8 rounded-full bg-[#377C80] hover:bg-teal-700 text-white flex items-center justify-center flex-shrink-0 transition-all">
                                                    <i class="bi bi-send-fill text-xs"></i>
                                                </button>
                                            </div>
                                        <?= form_close() ?>
                                    </div>

                                    <!-- Nesting Replies List -->
                                    <?php if (!empty($comment->replies)): ?>
                                        <div class="mt-4 pl-4 border-l-2 border-teal-800/50 space-y-4">
                                            <?php foreach ($comment->replies as $reply): ?>
                                                <div class="flex items-start gap-3 bg-[#1F3637]/20 p-3 rounded-xl">
                                                    <?php if (!empty($reply->author_avatar)): ?>
                                                        <div class="w-7 h-7 rounded-full overflow-hidden bg-teal-800 flex-shrink-0">
                                                            <img src="<?= base_url($reply->author_avatar) ?>" alt="Avatar" class="w-full h-full object-cover">
                                                        </div>
                                                    <?php else: ?>
                                                        <div class="w-7 h-7 rounded-full bg-teal-700/60 border border-[#374D49]/40 flex items-center justify-center font-bold text-white text-[10px] select-none flex-shrink-0">
                                                            <?= strtoupper(substr($reply->author_name ?? 'U', 0, 1)) ?>
                                                        </div>
                                                    <?php endif; ?>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center justify-between gap-2">
                                                            <h6 class="text-[11px] font-bold text-white"><?= htmlspecialchars($reply->author_name ?? 'Anggota Keluarga') ?></h6>
                                                            <span class="text-[9px] text-white/40"><?= time_elapsed_string_view($reply->created_at) ?></span>
                                                        </div>
                                                        <p class="text-xs text-white/70 mt-1 leading-relaxed whitespace-pre-line">
                                                            <?= htmlspecialchars($reply->comment) ?>
                                                        </p>
                                                        <?php if ($user && $reply->user_id == $user->id): ?>
                                                        <div class="mt-1">
                                                            <button onclick="deleteComment(<?= $reply->id ?>, this)" class="text-[10px] font-bold text-red-400 hover:text-red-300 transition-all flex items-center gap-1">
                                                                <i class="bi bi-trash3"></i> Hapus
                                                            </button>
                                                        </div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- Main Add Comment Form (Bottom of View) -->
                <div class="border-t border-teal-800/30 pt-6">
                    <?= form_open('forum/comment/' . $forum->id) ?>
                        <div class="flex items-center gap-3 bg-[#1b3435] rounded-full px-5 py-3 border border-teal-800/30">
                            <input type="text" name="comment" placeholder="Ketik komentar..." required autocomplete="off"
                                   class="flex-1 bg-transparent text-white placeholder-teal-300/40 text-xs focus:outline-none">
                            <button type="submit" class="w-9 h-9 rounded-full bg-[#377C80] hover:bg-teal-700 text-white flex items-center justify-center flex-shrink-0 shadow-md transition-all">
                                <i class="bi bi-send-fill text-sm"></i>
                            </button>
                        </div>
                    <?= form_close() ?>
                </div>
            </div>
        </div>
    </main>
</div>

<!-- SCRIPTS FOR FORUM VIEW -->
<script>
    // Toast alert helper
    function showToast(message) {
        const toastEl = document.getElementById('actionToast');
        const toastMsg = document.getElementById('toastMessage');
        toastMsg.innerText = message;
        const toast = new bootstrap.Toast(toastEl, { delay: 3500 });
        toast.show();
    }

    // Toggle sub-reply inputs
    function toggleReplyForm(commentId) {
        const form = document.getElementById('replyFormContainer_' + commentId);
        form.classList.toggle('hidden');
        if (!form.classList.contains('hidden')) {
            form.querySelector('input[name="comment"]').focus();
        }
    }

    // Ajax Like Post
    function likePostDetail(id, btn) {
        fetch(`<?= base_url('forum/like/') ?>${id}`, { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const icon = btn.querySelector('i');
                    const label = btn.querySelector('.like-count');
                    
                    label.textContent = `${data.likes_count} Likes`;
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
    function savePostDetail(id, btn) {
        fetch(`<?= base_url('forum/save/') ?>${id}`, { method: 'POST' })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    const icon = btn.querySelector('i');
                    const label = btn.querySelector('span');
                    if (data.action === 'saved') {
                        icon.className = 'bi bi-bookmark-fill text-[#E49438]';
                        btn.classList.add('text-[#E49438]', 'font-bold');
                        label.textContent = 'Tersimpan';
                        showToast('Diskusi disimpan ke penanda!');
                    } else {
                        icon.className = 'bi bi-bookmark';
                        btn.classList.remove('text-[#E49438]', 'font-bold');
                        label.textContent = 'Simpan';
                        showToast('Dihapus dari penanda.');
                    }
                } else {
                    showToast(data.message);
                }
            })
            .catch(() => showToast('Gagal menyimpan.'));
    }

    // Share link copy
    function sharePostDetail(url) {
        navigator.clipboard.writeText(url).then(() => {
            showToast('Tautan disalin ke papan klip!');
        }).catch(() => {
            showToast('Gagal menyalin tautan.');
        });
    }

    // Delete comment (AJAX)
    function deleteComment(commentId, btn) {
        if (!confirm('Hapus komentar ini?')) return;

        // Cari parent comment card
        const card = btn.closest('.flex.items-start');
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
                        setTimeout(() => card.remove(), 350);
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
</script>

<?php
// Helper to print elapsed times (e.g. 13 jam lalu)
function time_elapsed_string_view($datetime, $full = false) {
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