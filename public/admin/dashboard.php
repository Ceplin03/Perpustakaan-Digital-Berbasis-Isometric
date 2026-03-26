<?php
require_once '../../app/core/Auth.php';
require_once '../../app/core/DB.php';

if (!Auth::check()) {
    header('Location: /perpustakaan/public/login.php');
    exit;
}

$user = Auth::user();
$isAdmin = isset($user['role']) && strtolower(trim($user['role'])) === 'admin';

if (!$isAdmin) {
    http_response_code(403);
    die('ACCESS DENIED');
}

$db = DB::connect();

/* ================= QUICK STATS ================= */
$stats = [
    'books' => $db->query("SELECT COUNT(*) FROM books")->fetchColumn(),
    'members' => $db->query("SELECT COUNT(*) FROM users WHERE role='siswa'")->fetchColumn(),
    'active_borrows' => $db->query("SELECT COUNT(*) FROM borrows WHERE status='reserved'")->fetchColumn(),
    'late' => $db->query("SELECT COUNT(*) FROM borrows WHERE late_days > 0 AND status='reserved'")->fetchColumn()
];

/* ================= ACTIVITY FEED ================= */
$activities = $db->query("
    SELECT u.nama, bk.judul, b.status, b.borrow_date
    FROM borrows b
    JOIN users u ON u.id=b.user_id
    JOIN books bk ON bk.id=b.book_id
    ORDER BY b.borrow_date DESC
    LIMIT 6
")->fetchAll();

/* ================= MINI LEADERBOARD ================= */
$leaders = $db->query("
    SELECT u.nama, COUNT(b.id) total
    FROM borrows b
    JOIN users u ON u.id=b.user_id
    GROUP BY b.user_id
    ORDER BY total DESC
    LIMIT 3
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Dashboard | Smart Library</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&family=JetBrains+Mono&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>

    <style>
        :root {
            --bg-main: #020617;
            --bg-side: #0f172a;
            --card-bg: rgba(30, 41, 59, 0.5);
            --border: rgba(255, 255, 255, 0.08);
            --accent-primary: #3b82f6;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --sidebar-width: 280px;
        }

        * { box-sizing: border-box; transition: transform 0.2s ease, opacity 0.2s ease, background 0.2s ease; }

        body {
            margin: 0;
            background: var(--bg-main);
            color: var(--text-main);
            font-family: 'Plus Jakarta Sans', sans-serif;
            display: flex;
            min-height: 100vh;
        }

        /* --- SIDEBAR & MOBILE NAVIGATION --- */
        aside {
            width: var(--sidebar-width);
            background: var(--bg-side);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: transform 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .side-logo {
            padding: 32px;
            font-weight: 800;
            font-size: 1.2rem;
            background: linear-gradient(to right, #60a5fa, #3b82f6);
            -webkit-background-clip: text;
            background-clip: text;
            -webkit-text-fill-color: transparent;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav { padding: 0 16px; flex: 1; }
        
        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 16px; color: var(--text-muted);
            text-decoration: none; border-radius: 12px;
            margin-bottom: 4px; font-weight: 500; cursor: pointer;
        }

        .nav-item:hover, .nav-item.active {
            background: rgba(59, 130, 246, 0.15);
            color: var(--accent-primary);
        }

        .mobile-header {
            display: none;
            position: fixed; top: 0; left: 0; width: 100%;
            padding: 15px 20px; background: var(--bg-side);
            border-bottom: 1px solid var(--border);
            z-index: 900; align-items: center; justify-content: space-between;
        }

        .menu-toggle {
            background: transparent; border: none; color: white; cursor: pointer;
        }

        /* Backdrop overlay when sidebar is open on mobile */
        .sidebar-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.7);
            z-index: 999; display: none; backdrop-filter: blur(4px);
        }

        /* --- CONTENT AREA --- */
        main {
            margin-left: var(--sidebar-width);
            flex: 1; padding: 40px 5%;
        }

        .header-top { display: flex; justify-content: space-between; align-items: center; margin-bottom: 40px; }
        .header-top h1 { font-size: 1.8rem; margin: 0; }

        .stats-grid {
            display: grid; grid-template-columns: repeat(4, 1fr); gap: 24px; margin-bottom: 40px;
        }

        .stat-card {
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 20px; padding: 24px;
        }

        .stat-card h2 { font-size: 2rem; margin: 8px 0 0; font-family: 'JetBrains Mono', monospace; }

        .content-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 24px; }

        .panel {
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 24px; padding: 28px; backdrop-filter: blur(10px);
        }

        .activity-row {
            display: flex; align-items: center; padding: 14px 0;
            border-bottom: 1px solid var(--border); gap: 16px;
        }

        /* --- RESPONSIVE LOGIC --- */
        @media (max-width: 1024px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .content-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 768px) {
            aside { transform: translateX(-100%); }
            aside.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            .mobile-header { display: flex; }
            main { margin-left: 0; padding-top: 100px; }
            .stats-grid { grid-template-columns: 1fr; gap: 16px; }
            .header-top { flex-direction: column; align-items: flex-start; gap: 15px; }
            .header-top h1 { font-size: 1.5rem; }
        }
    </style>
</head>

<body>

    <div class="sidebar-overlay" id="overlay" onclick="toggleMenu()"></div>

    <div class="mobile-header">
        <div style="font-weight: 800; font-size: 1rem; color: var(--accent-primary);">SMARTLIB</div>
        <button class="menu-toggle" onclick="toggleMenu()">
            <i data-lucide="menu"></i>
        </button>
    </div>

    <aside id="sidebar">
        <div class="side-logo">
            <span>SMART | LIBRARY</span>
            <button class="menu-toggle" style="display: none;" id="close-btn" onclick="toggleMenu()">
                <i data-lucide="x"></i>
            </button>
        </div>
        
        <nav>
            <div class="nav-item active"><i data-lucide="layout-dashboard" size="18"></i> Dashboard</div>
            <div class="nav-item" onclick="go('books.php')"><i data-lucide="book-open" size="18"></i> Koleksi Buku</div>
            <div class="nav-item" onclick="go('members.php')"><i data-lucide="users" size="18"></i> Data Anggota</div>
            <div class="nav-item" onclick="go('borrows.php')"><i data-lucide="repeat" size="18"></i> Peminjaman</div>
            <div class="nav-item" onclick="go('invoices.php')"><i data-lucide="file-text" size="18"></i> Imvoice & Riwayat</div>
        </nav>

        <div style="padding: 24px; border-top: 1px solid var(--border);">
            <a href="/perpustakaan/public/library-room/index.php" style="color: #f87171; text-decoration: none; font-size: 0.85rem; font-weight: 600; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="log-out" size="18"></i> Portal Utama
            </a>
        </div>
    </aside>

    <main>
        <div class="header-top">
            <div>
                <h1>Overview Sistem</h1>
                <p style="color: var(--text-muted); margin: 4px 0 0;">Petugas: <?= htmlspecialchars($user['nama']) ?></p>
            </div>
            <div style="background: var(--card-bg); padding: 8px 16px; border-radius: 100px; border: 1px solid var(--border); font-size: 0.85rem;">
                <i data-lucide="calendar" size="14" style="vertical-align: middle; margin-right: 6px;"></i>
                <?= date('d M Y') ?>
            </div>
        </div>

        <div class="stats-grid">
            <div class="stat-card"><span>Total Koleksi</span><h2><?= $stats['books'] ?></h2></div>
            <div class="stat-card"><span>Anggota</span><h2><?= $stats['members'] ?></h2></div>
            <div class="stat-card" style="border-left: 3px solid var(--accent-primary);"><span>Peminjaman</span><h2><?= $stats['active_borrows'] ?></h2></div>
            <div class="stat-card"><span>Terlambat</span><h2 style="color: #f87171;"><?= $stats['late'] ?></h2></div>
        </div>

        <div class="content-grid">
            <div class="panel">
                <div style="display: flex; justify-content: space-between; margin-bottom: 25px;">
                    <h3 style="margin:0">Log Aktivitas</h3>
                    <i data-lucide="more-horizontal" style="color: var(--text-muted);"></i>
                </div>
                <?php foreach($activities as $a): ?>
                <div class="activity-row">
                    <div style="width: 36px; height: 36px; background: #334155; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 800;">
                        <?= substr($a['nama'], 0, 1) ?>
                    </div>
                    <div style="flex:1">
                        <p style="margin:0; font-size: 0.9rem;"><b><?= htmlspecialchars($a['nama']) ?></b> meminjam <?= htmlspecialchars($a['judul']) ?></p>
                        <span style="font-size: 0.75rem; color: var(--text-muted);"><?= date('H:i', strtotime($a['borrow_date'])) ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="panel" style="background: linear-gradient(135deg, rgba(30,41,59,0.9), rgba(15,23,42,0.9)); border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 10px 30px rgba(0,0,0,0.5);">
    <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 25px;">
        <h3 style="font-family: var(--font-mono); font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px; color: #fff;">
            <span style="margin-right: 10px;">🏆</span>Top Readers
        </h3>
        <span style="font-size: 0.6rem; background: var(--primary); padding: 4px 10px; border-radius: 20px; text-transform: uppercase; font-weight: 800;">Bulan Ini</span>
    </div>

    <?php foreach($leaders as $i=>$l): 
        // Logika Badge & Warna
        $rank = $i + 1;
        $badgeColor = '#888888'; // Default
        $bgEffect = 'rgba(255,255,255,0.05)';
        $crown = '';

        if($rank === 1) {
            $badgeColor = '#FFD700'; // Gold
            $bgEffect = 'linear-gradient(90deg, rgba(255,215,0,0.1) 0%, rgba(0,0,0,0) 100%)';
            $crown = '👑';
        } elseif($rank === 2) {
            $badgeColor = '#C0C0C0'; // Silver
        } elseif($rank === 3) {
            $badgeColor = '#CD7F32'; // Bronze
        }
    ?>
    
    <div style="display: flex; align-items: center; padding: 15px; background: <?= $bgEffect ?>; border: 1px solid rgba(255,255,255,0.05); border-radius: 16px; margin-bottom: 12px; transition: transform 0.3s ease;">
        
        <div style="width: 35px; height: 35px; background: <?= $badgeColor ?>; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-weight: 900; color: #000; margin-right: 15px; box-shadow: 0 4px 10px <?= $badgeColor ?>44; position: relative;">
            <span style="font-size: 0.9rem;"><?= $rank ?></span>
        </div>

        <div style="flex: 1;">
            <div style="display: flex; align-items: center; gap: 5px;">
                <span style="font-size: 0.9rem; font-weight: 600; color: #eee;">
                    <?= htmlspecialchars($l['nama']) ?>
                </span>
                <span style="font-size: 0.8rem;"><?= $crown ?></span>
            </div>
            <div style="font-size: 0.65rem; color: var(--text-muted); font-family: var(--font-mono); margin-top: 2px;">
                Telah membaca <?= $l['total'] ?> buku
            </div>
        </div>

        <div style="text-align: right;">
            <div style="font-size: 1.1rem; font-weight: 800; color: <?= $badgeColor ?>; line-height: 1;">
                <?= $l['total'] ?>
            </div>
            <div style="font-size: 0.55rem; text-transform: uppercase; color: var(--text-muted); margin-top: 4px;">Poin</div>
        </div>
    </div>
    <?php endforeach; ?>

    <div style="margin-top: 20px; text-align: center;">
        <a href="#" style="font-size: 0.7rem; color: var(--primary); text-decoration: none; font-family: var(--font-mono); text-transform: uppercase; letter-spacing: 1px;">Lihat Leaderboard Lengkap →</a>
    </div>
</div>
        </div>
    </main>

    <script>
        lucide.createIcons();

        function toggleMenu() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('open');
        }

        function go(url) {
            document.body.style.opacity = '0.5';
            setTimeout(() => location.href = url, 150);
        }
    </script>
</body>
</html>