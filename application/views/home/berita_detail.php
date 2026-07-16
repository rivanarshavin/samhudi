<style>
/* ======= THEME ADAPTING VARIABLES ======= */
:root {
    --bd-bg: #ffffff;
    --bd-text: #333333;
    --bd-title: #1a2e2b;
    --bd-meta: #666666;
    --bd-border: #eeeeee;
    --bd-divider: #e0e0e0;
    --bd-sidebar-bg: #eef0ef;
    --bd-sidebar-border: rgba(0,0,0,0.08);
    --bd-sidebar-hover: rgba(0,0,0,0.03);
    --bd-btn-bg: linear-gradient(135deg, #1B3835, #2E564F);
    --bd-btn-text: #ffffff;
}

body[data-theme="dark"] {
    --bd-bg: #0F211F;
    --bd-text: #e8ddd4;
    --bd-title: #C8A84E;
    --bd-meta: #b1cdce;
    --bd-border: #22443F;
    --bd-divider: #22443F;
    --bd-sidebar-bg: #1B3835;
    --bd-sidebar-border: rgba(255,255,255,0.08);
    --bd-sidebar-hover: rgba(255,255,255,0.03);
    --bd-btn-bg: linear-gradient(135deg, #C8A84E, #b8963c);
    --bd-btn-text: #0F211F;
}

/* ======= Detail Berita ======= */
.detail-berita-wrap {
    background: var(--bd-bg);
    color: var(--bd-text);
    min-height: 100vh;
    padding: 0 0 80px;
    transition: background-color 0.3s ease, color 0.3s ease;
}

/* Breadcrumb Bar */
.detail-breadcrumb-bar {
    background: var(--bd-bg);
    border-bottom: 1px solid var(--bd-border);
    padding: 14px 0;
    transition: background-color 0.3s ease, border-color 0.3s ease;
}
.detail-breadcrumb-bar .breadcrumb-inner {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.8rem;
    color: var(--bd-meta);
    font-family: 'Inter', sans-serif;
}
.detail-breadcrumb-bar a {
    color: var(--bd-meta);
    text-decoration: none;
    transition: color 0.2s;
}
.detail-breadcrumb-bar a:hover { color: #C8A84E; }
.detail-breadcrumb-bar .current { color: var(--bd-title); font-weight: 600; }

/* Hero Section */
.detail-hero {
    padding: 40px 0 20px;
}

/* Judul & Meta */
.detail-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 2.1rem;
    font-weight: 800;
    color: var(--bd-title);
    line-height: 1.3;
    margin-bottom: 18px;
    transition: color 0.3s ease;
}
.detail-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    align-items: center;
}
.detail-meta-item {
    display: flex;
    align-items: center;
    gap: 7px;
    font-size: 0.85rem;
    color: var(--bd-meta);
    font-family: 'Inter', sans-serif;
}
.detail-meta-item i { font-size: 0.9rem; }
.detail-meta-divider {
    width: 1px;
    height: 16px;
    background: var(--bd-divider);
    transition: background-color 0.3s ease;
}
.btn-like {
    cursor: pointer;
    transition: transform 0.2s;
}
.btn-like:hover {
    transform: scale(1.05);
}

/* Thumbnail kanan */
.detail-thumbnail-wrap {
    height: 100%;
    min-height: 260px;
    max-height: 400px;
    display: flex;
    justify-content: flex-end;
}
.detail-thumbnail-wrap img {
    max-width: 100%;
    height: auto;
    max-height: 400px;
    object-fit: contain;
    display: block;
}
.detail-thumbnail-wrap .no-img {
    width: 100%;
    height: 100%;
    min-height: 260px;
    background: var(--bd-sidebar-bg);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 10px;
    color: var(--bd-meta);
    font-size: 3rem;
}

/* Body Content Area */
.detail-body {
    padding: 30px 0 50px;
}

/* Konten berita (Tidak pakai card lagi) */
.detail-content-card {
    padding: 10px 0;
}
.detail-content-card p,
.detail-content-card .content-text {
    font-family: 'Inter', sans-serif;
    font-size: 1.05rem;
    line-height: 1.85;
    color: var(--bd-text);
    white-space: pre-wrap;
    word-break: break-word;
    transition: color 0.3s ease;
}
.detail-content-card h4 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--bd-title);
    margin-top: 30px;
    margin-bottom: 15px;
    padding-bottom: 8px;
    border-bottom: 2px solid var(--bd-border);
    transition: color 0.3s ease, border-color 0.3s ease;
}
.detail-content-card h4 i {
    color: #C8A84E;
    font-size: 1rem;
}

/* Sidebar berita lain */
.sidebar-other-news {
    position: sticky;
    top: 90px;
}
.sidebar-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.9rem;
    font-weight: 800;
    color: var(--bd-title);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: color 0.3s ease;
}
.sidebar-title::after {
    content: '';
    flex: 1;
    height: 2px;
    background: linear-gradient(90deg, #C8A84E, transparent);
    border-radius: 2px;
}

/* ======= Sidebar Berita Lainnya (SATU card abu, item TANPA card) ======= */
.sidebar-berita-box {
    background: var(--bd-sidebar-bg);
    border-radius: 18px;
    padding: 18px;
    transition: background-color 0.3s ease;
}
.sidebar-berita-item {
    display: flex;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid var(--bd-sidebar-border);
    text-decoration: none;
    color: inherit;
    transition: background-color 0.2s ease, border-color 0.3s ease;
}
.sidebar-berita-item:first-child {
    padding-top: 2px;
}
.sidebar-berita-item:last-child {
    border-bottom: none;
    padding-bottom: 2px;
}
.sidebar-berita-item:hover {
    background: var(--bd-sidebar-hover);
}
.sidebar-berita-item .thumb {
    width: 72px;
    height: 60px;
    border-radius: 10px;
    overflow: hidden;
    flex-shrink: 0;
}
.sidebar-berita-item .thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.sidebar-berita-item .thumb .no-img {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #1B3835, #2E564F);
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.6);
    font-size: 1.3rem;
}
.sidebar-berita-item .info { flex: 1; min-width: 0; }
.sidebar-berita-item .info .on-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 700;
    color: var(--bd-title);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 5px;
    transition: color 0.3s ease;
}
.sidebar-berita-item .info .on-date {
    font-size: 0.73rem;
    color: var(--bd-meta);
    display: flex;
    align-items: center;
    gap: 5px;
}

.lihat-semua-btn {
    display: block;
    text-align: center;
    background: var(--bd-btn-bg);
    color: var(--bd-btn-text);
    border-radius: 12px;
    padding: 12px 0;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem;
    font-weight: 700;
    text-decoration: none;
    letter-spacing: 0.04em;
    margin-top: 14px;
    transition: opacity 0.2s ease, background 0.3s ease, color 0.3s ease;
}
.lihat-semua-btn:hover { opacity: 0.88; color: #fff; text-decoration: none; }
</style>

<?php
// Helper format tanggal
if (!function_exists('fmt_date_detail')) {
    function fmt_date_detail($datetime) {
        $months = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $ts = strtotime($datetime);
        return date('j', $ts) . ' ' . $months[(int)date('n', $ts)] . ' ' . date('Y', $ts);
    }
}
?>

<div class="detail-berita-wrap">

    <!-- Breadcrumb -->
    <div class="detail-breadcrumb-bar">
        <div class="container">
            <div class="breadcrumb-inner">
                <a href="<?= base_url() ?>"><i class="bi bi-house-fill"></i> Home</a>
                <i class="bi bi-chevron-right" style="font-size:0.7rem;"></i>
                <a href="<?= base_url('berita') ?>">Berita</a>
                <i class="bi bi-chevron-right" style="font-size:0.7rem;"></i>
                <span class="current"><?= htmlspecialchars(mb_strimwidth($news['title'], 0, 55, '...')) ?></span>
            </div>
        </div>
    </div>

    <!-- Hero: Judul (kiri atas) + Thumbnail (kanan) -->
    <section class="detail-hero">
        <div class="container">
            <div class="row align-items-center g-4">
                <!-- Kiri: Judul + Meta -->
                <div class="col-lg-7">
                    <h1 class="detail-title"><?= htmlspecialchars($news['title']) ?></h1>
                    <div class="detail-meta">
                        <div class="detail-meta-item">
                            <i class="bi bi-calendar3"></i>
                            <span><?= fmt_date_detail($news['created_at']) ?></span>
                        </div>
                        <div class="detail-meta-divider"></div>
                        <div class="detail-meta-item">
                            <i class="bi bi-person-fill"></i>
                            <span>Oleh <?= htmlspecialchars($news['author_name'] ?? 'Admin') ?></span>
                        </div>
                        <div class="detail-meta-divider"></div>
                        <div class="detail-meta-item" title="Dilihat">
                            <i class="bi bi-eye-fill"></i>
                            <span><?= number_format($news['views'] ?? 0) ?> Kali Dilihat</span>
                        </div>
                        <div class="detail-meta-divider"></div>
                        <div class="detail-meta-item btn-like" onclick="likeBerita(<?= $news['id'] ?>)" title="Sukai / Batal Sukai">
                            <i id="like-icon" class="bi bi-heart-fill" style="color: #ccc;"></i>
                            <span id="like-count"><?= number_format($news['likes'] ?? 0) ?> Suka</span>
                        </div>
                    </div>
                </div>
                <!-- Kanan: Foto Thumbnail -->
                <div class="col-lg-5">
                    <div class="detail-thumbnail-wrap">
                        <?php if (!empty($news['thumbnail']) && file_exists('./' . $news['thumbnail'])): ?>
                            <img src="<?= base_url($news['thumbnail']) ?>" alt="<?= htmlspecialchars($news['title']) ?>">
                        <?php else: ?>
                            <div class="no-img">
                                <i class="bi bi-image"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Body: Isi Berita (kiri) + Berita Lain (kanan) -->
    <section class="detail-body">
        <div class="container">
            <div class="row g-4">

                <!-- Kiri: Isi Berita -->
                <div class="col-lg-8">
                    <div class="detail-content-card">
                        <h4>
                        </h4>
                        <?php if (!empty($news['content'])): ?>
                            <div class="content-text"><?= htmlspecialchars($news['content']) ?></div>
                        <?php else: ?>
                            <p style="color:#bbb; font-style: italic;">Konten berita belum tersedia.</p>
                        <?php endif; ?>
                        
                        <div class="mt-5 pt-4 border-top">
                            <button onclick="shareBerita()" class="btn btn-outline-success" style="border-radius: 12px; font-weight: 600; font-family: 'Plus Jakarta Sans', sans-serif;">
                                <i class="bi bi-share-fill me-2"></i> Bagikan Berita
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Kanan: Sidebar Berita Lainnya -->
                <div class="col-lg-4">
                    <div class="sidebar-other-news">
                        <div class="sidebar-title">
                            <i class="bi bi-newspaper" style="color: #2E564F;"></i>
                            Berita Lainnya
                        </div>

                        <?php if (!empty($other_news)): ?>
                            <div class="sidebar-berita-box">
                                <?php foreach ($other_news as $on): ?>
                                <a href="<?= base_url('berita/' . $on['slug']) ?>" class="sidebar-berita-item">
                                    <div class="thumb">
                                        <?php if (!empty($on['thumbnail']) && file_exists('./' . $on['thumbnail'])): ?>
                                            <img src="<?= base_url($on['thumbnail']) ?>" alt="<?= htmlspecialchars($on['title']) ?>">
                                        <?php else: ?>
                                            <div class="no-img"><i class="bi bi-image"></i></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="info">
                                        <div class="on-title"><?= htmlspecialchars($on['title']) ?></div>
                                        <div class="on-date">
                                            <i class="bi bi-calendar3"></i>
                                            <?php
                                                $months_s = [1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
                                                $ts = strtotime($on['created_at']);
                                                echo date('j', $ts) . ' ' . $months_s[(int)date('n', $ts)] . ' ' . date('Y', $ts);
                                            ?>
                                        </div>
                                    </div>
                                </a>
                                <?php endforeach; ?>

                                <a href="<?= base_url('berita') ?>" class="lihat-semua-btn">
                                    <i class="bi bi-grid-3x3-gap-fill"></i> Lihat Semua Berita
                                </a>
                            </div>
                        <?php else: ?>
                            <div class="sidebar-berita-box" style="text-align:center; padding: 30px 0; font-size: 0.85rem; color: var(--bd-meta);">
                                <i class="bi bi-newspaper" style="font-size: 2rem; display:block; margin-bottom:10px; opacity:0.5;"></i>
                                Belum ada berita lain.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>

<script>
// Initialize like state from server (DB-driven, not localStorage)
var isLiked = <?= $is_liked ? 'true' : 'false' ?>;

document.addEventListener('DOMContentLoaded', function() {
    updateLikeIcon(isLiked);
});

function updateLikeIcon(liked) {
    var icon = document.getElementById('like-icon');
    if (icon) {
        icon.style.color = liked ? '#dc3545' : '#aaa';
        icon.className = liked ? 'bi bi-heart-fill' : 'bi bi-heart';
    }
}

function likeBerita(id) {
    <?php if (!$this->session->userdata('user_id')): ?>
        alert('Anda harus login untuk menyukai berita.');
        return;
    <?php endif; ?>

    fetch('<?= base_url('home/like_berita/') ?>' + id, {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            isLiked = (data.action === 'like');
            updateLikeIcon(isLiked);
            document.getElementById('like-count').innerText = data.likes + ' Suka';
        } else if (data.status === 'error') {
            alert(data.message || 'Terjadi kesalahan.');
        }
    })
    .catch(err => console.error('Like error:', err));
}


function shareBerita() {
    if (navigator.share) {
        navigator.share({
            title: '<?= addslashes($news['title']) ?>',
            text: 'Baca berita menarik ini di Website Samhudi',
            url: window.location.href
        }).catch(console.error);
    } else {
        var dummy = document.createElement('input'),
            text = window.location.href;
        document.body.appendChild(dummy);
        dummy.value = text;
        dummy.select();
        document.execCommand('copy');
        document.body.removeChild(dummy);
        alert('Link berita berhasil disalin ke clipboard!');
    }
}
</script>