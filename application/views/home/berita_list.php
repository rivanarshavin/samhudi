<?php
/**
 * @var array  $news_items
 * @var array  $other_news
 * @var string $pagination_links
 * @var int    $total_news
 * @var int    $current_offset
 * @var int    $per_page
 */
?>
<style>
/* ===== THEME ADAPTING VARIABLES ===== */
:root {
    --bl-bg:           #fdfcfa;
    --bl-surface:      #ffffff;
    --bl-border:       rgba(39, 77, 79, 0.16);
    --bl-teal:         #274D4F;
    --bl-orange:       #E49438;
    --bl-muted:        #4b382f;
    --bl-white:        #1a202c;
    --bl-hero-bg:      #274D4F;
    /* hero text always on dark bg — always light */
    --bl-hero-title:   #ffffff;
    --bl-hero-muted:   rgba(200, 225, 225, 0.85);
    --bl-card-title:   #1a202c;
    --bl-card-text:    #4b382f;
    --bl-card-shadow:  0 6px 20px rgba(39, 77, 79, 0.08);
}

body[data-theme="dark"] {
    --bl-bg:           #15201E;
    --bl-surface:      #1E2E2B;
    --bl-border:       rgba(55,77,73,0.45);
    --bl-teal:         #377C80;
    --bl-orange:       #E49438;
    --bl-muted:        #B1CDCE;
    --bl-white:        #FFFFFF;
    --bl-hero-bg:      linear-gradient(135deg, #0d1614 0%, #15201E 40%, #1E3631 80%, #2a4a42 100%);
    --bl-hero-title:   #ffffff;
    --bl-hero-muted:   #B1CDCE;
    --bl-card-title:   #ffffff;
    --bl-card-text:    #B1CDCE;
    --bl-card-shadow:  0 8px 30px rgba(0,0,0,0.35);
}

/* Override body background for this page */
body { 
    background-color: var(--bl-bg) !important; 
    color: var(--bl-white); 
    transition: background-color 0.3s ease, color 0.3s ease;
}

.bl-section, .bl-card, .bl-card-title, .bl-hero, .bl-hero h1, .bl-hero p {
    transition: background-color 0.3s ease, background 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
}

/* ---------- HERO ---------- */
.bl-hero {
    background: var(--bl-hero-bg);
    padding: 70px 0 50px;
    position: relative;
    overflow: hidden;
}
.bl-hero::before {
    content: '';
    position: absolute;
    top: -40%;
    right: -8%;
    width: 480px;
    height: 480px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(55,124,128,0.12) 0%, transparent 70%);
    pointer-events: none;
}
.bl-hero::after {
    content: '';
    position: absolute;
    bottom: -30%;
    left: -5%;
    width: 350px;
    height: 350px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(228,148,56,0.06) 0%, transparent 70%);
    pointer-events: none;
}
.bl-hero-breadcrumb {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 20px;
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.8rem;
}
.bl-hero-breadcrumb a   { color: rgba(177,205,206,0.6); text-decoration: none; transition: color 0.2s; }
.bl-hero-breadcrumb a:hover { color: var(--bl-muted); }
.bl-hero-breadcrumb .sep { color: rgba(177,205,206,0.3); font-size: 0.7rem; }
.bl-hero-breadcrumb span { color: rgba(255,255,255,0.85); font-weight: 600; }

.bl-hero h1 {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 2.6rem;
    font-weight: 800;
    color: var(--bl-hero-title);
    margin-bottom: 10px;
    position: relative;
    z-index: 1;
}
.bl-hero p {
    color: var(--bl-hero-muted);
    font-size: 0.95rem;
    position: relative;
    z-index: 1;
}
.bl-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(55,124,128,0.2);
    border: 1px solid rgba(55,124,128,0.4);
    color: #7ecdd1;
    font-size: 0.75rem;
    font-weight: 700;
    padding: 5px 14px;
    border-radius: 50px;
    margin-bottom: 18px;
    letter-spacing: 0.06em;
    position: relative;
    z-index: 1;
}

/* ---------- MAIN SECTION ---------- */
.bl-section {
    background: var(--bl-bg);
    min-height: 60vh;
    padding: 50px 0 70px;
}

/* ---------- NEWS CARD (dark) ---------- */
.bl-card {
    background: var(--bl-surface);
    border: 1px solid var(--bl-border);
    border-radius: 18px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
    box-shadow: var(--bl-card-shadow);
    transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
}
.bl-card:hover {
    transform: translateY(-6px);
    box-shadow: 0 16px 48px rgba(39,77,79,0.18);
    border-color: rgba(55,124,128,0.5);
}
.bl-card-img {
    width: 100%;
    height: 200px;
    overflow: hidden;
    position: relative;
    flex-shrink: 0;
}
.bl-card-img img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    position: absolute;
    inset: 0;
    transition: transform 0.45s ease;
}
.bl-card:hover .bl-card-img img { transform: scale(1.06); }

/* PNG transparent "timbul" effect */
.bl-card.no-bg .bl-card-img {
    background: transparent;
    overflow: visible;
}
.bl-card.no-bg .bl-card-img img {
    object-fit: contain;
    filter: drop-shadow(0 8px 16px rgba(0,0,0,0.35));
    padding: 8px;
}

.bl-card-body {
    padding: 18px 20px 22px;
    flex: 1;
    display: flex;
    flex-direction: column;
}
.bl-card-meta {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 0.73rem;
    color: var(--bl-card-text);
    margin-bottom: 10px;
}
.bl-card-meta .dot {
    width: 3px; height: 3px;
    border-radius: 50%;
    background: rgba(177,205,206,0.4);
    display: inline-block;
}
.bl-card-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1rem;
    font-weight: 700;
    color: var(--bl-card-title);
    margin-bottom: 8px;
    line-height: 1.45;
    flex: 1;
}
.bl-card-title a {
    color: var(--bl-card-title);
    text-decoration: none;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    transition: color 0.2s;
}
.bl-card-title a:hover { color: var(--bl-teal); }
.bl-card-excerpt {
    font-size: 0.8rem;
    color: var(--bl-card-text);
    line-height: 1.6;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 14px;
}
.bl-card-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: var(--bl-teal);
    font-size: 0.75rem;
    font-weight: 700;
    text-decoration: none;
    letter-spacing: 0.06em;
    transition: gap 0.2s, color 0.2s;
    margin-top: auto;
}
.bl-card-btn:hover { color: #7ecdd1; gap: 10px; }

/* ---------- EMPTY STATE ---------- */
.bl-empty {
    text-align: center;
    padding: 70px 20px;
    color: var(--bl-card-text);
}
.bl-empty i { font-size: 3rem; margin-bottom: 14px; display: block; color: var(--bl-card-text); opacity: 0.5; }
.bl-empty h4 { font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; color: var(--bl-card-title); }

/* ---------- PAGINATION (dark) ---------- */
.custom-pagination { gap: 5px; }
.custom-pagination .page-link {
    border: 1px solid var(--bl-border) !important;
    border-radius: 10px !important;
    color: var(--bl-muted);
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-weight: 600;
    font-size: 0.82rem;
    padding: 7px 13px;
    background: var(--bl-surface);
    transition: all 0.2s;
}
.custom-pagination .page-item.active .page-link {
    background: linear-gradient(135deg, #1B3835, #377C80);
    color: #fff;
    border-color: transparent !important;
}
.custom-pagination .page-link:hover {
    background: rgba(55,124,128,0.25);
    color: #fff;
    border-color: rgba(55,124,128,0.5) !important;
}
.bl-page-info {
    text-align: center;
    font-size: 0.78rem;
    color: var(--bl-card-text);
    margin-bottom: 8px;
    font-family: 'Plus Jakarta Sans', sans-serif;
}
.bl-page-info strong { color: var(--bl-card-title); }

/* ---------- FEATURED / HIGHLIGHT CARD ---------- */
.bl-featured {
    border-radius: 20px;
    overflow: hidden;
    position: relative;
    border: 2px solid rgba(212,181,113,0.35);
    box-shadow: 0 8px 40px rgba(212,181,113,0.12), var(--bl-card-shadow);
    background: var(--bl-surface);
    display: flex;
    flex-direction: column;
    margin-bottom: 2rem;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.bl-featured:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 56px rgba(212,181,113,0.22);
}
.bl-featured-img {
    width: 100%;
    height: 320px;
    object-fit: cover;
    display: block;
}
@media (min-width: 768px) {
    .bl-featured {
        flex-direction: row;
    }
    .bl-featured-img {
        width: 45%;
        height: auto;
        min-height: 280px;
        object-fit: cover;
    }
}
.bl-featured-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(212,181,113,0.9);
    color: #1a160a;
    font-size: 0.68rem;
    font-weight: 800;
    letter-spacing: 0.1em;
    padding: 4px 12px;
    border-radius: 50px;
    margin-bottom: 14px;
}
.bl-featured-body {
    padding: 28px 30px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    flex: 1;
}
.bl-featured-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 1.45rem;
    font-weight: 800;
    color: var(--bl-card-title);
    line-height: 1.35;
    margin-bottom: 12px;
    text-decoration: none;
    display: block;
    transition: color 0.2s;
}
.bl-featured-title:hover { color: #c29a4e; }
.bl-featured-excerpt {
    font-size: 0.88rem;
    color: var(--bl-card-text);
    line-height: 1.65;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 20px;
}
.bl-featured-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #c29a4e, #d4b571);
    color: #1a160a;
    font-size: 0.78rem;
    font-weight: 800;
    letter-spacing: 0.06em;
    padding: 10px 22px;
    border-radius: 50px;
    text-decoration: none;
    width: fit-content;
    transition: opacity 0.2s, transform 0.2s;
}
.bl-featured-btn:hover { opacity: 0.9; transform: translateX(3px); color: #1a160a; }

/* ---------- SIDEBAR ---------- */
.bl-sidebar { position: sticky; top: 90px; }
.bl-sidebar-title {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.78rem;
    font-weight: 800;
    color: var(--bl-card-title);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin-bottom: 16px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.bl-sidebar-title::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, var(--bl-teal), transparent);
}
.bl-sidebar-box {
    background: var(--bl-surface);
    border: 1px solid var(--bl-border);
    border-radius: 18px;
    padding: 16px;
    box-shadow: var(--bl-card-shadow);
}
.bl-sidebar-item {
    display: flex;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid rgba(55,77,73,0.3);
    text-decoration: none;
    transition: opacity 0.2s;
}
.bl-sidebar-item:hover { opacity: 0.85; }
.bl-sidebar-item:first-child { padding-top: 2px; }
.bl-sidebar-item:last-child  { border-bottom: none; padding-bottom: 2px; }
.bl-sidebar-item .sthumb {
    width: 68px;
    height: 56px;
    border-radius: 10px;
    overflow: hidden;
    flex-shrink: 0;
    background: rgba(55,124,128,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
}
.bl-sidebar-item .sthumb img { width: 100%; height: 100%; object-fit: cover; }
.bl-sidebar-item .sinfo { flex: 1; min-width: 0; }
.bl-sidebar-item .sinfo .stitle {
    font-family: 'Plus Jakarta Sans', sans-serif;
    font-size: 0.82rem;
    font-weight: 700;
    color: var(--bl-card-title);
    line-height: 1.4;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: 5px;
}
.bl-sidebar-item .sinfo .sdate {
    font-size: 0.7rem;
    color: var(--bl-card-text);
    display: flex;
    align-items: center;
    gap: 5px;
}
</style>

<!-- Hero -->
<section class="bl-hero">
    <div class="container">
        <div class="bl-hero-breadcrumb">
            <a href="<?= base_url() ?>">Home</a>
            <i class="bi bi-chevron-right sep"></i>
            <span>Berita</span>
        </div>
        <div class="bl-hero-badge">
            <i class="bi bi-newspaper"></i>
            Berita &amp; Informasi
        </div>
        <h1>Berita &amp; Informasi</h1>
        <p>Kumpulan berita dan informasi terkini seputar Keluarga Besar H.M Samhudi</p>
    </div>
</section>

<!-- Daftar Berita -->
<section class="bl-section">
    <div class="container">
        <div class="row g-4">

            <!-- Kiri: Grid Berita -->
            <div class="col-lg-8">
                <?php
                // Pisahkan highlighted dari list biasa
                $highlight_id = !empty($highlighted_news) ? $highlighted_news['id'] : null;
                $regular_items = [];
                foreach ($news_items as $item) {
                    if ($item['id'] != $highlight_id) $regular_items[] = $item;
                }
                ?>

                <?php if (!empty($highlighted_news) && $highlighted_news['status'] === 'publish'): ?>
                <?php
                    $fThumb = !empty($highlighted_news['thumbnail']) && file_exists('./' . $highlighted_news['thumbnail'])
                        ? $highlighted_news['thumbnail']
                        : 'assets/images/berita/berita1.png';
                    $fMonths = [1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
                    $fTs = strtotime($highlighted_news['created_at']);
                ?>
                <!-- Featured Highlight Card -->
                <div class="bl-featured">
                    <img src="<?= base_url($fThumb) ?>"
                         alt="<?= htmlspecialchars($highlighted_news['title']) ?>"
                         class="bl-featured-img">
                    <div class="bl-featured-body">
                        <span class="bl-featured-badge">
                            ★ HIGHLIGHT
                        </span>
                        <div class="bl-card-meta" style="margin-bottom:12px;">
                            <i class="bi bi-calendar3"></i>
                            <?= date('j', $fTs) . ' ' . $fMonths[(int)date('n', $fTs)] . ' ' . date('Y', $fTs) ?>
                            <span class="dot"></span>
                            <i class="bi bi-person"></i>
                            <?= htmlspecialchars($highlighted_news['author_name'] ?? 'Admin') ?>
                        </div>
                        <a href="<?= base_url('berita/' . $highlighted_news['slug']) ?>" class="bl-featured-title">
                            <?= htmlspecialchars($highlighted_news['title']) ?>
                        </a>
                        <?php if (!empty($highlighted_news['content'])): ?>
                        <p class="bl-featured-excerpt"><?= htmlspecialchars(strip_tags($highlighted_news['content'])) ?></p>
                        <?php endif; ?>
                        <a href="<?= base_url('berita/' . $highlighted_news['slug']) ?>" class="bl-featured-btn">
                            BACA SELENGKAPNYA <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($regular_items)): ?>
                    <div class="row g-4">
                        <?php foreach ($regular_items as $item): ?>
                        <?php
                            $thumbPath = !empty($item['thumbnail']) && file_exists('./' . $item['thumbnail'])
                                ? $item['thumbnail']
                                : 'assets/images/berita/berita1.png';
                            $isNoBg = strtolower(pathinfo($thumbPath, PATHINFO_EXTENSION)) === 'png';
                        ?>
                        <div class="col-md-6">
                            <div class="bl-card<?= $isNoBg ? ' no-bg' : '' ?>">
                                <div class="bl-card-img">
                                    <img src="<?= base_url($thumbPath) ?>" alt="<?= htmlspecialchars($item['title']) ?>">
                                </div>
                                <div class="bl-card-body">
                                    <div class="bl-card-meta">
                                        <i class="bi bi-calendar3"></i>
                                        <?php
                                            $months = [1=>'Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agt','Sep','Okt','Nov','Des'];
                                            $ts = strtotime($item['created_at']);
                                            echo date('j', $ts) . ' ' . $months[(int)date('n', $ts)] . ' ' . date('Y', $ts);
                                        ?>
                                        <span class="dot"></span>
                                        <i class="bi bi-person"></i>
                                        <?= htmlspecialchars($item['author_name'] ?? 'Admin') ?>
                                    </div>
                                    <div class="bl-card-title">
                                        <a href="<?= base_url('berita/' . $item['slug']) ?>"><?= htmlspecialchars($item['title']) ?></a>
                                    </div>
                                    <?php if (!empty($item['content'])): ?>
                                    <p class="bl-card-excerpt"><?= htmlspecialchars(strip_tags($item['content'])) ?></p>
                                    <?php endif; ?>
                                    <a href="<?= base_url('berita/' . $item['slug']) ?>" class="bl-card-btn">
                                        BACA SELENGKAPNYA <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination Info + Links -->
                    <?php
                        $from = ($current_offset ?? 0) + 1;
                        $to   = min(($current_offset ?? 0) + ($per_page ?? 10), $total_news ?? count($news_items));
                    ?>
                    <?php if (!empty($total_news) && $total_news > ($per_page ?? 10)): ?>
                    <div class="bl-page-info mt-4">
                        Menampilkan <strong><?= $from ?>&ndash;<?= $to ?></strong> dari <strong><?= $total_news ?></strong> berita
                    </div>
                    <?php endif; ?>
                    <?php if (!empty($pagination_links)): ?>
                        <?= $pagination_links ?>
                    <?php endif; ?>

                <?php else: ?>
                    <div class="bl-empty">
                        <i class="bi bi-newspaper"></i>
                        <h4>Belum Ada Berita</h4>
                        <p style="font-size:0.85rem;">Belum ada berita yang dipublikasikan saat ini.</p>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Kanan: Sidebar Berita Lainnya -->
            <div class="col-lg-4">
                <div class="bl-sidebar">
                    <div class="bl-sidebar-title">
                        <i class="bi bi-newspaper" style="color: var(--bl-teal);"></i>
                        Berita Lainnya
                    </div>

                    <?php if (!empty($other_news)): ?>
                        <div class="bl-sidebar-box">
                            <?php foreach ($other_news as $on): ?>
                            <a href="<?= base_url('berita/' . $on['slug']) ?>" class="bl-sidebar-item">
                                <div class="sthumb">
                                    <?php if (!empty($on['thumbnail']) && file_exists('./' . $on['thumbnail'])): ?>
                                        <img src="<?= base_url($on['thumbnail']) ?>" alt="<?= htmlspecialchars($on['title']) ?>">
                                    <?php else: ?>
                                        <i class="bi bi-image" style="color: rgba(55,124,128,0.5); font-size:1.3rem;"></i>
                                    <?php endif; ?>
                                </div>
                                <div class="sinfo">
                                    <div class="stitle"><?= htmlspecialchars($on['title']) ?></div>
                                    <div class="sdate">
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
                        <div class="bl-sidebar-box" style="text-align:center; color:rgba(177,205,206,0.45); font-size:0.82rem; padding: 30px 18px;">
                            <i class="bi bi-newspaper" style="font-size:2rem; display:block; margin-bottom:10px;"></i>
                            Belum ada berita lain.
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </div>
</section>