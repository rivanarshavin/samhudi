<?php
/**
 * Verifikasi OTP — Keluarga H.M Samhudi
 * ------------------------------------------------------------------
 * Desain disamain sama halaman login (split layout: foto kiri, form kanan).
 * Backend: auth/verify_otp.php & auth/resend_otp.php
 * ------------------------------------------------------------------
 */
session_start();
$errors = $_SESSION['errors'] ?? [];
unset($_SESSION['errors']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Verifikasi OTP | Keluarga H.M Samhudi</title>
<link rel="icon" type="image/jpeg" href="<?= base_url('assets/favicon.jpeg') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          teal: {
            950: '#1E2B28',
            900: '#263530',
            800: '#374D49',
            700: '#445E59',
            600: '#536E6A',
            500: '#788F8B',
          },
          gold: {
            400: '#D4B571',
            500: '#C29A4E',
          },
        },
        fontFamily: {
          display: ['"Plus Jakarta Sans"', 'sans-serif'],
          body: ['Inter', 'sans-serif'],
        },
        keyframes: {
          fadeIn: {
            '0%':   { opacity: '0', transform: 'translateY(10px)' },
            '100%': { opacity: '1', transform: 'translateY(0)' },
          },
        },
        animation: {
          'fade-in': 'fadeIn .5s cubic-bezier(.22,.61,.36,1) both',
        },
      },
    },
  };
</script>
<style>
  * { font-family: 'Inter', sans-serif; }
  .font-display { font-family: 'Plus Jakarta Sans', sans-serif; }

  .form-scroll::-webkit-scrollbar { width: 6px; }
  .form-scroll::-webkit-scrollbar-track { background: transparent; }
  .form-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 999px; }
  .form-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }

  .animate-fade-in { animation: fadeIn .45s cubic-bezier(.22,.61,.36,1) both; }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* Kotak OTP */
  .otp-box {
    background: rgba(255,255,255,0.06);
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 12px;
    color: #fff;
    text-align: center;
    font-size: 1.4rem;
    font-weight: 600;
    width: 100%;
    aspect-ratio: 1 / 1.15;
    outline: none;
    transition: border-color .2s, background .2s;
  }
  .otp-box:focus {
    border-color: rgba(255,255,255,0.8);
    background: rgba(255,255,255,0.1);
  }
  .otp-box.otp-error { border-color: #f87171; }

  .resend-disabled { pointer-events: none; opacity: 0.4; }
</style>
</head>
<body class="h-screen overflow-hidden bg-teal-900 font-body">

  <div class="grid grid-cols-1 md:grid-cols-[40%_60%] h-full">

    <!-- ============ LEFT: PHOTO ============ -->
    <div class="relative hidden md:block h-full overflow-hidden">
      <div class="absolute inset-0 bg-teal-900"></div>
      <img src="<?= base_url('assets/images/login.jpeg') ?>" alt="Keluarga H.M Samhudi"
           class="absolute inset-0 w-full h-full object-cover z-10" style="object-position: center"
           onerror="this.style.display='none'">
      <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-black/40 to-transparent z-20"></div>
    </div>

    <!-- ============ RIGHT: FORM PANEL ============ -->
    <div class="form-scroll relative flex flex-col justify-center px-10 sm:px-14 md:px-16 lg:px-20 h-full bg-teal-800 overflow-y-auto py-12">

      <!-- Back button -->
      <a href="<?= base_url('auth/') ?>"
         class="absolute top-7 right-8 inline-flex items-center gap-1.5 text-white/50 hover:text-white/90 text-sm font-medium transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Kembali
      </a>

      <div class="w-full max-w-md mx-auto animate-fade-in">

        <!-- Error flash -->
        <?php if (!empty($errors)): ?>
          <div class="mb-6 text-red-300 text-sm space-y-1">
            <?php foreach ($errors as $err): ?>
              <p>• <?= htmlspecialchars($err) ?></p>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <h1 class="font-display text-white text-3xl font-semibold mb-3 tracking-tight">OTP Code</h1>
        <p class="text-white/50 text-sm mb-10 leading-relaxed">
          Kode udah dikirim ke email kamu, berlaku 10 menit.
        </p>

        <form id="form-otp" action="<?= base_url('auth/verify_otp') ?>" method="POST" autocomplete="off">

          <!-- 6 kotak OTP -->
          <div class="flex items-center justify-between gap-2.5 sm:gap-3.5">
            <?php for ($i = 0; $i < 6; $i++): ?>
              <input type="text" inputmode="numeric" pattern="[0-9]*" maxlength="1"
                     class="otp-box" data-otp-index="<?= $i ?>" autocomplete="one-time-code">
            <?php endfor; ?>
          </div>

          <!-- Hidden field yang beneran dikirim ke server -->
          <input type="hidden" name="otp_code" id="otp_code_hidden" required>

          <p id="otp-error" class="hidden text-red-300 text-xs mt-3">Kode belum lengkap, cek lagi ya</p>

          <div class="mt-10 space-y-4">
            <button type="submit"
                    class="w-full border border-white/70 text-white font-display font-semibold tracking-widest text-sm uppercase rounded-full py-3.5 hover:bg-white hover:text-teal-900 transition-all duration-300">
              Confirm
            </button>

            <p class="text-center text-white/50 text-sm">
              Gak dapet kode?
              <a href="<?= base_url('auth/resend_otp') ?>" id="resend-link"
                 class="text-gold-400 font-semibold hover:text-gold-500 transition-colors">
                Kirim Ulang <span id="resend-timer" class="text-white/40"></span>
              </a>
            </p>
          </div>

        </form>

      </div>
    </div>

  </div>

<script>
  // ---- Auto-advance & auto-focus antar kotak OTP ----
  (function() {
    var boxes  = Array.from(document.querySelectorAll('.otp-box'));
    var hidden = document.getElementById('otp_code_hidden');
    var error  = document.getElementById('otp-error');
    var form   = document.getElementById('form-otp');

    function syncHidden() {
      hidden.value = boxes.map(function(b) { return b.value; }).join('');
    }

    boxes.forEach(function(box, idx) {
      box.addEventListener('input', function() {
        box.value = box.value.replace(/[^0-9]/g, '').slice(0, 1);
        box.classList.remove('otp-error');
        if (box.value && idx < boxes.length - 1) {
          boxes[idx + 1].focus();
        }
        syncHidden();
      });

      box.addEventListener('keydown', function(e) {
        if (e.key === 'Backspace' && !box.value && idx > 0) {
          boxes[idx - 1].focus();
        }
      });

      // Support paste kode 6 digit sekaligus
      box.addEventListener('paste', function(e) {
        e.preventDefault();
        var text = (e.clipboardData || window.clipboardData).getData('text').replace(/[^0-9]/g, '');
        text.split('').slice(0, boxes.length).forEach(function(char, i) {
          boxes[i].value = char;
        });
        syncHidden();
        var next = boxes[Math.min(text.length, boxes.length - 1)];
        next.focus();
      });
    });

    form.addEventListener('submit', function(e) {
      syncHidden();
      if (hidden.value.length < 6) {
        e.preventDefault();
        boxes.forEach(function(b) { if (!b.value) b.classList.add('otp-error'); });
        error.classList.remove('hidden');
        boxes.find(function(b) { return !b.value; }).focus();
      }
    });

    boxes[0].focus();
  })();

  // ---- Countdown resend ----
  (function() {
    var link  = document.getElementById('resend-link');
    var timer = document.getElementById('resend-timer');
    var seconds = 60;

    function tick() {
      if (seconds <= 0) {
        link.classList.remove('resend-disabled');
        timer.textContent = '';
        return;
      }
      link.classList.add('resend-disabled');
      timer.textContent = '(' + seconds + 's)';
      seconds--;
      setTimeout(tick, 1000);
    }
    tick();
  })();
</script>
</body>
</html>
