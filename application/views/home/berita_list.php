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

/* ======= Item Berita (tanpa card/shadow, dipisah garis) ======= */
.news-item {
    margin-bottom: 36px;
    padding-bottom: 36px;
    padding-top: 50px; /* ruang cadangan untuk gambar no-bg yang "timbul" ke atas */
    border-bottom: 1px solid rgba(0,0,0,0.07);
    position: relative;
}
.news-item-img {
    width: 100%;
    height: 220px;
    border-radius: 14px;
    overflow: hidden;
    margin-bottom: 16px;
    background: #eef1f0; /* fallback belakang untuk gambar tanpa background */
    position: relative;
}
.news-item-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    top: 0;
    left: 0;
    transition: transform 0.4s ease;
}
.news-item:hover .news-item-img img {
    transform: scale(1.04);
}

/* ======= Gambar tanpa background (PNG transparan) - efek "timbul" AMAN (tidak overlap) ======= */
.news-item.no-bg-img {
    overflow: visible;
}
.news-item.no-bg-img .news-item-img {
    overflow: visible;
    background: transparent;
}
.news-item.no-bg-img .news-item-img img {
    position: absolute;
    top: -50px;               /* naik ke ruang cadangan (padding-top item), bukan ke row atasnya */
    left: 50%;
    transform: translateX(-50%);
    width: 80%;
    height: calc(100% + 50px); /* lebih tinggi mengikuti ruang cadangan */
    object-fit: contain;       /* jangan crop, tampilkan utuh */
    filter: drop-shadow(0 10px 18px rgba(0,0,0,0.18));
    z-index: 2;
}
.news-item.no-bg-img:hover .news-item-img img {
    transform: translateX(-50%) translateY(-8px) scale(1.04);
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
.news-item-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.1rem;
    font-weight: 700;
    color: #1a2e2b;
    margin-bottom: 10px;
    line-height: 1.45;
}
.news-item-title a {
    color: #1a2e2b;
    text-decoration: none;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.2s ease;
}
.news-item-title a:hover {
    color: #2E564F;
}
.news-card-excerpt {
    font-size: 0.85rem;
    color: #6c757d;
    line-height: 1.6;
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

/* ======= Pagination ======= */
.custom-pagination {
    gap: 6px;
}
.custom-pagination .page-link {
    border: none;
    border-radius: 10px !important;
    color: #2E564F;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 600;
    font-size: 0.85rem;
    padding: 8px 14px;
    background: #fff;
    box-shadow: 0 1px 6px rgba(0,0,0,0.06);
}
.custom-pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #1B3835, #2E564F);
    color: #fff;
    box-shadow: none;
}
.custom-pagination .page-link:hover {
    background: #eef2f1;
    color: #1B3835;
}

/* ======= Sidebar Berita Lainnya (SATU card abu, item TANPA card) ======= */
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
.sidebar-berita-box {
    background: #eef0ef; /* abu */
    border-radius: 18px;
    padding: 18px;
}
.sidebar-berita-item {
    display: flex;
    gap: 14px;
    padding: 14px 0;
    border-bottom: 1px solid rgba(0,0,0,0.08);
    text-decoration: none;
    color: inherit;
}
.sidebar-berita-item:first-child {
    padding-top: 2px;
}
.sidebar-berita-item:last-child {
    border-bottom: none;
    padding-bottom: 2px;
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
    background: linear-gradient(135deg, #2E564F, #3D6C63);
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
    color: #1a2e2b;
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 5px;
}
.sidebar-berita-item .info .on-date {
    font-size: 0.73rem;
    color: #999;
    display: flex;
    align-items: center;
    gap: 5px;
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
        <div class="row g-4">

            <!-- Kiri: Daftar Berita (2 kolom horizontal) -->
            <div class="col-lg-8">
                <?php if (!empty($news_items)): ?>
                    <div class="row g-4">
                        <?php foreach ($news_items as $item): ?>
                        <?php
                            // Tentukan path thumbnail (fallback ke default jika tidak ada / tidak ditemukan)
                            $thumbPath = !empty($item['thumbnail']) && file_exists('./' . $item['thumbnail'])
                                ? $item['thumbnail']
                                : 'assets/images/berita/berita1.png';
                            // Deteksi gambar tanpa background (PNG transparan) untuk efek "timbul"
                            $isNoBg = strtolower(pathinfo($thumbPath, PATHINFO_EXTENSION)) === 'png';
                        ?>
                        <div class="col-md-6">
                            <div class="news-item<?= $isNoBg ? ' no-bg-img' : '' ?>">
                                <div class="news-item-img">
                                    <img src="<?= base_url($thumbPath) ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                                </div>
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
                                <h3 class="news-item-title">
                                    <a href="<?php echo base_url('berita/' . $item['slug']); ?>"><?php echo htmlspecialchars($item['title']); ?></a>
                                </h3>
                                <?php if (!empty($item['content'])): ?>
                                <p class="news-card-excerpt"><?php echo htmlspecialchars(strip_tags($item['content'])); ?></p>
                                <?php endif; ?>
                                <a href="<?php echo base_url('berita/' . $item['slug']); ?>" class="news-card-btn">
                                    BACA SELENGKAPNYA <i class="bi bi-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Info halaman & Pagination -->
                    <?php
                        $from = ($current_offset ?? 0) + 1;
                        $to   = min(($current_offset ?? 0) + ($per_page ?? 10), $total_news ?? count($news_items));
                    ?>
                    <?php if (!empty($total_news) && $total_news > ($per_page ?? 10)): ?>
                    <div style="text-align:center; font-size:0.82rem; color:#888; margin-top:16px; margin-bottom:4px; font-family:'Plus Jakarta Sans',sans-serif;">
                        Menampilkan <strong style="color:#1a2e2b;"><?= $from ?>&ndash;<?= $to ?></strong> dari <strong style="color:#1a2e2b;"><?= $total_news ?></strong> berita
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($pagination_links)): ?>
                        <?= $pagination_links ?>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="empty-state">
                        <i class="bi bi-newspaper"></i>
                        <h4 style="color: #555; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700;">Belum Ada Berita</h4>
                        <p style="font-size: 0.9rem;">Belum ada berita yang dipublikasikan saat ini.</p>
                    </div>
                <?php endif; ?>
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
                        </div>
                    <?php else: ?>
                        <div class="sidebar-berita-box" style="text-align:center; color:#aaa; font-size: 0.85rem;">
                            <i class="bi bi-newspaper" style="font-size: 2rem; display:block; margin-bottom:10px;"></i>
                            Belum ada berita lain.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>