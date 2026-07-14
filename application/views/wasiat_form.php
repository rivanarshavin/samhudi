<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($will) ? 'Edit' : 'Tambah'; ?> Wasiat</title>
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
        padding: 20px;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .container {
        width: 100%;
        max-width: 650px;
        background: #fff;
        padding: 40px;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
    }
    .header {
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid var(--border);
    }
    h2 { margin: 0; color: var(--primary); font-size: 24px; font-weight: 700; }
    
    .form-group { margin-bottom: 20px; }
    label { display: block; margin-bottom: 8px; font-weight: 600; font-size: 14px; color: #475569; }
    input[type="text"], select, textarea { 
        width: 100%; 
        padding: 12px; 
        border: 1px solid var(--border); 
        border-radius: 6px; 
        box-sizing: border-box; 
        font-family: 'Inter', sans-serif;
        font-size: 15px;
        transition: border-color 0.2s;
    }
    input[type="text"]:focus, select:focus, textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 3px rgba(58, 77, 70, 0.1);
    }
    textarea { height: 200px; resize: vertical; line-height: 1.5; }
    
    .btn {
        display: inline-flex;
        align-items: center;
        padding: 12px 24px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        font-size: 15px;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-primary { background: var(--primary); color: white; }
    .btn-primary:hover { background: #2c3a35; transform: translateY(-1px); }
    .btn-outline { background: transparent; color: #64748b; margin-left: 10px; }
    .btn-outline:hover { color: #334155; text-decoration: underline; }
    
    .actions { margin-top: 30px; display: flex; align-items: center; }
</style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><?php echo isset($will) ? 'Edit' : 'Tambah'; ?> Surat Wasiat</h2>
        </div>
        
        <form action="<?php echo isset($will) ? site_url('wasiat/update/'.$will['id']) : site_url('wasiat/store'); ?>" method="post">
            
            <div class="form-group">
                <label>Point Wasiat</label>
                <input type="text" name="title" value="<?php echo isset($will) ? htmlspecialchars($will['title']) : ''; ?>" placeholder="Contoh: Pesan untuk keluarga..." required>
            </div>
            
            <div class="form-group">
                <label>Isi Wasiat</label>
                <textarea name="content" placeholder="Ketik isi wasiat di sini..." required><?php echo isset($will) ? htmlspecialchars($will['content']) : ''; ?></textarea>
            </div>

            <div class="actions">
                <button type="submit" class="btn btn-primary">Simpan Wasiat</button>
                <a href="<?php echo site_url('wasiat'); ?>" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
