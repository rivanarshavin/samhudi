<link rel="stylesheet" href="<?= base_url('assets/style/wasiat.css?v=' . time()) ?>">

  <!-- Hero Section -->
  <section class="hero" style="background-image: url('<?php echo base_url('assets/images/background.png'); ?>');">
    <div class="hero-overlay"></div>
    <div class="hero-content">
      <div class="profile-pic">
        <img src="<?php echo base_url('assets/images/photo.png'); ?>" alt="H.M Samhudi">
      </div>
      <h2 class="subtitle">Surat Wasiat</h2>
      <h1 class="title">H.M Samhudi</h1>
    </div>
    

  </section>

  <!-- Content Section -->
  <main class="main-content">
    <p class="intro-text fade-in">
      Dengan menyebut nama Allah Yang Maha Pengasih lagi Maha Penyayang. Inilah wasiat yang
      aku titipkan kepada seluruh keturunan dan keluargaku. Bacalah dengan hati yang terbuka,
      renungkan dengan pikiran yang jernih, dan amalkan dengan penuh keikhlasan. Semoga Allah
      meridhaimu semua.
    </p>

    <div class="divider fade-in">
      <span class="line"></span>
      <span class="icon">~</span>
      <span class="line"></span>
    </div>

    <!-- Cards Grid -->
    <div class="cards-grid">
      <?php 
      $i = 1;
      foreach($wasiat_list as $wasiat): 
        $fullText = $wasiat['content'];
        $shortText = mb_strimwidth(strip_tags($fullText), 0, 150, "...");
      ?>
      <div class="wasiat-card fade-in">
        <div class="card-number"><?php echo $i++; ?></div>
        <div class="card-body">
          <div style="display: flex; justify-content: space-between; align-items: flex-start; gap: 10px;">
              <h3 class="card-title" style="margin: 0;"><?php echo htmlspecialchars($wasiat['title']); ?></h3>
              <a href="<?php echo site_url('wasiat/edit/'.$wasiat['id']); ?>" style="color: #94a3b8; font-size: 13px; text-decoration: none; font-family: 'Inter', sans-serif; transition: color 0.2s; padding-top: 5px;" onmouseover="this.style.color='#3b82f6'" onmouseout="this.style.color='#94a3b8'">Edit ✎</a>
          </div>
          <p class="card-text" style="margin-top: 20px;"><?php echo htmlspecialchars($shortText); ?></p>
          <div style="display: flex; justify-content: space-between; align-items: center; margin-top: auto;">
              <a href="javascript:void(0);" class="btn-selengkapnya" 
                 data-number="<?php echo ($i - 1); ?>" 
                 data-title="<?php echo htmlspecialchars($wasiat['title']); ?>" 
                 data-text="<?php echo htmlspecialchars($fullText); ?>">
                 SELENGKAPNYA ▼
              </a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </main>

  <!-- Modal Component -->
  <div id="wasiatModal" class="modal-overlay">
    <div class="modal-box">
      <button class="modal-close" id="closeModal">&times;</button>
      <div class="modal-watermark" id="modalNumber">1</div>
      <div class="modal-content-inner">
        <h3 class="modal-title" id="modalTitle">Judul</h3>
        <p class="modal-text" id="modalText">Teks wasiat...</p>
      </div>
    </div>
  </div>

  <script>
    (function() {
      const modal = document.getElementById('wasiatModal');
      const closeBtn = document.getElementById('closeModal');
      const modalNumber = document.getElementById('modalNumber');
      const modalTitle = document.getElementById('modalTitle');
      const modalText = document.getElementById('modalText');
      const btnSelengkapnya = document.querySelectorAll('.btn-selengkapnya');

      if (!modal) return;

      // Open Modal
      btnSelengkapnya.forEach(btn => {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          
          const number = this.getAttribute('data-number');
          const title = this.getAttribute('data-title');
          const text = this.getAttribute('data-text');

          modalNumber.textContent = number;
          modalTitle.textContent = title;
          modalText.textContent = text;

          modal.classList.add('active');
          document.body.style.overflow = 'hidden'; 
        });
      });

      // Close Modal when X is clicked
      if (closeBtn) {
        closeBtn.addEventListener('click', function() {
          closeModalFunc();
        });
      }

      // Close Modal when clicking outside the box
      if (modal) {
        modal.addEventListener('click', function(e) {
          if (e.target === modal) {
            closeModalFunc();
          }
        });
      }

      function closeModalFunc() {
        modal.classList.remove('active');
        document.body.style.overflow = ''; 
      }

      // --- SCROLL ANIMATION ---
      const fadeElements = document.querySelectorAll('.fade-in');
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('visible');
            observer.unobserve(entry.target); // Hanya animasi sekali
          }
        });
      }, {
        threshold: 0.1, // Muncul saat 10% elemen terlihat
        rootMargin: "0px 0px -50px 0px" // Sedikit offset dari bawah layar
      });

      fadeElements.forEach(el => observer.observe(el));

    })();
  </script>