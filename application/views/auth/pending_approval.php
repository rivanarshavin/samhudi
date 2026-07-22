<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Menunggu Persetujuan | Keluarga H.M Samhudi</title>
<link rel="icon" type="image/jpeg" href="<?= base_url('assets/favicon.jpeg') ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&family=Inter:wght@400;500&display=swap" rel="stylesheet">
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
      },
    },
  };
</script>
</head>
<body class="h-screen bg-teal-900 flex items-center justify-center font-body text-white px-4">
    <div class="max-w-md w-full bg-[#15201E] border border-[#374D49]/60 rounded-3xl p-8 text-center shadow-2xl">
        <div class="w-16 h-16 bg-teal-800/50 rounded-full flex items-center justify-center mx-auto mb-6 border border-teal-700/50">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-[#D4B571]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
        </div>
        
        <h2 class="font-display text-2xl font-bold mb-3 tracking-tight text-white">Verifikasi Berhasil!</h2>
        <p class="text-xs text-white/50 mb-6 uppercase tracking-wider font-semibold">Status: Menunggu Persetujuan Admin</p>
        
        <div class="h-[1px] w-12 bg-[#374D49]/60 mx-auto mb-6"></div>
        
        <p class="text-sm text-white/70 leading-relaxed mb-8">
            Akun Anda berhasil didaftarkan dan diverifikasi dengan kode OTP. <br><br> Saat ini akun Anda sedang menunggu peninjauan dan persetujuan dari **Admin Utama** sebelum dapat digunakan untuk masuk ke platform keluarga.
        </p>
        
        <a href="<?= base_url('auth') ?>" class="inline-block w-full bg-[#E49438] hover:bg-[#c87e2b] text-white font-display font-bold text-sm rounded-full py-3.5 transition-all shadow-lg active:scale-95">
            Kembali ke Log In
        </a>
    </div>
</body>
</html>
