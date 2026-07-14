<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Kelola Wasiat</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    :root {
        --primary: #3a4d46;
        --secondary: #e5b95a;
        --bg: #f8f9fa;
        --text: #333;
        --border: #e2e8f0;
    }
    body { 
        font-family: 'Inter', sans-serif; 
        background-color: var(--bg); 
        color: var(--text);
        margin: 0;
        padding: 40px 20px;
    }
    .container {
        max-width: 1000px;
        margin: 0 auto;
        background: #fff;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    }
    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 20px;
        border-bottom: 1px solid var(--border);
    }
    h2 { margin: 0; color: var(--primary); font-size: 24px; font-weight: 700; }
    .btn {
        display: inline-flex;
        align-items: center;
        padding: 10px 18px;
        border-radius: 6px;
        font-weight: 500;
        text-decoration: none;
        font-size: 14px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: #2c3a35; transform: translateY(-1px); }
    .btn-outline { background: transparent; border: 1px solid var(--border); color: var(--text); }
    .btn-outline:hover { background: #f1f5f9; }
    .btn-edit { 
        background: #f0f9ff; 
        color: #0284c7; 
        border: 1px solid #bae6fd;
        padding: 6px 12px; 
        font-size: 13px; 
    }
    .btn-edit:hover { 
        background: #e0f2fe; 
        color: #0369a1;
    }
    .btn-delete { 
        background: #fef2f2; 
        color: #dc2626; 
        border: 1px solid #fecaca;
        padding: 6px 12px; 
        font-size: 13px; 
    }
    .btn-delete:hover { 
        background: #fee2e2; 
        color: #b91c1c;
    }
    
    table { width: 100%; border-collapse: collapse; }
    th, td { padding: 15px; text-align: left; border-bottom: 1px solid var(--border); }
    th { background: #f8fafc; font-weight: 600; color: #64748b; font-size: 13px; text-transform: uppercase; letter-spacing: 0.05em; }
    td { font-size: 15px; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #f8fafc; }
    
    .badge {
        padding: 4px 10px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-public { background: #dcfce7; color: #166534; }
    .badge-private { background: #fee2e2; color: #991b1b; }
    .actions { display: flex; gap: 8px; }
</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Data Surat Wasiat</h2>
            <div style="display: flex; gap: 10px;">
                <a href="<?php echo site_url('wasiat'); ?>" class="btn btn-outline">← Halaman Depan</a>
                <a href="<?php echo site_url('wasiat/add'); ?>" class="btn btn-primary">+ Tambah Wasiat</a>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="45%">Point Wasiat</th>
                    <th width="30%">Tanggal Dibuat</th>
                    <th width="20%">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $i=1; foreach($wills as $will): ?>
                <tr>
                    <td><?php echo $i++; ?></td>
                    <td style="font-weight: 500; color: #1e293b;"><?php echo htmlspecialchars($will['title']); ?></td>
                    <td style="color: #64748b; font-size: 14px;"><?php echo date('d M Y, H:i', strtotime($will['created_at'])); ?></td>
                    <td class="actions">
                        <a href="<?php echo site_url('wasiat/edit/'.$will['id']); ?>" class="btn btn-edit">Edit</a>
                        <a href="<?php echo site_url('wasiat/delete/'.$will['id']); ?>" class="btn btn-delete" onclick="return confirm('Yakin ingin menghapus wasiat ini?');">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if(empty($wills)): ?>
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px; color: #94a3b8;">Belum ada data wasiat.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
