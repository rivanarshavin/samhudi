<?php
$sambutan_config = json_decode(file_get_contents(FCPATH . 'assets/sambutan-config.json'), true);
$s_title = $sambutan_config['title'] ?? "Assalamu'alaikum Warahmatullahi Wabarakatuh,";
$s_paragraphs = $sambutan_config['paragraphs'] ?? [];
$s_closing = $sambutan_config['closing'] ?? "Wassalamu'alaikum Warahmatullahi Wabarakatuh.";
$s_sender = $sambutan_config['sender'] ?? 'Keluarga Besar H.M. Samhudi';
?>
<section class="section-padding sambutan-section">
        <div class="container">
            <div class="row">

                <div class="col-md-4 d-flex align-items-center justify-content-center col-foto-sambutan reveal reveal-slide-right">
                    <img src="<?= base_url('assets/images/photo.png') ?>" alt="H.M Samhudi" class="foto-sambutan">
                </div>

                <div class="col-md-8">
                    <h4 class="sambutan-title reveal reveal-slide-up">
                        <?= htmlspecialchars($s_title) ?>
                    </h4>
                    <?php foreach ($s_paragraphs as $i => $par): ?>
                    <p class="sambutan-text reveal reveal-slide-up delay-<?= min(($i + 1) * 100, 800) ?>">
                        <?= htmlspecialchars($par) ?>
                    </p>
                    <?php endforeach; ?>
                    <h5 class="sambutan-closing reveal reveal-slide-up delay-500">
                        <?= htmlspecialchars($s_closing) ?>
                    </h5>
                    <p class="sambutan-sender reveal reveal-slide-up delay-600">
                        <?= htmlspecialchars($s_sender) ?>
                    </p>
                </div>

            </div>
        </div>
    </section>