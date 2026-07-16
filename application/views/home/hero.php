<?php
$banner_config = json_decode(file_get_contents(FCPATH . 'assets/banner-config.json'), true);
$banner = $banner_config['file'] ?? 'background2.png';
?>
<section class="position-relative d-flex align-items-center justify-content-start text-center bg-hero-section" style="background-image: url('<?= base_url('assets/images/' . $banner) ?>');">

    <div class="position-absolute top-0 start-0 w-100 h-100 hero-overlay"></div>

    <div class="position-relative text-white hero-content" style="padding-left: 4rem; width: auto; max-width: 600px; text-align: center;">
        <div style="margin-bottom: 0.6rem; display: flex; justify-content: center;">
            <img src="<?= base_url('assets/images/ornamen-atas.png') ?>" alt="" style="max-width: 250px; width: 100%; height: auto;">
        </div>
        <h1 class="hero-title" style="font-family: 'Poppins', sans-serif; font-weight: 400; font-size: clamp(2.2rem, 5vw, 3.8rem);">
            Keluarga Besar
        </h1>
        <h1 class="hero-title" style="font-family: 'Poppins', sans-serif; font-weight: 400; font-size: clamp(2.5rem, 6vw, 4.8rem); margin-top: -0.3rem;">
            H.M Samhudi
        </h1>
        <div style="margin: 0.6rem auto 0.5rem; max-width: 380px; width: 100%;">
            <img src="<?= base_url('assets/images/ornamen-bawah.png') ?>" alt="" style="width: 100%; height: auto; display: block;">
        </div>
        <p class="hero-subtitle" style="font-family: 'Poppins', sans-serif; font-weight: 400; font-style: italic; font-size: clamp(1.2rem, 2.6vw, 1.6rem); color: #C8A84E; opacity: 1; margin-top: 0.4rem;">
            Family is not an important thing,<br>
            it's everything.
        </p>
        <p style="font-family: 'Poppins', sans-serif; font-weight: 400; font-size: clamp(1rem, 2.2vw, 1.3rem); margin-top: 0.8rem; line-height: 1.5; color: white;">
            Lebih dari sekadar darah,<br>
            kita adalah cerita yang terus berjalan<br>
            dan cinta yang tak pernah pudar
        </p>
    </div>

</section>
