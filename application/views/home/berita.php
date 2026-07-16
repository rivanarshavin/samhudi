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
            $main_news = $news_items[0];
            $other_news = array_slice($news_items, 1);
            ?>
            <div class="col-lg-6">
                <div class="news-wrapper h-100 reveal reveal-slide-right">
                    <img src="<?php echo !empty($main_news['thumbnail']) && file_exists('./' . $main_news['thumbnail']) ? base_url($main_news['thumbnail']) : base_url('assets/images/berita/berita1.png'); ?>" class="img-fluid w-100 news-img-main" style="object-fit: cover; height: 100%; min-height: 400px;">
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

            <?php if (!empty($other_news)): ?>
            <div class="col-lg-6">
                <div class="row g-3 h-100">
                    <?php foreach ($other_news as $i => $news): ?>
                    <div class="col-6">
                        <div class="news-wrapper h-100 reveal reveal-slide-left delay-<?= ($i + 1) * 100 + 100 ?>">
                            <img src="<?php echo !empty($news['thumbnail']) && file_exists('./' . $news['thumbnail']) ? base_url($news['thumbnail']) : base_url('assets/images/berita/berita2.png'); ?>" class="img-fluid w-100 news-img-grid" style="object-fit: cover; height: 100%; min-height: 190px;">
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
