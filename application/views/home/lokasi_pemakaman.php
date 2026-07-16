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
                                <iframe src="https://www.google.com/maps?q=Makam+Keluarga+H.+M.+Samhudi,+XVHJ%2B99H,+Citaman,+Nagreg,+Bandung,+Jawa+Barat+40215&output=embed" width="100%" height="100%" style="border:0; min-height: 350px;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <div class="makam-info">
                                <h4 class="makam-alamat-title">Alamat</h4>
                                <p class="makam-alamat">
                                    Makam Keluarga H. M. Samhudi, XVHJ+99H, Citaman, Kec. Nagreg, Kabupaten Bandung, Jawa Barat 40215
                                </p>
                                <h4 class="makam-alamat-title" style="margin-top:1rem;">Foto Pemakaman</h4>
                                <div class="makam-photo mb-3">
                                    <div class="row g-2">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                        <div class="<?= $i <= 3 ? 'col-4' : 'col-6' ?>">
                                            <img src="<?= base_url('assets/images/uji.jpeg') ?>" alt="Foto Makam <?= $i ?>" class="img-fluid w-100 makam-foto" style="border-radius: 8px; height: 100px; object-fit: cover; cursor: pointer;" onclick="previewImage(<?= $i - 1 ?>)">
                                        </div>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="d-flex gap-2 flex-wrap">
                                    <a href="https://www.google.com/maps/search/Makam+Keluarga+H.+M.+Samhudi,+XVHJ%2B99H,+Citaman,+Nagreg,+Bandung,+Jawa+Barat+40215" target="_blank" rel="noopener noreferrer" class="makam-link">
                                        <i class="bi bi-geo-alt-fill"></i> Lihat Detail
                                    </a>
                                    <a href="https://www.google.com/maps/dir//Makam+Keluarga+H.+M.+Samhudi,+XVHJ%2B99H,+Citaman,+Nagreg,+Bandung,+Jawa+Barat+40215" target="_blank" rel="noopener noreferrer" class="makam-link makam-link-outline">
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

<?php for ($i = 0; $i < 5; $i++): ?>
images.push('<?= base_url('assets/images/uji.jpeg') ?>');
<?php endfor; ?>

function previewImage(idx) {
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
