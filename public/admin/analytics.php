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
    die('ACCESS DENIED');
}

$db = DB::connect();

/* =========================
   ANALYTICS: BUKU TERPOPULER
========================= */
$popularBooks = $db->query("
    SELECT 
        bk.judul,
        COUNT(b.id) AS total
    FROM borrows b
    JOIN books bk ON bk.id = b.book_id
    GROUP BY b.book_id
    ORDER BY total DESC
    LIMIT 10
")->fetchAll();

/* =========================
   ANALYTICS: SISWA TERAKTIF BULAN INI
========================= */
$activeMembers = $db->query("
    SELECT 
        u.id AS nis,
        u.nama,
        COUNT(b.id) AS total
    FROM borrows b
    JOIN users u ON u.id = b.user_id
    WHERE MONTH(b.borrow_date) = MONTH(CURRENT_DATE())
      AND YEAR(b.borrow_date) = YEAR(CURRENT_DATE())
    GROUP BY b.user_id
    ORDER BY total DESC
    LIMIT 10
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Analytics | Smart Library</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Space+Mono&display=swap" rel="stylesheet">

<style>
:root{
  --bg:#020617;
  --card:#020617;
  --border:#1e293b;
  --text:#e5e7eb;
  --muted:#94a3b8;
  --gold:#facc15;
  --silver:#cbd5e1;
  --bronze:#f97316;
  --blue:#3b82f6;
}

body{
  margin:0;
  background:radial-gradient(circle at top,#020617,#000);
  color:var(--text);
  font-family:Inter,sans-serif;
}

header{
  padding:28px 40px;
  display:flex;
  justify-content:space-between;
  border-bottom:1px solid var(--border);
}

header a{
  color:#ef4444;
  text-decoration:none;
  font-family:'Space Mono',monospace;
  font-size:.75rem;
}

.container{
  padding:40px;
  display:grid;
  grid-template-columns:1fr 1fr;
  gap:32px;
}

.card{
  border:1px solid var(--border);
  border-radius:20px;
  padding:24px;
}

.card h2{
  margin-bottom:20px;
  font-size:1.1rem;
}

/* LEADERBOARD */
.leaderboard{
  display:flex;
  flex-direction:column;
  gap:12px;
}

.item{
  display:flex;
  align-items:center;
  gap:16px;
  padding:12px 16px;
  border-radius:14px;
  background:rgba(255,255,255,0.02);
  border:1px solid var(--border);
}

.rank{
  width:36px;
  height:36px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  font-weight:700;
}

.rank.gold{background:rgba(250,204,21,.2);color:var(--gold)}
.rank.silver{background:rgba(203,213,225,.2);color:var(--silver)}
.rank.bronze{background:rgba(249,115,22,.2);color:var(--bronze)}
.rank.normal{background:#020617;color:var(--muted)}

.info{
  flex:1;
}

.info strong{
  font-size:.9rem;
}

.info span{
  font-size:.7rem;
  color:var(--muted);
}

.score{
  font-family:'Space Mono',monospace;
  font-size:.75rem;
  color:var(--blue);
}

/* RESPONSIVE */
@media(max-width:900px){
  .container{
    grid-template-columns:1fr;
  }
}
</style>
</head>

<body>

<header>
  <h1>📊 Analytics & Leaderboard</h1>
  <a href="/perpustakaan/public/admin/dashboard.php">← Kembali ke Dashboard</a>
</header>

<div class="container">

<!-- ================= BUKU TERPOPULER ================= -->
<div class="card">
<h2>📚 Buku Terpopuler</h2>
<div class="leaderboard">
<?php foreach ($popularBooks as $i => $b):
  $rankClass = $i==0?'gold':($i==1?'silver':($i==2?'bronze':'normal'));
?>
<div class="item">
  <div class="rank <?= $rankClass ?>">#<?= $i+1 ?></div>
  <div class="info">
    <strong><?= htmlspecialchars($b['judul']) ?></strong><br>
    <span>Total dipinjam</span>
  </div>
  <div class="score"><?= $b['total'] ?>x</div>
</div>
<?php endforeach; ?>
</div>
</div>

<!-- ================= SISWA TERAKTIF ================= -->
<div class="card">
<h2>👥 Siswa Teraktif (Bulan Ini)</h2>
<div class="leaderboard">
<?php foreach ($activeMembers as $i => $m):
  $rankClass = $i==0?'gold':($i==1?'silver':($i==2?'bronze':'normal'));
?>
<div class="item">
  <div class="rank <?= $rankClass ?>">#<?= $i+1 ?></div>
  <div class="info">
    <strong><?= htmlspecialchars($m['nama']) ?></strong><br>
    <span>NIS <?= $m['nis'] ?></span>
  </div>
  <div class="score"><?= $m['total'] ?> buku</div>
</div>
<?php endforeach; ?>
</div>
</div>

</div>

</body>
</html>
