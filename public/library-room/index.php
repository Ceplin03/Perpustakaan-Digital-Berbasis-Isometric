<?php
require_once '../../app/core/Auth.php';

if (!Auth::check()) {
    header("Location: /perpustakaan/public/login.php");
    exit;
}

$user = Auth::user();
$isAdmin = ($user['role'] === 'admin');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Smart Library | Experience</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Space+Mono&display=swap" rel="stylesheet">

   <style>
    :root {
        --bg: #000000;
        --accent: #ffffff;
        --text-muted: #888888;
        --primary: #3b82f6; 
        --font-mono: 'Space Mono', monospace;
        --font-sans: 'Inter', sans-serif;
        --transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
    }

    /* --- BASE LAYOUT (TIDAK DIUBAH) --- */
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
        background: var(--bg);
        color: var(--accent);
        font-family: var(--font-sans);
        height: 100vh;
        overflow: hidden;
        -webkit-font-smoothing: antialiased;
    }
    header {
        position: fixed; top: 0; width: 100%; padding: 30px 40px;
        display: flex; justify-content: space-between; align-items: flex-start;
        z-index: 100; pointer-events: none;
    }
    header .logo, header .user-meta { pointer-events: auto; font-family: var(--font-mono); }
    header .logo { font-weight: 700; letter-spacing: 2px; font-size: 0.9rem; }
    header .user-meta { text-align: right; font-size: 0.7rem; text-transform: uppercase; }
    header a { color: #ff3e3e; text-decoration: none; display: inline-block; margin-top: 8px; padding: 2px 8px; border: 1px solid rgba(255, 62, 62, 0.3); border-radius: 4px; }
    /* ==========================================================
   SCENE SYSTEM (ABSOLUTE WEB MODE + HORIZONTAL SCROLL)
   ========================================================== */

/* VIEWPORT = WINDOW YANG BISA SCROLL */
.scene-viewport {
    width: 100vw;
    height: 100vh;
    overflow: hidden;
    position: relative;
}

/* CANVAS UTAMA (FIX 16:9, TIDAK PERNAH DI-SCALE) */
.scene-wrapper {
    position: relative;
    width: 100vw;
    height: 100vh;
    min-width: calc(100vh * 16 / 9); /* paksa rasio 16:9 */
    min-height: 100vh;
    background: #050505;
}

/* IMAGE TETAP ASLI */
.scene-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    filter: brightness(.6) contrast(1.1);
    transition: transform .8s ease-out;
    pointer-events: none;
}

/* ==========================================================
   HOTSPOT (ABSOLUTE – AMAN DI SEMUA RESOLUSI)
   ========================================================== */
.hotspot {
    position: absolute;
    width: 12px;
    height: 12px;
    background: #ffffff;
    border-radius: 50%;
    cursor: pointer;
    z-index: 20;
}

.hotspot::before {
    content: '';
    position: absolute;
    inset: -10px;
    border: 1px solid rgba(255,255,255,.4);
    border-radius: 50%;
    animation: pulse 2.5s infinite;
}

.hotspot::after {
    content: attr(data-label);
    position: absolute;
    top: 26px;
    left: 50%;
    transform: translateX(-50%);
    font-family: var(--font-mono);
    font-size: 0.6rem;
    white-space: nowrap;
    background: rgba(0,0,0,.65);
    padding: 4px 8px;
    border-radius: 6px;
    pointer-events: none;
}

@keyframes pulse {
    0% { transform: scale(1); opacity: 1 }
    100% { transform: scale(3); opacity: 0 }
}

    /* ==========================================================
       MODAL & RESPONSIVE MODAL SYSTEM (UPDATED)
       ========================================================== */

    /* --- MODAL OVERLAY BASE --- */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(15px);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 1000;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.4s ease;
    }

    .modal-overlay.active {
        opacity: 1;
        pointer-events: auto;
    }

    /* --- MODAL CONTENT BOX --- */
    .modal-content {
        width: 90%;
        max-width: 1000px;
        height: 80vh;
        background: #1a1a1a;
        border: 1px solid #333;
        padding: 40px;
        position: relative;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transform: scale(0.95);
        transition: var(--transition);
        border-radius: 12px;
    }

    .modal-overlay.active .modal-content {
        transform: scale(1);
    }

    .close-trigger {
        position: absolute;
        top: 25px;
        right: 25px;
        background: #222;
        border: none;
        color: #fff;
        padding: 8px 15px;
        font-family: var(--font-mono);
        font-size: 0.7rem;
        cursor: pointer;
        z-index: 100;
        border-radius: 4px;
    }

    h3#modalTitle {
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 25px;
        text-transform: uppercase;
        letter-spacing: -1px;
    }

    #modalContent {
        flex: 1;
        overflow-y: auto;
        padding-right: 10px;
    }

    /* --- BOOK GRID & CARDS --- */
    .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 25px;
        padding-bottom: 20px;
    }

    .book-card { cursor: pointer; transition: var(--transition); }
    .book-card:hover { transform: translateY(-8px); }
    .book-cover-wrapper {
        aspect-ratio: 2/3; background: #111; border-radius: 8px;
        overflow: hidden; margin-bottom: 12px; border: 1px solid #333;
    }
    .book-cover-wrapper img { width: 100%; height: 100%; object-fit: cover; }
    .book-info h4 { font-size: 0.85rem; margin-bottom: 4px; }
    .book-info p { font-size: 0.7rem; color: var(--text-muted); font-family: var(--font-mono); }

    /* --- DETAIL VIEW (RESPONSIVE GRID) --- */
    .detail-layout {
        display: grid;
        grid-template-columns: 280px 1fr;
        gap: 40px;
    }

    .detail-cover {
        width: 100%;
        border-radius: 8px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.5);
    }

    /* --- FORMS & INPUTS --- */
    .form-group { margin-bottom: 18px; }
    .form-group label {
        display: block; font-size: 0.7rem; margin-bottom: 6px;
        color: #aaa; font-family: var(--font-mono);
    }
    .form-group input, .form-group select {
        width: 100%; padding: 12px; background: #0a0a0a;
        border: 1px solid #333; color: #fff; border-radius: 6px;
    }

    .btn-request {
        background: #fff; color: #000; border: none; padding: 15px 30px;
        font-weight: 700; width: 100%; margin-top: 10px;
        cursor: pointer; font-family: var(--font-mono); font-size: 0.8rem;
    }

    /* --- INVOICE & PRINT MODES --- */
    .modal-overlay.invoice-mode { background: rgba(0, 0, 0, 0.95) !important; }
    .modal-overlay.invoice-mode .modal-content {
        background: transparent !important; border: none !important;
        box-shadow: none !important; padding: 0 !important;
        height: auto !important; max-width: 500px !important; display: block !important;
    }
    .modal-overlay.invoice-mode #modalTitle, .modal-overlay.invoice-mode .close-trigger { display: none !important; }

    @media print {
        body * { visibility: hidden; }
        .modal-overlay.invoice-mode, .modal-overlay.invoice-mode * { visibility: visible; }
        .modal-overlay.invoice-mode { position: absolute; inset: 0; background: #fff; }
    }

    /* --- MOBILE RESPONSIVE OPTIMIZATION --- */
    @media (max-width: 768px) {
        header { padding: 16px 20px; }
        /* MOBILE: viewport bisa scroll */
    .scene-viewport {
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
    }

    /* canvas tetap lebar, tidak mengecil */
    .scene-wrapper {
        width: calc(100vh * 16 / 9);
        height: 100vh;
    }


        /* Modal Fullscreen di Mobile */
        .modal-overlay { align-items: flex-end; }
        .modal-content {
            width: 100%;
            height: 92vh;
            max-width: none;
            border-radius: 20px 20px 0 0;
            padding: 60px 20px 20px;
            transform: translateY(100px);
        }
        .modal-overlay.active .modal-content { transform: translateY(0); }

        /* Detail Buku Mobile */
        .detail-layout {
            grid-template-columns: 1fr;
            gap: 20px;
            text-align: center;
        }
        .detail-cover { max-width: 180px; margin: 0 auto; }
        h3#modalTitle { font-size: 1.3rem; margin-bottom: 15px; }
        
        /* Form Mobile */
        #borrowModal .modal-content { height: auto; padding-bottom: 40px; }
    }

    /* Scrollbar Styling */
    #modalContent::-webkit-scrollbar { width: 4px; }
    #modalContent::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
</style>

<style>
    /* --- PERBAIKAN GRID BUKU (DEFAULT & DESKTOP) --- */
    .book-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 20px;
        padding: 10px 0;
    }

    .book-card {
        cursor: pointer;
        transition: var(--transition);
        display: flex;
        flex-direction: column;
    }

    .book-cover-wrapper {
        aspect-ratio: 3/4; /* Rasio cover buku yang lebih standar profesional */
        background: #111;
        border-radius: 8px;
        overflow: hidden;
        margin-bottom: 10px;
        border: 1px solid rgba(255,255,255,0.1);
        position: relative;
    }

    .book-cover-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    /* --- RESPONSIVE MOBILE (ELEGAN & PROFESSIONAL) --- */
    @media (max-width: 768px) {
        /* Modal tidak fullscreen total, beri sedikit celah di atas agar elegan */
        .modal-content {
            width: 100%;
            height: 85vh; 
            max-height: 85vh;
            border-radius: 24px 24px 0 0; /* Lengkungan lebih halus */
            padding: 20px;
            padding-top: 60px; /* Ruang untuk tombol close */
        }

        /* Tampilkan 2 kolom di mobile agar tidak terlalu besar */
        .book-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        /* Penyesuaian teks agar rapi di layar kecil */
        h3#modalTitle {
            font-size: 1.1rem;
            letter-spacing: 1px;
            margin-bottom: 20px;
            text-align: center;
        }

        .book-info h4 {
            font-size: 0.8rem;
            line-height: 1.2;
            margin-bottom: 4px;
        }

        .book-info p {
            font-size: 0.6rem;
            opacity: 0.7;
        }

        /* Tombol Close yang lebih minimalis */
        .close-trigger {
            top: 15px;
            right: 15px;
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 6px 12px;
            font-size: 0.6rem;
        }
    }
</style>

</head>
<body>
<!-- email admin
 sepriiratas430@gmail.com
 cpln030901 -->
<header>
    <div class="logo">SMART / LIBRARY™</div>
    <div class="user-meta">
        HELLO, <?= htmlspecialchars($user['nama']) ?><br>
        SYSTEM STATUS: ONLINE
        <a href="/perpustakaan/public/logout.php">LOGOUT</a>
    </div>
</header>

<!-- ================= VIEWPORT WRAPPER (NEW) ================= -->
<main class="scene-viewport">
<div class="scene-wrapper" id="scene">

<img src="/perpustakaan/public/assets/isometric/library-room.png" id="main-view">

<div class="hotspot" style="top:35%;left:45%" data-type="rack" data-rack="A" data-label="RAK SAINS & TEKNOLOGI"></div>
<div class="hotspot" style="top:25%;left:65%" data-type="rack" data-rack="B" data-label="RAK KOMIK & MANGA"></div>
<div class="hotspot" style="top:16%;left:33%" data-type="rack" data-rack="C" data-label="RAK BISNIS"></div>
<div class="hotspot" style="top:38%;left:26%" data-type="rack" data-rack="Top-Readers" data-label="PEMBACA TERBANYAK"></div>
<div class="hotspot" style="top:70%;left:42%" data-type="rack" data-rack="REKOMENDASI" data-label="BUKU PILIHAN"></div>

<?php if($isAdmin): ?>
<div class="hotspot" style="top:56%;left:73.5%" data-type="admin" data-label="Admin Dashboard"></div>
<?php endif; ?>

<a href="/perpustakaan/public/logout.php" 
   class="hotspot" 
   style="top:18%;left:84%; color: #ffffff !important;" 
   data-label="LOGOUT">
</a>


</div>

</main>

<div class="modal-overlay" id="modalOverlay">
    <div class="modal-content">
        <button class="close-trigger" onclick="closeModal()">CLOSE (ESC)</button>
        <h3 id="modalTitle">Loading</h3>
        <div id="modalContent"></div>
    </div>
</div>

<!-- MODAL KONFIRMASI PEMINJAMAN -->
<div class="modal-overlay" id="borrowModal">
    <div class="modal-content" style="max-width:520px; height: auto; min-height: unset;">
        <button class="close-trigger" onclick="closeBorrowModal()">CLOSE</button>

        <h3 style="font-size: 1.2rem; border-bottom: 1px solid #333; padding-bottom: 15px; margin-bottom: 20px;">
            KONFIRMASI PEMINJAMAN
        </h3>

        <form id="borrowForm">
            <input type="hidden" name="book_id" id="borrowBookId">
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Nama Siswa</label>
                    <input type="text" value="<?= htmlspecialchars($user['nama']) ?>" disabled>
                </div>
                <div class="form-group">
                    <label>ID / NIS</label>
                    <input type="text" value="<?= htmlspecialchars($user['id']) ?>" disabled>
                </div>
            </div>

            <div class="form-group">
                <label>Judul Buku</label>
                <input type="text" id="borrowBookTitle" disabled style="background: #1a1a1a; color: #3b82f6; font-weight: bold;">
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Lama Pinjam</label>
                    <select name="days" id="borrowDays">
                        <?php for($i=1;$i<=7;$i++): ?>
                            <option value="<?= $i ?>"><?= $i ?> Hari</option>
                        <?php endfor; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Tanggal Kembali</label>
                    <input type="text" id="returnDate" disabled>
                </div>
            </div>

            <button type="submit" class="btn-request" style="margin-top: 10px;">
                KONFIRMASI & BUAT INVOICE
            </button>
        </form>
    </div>
</div>

<script>
(() => {
    // Definisi Elemen (Penting: agar tidak error 'undefined')
    const scene = document.getElementById('scene');
    const img = document.getElementById('main-view');
    const overlay = document.getElementById('modalOverlay');
    const modalTitle = document.getElementById('modalTitle');
    const modalContent = document.getElementById('modalContent');
    const borrowDays = document.getElementById('borrowDays');
    const returnDateInput = document.getElementById('returnDate');

    // PARALLAX DESKTOP
  if(window.innerWidth>768){
    scene.addEventListener('mousemove',e=>{
      const x=(e.clientX/window.innerWidth-.5)*10;
      const y=(e.clientY/window.innerHeight-.5)*10;
      img.style.transform=`scale(1.05) translate(${-x}px,${-y}px)`;
    });
  }

  // AUTO CENTER MOBILE
  if(window.innerWidth<=768){
    setTimeout(()=>{
      viewport.scrollLeft=(viewport.scrollWidth-viewport.clientWidth)/2;
    },300);
  }

    // --- MODAL FUNCTIONS ---
    function openModal(title, html) {
        modalTitle.innerText = title;
        modalContent.innerHTML = html;
        overlay.classList.add('active');
    }

    function closeModal() {
        overlay.classList.remove('active');
    }
    

    // --- BORROW LOGIC ---
    function updateReturnDate() {
        const days = parseInt(borrowDays.value) || 0;
        const date = new Date();
        date.setDate(date.getDate() + days);

        // Format YYYY-MM-DD secara lokal agar tidak meleset zona waktu
        const year = date.getFullYear();
        const month = String(date.getMonth() + 1).padStart(2, '0');
        const day = String(date.getDate()).padStart(2, '0');
        
        returnDateInput.value = `${year}-${month}-${day}`;
    }

    function borrowBook(id, title) {
        document.getElementById('borrowBookId').value = id;
        document.getElementById('borrowBookTitle').value = title;
        updateReturnDate(); // Langsung hitung tanggal saat modal buka
        document.getElementById('borrowModal').classList.add('active');
    }

    function closeBorrowModal() {
        document.getElementById('borrowModal').classList.remove('active');
    }

    // --- EXPOSE TO GLOBAL (Agar bisa dipanggil dari HTML/Script lain) ---
    window.openModal = openModal;
    window.closeModal = closeModal;
    window.borrowBook = borrowBook;
    window.closeBorrowModal = closeBorrowModal;
    window.openBookDetail = function(id) {
        fetch(`/perpustakaan/api/getBookDetail.php?id=${id}`)
            .then(res => res.text())
            .then(html => openModal('Informasi Buku', html));
    };

    // --- EVENT LISTENERS ---
    overlay.addEventListener('click', e => {
        if (e.target === overlay) closeModal();
    });

    if (borrowDays) {
        borrowDays.addEventListener('change', updateReturnDate);
    }

    document.querySelectorAll('.hotspot').forEach(h=>{
    h.onclick=()=>{
      if(h.dataset.type==='admin'){
        location.href='/perpustakaan/public/admin/dashboard.php';
        return;
      }
      fetch(`/perpustakaan/api/getBooksByRack.php?rack=${h.dataset.rack}`)
        .then(r=>r.text())
        .then(html=>{
          document.getElementById('modalTitle').innerText=h.dataset.label;
          document.getElementById('modalContent').innerHTML=html;
          document.getElementById('modalOverlay').classList.add('active');
        });
    };
  });
})();
</script>

<script>
    // Pastikan menggunakan ID form yang benar
    const borrowForm = document.getElementById('borrowForm');
    
    if (borrowForm) {
        borrowForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('/perpustakaan/api/createInvoice.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    // Ambil tampilan invoice
                    fetch('/perpustakaan/api/renderInvoice.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify(data)
                    })
                    .then(r => r.text())
                    // Di dalam event listener borrowForm submit
.then(html => {
    closeAllModals(); // Bersihkan semua modal yang terbuka sebelumnya

    setTimeout(() => {
        const overlay = document.getElementById('modalOverlay');
        const content = document.getElementById('modalContent');
        
        // Aktifkan mode invoice khusus
        overlay.classList.add('active', 'invoice-mode');
        content.innerHTML = html;
    }, 100);
});
function closeModal() {
    const overlay = document.getElementById('modalOverlay');
    overlay.classList.remove('active', 'invoice-mode'); // Hapus class invoice-mode
}
                } else {
                    // Menggunakan SweetAlert2 jika ada, jika tidak gunakan alert biasa
                    if (window.Swal) {
                        Swal.fire('Gagal', data.message, 'error');
                    } else {
                        alert('Gagal: ' + data.message);
                    }
                }
            })
            .catch(err => {
                console.error('Error:', err);
                alert('Terjadi kesalahan sistem.');
            });
        });
    }
</script>
<script>
function closeAllModals() {
    document.querySelectorAll('.modal-overlay').forEach(m => {
        m.classList.remove('active', 'invoice-mode');
    });
}
</script>


</body>
</html>