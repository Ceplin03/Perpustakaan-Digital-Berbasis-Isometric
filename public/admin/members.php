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
$stmt = $db->query("
    SELECT id, nama, email, role, created_at
    FROM users
    ORDER BY created_at DESC
");
$members = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Manajemen Anggota | Smart Library</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Mono&display=swap" rel="stylesheet">

<style>
:root{
  --bg:#020617;
  --card:#020617;
  --border:#1e293b;
  --text:#e5e7eb;
  --muted:#94a3b8;
  --primary:#3b82f6;
}

*{box-sizing:border-box}

body{
  margin:0;
  background:var(--bg);
  color:var(--text);
  font-family:Inter,sans-serif;
  overflow-x: hidden;
}

/* HEADER */
header{
  padding:28px 40px;
  display:flex;
  justify-content:space-between;
  align-items:center;
  border-bottom:1px solid var(--border);
}

header h1{
  margin:0;
  font-size:1.4rem;
  letter-spacing:-0.02em;
}

header a{
  font-family:'Space Mono',monospace;
  color:#ef4444;
  text-decoration:none;
  font-size:.75rem;
}

/* CONTAINER */
.container{
  padding:32px 40px;
}

/* TABLE CARD */
.card{
  background:var(--card);
  border:1px solid var(--border);
  border-radius:16px;
  overflow:hidden;
}

/* TABLE */
table{
  width:100%;
  border-collapse:collapse;
}

thead{
  background:#020617;
}

th, td{
  padding:16px 18px;
  font-size:.85rem;
  border-bottom:1px solid var(--border);
}

th{
  text-align:left;
  color:var(--muted);
  font-weight:600;
}

tbody tr:hover{
  background:rgba(59,130,246,0.05);
}

/* ROLE BADGE */
.badge{
  display:inline-block;
  padding:4px 10px;
  border-radius:999px;
  font-size:.7rem;
  font-weight:600;
  letter-spacing:.04em;
}

.badge-admin{
  background:rgba(34,197,94,.15);
  color:#22c55e;
}

.badge-siswa{
  background:rgba(251,191,36,.15);
  color:#fbbf24;
}

/* EMPTY STATE */
.empty{
  padding:40px;
  text-align:center;
  color:var(--muted);
}

/* ==========================================================
   RESPONSIVE MODE (STACKING TECHNIQUE)
   ========================================================== */
@media (max-width: 768px) {
    header {
        padding: 20px;
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }

    header h1 { font-size: 1.2rem; }

    .container {
        padding: 20px 15px;
    }

    /* Sembunyikan Header Tabel di Mobile */
    thead {
        display: none;
    }

    /* Ubah Tabel menjadi baris-baris kartu */
    tr {
        display: block;
        border-bottom: 8px solid var(--border); /* Pemisah antar user */
        padding: 15px 0;
    }

    tr:last-child { border-bottom: none; }

    td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 15px;
        border: none;
        font-size: 0.8rem;
        text-align: right;
    }

    /* Menambahkan label di sebelah kiri data menggunakan pseudo-element */
    td::before {
        content: attr(data-label);
        font-weight: 600;
        color: var(--muted);
        text-transform: uppercase;
        font-size: 0.65rem;
        text-align: left;
    }

    /* Penyesuaian khusus untuk Badge */
    td .badge {
        margin-left: auto;
    }
}
</style>
</head>

<body>

<header>
  <h1>👥 Manajemen Anggota</h1>
  <a href="/perpustakaan/public/admin/dashboard.php">← Kembali ke Dashboard</a>
</header>

<div class="container">
  <div class="card">

    <?php if (count($members) === 0): ?>
      <div class="empty">
        Belum ada data anggota.
      </div>
    <?php else: ?>

    <table>
      <thead>
        <tr>
          <th>ID / NIS</th>
          <th>Nama</th>
          <th>Email</th>
          <th>Role</th>
          <th>Dibuat</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($members as $m): ?>
        <tr>
          <td data-label="ID / NIS"><?= htmlspecialchars($m['id']) ?></td>
          <td data-label="Nama"><?= htmlspecialchars($m['nama']) ?></td>
          <td data-label="Email"><?= htmlspecialchars($m['email']) ?></td>
          <td data-label="Role">
            <span class="badge badge-<?= $m['role'] === 'admin' ? 'admin' : 'siswa' ?>">
              <?= strtoupper($m['role']) ?>
            </span>
          </td>
          <td data-label="Dibuat">
            <?= date('d M Y H:i', strtotime($m['created_at'])) ?>
          </td>
        </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <?php endif; ?>

  </div>
</div>

</body>
</html>