<?php
/**
 * Login / Sign Up — Keluarga H.M Samhudi
 * ------------------------------------------------------------------
 * Ganti YOUR_PHOTO.jpg di <img> dengan foto keluarga lo.
 * Backend: auth/login.php & auth/register.php
 * Captcha: bawaan CI3 (GD, case-sensitive), di-generate oleh Auth::index()
 *          lewat $captcha_login & $captcha_signup.
 * ------------------------------------------------------------------
 */
session_start();
$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);
$mode = isset($_GET['mode']) && $_GET['mode'] === 'signup' ? 'signup' : 'login';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk / Daftar | Keluarga H.M Samhudi</title>
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

  /* Underline-only inputs */
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

  /* Panel swap */
  .swap-panel[data-hidden="true"]  { display: none; }
  .swap-panel[data-hidden="false"] { display: block; animation: fadeIn .45s cubic-bezier(.22,.61,.36,1) both; }

  @keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to   { opacity: 1; transform: translateY(0); }
  }

  /* Checkbox */
  input[type="checkbox"] { accent-color: #D4B571; }

  /* Password mismatch state */
  .input-line.input-error { border-bottom-color: #f87171; }

  /* Custom slim scrollbar buat panel form kanan */
  .form-scroll::-webkit-scrollbar { width: 6px; }
  .form-scroll::-webkit-scrollbar-track { background: transparent; }
  .form-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 999px; }
  .form-scroll::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.3); }

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

      <!-- Swap btn "Sign up / Login" — gaya outline pill di atas foto -->
      <div class="absolute inset-0 z-30 flex items-center justify-center">
        <!-- shown when mode=login → prompt to sign up -->
        <div data-hero="login" data-hidden="<?= $mode === 'login' ? 'false' : 'true' ?>" class="swap-panel">
          <button type="button" data-tab-target="signup"
                  class="tab-toggle inline-flex items-center gap-2 border border-white text-white rounded-full px-7 py-2.5 text-sm font-semibold tracking-wide hover:bg-white hover:text-teal-900 transition-all duration-300">
            Sign Up
          </button>
        </div>
      </div>

      <!-- Fallback background kalau foto belum ada -->
      <div class="absolute inset-0 bg-teal-900"></div>

      <!-- Photo — ganti src dengan path foto keluarga lo -->
      <img src="<?= base_url('assets/images/login.jpeg') ?>" alt="Keluarga H.M Samhudi"
					class="absolute inset-0 w-full h-full object-cover z-10" style="object-position: center"
					onerror="this.style.display='none'">

      <!-- Gradient tipis hanya di bawah biar tombol tetap terbaca -->
      <div class="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-black/40 to-transparent z-20"></div>
    </div>

    <!-- ============ RIGHT: FORM PANEL ============ -->
    <!-- FIX: overflow-y-auto + form-scroll biar konten ga kepotong kalau viewport pendek -->
    <div class="form-scroll relative flex flex-col justify-center px-10 sm:px-14 md:px-16 lg:px-20 h-full bg-teal-800 overflow-y-auto py-12">

      <!-- Back button -->
      <a href="<?= base_url() ?>"
         class="absolute top-7 right-8 inline-flex items-center gap-1.5 text-white/50 hover:text-white/90 text-sm font-medium transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
        </svg>
        Kembali
      </a>

      <!-- FIX: max-w-sm -> max-w-md biar signup (4 field) ga kerasa sempit horizontal -->
      <div class="w-full max-w-md mx-auto">

        <!-- Error flash -->
        <?php if (!empty($errors)): ?>
          <div class="mb-6 text-red-300 text-sm space-y-1 swap-panel" data-panel="<?= $mode ?>" data-hidden="false">
            <?php foreach ($errors as $err): ?>
              <p>• <?= htmlspecialchars($err) ?></p>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>

        <!-- -------- LOGIN FORM -------- -->
        <form id="form-login" data-panel="login" data-hidden="<?= $mode === 'login' ? 'false' : 'true' ?>"
              class="swap-panel" action="<?= base_url('auth/login') ?>" method="POST" autocomplete="on">

          <h1 class="font-display text-white text-3xl font-semibold mb-10 tracking-tight">Log In</h1>

          <div class="space-y-8">

            <div>
              <label for="login-email" class="block text-white/60 text-xs font-medium mb-2 tracking-wide uppercase">
                Email Address <span class="text-red-400">*</span>
              </label>
              <input id="login-email" name="identifier" type="text" required
                     value="<?= htmlspecialchars($old['identifier'] ?? '') ?>"
                     placeholder="example@email.com"
                     class="input-line">
            </div>

            <div>
              <label for="login-password" class="block text-white/60 text-xs font-medium mb-2 tracking-wide uppercase">
                Password <span class="text-red-400">*</span>
              </label>
              <input id="login-password" name="password" type="password" required
                     placeholder="••••••••"
                     class="input-line">
            </div>

            <div>
              <label class="block text-white/60 text-xs font-medium mb-2 tracking-wide uppercase">
                Captcha <span class="text-red-400">*</span>
              </label>
              <div class="flex items-center gap-3 mb-3">
                <div class="captcha-wrap">
                  <?= $captcha_login ?? '' ?>
                </div>
                <button type="button" data-captcha-refresh="login" class="captcha-refresh-btn">
                  Ganti Kode
                </button>
              </div>
              <input id="login-captcha" name="captcha_code" type="text" required
                     placeholder="Masukkan kode di atas"
                     autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false"
                     class="input-line">
              <p class="text-white/35 text-xs mt-1.5">Huruf besar dan kecil dibedakan.</p>
            </div>

          </div>

          <div class="flex items-center gap-2 mt-6">
            <input type="checkbox" id="remember" name="remember" class="w-3.5 h-3.5">
            <label for="remember" class="text-white/60 text-sm select-none cursor-pointer">Remember me</label>
          </div>

          <div class="mt-8 space-y-4">
            <button type="submit"
                    class="w-full border border-white/70 text-white font-display font-semibold tracking-widest text-sm uppercase rounded-full py-3.5 hover:bg-white hover:text-teal-900 transition-all duration-300">
              LOG IN
            </button>

            <p class="text-center">
              <a href="<?= base_url('auth/forgot_password') ?>" class="text-white/50 hover:text-white/80 text-sm transition-colors">
                Forgot Password?
              </a>
            </p>

            <!-- mobile only swap -->
            <p class="text-center text-white/50 text-sm md:hidden">
              Don't have an account?
              <button type="button" data-tab-target="signup" class="tab-toggle text-gold-400 font-semibold">Sign Up</button>
            </p>
          </div>

        </form>

        <!-- -------- SIGN UP FORM -------- -->
        <!-- FIX: heading mb-10->mb-8, field wrapper space-y-8->space-y-5 -->
        <form id="form-signup" data-panel="signup" data-hidden="<?= $mode === 'signup' ? 'false' : 'true' ?>"
              class="swap-panel" action="<?= base_url('auth/register') ?>" method="POST" autocomplete="on">

          <h1 class="font-display text-white text-3xl font-semibold mb-8 tracking-tight">Create Account</h1>

          <div class="space-y-5">

            <div>
              <label for="signup-name" class="block text-white/60 text-xs font-medium mb-2 tracking-wide uppercase">
                Full Name <span class="text-red-400">*</span>
              </label>
              <input id="signup-name" name="full_name" type="text" required
                     value="<?= htmlspecialchars($old['full_name'] ?? '') ?>"
                     placeholder="Lorem Ipsum"
                     class="input-line">
            </div>

            <div>
              <label for="signup-email" class="block text-white/60 text-xs font-medium mb-2 tracking-wide uppercase">
                Email Address <span class="text-red-400">*</span>
              </label>
              <input id="signup-email" name="email" type="email" required
                     value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                     placeholder="example@email.com"
                     class="input-line">
            </div>

            <div>
              <label for="signup-password" class="block text-white/60 text-xs font-medium mb-2 tracking-wide uppercase">
                Password <span class="text-red-400">*</span>
              </label>
              <input id="signup-password" name="password" type="password" required minlength="8"
                     placeholder="Min. 8 characters"
                     class="input-line">
            </div>

            <div>
              <label for="signup-password-confirm" class="block text-white/60 text-xs font-medium mb-2 tracking-wide uppercase">
                Confirm Password <span class="text-red-400">*</span>
              </label>
              <input id="signup-password-confirm" name="password_confirmation" type="password" required minlength="8"
                     placeholder="Re-enter password"
                     class="input-line">
              <p id="signup-password-error" class="hidden text-red-300 text-xs mt-1.5">Password tidak sama, cek lagi ya</p>
            </div>

            <div>
              <label class="block text-white/60 text-xs font-medium mb-2 tracking-wide uppercase">
                Captcha <span class="text-red-400">*</span>
              </label>
              <div class="flex items-center gap-3 mb-3">
                <div class="captcha-wrap">
                  <?= $captcha_signup ?? '' ?>
                </div>
                <button type="button" data-captcha-refresh="signup" class="captcha-refresh-btn">
                  Ganti Kode
                </button>
              </div>
              <input id="signup-captcha" name="captcha_code" type="text" required
                     placeholder="Masukkan kode di atas"
                     autocomplete="off" autocapitalize="off" autocorrect="off" spellcheck="false"
                     class="input-line">
              <p class="text-white/35 text-xs mt-1.5">Huruf besar dan kecil dibedakan.</p>
            </div>

          </div>

          <div class="flex items-start gap-2 mt-5">
            <input type="checkbox" id="agree" name="agree" required class="w-3.5 h-3.5 mt-0.5">
            <label for="agree" class="text-white/60 text-sm select-none cursor-pointer leading-snug">
              I agree to the <a href="#" class="text-teal-500 hover:text-gold-500 underline">Terms &amp; Conditions</a>
            </label>
          </div>

          <div class="mt-6 space-y-4">
            <button type="submit"
                    class="w-full border border-white/70 text-white font-display font-semibold tracking-widest text-sm uppercase rounded-full py-3.5 hover:bg-white hover:text-teal-900 transition-all duration-300">
              Sign Up
            </button>

            <!-- mobile only swap -->
            <p class="text-center text-white/50 text-sm">
							Already have an account?
							<button type="button" data-tab-target="login" class="tab-toggle text-teal-500 hover:text-gold-500 underline font-semibold">Log In</button>
						</p>
          </div>

        </form>

      </div>
    </div>

  </div>

<script>
  function setMode(mode) {
    document.querySelectorAll('[data-panel]').forEach(function(el) {
      el.setAttribute('data-hidden', el.getAttribute('data-panel') === mode ? 'false' : 'true');
    });
    document.querySelectorAll('[data-hero]').forEach(function(el) {
      el.setAttribute('data-hidden', el.getAttribute('data-hero') === mode ? 'false' : 'true');
    });
    var url = new URL(window.location);
    url.searchParams.set('mode', mode);
    window.history.replaceState({}, '', url);
  }

  document.querySelectorAll('.tab-toggle').forEach(function(el) {
    el.addEventListener('click', function() {
      setMode(el.getAttribute('data-tab-target'));
    });
  });

  // Confirm password validation
  (function() {
    var pw = document.getElementById('signup-password');
    var pwConfirm = document.getElementById('signup-password-confirm');
    var pwError = document.getElementById('signup-password-error');
    var signupForm = document.getElementById('form-signup');

    function checkMatch() {
      var match = pw.value === pwConfirm.value;
      pwConfirm.classList.toggle('input-error', !match && pwConfirm.value.length > 0);
      pwError.classList.toggle('hidden', match || pwConfirm.value.length === 0);
      return match;
    }

    pw.addEventListener('input', checkMatch);
    pwConfirm.addEventListener('input', checkMatch);

    signupForm.addEventListener('submit', function(e) {
      if (!checkMatch()) {
        e.preventDefault();
        pwConfirm.focus();
      }
    });
  })();

  // Captcha refresh (login & signup) — AJAX ke Auth::captcha_refresh()
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
          .catch(function() { /* diamkan aja, jangan ganggu UX */ });
      });
    });
  })();
</script>
</body>
</html>