<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
<link rel="stylesheet" href="<?php echo base_url('assets/style/admin_silsilah.css'); ?>">

<div class="admin-silsilah-wrapper">
    <div class="admin-header">
        <div class="admin-title">
            <h2>Kelola Silsilah Keluarga</h2>
            <p>Daftar anggota keluarga besar H.M Samhudi beserta relasi silsilah.</p>
        </div>
        <a href="<?php echo site_url('familytree/add'); ?>" class="btn-tambah">+ Tambah Anggota</a>
    </div>

    <div class="admin-filter-bar">
        <div style="display:flex; align-items:center; flex:1; background:transparent; border:1px solid var(--admin-border); border-radius:8px; padding-left:15px;">
            <i class="bi bi-search" style="color:var(--admin-text-muted);"></i>
            <input type="text" id="searchInput" class="filter-input" placeholder="Cari anggota keluarga..." style="border:none;">
        </div>
        <select id="genderFilter" class="filter-select">
            <option value="">Semua Jenis Kelamin</option>
            <option value="L">Laki-laki</option>
            <option value="P">Perempuan</option>
        </select>
        <select id="statusFilter" class="filter-select">
            <option value="">Semua Status Hidup</option>
            <option value="1">Hidup</option>
            <option value="0">Meninggal</option>
        </select>
    </div>

    <div class="admin-table-container">
        <table class="admin-table" id="dataTable">
            <thead>
                <tr>
                    <th>Anggota</th>
                    <th>L/P</th>
                    <th>Orang Tua</th>
                    <th>Kontak/TTL</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if(empty($members)): ?>
                <tr>
                    <td colspan="6" style="text-align:center; padding:30px; color:var(--admin-text-muted);">Belum ada data anggota keluarga.</td>
                </tr>
                <?php else: ?>
                    <?php foreach($members as $m): ?>
                    <tr class="member-row" data-name="<?php echo strtolower($m['full_name']); ?>" data-gender="<?php echo $m['gender']; ?>" data-status="<?php echo $m['is_alive']; ?>">
                        <td>
                            <div class="td-anggota">
                                <?php 
                                    $img_src = !empty($m['photo']) ? base_url('uploads/'.$m['photo']) : 'https://placehold.co/40x40/CBD9CF/4A6055?text='.strtoupper(substr($m['full_name'],0,1)); 
                                ?>
                                <img src="<?php echo $img_src; ?>" alt="Avatar" class="td-avatar">
                                <div>
                                    <p class="td-name"><?php echo htmlspecialchars($m['full_name']); ?></p>
                                    <p class="td-role"><?php echo !empty($m['occupation']) ? htmlspecialchars($m['occupation']) : '-'; ?></p>
                                </div>
                            </div>
                        </td>
                        <td><?php echo $m['gender']; ?></td>
                        <td>
                            <div class="td-parents">
                                <div>Ayah : <?php echo $m['ayah_name'] ? htmlspecialchars($m['ayah_name']) : '-'; ?></div>
                                <div>Ibu : <?php echo $m['ibu_name'] ? htmlspecialchars($m['ibu_name']) : '-'; ?></div>
                            </div>
                        </td>
                        <td>
                            <div class="td-contact">
                                <div><i class="bi bi-geo-alt-fill"></i> <?php echo htmlspecialchars($m['birth_place'] ?? '-'); ?>, <?php echo $m['birth_date'] ? date('d M Y', strtotime($m['birth_date'])) : '-'; ?></div>
                                <div><i class="bi bi-telephone-fill"></i> <?php echo htmlspecialchars($m['phone'] ?? '-'); ?></div>
                            </div>
                        </td>
                        <td>
                            <?php if($m['is_alive']): ?>
                                <span class="badge-status badge-hidup">Hidup</span>
                            <?php else: ?>
                                <span class="badge-status badge-mati">Mati</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="td-aksi">
                                <button class="btn-icon btn-edit" onclick="editMember(<?php echo $m['id']; ?>)"><i class="bi bi-pencil-square"></i></button>
                                <button class="btn-icon btn-delete" onclick="deleteMember(<?php echo $m['id']; ?>, '<?php echo addslashes($m['full_name']); ?>')"><i class="bi bi-trash"></i></button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
// Filter Logic
const searchInput = document.getElementById('searchInput');
const genderFilter = document.getElementById('genderFilter');
const statusFilter = document.getElementById('statusFilter');
const rows = document.querySelectorAll('.member-row');

function filterData() {
    const term = searchInput.value.toLowerCase();
    const gender = genderFilter.value;
    const status = statusFilter.value;

    rows.forEach(row => {
        const rowName = row.getAttribute('data-name');
        const rowGender = row.getAttribute('data-gender');
        const rowStatus = row.getAttribute('data-status');

        let matchName = rowName.includes(term);
        let matchGender = gender === "" || rowGender === gender;
        let matchStatus = status === "" || rowStatus === status;

        if (matchName && matchGender && matchStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

searchInput.addEventListener('input', filterData);
genderFilter.addEventListener('change', filterData);
statusFilter.addEventListener('change', filterData);

// Delete Logic
function deleteMember(id, name) {
    if (confirm(`Peringatan: Menghapus data "${name}" akan turut menghapus data pernikahannya, namun data anaknya akan tetap ada (hanya diputus relasi orang tuanya).\n\nApakah Anda yakin ingin menghapus data ini secara permanen?`)) {
        fetch('<?php echo site_url("admin/api_delete_member/"); ?>' + id, {
            method: 'POST'
        })
        .then(res => res.json())
        .then(data => {
            if(data.status) {
                alert(data.message);
                location.reload();
            } else {
                alert('Gagal menghapus data.');
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan jaringan.');
        });
    }
}

function editMember(id) {
    alert('Fitur edit anggota belum diimplementasikan untuk versi ini.');
}
</script>
