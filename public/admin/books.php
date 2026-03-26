<?php
require_once __DIR__ . '/../../app/core/Auth.php';
require_once __DIR__ . '/../../app/controllers/BookController.php';

if (!Auth::check() || Auth::user()['role'] !== 'admin') {
    die('Akses ditolak');
}

$controller = new BookController();
$controller->store();
$books = $controller->index();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Koleksi Buku | Smart Library Enterprise</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --bg-main: #020617;
            --bg-side: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.4);
            --border: rgba(255, 255, 255, 0.08);
            --accent-primary: #3b82f6;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --font-sans: 'Plus Jakarta Sans', sans-serif;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            background-color: var(--bg-main);
            color: var(--text-main);
            font-family: var(--font-sans);
            padding: 40px 5%;
            line-height: 1.6;
        }

        .container { max-width: 1400px; margin: auto; }

        /* --- HEADER --- */
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 40px;
        }

        .header-title h2 {
            font-size: 1.8rem;
            font-weight: 800;
            letter-spacing: -0.5px;
            background: linear-gradient(to right, #60a5fa, #3b82f6);
            background-clip: text;
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .btn-back {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            border: 1px solid var(--border);
            border-radius: 12px;
            background: var(--card-bg);
        }

        /* --- GRID LAYOUT --- */
        .main-grid {
            display: grid;
            grid-template-columns: 450px 1fr;
            gap: 32px;
            align-items: start;
        }

        .panel {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 32px;
            backdrop-filter: blur(12px);
        }

        .panel-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .panel-header h3 { font-size: 1.1rem; font-weight: 700; color: var(--text-main); }

        /* --- FORM STYLING --- */
        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 0.75rem; color: var(--text-muted); margin-bottom: 8px; text-transform: uppercase; font-weight: 600; }

        input, textarea, select {
            width: 100%;
            padding: 14px 18px;
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: white;
            font-size: 0.9rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }

        .btn-submit {
            width: 100%;
            padding: 16px;
            background: var(--accent-primary);
            color: white;
            border: none;
            border-radius: 14px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.3);
        }

        .btn-submit:hover { transform: translateY(-2px); filter: brightness(1.1); }

        /* --- SEARCH ONLINE RESULTS --- */
        #onlineResult {
            max-height: 450px;
            overflow-y: auto;
            border: 1px solid var(--border);
            border-radius: 16px;
            background: rgba(0,0,0,0.2);
            margin-top: 15px;
        }

        .online-book {
            padding: 16px;
            border-bottom: 1px solid var(--border);
            display: flex;
            gap: 15px;
            transition: 0.2s;
        }

        .online-book:hover { background: rgba(255,255,255,0.05); cursor: pointer; }

        /* --- CATALOG TABLE --- */
        .catalog-section { margin-top: 48px; }

        .table-wrap {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 24px;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        table { width: 100%; border-collapse: collapse; }
        th { background: rgba(255,255,255,0.03); padding: 18px 24px; text-align: left; font-size: 0.75rem; text-transform: uppercase; color: var(--text-muted); font-weight: 700; letter-spacing: 1px; }
        td { padding: 20px 24px; border-bottom: 1px solid var(--border); }
        tr:last-child td { border-bottom: none; }

        .rack-badge {
            font-family: 'JetBrains Mono', monospace;
            background: rgba(59, 130, 246, 0.1);
            color: var(--accent-primary);
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 0.8rem;
        }

        .btn-delete {
            color: #f87171;
            padding: 8px;
            border-radius: 10px;
            background: rgba(248, 113, 113, 0.1);
            border: 1px solid rgba(248, 113, 113, 0.2);
            cursor: pointer;
            text-decoration: none;
        }

        .btn-delete:hover { background: #f87171; color: white; }

        #coverPreview {
            width: 100%;
            height: 220px;
            object-fit: contain;
            background: rgba(0,0,0,0.3);
            border-radius: 16px;
            margin-bottom: 20px;
            border: 1px dashed var(--border);
        }

        @media (max-width: 1200px) {
            .main-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            body { padding: 20px; }
            .panel { padding: 20px; }
            .header-top { flex-direction: column; align-items: flex-start; gap: 20px; }
            table thead { display: none; }
            table tr { display: block; margin-bottom: 20px; border: 1px solid var(--border); border-radius: 16px; }
            table td { display: flex; justify-content: space-between; padding: 15px; border: none; text-align: right; }
            table td::before { content: attr(data-label); font-weight: 700; color: var(--text-muted); float: left; }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="header-top">
        <div class="header-title">
            <h2>Koleksi Buku</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Integrasi Cloud Database & API</p>
        </div>
        <a href="dashboard.php" class="btn-back">
            <i data-lucide="arrow-left" size="18"></i> Kembali ke Dashboard
        </a>
    </div>

    <div class="main-grid">
        <div class="panel">
            <div class="panel-header">
                <i data-lucide="plus-circle" class="text-accent" style="color: var(--accent-primary);"></i>
                <h3>Registrasi Buku Baru</h3>
            </div>
            
            <form method="POST" id="bookForm">
                <div id="previewContainer">
                    <img id="coverPreview" style="display:none;">
                </div>
                <input type="hidden" name="cover">
                
                <div class="form-group">
                    <label>Judul Buku</label>
                    <input type="text" name="judul" placeholder="Masukkan judul lengkap..." required>
                </div>

                <div class="form-group">
                    <label>Kategori</label>
                    <input type="text" name="kategori" placeholder="Sains, Fiksi, atau Bisnis..." required>
                </div>

                <div class="form-group">
                    <label>Alokasi Rak (Storage)</label>
                    <select name="rack_code" required>
                        <option value="">Pilih Lokasi Rak</option>
                        <option value="A">Rak A - Sains & Teknologi</option>
                        <option value="B">Rak B - Komik & Manga</option>
                        <option value="C">Rak C - Bisnis & Ekonomi</option>
                        
                    </select>
                </div>

                <div class="form-group">
                    <label>Deskripsi / Sinopsis</label>
                    <textarea name="deskripsi" placeholder="Informasi singkat isi buku..." rows="3"></textarea>
                </div>

                <button type="submit" class="btn-submit">
                    <i data-lucide="save" size="18"></i> Simpan ke Database
                </button>
            </form>
        </div>

        <div class="panel">
            <div class="panel-header">
                <i data-lucide="globe" style="color: #10b981;"></i>
                <h3>Database Global</h3>
            </div>
            <p style="color: var(--text-muted); font-size: 0.85rem; margin-bottom: 20px;">
                Gunakan fitur ini untuk mengisi metadata buku secara otomatis.
            </p>
            <div class="form-group" style="position: relative;">
                <input type="text" id="searchOnline" placeholder="Cari berdasarkan judul atau ISBN..." style="padding-left: 45px;">
                <i data-lucide="search" size="18" style="position: absolute; left: 15px; top: 15px; color: var(--text-muted);"></i>
            </div>

            <div id="onlineResult">
                <div style="padding: 60px 20px; text-align: center; color: var(--text-muted);">
                    <i data-lucide="library" size="40" style="margin-bottom: 15px; opacity: 0.2;"></i>
                    <p>Hasil pencarian akan tampil di sini secara real-time</p>
                </div>
            </div>
        </div>
    </div>

    <div class="catalog-section">
        <div class="panel-header">
            <i data-lucide="database" style="color: var(--accent-primary);"></i>
            <h3>Katalog Database Saat Ini</h3>
        </div>
        
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Metadata Buku</th>
                        <th>Kategori</th>
                        <th>Status Lokasi</th>
                        <th style="text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $b): ?>
                    <tr>
                        <td data-label="Buku">
                            <div style="font-weight: 700; color: var(--text-main);"><?= htmlspecialchars($b['judul']) ?></div>
                        </td>
                        <td data-label="Kategori">
                            <span style="font-size: 0.8rem; background: rgba(255,255,255,0.05); padding: 5px 12px; border-radius: 100px;"><?= htmlspecialchars($b['kategori']) ?></span>
                        </td>
                        <td data-label="Lokasi">
                            <span class="rack-badge">TRACK: UNIT-<?= htmlspecialchars($b['rack_code']) ?></span>
                        </td>
                        <td data-label="Kontrol" style="text-align: right;">
                            <a href="delete.php?id=<?= $b['id'] ?>" class="btn-delete" onclick="return confirm('Hapus record ini secara permanen?')">
                                <i data-lucide="trash-2" size="16"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <?php if(empty($books)): ?>
                <div style="padding: 50px; text-align: center; color: var(--text-muted);">
                    Tidak ada data buku yang ditemukan.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
lucide.createIcons();

const input = document.getElementById('searchOnline');
const resultBox = document.getElementById('onlineResult');

let timer = null;

/* ===============================
   SEARCH ONLINE (OPEN LIBRARY)
================================ */
input.addEventListener('input', function () {
    clearTimeout(timer);
    const q = this.value.trim();

    if (q.length < 3) {
        resultBox.innerHTML = '';
        return;
    }

    timer = setTimeout(() => {
        fetch(`/perpustakaan/api/searchBooksOnline.php?q=${encodeURIComponent(q)}`)
            .then(res => res.json())
            .then(data => {
                if (!data.length) {
                    resultBox.innerHTML = `
                        <div style="color:#94a3b8;font-size:.85rem">
                            Tidak ditemukan
                        </div>`;
                    return;
                }

                resultBox.innerHTML = data.map((b, i) => `
                    <div class="book-item" data-index="${i}">
                        ${b.cover
                            ? `<img src="${b.cover}">`
                            : `<div class="no-cover">No Cover</div>`
                        }
                        <div>
                            <strong>${b.title}</strong><br>
                            <small>${b.author} • ${b.year}</small>
                        </div>
                    </div>
                `).join('');

                document.querySelectorAll('.book-item').forEach((el, i) => {
                    el.onclick = () => selectOnlineBook(data[i]);
                });
            })
            .catch(() => {
                resultBox.innerHTML = `
                    <div style="color:#ef4444;font-size:.85rem">
                        Gagal mengambil data buku
                    </div>`;
            });
    }, 400);
});

/* ===============================
   PILIH BUKU ONLINE
================================ */
function selectOnlineBook(book) {
    document.querySelector('[name="judul"]').value = book.title || '';
    document.querySelector('[name="kategori"]').value =
        book.category || '';

    document.querySelector('[name="deskripsi"]').value =
        book.description || '';

    document.querySelector('[name="cover"]').value = book.cover || '';

    const preview = document.getElementById('coverPreview');
    if (book.cover) {
        preview.src = book.cover;
        preview.style.display = 'block';
        preview.style.borderStyle = 'solid';
    }

    resultBox.innerHTML = '';

    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Metadata buku berhasil dimuat',
        showConfirmButton: false,
        timer: 2000,
        background: '#1e293b',
        color: '#fff'
    });
}


/* ===============================
   SUBMIT FORM
================================ */
document.getElementById('bookForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);

    fetch('/perpustakaan/api/insertBook.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: data.message,
                background: '#0f172a',
                color: '#fff',
                confirmButtonColor: '#3b82f6'
            }).then(() => location.reload());
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: data.message,
                background: '#0f172a',
                color: '#fff'
            });
        }
    })
    .catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Terjadi kesalahan sistem',
            background: '#0f172a',
            color: '#fff'
        });
    });
});
</script>


</body>
</html>