<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href='https://fonts.googleapis.com/css?family=Libre Baskerville' rel='stylesheet'>
    <link href="https://fonts.cdnfonts.com/css/brittany-signature" rel="stylesheet">

    <title>Keluarga Besar H.M Samhudi</title>
</head>

<body style="background-color: #F5EBE2; font-family: 'Libre Baskerville';font-size: 22px;">

    <nav class="sticky-top bg-white border-bottom" style="z-index: 1050; background-color: rgba(255, 255, 255, 0.85) !important; backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);">
        <div class="container-fluid px-4 px-md-5 d-flex align-items-center justify-content-between" style="height: 80px;">
            <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 700; font-size: 1.125rem; letter-spacing: -0.025em;">
                <a href="#" style="color: #1B3835; text-decoration: none;">HM Samhudin</a>
            </div>
            <ul class="d-none d-md-flex align-items-center mb-0 list-unstyled" style="gap: 50px; font-family: 'Plus Jakarta Sans', sans-serif; font-weight: 600; font-size: 0.875rem; letter-spacing: 0.05em;">
                <li><a href="#" class="nav-link-custom">Home</a></li>
                <li><a href="#" class="nav-link-custom">Wasiat</a></li>
                <li><a href="#" class="nav-link-custom">Yayasan</a></li>
                <li><a href="#" class="nav-link-custom">Data Keluarga</a></li>
                <li><a href="#" class="nav-link-custom">Forum Diskusi</a></li>
            </ul>
            <div class="d-flex align-items-center">
                <a href="#" class="d-none d-md-inline-block style-btn-masuk">Masuk</a>
                <button id="menuBtn" onclick="openMenu()" class="d-inline-block d-md-none" style="background: none; border: none; color: #1B3835; font-size: 32px; cursor: pointer; padding: 0; transition: opacity 0.3s;">☰</button>
            </div>
        </div>
    </nav>

    <div id="sidebarMenu" style="position:fixed; top:0; left:-100%; width:min(85vw,350px); height:100vh; background:#274d4f; z-index:9999; transition:.4s ease; box-shadow:0 0 20px rgba(0,0,0,.3); overflow-y:auto; font-family: 'Plus Jakarta Sans', sans-serif;">
        <button onclick="closeMenu()" style="position:absolute; top:15px; right:20px; border:none; background:none; color:white; font-size:35px; line-height: 1;">&times;</button>
        <div style="padding:70px 25px 30px 25px;">
            <a href="#" class="sidebar-link-custom">Home</a>
            <a href="#" class="sidebar-link-custom">Wasiat almarhum HM Samhudi</a>
            <a href="#" class="sidebar-link-custom">Yayasan</a>
            <a href="#" class="sidebar-link-custom">Data Keluarga Besar Samhudi</a>
            <a href="#" class="sidebar-link-custom">Forum Diskusi</a>
            <hr style="border-color: rgba(255,255,255,0.2); margin: 25px 0;">
            <a href="#" class="sidebar-link-custom" style="background: rgba(255,255,255,0.1); text-align: center; padding: 10px; border-radius: 50px; margin-bottom: 0;">Masuk</a>
        </div>
    </div>

    <style>
        .nav-link-custom {
            color: rgba(27, 56, 53, 0.9);
            text-decoration: none;
            position: relative;
            padding: 8px 0;
            transition: color 0.3s;
        }

        .nav-link-custom:hover {
            color: #3D6C63;
        }

        .nav-link-custom::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 2px;
            background-color: #3D6C63;
            transition: width 0.3s;
        }

        .nav-link-custom:hover::after {
            width: 100%;
        }

        .style-btn-masuk {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            font-size: 0.875rem;
            background-color: #1B3835;
            color: white !important;
            padding: 10px 24px;
            border-radius: 50px;
            text-decoration: none;
            transition: all 0.3s;
        }

        .style-btn-masuk:hover {
            background-color: #2E564F;
            transform: translateY(-2px);
        }

        .sidebar-link-custom {
            display: block;
            color: white;
            text-decoration: none;
            margin-bottom: 20px;
            font-size: 16px;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .sidebar-link-custom:hover {
            opacity: 0.8;
            color: #f5ebe2;
        }
    </style>
    <div class="p-3" style="background-color: #2A4D4F;">

        <section class="position-relative d-flex align-items-center justify-content-center text-center m-1 rounded"
            style=" height:calc(100vh - 2rem); background-image:url('assets/background.png'); background-size:cover; background-position:center; background-repeat:no-repeat;">
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background:rgba(0,0,0,.45);">
            </div>
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
    <section style="padding:60px 0;">
        <div class="container">

            <div class="row">

                <div class="col-md-4 d-flex align-items-center justify-content-center" style="margin-bottom:20px;">
                    <img src="assets/sambutan.png" alt="H.M Samhudi"
                        style=" max-width:300px; width:100%; border:1px solid #ccc; box-shadow:0 0 10px rgba(0,0,0,.2); ">
                </div>

                <div class="col-md-8">

                    <h4 style=" font-family:Georgia,serif; color:#4d3b2f; margin-bottom:20px; font-weight:bold; ">
                        Assalamu'alaikum Warahmatullahi Wabarakatuh,
                    </h4>

                    <p style=" text-align:justify; color:#6b584c; line-height:1.8; font-size:15px; ">
                        Puji syukur ke hadirat Allah SWT atas segala rahmat dan karunia-Nya
                        sehingga website Keluarga Besar H.M. Samhudi ini dapat hadir sebagai
                        sarana silaturahmi, informasi, dan dokumentasi keluarga yang dapat
                        dinikmati oleh seluruh anggota keluarga di mana pun berada.
                    </p>

                    <p style=" text-align:justify; color:#6b584c; line-height:1.8; font-size:15px; ">
                        Di tengah perkembangan zaman yang semakin pesat, menjaga hubungan
                        kekeluargaan menjadi hal yang sangat penting. Melalui website ini,
                        kami berharap setiap anggota keluarga dapat saling mengenal lebih dekat,
                        berbagi kabar, mengenang sejarah keluarga, serta mempererat tali
                        persaudaraan yang telah diwariskan oleh para pendahulu kita.
                    </p>

                    <p style=" text-align:justify; color:#6b584c; line-height:1.8; font-size:15px; ">
                        Website ini juga menjadi wadah untuk mendokumentasikan berbagai
                        kegiatan keluarga, menyimpan cerita dan sejarah, serta menjadi
                        jembatan komunikasi antar generasi agar nilai-nilai kebersamaan
                        dan kekeluargaan tetap terjaga sepanjang masa.
                    </p>

                    <p style=" text-align:justify; color:#6b584c; line-height:1.8; font-size:15px; ">
                        Semoga kehadiran website ini dapat memberikan manfaat,
                        mempererat silaturahmi, dan menjadi media yang mampu
                        menyatukan keluarga besar H.M. Samhudi dalam semangat
                        persaudaraan yang hangat dan harmonis.
                    </p>

                    <h5 style=" margin-top:30px; font-family:Georgia,serif; color:#4d3b2f; font-weight:bold; ">
                        Wassalamu'alaikum Warahmatullahi Wabarakatuh.
                    </h5>

                    <p style=" margin-top:15px; font-weight:bold; color:#4d3b2f; ">
                        Keluarga Besar H.M. Samhudi
                    </p>

                </div>

            </div>

        </div>
    </section>

    <section style="padding:40px 0;background: linear-gradient(to bottom, #8F9F9F 0%, #8F9F9F 30%, #274D4F 30%, #274D4F 100%);
">

        <div id="carousel" style="position:relative;height:min(400px,60vw);min-height:250px;width:100%;overflow:hidden;">

            <div class="card" style=" position:absolute; top:50%; left:50%; width:220px; background:#fff; padding:12px; border-radius:0; box-shadow:0 10px 25px rgba(0,0,0,.3); transition:.6s; cursor:pointer;
" data-rot="-10">

                <img src="assets/family1.png" style="width:100%;height:240px;object-fit:cover;">
                <div
                    style=" text-align:right; font-family:'Brittany Signature', cursive; font-size:20px; margin-top:8px; color:#1f1f1f; ">
                    Keluarga (a)
                </div>

            </div>

            <div class="card"
                style="position:absolute;top:50%;left:50%;width:220px;background:#fff;padding:12px;border-radius:0;box-shadow:0 10px 25px rgba(0,0,0,.3);transition:.6s;cursor:pointer;"
                data-rot="10">
                <img src="assets/family2.png" style="width:100%;height:240px;object-fit:cover;">
                <div
                    style="text-align:right;font-family:'Brittany Signature', cursive;font-size:20px;margin-top:8px;color:#1f1f1f;">
                    Keluarga (b)
                </div>
            </div>

            <div class="card"
                style="position:absolute;top:50%;left:50%;width:220px;background:#fff;padding:12px;border-radius:0;box-shadow:0 10px 25px rgba(0,0,0,.3);transition:.6s;cursor:pointer;"
                data-rot="-5">
                <img src="assets/family3.png" style="width:100%;height:240px;object-fit:cover;">
                <div
                    style="text-align:right;font-family:'Brittany Signature', cursive;font-size:20px;margin-top:8px;color:#1f1f1f;">
                    Keluarga (c)
                </div>
            </div>

            <div class="card"
                style="position:absolute;top:50%;left:50%;width:220px;background:#fff;padding:12px;border-radius:0;box-shadow:0 10px 25px rgba(0,0,0,.3);transition:.6s;cursor:pointer;"
                data-rot="8">
                <img src="assets/family4.png" style="width:100%;height:240px;object-fit:cover;">
                <div
                    style="text-align:right;font-family:'Brittany Signature', cursive;font-size:20px;margin-top:8px;color:#1f1f1f;">
                    Keluarga (d)
                </div>
            </div>

            <div class="card"
                style="position:absolute;top:50%;left:50%;width:220px;background:#fff;padding:12px;border-radius:0;box-shadow:0 10px 25px rgba(0,0,0,.3);transition:.6s;cursor:pointer;"
                data-rot="-13">
                <img src="assets/family5.png" style="width:100%;height:240px;object-fit:cover;">
                <div
                    style="text-align:right;font-family:'Brittany Signature', cursive;font-size:20px;margin-top:8px;color:#1f1f1f;">
                    Keluarga (e)
                </div>
            </div>

            <div class="card"
                style="position:absolute;top:50%;left:50%;width:220px;background:#fff;padding:12px;border-radius:0;box-shadow:0 10px 25px rgba(0,0,0,.3);transition:.6s;cursor:pointer;"
                data-rot="7">
                <img src="assets/family6.png" style="width:100%;height:240px;object-fit:cover;">
                <div
                    style="text-align:right;font-family:'Brittany Signature', cursive;font-size:20px;margin-top:8px;color:#1f1f1f;">
                    Keluarga (f)
                </div>
            </div>

            <div class="card"
                style="position:absolute;top:50%;left:50%;width:220px;background:#fff;padding:12px;border-radius:0;box-shadow:0 10px 25px rgba(0,0,0,.3);transition:.6s;cursor:pointer;"
                data-rot="-4">
                <img src="assets/family7.png" style="width:100%;height:240px;object-fit:cover;">
                <div
                    style="text-align:right;font-family:'Brittany Signature', cursive;font-size:20px;margin-top:8px;color:#1f1f1f;">
                    Keluarga (g)
                </div>
            </div>
        </div>
    </section>
    <section class="py-5" style="min-height:100vh; display:flex; align-items:center;">

        <div class="container">
            <div class="row align-items-center justify-content-center g-4">

                <!-- IMAGE -->
                <div class="col-12 col-lg-7 position-relative text-center">

                    <img src="assets/sambutan2.png" alt="Keluarga H.M Samhudi"
                        class="img-fluid w-100"
                        style="object-fit:cover;">

                    <!-- dekorasi titik (hide di mobile pakai d-none d-lg-block) -->
                    <div class="d-none d-lg-block"
                        style="position:absolute; left:-20px; top:50%; transform:translateY(-50%);
                    width:60px; height:180px;
                    background-image:radial-gradient(#8b7d72 1px, transparent 1px);
                    background-size:8px 8px; opacity:.5;">
                    </div>

                </div>

                <!-- TEXT -->
                <div class="col-12 col-lg-5">

                    <div class="bg-white shadow p-4 p-lg-5 mx-auto"
                        style="
                        max-width:500px;
                        width:100%;
                        position:relative;
                        z-index:2;
                        margin-top:-40px;
                    ">

                        <p class="mb-4"
                            style="font-family:Georgia, serif; font-size:1.25rem; font-weight:600; line-height:1.6; color:#4b382f;">
                            Dengan rasa syukur dan bangga,
                            kami persembahkan website ini
                            sebagai ruang digital untuk
                            menyambung tali silaturahmi
                        </p>

                        <div class="text-end">
                            <span style="font-family:Georgia, serif; font-size:1.2rem; font-weight:700; color:#4b382f;">
                                From (nama)
                            </span>
                        </div>

                    </div>

                </div>

            </div>
        </div>

    </section>
    <section class="py-5" style="min-height:100vh; background:#274d4f;">

        <div class="container">

            <div class="text-center mb-5">
                <h2 style=" color:#fff; font-family:Georgia, serif; font-size:3rem; font-weight:700; letter-spacing:1px;
        "> Berita
                </h2>

                <p style=" color:#e9e2dc; font-size:1.1rem; margin-top:-10px;
        ">
                    Informasi Terkait Civitas H.M Samhudi
                </p>
            </div>

            <div class="row g-3">

                <div class="col-lg-6">
                    <div style="position:relative; overflow:hidden;">

                        <img src="assets/berita1.png" class="img-fluid w-100" style=" height:330px; object-fit:cover; transition:.4s; transform:scale(1);
                    " onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                        <div style=" position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,.75), rgba(0,0,0,.1)); transition:.4s;
                " onmouseover="this.style.background='linear-gradient(to top, rgba(0,0,0,.92), rgba(0,0,0,.25))'" onmouseout="this.style.background='linear-gradient(to top, rgba(0,0,0,.75), rgba(0,0,0,.1))'">
                        </div>

                        <div style="position:absolute;left:20px;right:20px;bottom:20px;color:white;transition:.4s;
                " onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                            <h5 style="font-family:Georgia, serif;font-size:1.25rem;line-height:1.4;
">
                                Assalamu'alaikum Warahmatullahi Wabarakatuh Para warga Keluarga...
                            </h5>

                            <a href="#" style=" color:#f5ebe2; font-size:.85rem; text-decoration:none;
                    ">
                                BACA SELENGKAPNYA →
                            </a>

                        </div>

                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="row g-3">

                        <div class="col-6">
                            <div style="position:relative; overflow:hidden;">

                                <img src="assets/berita2.png" class="img-fluid w-100" style=" height:160px; object-fit:cover; transition:.4s; transform:scale(1);
                            " onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">

                                <div style="position:absolute;inset:0;background:linear-gradient(to top, rgba(0,0,0,.75), rgba(0,0,0,.1));transition:.4s;
                        " onmouseover="this.style.background='linear-gradient(to top, rgba(0,0,0,.92), rgba(0,0,0,.25))'" onmouseout="this.style.background='linear-gradient(to top, rgba(0,0,0,.75), rgba(0,0,0,.1))'">
                                </div>

                                <div style="position:absolute;left:10px;right:10px;bottom:10px;color:white;transition:.4s;
                        " onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">

                                    <h6 style="font-family:Georgia, serif; font-size:.9rem;">
                                        SILATURAHMI KELUARGA HM SAMHUDI 2024
                                    </h6>

                                    <small style="color:#f5ebe2;">BACA SELENGKAPNYA</small>

                                </div>

                            </div>
                        </div>

                        <div class="col-6">
                            <div style="position:relative; overflow:hidden;">

                                <img src="assets/berita3.png" class="img-fluid w-100" style="height:160px;object-fit:cover;transition:.4s;transform:scale(1);
                            " onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                                <div style=" position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,.75), rgba(0,0,0,.1)); transition:.4s;" onmouseover="this.style.background='linear-gradient(to top, rgba(0,0,0,.92), rgba(0,0,0,.25))'" onmouseout="this.style.background='linear-gradient(to top, rgba(0,0,0,.75), rgba(0,0,0,.1))'"></div>
                                <div style=" position:absolute; left:10px; right:10px; bottom:10px; color:white; transition:.4s;
                        " onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">
                                    <h6 style="font-family:Georgia, serif; font-size:.9rem;">
                                        SILATURAHMI KELUARGA BESAR
                                    </h6>

                                    <small style="color:#f5ebe2;">BACA SELENGKAPNYA</small>

                                </div>

                            </div>
                        </div>

                        <div class="col-6">
                            <div style="position:relative; overflow:hidden;">

                                <img src="assets/berita4.png" class="img-fluid w-100" style="height:160px;object-fit:cover;transition:.4s;transform:scale(1);
                            " onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                                <div style=" position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,.75), rgba(0,0,0,.1)); transition:.4s;
                        " onmouseover="this.style.background='linear-gradient(to top, rgba(0,0,0,.92), rgba(0,0,0,.25))'" onmouseout="this.style.background='linear-gradient(to top, rgba(0,0,0,.75), rgba(0,0,0,.1))'"> </div>

                                <div style=" position:absolute; left:10px; right:10px; bottom:10px; color:white; transition:.4s;
                        " onmouseover="this.style.transform='translateY(-5px)'" onmouseout="this.style.transform='translateY(0)'">

                                    <h6 style="font-family:Georgia, serif; font-size:.9rem;">
                                        Video Pembersihan Lahan
                                    </h6>

                                    <small style="color:#f5ebe2;">BACA SELENGKAPNYA</small>

                                </div>

                            </div>
                        </div>

                        <div class="col-6">
                            <div style="position:relative; overflow:hidden;">

                                <img src="assets/berita5.png" class="img-fluid w-100" style="height:160px;object-fit:cover;transition:.4s;transform:scale(1);
                            " onmouseover="this.style.transform='scale(1.08)'" onmouseout="this.style.transform='scale(1)'">
                                <div style=" position:absolute; inset:0; background:linear-gradient(to top, rgba(0,0,0,.75), rgba(0,0,0,.1)); transition:.4s;
                        " onmouseover="this.style.background='linear-gradient(to top, rgba(0,0,0,.92), rgba(0,0,0,.25))'" onmouseout="this.style.background='linear-gradient(to top, rgba(0,0,0,.75), rgba(0,0,0,.1))'"></div>
                                <div style=" position:absolute; left:10px; right:10px; bottom:10px; color:white; transition:.4s; " onmouseover="this.style.transform='translateY(-5px)'"
                                    onmouseout="this.style.transform='translateY(0)'">

                                    <h6 style="font-family:Georgia, serif; font-size:.9rem;">
                                        Rencana pemanfaatan tanah Cianjur
                                    </h6>

                                    <small style="color:#f5ebe2;">BACA SELENGKAPNYA</small>

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

        function getLayout() {

            if (window.innerWidth < 768) {
                return [{
                        x: -500,
                        s: .65
                    },
                    {
                        x: -300,
                        s: .75
                    },
                    {
                        x: -150,
                        s: .85
                    },
                    {
                        x: 0,
                        s: 1.2
                    },
                    {
                        x: 150,
                        s: .85
                    },
                    {
                        x: 300,
                        s: .75
                    },
                    {
                        x: 500,
                        s: .65
                    }
                ];
            }

            return [{
                    x: -660,
                    s: .85
                },
                {
                    x: -440,
                    s: .90
                },
                {
                    x: -220,
                    s: .95
                },
                {
                    x: 0,
                    s: 1.3
                },
                {
                    x: 220,
                    s: .95
                },
                {
                    x: 440,
                    s: .90
                },
                {
                    x: 660,
                    s: .85
                }
            ];
        }

        let active = 2;

        function render() {

            const layout = getLayout();

            cards.forEach((card, i) => {

                let pos = i - active;
                let idx = pos + 3;

                if (idx < 0) idx += 7;
                if (idx > 6) idx -= 7;

                let p = layout[idx];

                let rot = card.dataset.rot;

                if (idx === 3) {
                    rot = 0;
                }

                card.style.position = "absolute";
                card.style.top = "50%";
                card.style.left = "50%";
                card.style.transition = "all .6s ease";

                card.style.transform =
                    `translate(calc(-50% + ${p.x}px), -50%) rotate(${rot}deg) scale(${p.s})`;

                card.style.zIndex = idx === 3 ? 999 : 100;
            });
        }

        cards.forEach((card, i) => {
            card.onclick = () => {
                active = i;
                render();
            };
        });

        window.addEventListener("resize", render);

        render();
    </script>
    <script>
        function openMenu() {
            document.getElementById("sidebarMenu").style.left = "0";
            document.getElementById("menuBtn").style.opacity = "0";
        }

        function closeMenu() {
            document.getElementById("sidebarMenu").style.left = "-100%";
            document.getElementById("menuBtn").style.opacity = "1";
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>