<?php
require_once __DIR__ . '/../../app/core/Auth.php';
require_once __DIR__ . '/../../app/core/DB.php';

if (!Auth::check()) {
    header('Location: /perpustakaan/public/login.php');
    exit;
}

$user = Auth::user();
$isAdmin = isset($user['role']) && strtolower(trim($user['role'])) === 'admin';

if (!$isAdmin) {
    http_response_code(403);
    die('AKSES DITOLAK');
}

$db = DB::connect();

/* ================================
   LOGIKA PEMBARUAN STATUS
================================ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['borrow_id'], $_POST['status'])) {
    $borrowId = (int) $_POST['borrow_id'];
    $status   = $_POST['status'];

    if (in_array($status, ['returned', 'cancelled'])) {
        $stmt = $db->prepare("SELECT DATEDIFF(CURDATE(), due_date) FROM borrows WHERE id = ?");
        $stmt->execute([$borrowId]);
        $late = (int) $stmt->fetchColumn();
        $lateDays = $late > 0 ? $late : 0;

        $update = $db->prepare("UPDATE borrows SET status = ?, return_date = CURDATE(), late_days = ? WHERE id = ?");
        $update->execute([$status, $lateDays, $borrowId]);
    }

    // Tambahkan parameter status=success pada redirect
header("Location: borrows.php?status=success");
exit;
}

$sql = "SELECT b.id, u.id AS user_id, u.nama, bk.judul AS book_title, b.borrow_date, b.due_date, b.invoice_code 
        FROM borrows b JOIN users u ON u.id = b.user_id JOIN books bk ON bk.id = b.book_id 
        WHERE b.status = 'reserved' ORDER BY b.borrow_date DESC";
$borrows = $db->query($sql)->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Peminjaman | Smart Library Enterprise</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #05070a;
            --surface: #0c0f16;
            --border: rgba(255, 255, 255, 0.08);
            --text-main: #f8fafc;
            --text-dim: #94a3b8;
            --primary: #3b82f6;
            --primary-glow: rgba(59, 130, 246, 0.3);
            --danger: #f43f5e;
            --success: #22c55e;
            --font-sans: 'Plus Jakarta Sans', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
        }

        * { box-sizing: border-box; -webkit-font-smoothing: antialiased; }

        body {
            margin: 0;
            background-color: var(--bg);
            background-image: radial-gradient(circle at 50% -20%, #1e293b 0%, var(--bg) 80%);
            color: var(--text-main);
            font-family: var(--font-sans);
            line-height: 1.5;
            min-height: 100vh;
        }

        header {
            padding: 24px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        header h1 { 
            margin: 0; 
            font-size: 1.25rem; 
            font-weight: 700;
            letter-spacing: -0.02em;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        header h1::before {
            content: '';
            width: 8px;
            height: 24px;
            background: var(--primary);
            border-radius: 4px;
            box-shadow: 0 0 15px var(--primary-glow);
        }

        header a {
            color: var(--text-dim);
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid var(--border);
            transition: all 0.2s;
        }

        header a:hover {
            background: rgba(255,255,255,0.05);
            color: var(--danger);
            border-color: rgba(244, 63, 94, 0.3);
        }

        .container { padding: 40px 48px; max-width: 1400px; margin: 0 auto; }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
        }

        .table-header {
            padding: 20px 24px;
            background: rgba(255,255,255,0.02);
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .table-header span {
            font-size: 0.75rem;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-weight: 700;
        }

        table { width: 100%; border-collapse: collapse; }

        th {
            text-align: left;
            padding: 16px 24px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-dim);
            font-weight: 600;
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 18px 24px;
            font-size: 0.85rem;
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
        }

        tbody tr { transition: background 0.2s; }
        tbody tr:hover { background: rgba(255, 255, 255, 0.03); }

        .user-info { display: flex; flex-direction: column; }
        .user-id { font-size: 0.7rem; color: var(--text-dim); font-family: var(--font-mono); }

        .timeline-box {
            font-size: 0.75rem;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        select {
            background: #161b22;
            color: #fff;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 0.75rem;
            font-family: var(--font-sans);
            cursor: pointer;
            outline: none;
        }

        button {
            background: var(--primary);
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 12px var(--primary-glow);
        }

        button:hover { transform: translateY(-1px); filter: brightness(1.1); }

        .invoice-code {
            font-family: var(--font-mono);
            font-size: 0.75rem;
            color: var(--primary);
            background: rgba(59, 130, 246, 0.1);
            padding: 4px 8px;
            border-radius: 6px;
            border: 1px solid rgba(59, 130, 246, 0.2);
        }

        .empty-state {
            padding: 80px 0;
            text-align: center;
            color: var(--text-dim);
        }

        /* Responsif Mobile */
        @media (max-width: 1024px) {
            header { padding: 20px 24px; }
            .container { padding: 24px; }
            thead { display: none; }
            tr { display: block; border-bottom: 8px solid var(--bg); padding: 20px; background: var(--surface); }
            td { display: flex; justify-content: space-between; align-items: center; border: none; padding: 10px 0; }
            td::before { content: attr(data-label); color: var(--text-dim); font-size: 0.7rem; font-weight: 600; text-transform: uppercase; }
            form { width: 100%; display: flex; gap: 8px; margin-top: 10px; }
            select { flex: 1; }
        }

        /* Custom SweetAlert2 Dark Theme */
.swal2-popup {
    background: var(--surface) !important;
    border: 1px solid var(--border) !important;
    border-radius: 24px !important;
    color: var(--text-main) !important;
    backdrop-filter: blur(10px);
}
.swal2-title {
    color: var(--text-main) !important;
    font-family: var(--font-sans) !important;
    font-size: 1.2rem !important;
}
.swal2-html-container {
    color: var(--text-dim) !important;
    font-size: 0.9rem !important;
}
.swal2-confirm {
    background: var(--primary) !important;
    box-shadow: 0 4px 12px var(--primary-glow) !important;
    border-radius: 12px !important;
    padding: 10px 24px !important;
}
.swal2-cancel {
    background: transparent !important;
    color: var(--text-dim) !important;
    border: 1px solid var(--border) !important;
    border-radius: 12px !important;
}

    </style>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>

<header>
    <h1>Data Peminjaman Aktif</h1>
    <a href="/perpustakaan/public/admin/dashboard.php">Kembali ke Dashboard</a>
</header>

<div class="container">
    <div class="card">
        <div class="table-header">
            <span>Rekaman Peminjaman Real-time</span>
            <div style="display: flex; align-items: center; gap: 8px; font-size: 0.7rem; color: var(--success); font-weight: 600;">
                <div style="width: 8px; height: 8px; background: var(--success); border-radius: 50%; box-shadow: 0 0 10px var(--success);"></div>
                SISTEM LIVE
            </div>
        </div>

        <?php if (!$borrows): ?>
            <div class="empty-state">
                <p>Tidak ada data peminjaman aktif yang ditemukan.</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Peminjam</th>
                        <th>Aset Buku</th>
                        <th>Timeline</th>
                        <th>Resolusi Status</th>
                        <th>Kode Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($borrows as $b): ?>
                    <tr>
                        <td data-label="Peminjam">
                            <div class="user-info">
                                <span style="font-weight: 600;"><?= htmlspecialchars($b['nama']) ?></span>
                                <span class="user-id">NIS: <?= $b['user_id'] ?></span>
                            </div>
                        </td>
                        <td data-label="Buku">
                            <span style="color: var(--text-dim); font-weight: 500;"><?= htmlspecialchars($b['book_title']) ?></span>
                        </td>
                        <td data-label="Timeline">
                            <div class="timeline-box">
                                <span style="color: var(--success);">Pinjam: <?= $b['borrow_date'] ?></span>
                                <span style="color: var(--danger);">Tempo: <?= $b['due_date'] ?></span>
                            </div>
                        </td>
                        <td data-label="Aksi">
                            <form method="POST">
                                <input type="hidden" name="borrow_id" value="<?= $b['id'] ?>">
                                <select name="status" required>
                                    <option value="">Pilihan</option>
                                    <option value="returned">Dikembalikan</option>
                                    <option value="cancelled">Dibatalkan</option>
                                </select>
                                <button type="submit">Proses</button>
                            </form>
                        </td>
                        <td data-label="Invoice">
                            <span class="invoice-code"><?= $b['invoice_code'] ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gunakan Event Delegation agar lebih ringan di Android
    document.addEventListener('submit', function(e) {
        // Pastikan yang disubmit adalah form di dalam tabel
        if (e.target.tagName === 'FORM' && e.target.closest('td[data-label="Aksi"]')) {
            e.preventDefault();
            const form = e.target;
            
            const selectElement = form.querySelector('select[name="status"]');
            const statusText = selectElement.options[selectElement.selectedIndex].text;
            
            // Perbaikan pengambilan nama: Android kadang bingung dengan selector yang terlalu dalam
            const row = form.closest('tr');
            // Ambil text dari elemen span pertama di kolom peminjam
            const userName = row.querySelector('td[data-label="Peminjam"] span').innerText;

            if (!selectElement.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Opsi Belum Dipilih',
                    text: 'Harap tentukan resolusi status.',
                    confirmButtonText: 'OK',
                    background: '#0c0f16',
                    color: '#f8fafc'
                });
                return;
            }

            Swal.fire({
                title: 'Konfirmasi',
                html: `Ubah status <b>${userName}</b> ke <br><span style="color:#3b82f6">${statusText}</span>?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Proses',
                cancelButtonText: 'Batal',
                background: '#0c0f16',
                color: '#f8fafc',
                reverseButtons: true,
                // Tambahkan ini agar tidak tertutup saat klik di luar (penting untuk layar kecil)
                allowOutsideClick: false 
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Memproses...',
                        allowOutsideClick: false,
                        didOpen: () => { Swal.showLoading(); }
                    });
                    form.submit();
                }
            });
        }
    });

    // Alert Sukses
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: 'Data telah diperbarui.',
            timer: 2000,
            showConfirmButton: false,
            background: '#0c0f16',
            color: '#f8fafc'
        }).then(() => {
            window.history.replaceState({}, document.title, window.location.pathname);
        });
    }
});
</script>

</body>
</html>