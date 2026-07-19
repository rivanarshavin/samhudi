<?php
$makam_config = json_decode(file_get_contents(FCPATH . 'assets/makam-config.json'), true);
$makam_address = $makam_config['address'] ?? '';
$makam_maps_url = $makam_config['maps_embed_url'] ?? '';
$makam_maps_link = $makam_config['maps_link'] ?? '';
$makam_photos = $makam_config['photos'] ?? [];
$maps_search_url = $makam_maps_link;
$maps_dir_url = 'https://www.google.com/maps/dir//' . urlencode($makam_address);
?>
<section class="py-5 makam-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="makam-heading reveal reveal-slide-up">
                Lokasi Pemakaman
            </h2>
            <p class="makam-subheading reveal reveal-slide-up delay-100">
                Keluarga Besar H.M Samhudi
            </p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="makam-card reveal reveal-slide-up delay-200">
                    <div class="row g-0">
                        <div class="col-md-7">
                            <div class="makam-map">
                                <iframe src="<?= htmlspecialchars($makam_maps_url) ?>" width="100%" height="100%" style="border:0; min-height: 350px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="makam-info">
                                <h4 class="makam-alamat-title">Alamat</h4>
                                <p class="makam-alamat">
                                    <?= htmlspecialchars($makam_address) ?>
                                </p>
                                <h4 class="makam-alamat-title" style="margin-top:1rem;">Foto Pemakaman</h4>
                                <?php if (!empty($makam_photos)):
                                $total_photos = count($makam_photos);
                                $has_more = $total_photos > 5;
                                $more_count = $total_photos - 5;
                                $display_count = $has_more ? 5 : $total_photos;
                                ?>
                                <div class="makam-photo mb-3">
                                    <div class="row g-2">
                                        <?php for ($i = 0; $i < $display_count; $i++):
                                        $photo = $makam_photos[$i];
                                        $is_last_overlay = $has_more && ($i === $display_count - 1);

                                        if ($total_photos === 1) {
                                            $col_class = 'col-12';
                                            $img_height = '180px';
                                        } elseif ($total_photos === 2) {
                                            $col_class = 'col-6';
                                            $img_height = '130px';
                                        } elseif ($total_photos === 3) {
                                            $col_class = 'col-4';
                                            $img_height = '100px';
                                        } elseif ($total_photos === 4) {
                                            $col_class = 'col-3';
                                            $img_height = '90px';
                                        } else {
                                            $col_class = $i < 3 ? 'col-4' : 'col-6';
                                            $img_height = '100px';
                                        }
                                        ?>
                                        <div class="<?= $col_class ?>">
                                            <?php if ($is_last_overlay): ?>
                                            <div class="makam-foto-overlay" onclick="previewImage(<?= $i ?>)" style="position:relative;border-radius:8px;overflow:hidden;height:<?= $img_height ?>;cursor:pointer;">
                                                <img src="<?= base_url($photo) ?>" alt="Foto Makam <?= $i + 1 ?>" class="img-fluid w-100 makam-foto" style="width:100%;height:100%;object-fit:cover;filter:brightness(.4);">
                                                <div class="makam-foto-more">+<?= $more_count ?></div>
                                            </div>
                                            <?php else: ?>
                                            <img src="<?= base_url($photo) ?>" alt="Foto Makam <?= $i + 1 ?>" class="img-fluid w-100 makam-foto" style="border-radius: 8px; height: <?= $img_height ?>; object-fit: cover; cursor: pointer;" onclick="previewImage(<?= $i ?>)">
                                            <?php endif; ?>
                                        </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="<?= htmlspecialchars($maps_search_url) ?>" target="_blank" rel="noopener noreferrer" class="makam-link">
                                        <i class="bi bi-geo-alt-fill"></i> Lihat Detail
                                    </a>
                                    <a href="<?= htmlspecialchars($maps_dir_url) ?>" target="_blank" rel="noopener noreferrer" class="makam-link">
                                        <i class="bi bi-signpost-2-fill"></i> Rute
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div id="lightbox" class="lightbox-overlay">
    <span class="lightbox-close" onclick="closePreview()">&times;</span>
    <span class="lightbox-nav lightbox-prev" id="lb-prev" onclick="changeImage(-1)">&#10094;</span>
    <span class="lightbox-nav lightbox-next" id="lb-next" onclick="changeImage(1)">&#10095;</span>
    <img id="lightbox-img" src="" alt="Preview">
    <div id="lightbox-counter" style="position:absolute;bottom:20px;color:#fff;font-size:16px;z-index:10;">&nbsp;</div>
</div>

<style>
.lightbox-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0;
    width: 100%; height: 100%;
    background: rgba(0,0,0,.85);
    z-index: 99999;
    justify-content: center;
    align-items: center;
}
.lightbox-overlay.show {
    display: flex;
}
.lightbox-overlay img {
    max-width: 80%;
    max-height: 85%;
    border-radius: 8px;
}
.lightbox-close {
    position: absolute;
    top: 20px; right: 30px;
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    z-index: 10;
}
.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    color: #fff;
    font-size: 40px;
    font-weight: bold;
    cursor: pointer;
    padding: 15px;
    z-index: 10;
    user-select: none;
    transition: .3s;
}
.lightbox-nav:hover {
    color: #C8A84E;
}
.lightbox-prev { left: 20px; }
.lightbox-next { right: 20px; }
.lightbox-nav.hidden { display: none; }
</style>

<script>
var images = [];
var currentIdx = 0;

<?php foreach ($makam_photos as $photo): ?>
images.push('<?= base_url($photo) ?>');
<?php endforeach; ?>

function previewImage(idx) {
    if (images.length === 0) return;
    currentIdx = idx;
    updateLightbox();
    document.getElementById('lightbox').classList.add('show');
}

function updateLightbox() {
    document.getElementById('lightbox-img').src = images[currentIdx];
    document.getElementById('lightbox-counter').textContent = (currentIdx + 1) + ' / ' + images.length;
    document.getElementById('lb-prev').classList.toggle('hidden', currentIdx === 0);
    document.getElementById('lb-next').classList.toggle('hidden', currentIdx === images.length - 1);
}

function changeImage(dir) {
    currentIdx += dir;
    if (currentIdx < 0) currentIdx = 0;
    if (currentIdx >= images.length) currentIdx = images.length - 1;
    updateLightbox();
}

function closePreview() {
    document.getElementById('lightbox').classList.remove('show');
}

document.addEventListener('keydown', function(e) {
    if (!document.getElementById('lightbox').classList.contains('show')) return;
    if (e.key === 'Escape') closePreview();
    if (e.key === 'ArrowLeft') changeImage(-1);
    if (e.key === 'ArrowRight') changeImage(1);
});
</script>