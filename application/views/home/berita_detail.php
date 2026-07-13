<style>
/* ======= Detail Berita ======= */
.detail-berita-wrap {
    background: #f5f6f8;
    min-height: 100vh;
    padding: 0 0 80px;
}

/* Breadcrumb Bar */
.detail-breadcrumb-bar {
    background: #fff;
    border-bottom: 1px solid #eee;
    padding: 14px 0;
}
.detail-breadcrumb-bar .breadcrumb-inner {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.8rem;
    color: #888;
    font-family: 'Inter', sans-serif;
}
.detail-breadcrumb-bar a {
    color: #888;
    text-decoration: none;
    transition: color 0.2s;
}
.detail-breadcrumb-bar a:hover { color: #2E564F; }
.detail-breadcrumb-bar .current { color: #333; font-weight: 600; }

/* Hero Section */
.detail-hero {
    background: linear-gradient(135deg, #1B3835 0%, #2E564F 60%, #3D6C63 100%);
    padding: 60px 0 40px;
    position: relative;
    overflow: hidden;
}
.detail-hero::before {
    content: '';
    position: absolute;
    top: -80px; right: -80px;
    width: 400px; height: 400px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
    pointer-events: none;
}

/* Judul & Meta */
.detail-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 2.1rem;
    font-weight: 800;
    color: #fff;
    line-height: 1.3;
    margin-bottom: 18px;
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
    color: rgba(255,255,255,0.75);
    font-family: 'Inter', sans-serif;
}
.detail-meta-item i { font-size: 0.9rem; }
.detail-meta-divider {
    width: 1px;
    height: 16px;
    background: rgba(255,255,255,0.25);
}

/* Thumbnail kanan */
.detail-thumbnail-wrap {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    height: 100%;
    min-height: 260px;
    max-height: 360px;
    position: relative;
}
.detail-thumbnail-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.detail-thumbnail-wrap .no-img {
    width: 100%;
    height: 100%;
    min-height: 260px;
    background: rgba(255,255,255,0.08);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    gap: 10px;
    color: rgba(255,255,255,0.4);
    font-size: 3rem;
}

/* Body Content Area */
.detail-body {
    padding: 50px 0;
}

/* Konten berita */
.detail-content-card {
    background: #fff;
    border-radius: 16px;
    padding: 36px 40px;
    box-shadow: 0 2px 20px rgba(0,0,0,0.06);
    border: 1px solid rgba(0,0,0,0.05);
}
.detail-content-card p,
.detail-content-card .content-text {
    font-family: 'Inter', sans-serif;
    font-size: 1rem;
    line-height: 1.85;
    color: #444;
    white-space: pre-wrap;
    word-break: break-word;
}
.detail-content-card h4 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a2e2b;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    padding-bottom: 14px;
    border-bottom: 2px solid #f0f0f0;
}
.detail-content-card h4 i {
    color: #2E564F;
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
    color: #1a2e2b;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    margin-bottom: 18px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.sidebar-title::after {
    content: '';
    flex: 1;
    height: 2px;
    background: linear-gradient(90deg, #2E564F, transparent);
    border-radius: 2px;
}

.other-news-card {
    display: flex;
    gap: 14px;
    background: #fff;
    border-radius: 14px;
    padding: 14px;
    margin-bottom: 14px;
    border: 1px solid rgba(0,0,0,0.06);
    box-shadow: 0 2px 10px rgba(0,0,0,0.04);
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    text-decoration: none;
    color: inherit;
}
.other-news-card:hover {
    transform: translateX(4px);
    box-shadow: 0 6px 24px rgba(0,0,0,0.1);
    color: inherit;
    text-decoration: none;
}
.other-news-card .thumb {
    width: 76px;
    height: 64px;
    border-radius: 10px;
    overflow: hidden;
    flex-shrink: 0;
}
.other-news-card .thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.other-news-card .thumb .no-img {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #2E564F, #3D6C63);
    display: flex;
    align-items: center;
    justify-content: center;
    color: rgba(255,255,255,0.6);
    font-size: 1.4rem;
}
.other-news-card .info { flex: 1; min-width: 0; }
.other-news-card .info .on-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.85rem;
    font-weight: 700;
    color: #1a2e2b;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 5px;
}
.other-news-card .info .on-date {
    font-size: 0.73rem;
    color: #aaa;
    display: flex;
    align-items: center;
    gap: 5px;
}

.lihat-semua-btn {
    display: block;
    text-align: center;
    background: linear-gradient(135deg, #1B3835, #2E564F);
    color: #fff;
    border-radius: 12px;
    padding: 12px 0;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem;
    font-weight: 700;
    text-decoration: none;
    letter-spacing: 0.04em;
    margin-top: 6px;
    transition: opacity 0.2s ease;
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
                            <i class="bi bi-file-text-fill"></i>
                            Isi Berita
                        </h4>
                        <?php if (!empty($news['content'])): ?>
                            <div class="content-text"><?= htmlspecialchars($news['content']) ?></div>
                        <?php else: ?>
                            <p style="color:#bbb; font-style: italic;">Konten berita belum tersedia.</p>
                        <?php endif; ?>
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
                            <?php foreach ($other_news as $on): ?>
                            <a href="<?= base_url('berita/' . $on['slug']) ?>" class="other-news-card">
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
                        <?php else: ?>
                            <div style="text-align:center; color:#bbb; padding: 30px 0; font-size: 0.85rem;">
                                <i class="bi bi-newspaper" style="font-size: 2rem; display:block; margin-bottom:10px;"></i>
                                Belum ada berita lain.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </section>

</div>
