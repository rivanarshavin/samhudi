<style>
.berita-list-hero {
    background: linear-gradient(135deg, #1B3835 0%, #2E564F 50%, #3D6C63 100%);
    padding: 80px 0 60px;
    position: relative;
    overflow: hidden;
}
.berita-list-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 500px;
    height: 500px;
    border-radius: 50%;
    background: rgba(255,255,255,0.03);
    pointer-events: none;
}
.berita-list-hero::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -5%;
    width: 350px;
    height: 350px;
    border-radius: 50%;
    background: rgba(255,255,255,0.04);
    pointer-events: none;
}
.berita-list-hero h1 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 2.8rem;
    font-weight: 800;
    color: #fff;
    margin-bottom: 12px;
    position: relative;
    z-index: 1;
}
.berita-list-hero p {
    color: rgba(255,255,255,0.7);
    font-size: 1rem;
    position: relative;
    z-index: 1;
}
.berita-list-section {
    background: #f8f9fa;
    min-height: 60vh;
    padding: 60px 0;
}
.news-card-list {
    background: #fff;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 20px rgba(0,0,0,0.06);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    height: 100%;
    border: 1px solid rgba(0,0,0,0.05);
}
.news-card-list:hover {
    transform: translateY(-6px);
    box-shadow: 0 12px 40px rgba(0,0,0,0.12);
}
.news-card-list .card-img-wrap {
    width: 100%;
    height: 200px;
    overflow: hidden;
    position: relative;
}
.news-card-list .card-img-wrap img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.4s ease;
}
.news-card-list:hover .card-img-wrap img {
    transform: scale(1.05);
}
.news-card-body {
    padding: 20px 22px 24px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.news-card-meta {
    font-size: 0.78rem;
    color: #888;
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.news-card-meta .meta-dot {
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: #ccc;
    display: inline-block;
}
.news-card-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.05rem;
    font-weight: 700;
    color: #1a2e2b;
    margin-bottom: 10px;
    line-height: 1.45;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
.news-card-excerpt {
    font-size: 0.85rem;
    color: #6c757d;
    line-height: 1.6;
    flex: 1;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 16px;
}
.news-card-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #2E564F;
    font-weight: 700;
    font-size: 0.82rem;
    text-decoration: none;
    letter-spacing: 0.04em;
    transition: gap 0.2s ease, color 0.2s ease;
}
.news-card-btn:hover {
    color: #1B3835;
    gap: 10px;
}
.empty-state {
    text-align: center;
    padding: 80px 20px;
    color: #aaa;
}
.empty-state i {
    font-size: 3.5rem;
    margin-bottom: 16px;
    display: block;
}
</style>

<!-- Hero Berita -->
<section class="berita-list-hero">
    <div class="container">
        <div style="display: flex; align-items: center; gap: 12px; margin-bottom: 18px;">
            <a href="<?= base_url() ?>" style="color: rgba(255,255,255,0.55); font-size: 0.82rem; text-decoration: none; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 500;">Home</a>
            <i class="bi bi-chevron-right" style="color: rgba(255,255,255,0.35); font-size: 0.75rem;"></i>
            <span style="color: rgba(255,255,255,0.85); font-size: 0.82rem; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600;">Berita</span>
        </div>
        <h1>Berita & Informasi</h1>
        <p>Kumpulan berita dan informasi terkini seputar Keluarga Besar H.M Samhudi</p>
    </div>
</section>

<!-- Daftar Berita -->
<section class="berita-list-section">
    <div class="container">
        <?php if (!empty($news_list)): ?>
            <div class="row g-4">
                <?php foreach ($news_list as $item): ?>
                <div class="col-lg-4 col-md-6">
                    <div class="news-card-list">
                        <div class="card-img-wrap">
                            <img
                                src="<?php echo !empty($item['thumbnail']) && file_exists('./' . $item['thumbnail'])
                                    ? base_url($item['thumbnail'])
                                    : base_url('assets/images/berita/berita1.png'); ?>"
                                alt="<?php echo htmlspecialchars($item['title']); ?>">
                        </div>
                        <div class="news-card-body">
                            <div class="news-card-meta">
                                <i class="bi bi-calendar3"></i>
                                <?php
                                    $months = [1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
                                    $ts = strtotime($item['created_at']);
                                    echo date('j', $ts) . ' ' . $months[(int)date('n', $ts)] . ' ' . date('Y', $ts);
                                ?>
                                <span class="meta-dot"></span>
                                <i class="bi bi-person"></i>
                                <?php echo htmlspecialchars($item['author_name'] ?? 'Admin'); ?>
                            </div>
                            <h3 class="news-card-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                            <?php if (!empty($item['content'])): ?>
                            <p class="news-card-excerpt"><?php echo htmlspecialchars(strip_tags($item['content'])); ?></p>
                            <?php endif; ?>
                            <a href="<?php echo base_url('berita/' . $item['slug']); ?>" class="news-card-btn">
                                BACA SELENGKAPNYA <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-newspaper"></i>
                <h4 style="color: #555; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">Belum Ada Berita</h4>
                <p style="font-size: 0.9rem;">Belum ada berita yang dipublikasikan saat ini.</p>
            </div>
        <?php endif; ?>
    </div>
</section>
