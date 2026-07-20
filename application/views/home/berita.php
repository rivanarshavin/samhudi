<section class="py-5 berita-section">
    <div class="container">

        <div class="text-center mb-5">
            <h2 class="berita-heading reveal reveal-slide-up">
                Berita
            </h2>
            <p class="berita-subheading reveal reveal-slide-up delay-100">
                Informasi Terkait Civitas H.M Samhudi
            </p>
        </div>

        <?php if (!empty($news_items)): ?>
        <div class="row g-3">
            <?php
            // Jika ada berita highlight (publish), gunakan sebagai main card
            $main_news   = (!empty($highlighted_news)) ? $highlighted_news : $news_items[0];
            // Sisanya: semua berita kecuali yang jadi main card
            $grid_news = [];
            foreach ($news_items as $n) {
                if ($n['id'] != $main_news['id']) $grid_news[] = $n;
            }
            $grid_news = array_slice($grid_news, 0, 4);
            ?>

            <!-- Main / Featured Card -->
            <div class="col-lg-6">
                <div class="news-wrapper h-100 reveal reveal-slide-right" style="position:relative;">
                    <?php if (!empty($highlighted_news)): ?>
                    <span style="position:absolute;top:14px;left:14px;z-index:10;
                                 background:rgba(212,181,113,0.92);color:#1a160a;
                                 font-size:0.7rem;font-weight:800;letter-spacing:.08em;
                                 padding:4px 12px;border-radius:50px;
                                 display:inline-flex;align-items:center;gap:5px;
                                 box-shadow:0 2px 8px rgba(0,0,0,.25);">
                        ★ HIGHLIGHT
                    </span>
                    <?php endif; ?>
                    <img src="<?php echo !empty($main_news['thumbnail']) && file_exists('./' . $main_news['thumbnail']) ? base_url($main_news['thumbnail']) : base_url('assets/images/berita/berita1.png'); ?>"
                         class="img-fluid w-100 news-img-main"
                         style="object-fit: cover; height: 100%; min-height: 400px;">
                    <div class="news-overlay"></div>
                    <div class="news-content-main">
                        <h5 class="news-title-main">
                            <?php echo htmlspecialchars($main_news['title']); ?>
                        </h5>
                        <a href="<?php echo base_url('berita/' . $main_news['slug']); ?>" class="news-link-main" style="text-decoration:none;">
                            BACA SELENGKAPNYA →
                        </a>
                    </div>
                </div>
            </div>

            <?php if (!empty($grid_news)): ?>
            <div class="col-lg-6">
                <div class="row g-3 h-100">
                    <?php foreach ($grid_news as $i => $news): ?>
                    <div class="col-6">
                        <div class="news-wrapper h-100 reveal reveal-slide-left delay-<?= ($i + 1) * 100 + 100 ?>">
                            <img src="<?php echo !empty($news['thumbnail']) && file_exists('./' . $news['thumbnail']) ? base_url($news['thumbnail']) : base_url('assets/images/berita/berita2.png'); ?>"
                                 class="img-fluid w-100 news-img-grid"
                                 style="object-fit: cover; height: 100%; min-height: 190px;">
                            <div class="news-overlay"></div>
                            <div class="news-content-grid">
                                <h6 class="news-title-grid">
                                    <?php echo htmlspecialchars($news['title']); ?>
                                </h6>
                                <a href="<?php echo base_url('berita/' . $news['slug']); ?>" style="text-decoration:none;">
                                    <small class="news-link-grid text-white">BACA SELENGKAPNYA</small>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

        </div>
        <?php endif; ?>
    </div>
</section>
