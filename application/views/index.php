
<?php
//Load Header & Layout Global Atas
include 'layout/header.php';
include 'layout/sidebar.php';

//Load Konten Spesifik Halaman (Home)
include 'home/hero.php';
include 'home/sambutan.php';
include 'home/carousel.php';
include 'home/overlap.php';
include 'home/berita.php';

//Load Footer & Script 
include 'layout/footer.php';
?>

<!-- <!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href='https://fonts.googleapis.com/css?family=Libre Baskerville' rel='stylesheet'>
    <link href="https://fonts.cdnfonts.com/css/brittany-signature" rel="stylesheet">
    
    <link rel="stylesheet" href="/style/style.css">

    <title>Keluarga Besar H.M Samhudi</title>
</head>

<body class="body-main">

    <div id="sidebarMenu" class="sidebar-menu">

        <button onclick="closeMenu()" class="btn-close-menu">
            ×
        </button>

        <div class="sidebar-content">

            <a href="#" class="sidebar-link">
                Home
            </a>

            <a href="#" class="sidebar-link">
                Wasiat almarhum HM Samhudi
            </a>

            <a href="#" class="sidebar-link">
                Yayasan
            </a>

            <a href="#" class="sidebar-link">
                Data Keluarga Besar Samhudi
            </a>

            <a href="#" class="sidebar-link-last">
                Forum Diskusi
            </a>

        </div>
    </div>

    <div class="p-3 bg-hero-wrapper">

        <section class="position-relative d-flex align-items-center justify-content-center text-center m-1 rounded bg-hero-section">

            <div class="position-absolute top-0 start-0 w-100 h-100 hero-overlay">
            </div>

            <button id="menuBtn" onclick="openMenu()" class="btn-menu">
                ☰
            </button>

            <div class="position-relative text-white">
                <h1 class="display-2 fw-light">
                    Keluarga Besar<br>
                    H.M Samhudi
                </h1>

                <p class="fst-italic mt-3">
                    Family is not an important thing, it's everything.
                </p>
            </div>

        </section>
    </div>
    <section class="section-padding">
        <div class="container">

            <div class="row">

                <div class="col-md-4 d-flex align-items-center justify-content-center col-foto-sambutan">
                    <img src="/images/sambutan.png" alt="H.M Samhudi" class="foto-sambutan">
                </div>

                <div class="col-md-8">

                    <h4 class="sambutan-title">
                        Assalamu'alaikum Warahmatullahi Wabarakatuh,
                    </h4>

                    <p class="sambutan-text">
                        Puji syukur ke hadirat Allah SWT atas segala rahmat dan karunia-Nya
                        sehingga website Keluarga Besar H.M. Samhudi ini dapat hadir sebagai
                        sarana silaturahmi, informasi, dan dokumentasi keluarga yang dapat
                        dinikmati oleh seluruh anggota keluarga di mana pun berada.
                    </p>

                    <p class="sambutan-text">
                        Di tengah perkembangan zaman yang semakin pesat, menjaga hubungan
                        kekeluargaan menjadi hal yang sangat penting. Melalui website ini,
                        kami berharap setiap anggota keluarga dapat saling mengenal lebih dekat,
                        berbagi kabar, mengenang sejarah keluarga, serta mempererat tali
                        persaudaraan yang telah diwariskan oleh para pendahulu kita.
                    </p>

                    <p class="sambutan-text">
                        Website ini juga menjadi wadah untuk mendokumentasikan berbagai
                        kegiatan keluarga, menyimpan cerita dan sejarah, serta menjadi
                        jembatan komunikasi antar generasi agar nilai-nilai kebersamaan
                        dan kekeluargaan tetap terjaga sepanjang masa.
                    </p>

                    <p class="sambutan-text">
                        Semoga kehadiran website ini dapat memberikan manfaat,
                        mempererat silaturahmi, dan menjadi media yang mampu
                        menyatukan keluarga besar H.M. Samhudi dalam semangat
                        persaudaraan yang hangat dan harmonis.
                    </p>

                    <h5 class="sambutan-closing">
                        Wassalamu'alaikum Warahmatullahi Wabarakatuh.
                    </h5>

                    <p class="sambutan-sender">
                        Keluarga Besar H.M. Samhudi
                    </p>

                </div>

            </div>

        </div>
    </section>

    <section class="carousel-section">

        <div id="carousel" class="carousel-container">

            <div class="card carousel-card" data-rot="-10">
                <img src="images/family/family1.png" class="carousel-img">
                <div class="carousel-caption">
                    Keluarga (a)
                </div>
            </div>

            <div class="card carousel-card" data-rot="10">
                <img src="images/family/family2.png" class="carousel-img">
                <div class="carousel-caption">
                    Keluarga (b)
                </div>
            </div>

            <div class="card carousel-card" data-rot="-5">
                <img src="images/family/family3.png" class="carousel-img">
                <div class="carousel-caption">
                    Keluarga (c)
                </div>
            </div>

            <div class="card carousel-card" data-rot="8">
                <img src="images/family/family4.png" class="carousel-img">
                <div class="carousel-caption">
                    Keluarga (d)
                </div>
            </div>

            <div class="card carousel-card" data-rot="-13">
                <img src="images/family/family5.png" class="carousel-img">
                <div class="carousel-caption">
                    Keluarga (e)
                </div>
            </div>

            <div class="card carousel-card" data-rot="7">
                <img src="images/family/family6.png" class="carousel-img">
                <div class="carousel-caption">
                    Keluarga (f)
                </div>
            </div>

            <div class="card carousel-card" data-rot="-4">
                <img src="images/family/family7.png" class="carousel-img">
                <div class="carousel-caption">
                    Keluarga (g)
                </div>
            </div>
        </div>
    </section>
    
    <section class="py-5 overlap-section">

        <div class="container">
            <div class="row align-items-center justify-content-center">

                <div class="col-lg-7 position-relative">

                    <img src="images/sambutan2.png" alt="Keluarga H.M Samhudi" class="img-fluid overlap-img">

                    <div class="overlap-ornament"></div>

                </div>

                <div class="col-lg-5">

                    <div class="overlap-box">

                        <h2 class="overlap-heading">
                            Hii Keluarga H.M Samhudi!!!
                        </h2>

                        <div class="overlap-content">

                            <p class="overlap-text">
                                Dengan rasa syukur dan bangga,
                                kami persembahkan website ini
                                sebagai ruang digital untuk
                                menyambung tali silaturahmi
                            </p>

                            <div class="text-end">
                                <span class="overlap-sender">
                                    From (nama)
                                </span>
                            </div>

                        </div>

                    </div>
                </div>

            </div>
        </div>

    </section>
    
    <section class="py-5 berita-section">

        <div class="container">

            <div class="text-center mb-5">
                <h2 class="berita-heading">
                    Berita
                </h2>

                <p class="berita-subheading">
                    Informasi Terkait Civitas H.M Samhudi
                </p>
            </div>

            <div class="row g-3">

                <div class="col-lg-6">
                    <div class="news-wrapper">

                        <img src="images/berita/berita1.png" class="img-fluid w-100 news-img-main">

                        <div class="news-overlay"></div>

                        <div class="news-content-main">

                            <h5 class="news-title-main">
                                Assalamu'alaikum Warahmatullahi Wabarakatuh Para warga Keluarga...
                            </h5>

                            <a href="#" class="news-link-main">
                                BACA SELENGKAPNYA →
                            </a>

                        </div>

                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row g-3">

                        <div class="col-6">
                            <div class="news-wrapper">

                                <img src="images/berita/berita2.png" class="img-fluid w-100 news-img-grid">

                                <div class="news-overlay"></div>

                                <div class="news-content-grid">

                                    <h6 class="news-title-grid">
                                        SILATURAHMI KELUARGA HM SAMHUDI 2024
                                    </h6>

                                    <small class="news-link-grid">BACA SELENGKAPNYA</small>

                                </div>

                            </div>
                        </div>

                        <div class="col-6">
                            <div class="news-wrapper">

                                <img src="images/berita/berita3.png" class="img-fluid w-100 news-img-grid">

                                <div class="news-overlay"></div>

                                <div class="news-content-grid">

                                    <h6 class="news-title-grid">
                                        SILATURAHMI KELUARGA BESAR
                                    </h6>

                                    <small class="news-link-grid">BACA SELENGKAPNYA</small>

                                </div>

                            </div>
                        </div>

                        <div class="col-6">
                            <div class="news-wrapper">

                                <img src="images/berita/berita4.png" class="img-fluid w-100 news-img-grid">

                                <div class="news-overlay"></div>

                                <div class="news-content-grid">

                                    <h6 class="news-title-grid">
                                        Video Pembersihan Lahan
                                    </h6>

                                    <small class="news-link-grid">BACA SELENGKAPNYA</small>

                                </div>

                            </div>
                        </div>

                        <div class="col-6">
                            <div class="news-wrapper">

                                <img src="images/berita/berita5.png" class="img-fluid w-100 news-img-grid">

                                <div class="news-overlay"></div>

                                <div class="news-content-grid">

                                    <h6 class="news-title-grid">
                                        Rencana pemanfaatan tanah Cianjur
                                    </h6>

                                    <small class="news-link-grid">BACA SELENGKAPNYA</small>

                                </div>

                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>

    </section>

    <script>
        const cards = document.querySelectorAll('.card');

        const layout = [
            { x: -660, s: 0.85 },
            { x: -440, s: 0.90 },
            { x: -220, s: 0.95 },
            { x: 0, s: 1.3 },
            { x: 220, s: 0.95 },
            { x: 440, s: 0.90 },
            { x: 660, s: 0.85 }
        ];

        let active = 2;

        function render() {

            cards.forEach((card, i) => {

                let pos = i - active;
                let idx = pos + 3;

                if (idx < 0) idx += 7;
                if (idx > 6) idx -= 7;

                let p = layout[idx];

                // ROTATE FIX LOGIC 🔥
                let rot = card.dataset.rot;

                if (idx === 3) {
                    rot = 0;
                }

                card.style.position = "absolute";
                card.style.top = "50%";
                card.style.left = "50%";
                card.style.transition = "all .6s ease";

                card.style.transform =
                    `translate(calc(-50% + ${p.x}px), -50%)  rotate(${rot}deg)  scale(${p.s})`;

                card.style.zIndex = idx === 3 ? 999 : 100;
            });
        }

        cards.forEach((card, i) => {
            card.onclick = () => {
                active = i;
                render();
            };
        });

        render();
    </script>
    <script>
        function openMenu() {
            document.getElementById("sidebarMenu").style.left = "0";
            document.getElementById("menuBtn").style.display = "none";
        }

        function closeMenu() {
            document.getElementById("sidebarMenu").style.left = "-50%";
            document.getElementById("menuBtn").style.display = "block";
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

 -->