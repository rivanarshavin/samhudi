<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
$is_rundayan = ($page_type === 'rundayan');
$theme_primary = $is_rundayan ? 'cyan' : 'amber';
$theme_dark_text = $is_rundayan ? 'text-slate-950' : 'text-teal-950';

// Map roles from the database records
$role_candidates = [
    'Ketua' => '-',
    'Bendahara' => '-',
    'Sekretaris' => '-'
];
foreach ($candidates as $c) {
    if (!empty($c['description'])) {
        $role_candidates[$c['description']] = $c['candidate_name'];
    }
}
?>

<main class="min-h-screen bg-gradient-to-b from-[#274d4f] via-[#1a3638] to-[#0f2122] text-white pt-32 sm:pt-36 pb-16 px-4 sm:px-6 lg:px-8 flex items-center justify-center">
    <div class="max-w-md w-full animate-fade-in">
        
        <!-- Bukti Card -->
        <div class="bg-gradient-to-b from-[#1b3638] to-[#122829] border border-white/15 rounded-3xl p-6 sm:p-8 shadow-2xl text-center relative overflow-hidden backdrop-blur-md">
            
            <!-- Success Icon Glow -->
            <div class="w-16 h-16 bg-emerald-500/20 border border-emerald-500/30 rounded-full flex items-center justify-center mx-auto mb-6 shadow-[0_0_20px_rgba(16,185,129,0.2)]">
                <i class="bi bi-check-lg text-3xl text-emerald-400"></i>
            </div>

            <!-- Title & Messages -->
            <h2 class="text-xl sm:text-2xl font-display font-bold text-white leading-tight mb-2">
                Terima Kasih!
            </h2>
            <p class="text-sm text-emerald-100/75 leading-relaxed mb-4">
                Terima kasih sudah berpartisipasi di Keluarga Besar H.M. Samhudi
            </p>
            <p class="text-xs text-white/55 mb-6">
                Data yang anda masukkan dapat dilihat di QR Code sebagai berikut:
            </p>

            <!-- QR Code Box -->
            <div class="bg-white p-4 rounded-2xl inline-block shadow-xl mb-6 relative min-w-[180px] min-h-[180px] flex items-center justify-center mx-auto">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=180x180&data=<?= urlencode($receipt_url) ?>" 
                     alt="QR Code Bukti" class="w-40 h-40">
            </div>

            <!-- Receipt Details Table -->
            <div class="text-left bg-black/20 border border-white/5 rounded-2xl p-4 sm:p-5 space-y-3.5 mb-6 text-xs sm:text-sm">
                <div class="border-b border-white/5 pb-2">
                    <span class="text-white/40 block text-[10px] uppercase font-semibold tracking-wider">Nama Pengusul</span>
                    <strong class="text-white text-sm"><?= htmlspecialchars($nominator) ?></strong>
                </div>
                <div class="border-b border-white/5 pb-2">
                    <span class="text-white/40 block text-[10px] uppercase font-semibold tracking-wider">Rundayan (Keturunan)</span>
                    <strong class="text-white text-sm"><?= htmlspecialchars($ancestor) ?></strong>
                </div>
                <div class="border-b border-white/5 pb-2">
                    <span class="text-white/40 block text-[10px] uppercase font-semibold tracking-wider">Calon Ketua (Formatur 1)</span>
                    <strong class="text-[#E3E3E3] text-sm"><?= htmlspecialchars($role_candidates['Ketua']) ?></strong>
                </div>
                <div class="border-b border-white/5 pb-2">
                    <span class="text-white/40 block text-[10px] uppercase font-semibold tracking-wider">Calon Bendahara (Formatur 2)</span>
                    <strong class="text-[#E3E3E3] text-sm"><?= htmlspecialchars($role_candidates['Bendahara']) ?></strong>
                </div>
                <div class="pb-1">
                    <span class="text-white/40 block text-[10px] uppercase font-semibold tracking-wider">Calon Sekretaris (Formatur 3)</span>
                    <strong class="text-[#E3E3E3] text-sm"><?= htmlspecialchars($role_candidates['Sekretaris']) ?></strong>
                </div>
            </div>

            <!-- Footer Action Buttons -->
            <div class="flex gap-3">
                <button onclick="downloadQR();" 
                        class="flex-1 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-xs font-bold text-white transition-all flex items-center justify-center gap-1.5 shadow-sm">
                    <i class="bi bi-download"></i> Unduh QR Code
                </button>
                <a href="<?= base_url($is_rundayan ? 'rundayan' : 'anggota') ?>" 
                   class="flex-1 py-2.5 bg-<?= $theme_primary ?>-500 hover:bg-<?= $theme_primary ?>-600 <?= $theme_dark_text ?> rounded-xl text-xs font-bold transition-all flex items-center justify-center gap-1.5 shadow-md">
                    Kembali <i class="bi bi-arrow-right"></i>
                </a>
            </div>

        </div>
    </div>
</main>

<script>
function downloadQR() {
    const qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=<?= urlencode($receipt_url) ?>";
    const button = document.querySelector('button[onclick="downloadQR();"]');
    const originalText = button ? button.innerHTML : '';
    
    if (button) {
        button.innerHTML = '<i class="bi bi-arrow-repeat animate-spin"></i> Mengunduh...';
        button.disabled = true;
    }
    
    fetch(qrUrl)
        .then(response => response.blob())
        .then(blob => {
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.style.display = 'none';
            a.href = url;
            a.download = 'QR_Bukti_Pencalonan_<?= str_replace([' ', "'", '"'], '_', urlencode($nominator)) ?>.png';
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            
            if (button) {
                button.innerHTML = originalText;
                button.disabled = false;
            }
        })
        .catch(() => {
            alert('Gagal mengunduh QR Code otomatis. Silakan klik kanan atau tekan lama pada gambar QR Code di atas, lalu pilih "Simpan Gambar".');
            if (button) {
                button.innerHTML = originalText;
                button.disabled = false;
            }
        });
}

// Automatically trigger download on page load
document.addEventListener('DOMContentLoaded', () => {
    setTimeout(downloadQR, 600);
});
</script>
