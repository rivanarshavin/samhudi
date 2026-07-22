<?php
/**
 * Reset Password — Keluarga H.M Samhudi
 * ------------------------------------------------------------------
 * Desain disamain sama halaman login, OTP & lupa password.
 * Backend: auth/reset_password/{token}
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
<title>Reset Password | Keluarga H.M Samhudi</title>
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
  .input-line.input-error { border-bottom-color: #f87171; }

  .form-scroll::-webkit-scrollbar { width: 6px; }
  .form-scroll::-webkit-scrollbar-track { background: transparent; }
  .form-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 999px; }
  .form-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }

  .animate-fade-in { animation: fadeIn .45s cubic-bezier(.22,.61,.36,1) both; }
  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
  }
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

        <h1 class="font-display text-white text-3xl font-semibold mb-3 tracking-tight">Password Baru</h1>
        <p class="text-white/50 text-sm mb-10 leading-relaxed">
          Bikin password baru buat akun kamu. Minimal 8 karakter.
        </p>

        <form id="form-reset" action="<?= base_url('auth/reset_password/' . $token) ?>" method="POST" autocomplete="off">

          <div class="space-y-6">

            <div>
              <label for="reset-password" class="block text-white/60 text-xs font-medium mb-2 tracking-wide uppercase">
                Password Baru <span class="text-red-400">*</span>
              </label>
              <input id="reset-password" name="password" type="password" required minlength="8"
                     placeholder="Min. 8 characters"
                     class="input-line">
            </div>

            <div>
              <label for="reset-password-confirm" class="block text-white/60 text-xs font-medium mb-2 tracking-wide uppercase">
                Konfirmasi Password <span class="text-red-400">*</span>
              </label>
              <input id="reset-password-confirm" name="password_confirmation" type="password" required minlength="8"
                     placeholder="Re-enter password"
                     class="input-line">
              <p id="reset-password-error" class="hidden text-red-300 text-xs mt-1.5">Password tidak sama, cek lagi ya</p>
            </div>

          </div>

          <div class="mt-10">
            <button type="submit"
                    class="w-full border border-white/70 text-white font-display font-semibold tracking-widest text-sm uppercase rounded-full py-3.5 hover:bg-white hover:text-teal-900 transition-all duration-300">
              Simpan Password
            </button>
          </div>

        </form>

      </div>
    </div>

  </div>

<script>
  // Confirm password validation
  (function() {
    var pw        = document.getElementById('reset-password');
    var pwConfirm = document.getElementById('reset-password-confirm');
    var pwError   = document.getElementById('reset-password-error');
    var resetForm = document.getElementById('form-reset');

    function checkMatch() {
      var match = pw.value === pwConfirm.value;
      pwConfirm.classList.toggle('input-error', !match && pwConfirm.value.length > 0);
      pwError.classList.toggle('hidden', match || pwConfirm.value.length === 0);
      return match;
    }

    pw.addEventListener('input', checkMatch);
    pwConfirm.addEventListener('input', checkMatch);

    resetForm.addEventListener('submit', function(e) {
      if (!checkMatch()) {
        e.preventDefault();
        pwConfirm.focus();
      }
    });
  })();
</script>
</body>
</html>
