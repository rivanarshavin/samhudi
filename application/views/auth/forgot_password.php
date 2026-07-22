<?php
/**
 * Lupa Password — Keluarga H.M Samhudi
 * ------------------------------------------------------------------
 * Desain disamain sama halaman login & OTP (split layout: foto kiri, form kanan).
 * Backend: auth/forgot_password.php
 * Captcha: bawaan CI3 (GD, case-sensitive), di-generate oleh Auth::forgot_password()
 *          lewat $captcha_forgot.
 * ------------------------------------------------------------------
 */
session_start();
$message = $_SESSION['message'] ?? '';
$errors  = $_SESSION['errors'] ?? [];
$old     = $_SESSION['old'] ?? [];
unset($_SESSION['message'], $_SESSION['errors'], $_SESSION['old']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lupa Password | Keluarga H.M Samhudi</title>
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

  .input-line {
    background: transparent;
    border: none;
    border-bottom: 1px solid rgba(255,255,255,0.25);
    border-radius: 0;
    color: #fff;
    outline: none;
    width: 100%;
    padding: 10px 0;
    font-size: 0.9rem;
    transition: border-color .2s;
  }
  .input-line::placeholder { color: rgba(255,255,255,0.35); }
  .input-line:focus { border-bottom-color: rgba(255,255,255,0.8); }
  .input-line:-webkit-autofill,
  .input-line:-webkit-autofill:focus {
    -webkit-box-shadow: 0 0 0 1000px #374D49 inset;
    -webkit-text-fill-color: #fff;
    caret-color: #fff;
  }

  .form-scroll::-webkit-scrollbar { width: 6px; }
  .form-scroll::-webkit-scrollbar-track { background: transparent; }
  .form-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 999px; }
  .form-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }

  .animate-fade-in { animation: fadeIn .45s cubic-bezier(.22,.61,.36,1) both; }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* Captcha box */
  .captcha-wrap {
    border: 1px solid rgba(255,255,255,0.15);
    background: rgba(255,255,255,0.04);
    border-radius: 8px;
    padding: 6px;
    display: inline-flex;
    align-items: center;
  }
  .captcha-wrap img { display: block; border-radius: 4px; }
  .captcha-refresh-btn {
    color: rgba(255,255,255,0.5);
    font-size: 0.75rem;
    text-decoration: underline;
    text-underline-offset: 2px;
    transition: color .2s;
  }
  .captcha-refresh-btn:hover { color: #fff; }
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

        <!-- Success flash -->
        <?php if (!empty($message)): ?>
          <div class="mb-6 text-green-300 text-sm">
            <p><?= htmlspecialchars($message) ?></p>
          </div>
        <?php endif; ?>

        <!-- Error flash -->
        <?php if (!empty($errors)): ?>
          <div class="mb-6 text-red-300 text-sm space-y-1">
            <?php foreach ($errors as $err): ?>
              <p>• <?= htmlspecialchars($err) ?></p>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <h1 class="font-display text-white text-3xl font-semibold mb-3 tracking-tight">Lupa Password</h1>
        <p class="text-white/50 text-sm mb-10 leading-relaxed">
          Masukin email kamu, nanti kami kirim link buat reset password.
        </p>

        <form action="<?= base_url('auth/forgot_password') ?>" method="POST" autocomplete="on">

          <div>
            <label for="fp-email" class="block text-white/60 text-xs font-medium mb-2 tracking-wide uppercase">
              Email Address <span class="text-red-400">*</span>
            </label>
            <input id="fp-email" name="email" type="email" required
                   value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                   placeholder="nama@email.com"
                   class="input-line">
          </div>

          <div class="mt-8">
            <label class="block text-white/60 text-xs font-medium mb-2 tracking-wide uppercase">
              Captcha <span class="text-red-400">*</span>
            </label>
            <div class="flex items-center gap-3 mb-3">
              <div class="captcha-wrap">
                <?= $captcha_forgot ?? '' ?>
              </div>
              <button type="button" data-captcha-refresh="forgot" class="captcha-refresh-btn">
                Ganti Kode
              </button>
            </div>
            <input id="fp-captcha" name="captcha_code" type="text" required
                   placeholder="Masukkan kode di atas"
                   autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false"
                   class="input-line">
            <p class="text-white/35 text-xs mt-1.5">Huruf besar dan kecil dibedakan.</p>
          </div>

          <div class="mt-10 space-y-4">
            <button type="submit"
                    class="w-full border border-white/70 text-white font-display font-semibold tracking-widest text-sm uppercase rounded-full py-3.5 hover:bg-white hover:text-teal-900 transition-all duration-300">
              Kirim Link Reset
            </button>

            <p class="text-center text-white/50 text-sm">
              Inget password kamu?
              <a href="<?= base_url('auth/') ?>" class="text-gold-400 font-semibold hover:text-gold-500 transition-colors">
                Log In
              </a>
            </p>
          </div>

        </form>

      </div>
    </div>

  </div>

<script>
  // Captcha refresh — AJAX ke Auth::captcha_refresh()
  (function() {
    var base = '<?= base_url('auth/captcha_refresh/') ?>';

    document.querySelectorAll('[data-captcha-refresh]').forEach(function(btn) {
      btn.addEventListener('click', function() {
        var type = btn.getAttribute('data-captcha-refresh');
        var img = document.getElementById('captcha-' + type + '-img');

        fetch(base + type)
          .then(function(res) { return res.json(); })
          .then(function(data) {
            if (data.image_url && img) {
              img.src = data.image_url + '?t=' + Date.now();
            }
          })
          .catch(function() { /* diamkan aja */ });
      });
    });
  })();
</script>
</body>
</html>