<?php
$intro_config = json_decode(file_get_contents(FCPATH . 'assets/intro-config.json'), true);
$intro_text = $intro_config['text'] ?? "Dengan rasa syukur dan bangga,\nkami persembahkan website ini\nsebagai ruang digital untuk\nmenyambung tali silaturahmi";
$intro_sender = $intro_config['sender'] ?? 'From (nama)';
?>
<section class="py-5 overlap-section">
        <div class="container">
            <div class="row align-items-center justify-content-center">

                <div class="col-lg-7 position-relative reveal reveal-slide-right">
                    <img src="<?= base_url('assets/images/sambutannew.png') ?>" alt="Keluarga H.M Samhudi" class="img-fluid overlap-img">
                    <div class="overlap-ornament"></div>
                </div>

                <div class="col-lg-5">
                    <div class="overlap-box reveal reveal-slide-left delay-200">
                        <div class="overlap-content">
                            <p class="overlap-text">
                                <?= nl2br(htmlspecialchars($intro_text)) ?>
                            </p>
                            <div class="text-end">
                                <span class="overlap-sender">
                                    <?= htmlspecialchars($intro_sender) ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>