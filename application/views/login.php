<?php
/**
 * Login / Sign Up — Keluarga H.M Samhudi
 * ------------------------------------------------------------------
 * Frontend only for now. Wire up the two <form> blocks below to your
 * auth backend later:
 *   - #form-login  -> POST action="auth/login.php"
 *   - #form-signup -> POST action="auth/register.php"
 *
 * $errors / $old are placeholders — drop your session flash data in
 * once the backend exists.
 * ------------------------------------------------------------------
 */
session_start();

$errors = $_SESSION['errors'] ?? [];
$old    = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['old']);

// Which panel is active on load: "login" (default) or "signup".
$mode = isset($_GET['mode']) && $_GET['mode'] === 'signup' ? 'signup' : 'login';
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Masuk — Keluarga H.M Samhudi</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          teal: {
            950: '#0F211F',
            900: '#1B3835',
            800: '#22443F',
            700: '#2E564F',
            600: '#3D6C63',
          },
          sand: {
            100: '#F6F2E7',
            200: '#EDE6D3',
            300: '#D8CBA6',
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
          floatSlow: {
            '0%, 100%': { transform: 'translate(0,0)' },
            '50%': { transform: 'translate(-18px,-26px)' },
          },
          floatSlower: {
            '0%, 100%': { transform: 'translate(0,0)' },
            '50%': { transform: 'translate(22px,18px)' },
          },
          fadeUp: {
            '0%': { opacity: '0', transform: 'translateY(14px)' },
            '100%': { opacity: '1', transform: 'translateY(0)' },
          },
        },
        animation: {
          'float-slow': 'floatSlow 9s ease-in-out infinite',
          'float-slower': 'floatSlower 13s ease-in-out infinite',
          'fade-up': 'fadeUp .7s cubic-bezier(.22,.61,.36,1) both',
        },
      },
    },
  };
</script>
<style>
  body { font-family: 'Inter', sans-serif; }
  .font-display { font-family: 'Plus Jakarta Sans', sans-serif; letter-spacing: -0.01em; }

  .lattice {
    background-image: radial-gradient(rgba(246,242,231,0.06) 1px, transparent 1px);
    background-size: 24px 24px;
  }

  input:-webkit-autofill {
    -webkit-box-shadow: 0 0 0 1000px rgba(255,255,255,0.06) inset;
    -webkit-text-fill-color: #F6F2E7;
    caret-color: #F6F2E7;
  }

  .tab-underline { position: relative; }
  .tab-underline::after {
    content: '';
    position: absolute;
    left: 0; right: 0; bottom: -1px;
    height: 2px;
    background: #D4B571;
    transform: scaleX(0);
    transform-origin: left;
    transition: transform .25s ease;
  }
  .tab-underline.is-active::after { transform: scaleX(1); }

  /* buat swap login ke register */
  .swap-panel[data-hidden="true"] {
    display: none;
  }
  .swap-panel[data-hidden="false"] {
    display: block;
    animation: fadeUp .45s cubic-bezier(.22,.61,.36,1) both;
  }
</style>
</head>
<body class="min-h-screen bg-teal-950 font-body">

  <div class="relative min-h-screen grid grid-cols-1 md:grid-cols-2 overflow-hidden">

    <!-- Ambient background across the whole page -->
    <div class="absolute inset-0 bg-gradient-to-br from-teal-800 via-teal-900 to-teal-950"></div>
    <div class="absolute inset-0 lattice"></div>
    <div class="absolute -top-24 -left-16 w-80 h-80 rounded-full bg-teal-600/30 blur-3xl animate-float-slow"></div>
    <div class="absolute bottom-0 right-0 w-[26rem] h-[26rem] rounded-full bg-gold-500/10 blur-3xl animate-float-slower"></div>
    <div class="absolute top-1/3 right-1/4 w-56 h-56 rounded-full bg-teal-500/10 blur-3xl animate-float-slow"></div>

    <div class="absolute top-6 right-6 z-20">
      <a href="index"
         class="group inline-flex items-center gap-2 text-sand-100/60 hover:text-sand-100 text-sm font-medium transition-colors duration-200">
        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full border border-white/20 bg-white/5 group-hover:bg-white/10 transition-colors duration-200">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
          </svg>
        </span>
        <span class="hidden sm:inline">Kembali</span>
      </a>
    </div>

    <!-- ============ LEFT: BRAND / DYNAMIC PROMPT ============ -->
    <div class="relative z-10 flex flex-col justify-between p-8 sm:p-12 md:p-16 min-h-[280px] md:min-h-screen">

      <div class="flex items-center gap-2.5 animate-fade-up">
        <span class="inline-flex items-center justify-center w-9 h-9 rounded-full border border-gold-400/60 text-gold-400">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="w-4.5 h-4.5" fill="none" stroke="currentColor" stroke-width="1.6">
            <circle cx="8" cy="9" r="3.2"/>
            <circle cx="16" cy="9" r="3.2"/>
            <path d="M4 19c0-2.8 1.9-5 4.5-5s4.5 2.2 4.5 5M11 19c0-2.8 1.9-5 4.5-5s4.5 2.2 4.5 5" stroke-linecap="round"/>
          </svg>
        </span>
        <span class="font-display text-sand-100 text-sm tracking-[0.2em] uppercase">Samhudi</span>
      </div>

      <!-- Two hero states cross-fade in the same slot -->
      <div class="relative mt-10 md:mt-0 max-w-md">

        <div data-hero="login" data-hidden="<?= $mode === 'login' ? 'false' : 'true' ?>" class="swap-panel">
          <p class="font-display text-gold-400 text-xs tracking-[0.3em] uppercase mb-3">Civitas Keluarga</p>
          <h1 class="font-display text-sand-100 text-3xl sm:text-4xl md:text-[2.4rem] leading-[1.15] font-bold mb-4">
            Belum Punya<br>Akun?
          </h1>
          <p class="text-sand-200/75 text-sm leading-relaxed mb-8">
            Bergabung untuk mengikuti kabar, silaturahmi, dan agenda Keluarga Besar H.M Samhudi.
          </p>
          <button type="button" data-tab-target="signup"
                  class="tab-toggle group inline-flex items-center gap-2 border border-sand-100/60 hover:border-gold-400 hover:bg-gold-400 hover:text-teal-950 text-sand-100 rounded-full px-6 py-2.5 text-sm font-semibold tracking-wide transition-all duration-300">
            Daftar Sekarang
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5l6 7.5-6 7.5M19 12H4" />
            </svg>
          </button>
        </div>

        <div data-hero="signup" data-hidden="<?= $mode === 'signup' ? 'false' : 'true' ?>" class="swap-panel">
          <p class="font-display text-gold-400 text-xs tracking-[0.3em] uppercase mb-3">Civitas Keluarga</p>
          <h1 class="font-display text-sand-100 text-3xl sm:text-4xl md:text-[2.4rem] leading-[1.15] font-bold mb-4">
            Sudah Jadi<br>Bagian Keluarga?
          </h1>
          <p class="text-sand-200/75 text-sm leading-relaxed mb-8">
            Masuk kembali untuk melanjutkan mengikuti kabar dan agenda Keluarga Besar H.M Samhudi.
          </p>
          <button type="button" data-tab-target="login"
                  class="tab-toggle group inline-flex items-center gap-2 border border-sand-100/60 hover:border-gold-400 hover:bg-gold-400 hover:text-teal-950 text-sand-100 rounded-full px-6 py-2.5 text-sm font-semibold tracking-wide transition-all duration-300">
            Masuk Sekarang
            <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5 transition-transform duration-300 group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5l6 7.5-6 7.5M19 12H4" />
            </svg>
          </button>
        </div>
      </div>

      <p class="hidden md:block text-sand-100/40 text-xs tracking-wide animate-fade-up">
        &copy; <?= date('Y') ?> Keluarga Besar H.M Samhudi
      </p>
    </div>

    <!-- ============ RIGHT: GLASS FORM PANEL ============ -->
    <div class="relative z-10 flex items-center justify-center p-6 sm:p-10 md:p-16">
      <div class="w-full max-w-md rounded-2xl border border-white/15 bg-white/10 backdrop-blur-2xl backdrop-saturate-150 shadow-2xl shadow-black/30 p-8 sm:p-10 animate-fade-up">

        <!-- Tabs -->
        <div class="flex items-center gap-6 border-b border-white/15 mb-8">
          <button type="button" data-tab-target="login"
                  class="tab-toggle tab-underline <?= $mode === 'login' ? 'is-active text-sand-100' : 'text-sand-100/40' ?> font-display text-lg font-semibold pb-3 transition-colors">
            Masuk
          </button>
          <button type="button" data-tab-target="signup"
                  class="tab-toggle tab-underline <?= $mode === 'signup' ? 'is-active text-sand-100' : 'text-sand-100/40' ?> font-display text-lg font-semibold pb-3 transition-colors">
            Daftar
          </button>
        </div>

        <?php if (!empty($errors)): ?>
          <div class="mb-6 rounded-lg border border-red-300/40 bg-red-500/10 text-red-100 text-sm px-4 py-3">
            <ul class="list-disc list-inside space-y-0.5">
              <?php foreach ($errors as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
              <?php endforeach; ?>
            </ul>
          </div>
        <?php endif; ?>

        <div class="relative">

          <!-- ---------- LOGIN FORM ---------- -->
          <form id="form-login" data-panel="login" data-hidden="<?= $mode === 'login' ? 'false' : 'true' ?>"
                class="swap-panel space-y-5" action="auth/login.php" method="POST" autocomplete="on">

            <div>
              <label for="login-email" class="block text-xs font-semibold tracking-wide text-sand-100/80 uppercase mb-2">
                Email atau Username
              </label>
              <input id="login-email" name="identifier" type="text" required
                     value="<?= htmlspecialchars($old['identifier'] ?? '') ?>"
                     placeholder="nama@email.com"
                     class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-sand-100 placeholder:text-sand-100/35 focus:outline-none focus:ring-2 focus:ring-gold-400/50 focus:border-gold-400/60 transition">
            </div>

            <div>
              <div class="flex items-center justify-between mb-2">
                <label for="login-password" class="block text-xs font-semibold tracking-wide text-sand-100/80 uppercase">
                  Kata Sandi
                </label>
                <a href="lupa-password.php" class="text-xs text-gold-400 hover:text-sand-100 font-medium">Lupa sandi?</a>
              </div>
              <input id="login-password" name="password" type="password" required
                     placeholder="••••••••"
                     class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-sand-100 placeholder:text-sand-100/35 focus:outline-none focus:ring-2 focus:ring-gold-400/50 focus:border-gold-400/60 transition">
            </div>

            <label class="flex items-center gap-2 text-sm text-sand-100/60 select-none">
              <input type="checkbox" name="remember" class="w-4 h-4 rounded border-white/30 bg-white/5 text-gold-400 focus:ring-gold-400/40">
              Ingat saya
            </label>

            <button type="submit"
                    class="w-full bg-gold-500 hover:bg-gold-400 text-teal-950 font-display font-semibold tracking-wide text-sm uppercase rounded-lg py-3.5 transition-all duration-300 shadow-lg shadow-black/20 hover:-translate-y-0.5">
              Masuk
            </button>

            <p class="text-center text-sm text-sand-100/60 pt-1">
              Belum punya akun?
              <button type="button" data-tab-target="signup" class="tab-toggle text-gold-400 font-semibold hover:text-sand-100">Daftar di sini</button>
            </p>
          </form>

          <!-- ---------- SIGN UP FORM ---------- -->
          <form id="form-signup" data-panel="signup" data-hidden="<?= $mode === 'signup' ? 'false' : 'true' ?>"
                class="swap-panel space-y-5" action="auth/register.php" method="POST" autocomplete="on">

            <div>
              <label for="signup-name" class="block text-xs font-semibold tracking-wide text-sand-100/80 uppercase mb-2">
                Nama Lengkap
              </label>
              <input id="signup-name" name="full_name" type="text" required
                     value="<?= htmlspecialchars($old['full_name'] ?? '') ?>"
                     placeholder="Sesuai KTP"
                     class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-sand-100 placeholder:text-sand-100/35 focus:outline-none focus:ring-2 focus:ring-gold-400/50 focus:border-gold-400/60 transition">
            </div>

            <div>
              <label for="signup-email" class="block text-xs font-semibold tracking-wide text-sand-100/80 uppercase mb-2">
                Email
              </label>
              <input id="signup-email" name="email" type="email" required
                     value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                     placeholder="nama@email.com"
                     class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-sand-100 placeholder:text-sand-100/35 focus:outline-none focus:ring-2 focus:ring-gold-400/50 focus:border-gold-400/60 transition">
            </div>

            <div>
              <label for="signup-password" class="block text-xs font-semibold tracking-wide text-sand-100/80 uppercase mb-2">
                Kata Sandi
              </label>
              <input id="signup-password" name="password" type="password" required minlength="8"
                     placeholder="Minimal 8 karakter"
                     class="w-full rounded-lg border border-white/20 bg-white/5 px-4 py-3 text-sm text-sand-100 placeholder:text-sand-100/35 focus:outline-none focus:ring-2 focus:ring-gold-400/50 focus:border-gold-400/60 transition">
            </div>

            <label class="flex items-start gap-2 text-sm text-sand-100/60 select-none">
              <input type="checkbox" name="agree" required class="mt-0.5 w-4 h-4 rounded border-white/30 bg-white/5 text-gold-400 focus:ring-gold-400/40">
              <span>Saya menyetujui <a href="#" class="text-gold-400 font-medium hover:text-sand-100">Ketentuan &amp; Privasi</a></span>
            </label>

            <button type="submit"
                    class="w-full bg-gold-500 hover:bg-gold-400 text-teal-950 font-display font-semibold tracking-wide text-sm uppercase rounded-lg py-3.5 transition-all duration-300 shadow-lg shadow-black/20 hover:-translate-y-0.5">
              Buat Akun
            </button>

            <p class="text-center text-sm text-sand-100/60 pt-1">
              Sudah punya akun?
              <button type="button" data-tab-target="login" class="tab-toggle text-gold-400 font-semibold hover:text-sand-100">Masuk di sini</button>
            </p>
          </form>

        </div>
      </div>
    </div>

  </div>

<script>
  function setMode(mode) {
    // Forms + hero copy share the same data-hidden swap mechanism
    document.querySelectorAll('[data-panel]').forEach(function (panel) {
      panel.setAttribute('data-hidden', panel.getAttribute('data-panel') === mode ? 'false' : 'true');
    });
    document.querySelectorAll('[data-hero]').forEach(function (hero) {
      hero.setAttribute('data-hidden', hero.getAttribute('data-hero') === mode ? 'false' : 'true');
    });

    // Tabs inside the glass card
    document.querySelectorAll('.tab-underline').forEach(function (t) {
      var active = t.getAttribute('data-tab-target') === mode;
      t.classList.toggle('is-active', active);
      t.classList.toggle('text-sand-100', active);
      t.classList.toggle('text-sand-100/40', !active);
    });

    var url = new URL(window.location);
    url.searchParams.set('mode', mode);
    window.history.replaceState({}, '', url);
  }

  document.querySelectorAll('.tab-toggle').forEach(function (el) {
    el.addEventListener('click', function () {
      setMode(el.getAttribute('data-tab-target'));
    });
  });
</script>
</body>
</html>