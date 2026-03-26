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

/**
 * Ambil data invoices (returned & cancelled)
 */
$sql = "
SELECT 
    b.id,
    u.id AS user_id,
    u.nama,
    bk.judul AS book_title,
    b.borrow_date,
    b.return_date,
    b.status,
    b.late_days,
    b.invoice_code
FROM borrows b
JOIN users u ON u.id = b.user_id
JOIN books bk ON bk.id = b.book_id
WHERE b.status IN ('returned','cancelled')
ORDER BY b.return_date DESC
";

$data = $db->query($sql)->fetchAll();

/* Pisahkan data */
$returned  = [];
$cancelled = [];

foreach ($data as $row) {
    if ($row['status'] === 'returned') {
        $returned[] = $row;
    } else {
        $cancelled[] = $row;
    }
}

/* Helper format rupiah */
function rupiah($n) {
    return 'Rp ' . number_format($n, 0, ',', '.');
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoices & Riwayat | Smart Library Enterprise</title>
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
            --success: #22c55e;
            --danger: #f43f5e;
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
            min-height: 100vh;
        }

        header {
            padding: 20px 48px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(12px);
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(5, 7, 10, 0.8);
        }

        header h1 { 
            margin: 0; 
            font-size: 1.1rem; 
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        header h1::before {
            content: '';
            width: 4px;
            height: 20px;
            background: var(--primary);
            border-radius: 4px;
            box-shadow: 0 0 15px var(--primary-glow);
        }

        header a {
            color: var(--text-dim);
            text-decoration: none;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 8px 16px;
            border-radius: 8px;
            border: 1px solid var(--border);
            transition: all 0.2s;
        }

        header a:hover {
            background: rgba(255,255,255,0.05);
            color: var(--text-main);
            border-color: var(--text-dim);
        }

        .container { padding: 30px 48px; max-width: 1400px; margin: 0 auto; }

        .section-title {
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--text-dim);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            margin-bottom: 40px;
        }

        table { width: 100%; border-collapse: collapse; }

        th {
            text-align: left;
            padding: 16px 24px;
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-dim);
            background: rgba(255, 255, 255, 0.02);
            border-bottom: 1px solid var(--border);
        }

        td {
            padding: 18px 24px;
            font-size: 0.85rem;
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
        }

        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover { background: rgba(255, 255, 255, 0.03); }

        .badge-status {
            padding: 4px 12px;
            border-radius: 6px;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .returned-badge { background: rgba(34, 197, 94, 0.1); color: var(--success); border: 1px solid rgba(34, 197, 94, 0.2); }
        .cancelled-badge { background: rgba(244, 63, 94, 0.1); color: var(--danger); border: 1px solid rgba(244, 63, 94, 0.2); }

        .invoice-box {
            font-family: var(--font-mono);
            font-size: 0.7rem;
            color: var(--primary);
            background: rgba(59, 130, 246, 0.05);
            padding: 4px 10px;
            border-radius: 6px;
            border: 1px solid rgba(59, 130, 246, 0.2);
            display: inline-block;
        }

        .user-id { font-family: var(--font-mono); font-size: 0.75rem; color: var(--text-dim); }
        .empty { padding: 60px; text-align: center; color: var(--text-dim); font-size: 0.85rem; }

        /* RESPONSIVE STRATEGY: INOVATIF CARD LAYOUT */
        @media (max-width: 1024px) {
            header { padding: 15px 24px; }
            .container { padding: 20px 24px; }
            
            thead { display: none; } /* Sembunyikan header tabel */
            
            table, tbody, tr, td { display: block; width: 100%; }
            
            tr {
                margin-bottom: 20px;
                padding: 15px;
                border-radius: 16px;
                background: rgba(255, 255, 255, 0.01);
                border: 1px solid var(--border);
            }

            td {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 10px 0 !important;
                border-bottom: 1px solid rgba(255,255,255,0.03) !important;
                text-align: right;
            }

            td:last-child { border-bottom: none !important; }

            /* Label otomatis dari data-label */
            td::before {
                content: attr(data-label);
                float: left;
                font-size: 0.7rem;
                font-weight: 700;
                color: var(--text-dim);
                text-transform: uppercase;
            }

            /* Highlight Nama & Judul di Mobile */
            td[data-label="Nama Peminjam"], td[data-label="Judul Buku"] {
                flex-direction: column;
                align-items: flex-start;
                text-align: left;
            }
            td[data-label="Nama Peminjam"]::before, td[data-label="Judul Buku"]::before {
                margin-bottom: 4px;
            }
            
            .invoice-box { font-size: 0.8rem; }
        }
    </style>
</head>
<body>

<header>
    <h1>Invoices & Riwayat</h1>
    <a href="/perpustakaan/public/admin/dashboard.php">Dashboard</a>
</header>

<div class="container">

    <div class="section-title">
        <span>✅</span> Pengembalian Selesai
    </div>
    <div class="card">
        <?php if (!$returned): ?>
            <div class="empty">Belum ada riwayat pengembalian.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>Nama Peminjam</th>
                        <th>Judul Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Denda</th>
                        <th>Status</th>
                        <th>Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($returned as $r): $denda = $r['late_days'] * 1000; ?>
                    <tr>
                        <td data-label="NIS" class="user-id"><?= $r['user_id'] ?></td>
                        <td data-label="Nama Peminjam">
                            <span style="font-weight: 600; color: var(--primary);"><?= htmlspecialchars($r['nama']) ?></span>
                        </td>
                        <td data-label="Judul Buku">
                            <span style="color: var(--text-main); font-weight: 500;"><?= htmlspecialchars($r['book_title']) ?></span>
                        </td>
                        <td data-label="Tgl Pinjam"><?= $r['borrow_date'] ?></td>
                        <td data-label="Tgl Kembali" style="color: var(--success); font-weight: 600;"><?= $r['return_date'] ?></td>
                        <td data-label="Denda">
                            <div style="line-height: 1.2;">
                                <div style="color: var(--danger); font-weight: 700;"><?= rupiah($denda) ?></div>
                                <div style="color: var(--text-dim); font-size: 0.65rem;"><?= $r['late_days'] ?> hari terlambat</div>
                            </div>
                        </td>
                        <td data-label="Status"><span class="badge-status returned-badge">Returned</span></td>
                        <td data-label="Invoice"><span class="invoice-box"><?= $r['invoice_code'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="section-title">
        <span>❌</span> Peminjaman Dibatalkan
    </div>
    <div class="card">
        <?php if (!$cancelled): ?>
            <div class="empty">Tidak ada data pembatalan.</div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>NIS</th>
                        <th>Nama Peminjam</th>
                        <th>Judul Buku</th>
                        <th>Tgl Pengajuan</th>
                        <th>Tgl Batal</th>
                        <th>Status</th>
                        <th>Invoice</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cancelled as $c): ?>
                    <tr>
                        <td data-label="NIS" class="user-id"><?= $c['user_id'] ?></td>
                        <td data-label="Nama Peminjam">
                            <span style="font-weight: 600;"><?= htmlspecialchars($c['nama']) ?></span>
                        </td>
                        <td data-label="Judul Buku">
                            <span style="color: var(--text-dim);"><?= htmlspecialchars($c['book_title']) ?></span>
                        </td>
                        <td data-label="Tgl Pengajuan"><?= $c['borrow_date'] ?></td>
                        <td data-label="Tgl Batal" style="color: var(--danger);"><?= $c['return_date'] ?></td>
                        <td data-label="Status"><span class="badge-status cancelled-badge">Cancelled</span></td>
                        <td data-label="Invoice"><span class="invoice-box"><?= $c['invoice_code'] ?></span></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

</div>

</body>
</html>