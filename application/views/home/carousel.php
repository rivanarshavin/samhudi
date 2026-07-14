<?php
$carousel_items = json_decode(file_get_contents(FCPATH . 'assets/carousel-config.json'), true);
?>
<style>
    .carousel-section {
        width: 100% !important;
        max-width: 100vw !important;
        overflow: hidden !important;
        position: relative;
    }

    .carousel-container {
        max-width: 100%;
        overflow: hidden;
    }
</style>

<section class="carousel-section w-full overflow-x-hidden reveal reveal-scale-up">
    <div id="carousel" class="carousel-container">

        <?php $rotations = [-10, 10, -5, 8, -13, 7, -4]; $i = 0; ?>
        <?php foreach ($carousel_items as $item): ?>
        <div class="card carousel-card" data-rot="<?= $rotations[$i % count($rotations)] ?>">
            <img src="<?= base_url('assets/images/' . $item['file']) ?>" class="carousel-img">
            <div class="carousel-caption">
                <?= htmlspecialchars($item['caption']) ?>
            </div>
        </div>
        <?php $i++; ?>
        <?php endforeach; ?>

    </div>
</section>